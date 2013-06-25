<?php
class RentalsController extends AppController 
{	
	public $helpers = array('Html');
	public $uses = array('Rental', 'Fee');
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
  Save each rental object and fee object passed via POST data.
  REQUIRES: each rental and fee object is in the form cake expects for a valid save.
  */
  public function Save()
  {
    $this->layout = 'ajax';
    $rentalObject = $this->params['data'];
    $rental = $rentalObject['Rental'];
    $fees = $rentalObject['Fees'];
    //$rentals['user_id'] = $this->Auth('user');
    $rentals['user_id'] = 25;
    $rentalResponse = $this->Rental->SaveRental($rental);

    /*
    $rentalResponse contains the listing_id of the saved rental if successful.
    If rental save was unsuccessful, return an error code to client.
    */
    $listing_id = null;
    $ajaxResponse = null;
    if (!array_key_exists('error', $rentalResponse) && array_key_exists('listing_id', $rentalResponse))
      $listing_id = $rentalResponse['listing_id'];
    else
    {
      if (array_key_exists('error', $rentalResponse))
        $this->set('response', json_encode($ajaxResponse));
      else
        $this->set('response', json_encode(array('error' => 'Rental save unsuccessful')));

      return;
    }

    /* Save fees using $listing_id of saved rental */
    $feesResponse = $this->Fee->SaveFees($fees, $listing_id);
    $this->set('response', json_encode($feesResponse));
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