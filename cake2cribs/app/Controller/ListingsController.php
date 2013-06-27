<?php

class ListingsController extends AppController {
	public $uses = array('Listing', 'Rental');
	public $components= array('Session');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('Save');
		$this->Auth->allow('Delete');
		$this->Auth->allow('Get');
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

}

?>