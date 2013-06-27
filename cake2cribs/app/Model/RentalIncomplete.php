<?php 

/*
Table for storing rentals that are not yet complete.
To allow for this, it has loose restrictions for what can be saved.
*/

App::import('Model', "RentalPrototype");
class RentalIncomplete extends RentalPrototype {
	public $name = 'RentalIncomplete';
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
				'rule' => array('between', 0, 255)
			)
		),
		'city' => array(
			'between' => array(
				'rule' => array('between', 0, 255)
			)
		),
		'state' => array(
			'between' => array(
				'rule' => array('between', 0, 2)
			)
		),
		'zipcode' => array(
        	'between' => array(
				'rule' => array('between', 0, 15)
			)
    	),
		'unit_style_options' => array(
			'rule' => 'alphaNumeric',
        	'required' => false
		),
		'unit_style_type' => array(
			'rule' => 'alphaNumeric',
        	'required' => false
		),
		'unit_style_description' => array(
			'between' => array(
				'rule' => array('between', 0, 255)
			)
		),
		'building_name' => array(
			'between' => array(
				'rule' => array('between', 0, 255)
			)
		),
		'beds' => array(
			'rule' => 'alphaNumeric',
        	'required' => false
		),
		'min_occupancy' => array(
			'rule' => 'alphaNumeric',
        	'required' => false
		),
		'max_occupancy' => array(
			'rule' => 'alphaNumeric',
        	'required' => false
		),
		'building_type' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'rent' => array(  /*this is total rent, not per person */
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
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
				'required' => false
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
				'required' => false
			)
		),
		'dates_negotiable' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			)
		),
		'available' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			)
		),
		'baths' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'air' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'parking_type' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'parking_spots' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'street_parking' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'furnished_type' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'pets_type' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'washer_dryer' => array(    ////////////////// need a type enum for this
			'boolean' => array(
				'rule' => 'numeric',
				'required' => false
			),
		), 
		'smoking' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'tv' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'balcony' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'fridge' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		), 
		'storage' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		), 
		'square_feet' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'year_built' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'pool' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'hot_tub' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'fitness_center' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'game_room' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'front_desk' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'security_system' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'tanning_beds' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'study_lounge' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'patio_deck' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'yard_space' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'elevator' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'electric' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'water' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'gas' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'heat' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'sewage' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'trash' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'cable' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'internet' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'utility_total_flat_rate' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'utility_estimate_winter' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'utility_estimate_summer' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'deposit' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			),
		),
		'highlights' => array(
			'between' => array(
				'rule' => array('between', 0, 255)
			)
		),
		'description' => array(
			'between' => array(
				'rule' => array('between', 0, 255)
			)
		),
		'waitlist' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		),
		'waitlist_open_date' => array(
			'rule' => 'date',
			'required' => false
		),
		'lease_office_street_address' => array(
			'between' => array(
				'rule' => array('between', 0, 255)
			)
		),
		'lease_office_city' => array(
			'between' => array(
				'rule' => array('between', 0, 255)
			)
		),
		'lease_office_state' => array(
			'between' => array(
				'rule' => array('between', 0, 2)
			)
		),
		'lease_office_zipcode' => array(
        	'rule' => 'alphaNumeric',
        	'required' => false
    	),
		'contact_email' => array(
			'between' => array(
				'rule' => array('between', 0, 255)
			)
		),
		'contact_phone' => array(
			'rule' => 'alphaNumeric',
        	'required' => false
		),
		'website' => array(
			'between' => array(
				'rule' => array('between', 0, 20)
			)
		),
		'is_complete' => array(
			'boolean' => array(
				'rule' => 'boolean',
				'required' => false
			),
		)
	);

	/*
	
	*/
	public function SaveRental($rental)
	{
		if ($rental == null)
			return array("error" => "Rental cannot be null");

		CakeLog::write("RentalIncompleteSave", print_r($rental, true));
		$rental['is_complete'] = 0;
		if ($this->save(array('RentalIncomplete' => $rental)))
			return array('success' => '');
		else
		{
			/* There were validation errors. */
			CakeLog::write("RentalIncompleteSaveValidationErrors", print_r($this->validationErrors, true));
			return array('error' => array('type' => 'TYPE_FATAL'));
		}
	}
}

?>