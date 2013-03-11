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

	public function getAllCoordinates()
	{
		if (($markers = Cache::read('markers')) === false)
		{
			CakeLog::write('debug', 'updated cache');
			$this->UpdateCache();
		}

		$markers = Cache::read('markers');
		die(debug($markers));
		return json_encode($markers);
	}

	public function UpdateCache()
	{
		$this->contain();
		Cache::write('markers', $this->find('all'));
	}

}

?> 
