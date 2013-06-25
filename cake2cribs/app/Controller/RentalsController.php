<?php
class RentalsController extends AppController 
{	
	public $helpers = array('Html');
	public $uses = array('Rental');
	//public $components= array('');

  public function beforeFilter()
  {
    parent::beforeFilter();
    $this->Auth->allow('Save');
    $this->Auth->allow('Delete');
    $this->Auth->allow('Get');
  }

  public function index()
  {

  }

  /*
  Save each rental object in $rentals
  REQUIRES: each rental object is in the form cake expects for a valid save.
  */
  public function Save()
  {
    $this->layout = 'ajax';
    //CakeLog::write("AjaxDebug", print_r($this->params->, true));
    $rentals = $this->params['data'];
    //CakeLog::write("RentalSave", print_r($rentals, true));
    $response = $this->Rental->SaveRental($rentals, 26);
    $this->set('response', json_encode($response));
  }

  /*
  Delete each rental with an id in $rental_ids
  */
  public function Delete($rental_ids = null)
  {

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