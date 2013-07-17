<?php

class Order extends AppModel {
    public $name = 'Order';
    public $actsAs = array('Containable');
    public $uses = array('FeaturedListing', 'Listing', 'Order', 'PendingOrder');

    const FLWeekdayCost = 15.00;
    const FLWeekendCost = 5.00;
    const FLMinStartOffset = 259200;  // 3 * (60 * 60 * 24);   //3 days ahead is the gap needed to feature listing
    const FLDailyLimit = 2;
    // public $belongsTo = array('Listing', 'User');
    
    // public $validate = array(
    //     'user_id' => 'numeric',
    //     'listing_id' => 'numeric',
    //     'latitude' => 'numeric',
    //     'longitude' => 'numeric',
    // );


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
            throw new Exception("Order did not go through");

        return $order;
    }

    public function validateOrder($orderItems, $user_id, $SU=false){
        $FeaturedListing = ClassRegistry::init('FeaturedListing');
        $Listing = ClassRegistry::init('Listing');
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
        
        $min_start_date = date("m/d/Y", time() + $this::FLMinStartOffset); // first day they can start featuring
        $min_start = strtotime($min_start_date);
        // echo $min_start_date;
        // echo $min_start;
        foreach($dates as $date=>$info){
            
            // Convert the date string to a more readable format for the user

            $date = date("m/d/Y", strtotime($date));
            // See if featuring the listings on a given date will put us over
            // The limit
            if($info['count'] + count($info['id']) > $this::FLDailyLimit){
                $msg = "Not enough spots left to feature on $date, only ".$info['count']." spots left";
                foreach($info['id'] as $id){
                    array_push($validationErrors, array("id"=>$id, "reason"=>$msg));
                }
            }

            //See if the date trying to be featured on is before the minimum start date
            if(strtotime($date) < $min_start){
                $msg = "Date selected ($date) is before the minimum start date ($min_start_date)";
                foreach($info['id'] as $id){
                    array_push($validationErrors, array("id"=>$id, "reason"=>$msg));
                }   
            }

            //See if the listing is already featured on the given day.
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
            return $groupedErrors;
        }else{
            return null;
        }        
    }

    /*
        Makes sure the order item is valid and will make alterations to the object to make sure
        an example featured listing order item will have the structure 
        
        type: 'FeaturedListing'
        price: ???
        item:{
            street_address
            listing_id
            dates
        }

        this function will make sure that all the dates fall within the valid range
        all duplicates will be removed as well. The price will be recalculated as well

        a check to see if the listing that the FL is referencing is valid is made as well
        and the relevant info is updated too such as address
    
    */
    public function validateFeaturedListing($orderItem){
        
        if($orderItem->type != "FeaturedListing"){
            throw new Exception("Invalid type for order item, FeaturedListing expected");
        }

        //Check to see if listing that is being referenced is valid
        $Listing = ClassRegistry::init('Listing');
        $listing_id = $orderItem->item->listing_id;
        $listing = $Listing->Get($listing_id);
        if($listing == null)
            throw new Exception("Invalid listing_id $listing_id for requested featured listing");

        // Add the listings street address to the order item so we don't have 
        // to keep looking it up. Used to identify clearly the listing instead of an id to the user
        $orderItem->item->street_address = $listing['Marker']['street_address'];


        //Validate the dates selected in the order
        
        //Only want unique dates, incase extras got in we remove them
        $orderItem->item->dates = array_unique($orderItem->item->dates); 
        $dates = $orderItem->item->dates;
        if(count($dates) == 0){
            throw new Exception("No dates selected.");
        }

        $min_start_date = date("m/d/Y", time() + $this::FLMinStartOffset); // first day they can start featuring
        $min_start = strtotime($min_start_date);
        // Need to make sure each date selected falls within the valid
        // range of dates. Example: Dates have to be selected 3 days in advanced

        foreach ($dates as $key => $date) {
            $start = strtotime($date);
            if($min_start > $start){   
                throw new Exception("Date selected ($date) is before the minimum start date ($min_start_date)");
            }
        }

        App::uses('DateHelpers', 'Utilities');
        $day_counts = DateHelpers::getDayCounts($dates);
        $price = ($day_counts['weekdays'] * $this::FLWeekdayCost) + ($day_counts['weekends'] * $this::FLWeekendCost);
        $orderItem->price = $price;

    }

    


}   