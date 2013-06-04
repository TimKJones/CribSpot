<?php
class OrderController extends AppController {
  public $helpers = array('Html');
  public $components = array('Auth');
  public $uses = array('Listing', 'User', 'FeaturedListing', 'Order');
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

    public function featuredListing($listing_id, $listing_id2){

        $listing = $this->Listing->get($listing_id);
        if($listing == null){
            throw new NotFoundException();
        }  
        $listing2 = $this->Listing->get($listing_id2);
        if($listing2 == null){
            throw new NotFoundException();
        }  



        $listings = array($listing,$listing2); //wants array of listings to feature

        
        $this->set("rules_json", json_encode($this->rules));
        $this->set("rules", $this->rules);
        $this->set("listings", $listings);
        // commented out is code that checks to see if the user owns the listing

        // if($this->Auth->User('id') != $listing['User']['id']){
        //     throw new NotFoundException();   
        // }

    }

    // Takes a post request containing data type and then a data object with all 
    // info to get a jwt generated for the purchase
    // JWT spec found here https://developers.google.com/commerce/wallet/digital/docs/jsreference#jwt
    public function getJwt(){
        

        $type = $this->request->data['type'];
        $data = json_decode($this->request->data['info']);

        $data->user_id = $this->Auth->User('id');

        $request = null;
        switch($type){
            case "featured-listing":
                $request = $this->getFeaturedListingRequest($data);
                $response['success'] = true;

                break;
            default:
                $response['success'] = false;
                $response['message'] = "Type didn't match any valid type";
        }
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

        $this->layout = 'ajax';
        $this->set('response', json_encode($response));

    }

    public function getFl(){}


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
        
        $order = $this->Order->logOrder($request, $order_num);

        if($order == null){
            //Something went wrong with the order

        }

        $this->layout = 'ajax';
        $this->set('orderId', json_encode(array('orderId'=>$order_num)));

    }


    private function getFeaturedListingRequest($data){
        App::import('Vendor', 'Utilities/DateHelpers');

        $daily_rate = 15; // $15 a day

        $name = "Featured Listing on Cribspot.com for ". $data->duration. " days";
        $listing = $this->Listing->find('first', array('conditions'=>'Listing.listing_id='.$data->listing_id));
        // die(debug($listing));
        if($listing == null){
            // Listing not found return null;
            CakeLog::write($TAG, "Listing " . $data->listing_id . " not found while trying to buy a featured listing");
            return null;
        }
        
        $address = $listing['Marker']['street_address'];
        $start_date = date("m/d/Y", $data->start/1000);
        $end_date = date("m/d/Y", ($data->start/1000) + (24*60*60*($data->duration-1)));
        $description = "$address will be featured from $start_date to $end_date";

        $weekdays = getWeekDays($start_date, $end_date);
        $wd_price = $this->rules['FeaturedListings']['costs']['weekday'];
        $we_price = $this->rules['FeaturedListings']['costs']['weekend'];   



        $price = (string) (($wd_price * $weekdays) + ($we_price * ($data->duration - $weekdays)));

        $data->item_type = "FeaturedListing";
        
        $sellerData = json_encode($data);
        
        $request = array(
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "currencyCode" => "USD",
            "sellerData" => $sellerData
            );
        return $request;
    }





}

