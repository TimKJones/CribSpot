<?php

class ListingsController extends AppController {
	public $uses = array('Listing', 'Rental', 'Sublet', 'Image', 'Favorite', 'University', 'NewspaperAdmin', 'UniversityAdmin',
		'FeaturedPM', 'User', 'LoginCode');

	public $components= array('Session', 'Cookie');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('view');
		$this->Auth->allow('GetListing');
		$this->Auth->allow('GetListingsByLoggedInUser');
		$this->Auth->allow('GetOwnedListingsByMarkerId');
		$this->Auth->allow('LoadMarkerData');
		$this->Auth->allow('Save');
		$this->Auth->allow('Delete');
		$this->Auth->allow('GetFeaturedPMListings');
		$this->Auth->allow('SetAvailabilityFromEmail');
		$this->Auth->allow('APIGetListing');
		$this->Auth->allow('APIGetListingsByMarkerId');
		$this->Auth->allow('ApplyFilter');
	}

	/*					
	View a full page listing. Grabs the listing data and modifies it for
	the full page listing view (view.ctp)
	*/
	public function view($listing_id = null, $address = null)
	{
		if ($listing_id == null)
		{
			$flash_message['method'] = "Error";
			$flash_message['message'] = "Sorry - That listing no longer exists!";
			$json = json_encode($flash_message);
			$this->Cookie->write('flash-message', $json);
			$this->redirect(array('controller' => 'landing', 'action' => 'index'), 301);
		}

		$AuthUser = null;
		$email = null;
		if ($this->Auth->User())
		{
			$user = $this->Auth->User();
			$email = $user['email'];
			$AuthUser = $this->Auth->User('id');
		}

		$this->set('AuthUser', $AuthUser);
		$this->set('user_email', $email);
		
		$listing = $this->Listing->GetListing($listing_id);
		
		if (!array_key_exists(0, $listing) || $listing === null || !array_key_exists("Listing", $listing[0]) || empty($listing[0]["Listing"]["visible"]))
		{
			$flash_message['method'] = "Error";
			$flash_message['message'] = "Sorry - That listing no longer exists!";
			$json = json_encode($flash_message);
			$this->Cookie->write('flash-message', $json);
			$this->redirect(array('controller' => 'landing', 'action' => 'index'), 301);
		}
		
		$listing = $listing[0];

		$full_address = $listing["Marker"]["street_address"];
		$full_address .= " " . $listing["Marker"]["city"];
		$full_address .= " " . $listing["Marker"]["state"];
		$full_address .= " " . $listing["Marker"]["zip"];
		$full_address = str_replace(" ", "-", $full_address);

		if ($address === null)
		{
			$this->redirect(array('action' => 'view', $listing_id, $full_address), 301);
		}




		$listing['Marker']['building_type_id'] = $this->Rental->building_type($listing['Marker']['building_type_id']);

		/* set whether or not this is a favorited property */
		$listing['Favorite'] = false;
		if ($this->_getUserId() !== null)
		{
			$favorites = $this->Favorite->GetFavoritesListingIds($this->_getUserId());
			$listing['Favorite'] = in_array($listing_id, $favorites);
		}

		/* Set the directive - i.e. go right to the contact owner text box being opened */
		$directive = $this->Cookie->read('fullpage-directive');
 		$this->Cookie->delete('fullpage-directive');
 		if($directive == null){
 			$directive = array('contact_owner'=>null);
 		}

 		$listing_type = 'Rental';
 		if (array_key_exists('Sublet', $listing))
 			$listing_type = 'Sublet';

 		$this->_refactorMoneyFields($listing, $listing_type);
		$this->_refactorTextFields($listing, $listing_type);
		$this->_refactorAmenities($listing, $listing_type);
		CakeLog::write('Amenities', print_r($listing, true));
		$this->_setPrimaryImage($listing);
		$this->_refactorDates($listing, $listing_type);
		if (array_key_exists('Image', $listing)){
			$this->_setImagePathsForFullPageView($listing['Image']);
		}

		if (array_key_exists('Sublet', $listing)){
			$this->_viewSublet($listing);
			$this->set('listing_type', 'Sublet');
		}
		else{
			$this->_viewRental($listing);
			$this->set('listing_type', 'Rental');
		}

		$this->set('listing_json', json_encode($listing));
		$this->set('directive', json_encode($directive));
		$this->set('listing', $listing);
		$this->set('full_address', $full_address);
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
			$images = array('Image' => $listingObject['Image']);
		}

		$user = $this->Auth->User();
		$user_id = $this->Auth->User('id');
		/* if this is a university_admin, don't save their user_id */
		if ($this->UniversityAdmin->GetByUserId($user_id) != null)
			$user_id = null;

		$response = $this->Listing->SaveListing($listingObject, $user_id);
		if (!array_key_exists('error', $response) && 
			array_key_exists('listing_id', $response) && 
			$images != null) {
			// Update images that bad been saved before listing_id was known
			$imageResponse = $this->Image->UpdateAfterListingSave($response['listing_id'], $images, $this->_getUserId());
			if (array_key_exists('error', $imageResponse))
				$response['error'] = $imageResponse['error'];
		}

		/* 
		Overwrite basicdata in cache for this listing
		*/
		if (array_key_exists('listing', $response) && array_key_exists('Listing', $response['listing']) &&
			array_key_exists('listing_id', $response['listing']['Listing'])){
			$listing_id = $response['listing']['Listing']['listing_id'];
			if (!Cache::read('ListingBasicData-'.$listing_id)){
				/* 
				this listing doesn't exist in cache yet.
				need to add it, then find its closest universities
				*/
				$listing = $response['listing'];
				Cache::write('ListingBasicData-'.$listing_id, $listing, 'MapData');
				$universities = $this->University->getSchools();
				$this->Listing->CacheListingBasicDataForClosestUniversities($universities, $listing);
			}
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
Returns a list of marker_ids that will be visible based on the current filter settings.
*/
	public function ApplyFilter($listing_type)
	{
		if(!$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		if ($this->params == null || !array_key_exists('url', $this->params))
			return;

		$this->layout = 'ajax';
		$filterSettings = $this->params['url'];
		$listing_type = $this->Listing->listing_type($listing_type);
		$response = $this->Listing->GetMarkerIdList($listing_type, $filterSettings);
		$this->set('response', $response);
	}

	/*
	Returns all marker data by the logged in user
	If this user is a university admin, returns all listings close to that university
	*/
	public function GetMarkerDataByLoggedInUser()
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;
		
		$this->layout = 'ajax';
		$user_id = $this->Auth->User('id');
		if ($user_id === null){
			$error = "USER_NOT_LOGGED_IN";
			$this->Listing->LogError($user_id, 61, $error);
			$this->set('response', json_encode(array('error' => 'USER_NOT_LOGGED_IN')));
			return; 
		}

		$markers = null;
		/* If this user is a university admin, return all listings for their university */
		App::Import('model', 'User');	
		App::Import('model', 'Listing');
		if ($this->Auth->User('user_type') == User::USER_TYPE_UNIVERSITY_ADMIN){
			$admin = $this->UniversityAdmin->GetByUserId($user_id);
			if (!array_key_exists('UniversityAdmin', $admin) || !array_key_exists('university_id', $admin['UniversityAdmin'])) {
				$error = array();
				$error['UniversityAdmin'] = $admin;
				$this->Listing->LogError($user_id, 65, $error);
				$this->set('response', json_encode(array('error' => "There was an error retrieving your university's listings")));
				return; 
			}

			$lat_long = $this->University->getTargetLatLong($admin['UniversityAdmin']['university_id']);
			$markers = $this->Listing->GetBasicDataCloseToPoint(Listing::LISTING_TYPE_RENTAL, $lat_long, $this->Listing->RADIUS);
		} else {
			$markers = $this->Listing->GetBasicMarkerDataByUser($user_id);
		}

		$this->set('response', json_encode($markers));
	}

	/*
	Returns all data for the given marker_id.
	Only returns this data if owned by the logged-in user.
	*/
	public function GetOwnedListingsByMarkerId($marker_id)
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		$this->layout = 'ajax';
		$user_id = $this->Auth->User('id');
		App::Import('model', 'User');
		$is_university_admin = ($this->Auth->User('user_type') == User::USER_TYPE_UNIVERSITY_ADMIN);
		if ($user_id === 0 || (!$this->Listing->UserOwnsAListingAtMarkerId($user_id, $marker_id)) && !$is_university_admin) {
				$error = null;
				$error['marker_id'] = $marker_id;
				$this->Listing->LogError($user_id, 60, $error);
				$this->set('response', json_encode(array('error' => 
					'We had some problems loading your listing. Chat with us using the tab along the bottom of the screen ' .
					'or contact help@cribspot.com if the error persists. Reference error code 60')));
				return; 
		}

		/* Fetch all listing data for this marker_id, user_id combo */
		if ($is_university_admin)
			$user_id = null;
		$listings = $this->Listing->GetListingsByMarkerId($marker_id, $user_id);
		$this->set('response', json_encode($listings));
		return;
	}

	/*
	Returns json-encoded listing
	NOTE: only returns PUBLIC user data
	If $listing_id is null, returns all listings owned by logged-in user
	unless the user is a newspaper admin in which case they are returned
	all the listings near their university
.	*/
	public function GetListing($listing_id = null)
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		$this->layout = 'ajax';
		$listings = null;
		if ($listing_id == null){
			
			$newspaper_admin = $this->NewspaperAdmin->getByUserId($this->_getUserId());
			
			if($newspaper_admin != null){
				$tll = $this->University->getTargetLatLong($newspaper_admin['NewspaperAdmin']['university_id']);
				$options['fields'] = array("Listing.listing_id, Listing.marker_id, Marker.marker_id, Marker.street_address, Marker.alternate_name, Listing.user_id, Listing.listing_type, Rental.unit_style_type, Rental.unit_style_description" );
				$this->Listing->contain("Marker", "Rental");
				$listings = $this->Listing->GetListingsCloseToPoint($tll['latitude'], $tll['longitude'], $this->Listing->RADIUS, Listing::LISTING_TYPE_RENTAL);
			}else{
				/* Return all listings owned by this user. */
				$listings = $this->_getListingsByLoggedInUser();
			}


			$this->set('response', json_encode($listings));
			return;
		}
		
		/* Return the listing given by $listing_id */
		$listings = $this->Listing->GetListing($listing_id);
		if ($listings == null)
			$listings['error'] = array('message' => 'LISTING_ID_NOT_FOUND', 'code' => 5);

		/* Convert unit_style_options to its string value */
		foreach ($listings as &$listing){
			if (array_key_exists('Rental', $listing) && array_key_exists('unit_style_options', $listing['Rental']))
				$listing['Rental']['unit_style_options'] = $this->Rental->unit_style_options($listing['Rental']['unit_style_options']);

			/* HACK TO FIX FEATURED LISTINGS DASH */
			if (array_key_exists('Marker', $listing) && array_key_exists('coordinates', $listing['Marker']))
				unset($listing['Marker']['coordinates']);
		}
		
		$this->set('response', json_encode($listings));
	}

	/*
	Function to redirect user to the property manager's website
	You must be logged in to access the page
	*/
	public function website($listing_id)
	{
		$listing = $this->Listing->GetListing($listing_id);
		if ($listing == null || count($listing) != 1)
			throw new NotFoundException('There is no listing provided!');
		$listing = $listing[0];
		$this->set("response", json_encode($listing));
		if (array_key_exists("Rental", $listing))
		{
			if (array_key_exists("website", $listing["Rental"]) && $listing["Rental"]["website"] != null)
			{
				$url_string = $listing["Rental"]["website"];
				if (strpos($url_string, "http") === false)
					$url_string = "http://" . $url_string;

				$this->redirect($url_string, "301");
			}
			else
				throw new NotFoundException('There is no listing provided!');
		}
		//	$this->redirect($listing["Rental"]["website"], "301");
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
	Returns json_encoded map of user_id => listing_ids for all featured pms for today
	*/
	public function GetFeaturedPMListings($university_id = null)
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		if ($university_id === null)
			return;

		$this->layout = 'ajax';
		$pm_ids = $this->FeaturedPM->GetPMsByUniversityID($university_id);
		CakeLog::write('pms', print_r($pm_ids, true));
		$pmIdToListingIDsMap = $this->Listing->GetPMToListingIdsMap($pm_ids);
		CakeLog::write("map", print_r($pmIdToListingIDsMap, true));
		$this->set('response', json_encode($pmIdToListingIDsMap));
	}


	/* 
	Function called when PM sets availability directly from an email
	URL parameters will include:
	id: user_id of the user
	code: code from the login_codes table used to ensure this is the correct user
	l: listing_id being modified
	a: set to 0 (not available) or 1 (available)
	*/
	public function SetAvailabilityFromEmail()
	{
		/* Check URL credentials */
		if (!array_key_exists('id', $this->request->query) || !array_key_exists('code', $this->request->query) 
			|| !array_key_exists('l', $this->request->query) || !array_key_exists('a', $this->request->query))
				$this->redirect('/login');

		$listing_id = $this->request->query['l'];
		$user_id = $this->request->query['id'];
		$code = $this->request->query['code'];
		$available = $this->request->query['a'];

		/* 
		Initialize error message and alert method (type of message user sees following this action's completion 
		The method and message will be modified based on the success or failure of this request
		*/
		$errorMessage = null;
		$method = 'Error';
		$redirect_url = '/users/login?invalid_link=true';
		$valid = $this->LoginCode->IsValidLoginCode($user_id, $code);
		if (array_key_exists('error', $valid)){
            $errorMessage = "That link is invalid. Let us know in the chat along the bottom of the screen if you think we messed up!";
            if (!strcmp($valid['error'], 'LOGIN_CODE_EXPIRED'))
                $errorMessage = "That link is over 2 weeks old and has expired. Log in to update availabilities from your dashboard.";
        } 
        else if (!$this->Listing->UserOwnsListing($listing_id, $user_id)){
        	/* Make sure user owns this listing */
            $errorMessage = "That link is invalid. Let us know in the chat along the bottom of the screen ".
            "if you think we messed up!";
		} else {
			/* Update availability */
			$response = $this->Listing->SetAvailable($listing_id, $available);
			if (array_key_exists('error', $response))
				$errorMessage = $response['error'];
		}

		/* Availability updated */
		if ($errorMessage === null){
			$method = 'Success';
			$errorMessage = "Successfully updated your listing's availability!";
			/* Log in the user */
			$user = $this->User->get($user_id);
	        $this->User->VerifyEmail($user_id);
	        $this->_login($user);
			$redirect_url = '/dashboard?updated_listing=true&l_id='.$listing_id.'&a='.$available;
		}

		$flash_message['method'] = $method;
        $flash_message['message'] = $errorMessage;
        $json = json_encode($flash_message);
        $this->Cookie->write('flash-message', $json);
        $this->redirect($redirect_url);
	}

/* ----------------------------------- iPhone API ------------------------------------- */
	public function APIGetListing($listing_id)
	{
		$this->layout = 'ajax';
		$listing = null;
		if (array_key_exists('token', $this->request->query) &&
			!strcmp($this->request->query['token'], Configure::read('IPHONE_API_TOKEN'))) {
			header('Access-Control-Allow-Origin: *');
			$listing = $this->Listing->GetListing($listing_id);
			$listing = json_encode($listing);
		}
	
		$this->set('response', $listing);
	}

	public function APIGetListingsByMarkerId($marker_id)
	{
		$this->layout = 'ajax';
		$listings = null;
		if (array_key_exists('token', $this->request->query) &&
			!strcmp($this->request->query['token'], Configure::read('IPHONE_API_TOKEN'))) {
			header('Access-Control-Allow-Origin: *');
			$listings = $this->Listing->GetListingsByMarkerId($marker_id);
			$listings = json_encode($listings);
		}

		$this->set('response', $listings);
	}

/* ----------------------------------- private ---------------------------------------- */

	private function _viewRental(&$listing)
	{
		/* use email_exists to determine whether or not to enable contacting the user */
		$email_exists = !empty($listing['User']['email']);
		$phone_exists = !empty($listing['Rental']['contact_phone']);
		if (empty($listing['Rental']['contact_phone']) && !empty($listing['User']['phone']))
			$listing['Rental']['contact_phone'] = $listing['User']['phone'];

		$this->_refactorOwnerFields($listing, 'Rental');		
		$this->set('email_exists', 1 * $email_exists);
		$this->set('messaging_enabled', $email_exists || $phone_exists);

	}

	private function _viewSublet(&$listing)
	{
		$email_exists = true;
		$this->set('email_exists', 1 * $email_exists);
		$this->set('messaging_enabled', true);
		if (intval($listing['Sublet']['bathroom_type']) === $this->Sublet->BATHROOM_TYPE_SHARED)
			$listing['Sublet']['bathroom_type'] = 'No';
		if (intval($listing['Sublet']['bathroom_type']) === $this->Sublet->BATHROOM_TYPE_PRIVATE)
			$listing['Sublet']['bathroom_type'] = 'Yes';

		$fields = array('furnished_type', 'washer_dryer');
		$listing['Sublet']['furnished_type'] = $this->Rental->furnished($listing['Sublet']['furnished_type']);
		$listing['Sublet']['washer_dryer'] = $this->Rental->washer_dryer($listing['Sublet']['washer_dryer']);

		/* Format the parking and utilities descriptions */
		$fields_to_descriptions_map = array(
			array(
				'included' => 'parking_available',
				'description' => 'parking_description'
			),
			array(
				'included' => 'utilities_included',
				'description' => 'utilities_description'
			)
		);

		foreach($fields_to_descriptions_map as $map){
			if (!empty($listing['Sublet'][$map['included']]) && $listing['Sublet'][$map['included']] === true)
				$listing['Sublet'][$map['description']] = 'Yes - '.$listing['Sublet'][$map['description']];
			else
				$listing['Sublet'][$map['description']] = 'No';
		}
	}

	private function _setImagePathsForFullPageView(&$images)
	{
		foreach ($images as &$image){
			if (array_key_exists('image_path', $image)){
				$fileName = 'lrg_'.$this->_getFileNameFromPath($image['image_path']);
				$directory = $this->_getDeepestDirectoryFromPath($image['image_path']);
				$image['image_path'] = $directory.'/'.$fileName;
			}
		}
	}

	/*
	Returns the file name given the full path to the file
	*/
	private function _getFileNameFromPath($image_path)
	{
		return substr($image_path, strrpos($image_path, '/') + 1);
	}

	/*
	Returns the deepest directory from a given path.
	*/
	private function _getDeepestDirectoryFromPath($path)
	{
		return substr($path, 0, strrpos($path, '/'));
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
	private function _refactorAmenities(&$listing, $listing_type)
	{
		$this->_refactorBooleanAmenities($listing, $listing_type);

		$amenities = array();
		if (!strcmp($listing_type, 'Rental'))
			$amenities = array('furnished_type', 'washer_dryer', 'parking_type', 'parking_spots', 'pets_type');
		foreach ($amenities as $field){
			if (empty($listing[$listing_type][$field]))
				$listing[$listing_type][$field] = '-';
		}

		if ($listing['furnished_type'] !== '-')
			$listing[$listing_type]['furnished_type'] = $this->Rental->furnished($listing[$listing_type]['furnished_type']);

		if ($listing[$listing_type]['washer_dryer'] !== '-')
			$listing[$listing_type]['washer_dryer'] = $this->Rental->washer_dryer($listing[$listing_type]['washer_dryer']);

		if ($listing[$listing_type]['parking_type'] !== '-')
			$listing[$listing_type]['parking_type'] = $this->Rental->parking($listing[$listing_type]['parking_type']);	
	}

	private function _refactorBooleanAmenities(&$listing, $listing_type)
	{
		$amenities = null;
		if (!strcmp($listing_type, 'Rental'))
			$amenities = array('air', 'tv', 'balcony', 'fridge', 'storage', 'street_parking', 'smoking');
		else if (!strcmp($listing_type, 'Sublet'))
			$amenities = array('air');

		foreach ($amenities as $field){
			if (array_key_exists($field, $listing[$listing_type])) {
				if ($listing[$listing_type][$field] === true || intval($listing[$listing_type][$field]) > 0)
					$listing[$listing_type][$field] = 'Yes';
				else if ($listing[$listing_type][$field] === false)
					$listing[$listing_type][$field] = 'No';
				else
					$listing[$listing_type][$field] = '-';
			}
		}
	}


	private function _refactorMoneyFields(&$listing, $listing_type)
	{
		$money_fields = $monthly_fees = null;
		if (!strcmp($listing_type, 'Rental')){
			$money_fields = array('rent', 'extra_occupant_amount', 'parking_amount', 'furniture_amount', 
				'amenity_amount', 'upper_floor_amount', 'deposit_amount', 'admin_amount');
			$monthly_fees = array('rent', 'extra_occupant_amount', 'parking_amount', 'furniture_amount', 
				'amenity_amount', 'upper_floor_amount');
		}
		else if (!strcmp($listing_type, 'Sublet')){
			$money_fields = array('rent');
			$monthly_fees = array('rent');
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

	private function _refactorTextFields(&$listing, $listing_type)
	{
		$text_fields = null;
		if (!strcmp($listing_type, 'Rental')){
			$text_fields = array('description', 'highlights');
		}
		else if (!strcmp($listing_type, 'Sublet')){
			$text_fields = array('description');
		}

		foreach ($text_fields as $field) {
			if (!array_key_exists($field, $listing[$listing_type]) || 
				$listing[$listing_type][$field] == null || strlen($listing[$listing_type][$field]) == 0)
				$listing[$listing_type][$field] = "The user has not entered a " . $field . " yet.";

		}

	}

	private function _refactorOwnerFields(&$listing)
	{
		if (!array_key_exists("company_name", $listing["User"]))
			$listing["User"]["company_name"] = $listing["User"]["first_name"] . " " . $listing["User"]["last_name"];

		if (array_key_exists("contact_phone", $listing["Rental"]) && $listing["Rental"]["contact_phone"] != null)
		{
			$phone = $listing["Rental"]["contact_phone"];
			$listing["Rental"]["contact_phone"] = "(" . substr($phone, 0, 3) . ") " . substr($phone, 3, 3) . "-" . substr($phone, 6, 4);
		}
	}

	private function _refactorDates(&$listing, $listing_type)
	{
		if (!strcmp('Sublet', $listing_type)){
			$date_fields = array('start_date', 'end_date');
			foreach ($date_fields as &$field){
				$date = strtotime($listing[$listing_type][$field]);
				$month = date('M', $date);
		        $day = date('j', $date);
		        $year = date('Y', $date);
				$listing[$listing_type][$field] = $month . " " . intval($day) . ", " . $year;
			}

			$listing['Sublet']['formatted_date_range'] = $listing['Sublet']['start_date'].' - '.$listing['Sublet']['end_date'];
		}
	}

	private function _setPrimaryImage(&$listing)
	{
		$length = count($listing["Image"]);
		// Default to the first image if there is no primary image set
		if ($length > 0)
			$listing["primary_image"] = 0;

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
