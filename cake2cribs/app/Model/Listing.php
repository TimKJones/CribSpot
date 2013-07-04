<?php 

class Listing extends AppModel {
	public $name = 'Listing';
	public $primaryKey = 'listing_id';
	public $hasOne = array(
		'Rental' => array(
			'className' => 'Rental',
			'dependent' => true
		)
	);
	public $belongsTo = array(
		'User' => array(
            'className'    => 'User',
            'foreignKey'   => 'user_id',
            'dependent'    => true
        )
	);
	public $validate = array(
		'listing_id' => 'alphaNumeric',
		'listing_type' => 'alphaNumeric',
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

	public function SaveListing($listing_type)
	{
		$newListing = array('Listing' => array('listing_type' => $listing_type));
		if ($this->save($newListing))
			return array('listing_id' => $this->id);

		/* Listing failed to save - return error code */
		CakeLog::write("listingValidationErrors", print_r($this->validationErrors, true));
		return array("error" => "Failed to save listing");
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
}	

?>