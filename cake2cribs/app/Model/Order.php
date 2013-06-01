<?php

class Order extends AppModel {
    public $name = 'Order';
    public $actsAs = array('Containable');
    public $uses = array('FeaturedListing', 'Order');
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
    public function logOrder($request){

        $seller_data = json_decode($request->sellerData);

        $item_type = $seller_data->item_type;

        // We do generic switch on type here for the various things you can buy
        // Creates the object that was purchased and we get the id for our generic fk
        
        switch($item_type){
            case "FeaturedListing":
                
                $FeaturedListing = ClassRegistry::init('FeaturedListing');
                $featured_listing = $FeaturedListing->add($seller_data);

                $item_id = $featured_listing['FeaturedListing']['id'];

                break;

            default:

                break;
        }

        $order_data['Order'] = array(
        
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>floatval($request->price),
            'currency_code'=>$request->currencyCode,
            'seller_data'=>$request->sellerData,
            'item_type'=>$item_type,
            'item_id'=>$item_id,
        
        );

        if(!$this->save($order_data)){
            die(debug($this->validationErrors));
        }

        $order = $this->read();

        return $order;
    }

    


}   