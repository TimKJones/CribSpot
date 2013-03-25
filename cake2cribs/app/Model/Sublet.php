<?php 

class Sublet extends AppModel {
	//Not sure if belongs to many. Perhaps just allow one listing.
	public $belongsTo = array('User', 'Marker', 'University','BuildingType','UtilityType','BathroomType','PaymentType', 'FurnishedType');
	public $hasMany = array('Housemate', 'Favorite', /*'Image'*/);
	//public $hasOne = array();
	public $primaryKey = 'id';
	public $actsAs = array('Containable');
	//public $uses = array('Housemate', 'BuildingType','UtilityType','BathroomType','PaymentType');

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
		//section for buildingID
		//is not required(will be generated if an existing building isn't selected, we probably will want to generate buildings by hand)
		//this could be selected through an autocomplete form for the selected university
		//if a building isn't found, perhaps create one?
		//completed
		'building_type_id' => array(
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid building ID'
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
		'short_description' => array(
			'between' => array(
				'rule' => array('between',1,160),
				'message' => 'Must be between 1 and 160 characters'
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
		'ac' => 'boolean',
		'parking' => 'boolean',
		'is_finished' => 'boolean',
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
CakeLog::write("urlParams", "in func: " . print_r($params, true));
		$building_type_id_OR = array();
		if ($params['house'] == "true")
			array_push($building_type_id_OR, $this->getBuildingTypeId('House'));
		if ($params['apt'] == "true")
			array_push($building_type_id_OR, $this->getBuildingTypeId('Apartment'));
		if ($params['unit_type_other'] == "true")
			array_push($building_type_id_OR, $this->getBuildingTypeId('Duplex'));

		$bathroom_type_id_OR = array();
		if ($params['bathroom_type'] != "Private" && $params['bathroom_type'] != 'Shared')
		{
			array_push($bathroom_type_id_OR, $this->getBathroomTypeId('Private'));
			array_push($bathroom_type_id_OR, $this->getBathroomTypeId('Shared'));
			array_push($conditions, array('OR' => array(
			array('Sublet.bathroom_type_id' => $bathroom_type_id_OR),
			array('Sublet.bathroom_type_id' => NULL))));
		}
		else
		{
			if ($params['bathroom_type'] == 'Private')
				array_push($conditions, array('Sublet.bathroom_type_id' => $this->getBathroomTypeId('Private')));
			else
				array_push($conditions, array('Sublet.bathroom_type_id' => $this->getBathroomTypeId('Shared')));
		}

		
		$grad_undergrad_OR = array();
		if ($params['grad'] == "true")
			array_push($grad_undergrad_OR, $this->getStudentTypeId('Graduate'));
		if ($params['undergrad'] == "true")
			array_push($grad_undergrad_OR, $this->getStudentTypeId('Undergraduate'));
		if (count($grad_undergrad_OR) > 0)
			array_push($grad_undergrad_OR, $this->getStudentTypeId('Mix'));
		//array_push($grad_undergrad_OR, 	NULL);


		$gender_OR = array();
		if ($params['male'] == "true")
			array_push($gender_OR, $this->getGenderTypeId('Male'));
		if ($params['female'] == "true")
			array_push($gender_OR, $this->getGenderTypeId('Female'));
		if (count($gender_OR) > 0)
			array_push($gender_OR, $this->getGenderTypeId('Mix'));
		//array_push($gender_OR, 	NULL);


		if ($params['students_only'] == "true")
			array_push($conditions, array(
				'Housemate.enrolled' => true));

CakeLog::write("filterConditions", "params: " . print_r($params, true));

		array_push($conditions, array('OR' => array(
			array('Marker.building_type_id' => $building_type_id_OR),
			array('Marker.building_type_id' => NULL))));

		array_push($conditions, array('OR' => array(
			array('Housemate.student_type'   => $grad_undergrad_OR), 
			array('Housemate.student_type'   => NULL))));

		array_push($conditions, array('OR' => array(
			array('Housemate.gender'   => $gender_OR),
			array('Housemate.gender'   => NULL))));

		array_push($conditions, array(
			'Sublet.price_per_bedroom >=' => $params['min_rent'],
			'Sublet.price_per_bedroom <=' => $params['max_rent'],
			'Sublet.number_bedrooms >=' => $params['beds']));

		$date_conditions = array();
		if ($params['start_date'] != 'NOT_SET')
			$date_conditions['Sublet.date_begin <='] = date($params['start_date']);

		if ($params['end_date'] != 'NOT_SET')
			$date_conditions['Sublet.date_end >='] = date($params['end_date']);

		if (count($date_conditions) > 0)
			array_push($conditions, $date_conditions);

		if ($params['utilities_included'] == "true")
			array_push($conditions, array(
				'Sublet.utility_cost' => 0,
			));

		if ($params['no_security_deposit'] == "true")
			array_push($conditions, array(
				'Sublet.deposit_amount' => 0,
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
		CakeLog::write("urlParams", print_r($params, true));
		$conditions = $this->getFilteredQueryConditions($params);
		CakeLog::write("filterConditions", print_r($conditions, true));

		/* Limit which tables are queried */
		$contains = array('Housemate', 'Marker');

		$markerIdList = $this->find('all', array(
			'conditions' => $conditions,
			'joins' => array(
		        array(
		            'table' => 'housemates',
		            'alias' => 'Housemate',
		            'type' => 'INNER',
		            'conditions' => array(
		                'Housemate.sublet_id = Sublet.id'
		            )
		        )
		    ),
			'fields' => array('marker_id'),
			'contain' => $contains));

		//return $markerIdList[0]['Listing']['marker_id'];
		$formattedIdList = array();
		for ($i = 0; $i < count($markerIdList); $i++)
			array_push($formattedIdList, $markerIdList[$i]['Sublet']['marker_id']);


$log = $this->getDataSource()->getLog(false, false); 
	  	CakeLog::write("lastQuery", print_r($log, true));
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

	public function getSubletDataByMarkerId($marker_id)
	{
		$conditions = array('Sublet.marker_id' => $marker_id);

		$University = ClassRegistry::init("University");
		$university_verified = $University->getUniversityFromEmail('test@umich.edu');
	 	$subletQuery = $this->find('all', array(
	                     'conditions' => $conditions, 
	                     'contain' => array('User.id', 'User.first_name', 'User.email', 'User.facebook_userid', 'Housemate', 
	                     	'Image.sublet_id', 'Image.image_path', 'Image.is_primary', 'Image.caption')
	  	));

	  	for ($i = 0; $i < count($subletQuery); $i++)
	  	{
	  		unset($subletQuery[$i]['User']['email']);
	  		//$email = array('verified_university' => $university_verified);
	  		$subletQuery[$i]['User']['verified_university'] = $university_verified;
	  		$User = ClassRegistry::init("User");
	  		/*$twitter_followers = $User->getTwitterFollowersCount($subletQuery[$i]['User']['id']);
	  		//$followers_array = array('twitter_followers' => $twitter_followers);
	  		$subletQuery[$i]['User']['twitter_followers'] = $twitter_followers;*/
	  		unset($subletQuery[$i]['User']['id']);
	  	}
	  	
	  	CakeLog::write("loadMarkerData",  print_r($subletQuery, true));

	 	return $subletQuery;
	}

	public function LoadHoverData()
	{
		$this->contain();
		$hover_data = $this->find('all', array(
			'fields' => array('marker_id', 'number_bedrooms', 'price_per_bedroom', 'date_begin', 'date_end')));
		return $hover_data;
	}

	/*
	Pulls marker_ids for sublets in the logged-in users favorites
	*/
	public function GetFavoritesMarkerIds($subletIdsResultSet)
	{
		$subletIdsList = array();
		for ($i = 0; $i < count($subletIdsResultSet); $i++)
		{
			array_push($subletIdsList, $subletIdsResultSet[$i]['Favorite']['sublet_id']);
		}

		$this->contain();	
		$marker_ids = $this->find('all', array(
			'conditions' => array('Sublet.id' => $subletIdsList),
			'fields' => array('Sublet.marker_id')));

		return $marker_ids;
	}

	public function getBuildingTypeId($buildingString)
	{
		$buildingTypes = Cache::read("buildingTypes");
		$buildingId = null;
		if ($buildingTypes == null)
		{
			$BuildingType = ClassRegistry::init("BuildingType");
			$allBuildingTypes = $BuildingType->find('all');
			for ($i = 0; $i < count($allBuildingTypes); $i++)
			{
				$id = $allBuildingTypes[$i]['BuildingType']['id'];
				$name = $allBuildingTypes[$i]['BuildingType']['name'];
				$buildingTypes[$name] = $id;
			}
		}

		if (array_key_exists($buildingString, $buildingTypes))
			return $buildingTypes[$buildingString];
		else
			return null;
	}	

	function getBathroomTypeId($bathroomString)
	{
		$bathroomTypes = Cache::read("bathroomTypes");
		$bathroomId = null;
		if ($bathroomTypes == null)
		{
			$BathroomType = ClassRegistry::init("BathroomType");
			$allBathroomTypes = $BathroomType->find('all');
			for ($i = 0; $i < count($allBathroomTypes); $i++)
			{
				$id = $allBathroomTypes[$i]['BathroomType']['id'];
				$name = $allBathroomTypes[$i]['BathroomType']['name'];
				$bathroomTypes[$name] = $id;
			}
		}

		if (array_key_exists($bathroomString, $bathroomTypes))
			return $bathroomTypes[$bathroomString];
		else
			return null;
	}

	function getGenderTypeId($genderString)
	{
		$genderTypes = Cache::read("genderTypes");
		$genderId = null;
		if ($genderTypes == null)
		{
			$GenderType = ClassRegistry::init("GenderType");
			$allGenderTypes = $GenderType->find('all');
			for ($i = 0; $i < count($allGenderTypes); $i++)
			{
				$id = $allGenderTypes[$i]['GenderType']['id'];
				$name = $allGenderTypes[$i]['GenderType']['name'];
				$genderTypes[$name] = $id;
			}
		}

		if (array_key_exists($genderString, $genderTypes))
			return $genderTypes[$genderString];
		else
			return null;
	}

	function getStudentTypeId($studentTypeString)
	{
		$studentTypes = Cache::read("studentTypes");
		$studentId = null;
		if ($studentTypes == null)
		{
			$StudentType = ClassRegistry::init("StudentType");
			$allStudentTypes = $StudentType->find('all');
			for ($i = 0; $i < count($allStudentTypes); $i++)
			{
				$id = $allStudentTypes[$i]['StudentType']['id'];
				$name = $allStudentTypes[$i]['StudentType']['name'];
				$studentTypes[$name] = $id;
			}
		}

		if (array_key_exists($studentTypeString, $studentTypes))
			return $studentTypes[$studentTypeString];
		else
			return null;
	}

	function getLastQuery()
	{
		$dbo = $this->getDatasource();
		$logs = $dbo->_queriesLog;

		return end($logs);
	}

	
}
?>