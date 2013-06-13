<?php

class ShoppingCartController extends AppController {
  public $helpers = array('Html');
  public $components = array('Auth');
  public $uses = array('User', 'ShoppingCart');
  public $TAG = "ShoppingCartController";

  /*
    Receives a post request containing a order_items json array to add to the users cart
  */
  public function add(){
    
    if(!$this->request->is('POST')){
      throw new NotFoundException();
    }

    $orderItem = json_decode($this->request->data('orderItem'));
    $user_id = $this->Auth->User("id");
    $this->ShoppingCart->add($orderItem, $user_id);

    $this->layout = 'ajax';
    $response = array('success'=>true);
    $this->set('response', json_encode($response));

  }

  /*
    Receives a post request containing the shopping cart index to edit and the updated order item info
    fields needed 'order_item', 'index'
  */
  public function edit(){
    if(!$this->request->is('POST')){
      throw new NotFoundException();
    }

    $user_id = $this->Auth->User("id");

    $orderItem = json_decode($this->request->data('orderItem'));
    $index = $this->request->data('index');

    $this->ShoppingCart->edit($index, $orderItem, $user_id);

    $this->layout = 'ajax';
    $response = array('success'=>true);
    $this->set('response', json_encode($response));

  }

  /*
    Receives post request with the field index, we remove the item at that index from the shopping cart
  */
  public function remove(){
    if(!$this->request->is('POST')){
      throw new NotFoundException();
    }

    $user_id = $this->Auth->User("id");

    $index = $this->request->data('index');

    $this->ShoppingCart->remove($index, $user_id);

    $this->layout = 'ajax';
    $response = array('success'=>true);
    $this->set('response', json_encode($response));

  }

  public function removeAll(){
    
    if(!$this->request->is('POST')){
      throw new NotFoundException();
    }

    $user_id = $this->Auth->User("id");
    $this->ShoppingCart->removeAll($user_id);

    $this->layout = 'ajax';
    $response = array('success'=>true);
    $this->set('response', json_encode($response));

  }

  public function get(){
    $user_id = $this->Auth->User("id");
    $cart = $this->ShoppingCart->get($user_id);
    die(debug($cart));
  }



}

?>
