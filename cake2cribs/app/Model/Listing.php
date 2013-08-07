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
				'required' => true
			)
		),
		'visible' => 'boolean' /* visible is set to false when listing is deleted */
	);

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
			'message' => 'Failed to save listing. Contact help@cribspot.com if the error persists. Reference error code 6'));
	}

	/* returns listing with id = $listing_id */
	public function Get($listing_id)
	{
		$listing = $this->find('all', array(
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
				'message' => 'Failed to save listing. Contact help@cribspot.com if the error persists. Reference error code 2'));
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
			'conditions' => array(
				'Listing.listing_id' => $listing_id,
				'Listing.visible' => 1)
		));

		/* Remove sensitive user data */
		for ($i = 0; $i < count($listing); $i++){
			if (array_key_exists('User', $listing[$i]))
				$listing[$i]['User'] = $this->_removeSensitiveUserFields($listing[$i]['User']);
		}

		return $listing;
	}

	/*
	Returns an array of listings owned by the given user_id
	*/
	public function GetListingsByUserId($user_id)
	{
		$listings = $this->find('all', array(
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

	public function GetBasicData($listing_type)
	{
		if ($listing_type == Listing::LISTING_TYPE_RENTAL)
			return $this->_getRentalBasicData();
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

	private function _getRentalBasicData()
	{
		$this->contain('Rental');
		$options = array();
		$options['fields'] = array(
			'Rental.rent',
			'Rental.listing_id',
			'Rental.beds', 
			'Listing.marker_id',
			'Listing.listing_id');
		$options['conditions'] = array('Listing.visible' => 1);
		return $this->find('all', $options);
	}
}	

?>