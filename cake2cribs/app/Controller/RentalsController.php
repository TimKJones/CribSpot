<?php
class RentalsController extends AppController 
{	
	public $helpers = array('Html');
	public $uses = array('Rental', 'RentalIncomplete', 'Fee', 'Listing');
	public $components= array('RequestHandler', 'Auth', 'Session');

  public function beforeFilter()
  {
    parent::beforeFilter();
    $this->Auth->allow('Save');
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
    /*
    if rental[listing_id] is set
      Check if user owns listing.
        yes -> create the listings array and set listing_id
        no  -> return error message
    Listing->Save()
    If successful return listing_id.
    If fail, return validation errors.
    */
    $this->layout = 'ajax';
    $rentalObject = $this->params['data'];
    $rentalObject['Listing'] = array();
    $rentalObject['Listing']['user_id'] = $this->_getUserId();
    $rentalObject['Listing']['listing_type'] = 0; /* TODO: Make this reference the Listing::LISTING_TYPE_RENTAL constant */
    if (array_key_exists('listing_id', $rentalObject['Rental'])){
      /* We are saving an existing listing. */
      if ($this->UserOwnsListing($rentalObject['Rental']['listing_id'])){
        $rentalObject['Listing']['listing_id'] = $rentalObject['Rental']['listing_id'];
      }
      else {
        $this->set('response', json_encode(array('error' => 'Rental save unsuccessful. Error code: 2')));
        return;
      }
    }
    
    $response = $this->Listing->SaveListing($rentalObject);
    $this->set('response', json_encode($response));
    return;
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

  private function _getUserId()
  {
    return 15;
  }

  /*
    Check if user owns listing_id. Returns true if so, false otherwise.
  */
  function UserOwnsListing($listing_id)
  {
    $user_id = $this->_getUserId();
    if ($user_id == null || $user_id == 0)
      return false;

    $listingType = $this->Listing->GetListingType($listing_id);
    if ($listingType == Listing::LISTING_TYPE_RENTAL)
      return $this->Listing->UserOwnsListing($listing_id, $user_id);

    return false;
  }
}