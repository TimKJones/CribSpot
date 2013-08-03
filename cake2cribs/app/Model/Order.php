<?php

class Order extends AppModel {
    public $name = 'Order';
    public $actsAs = array('Containable');
    public $uses = array('FeaturedListing', 'Listing', 'Order', 'PendingOrder');

    const FLWeekdayCost = 15.00;
    const FLWeekendCost = 5.00;
    const FLMinStartOffset = 259200;  // 3 * (60 * 60 * 24);   //3 days ahead is the gap needed to feature listing
    const FLDailyLimit = 2;

    const WalletSellerID = "10354430150694430158";
    const WalletSecretKey = "XWLZaH-bSdUGUJxlSZZVSg";

    const ORDER_TYPE_FEATURED_LISTING = 0;
    // const ORDER_TYPE_PARKING = 1;
    

    public static function order_type($value = null){
        $options = array(
            self::ORDER_TYPE_FEATURED_LISTING => __("Featured Listing", true),
            // self::ORDER_TYPE_PARKING => __("Parking", true),
        );
        return parent::enum($value, $options);
    }

    // Takes an array of validated order items and returns a google wallet jwt
    // also creates a pending order to be used when the purchased goes through.
    // JWT spec found here https://developers.google.com/commerce/wallet/digital/docs/jsreference#jwt

    // For right now all the types of the orderItems must be the same, all featured listings for example

    public function generateJWT($orderItems, $user_id, $order_type){
        switch($order_type){
            case self::ORDER_TYPE_FEATURED_LISTING:
                $request = $this->generateFLRequest($orderItems);
                break;
            default:
                $this->LogError($user_id, 36, array('orderItems'=>$orderItems, 'orderType'=>$order_type));
                throw new Exception("Order Type was not valid");
        }

        //Create a pending order so we can stash the pending order id 
        //in the request sellerdata for later retreival and order fulfillment

        $PendingOrder = ClassRegistry::init('PendingOrder');        
        $pendingOrder = $PendingOrder->add($request['price'], $orderItems, $user_id);

        $sellerData = array(
            'pendingOrder_id'=>$pendingOrder['PendingOrder']['id']
            );

        $request["currencyCode"] = "USD";
        $request["sellerData"] = json_encode($sellerData);

        //At this point the request array is complete per the Wallet Specs
        $jwt = array(
            "iss" => self::WalletSellerID,
            "aud" => "Google",
            "typ" => "google/payments/inapp/item/v1",
            "exp" => (time() + 3600) * 1000,
            "iat" => time() * 1000,
            "request" => $request,
            "response"=> array("orderId"=>"69")  //Not a valid field but used to test postback
                                                // will remove in production
        );
        
        return $jwt;
    }

    // Returns a google wallet request array. It'll contain the following
    // Fields name, description, price.
    // Note the other two fields required by Wallet will be added to the array later
    // since they are not specific to a featured listing
    private function generateFLRequest($orderItems){
        App::uses('DateHelpers', 'Utilities');

        $total = 0;
        $weekdays = 0;
        $weekends = 0;
        
        // We need to add up all the dates (weekdays and weekends)
        // as well as generate a price for each orderItem
        foreach($orderItems as &$orderItem){
            
            // this is a two indexed array that contains the number of weekdays and weekends
            // that are contained in the dates for the order item
            $day_counts = DateHelpers::getDayCounts($orderItem->item->dates);
            $weekdays += $day_counts['weekdays'];
            $weekends += $day_counts['weekends'];

            // Calculate the price for this order Item
            $item_price = ($day_counts['weekdays'] * self::FLWeekdayCost) + ($day_counts['weekends'] * self::FLWeekendCost);
            $orderItem->price = $item_price;
            $total += $item_price;
        }

        // Now generate the name of the order and the description
        $name = "Featured Listing on Cribspot.com";
        $description = "Weekdays: $weekdays x $".self::FLWeekdayCost."/day + Weekends: $weekends x $".self::FLWeekendCost."/day";

        return array(
            "name" => $name,
            "description" => $description,
            "price" => $total,
            );


    }


    /*
        
        Takes in a Google Wallet $request
        
        Logs the order information into an order object and uses the
        data provided in the json encoded seller data field to create
        the new item.

    */
    public function logOrder($request, $wallet_order_id, $user_id){
        $PendingOrder = ClassRegistry::init('PendingOrder');

        //The pending order id is stored in the seller data of the request
        $seller_data = json_decode($request->sellerData);        
        $pendingOrder_id = $seller_data->pendingOrder_id;
        
        //Fetch the pending order related to the request
        $conditions = array("PendingOrder.id"=>$pendingOrder_id, "PendingOrder.user_id"=>$user_id);
        $pendingOrder = $PendingOrder->find('first', array('conditions'=>$conditions));

        if($pendingOrder == null){
            $this->LogError($user_id, 37, array("wallet_order_id"=>$wallet_order_id, "request"=>$request));
            throw new Exception("No pending order found for user");
        }

        $orderItems = json_decode($pendingOrder['PendingOrder']['orderItems']);
        

        foreach($orderItems as &$orderItem){

            // We do generic switch on type here for the various things you can buy
            // Creates the object that was purchased and we get the id for our generic fk
            switch($orderItem->type){
                case "FeaturedListing":
                    $FeaturedListing = ClassRegistry::init('FeaturedListing');
                    // We want to store all the id's of the featured listings we create
                    // so we can link a users order to the specific featured listings 
                    // instances that they bought
                    $orderItem->item->featured_listing_ids = array();
                    foreach ($orderItem->item->dates as $date) {
                        $featured_listing = $FeaturedListing->add($orderItem->item->listing_id, $date, $user_id);
                        $item_id = $featured_listing['FeaturedListing']['id'];
                        array_push($orderItem->item->featured_listing_ids, $item_id);
                    }
                    break;

                default:

                    break;
            }
        }

        
        // die(debug(json_encode($order->orderItems)));
        $order_data['Order'] = array(
        
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>floatval($request->price),
            'currency_code'=>$request->currencyCode,
            'orderItems'=>json_encode($orderItems),
            'user_id'=>$user_id,
            'wallet_order_id'=>$wallet_order_id
        
        );
        $this->create($order_data);
        if(!$this->save()){
            die(debug($this->validationErrors));
        }

        $order = $this->read();
        
        //Clean up the pending order related to this order
        if($order != null)
            $PendingOrder->delete($pendingOrder_id);
        else
            $this->logError($user_id, 38, $orderItems);
            throw new Exception("Order did not go through");

        return $order;
    }

    public function validateFLOrder($orderItems, $user_id, $SU=false){
        $FeaturedListing = ClassRegistry::init('FeaturedListing');
        $Listing = ClassRegistry::init('Listing');
        $University = ClassRegistry::init('University');
        // Build a map of a date and num featuredlistings on that date
        $dates = array();
        $validationErrors = array();

        foreach($orderItems as &$orderItem){
            //Only want unique dates, incase extras got in we remove them
            $orderItem->item->dates = array_unique($orderItem->item->dates); 
            $listing_id = $orderItem->item->listing_id;
            
            // Depending on if the order is being carried out by a super
            // user do different checks
            if(!$SU){
                if(!$Listing->UserOwnsListing($listing_id, $user_id)){
                    $msg = "User doesn't own the listing";
                    array_push($validationErrors, array('id'=>$listing_id, 'reason'=>$msg));
                }
            }else{

                // Super users like the Mich Daily don't have to own listing to feature it
                if(!$Listing->ListingExists($listing_id)){
                    $msg = "Listing doesn't exist";
                    array_push($validationErrors, array('id'=>$listing_id, 'reason'=>$msg));   
                }
            }

            // Go through and make sure each university they are trying to feature
            // at actually exists
            foreach ($orderItem->item->universities as $university_id => $feature) {
                if(!$University->UniExists($university_id)){
                    $msg = "University with ID $university_id doesn't exist";
                    array_push($validationErrors, array('id'=>$listing_id, 'reason'=>$msg));   
                }
            }

            // unique dates incase duplicate dates slipped in the post data
            foreach($orderItem->item->dates as $date){
                if(!array_key_exists($date, $dates)){
                    $dates[$date]['id'] = array();
                    $dates[$date]['count'] = $FeaturedListing->countListingsOnDate($date);
                }
                array_push($dates[$date]['id'], $listing_id);
            }
        }

        // We now want to see if featuring the listings on the given dates
        // will put us over our daily limit. Also check to see if a date 
        // is with in the valid range (n days in the future). Check to see if
        // a listing is already featured on a day
        
        $min_start_date = date("m/d/Y", time() + self::FLMinStartOffset); // first day they can start featuring
        $min_start = strtotime($min_start_date);
        // echo $min_start_date;
        // echo $min_start;
        foreach($dates as $date=>$info){
            
            // Convert the date string to a more readable format for the user
            $date = date("m/d/Y", strtotime($date));

            // Note 8/2/2013 We aren't checking for the limit per day as Jason
            // Says there is no limit. This check also complicates our validation flow
            // on the front end that we'll get aroundt to later when we do need to enforce the count

            // // See if featuring the listings on a given date will put us over
            // // The limit
            // if($info['count'] + count($info['id']) > self::FLDailyLimit){
            //     $msg = "Not enough spots left to feature on $date, only ".$info['count']." spots left";
            //     foreach($info['id'] as $id){
            //         array_push($validationErrors, array("id"=>$id, "reason"=>$msg));
            //     }
            // }

            //See if the date trying to be featured on is before the minimum start date
            if(strtotime($date) < $min_start){
                $msg = "Date selected ($date) is before the minimum start date ($min_start_date)";
                foreach($info['id'] as $id){
                    array_push($validationErrors, array("id"=>$id, "reason"=>$msg));
                }   
            }

            // See if the listing is already featured on the given day.
            // If it is we want to tell the user, let them go through with it
            // but not 
            foreach($info['id'] as $id){
                if($FeaturedListing->featuredOnDate($id, $date)){
                    $msg = "Listing already featured on date ($date)";
                    array_push($validationErrors, array("id"=>$id, "reason"=>$msg));
                }
            }
        }



        if(count($validationErrors) > 0){
            // Rearrange the validation errors structure to be grouped by listing_id
            $groupedErrors = array();
            foreach($validationErrors as $error){

                $id = $error['id'];

                if(!array_key_exists($id, $groupedErrors)){
                    $groupedErrors[$id]=array();
                }
                array_push($groupedErrors[$id], $error['reason']);
            }
            $this->logError($user_id, 39, array("orderItems"=>$orderItems, "errors"=>$groupedErrors));
            return $groupedErrors;
        }else{
            return null;
        }        
    }


}   