<?php

class ListingFilterBehavior extends ModelBehavior {

/*
Sets up the configuration for the model
Parameters needed:
- $filter_fields: the fields being filtered for this model
*/
	public function setup(Model $model, $config = array()) {

	}

	public function GetMarkerIdList(Model $model, $listing_type, $params)
	{
		App::import('model', $listing_type);
		$listingTypeModel = new $listing_type();
		$conditions = $this->_getFilteredQueryConditions($params, $listingTypeModel->FILTER_FIELDS, $listing_type);

		/* Limit which tables are queried */
		$contains = array('Marker', $listing_type);

		$findConditions = array(
		    'contain' => $contains,
			'fields' => array('DISTINCT (Listing.listing_id)'));
		if (count($conditions) > 0)
			$findConditions['conditions'] = $conditions;

		$markerIdList = $model->find('all', $findConditions);
		$log = $model->getDataSource()->getLog(false, false); 
	  	CakeLog::write("lastQuery", print_r($log, true));
		$formattedIdList = array();
		for ($i = 0; $i < count($markerIdList); $i++)
			array_push($formattedIdList, $markerIdList[$i]['Listing']['listing_id']);

		CakeLog::write('test', print_r($formattedIdList, true));

		return json_encode($formattedIdList);
	}

/* --------------------------------- private functions --------------------------- */

	/*
	Returns the conditions array used to match the current filter settings in a query using model->find()
	*/
	private function _getFilteredQueryConditions($params, $filter_fields, $method)
	{
		$conditions = array();
		/* Get a separate piece of the conditions array for each field */
		foreach ($filter_fields as $field => $filterOptions){
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
					else if ($filterType === 'DatePicker'){
						$date = json_decode($params[$field]);
						CakeLog::write('date', $date);
						$next_conditions = $this->_getDatePickerConditions($filterParams[0], $filterParams[1], $date, $filterParams[2]);
					}
			
					if ($next_conditions !== null)
						array_push($conditions, $next_conditions);
				}
			}
		}

		/* Handle parking separately...there are a couple checks that are factored into it */
		if (!strcmp($method, 'Rental') && 
			array_key_exists('ParkingAvailable', $params) && $params['ParkingAvailable'] == 1)
			array_push($conditions, $this->_getParkingConditions($params, $method));

		return $conditions;
	}

	/*
	Returns the piece of the filter conditions array for a specific date.
	$compareOperator is either '<' or '>' to specify that listings > or < than this date are returned.
	*/
	private function _getDatePickerConditions($field_name, $compareOperator, $date, $table_name='Rental')
	{
		if (!strcmp('start_date', $field_name))
			$date = "'".$date." 11:59:59'";
		else
			$date = "'".$date." 00:00:00'";	

		$conditions = array($table_name.'.'.$field_name.' '.$compareOperator.' '.$date);
		CakeLog::write('conditions', print_r($conditions, true));
		return $conditions;
	}

	/*
	Returns the piece of the filter conditions array for 'Parking Available'
	*/
	private function _getParkingConditions($params, $method)
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