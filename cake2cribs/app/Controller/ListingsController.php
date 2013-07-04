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
	Check if user owns listing_id. Returns true if so, false otherwise.
	*/
	function UserOwnsListing($listing_id)
	{
		$user_id = 15;
		if ($user_id == null || $user_id == 0)
			return false;

		$listingType = $this->Listing->GetListingType($listing_id);
		if ($listingType == Listing::LISTING_TYPE_RENTAL)
			return $this->Rental->UserOwnsRental($listing_id, $user_id);

		return false;
	}

	/*
	Returns json-encoded listing
	NOTE: only returns PUBLIC user data
	*/
	function GetListing($listing_id)
	{
		$this->layout = 'ajax';
		$listing = $this->Listing->GetListing($listing_id);
		if ($listing == null)
			$listing['error'] = 'Listing id not found';

		$this->set('response', json_encode($listing));
	}

	/*
	Returns all listing_data for given user_id.
	NOTE: only returns PUBLIC user data
	*/
	function GetListingsByLoggedInUser()
	{
		$this->layout = 'ajax';
		$user_id = $this->Auth->User('id');
		if ($user_id == 0 || $user_id == null){
			$listings['error'] = 'Error retrieving listings. User not logged in.';
			$this->set('response', json_encode($listings));
			return;
		}
		$listings = $this->Listing->GetListingsByUserId($this->Auth->User('id'));
		if ($listings == null)
			$listings['error'] = 'Error retrieving listings';

		$this->set('response', json_encode($listings));
	}

}

?>