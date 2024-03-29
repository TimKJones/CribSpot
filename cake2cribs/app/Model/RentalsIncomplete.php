<?php 

App::import('Model', "RentalPrototype");
class Rental extends RentalPrototype {
	public $name = 'Rental';
	public $primaryKey = 'rental_id';
	public $hasOne = array('Listing');
	public $validate = array(
		'rental_id' => 'numeric',
		'listing_id' => 'numeric',
		'user_id' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => true
			)
		),
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
		'unit_style_options' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => true
			)
		),
		'unit_style_type' => array(
			'between' => array(
				'rule' => array('between', 1, 20)
			)
		),
		'unit_style_description' => array(
			'between' => array(
				'rule' => array('between', 1, 255)
			)
		),
		'building_name' => array(
			'between' => array(
				'rule' => array('between',0,100),
				'message' => 'Must be less than 100 characters'
			)
		),
		'beds' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => true
			)
		),
		'min_occupancy' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'max_occupancy' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => true
			)
		),
		'building_type' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => true
			)
		),
		'rent' => array(  /*this is total rent, not per person */
			'numeric' => array(
				'rule' => 'numeric',
				'required' => true
			)
		), 
		'rent_negotiable' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'unit_count' => array(  /* Not required - will default to 1 */
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		), 
		'start_date' => array(
			'date' => array(
				'rule' => 'date',
				'required' => true
			)
		),
		'alternate_start_date' => array(
			'date' => array(
				'rule' => 'date',
				'required' => false
			)
		),
		'end_date' => array(
			'date' => array(
				'rule' => 'date',
				'required' => true
			)
		),
		'dates_negotiable' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => true
			)
		),
		'available' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => true
			)
		),
		'baths' => 'numeric',
		'air' => 'boolean',
		'parking_type' => 'numeric',
		'parking_spots' => 'numeric',
		'street_parking' => 'boolean',
		'furnished_type' => 'numeric',
		'pets_type' => 'numeric',
		'washer_dryer' => 'numeric', ////////////////// need a type enum for this
		'smoking' => 'boolean',
		/* ------------- start new fields ----------------*/
		'tv' => 'boolean',
		'balcony' => 'boolean',
		'fridge' => 'boolean', 
		'storage' => 'boolean', 
		/* ------------ end new fields ------------------ */	
		'square_feet' => 'numeric',
		'year_built' => 'numeric',
		/* ------------ start new fields --------------- */
		'pool' => 'boolean',
		'hot_tub' => 'boolean',
		'fitness_center' => 'boolean',
		'game_room' => 'boolean',
		'front_desk' => 'boolean',
		'security_system' => 'boolean',
		'tanning_beds' => 'boolean',
		'study_lounge' => 'boolean',
		'patio_deck' => 'boolean',
		'yard_space' => 'boolean',
		'elevator' => 'boolean',
		/* ------------ end new fields --------------- */
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
		'lease_office_street_address' => array(
			'between' => array(
				'rule' => array('between', 0, 100),
				'message' => 'Must be less than 100 characters'
			)
		),
		'lease_office_city' => array(
			'between' => array(
				'rule' => array('between', 1, 255)
			)
		),
		'lease_office_state' => array(
			'between' => array(
				'rule' => array('between',2, 2),
				'message' => 'Must be 2 characters'
			)
		),
		'lease_office_zipcode' => array(
        	'rule' => array('postal', null, 'us')
    	),
		'contact_email' => 'email',
		'contact_phone' => array(
			'rule' => array('phone', null, 'us')
		),
		'website' => 'url',
		'is_complete' => 'boolean'
	);

	/*
	Marks the rental as either complete or incomplete, depending on whether all fields have been filled in.
	Then it saves the rental.
	REQUIRES: it has been verified that user is logged-in.
	*/
	public function SaveRental($rental)
	{
		if ($rental == null)
			return array("error" => "Rental cannot be null");

		$validationErrors = null;

		// Remove fields with null values so cake doesn't complain (they will be saved to null as default)
		$rental = parent::_removeNullEntries($rental);
		//CakeLog::write("RentalSave", print_r($rental, true));
		$rental['is_complete'] = 1;
		if ($this->save(array('Rental' => $rental)))
			return array('success' => '');
		else
		{
			/* 
			There were validation errors.
			Remove failed keys and try to save again.
			The failed keys will be saved as null values and is_complete will be set to 0.
			*/
			CakeLog::write("RentalSaveValidationErrors", print_r($this->validationErrors, true));
			$rental['is_complete'] = 0;
			$validationErrors = $this->validationErrors;
			$rental = parent::_removeFailedKeys($rental, $validationErrors);
			/* Now try and save again having removed the failed keys */
			if ($this->save(array('Rental' => $rental)))
				return array('error' => array(
					'columns' => $validationErrors,
					'type' => 'TYPE_VALIDATION')
				);
			else
			{
				CakeLog::write("RentalSaveSecondAttempt", print_r($rental, true));
				CakeLog::write("RentalSaveSecondAttemptErrors", print_r($this->validationErrors, true));
				return array('error' => array('type' => 'TYPE_FATAL'));
			}
		}
	}
}

?>