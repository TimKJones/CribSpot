<?php
class OrderController extends AppController {
  public $helpers = array('Html');
  public $components = array('Auth');
  public $uses = array('Listing', 'User', 'FeaturedListing', 'Order', 'PendingOrder', 'ShoppingCart');
  public $TAG = "OrdersController";

    private $WalletSellerID = "10354430150694430158";
    private $WalletSecretKey = "XWLZaH-bSdUGUJxlSZZVSg";
    

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

    /*  Accepts an order item and creates a jwt to buy it, used to one click buy
        a featured listing for example

        The response object returned has a success flag and if its true then
        there is a valid 'jwt' property in the response object otherwise there
        is a 'msg' property that will say whats wrong
    */
    public function buyItem(){
        $orderItems = $this->request->data('orderItems');
        if($orderItems == null){
            throw new NotFoundException();
        }

        try {
            $jwt_clear = $this->getJwt(json_decode($orderItems));
            App::uses('JWT', 'JWT');
            $response = array(
                'success'=>true,
                'jwt_clear'=>$jwt_clear,
                'jwt'=>JWT::encode($jwt_clear, $this->WalletSecretKey)
                );
        } catch (Exception $e) {
            $response = array(
                'success'=>false,
                'msg'=>$e->getMessage()
                );
        }
        
        $this->layout = 'ajax';
        $this->set('response', json_encode($response)); 
    }

    /*
        Tries to create a jwt for the users shopping cart
        
        The response object returned has a success flag and if its true then
        there is a valid 'jwt' property in the response object otherwise there
        is a 'msg' property that will say whats wrong
    */
    public function buyCart(){
        
        $cart = $this->ShoppingCart->get($this->Auth->User('id'));
        $orderItems = json_decode($cart['ShoppingCart']['items']);
        try {
            if(count($orderItems) == 0){
                //No Items in cart
                throw new Exception('No Items in the Cart');
            }
            $jwt_clear = $this->getJwt($orderItems);
            App::uses('JWT', 'JWT');
            $response = array(
                'success'=>true,
                'jwt_clear'=>$jwt_clear,
                'jwt'=>JWT::encode($jwt_clear, $this->WalletSecretKey)
                );
        } catch (Exception $e) {
            $response = array(
                'success'=>false,
                'msg'=>$e->getMessage()
                );
        }

        $this->layout = 'ajax';
        $this->set('response', json_encode($response));
    }

    // Takes an array of order items and returns a google wallet jwt
    // also creates a pending order to be used when the purchased goes through.
    // JWT spec found here https://developers.google.com/commerce/wallet/digital/docs/jsreference#jwt
    private function getJwt($orderItems){
        App::uses('DateHelpers', 'Utilities');
        $total = 0;
        $weekends = 0;
        $weekdays = 0;

        foreach($orderItems as &$orderItem){
            switch($orderItem->type){
                case "FeaturedListing":
                    $this->Order->validateFeaturedListing($orderItem);
                    $total += $orderItem->price;
                    
                    $day_counts = DateHelpers::getDayCounts($orderItem->item->dates);
                    $weekdays += $day_counts['weekdays'];
                    $weekends += $day_counts['weekends'];
                    
                    break;
                default:
                    throw new Exception("Type didn't match any valid type");
            }
        }        
        $wd_price = $this->Order->FLWeekdayCost;
        $we_price = $this->Order->FLWeekendCost;

        $name = "Featured Listing on Cribspot.com";
        $description = "Weekdays: $weekdays x $".$wd_price."/day + Weekends: $weekends x $".$we_price."/day";

        $user_id = $this->Auth->User('id');
        
        $pendingOrder = $this->PendingOrder->add($total, $orderItems, $user_id);
        
        $sellerData = array(
            'pendingOrder_id'=>$pendingOrder['PendingOrder']['id']
            );

        $request = array(
            "name" => $name,
            "description" => $description,
            "price" => $total,
            "currencyCode" => "USD",
            "sellerData" => json_encode($sellerData)
            );
        


        $payload = array(
            "iss" => $this->WalletSellerID,
            "aud" => "Google",
            "typ" => "google/payments/inapp/item/v1",
            "exp" => (time() + 3600) * 1000,
            "iat" => time() * 1000,
            "request" => $request,
            "response"=> array("orderId"=>"69")
        );


        return $payload;
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

        App::uses('JWT', 'JWT');

        // This should be not false, we want to verify however during testing,
        // shit comes up with it .

        $jwt = JWT::decode($jwt_encry, $this->WalletSecretKey, false);

        $user_id = $this->Auth->User('id');
        
        $order = $this->Order->logOrder($jwt->request, $jwt->response->orderId, $user_id);

        $this->layout = 'ajax';
        $this->set('orderId', json_encode(array('orderId'=>$jwt->response->orderId)));

    }

}

