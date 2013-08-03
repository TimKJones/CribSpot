<?php

class PendingOrder extends AppModel {
    public $name = 'PendingOrder';
    // public $actsAs = array('Containable');
    // public $uses = array('FeaturedListing', 'Order');

    public function add($total, $orderItems, $order_type, $user_id){
        $pending_order_data = array(
            'PendingOrder'=>array(
                'total'=>$total,
                'orderItems'=>json_encode($orderItems),
                'order_type'=>$order_type,
                'user_id'=>$user_id,
                )
        );
        $this->create($pending_order_data);
        if(!$this->save()){
            $this->logError($user_id, 40, array("orderItems"=>$orderItems, "validationErrors"=>$this->validationErrors));
            die(debug($this->validationErrors));
        }

        $pending_order = $this->read();
        return $pending_order;
    }

}