<?php
class OrderController extends AppController {
  public $helpers = array('Html');
  public $components = array('Auth');
  public $uses = array('Listing', 'User', 'FeaturedListing', 'Order', 'PendingOrder', 'NewspaperAdmin');
  public $TAG = "OrdersController";

    

    public function myOrders(){

        $user_id = $this->Auth->User('id');
        $orders = $this->Order->find('all', array('conditions'=>"Order.user_id=$user_id"));
        $this->set("orders", $orders);
    }

    // [TODO] move this function FeaturedListingController
    public function singleFeaturedListing($listing_id){

        $listing = $this->Listing->get($listing_id);
        if($listing == null){
            throw new NotFoundException();
        }  
        $listing_id = $listing['Listing']['listing_id'];
        $this->set('address', $listing['Marker']['street_address']);
        $this->set('listing_id', $listing_id);

        $this->set('wd_price', $this->Order->FLWeekdayCost);
        $this->set('we_price', $this->Order->FLWeekendCost);

        // $featured_dates = $this->FeaturedListing->getDates($listing_id);
        // $this->set("disabled_dates", json_encode($featured_dates));

    }

    /*
        This is a super user feature listing function
        it accepts an orderItem pertaining to a featured listing
        The order will be validated and immediately filled. Without
        payment. This is used by our super user accounts such as the 
        Michigan Daily.

        Function will return a JSON object containing the following 
        fields 
        {
            success: (TRUE or FALSE)
            msg: (Success message or reason for failure)
        }
    */
    public function suFeatureListing(){
        
        $orderItem = json_decode($this->request->data('orderItem'));
        if($orderItem == null){
            throw new NotFoundException();
        }

        //We need to make sure the user has Super User rights
        $user = $this->User->get($this->Auth->User('id'));

        // if($user['User']['type'] != SUPERUSER){
        //     throw new NotFoundException();
        // }
        
        $user_id = $user['User']['id'];
        $response = array();
        try {
            $this->Order->validateFeaturedListing($orderItem);
            $listing_id = $orderItem->item->listing_id;
            foreach ($orderItem->item->dates as $key => $date) {
                $this->FeaturedListing->add($listing_id, $date, $user_id);    
            }

            $addr = $orderItem->item->street_address;
            $num_dates = count($orderItem->item->dates);
            $response['msg'] = "$addr successfully featured for $num_dates days.";
            $response['success'] = true;

        } catch (Exception $e) {
            $response['success'] = false;
            $response['msg'] = $e->getMessage();
        }

        $this->layout = 'ajax';
        $this->set('response', json_encode($response)); 

    }

    /*  Accepts an array of orderItems and creates a jwt to buy it
        
        The response object returned has a success flag and if its true then
        there is a valid 'jwt' property in the response object otherwise there
        is an array of validation errors passed back
    */

    public function buy(){
        ClassRegistry::init('Order'); //For some reason I can't access the static properties of the
                                      // of the order class

        $orderItems_json = $this->request->data('orderItems');
        $order_type_str = $this->request->data('order_type');
        
        if($orderItems_json == null or $order_type_str == null){
            throw new NotFoundException();
        }

        $order_type = intval($order_type_str);

        $orderItems = json_decode($orderItems_json);
        $user_id =$this->_getUserId();
        $newspaper_admin = $this->NewspaperAdmin->getByUserId($user_id);
        $SU = $newspaper_admin != null;
        switch($order_type){
            case Order::ORDER_TYPE_FEATURED_LISTING:
                $validationErrors = $this->Order->validateFLOrder($orderItems, $user_id, $SU);
                break;

            // case Order::ORDER_TYPE_PARKING:

            //     break;
            
            default:
                throw new Exception("Order Type was not valid");
        }
        
        /* Default response if doesn't get set anywhere */
        $response = array(
            'success' => false
        );

        if($validationErrors){
            $response = array(
                'success'=>false,
                'errors'=>$validationErrors
                );
        }else{
            //Auto fulfill order for SU's
            if($SU){
                $university_id = $newspaper_admin['NewspaperAdmin']['university_id'];
                foreach ($orderItems as $orderItem) {
                    $listing_id = $orderItem->listing_id;
                    foreach ($orderItem->dates as $key => $date) {
                        $this->FeaturedListing->add($listing_id, $university_id, $date, $user_id);    
                    }

                    $num_dates = count($orderItem->dates);
                    $response['msg'] = "Successfully featured for $num_dates days.";
                    $response['success'] = true;
                }
            }else{
                $jwt_clear = $this->Order->generateJWT($orderItems, $user_id, $order_type);
                App::uses('JWT', 'JWT');
                $response = array(
                'success'=>true,
                'jwt_clear'=>$jwt_clear,
                'jwt'=>JWT::encode($jwt_clear, Order::WalletSecretKey)
                );    
            }
        }
        $this->layout = 'ajax';
        $this->set('response', json_encode($response)); 
    }

    /*
        From Google Wallet Doc's

        Your server must send a 200 OK response for each HTTP POST message that 
        Google sends to your postback URL. To send this response, your server must:

        1. Decode the JWT that's specified in the jwt parameter of the POST message.
        2. Check to make sure that the order is OK.
        3. Get the value of the JWT's "orderId" field.
        4. Send a 200 OK response that has only one thing in the body: the 
           "orderId" value you got in step 3.
        
        https://developers.google.com/commerce/wallet/digital/docs/postback
    */
    public function postBackHandler(){

        $jwt_encry = $this->request->data['jwt'];
        
        if($jwt_encry == null){
            throw new NotFoundException();
        }
        ClassRegistry::init('Order');
        App::uses('JWT', 'JWT');

        // This should be not false, we want to verify however during testing,
        // shit comes up with it .
        
        $jwt = JWT::decode($jwt_encry, Order::WalletSecretKey, false);

        $user_id = $this->Auth->User('id');
        
        $order = $this->Order->logOrder($jwt->request, $jwt->response->orderId, $user_id);

        $this->layout = 'ajax';
        $this->set('orderId', json_encode(array('orderId'=>$jwt->response->orderId)));

    }

}

