<?php

class Marker extends AppModel {
	public $name = 'Marker';
	public $actsAs = array('Containable');
	public $primaryKey = 'marker_id';
	public $belongsTo = array('BuildingType');
	public $hasMany = array(
						'Sublet' => array(
							'className' => 'Sublet', 
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

	public function getAllMarkers($target_lat_long)
	{
		if (($markers = Cache::read('markers')) === false)
		{
			CakeLog::write('debug', 'updated cache');
			$this->UpdateCache();
		}

		$markers = Cache::read('markers');
		$this->contain();

		//Find all visible markers
		$markers = $this->find('all', array('conditions'=>'Marker.visible=1'));
		$filtered_markers = array();
		CakeLog::write("loadMarkers", print_r($markers, true));
		
		// TODO change this functionality to use a custom sql query
		// to eliminate the need to filter all the markers everytime

		for ($i = 0; $i < count($markers); $i++)
		{
			$lat = $markers[$i]['Marker']['latitude'];
			$long = $markers[$i]['Marker']['longitude'];
			$distance = $this->distance($lat, $long, $target_lat_long['latitude'], $target_lat_long['longitude']);
			CakeLog::write('distance', $distance);
			if ($distance < $this->RADIUS)
			{
				array_push($filtered_markers, $markers[$i]);
			}
			//else
				//CakeLog::write('distance', print_r($markers[$i], true) . " | " . $distance);
				
		}

		//die(debug($markers));
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
	Finds marker_id by street_address
	*/
	public function FindMarkerId($marker)
	{
		$street_address = $marker['street_address'];
		$city = $marker['city'];
		$state = $marker['state'];
		$conditions = array('Marker.street_address' => $street_address,
							'Marker.city' => $city,
							'Marker.state' => $state);
		$markerMatch = $this->find('first', array(
	                     'conditions' => $conditions,
	    	                 'fields' => 'Marker.marker_id'
	  	));

	  	if($markerMatch['Marker']['visible']==0){
	  		//marker was previously made invisible so we
	  		//need to make it visible again
	  		$markerMatch['Marker']['visible']=1;
	  		if(!$this->save($markerMatch)){
	  			CakeLog::write("Marker", "Making marker ". $markerMatch['Marker']['id'] ." invisible failed");
	  		}
	  	}

	  	if ($markerMatch == null)
	  	{
	  		// create new marker
	  		$marker_to_save = array('Marker' => $marker);
	  		if ($this->save($marker_to_save))
	  		{
	  			return $this->id;
	  		}
	  		else
	  		{
	  			CakeLog::write("savingMarker", "error: " . print_r($this->validationErrors, true));
	  			return null;
	  		}
	  	}
	  	else
	  		return $markerMatch['Marker']['marker_id'];
	}

	public function hide($marker){
		$marker['visible'] = 0;
		$this->save($marker);
	}

}

?>