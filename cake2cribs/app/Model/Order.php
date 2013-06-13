<?php

class Order extends AppModel {
    public $name = 'Order';
    public $actsAs = array('Containable');
    public $uses = array('FeaturedListing', 'Order', 'PendingOrder');
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

        $seller_data = json_decode($request->sellerData);
        
        $pendingOrder_id = $seller_data->pendingOrder_id;
        
        $pendingOrder = $PendingOrder->find('first', array('conditions'=>
            'PendingOrder.id='.$pendingOrder_id));

        if($pendingOrder == null){
            return null;
        }

        $order = json_decode($pendingOrder['PendingOrder']['order']);
        

        $price = $order->total;

        if($order->user_id != $user_id){
            throw new Exception("Order's user and logged in user don't match");
        }

        foreach($order->orderItems as &$orderItem){

            // We do generic switch on type here for the various things you can buy
            // Creates the object that was purchased and we get the id for our generic fk
            switch($orderItem->type){
                case "FeaturedListing":
                    $FeaturedListing = ClassRegistry::init('FeaturedListing');
                    $orderItem->item->featured_listing_ids = array();
                    foreach ($orderItem->item->dates as $date) {
                        $featured_listing = $FeaturedListing->add($orderItem->item->listing_id, $date, $user_id);
                        $item_id = $featured_listing['FeaturedListing']['id'];
                        array_push($orderItem->item->featured_listing_ids, $item_id);
                    }
                    
                    $orderItem->item->street_address = $featured_listing['FeaturedListing']['street_address'];



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
            'orderItems'=>json_encode($order->orderItems),
            'user_id'=>$user_id,
            'wallet_order_id'=>$wallet_order_id
        
        );
        $this->create($order_data);
        if(!$this->save()){
            die(debug($this->validationErrors));
        }

        $order = $this->read();
        
        if($order != null)
            // $PendingOrder->delete($pendingOrder_id);

        return $order;
    }

    


}   