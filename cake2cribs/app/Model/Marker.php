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
		'alternate_name' => array(							/*******TODO: NEED TO ADD MAX LENGTH FOR THIS AND CHECK FOR ONLY ALPHANUMERIC CHARACTERS */
			'rule' => 'alphaNumeric'		/*            NOTE: 'title' is the previous 'alternate_name' field */
		), 	
		'unit_type' => 'alphaNumeric',
		'address' => array(
			'rule'    => 'alphaNumeric'
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
		$markers = $this->find('all');
		$filtered_markers = array();
		CakeLog::write("loadMarkers", print_r($markers, true));
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

}

?> 
