<?php

class ListingsController extends AppController {
	public $uses = array('Listing', 'Rental');
	public $components= array('Session');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('Save');
		$this->Auth->allow('Delete');
		$this->Auth->allow('GetListing');
		$this->Auth->allow('GetListingsByLoggedInUser');
		$this->Auth->allow('LoadMarkerData');
	}

	/*
	Save each rental object and fee object passed via POST data.
	If unsuccessful, returns an error code as well as a list of the fields that failed validation
	REQUIRES: each rental and fee object is in the form cake expects for a valid save.
	*/
	public function Save()
	{
		$this->layout = 'ajax';
		$listingObject = $this->params['data'];
		$listingObject['Listing']['user_id'] = $this->_getUserId();
		$response = $this->Listing->SaveListing($listingObject);
		$this->set('response', json_encode($response));
		return;
	}

	/* Deletes the listings in $listing_ids */
	public function Delete ($listing_ids)
	{
		$this->layout = 'ajax';
		$listing_ids = json_decode($listing_ids);

		for ($i = 0; $i < count($listing_ids); $i++){
			if (!$this->UserOwnsListing($listing_ids[$i]))
			{
				$this->set('response', json_encode(array('error' => 'failed to delete listing. Error code 3')));
				return;
			}

			/* Delete from listings table. Set cascade=true to also delete from either rentals, parkings, or sublets. */
			if (!$this->Listing->delete($listing_ids[$i], true))
			{
				$this->set('response', json_encode(array('error' => 'failed to delete listing. Error code 2')));
				return;
			}
		}

		$this->set('response', json_encode(array('success' => '')));
		return;
	}

	/*
	Returns json-encoded listing
	NOTE: only returns PUBLIC user data
	If $listing_id is null, returns all listings owned by logged-in user
	*/
	function GetListing($listing_id = null)
	{
		$this->layout = 'ajax';
		if ($listing_id == null){
			/* Return all listings owned by this user. */
			$listings = $this->GetListingsByLoggedInUser();
			$this->set('response', json_encode($listings));
		}
		else{
			/* Return the listing given by $listing_id */
			$listing = $this->Listing->GetListing($listing_id);
			if ($listing == null)
				$listing['error'] = 'Listing id not found';

			$this->set('response', json_encode($listing));
		}
	}

	/*
	Returns all listing_data for given user_id.
	NOTE: only returns PUBLIC user data
	*/
	function GetListingsByLoggedInUser()
	{
		$user_id = $this->_getUserId();
		if ($user_id == 0 || $user_id == null){
			return array('error' => 'Error retrieving listings. User not logged in.');
		}
		
		$listings = $this->Listing->GetListingsByUserId($user_id);
		if ($listings == null)
			return array('error' => 'Error retrieving listings');

		return $listings;
	}

	/*
	AJAX
	Returns all listings of given listing_type with given marker_id
	*/
	function LoadMarkerData($listing_type, $marker_id)
	{
		$this->layout = 'ajax';
		$listings = $this->Listing->GetMarkerData($listing_type, $marker_id);
		$this->set('response', json_encode($listings));
	}
}

?>