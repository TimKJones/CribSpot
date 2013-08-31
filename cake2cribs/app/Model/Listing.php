<?php 

class Listing extends AppModel {
	public $name = 'Listing';
	public $primaryKey = 'listing_id';
	public $actsAs = array('Containable');
	public $hasOne = array(
		'Rental' => array(
			'className' => 'Rental',
			'dependent' => true
		)
	);
	public $hasMany = array(
		'Fee' => array(
			'className' => 'Fee',
			'dependent' => true
		),
		'Image' => array(
			'className' => 'Image',
			'dependent' => true
		),
		'Favorite' => array(
			'className' => 'Favorite',
			'dependent' => true
		)
	);
	public $belongsTo = array(
		'User' => array(
            'className'    => 'User',
            'foreignKey'   => 'user_id',
            'dependent'    => true
        ),
        'Marker' => array(
            'className'    => 'Marker',
            'foreignKey'   => 'marker_id',
            'dependent'    => true
        )
	);
	
	public $validate = array(
		'listing_id' => 'numeric',
		'listing_type' => 'numeric',
		'marker_id' => 'numeric',
		'user_id' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'visible' => 'boolean' /* visible is set to false when listing is deleted */
	);

	public $RADIUS = 12; // radius from center (km) encompassing area to pull properties from

	/* ---------- unit_style_options ---------- */
	const LISTING_TYPE_RENTAL = 0;
	const LISTING_TYPE_SUBLET = 1;
	const LISTING_TYPE_PARKING = 2;

	public static function listing_type($value = null) {
		$options = array(
		    self::LISTING_TYPE_RENTAL => __('Rental',true),
		    self::LISTING_TYPE_SUBLET => __('Sublet',true),
		    self::LISTING_TYPE_PARKING => __('Parking',true),
		);
		return parent::enum($value, $options);
	}

	/*
	Attempts to save $listing to the Listing table and any associated tables.
	Returns listing_id of saved listing on success; validation errors on failure.
	*/
	public function SaveListing($listing, $user_id=null)
	{
		$listing['Listing']['user_id'] = $user_id;

		if (array_key_exists('Rental', $listing))
		{
			$listing['Rental'] = $this->_removeNullEntries($listing['Rental']);
			if (array_key_exists('listing_id', $listing['Listing']))
			{
				$rental_id = $this->Rental->GetRentalIdFromListingId($listing['Listing']['listing_id'], $user_id);
				$listing['Rental']['rental_id'] = $rental_id;
			}
		}
		else if (array_key_exists('Sublet', $listing))
			$listing['Sublet'] = $this->_removeNullEntries($listing['Sublet']);
		else if (array_key_exists('Parking', $listing))
			$listing['Parking'] = $this->_removeNullEntries($listing['Parking']);

		/* If alternate_start_date is not present, then set it to an empty string so it overwrites as null */	
		if (!array_key_exists('alternate_start_date', $listing['Rental']))
			$listing['Rental']['alternate_start_date'] = '';
	
		if ($this->saveAll($listing, array('deep' => true)))
		{
			return array('listing_id' => $this->id);
		}

		/* Listing failed to save - return error code */
		$error = null;
		$error['Listing'] = $listing;
		$error['validationErrors'] = $this->validationErrors;
		$this->LogError($user_id, 6, $error);
		return array("error" => array('validation' => $this->validationErrors,
			'message' => 'Looks like we had some problems saving your listing! We want to help! If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 6.'));
	}

	/* returns listing with id = $listing_id */
	public function Get($listing_id)
	{
		$listing = $this->find('first', array(
        	'conditions' => array('Listing.listing_id' => $listing_id)
    	));

		if (array_key_exists('User', $listing))
			$listing['User'] = $this->_removeSensitiveUserFields($listing['User']);

    	return $listing;
	}



	/*
	Mark all listings in $listing_ids as invisible
	Returns true on success, false otherwise.
	*/
	public function DeleteListing($listing_ids, $user_id)
	{
		$listings = array();
		$listings['Listing'] = array();
		for ($i = 0; $i < count($listing_ids); $i++){
			$this->id = $listing_ids[$i];
			if (!$this->saveField('visible', 0)){
				$error = null;
				$error['listings'] = $listings;
				$error['validation'] = $this->validationErrors;
				$this->LogError($user_id, 2, $error);
				return array("error" => array('validation' => $this->validationErrors,
			'message' => 'Looks like we had some problems deleting your listing! We want to help! If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 2.'));
			}
		}

		return array('success' => '');
	}

	/*
	Returns the listing_type for the given listing_id
	*/
	public function GetListingType($listing_id)
	{
		$listingType = $this->find('first', array(
			'fields' => array('Listing.listing_type'),
        	'conditions' => array('Listing.listing_id' => $listing_id)
    	));

		if ($listingType != null)
    		return $listingType['Listing']['listing_type'];
    	else
    		return null;
	}

	/*
	Returns array of listings with ids in listing_ids
	NOTE: Only return non-sensitive user data
	*/
	public function GetListing($listing_id)
	{
		$listing = $this->find('all', array(
			'contain' => array('Image', 'Rental', 'User', 'Marker'),
			'conditions' => array(
				'Listing.listing_id' => $listing_id,
				'Listing.visible' => 1)
		));

		/* Remove sensitive user data */
		/* Convert type fields to their appropriate string values */
		$amenities = array('furnished_type', 'washer_dryer', 'parking_type', 'parking_spots', 'pets_type');
		for ($i = 0; $i < count($listing); $i++){
			if (array_key_exists('User', $listing[$i])){
				$listing[$i]['User'] = $this->_removeSensitiveUserFields($listing[$i]['User']);
			}
			if (array_key_exists('Rental', $listing[$i])){
				foreach ($amenities as $field){
					if (empty($listing[$i]['Rental'][$field]))
						$listing[$i]['Rental'][$field] = '-';
				}
			}
			$listing[$i] = $this->_convertTypesToStrings($listing[$i]);
		}

		return $listing;
	}

	/*
	Converts all integer 'type' fields to their string values
	*/
	private function _convertTypesToStrings($listing)
	{
		if (array_key_exists('Marker', $listing)) {
			if (array_key_exists('building_type_id', $listing['Marker']) &&
				$listing['Marker']['building_type_id'] !== '-')
					$listing['Marker']['building_type_id'] = Rental::building_type($listing['Marker']['building_type_id']);
		}

		if (array_key_exists('Rental', $listing)) {
			if (array_key_exists('parking_type', $listing['Rental']) &&
				$listing['Rental']['parking_type'] !== '-')
					$listing['Rental']['parking_type'] = Rental::parking($listing['Rental']['parking_type']);
			if (array_key_exists('furnished_type', $listing['Rental']) &&
				$listing['Rental']['furnished_type'] !== '-')
					$listing['Rental']['furnished_type'] = Rental::furnished($listing['Rental']['furnished_type']);

			if (array_key_exists('pets_type', $listing['Rental']) &&
				$listing['Rental']['pets_type'] !== '-')
					$listing['Rental']['pets_type'] = Rental::pets($listing['Rental']['pets_type']);

			if (array_key_exists('washer_dryer', $listing['Rental']) &&
				$listing['Rental']['washer_dryer'] !== '-')
					$listing['Rental']['washer_dryer'] = Rental::washer_dryer($listing['Rental']['washer_dryer']);
		}
	
		return $listing;
	}

	/*
	Listing type is a string representation of type (Rental, Sublet...)
	Returns an array of listings that match the given type
	*/
	public function GetListingsByType($listing_type){	
		$listings = $this->find('all', array(
			"conditions" => array('Listing.listing_type' => $listing_type)
		));

		return $listings;
	}

	/*
	Returns an array of listings owned by the given user_id
	*/
	public function GetListingsByUserId($user_id)
	{
		$listings = $this->find('all', array(
			'contain' => array('Image', 'Rental', 'User', 'Marker'),
			'conditions' => array(
				'Listing.user_id' => $user_id,
				'Listing.visible' => 1)
		));

		/* Remove sensitive user data and null fields */
		for ($i = 0; $i < count($listings); $i++){
			if (array_key_exists('User', $listings[$i]))
				$listings[$i]['User'] = $this->_removeSensitiveUserFields($listings[$i]['User']);
			if (array_key_exists('Listing', $listings[$i]))
				$listings[$i]['Listing'] = $this->_removeNullEntries($listings[$i]['Listing']);
			if (array_key_exists('Rental', $listings[$i]))
				$listings[$i]['Rental'] = $this->_removeNullEntries($listings[$i]['Rental']);
			if (array_key_exists('Fee', $listings[$i]))
				$listings[$i]['Fee'] = $this->_removeNullEntries($listings[$i]['Fee']);
			if (array_key_exists('Image', $listings[$i]))
				$listings[$i]['Image'] = $this->_removeNullEntries($listings[$i]['Image']);
			if (array_key_exists('Marker', $listings[$i]))
				$listings[$i]['Marker'] = $this->_removeNullEntries($listings[$i]['Marker']);
	}	

		return $listings;
	}
	/*
	Returns an array of listing ids owned by the user
	*/
	public function GetListingIdsByUserId($user_id){
		$listing_ids = $this->find('list', array(
			'conditions' => array(
				'Listing.user_id' => $user_id,
				'Listing.visible' => 1),
			'fields' => array(
				'Listing.listing_id'
			)
		));
		return array_values($listing_ids);
	}

	/*
	Returns all listing data for the given marker_id and user_id combo
	*/
	public function GetListingsByMarkerId($marker_id, $user_id){
		$conditions = array(
				'Listing.marker_id' => $marker_id,
				'Listing.user_id' => $user_id,
				'Listing.visible' => 1);

		$listings = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array('Marker', 'Image', 'Rental')
		));

		return array_values($listings);
	}

	public function GetListingIdsByMarkerId($marker_id){
		$conditions = array(
				'Listing.marker_id' => $marker_id,
				'Listing.visible' => 1);

		$listing_ids = $this->find('list', array(
			'conditions' => $conditions,
			'fields' => array(
				'Listing.listing_id'
			)
		));
		return array_values($listing_ids);
	}

	/*
	Returns all listings of given listing_type with given marker_id
	*/
	public function GetMarkerData($listing_type, $marker_id, $user_id)
	{
		$this->contain('Rental', 'User', 'Fee');
		$listings = $this->find('all', array(
			'conditions' => array(
				'Listing.listing_type' => $listing_type,
				'Listing.marker_id' => $marker_id
			)
		));

		if ($listings == null){
			$listings['error'] = array('message' => 'FAILED_TO_RETRIEVE_LISTINGS', 'code' => 7);
			$error = null;
			$error['listing_type'] = $listing_type;
			$error['marker_id'] = $marker_id;
			$this->LogError($user_id, 7, $error);
		}

		/* Remove sensitive user data */
		for ($i = 0; $i < count($listings); $i++){
			if (array_key_exists('User', $listings[$i]))
				$listings[$i]['User'] = $this->_removeSensitiveUserFields($listings[$i]['User']);
		}

		return $listings;
	}

	/*
	Returns true if $user_id owns $listing_id; false otherwise
	*/
	public function UserOwnsListing ($listing_id, $user_id)
	{
		if ($user_id == null || $user_id == 0)
			return false;

		$listings = $this->find('first', array(
			'fields' => array('Listing.listing_id'),
			'conditions' => array(
				'Listing.user_id' => $user_id,
				'Listing.listing_id' => $listing_id
			)
		));

		return $listings != null;
	}


	public function ListingExists($listing_id){
		$this->id = $listing_id;
		return $this->exists();
	}
	/*
	Retrieve all data needed for the onHover menu
	Only retrieve for listing type specified in $listing_type
	*/
	public function LoadHoverData($listing_type)
	{
		if ($listing_type == Listing::LISTING_TYPE_RENTAL)
			return $this->_loadRentalHoverData();
		else if ($listing_type == Listing::LISTING_TYPE_SUBLET)
			return $this->_loadSubletHoverData();

		return $this->loadParkingHoverData();
	}

	public function GetBasicData($listing_type, $target_lat_long)
	{
		if ($listing_type == Listing::LISTING_TYPE_RENTAL)
			return $this->_getRentalBasicData($target_lat_long);
		/* Coming soon! 
		else if ($listing_type == Listing::LISTING_TYPE_SUBLET)
			return $this->_loadSubletHoverData();

		return $this->loadParkingHoverData();
		*/
	}

	/*
	Returns true if the user with $user_id owns at least one listing at $marker_id
	*/
	public function UserOwnsAListingAtMarkerId($user_id, $marker_id)
	{
		$listings = $this->find('first', array(
			'fields' => array('Listing.listing_id'),
			'conditions' => array(
				'Listing.user_id' => $user_id,
				'Listing.marker_id' => $marker_id
			)
		));

		return $listings != null;
	}

	/*
		returns an array of Listings, you can provide an optional options
		parameter if you want to further refine the search.
	*/
	public function GetListingsNear($latitude, $longitude, $radius, $options=null){
		
		if($options==null){
			$options = array();
		}

		// Fetch all the nearby markers, they are the source of finding
		// listings nearby
		$Marker = ClassRegistry::init('Marker');
		$options2['fields'] = array("Marker.marker_id");
		$markers = $Marker->getNear($latitude, $longitude, $radius, $options2);

		//Create a list of marker_ids to then find which listings link to them
		$markerIds = array();
		foreach ($markers as $marker) {
			array_push($markerIds, $marker['Marker']['marker_id']);
		}

		return $this->GetListingsFromMarkerIds($markerIds, $options);
	}

	
	/*
	Return

	*/
	public function GetListingsFromMarkerIds($markers, $options){
		if($options==null){
			$options = array();
		}

		// $this->contain();

		if(array_key_exists("conditions", $options)){
			array_push($options['conditions'], array('Listing.marker_id =' => $markers));
		}else{
			$options['conditions'] = array('Listing.marker_id =' => $markers);
		}

		return $this->find('all', $options);


	}

	/*
	Pulls marker_ids for listings in the logged-in users favorites
	*/
	public function GetFavoritesMarkerIds($listingIds)
	{
		$this->contain();	
		$marker_ids = $this->find('all', array(
			'conditions' => array('Listing.listing_id' => $listingIds),
			'fields' => array('Listing.marker_id')));

		$ids = array();
		foreach ($marker_ids as $markerId){
			array_push($ids, $markerId['Listing']['marker_id']);
		}

		return $ids;
	}

	/*
	Returns street_address for given $listing_id
	*/
	public function GetStreetAddressFromListingId($listing_id)
	{
		$listing = $this->find('first', array(
			'fields' => 'Marker.street_address',
			'contains' => array('Marker'),
			'conditions' => array('Listing.listing_id' => $listing_id)
		));

		if ($listing === null)
			return null;

		return $listing['Marker']['street_address'];
	}

	public function GetListingIdFromAddress($address)
	{
		if (!array_key_exists('street_address', $address) ||
			!array_key_exists('city', $address) ||
			!array_key_exists('state', $address))
			return null;

		$listing = $this->find('all', array(
			'fields' => array('Listing.marker_id', 'Listing.user_id'),
			'conditions' => array(
				'Marker.street_address' => $address['street_address'],
				'Marker.city' => $address['city'],
				'Marker.state' => $address['state']
			)
		));

		if ($listing === null)
			return null;

		return $listing;
	}

	public function GetBasicMarkerDataByUser($user_id)
	{
		$this->contain('Marker');
		$markers = $this->find('all', array(
			'conditions' => array(
				'Listing.user_id' => $user_id,
				'Listing.visible' => 1
			),
			'contains' => array('Marker')
		));

		return $markers;
	}

	/*
	return all data needed for a rental hover menu (for all markers)
	*/
	private function _loadRentalHoverData()
	{
		$this->contain('Rental');
		$options = array();
		$options['fields'] = array(
			'MIN(Rental.rent) as min_rent', 
			'MAX(Rental.rent) as max_rent',
			'MIN(Rental.beds) as min_beds', 
			'MAX(Rental.beds) as max_beds',
			'MIN(Rental.baths) as min_baths', 
			'MAX(Rental.baths) as max_baths',
			'Listing.marker_id');
		$options['conditions'] = array('Listing.visible' => 1);
		return $this->find('all', $options);
	}

	/*
	return all data needed for a sublet hover menu (for all markers)
	*/
	private function _loadSubletHoverData()
	{
		$this->contain();
		$options = array();
		$options['fields'] = array('marker_id', 'number_bedrooms', 'price_per_bedroom', 'date_begin', 'date_end');
		$options['conditions'] = array('Sublet.visible' => 1);
		$hover_data = $this->find('all', $options);
	}

	/*
	return all data needed for a parking hover menu (for all markers)
	*/
	private function _loadParkingHoverData()
	{
		$this->contain();
		$options = array();
		$options['fields'] = array('marker_id', 'number_bedrooms', 'price_per_bedroom', 'date_begin', 'date_end');
		$options['conditions'] = array('Sublet.visible' => 1);
		$hover_data = $this->find('all', $options);
	}

	/*
	Returns basic data for all listings within $RADIUS of $target_lat_long
	*/
	private function _getRentalBasicData($target_lat_long)
	{
		$this->contain('Rental', 'Marker');
		$options = array();
		$options['fields'] = array(
			'Rental.rent',
			'Rental.listing_id',
			'Rental.beds',
			'Rental.start_date',
			'Rental.lease_length',
			'Listing.marker_id',
			'Listing.listing_id',
			'Marker.marker_id',
			'Marker.latitude',
			'Marker.longitude',
			'Marker.street_address',
			'Marker.building_type_id',
			'Marker.alternate_name',
			'Marker.city',
			'Marker.state',
			'Marker.zip'
			);
		$options['conditions'] = array('Listing.visible' => 1);
		$basicData = $this->find('all', $options);
		$locationFilteredBasicData = $this->_filterBasicDataByLocation($target_lat_long, $basicData);
		foreach ($locationFilteredBasicData as &$listing) {
			$listing["Marker"]["building_type_id"] = Rental::building_type(intval($listing['Marker']['building_type_id']));
		}

		return $locationFilteredBasicData;
	}

	function distance($lat1,$lon1,$lat2,$lon2) {
	  $R = 6371; // Radius of the earth in km
	  $dLat = deg2rad($lat2-$lat1);  // deg2rad below
	  $dLon = deg2rad($lon2-$lon1); 
	  $a = 
	    sin($dLat/2) * sin($dLat/2) +
	    cos($this->deg2rad($lat1)) * cos($this->deg2rad($lat2)) * 
	    sin($dLon/2) * sin($dLon/2); 
	  $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
	  $d = $R * $c; // Distance in km
	  return $d;
	}

	function deg2rad($deg) {
	  return $deg * (pi()/180);
	}

	private function _filterBasicDataByLocation($target_lat_long, $basicData)
	{
		$filteredBasicData = array();
		for ($i = 0; $i < count($basicData); $i++)
		{
			$lat = $basicData[$i]['Marker']['latitude'];
			$long = $basicData[$i]['Marker']['longitude'];
			$distance = $this->distance($lat, $long, $target_lat_long['latitude'], $target_lat_long['longitude']);
			if ($distance < $this->RADIUS)
			{
				array_push($filteredBasicData, $basicData[$i]);
			}				
		}

		return $filteredBasicData;
	}



}	

?>
