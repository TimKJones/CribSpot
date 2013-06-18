<?php
class OrderController extends AppController {
  public $helpers = array('Html');
  public $components = array('Auth');
  public $uses = array('Listing', 'User', 'FeaturedListing', 'Order', 'PendingOrder', 'ShoppingCart');
  public $TAG = "OrdersController";

    private $WalletSellerID = "10354430150694430158";
    private $WalletSecretKey = "XWLZaH-bSdUGUJxlSZZVSg";
    private $rules = array('FeaturedListings'=>array(
                'costs'=>array( 
                    'weekday'=>15.00,
                    'weekend'=>5.00,
                )
            )
        );

    public function myOrders(){

        $user_id = $this->Auth->User('id');
        $orders = $this->Order->find('all', array('conditions'=>"Order.user_id=$user_id"));
        $this->set("orders", $orders);
    }

    public function singleFeaturedListing($listing_id){

        $listing = $this->Listing->get($listing_id);
        if($listing == null){
            throw new NotFoundException();
        }  


        
        $this->set("rules_json", json_encode($this->rules));
        $this->set("rules", $this->rules);
        $this->set("listing", $listing);
        // commented out is code that checks to see if the user owns the listing

        // if($this->Auth->User('id') != $listing['User']['id']){
        //     throw new NotFoundException();   
        // }

    }

    

    //Accepts an order item and creates a jwt to buy it, used to one click buy
    // a featured listing for example
    public function buyItem(){
        $order_item = $this->request->data('orderItem');
        $response = $this->getJwt(array($order_item));
        $this->layout = 'ajax';
        $this->set('response', json_encode($response)); 
    }

    //Returns a jwt for the users entire shopping cart.
    public function buyCart(){
        $cart = $this->ShoppingCart->get($this->Auth->User('id'));
        $orderItems = json_decode($cart['ShoppingCart']['items']);
        $response = $this->getJwt($orderItems);
        $this->layout = 'ajax';
        $this->set('response', json_encode($response));
    }

    // Takes an array of order items and returns a google wallet jwt
    // also creates a pending order to be used when the purchased goes through.
    // JWT spec found here https://developers.google.com/commerce/wallet/digital/docs/jsreference#jwt
    private function getJwt($orderItems){
        $response = array();
       


        $request = null;
        $total = 0;
        $weekends = 0;
        $weekdays = 0;
        try{
            foreach($orderItems as &$orderItem){
                switch($orderItem->type){
                    case "FeaturedListing":
                        
                        $pricing_info = $this->getFeaturedListingPrice($orderItem->item);
                        $total += $pricing_info['Price'];
                        $orderItem->price = $pricing_info['Price'];
                        $weekends += $pricing_info['Weekends'];
                        $weekdays += $pricing_info['Weekdays'];
                        break;
                    default:
                        throw new Exception("Type didn't match any valid type");
                }
            }        
            $wd_price = $this->rules['FeaturedListings']['costs']['weekday'];
            $we_price = $this->rules['FeaturedListings']['costs']['weekend'];  

            $name = "Featured Listing on Cribspot.com";
            $description = "Weekdays: $weekdays x $".$wd_price."/day + Weekends: $weekends x $".$we_price."/day";

            $user_id = $this->Auth->User('id');
            $order = array(
                'total'=>$total,
                'user_id'=>$user_id,
                'orderItems'=>$orderItems,
                );

            $pendingOrder = $this->PendingOrder->add($order, $user_id);
            
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
            
            $response['success'] = true;
        }catch(Exception $e){
            $response['success']= false;
            $response['message'] = $e->getMessage();
        }
        

       

        // We have a valid order request
        if($response['success']){
            //Encode the jwt


            $payload = array(
                "iss" => $this->WalletSellerID,
                "aud" => "Google",
                "typ" => "google/payments/inapp/item/v1",
                "exp" => (time() + 3600) * 1000,
                "iat" => time() * 1000,
                "request" => $request,
                "response"=> array("orderId"=>"69")
            );


            App::uses('JWT', 'JWT');
            $response['jwt_plain'] = $payload;
            $response['jwt'] = JWT::encode($payload, $this->WalletSecretKey);
        }  

        return $response;

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

        $request = $jwt->request;
        $order_num = $jwt->response->orderId;

        $user_id = $this->Auth->User('id');
        
        $order = $this->Order->logOrder($request, $order_num, $user_id);

        if($order == null){
            //Something went wrong with the order
            die();
        }

        $this->layout = 'ajax';
        $this->set('orderId', json_encode(array('orderId'=>$order_num)));

    }


    private function getFeaturedListingPrice($featuredListing){
        App::import('Vendor', 'Utilities/DateHelpers');

        $price = 0;
        $weekdays = 0;
        $weekends = 0;
        $days = 0;

        $wd_price = $this->rules['FeaturedListings']['costs']['weekday'];
        $we_price = $this->rules['FeaturedListings']['costs']['weekend'];  

        // Make sure listing exists

        $listing = $this->Listing->find('first', array('conditions'=>'Listing.listing_id='.$featuredListing->listing_id));
            // die(debug($listing));
            if($listing == null){
                // Listing not found return null;
                CakeLog::write($TAG, "Listing " . $featuredlisting->listing_id . " not found while trying to buy a featured listing");
                return null;
            }


        foreach($featuredListing->dates as $date){
                $day = date("N", strtotime($date));

                if($day > 5){
                    //Sat or Sun
                    $weekends++;
                }else{
                    $weekdays++;
                }
                $days++;
            }

        $price += $wd_price * $weekdays + $we_price * $weekends;

        return array(
            "Price"=>$price,
            "Weekdays"=>$weekdays,
            "Weekends"=>$weekends,
            );
    }






}

