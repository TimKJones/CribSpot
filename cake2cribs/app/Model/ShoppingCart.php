
<?php

class ShoppingCart extends AppModel {
    public $name = 'ShoppingCart';
    public $actsAs = array('Containable');
    public $uses = array('User');
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
            die(debug($this->v3alidateErrors));
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
            $cart = $this->initNewCart($user_id);
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
