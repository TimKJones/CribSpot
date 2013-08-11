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
		'lease_length' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => true
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
				'required' => true
			)
		),
		'baths' => 'numeric',
		'air' => 'integer',
		'parking_type' => 'numeric',
		'parking_spots' => 'numeric',
		'street_parking' => 'boolean',
		'furnished_type' => 'numeric',
		'pets_type' => 'numeric',
		'washer_dryer' => 'numeric', 
		'smoking' => 'boolean',
		'laundry' => 'integer',
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
				'rule' => 'date',
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
		'website' => 'url',
		'is_complete' => 'boolean'
	);

	private $MAX_BEDS = 10;
	private $MAX_RENT = 5000;

	/*
	Marks the rental as either complete or incomplete, depending on whether all fields have been filled in.
	Then it saves the rental.
	REQUIRES: it has been verified that user is logged-in.
	*/
	public function SaveRental($rental, $user_id=null)
	{
		if ($rental == null){
			$error = null;
			$error['rental'] = $rental;
			$this->LogError($user_id, 12, $error);
			return array('error' => 
					'Failed to save rental. Contact help@cribspot.com if the error persists. Reference error code 12');
		}

		// Remove fields with null values so cake doesn't complain (they will be saved to null as default)
		$rental = parent::_removeNullEntries($rental);
		$rental['is_complete'] = 1;
		if ($this->save(array('Rental' => $rental)))
			return array('success' => '');
		else
		{
			$error = null;
			$error['rental'] = array('Rental' => $rental);
			$error['validation'] = $this->validationErrors;
			$this->LogError($user_id, 13, $error);
			return array('error' => array('message' => 
				'Failed to save rental. Contact help@cribspot.com if the error persists. Reference error code 13',
				'validation' => $this->validationErrors));
		}
	}

	/*
	Delete the rental with listing_id = $listing_id
	IMPORTANT: this is listing_id, NOT rental_id.
	*/
	public function DeleteRental($listing_id)
	{
		return array('success' => '');
	}

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
	public function getFilteredMarkerIdList($params)
	{
		$conditions = $this->_getFilteredQueryConditions($params);

		/* Limit which tables are queried */
		$contains = array('Marker', 'Rental');

		$findConditions = array(
		    'contain' => $contains,
			'fields' => array('DISTINCT (Listing.marker_id)'));
		if (count($conditions) > 0)
			$findConditions['conditions'] = $conditions;

		$markerIdList = $this->Listing->find('all', $findConditions);
CakeLog::write('markeridresult', print_r($markerIdList, true));

		$formattedIdList = array();
		for ($i = 0; $i < count($markerIdList); $i++)
			array_push($formattedIdList, $markerIdList[$i]['Listing']['marker_id']);

		$log = $this->getDataSource()->getLog(false, false); 
	  	CakeLog::write("lastQuery", print_r($log, true));
		return json_encode($formattedIdList);
	}

	/*
	Returns the conditions array used to match the current filter settings in a query using model->find()
	*/
	private function _getFilteredQueryConditions($params)
	{
		$conditions = array();
CakeLog::write("params", print_r($params, true));
		/* Get a separate piece of the conditions array for each field */

		if (array_key_exists('Dates', $params)) {
			$months = json_decode($params['Dates']);
			if ($months !== -1){
				$date_conditions = $this->_getDateConditions($months);
				array_push($conditions, $date_conditions);
			}
		}

		if (array_key_exists('LeaseRange', $params)){
			$leaseRange = json_decode($params['LeaseRange']);
			$rent_conditions = array(
				'Rental.lease_length >=' => $leaseRange->min,
				'Rental.lease_length <=' => $leaseRange->max);
			array_push($conditions, $rent_conditions);
		}
	
		if (array_key_exists('UnitTypes', $params)) {
			$unit_types = json_decode($params['UnitTypes']);
CakeLog::write("unittypes", print_r($unit_types, true));
			if ($unit_types !== -1){
				$unit_types_conditions = $this->_getMultipleOptionFilterConditions($unit_types, 
					'building_type_id', Rental::BUILDING_TYPE_DUPLEX + 1, 'Marker');
				CakeLog::write('buildingtypes', print_r($unit_types_conditions, true));
				array_push($conditions, $unit_types_conditions);
			}
		}

		if (array_key_exists('Beds', $params)){
			$beds = json_decode($params['Beds']);
			if ($beds !== -1){
				$beds_conditions = $this->_getBedsConditions($beds);
				array_push($conditions, $beds_conditions);
			}
		}

		if (array_key_exists('Rent', $params)){
			$rent = json_decode($params['Rent']);
			if ($rent !== -1){
				if (intval($rent->max) === $this->MAX_RENT)
					$rent->max = 9999999;

				$rent_conditions = array(
					'Rental.rent >=' => $rent->min,
					'Rental.rent <=' => $rent->max);
				array_push($conditions, $rent_conditions);
			}
		}

		if (array_key_exists('PetsAllowed', $params)) {
			$pets_allowed = json_decode($params['PetsAllowed']);
			if (intval($pets_allowed) == 1){
				$pets_conditions = $this->_getBooleanFilterConditions('pets_type', Rental::PETS_NOT_ALLOWED);
				array_push($conditions, $pets_conditions);
			}			
		}

		if (array_key_exists('ParkingAvailable', $params)) {
			$parking_available = json_decode($params['ParkingAvailable']);
			if (intval($parking_available) == 1){
				$parking_conditions = $this->_getBooleanFilterConditions('parking_type', Rental::PARKING_NO_PARKING);
				array_push($conditions, $parking_conditions);
			}			
		}

		if (array_key_exists('Air', $params)) {
			$air = json_decode($params['Air']);
			if (intval($pets_allowed) == 1){
				$air_conditions = $this->_getBooleanFilterConditions('air', Rental::AIR_NO_AIR);
				array_push($conditions, $air_conditions);
			}			
		}

		return $conditions;
	}

	/*
	Get the piece of the filter conditions array related to beds
	*/
	private function _getBedsConditions($beds)
	{
		CakeLog::write('beds', print_r($beds, true));
		$include_greater_than_max = false;
		$processed_beds = array();
		for ($i = 0; $i < count($beds); $i++){
			if ($beds[$i] == $this->MAX_BEDS)
				$include_greater_than_max = true;

			array_push($processed_beds, intval($beds[$i]));
		}

		$beds_conditions = array( 'OR' => array(
			array('Rental.beds' => $processed_beds),
			array('Rental.beds' => NULL)));

		if ($include_greater_than_max)
			array_push($beds_conditions['OR'], array(
				'Rental.beds >' => $this->MAX_BEDS
			));

		return $beds_conditions;
	}

	/*
	$field_name is the name of the field in $table_name that is being filtered
	Adds a condition to return all rows where value of $field_name is GREATER THAN OR EQUAL TO $min_value
	*/
	private function _getBooleanFilterConditions($field_name, $min_value, $table_name='Rental')
	{
		$conditions = array('OR' => array(
			array($table_name . '.' . $field_name . ' >' => $min_value),
			array($table_name . '.' . $field_name => NULL))
		);

		return $conditions;
	}

	/*
	Takes an input of an array of (key, value) pairs
	Only filters for fields that can be multiple values (ex. unit_type)
	$prefix is the part of the key that has been pre-pended (ex. 'unit_type'), excluding the last underscore.
	$other_max_value - value above which all values are valid if 'other' box is checked.
	*/
	private function _getMultipleOptionFilterConditions($params, $field_name, $other_value, $table_name='Rental')
	{
		$conditions = array();

		$conditions['OR'] = array(
			array($table_name . '.' . $field_name => $params),
			array($table_name . '.' . $field_name => NULL));

		if (in_array($other_value, $params))
			array_push($conditions['OR'], array(
				$table_name . '.' . $field_name . ' >' => $other_value
			));

		return $conditions;
	}

	/*
	Given a typeString (like 'House' or 'Duplex', )
	*/
	private function _getIntegerFromTypeString($typeString, $type_name)
	{
		$int_value = null;
		if ($type_name == 'building_type_id') {
			$int_value = $this->building_type_reverse($typeString);
		}

		return $int_value;
	}

	/*
	Returns a piece of the conditions array for the query filter dealing with dates.
	Specifically, adds checks to include rentals that occur within the checked months.
	*/
	private function _getDateConditions($params)
	{
		/* TODO: MAKE SURE ALL FIELDS ARE PRESENT BEFORE ARRAY ACCESSES */

		/* get conditions related to start and end date */
		$dateConditions = array();
		$startDateConditions = array();
		$startDateConditions['OR'] = array();
		CakeLog::write("dateparams", print_r($params, true));
		$startDateRanges = $this->_getStartDateRanges($params->months, $params->year);
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

	/*
	Returns array of months selected in filter as integer-strings
	*/
	private function _getMonthsSelectedArray($months)
	{
		$months_selected = array();
		foreach ($months as $key => $value) {
			if (intval($value) === 1){
				array_push($months_selected, intval($key));
			}
		}

		return $months_selected;
	}


}

?>