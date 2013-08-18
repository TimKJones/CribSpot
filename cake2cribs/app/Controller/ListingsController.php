<?php

class ListingsController extends AppController {
	public $uses = array('Listing', 'Rental', 'Image', 'Favorite');
	public $components= array('Session');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('view');
		$this->Auth->allow('GetListing');
		$this->Auth->allow('GetListingsByLoggedInUser');
		$this->Auth->allow('LoadMarkerData');
		$this->Auth->allow('Save');
		$this->Auth->allow('Delete');
	}

	/*
	View a full page listing. Grabs the listing data and modifies it for
	the full page listing view (view.ctp)
	*/
	public function view($listing_id = null, $address = null)
	{
		if ($listing_id == null)
			throw new NotFoundException('There is no listing provided!');

		$listing = $this->Listing->GetListing($listing_id);
		$listing = $listing[0];

		if ($listing == null)
			throw new NotFoundException('There is no listing provided!');

		$full_address = $listing["Marker"]["street_address"];
		$full_address .= " " . $listing["Marker"]["city"];
		$full_address .= " " . $listing["Marker"]["state"];
		$full_address .= " " . $listing["Marker"]["zip"];
		$full_address = str_replace(" ", "-", $full_address);

		if ($address == null)
			$this->redirect(array('action' => 'view', $listing_id, $full_address));

		$listing['Favorite'] = false;
		if ($this->_getUserId() !== null)
		{
			$favorites = $this->Favorite->GetFavoritesListingIds($this->_getUserId());
			$listing['Favorite'] = in_array($listing_id, $favorites);
		}

		$this->_refactorMoneyFields($listing);
		$this->_refactorTextFields($listing);
		$this->_refactorOwnerFields($listing);
		$this->_setPrimaryImage($listing);
		$this->_refactorAmenities($listing['Rental']);

		$this->set('listing_json', json_encode($listing));
		$this->set('listing', $listing);

	}

	/*
	Save each rental object and fee object passed via POST data.
	If unsuccessful, returns an error code as well as a list of the fields that failed validation
	REQUIRES: each rental and fee object is in the form cake expects for a valid save.
	*/
	public function Save()
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		$this->layout = 'ajax';
		$listingObject = $this->params['data'];
		$listing = $listingObject['Listing'];
		$listing['Listing'] = $listing;
		$images = null;
		if (array_key_exists('Image', $listing)){
			$images = $listingObject['Image'];
			$images['Image'] = $images;
		}

		$response = $this->Listing->SaveListing($listingObject, $this->_getUserId());
		if (!array_key_exists('error', $response) && 
			array_key_exists('listing_id', $response) && 
			$images != null) {
			// Update images that bad been saved before listing_id was known
			$imageResponse = $this->Image->UpdateAfterListingSave($response['listing_id'], $images, $this->_getUserId());
			if (array_key_exists('error', $imageResponse))
				$response['error'] = $imageResponse['error'];
		}

		$this->set('response', json_encode($response));
		return;
	}

	/* Deletes the listings in $listing_ids */
	public function Delete ($listing_ids)
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		$this->layout = 'ajax';
		$listing_ids = json_decode($listing_ids);	

		for ($i = 0; $i < count($listing_ids); $i++){
			if (!$this->Listing->UserOwnsListing($listing_ids[$i], $this->_getUserId()))
			{
				$error = null;
				$error['listing_id'] = $listing_ids[$i];
				$this->Listing->LogError($this->_getUserId(), 1, $error);
				$this->set('response', json_encode(array('error' => 
					'Failed to delete listing. Contact help@cribspot.com if the error persists. Reference error code 1')));
				return;
			}
		}

		/* "Delete" from listings table (set visible=0) */
		$response = $this->Listing->DeleteListing($listing_ids, $this->_getUserId());
		$this->set('response', json_encode($response));
		return;
	}

	/*
	Returns json-encoded listing
	NOTE: only returns PUBLIC user data
	If $listing_id is null, returns all listings owned by logged-in user
	*/
	function GetListing($listing_id = null)
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		$this->layout = 'ajax';
		if ($listing_id == null){
			/* Return all listings owned by this user. */
			$listings = $this->_getListingsByLoggedInUser();
			$this->set('response', json_encode($listings));
			return;
		}
		
		/* Return the listing given by $listing_id */
		$listing = $this->Listing->GetListing($listing_id);
		if ($listing == null)
			$listing['error'] = array('message' => 'LISTING_ID_NOT_FOUND', 'code' => 5);

		$this->set('response', json_encode($listing));
	}

	/*
	AJAX
	Returns all listings of given listing_type with given marker_id
	*/
	public function LoadMarkerData($listing_type, $marker_id)
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;
		
		$this->layout = 'ajax';
		$listings = $this->Listing->GetMarkerData($listing_type, $marker_id, $this->_getUserId());
		$this->set('response', json_encode($listings));
	}

	/*
	Returns all listing_data for given user_id.
	NOTE: only returns PUBLIC user data
	*/
	private function _getListingsByLoggedInUser()
	{
		$user_id = $this->_getUserId();
		if ($user_id == 0 || $user_id == null){
			return array('error' => 'USER_NOT_LOGGED_IN', 'code' => 3);
		}
		
		$listings = $this->Listing->GetListingsByUserId($user_id);
		if ($listings == null)
			return array('error' => 'FAILED_TO_RETRIEVE_LISTINGS', 'code' => 4);

		return $listings;
	}

	/*
	Convert numeric values or null into their corresponding string values
	*/
	private function _refactorAmenities(&$listing)
	{
		CakeLog::write('viewAmenities', print_r($listing, true));
		$this->_refactorBooleanAmenities($listing);

		$amenities = array('furnished_type', 'washer_dryer', 'parking_type', 'parking_spots', 'pets_type');
		foreach ($amenities as $field){
			if (empty($listing[$field]))
				$listing[$field] = '-';
		}

		if ($listing['furnished_type'] != '-')
			$listing['furnished_type'] = Rental::furnished($listing['furnished_type']);

		if ($listing['washer_dryer'] != '-')
			$listing['washer_dryer'] = Rental::washer_dryer($listing['washer_dryer']);

		if ($listing['parking_type'] != '-')
			$listing['parking_type'] = Rental::parking($listing['parking_type']);	
	}

	private function _refactorBooleanAmenities(&$listing)
	{
		$amenities = array('air', 'tv', 'balcony', 'fridge', 'storage', 'street_parking', 'smoking');
		foreach ($amenities as $field){
			if (array_key_exists($field, $listing)){
				if ($listing[$field] === true)
					$listing[$field] = 'Yes';
				else if ($listing[$field] === false)
					$listing[$field] = 'No';
				else
					$listing[$field] = '-';
			}
		}
	}

	private function _refactorMoneyFields(&$listing)
	{
		if (array_key_exists("Rental", $listing))
		{
			$money_fields = array('rent', 'extra_occupant_amount', 'parking_amount', 'furniture_amount', 
				'amenity_amount', 'upper_floor_amount', 'deposit_amount', 'admin_amount');
			$monthly_fees = array('rent', 'extra_occupant_amount', 'parking_amount', 'furniture_amount', 
				'amenity_amount', 'upper_floor_amount');
			$listing_type = "Rental";
		}

		$listing[$listing_type]["total_fees"] = 0;
		foreach ($monthly_fees as $fee) {
			if (array_key_exists($fee, $listing[$listing_type]) && $listing[$listing_type][$fee] != 0)
					$listing[$listing_type]["total_fees"] += intval($listing[$listing_type]["rent"]);
		}

		$listing[$listing_type]["total_fees"] = "$" . number_format($listing[$listing_type]["total_fees"]);

		foreach ($money_fields as $field)
		{
			if (array_key_exists($field, $listing[$listing_type]))
			{
				if ($listing[$listing_type][$field] != 0)
				{
					// add $ and commas appropriately
					$listing[$listing_type][$field] = "$" . number_format($listing[$listing_type][$field]);
				}
				else
				{
					$listing[$listing_type][$field] = "-";
				}
			}
			else
				$listing[$listing_type][$field] = "??";
		}
	}

	private function _refactorTextFields(&$listing)
	{
		if (array_key_exists("Rental", $listing))
		{
			$text_fields = array('description', 'highlights');
			$listing_type = "Rental";
		}

		foreach ($text_fields as $field) {
			if (!array_key_exists($field, $listing[$listing_type]) || 
				$listing[$listing_type][$field] == null || strlen($listing[$listing_type][$field]) == 0)
				$listing[$listing_type][$field] = "The user has not entered " . $field . " yet.";

		}

	}

	private function _refactorOwnerFields(&$listing)
	{
		if (!array_key_exists("company_name", $listing["User"]))
			$listing["User"]["company_name"] = $listing["User"]["first_name"] . " " . $listing["User"]["last_name"];
	}

	private function _setPrimaryImage(&$listing)
	{
		$length = count($listing["Image"]);
		for ($i=0; $i < $length; $i++)
		{
			if ($listing["Image"][$i]["is_primary"])
			{
				$listing["primary_image"] = $i;
				break;
			}
		}
	}
}

?>