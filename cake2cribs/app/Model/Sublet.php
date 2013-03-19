<?php 

class Sublet extends AppModel {
	//Not sure if belongs to many. Perhaps just allow one listing.
	public $belongsTo = array(/*'User',*/'University','BuildingType','UtilityType','BathroomType','PaymentType');
	public $hasMany = 'Housemate';
	public $hasOne = array();
	public $primaryKey = 'id';

	public $validate = array (
		'id' => 'alphaNumeric', //TODO: make rule more precise
		//section for user_id
		'user_id' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A user ID is required.'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid user ID'
				)
			),
		//section for University ID
		//is required, must be natural number, must correspond to near location? might want to do some calculations later
		//completed
		'university_id' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'An associated university is required'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid university ID'
				)
			),
		//section for building_type_id
		//is required, must be natural number
		//completed
		'building_type_id' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'An building type is required'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid building type'
				)
			),
		//section for buildingID
		//is not required(will be generated if an existing building isn't selected, we probably will want to generate buildings by hand)
		//this could be selected through an autocomplete form for the selected university
		//if a building isn't found, perhaps create one?
		//completed
		'building_id' => array(
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid building ID'
				)
			),
		//section for name
		//Must be between 1 and 250 characters. Required for everything but house. We can use this to adjust the buildingID
		//completed
		'name' => array(
			'between' => array(
				'rule' => array('between',0,250),
				'message' => 'Must be between 1 and 250 characters'
				)
			),
		//section for streetAddress
		// required, length limited. Too hard to apply a custom validation to, so much variation. 
		//completed
		'street_address' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A street address is required.'
				),
			'between' => array(
				'rule' => array('between',1,250),
				'message' => 'Must be between 1 and 250 characters'
				)
			),
		//section for city
		//required. 
		//completed
		'city' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A city name is required.'
				),
			'between' => array(
				'rule' => array('between',1,250),
				'message' => 'Must be between 1 and 250 characters'
				)
			),
		//section for state
		//alphanumeric, required, might want to write a custom regex validation for this one later
		'state' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A state is required.'
				),
			'between' => array(
				'rule' => array('between',1,2),
				'message' => 'Must be between 1 and 2 characters'
				)
			),
		//section for ZIP
		// postal, required.
		//completed
		'zip' => array(
			'isZIP' => array(
				'rule' => array('postal', null, 'us'),
				'message' => 'ZIP is invalid.'
				),
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A ZIP is required.'
				)
			),

		//section for latitude
		//required, decimal, generated through map helper
		//completed
		'latitude' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A latitude is required.'
				),
			'isDecimal' => array(
				'rule' => 'decimal',
				'message' => 'A valid latitude is required.'
				)
			),

		//section for longitude
		//required, decimal, generated through map helper
		//completed
		'longitude' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A longitude is required.'
				),
			'isDecimal' => array(
				'rule' => 'decimal',
				'message' => 'A valid longitude is required.'
				)
			),


		//section for dateBegin
		//date, required.
		//completed
		'date_begin' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A start date is required.'
				)
			),

		//section for dateEnd
		//date, required
		//completed
		'date_end' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'An end date is required.'
				)
			),
		//section for numberBedrooms
		//naturalNumber, required.
		//completed
		'number_bedrooms' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'The number of bedroms is required.'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',false),
				'message' => 'Invalid number of bedrooms.'
				)
			),
		//section for pricePerBedroom
		//naturalNumber, required. MIGHT WANT TO MAKE THIS A DECIMAL AND DOUBLE IN TABLE
		//completed
		'price_per_bedroom' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A price per bedroom is required.'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',false),
				'message' => 'Bedroom price must be a positive, whole number.'
				)
			),
		//section for paymentTypeID
		//naturalNumber, required, look in the paymentType table
		//complete
		'payment_type_id' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A payment type is required.'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid payment type.'
				)
			),
		//section for description
		//no validations besides escaping special characters
		'description' => array(
			'between' => array(
				'rule' => array('between',1,1000),
				'message' => 'Must be between 1 and 1000 characters'
				)
			),
		
		//section for numberBathrooms
		//naturalnumber, required.
		//complete
		'number_bathrooms' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'The number of bathrooms is required.'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid number of bathrooms.'
				)
			),
		//section for bathroomType
		//naturalnumber, required.
		//complete
		'bathroom_type_id' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A bathroom type is required.'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid bathroom type.'
				)
			),
		//section for utilityType
		//natural number, required
		'utility_type_id' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A utility type is required.'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid utility type.'
				)
			),
		//section for utilityCost
		//naturalnumber, required
		'utility_cost' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'Cost of utilities is required.'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid utility cost.'
				)
			),
		//section for depositAmount
		//naturalNumber, required.
		'deposit_amount' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A deposit amount is required.'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid deposit amount.'
				)
			),
		//section for additionalFeesDescription
		//no additional checks besides escaping special characters
		'additional_fees_description' => array(
			'between' => array(
				'rule' => array('between',1,1000),
				'message' => 'Must be between 1 and 1000 characters'
				)
			),
		//section for additionalFeesAmount
		//naturalNumber, not required.
		'additional_fees_amount' => array(
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Additional fees must be a whole, positive number.'
				)
			),
		'unit_number' =>array(
			'between' => array(
				'rule' => array('between',0,250),
				'message' => 'Must be between 1 and 250 characters'
				)),
		'flexible_dates' => 'boolean',
		'furnished_type_id' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'You must specify if the sublet is furnished..'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid furnished value.'
				)
			)


		//section for galleryID
		//have to ask Tim about this

		//section for primaryPhotoID
		//ask Tim about this

		//section for markerID
		//generate onSave by bundling with other properties at same location.
	);
	

	/*
	Returns the conditions array used to match the current filter settings in a query using model->find()
	*/
	private function getFilteredQueryConditions($params)
	{
		$conditions = array();

		$unit_type_OR = array();
		if ($params['house'] == "true")
			array_push($unit_type_OR, 'house');
		if ($params['apt'] == "true")
			array_push($unit_type_OR, 'apt');
		if ($params['other'] == "true")
			array_push($unit_type_OR, 'other');

		$housemates_OR = array();
		// need fields for ac, parking
		/*
		add more 'OR' arrays depending on sublets table structure
		*/


		/*if (count($lease_range_OR) > 0)
		{
			array_push($conditions, array('OR' => array(
				'Listing.lease_range' => $lease_range_OR)));
		}	
		else
			array_push($conditions, array('OR' => array(
				'Listing.lease_range' => 'NONE')));
				// Without this, all lease ranges would be returned when all check boxes are unchecked*/

		array_push($conditions, array('OR' => array(
			'Sublet.unit_type'   => $unit_type_OR)));

		array_push($conditions, array(
			'Sublet.rent >=' => $params['minRent'],
			'Sublet.rent <=' => $params['maxRent'],
			'Sublet.beds >=' => $params['minBeds'],
			'Sublet.beds <=' => $params['maxBeds']));

		if ($params['utilities_included'] == "true")
			array_push($conditions, array(
				'Sublet.deposit_amount' => 0,
			));

			if ($params['no_security_deposit'] == "true")
			array_push($conditions, array(
				'Sublet.utility_cost' => 0,
			)); 

		return $conditions;
	}


	/*
	Retrieves all listing data for a specific markerId.
	Returns a (json_encoded) array of associative arrays, with assoc. array for each listing. 
		Each assoc. array maps table column name to value.
	*/
	/*TODO: Filter which columns are retrieved - for example, not everything for realtor needs to be fetched */
	public function getListingData($markerId, $includeRealtor)
	{
		$conditions = array('Listing.marker_id' => $markerId);

		// Contain the query to only retrieve the fields needed for the marker tooltip.
		$contains = array();

		$listingsQuery = array();
	 	$listingsQuery = $this->find('all', array(
	                     'conditions' => $conditions,
	                     'contain' => $contains
	  	));

	 	return $listingsQuery;
	}

	/*
	Given array of parameter values as input.
	Returns a list of marker_ids that have listings matching the parameter criteria.
	*/
	public function getFilteredMarkerIdList($params)
	{
		$conditions = $this->getFilteredQueryConditions($params);

		/* Limit to only querying from the Listing table. */
		$this->contain();

		$markerIdList = $this->find('all', array(
			'conditions' => $conditions,
			'fields' => array('marker_id')));

		//return $markerIdList[0]['Listing']['marker_id'];
		$formattedIdList = array();
		for ($i = 0; $i < count($markerIdList); $i++)
			array_push($formattedIdList, $markerIdList[$i]['Listing']['marker_id']);

		return json_encode($formattedIdList);
	}

	/*
	Returns the marker id corresponding the listing with id=$sublet_id
	*/
	public function getMarkerId($sublet_id)
	{
		return 1;
	}

	public function getSubletData($sublet_id)
	{
		$conditions = array('Sublet.id' => $sublet_id);

		$subletQuery = array();
	 	$subletQuery = $this->find('first', array(
	                     'conditions' => $conditions
	  	));

	 	return $subletQuery;
	}
}
?>
