<?php

class Marker extends AppModel {
	public $name = 'Marker';
	public $actsAs = array('Containable');
	public $primaryKey = 'marker_id';
	public $hasMany = array(
						'Listing' => array(
							'className' => 'Listing', 
							'foreignKey' => 'marker_id'
						)
	);

	public $RADIUS = 12; // radius from center (km) encompassing area to pull properties from

	public $validate = array(
		'marker_id' =>'alphaNumeric', 
		//section for name
		//Must be between 1 and 250 characters. Required for everything but house. We can use this to adjust the buildingID
		//completed
		'alternate_name' => array(
			'between' => array(
				'rule' => array('between',0,250),
				'message' => 'Must be between 0 and 250 characters'
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
		/*'street_address' => array(
			'rule'    => 'alphaNumeric'
		),*/
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
		'latitude' => 'decimal',
		'longitude' => 'decimal'
	);
	

	/*
	Retrieves the markers near a certain point. Options can be any
	options you'd add to a find all statement. I safely add a condition
	to filter by distance, none of your conditions will be affects
	*/

	public function getNear($latitude, $longitude, $radius, $options=null){
		if($options==null){
			$options = array();
		}

		$this->contain();

		// First we do a lat long bound box search to filter out 
		// as many markers as possible with a general query

		$lat1 = $latitude - $radius/69;
		$lat2 = $latitude + $radius/69;
		
		$lon1 = $longitude - $radius/abs(cos(deg2rad($latitude))*69);
		$lon2 = $longitude + $radius/abs(cos(deg2rad($latitude))*69);

		$this->virtualFields = array(
    		'distance' => "(GLength(
			LineStringFromWKB(
			  LineString(
			    geoPoint, 
			    GeomFromText('POINT(".$latitude." ".$longitude.")')
			  )
			 )
			))"
		);

		$data = $this->query("
			SELECT
			marker_id,
			(GLength(
			LineStringFromWKB(
			  LineString(
			    coordinates, 
			    GeomFromText('POINT(".$latitude." ".$longitude.")')
			  )
			 )
			))
			AS distance
			FROM markers
			WHERE distance < " . $radius
		);
		
		/*if(array_key_exists("conditions", $options)){
			// array_push($options['conditions'], array('distance <' => $radius));
			array_push($options['conditions'], array('Marker.latitude >' => $lat1));
			array_push($options['conditions'], array('Marker.latitude <=' => $lat2));
			array_push($options['conditions'], array('Marker.longitude >' => $lon1));
			array_push($options['conditions'], array('Marker.longitude ' => $lon1));


		}else{
			$options['conditions'] = array('distance <' => $radius);
		} */

		$data = $this->find('all', $options);
		return $data;
	}


	
	public function getAllMarkers($target_lat_long)
	{
		if (($markers = Cache::read('markers')) === false)
		{
			CakeLog::write('debug', 'updated cache');
			$this->UpdateCache();
		}

		$markers = Cache::read('markers');

		//Find all visible markers
		$this->contain();
		$markers = $this->find('all', array('conditions'=>'Marker.visible=1'));
		$filtered_markers = array();
		
		// TODO change this functionality to use a custom sql query
		// to eliminate the need to filter all the markers everytime

		App::Import('model', 'Rental');
		for ($i = 0; $i < count($markers); $i++)
		{
			$lat = $markers[$i]['Marker']['latitude'];
			$long = $markers[$i]['Marker']['longitude'];
			$distance = $this->distance($lat, $long, $target_lat_long['latitude'], $target_lat_long['longitude']);
			if ($distance < $this->RADIUS)
			{
				$markers[$i]['Marker']['building_type_id'] = Rental::building_type(intval($markers[$i]['Marker']['building_type_id']));
				array_push($filtered_markers, $markers[$i]);
			}				
		}
		CakeLog::write('filteredMarkers', print_r($filtered_markers, true));
		return json_encode($filtered_markers);
	}

	function distance($lat1,$lon1,$lat2,$lon2) {
	  $R = 6371; // Radius of the earth in km
	  $dLat = deg2rad($lat2-$lat1);  // deg2rad below
	  $dLon = deg2rad($lon2-$lon1); 
	  $a = 
	    sin($dLat/2) * sin($dLat/2) +
	    cos($this->deg2rad($lat1)) * cos($this->deg2rad($lat2)) * 
	    sin($dLon/2) * sin($dLon/2); 
	  $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
	  $d = $R * $c; // Distance in km
	  return $d;
	}

	function deg2rad($deg) {
	  return $deg * (pi()/180);
	}

	/*public function distance($lat_cand, $long_cand, $lat_target, $long_target)
	{
		$distance = acos(sin($lat_cand)*sin($lat_target)+cos($lat_cand)*cos($lat_target)*cos($long_target-$long_cand))*6371;
		return $distance;
	}*/

	public function UpdateCache()
	{
		$this->contain();
		Cache::write('markers', $this->find('all'));
	}

	/*
	Finds marker_id by address.
	If no marker exists, creates new marker.
	If use is a PROPERTY_MANAGER, allow them to overwrite an existing marker.
	Returns marker_id on success; error message on failure.
	*/
	public function FindMarkerId($marker, $user_id=null, $user_type)
	{
		if (!array_key_exists('street_address', $marker) || 
			!array_key_exists('city', $marker) ||
			!array_key_exists('state', $marker)){
			$error = null;
			$error['marker'] = $marker;
  			$this->LogError($user_id, 34, $error);
  			return json_encode(array('error' =>
	  				'Looks like we had some issues...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 34.'));
		}

		$marker['Marker'] = $marker;
		if (!array_key_exists('marker_id', $marker['Marker']) || $user_type == User::USER_TYPE_SUBLETTER) {
			/* 
			No marker_id given or user is student and doesn't have permission to change marker. 
			Need to find an existing marker with the given address 
			*/
			$markerMatch = $this->_getMarkerByStreetAddress($marker['street_address'], $marker['city'], $marker['state']);
			if ($markerMatch != null){
				$markerMatch['Marker']['visible'] = 1;
				$marker = $markerMatch;
			}
			else /* No existing marker found. We'll create a new one. */
				$marker['Marker']['visible'] = 1;
		}

		/* Convert latitude and longitude to a mysql point datatype */
		$this->ConvertLatLongToSpatialPoint($marker);

  		if(!$this->save($marker)){
  			$error = null;
			$error['marker'] = $marker;
			$error['validation'] = $this->validationErrors;
  			$this->LogError($user_id, 35, $error);
  			return json_encode(array('error' =>
	  				'Looks like we had some issues...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 35.'));
  		}
		  		
		return $this->id;
	}

	public function GetMarkerByAddress($address)
	{
		if (!array_key_exists('street_address', $address) || 
			!array_key_exists('city', $address) ||
			!array_key_exists('state', $address)) {
				return null;
		}

		$marker = $this->find('first', array(
			'conditions' => array(
				'Marker.street_address' => $address['street_address'],
				'Marker.city' => $address['city'],
				'Marker.state' => $address['state']
		)));

		/* remove some fields that shouldn't be saved again */
		if ($marker){
			unset($marker['Marker']['created']);
			unset($marker['Marker']['modified']);
		}
		
		return $marker;
	}


	public function hide($marker){
		$marker['visible'] = 0;
		$this->save($marker);
	}

	/* 
	Returns a marker by its address information, or null if no marker exists
	*/
	public function FindByAddress($street_address, $city, $state)
	{
		$marker = $this->find('first', array(
			'conditions' => array(
				'Marker.street_address' => $street_address,
				'Marker.city' => $city,
				'Marker.state' => $state
			)
		));

		if (array_key_exists('Marker', $marker))
			return $marker['Marker'];

		return null;	
	}

	/*
	Takes a marker object as input.
	Creates a new field called 'coordinates' that is a mysql point datatype.
	Deletes the latitude and longitude fields from the array.
	*/
	public function ConvertLatLongToSpatialPoint(&$marker)
	{
		$db = ConnectionManager::getDataSource('default');
		$marker['Marker']['coordinates'] = (object) $db->expression("GeomFromText('POINT(" .
     		$marker['Marker']['latitude'] . " " . $marker['Marker']['longitude'] . ")')");
	}

	private function _getMarkerByStreetAddress($street_address, $city, $state)
	{
		$conditions = array('Marker.street_address' => $street_address,
							'Marker.city' => $city,
							'Marker.state' => $state);
		$markerMatch = $this->find('first', array(
	                     'conditions' => $conditions
	  	));


	  	return $markerMatch;
	}
}

?>