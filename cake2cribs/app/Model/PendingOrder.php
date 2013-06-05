<?php

class PendingOrder extends AppModel {
    public $name = 'PendingOrder';
    // public $actsAs = array('Containable');
    // public $uses = array('FeaturedListing', 'Order');

    public function add($order, $user_id){
        $pending_order_data = array(
            'PendingOrder'=>array(
                'price'=>$order['total'],
                'order'=>json_encode($order),
                'user_id'=>$user_id,
                )
        );
        $this->create($pending_order_data);
        if(!$this->save()){
            die(debug($this->validationErrors));
        }

        $pending_order = $this->read();
        return $pending_order;
    }

}