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

/*
Returns a list of marker_ids that will be visible based on the current filter settings.
*/
  public function ApplyFilter($filterSettings)
  {
    if(!$this->request->is('ajax') && !Configure::read('debug') > 0)
      return;

    $this->layout = 'ajax';
    $response = $this->Rental->getFilteredMarkerIdList($filterSettings);
    $this->set('response', $response);
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