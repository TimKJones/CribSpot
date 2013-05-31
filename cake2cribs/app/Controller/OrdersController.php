<?php
class OrdersController extends AppController {
  public $helpers = array('Html');
  public $components = array('Auth');
  public $uses = array('Listing', 'User', 'FeaturedListing');
  public $TAG = "OrdersController";

    // Takes a post request containing data type and then a data object with all 
    // info to get a jwt generated for the purchase
    // JWT spec found here https://developers.google.com/commerce/wallet/digital/docs/jsreference#jwt
    public function getJwt(){
        $WalletSellerID = "10354430150694430158";
        $WalletSecretKey = "XWLZaH-bSdUGUJxlSZZVSg";

        $type = $this->request->data['type'];
        $data = json_decode($this->request->data['info']);
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
                "iss" => $WalletSellerID,
                "aud" => "Google",
                "typ" => "google/payments/inapp/item/v1",
                "exp" => (time() + 3600) * 1000,
                "iat" => time() * 1000,
                "request" => $request
            );


            App::uses('JWT', 'JWT');
            $response['jwt_plain'] = $payload;
            $response['jwt'] = JWT::encode($payload, $WalletSecretKey);
        }

        $this->layout = 'ajax';
        $this->set('response', json_encode($response));

    }

    public function getFl(){}



    private function getFeaturedListingRequest($data){
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
        $start_date = date("m-d-Y", $data->start/1000);
        $end_date = date("m-d-Y", ($data->start/1000) + (24*60*60*$data->duration));
        $description = "$address will be featured from $start_date - $end_date";
        $price = (string) ($daily_rate * $data->duration);
        
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

