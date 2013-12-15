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

	public $MAX_BEDS = 10;
	public $MAX_RENT = 5000;
	public $FILTER_FIELDS = array(
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

	/*
	Given array of parameter values as input.
	Returns a list of marker_ids that have rentals matching the parameter criteria.
	*/
	public function ApplyFilter($params)
	{
		$marker_id_list = $this->GetFilteredMarkerIdList($params);
		return json_encode($marker_id_list);
	}

	/*
	Returns the conditions array used to match the current filter settings in a query using model->find()
	*/
	private function _getFilteredQueryConditions($params)
	{
		$conditions = array();
		/* Get a separate piece of the conditions array for each field */
		foreach ($this->FILTER_FIELDS as $field => $filterOptions){

			if (array_key_exists($field, $params)){
				$next_conditions = null;
				foreach ($filterOptions as $filterType => $filterParams){
					if ($filterType === 'Range'){
						$decoded = json_decode($params[$field]);
						$min = $decoded->min;
						$max = $decoded->max;
						$next_conditions = $this->_getRangeConditions($filterParams[0], $min, $max, $filterParams[1], $filterParams[2]);
					}
					else if ($filterType === 'Date'){
						$decoded = json_decode($params[$field]);
						$months = $decoded->months;
						$year = $decoded->year;
						$next_conditions = $this->_getDateConditions($months, $year);
					}
					else if ($filterType === 'MultipleOption'){
						$next_conditions = $this->_getMultipleOptionFilterConditions($params, $filterParams[0], $filterParams[1], $filterParams[2]);
					}
					else if ($filterType === 'Boolean'){
						if (intval($params[$field]) === 1)
							$next_conditions = $this->_getBooleanFilterConditions($filterParams[0], $filterParams[1], $filterParams[2]);
					}

					if ($next_conditions !== null)
						array_push($conditions, $next_conditions);
				}
			}
		}

		/* Handle parking separately...there are a couple checks that are factored into it */
		if (array_key_exists('ParkingAvailable', $params) && $params['ParkingAvailable'] == 1)
			array_push($conditions, $this->_getParkingConditions($params));

		return $conditions;
	}

	/*
	Returns the piece of the filter conditions array for 'Parking Available'
	*/
	private function _getParkingConditions($params)
	{
		return array('OR' => array(
			'Rental.parking_type >' => 0,
			'Rental.private_parking' => 1,
			'Rental.street_parking' => 1
		));
	}

	/*
	Returns the piece of the conditions array for a filter query dealing with
	fields that have min and max values (like a slider).
	$max_value is the maximum possible value for the range
	*/
	private function _getRangeConditions($field_name, $min, $max, $max_value, $table_name='Rental')
	{
		if (intval($max) === $max_value)
			$max = 9999999;

		$conditions = array(
			$table_name . '.' . $field_name . ' >=' => $min,
			$table_name . '.' . $field_name . ' <=' => $max);
		
		return $conditions;
	}

	/*
	$field_name is the name of the field in $table_name that is being filtered
	Adds a condition to return all rows where value of $field_name is GREATER THAN OR EQUAL TO $min_value
	*/
	private function _getBooleanFilterConditions($field_name, $min_value, $table_name='Rental')
	{

		$conditions = array('OR' => array(
			array($table_name . '.' . $field_name . ' >' => $min_value))
		);

		return $conditions;
	}

	/*
	Takes an input of an array of (key, value) pairs
	Only filters for fields that can be multiple values (ex. unit_type)
	$prefix is the part of the key that has been pre-pended (ex. 'unit_type'), excluding the last underscore.
	$other_max_value - value above which (and equal to) all values are valid if 'other' box is checked.
	*/
	private function _getMultipleOptionFilterConditions($params, $field_name, $other_value, $table_name='Rental')
	{
		$safe_field_name = $this->_getSafeFieldName($field_name);
		$conditions = array();
		$possibleValues = json_decode($params[$safe_field_name]);
		if (count($possibleValues) === 0)
			return null;

		$conditions['OR'] = array(array($table_name . '.' . $field_name => $possibleValues));

		if (in_array($other_value, $possibleValues))
			array_push($conditions['OR'], array(
				$table_name . '.' . $field_name . ' >=' => $other_value
			));

		return $conditions;
	}

	private function _getSafeFieldName($field_name)
	{
		if ($field_name == 'building_type_id')
			return 'UnitTypes';
		if ($field_name == 'beds')
			return 'Beds';

		return $field_name;
	}

	/*
	Returns a piece of the conditions array for the query filter dealing with dates.
	Specifically, adds checks to include rentals that occur within the checked months.
	*/
	private function _getDateConditions($months, $year)
	{
		/* TODO: MAKE SURE ALL FIELDS ARE PRESENT BEFORE ARRAY ACCESSES */
		$dateConditions = array();
		$startDateConditions = array();
		$startDateConditions['OR'] = array();
		$startDateRanges = $this->_getStartDateRanges($months, $year);
		foreach ($startDateRanges as $pair){
			$and_array = array();
			$and_array['AND'] = array(
				'Rental.start_date >=' => $pair['start_date_min'],
				'Rental.start_date <=' => $pair['start_date_max']
			);
			array_push($startDateConditions['OR'], $and_array);
		}
		
		array_push($dateConditions, $startDateConditions);	
		return array('AND' => $dateConditions);
	}

	/*
	Returns start_dates that are valid to be searched based on user's current filter preferences.	
	*/
	private function _getStartDateRanges($months, $start_year)
	{
		$pairs = array();
		for ($j = 0; $j < count($months); $j++){
			$start_date = date('Y-m-d', strtotime('20' . $start_year . '-' . $months[$j] . '-01'));
			$end_date = date('Y-m-d', strtotime('+1 month', strtotime($start_date)));
			$end_date = date('Y-m-d', strtotime('-1 day', strtotime($end_date)));
			$new_pair = array(
				'start_date_min' => $start_date,
				'start_date_max' => $end_date
			);
			array_push($pairs, $new_pair);
		}

		return $pairs;
	}
}

?>
