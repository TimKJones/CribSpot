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
				$rental_id = $this->Rental->GetRentalIdFromListingId($listing['Listing']['listing_id']);
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
		$this->LogError($user_id, 6, json_encode($error));
		return array("error" => array('validation' => $this->validationErrors,
			'message' => 'Failed to save listing. Contact help@cribspot.com if the error persists. Reference error code 6'));
	}

	/* returns listing with id = $listing_id */
	public function Get($listing_id)
	{
		$listing = $this->find('all', array(
        	'conditions' => array('Listing.listing_id' => $listing_id)
    	));

    	return $listing;
	}

	/*
	Delete the listing with id = $listing_id
	Returns true on success, false otherwise.
	*/
	public function DeleteListing($listing_id)
	{
		$response = $this->delete($listing_id);
		return $response != null;

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
			'conditions' => array('Listing.listing_id' => $listing_id)
		));

		return $listing;
	}

	/*
	Returns an array of listings owned by the given user_id
	*/
	public function GetListingsByUserId($user_id)
	{
		$listings = $this->find('all', array(
			'conditions' => array('Listing.user_id' => $user_id)
		));
		
		return $listings;
	}

	/*
	Returns all listings of given listing_type with given marker_id
	*/
	public function GetMarkerData($listing_type, $marker_id, $user_id)
	{
		$this->contain('Rental', 'User');
		$listings = $this->find('all', array(
			'conditions' => array(
				'Listing.listing_type' => $listing_type,
				'Listing.marker_id' => $marker_id
			)
		));

		if ($listings == null){
			$listings['error'] = array('message' => 'FAILED_TO_RETRIEVE_LISTINGS', 'code' => 7);
			$this->LogError($user_id, 7, 'listing_type: ' . $listing_type . '; marker_id: ' . $marker_id);
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
}	

?>