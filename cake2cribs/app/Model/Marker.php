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
						),
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
	Finds marker_id by address.
	If no marker exists, creates new marker.
	Returns marker_id on success; error message on failure.
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
	    	                 'fields' => array('Marker.marker_id', 'Marker.visible')
	  	));

	  	if($markerMatch != null){
	  		/* Marker for this address already exists */
	  		if (!array_key_exists('Marker', $markerMatch) || 
	  			!array_key_exists('visible', $markerMatch['Marker']) || 
	  			!array_key_exists('marker_id', $markerMatch['Marker'])){
	  			/* TODO: Log error info here. */
	  			return array('error' => 'failed to save marker');
	  		}

	  		if ($markerMatch['Marker']['visible']==0){
	  			/* Marker exists but is invisible. Make it visible. */
	  			$markerMatch['Marker']['visible']=1;
		  		if(!$this->save($markerMatch)){
		  			/* TODO: Log important error info here */
		  			return array('error' => 'failed to save marker 2');
		  		}
	  		}
		  		
		  	return $markerMatch['Marker']['marker_id'];
	  	}
	  	else {
	  		/* Marker for this address doesn't exist, so create a new one. */
	  		if ($this->save(array('Marker' => $marker)))
	  			return $this->id;
	  		else {
	  			/* TODO: Log important error info here. */
	  			return array('error' => 'failed to saved marker 3');
	  		}
	  	}
	}

	public function hide($marker){
		$marker['visible'] = 0;
		$this->save($marker);
	}

}

?>