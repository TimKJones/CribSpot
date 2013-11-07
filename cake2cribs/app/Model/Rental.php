<?php 

App::import('Model', "RentalPrototype");
class Rental extends RentalPrototype {
	public $name = 'Rental';
	public $primaryKey = 'rental_id';
	public $belongsTo = array(
		'Listing' => array(
            'className'    => 'Listing',
            'foreignKey'   => 'listing_id'
        )
	);
	public $actsAs = array('Containable');
	public $validate = array(
		'rental_id' => 'numeric',
		'listing_id' => 'numeric',
		'unit_style_options' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
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
				'required' => false
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
				'rule' => array('date', 'ymd'),
				'required' => false
			)
		),
		'alternate_start_date' => array(
			'date' => array(
				'rule' => array('date', 'ymd'),
				'required' => false
			)
		),
		'lease_length' => array(
			'numeric' => array(
				'rule' => 'numeric',
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
		'baths' => 'decimal',
		'air' => 'boolean',
		'parking_type' => 'numeric',
		'parking_spots' => 'numeric',
		'street_parking' => 'boolean',
		'private_parking' => 'boolean',
		'furnished_type' => 'numeric',
		'pets_type' => 'numeric',
		'washer_dryer' => 'numeric', 
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
		'electric' => 'boolean', 
		'water' => 'boolean',
		'gas' => 'boolean',
		'heat' => 'boolean',
		'sewage' => 'boolean',
		'trash' => 'boolean',
		'cable' => 'boolean',
		'internet' => 'boolean',
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
		'deposit_description' => array(
			'between' => array(
				'rule' => array('between',0,25),
				'message' => 'Must be less than 25 characters'
			)
		),
		'deposit_amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'admin_description' => array(
			'between' => array(
				'rule' => array('between',0,25),
				'message' => 'Must be less than 25 characters'
			)
		),
		'admin_amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'parking_description' => array(
			'between' => array(
				'rule' => array('between',0,25),
				'message' => 'Must be less than 25 characters'
			)
		),
		'parking_amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'furniture_description' => array(
			'between' => array(
				'rule' => array('between',0,25),
				'message' => 'Must be less than 25 characters'
			)
		),
		'furniture_amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'pets_description' => array(
			'between' => array(
				'rule' => array('between',0,25),
				'message' => 'Must be less than 25 characters'
			)
		),
		'pets_amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'amenity_amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'deposit_description' => array(
			'between' => array(
				'rule' => array('between',0,25),
				'message' => 'Must be less than 25 characters'
			)
		),
		'deposit_amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'upper_floor_description' => array(
			'between' => array(
				'rule' => array('between',0,25),
				'message' => 'Must be less than 25 characters'
			)
		),
		'upper_floor_amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'extra_occupant_description' => array(
			'between' => array(
				'rule' => array('between',0,25),
				'message' => 'Must be less than 25 characters'
			)
		),
		'extra_occupant_amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'waitlist' => 'boolean',
		'waitlist_open_date' => array(
			'date' => array(
				'rule' => array('date', 'ymd'),
				'required' => false
			)
		),
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
		/*'website' => 'url',*/
		'is_complete' => 'boolean',
		'created' => 'datetime',
		'modified' => 'datetime'
	);

	private $MAX_BEDS = 10;
	private $MAX_RENT = 5000;
	private $FILTER_FIELDS = array(
		'Dates' => array('Date' => array()), 
		'LeaseRange' => array('Range' => array('lease_length', null, 'Rental')),
		'UnitTypes' => array('MultipleOption' => array('building_type_id', Rental::BUILDING_TYPE_CONDO, 'Marker')),
		'Beds' => array('MultipleOption'=>array('beds', 10, 'Rental')), /* 10 is MAX_BEDS */
		'Rent' => array('Range' => array('rent', 5000, 'Rental')), /* 5000 is MAX_RENT */
		'PetsAllowed'  => array('Boolean' => array('pets_type', Rental::PETS_NOT_ALLOWED, 'Rental')),/*
		'ParkingAvailable' => array('Boolean' => array('parking_type', Rental::PARKING_NO_PARKING, 'Rental')),*/
		'Air' => array('Boolean' => array('air', Rental::AIR_NO_AIR, 'Rental'))
	);

	/*
	Returns the rental_id for the rental with the given listing_id
	*/
	public function GetRentalIdFromListingId($listing_id, $user_id)
	{
		$rentalId = $this->find('first', array(
			'fields' => array('Rental.rental_id'),
			'conditions' => array(
				'Rental.listing_id' => $listing_id
			)
		));

		if ($rentalId != null)
			return $rentalId['Rental']['rental_id'];
		else{
			$error = null;
			$error['listing_id'] = $listing_id;
			$this->LogError($user_id, 23, $error);
			return null;
		}
	}
}

?>