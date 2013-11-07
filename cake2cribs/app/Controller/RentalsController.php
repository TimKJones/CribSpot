<?php
class RentalsController extends AppController 
{ 
  public $helpers = array('Html');
  public $uses = array('Rental', 'RentalIncomplete', 'Fee', 'Listing');
  public $components= array('RequestHandler', 'Auth', 'Session', 'Cookie');

  public function beforeFilter()
  {
    parent::beforeFilter();
    $this->Auth->allow('Save');
    $this->Auth->allow('Get');
  }

  public function View()
  {
    $directive['classname'] = 'rental';
    $json = json_encode($directive);
    $this->Cookie->write('dashboard-directive', $json);
    $this->redirect('/dashboard');
  }

  /*
  Returns JSON encoded array of all rentals with ids in $rental_ids.
  If $rental_ids is null, returns all rentals owned by the logged-in user.
  */
  public function Get($rental_ids = null)
  {

  }

  /*
  Save an additional row for each rental with an id in $rental_ids
  TODO: This will be implemented later.
  */
  public function Copy($rental_ids = null)
  {

  }
}