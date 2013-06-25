<?php 

class Rental extends AppModel {
	public $name = 'Rental';
	public $primaryKey = 'rental_id';
	public $hasOne = array('Listing');
	public $validate = array(
		'rental_id' => 'numeric',
		'listing_id' => 'numeric',
		'street_address' => array(
			'between' => array(
				'rule' => array('between', 1, 255)
			)
		),
		'city' => array(
			'between' => array(
				'rule' => array('between', 1, 255)
			)
		),
		'state' => array(
			'between' => array(
				'rule' => array('between',2, 2),
				'message' => 'Must be 2 characters'
			)
		),
		'zipcode' => array(
        	'rule' => array('postal', null, 'us')
    	),
		'unit_style_options' => 'alphaNumeric',
		'unit_style_type' => 'alphaNumeric',
		'unit_style_description' => 'alphaNumeric',
		'building_name' => array(
			'between' => array(
				'rule' => array('between',0,100),
				'message' => 'Must be less than 100 characters'
			)
		),
		'beds' => 'numeric',
		'min_occupancy' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'max_occupancy' => 'numeric',
		'building_type' => 'numeric',
		'rent' => 'numeric', /*this is total rent, not per person */
		'rent_negotiable' => 'boolean',
		'unit_count' => 'numeric',
		'start_date' => 'date',
		'alternate_start_date' => 'date',
		'lease_length' => 'numeric', /* in months */
		'available' => 'boolean',
		'baths' => 'numeric',
		'air' => 'boolean',
		'parking_type' => 'numeric',
		'parking_spots' => 'numeric',
		'street_parking' => 'boolean',
		'furnished_type' => 'numeric',
		'pets_type' => 'numeric',
		'smoking' => 'boolean',
		'square_feet' => 'numeric',
		'year_built' => 'numeric',
		'electric' => 'numeric',
		'water' => 'numeric',
		'gas' => 'numeric',
		'heat' => 'numeric',
		'sewage' => 'numeric',
		'trash' => 'numeric',
		'cable' => 'numeric',
		'internet' => 'numeric',
		'utility_total_flat_rate' => 'numeric',
		'utility_estimate_winter' => 'numeric',
		'utility_estimate_summer' => 'numeric',
		'deposit' => 'numeric',
		'highlights' => array(
			'between' => array(
				'rule' => array('between',0,160),
				'message' => 'Must be less than 160 characters'
			)
		),
		'description' => array(
			'between' => array(
				'rule' => array('between',0,1000),
				'message' => 'Must be less than 1000 characters'
			)
		),
		'waitlist' => 'boolean',
		'waitlist_open_date' => 'date',
		'lease_office_address' => array(
			'between' => array(
				'rule' => array('between', 0, 100),
				'message' => 'Must be less than 100 characters'
			)
		),
		'contact_email' => 'email',
		'contact_phone' => array(
			'rule' => array('phone', null, 'us')
		),
		'website' => 'url',
		'is_complete' => 'boolean'
	);

	/* ---------- unit_style_options ---------- */
	const UNIT_STYLE_OPTIONS_STYLE = 0;
	const UNIT_STYLE_OPTIONS_UNIT = 1;
	const UNIT_STYLE_OPTIONS_ENTIRE_UNIT = 2;

	public static function unit_style_options($value = null) {
		$options = array(
		    self::UNIT_STYLE_OPTIONS_STYLE => __('Style',true),
		    self::UNIT_STYLE_OPTIONS_UNIT => __('Unit',true),
		    self::UNIT_STYLE_OPTIONS_ENTIRE_UNIT => __('Entire Unit',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- air ---------- */
	const AIR_CENTRAL = 0;
	const AIR_WALL_UNIT = 1;
	const AIR_NONE= 2; 

	public static function air($value = null) {
		$options = array(
		    self::AIR_CENTRAL => __('Central',true),
		    self::AIR_WALL_UNIT => __('Wall Unit',true),
		    self::AIR_NONE => __('None',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- parking ---------- */
	const PARKING_PARKING_LOT = 0;
	const PARKING_DRIVEWAY = 1;
	const PARKING_GARAGE = 2;
	const PARKING_OFF_SITE = 3;

	public static function parking($value = null) {
		$options = array(
		    self::PARKING_PARKING_LOT => __('Parking Lot',true),
		    self::PARKING_DRIVEWAY => __('Driveway',true),
		    self::PARKING_GARAGE => __('Garage',true),
		    self::PARKING_OFF_SITE => __('Off Site',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- furnished ---------- */
	const FURNISHED_FULLY = 0;
	const FURNISHED_PARTIALLY = 1;
	const FURNISHED_NO = 2;

	public static function furnished($value = null) {
		$options = array(
		    self::FURNISHED_FULLY => __('Fully',true),
		    self::FURNISHED_PARTIALLY => __('Partially',true),
		    self::FURNISHED_NO => __('No',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- pets ---------- */
	const PETS_CATS_ONLY = 0;
	const PETS_DOGS_ONLY = 1;
	const PETS_CATS_AND_DOGS = 2;

	public static function pets($value = null) {
		$options = array(
		    self::PETS_CATS_ONLY => __('Cats Only',true),
		    self::PETS_DOGS_ONLY => __('Dogs Only',true),
		    self::PETS_CATS_AND_DOGS => __('Cats and Dogs',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- utilities_included ---------- */
	const UTILITY_INCLUDED_NO = 0;
	const UTILITY_INCLUDED_YES = 1;
	const UTILITY_INCLUDED_FLAT_RATE = 2;

	public static function utilities_included($value = null) {
		$options = array(
		    self::UTILITY_INCLUDED_NO => __('No',true),
		    self::UTILITY_INCLUDED_YES => __('Yes',true),
		    self::UTILITY_INCLUDED_FLAT_RATE => __('Flat Rate',true),
		);
		return parent::enum($value, $options);
	}

	/* ------------------------------------------*/

	/*
	Marks the rental as either complete or incomplete, depending on whether all fields have been filled in.
	Then it saves the rental.
	REQUIRES: it has been verified that user is logged-in.
	*/
	public function SaveRental($rental, $user_id)
	{
		if ($rental == null || $user_id == null || $user_id == 0)
			return array("error" => "User not logged in");

		$rental = $this->_markAsSaved($rental);
		CakeLog::write("RentalSave", print_r($rental, true));
		$rentalWrapper['Rental'] = $rental;
		if ($this->save($rentalWrapper))
			return array("success" => "");
		else
		{
			CakeLog::write("RentalSaveValidationErrors", print_r($this->validationErrors, true));
			return array("error" => "it didn't work");
		}
	}

	/*
	Delete the rental with rental_id = $rental_id.
	REQUIRES: it has been verified that the logged-in user owns this rental.
	*/
	public function Delete ($rental_id)
	{

	}

	/*
	Marks $rental (via the 'is_complete' field) as complete or incomplete by checking for all required fields.
	Returns modified $rental object;
	*/
	private function _markAsSaved($rental)
	{
		$rental['is_complete'] = 1;

		/* 
		Remove entries from array that are null so that cakephp won't complain.
		They will be set to null by default in the rentals table.
		*/
		foreach ($rental as $key => $value)
		{
			if ($rental[$key] == null)
				unset($rental[$key]);
		}

		return $rental;
	}
}

?>