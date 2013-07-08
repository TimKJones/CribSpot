
<?php

class ShoppingCart extends AppModel {
    public $name = 'ShoppingCart';
    public $actsAs = array('Containable');
    public $uses = array('User', 'Order');
    public $belongsTo = array('User');

    public function add($orderItems, $user_id){
        $cart = $this->get($user_id);
        $items = json_decode($cart['ShoppingCart']['items'], true);

        foreach($orderItems as $orderItem){
            array_push($items, $orderItem);    
        }

        $cart['ShoppingCart']['items'] = json_encode($items);
        if(!$this->save($cart)){
            die(debug($this->validateErrors));
        }else{
            return true;
        }


    }

    public function edit($index, $orderItem, $user_id){

        $cart = $this->get($user_id);
        $items = json_decode($cart['ShoppingCart']['items'], true);
        $keys = array_keys($items);
        if($items[$keys[$index]] == null){
            throw new Exception('invalid index given, no item exists');
        }

        $items[$keys[$index]] = $orderItem;
        $cart['ShoppingCart']['items'] = json_encode($items);
        if(!$this->save($cart)){
            die(debug($this->validateErrors));
        }else{
            return true;
        }

    }

    public function remove($index, $user_id){
        $cart = $this->get($user_id);
        $items = json_decode($cart['ShoppingCart']['items'], true);
        
        $keys = array_keys($items);
        if($items[$keys[$index]] == null){
            throw new Exception('invalid index given, no item exists');
        }

        array_splice($items, $index, 1);
        $cart['ShoppingCart']['items'] = json_encode($items);
        if(!$this->save($cart)){
            die(debug($this->validateErrors));
        }else{
            return true;
        }
    }

    public function removeAll($user_id){
        $cart = $this->get($user_id);
        $cart['ShoppingCart']['items'] = json_encode(array());
        if(!$this->save($cart)){
            die(debug($this->validateErrors));
        }else{
            return true;
        }
    }

    public function get($user_id){
        $this->contain();
        $cart = $this->find('first', array("conditions"=>"ShoppingCart.user_id = $user_id"));
        if($cart == null){
            //The user has never tried to access their cart so we just make a new one
            $cart = $this->initNewCart($user_id);
        }else{
            /*
                We need to make sure that the orderItems in the cart are all valid
                Maybe the case that the user has a featuredListing in their cart with
                an invalid date example a date in the past. We only need to do this check once a day so if the 
                cart hasn't been modified today we need to check it.

                Our naive solution for right now is to, if the cart hasn't been modified today
                is to just clear it.

                [TODO] come up with a good solution to resolve this, maybe removing the invalid dates
                or flagging it for the user. One issue existed with trying to have the user resolve it
                and that is the multi date picker widget used is tricky and if dates are selected
                in the unselectable range then the user can't unselect them.
            */

            $date_modified = date("Y/m/d", strtotime($cart['ShoppingCart']['modified']));
            $today = date("Y/m/d");

            /*
                We unset this field to allow cakephp to update the modified field
                Since we are calling save on the cart object that we fetched
                it can't have the modified field present while saving
            */

            unset($cart['ShoppingCart']['modified']);

            // die(debug($cart));
            if($date_modified != $today){
                $cart['ShoppingCart']['items'] = json_encode(array());
                if(!$this->save($cart)){
                    die(debug($this->validateErrors));
                }
            }

        }
        return $cart;
    }

    private function initNewCart($user_id){
        $cart_data = array(
            'ShoppingCart'=>array(
                'user_id'=>$user_id,
                'items'=>json_encode(array()),
                )
            );

        $this->create($cart_data);
        if(!$this->save()){
            die(debug($this->validateErrors));
        }

        return $this->read();
    }
    


}
