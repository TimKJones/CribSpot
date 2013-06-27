<?php
class RentalsController extends AppController 
{	
	public $helpers = array('Html');
	public $uses = array('Rental', 'Fee', 'Listing');
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
  If unsuccessful, returns an error code as well as a list of the fields that failed validation
  REQUIRES: each rental and fee object is in the form cake expects for a valid save.
  */
  public function Save()
  {
    $this->layout = 'ajax';
    $rentalObject = $this->params['data'];
    $rental = $rentalObject['Rental'];
    $fees = $rentalObject['Fees'];
    //CakeLog::write("SavingFees", print_r($fees, true));

    /* TODO: get the real user id here */
    $rentals['user_id'] = 25;

    $listingResponse = $this->Listing->SaveListing(Listing::LISTING_TYPE_RENTAL);
    /*
    $listingResponse contains the listing_id of the saved rental if successful.
    If rental save was unsuccessful, return an error code to client.
    */
    $listing_id = null;
    $ajaxResponse = null;
    if (!array_key_exists('error', $listingResponse) && array_key_exists('listing_id', $listingResponse))
      $listing_id = $listingResponse['listing_id'];
    else
    {
      $this->set('response', json_encode(array('error' => 'Rental save unsuccessful')));
      return;
    }

    $rentalResponse = $this->Rental->SaveRental($rental);
    if (array_key_exists('error', $rentalResponse))
    {
      $rentalResponseWrapper['Rental'] = $rentalResponse['error'];
      /* Return to the client a list of fields that failed validation */
      $this->set('response', json_encode(array('error' => $rentalResponseWrapper)));
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