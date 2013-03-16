<?php

class School extends AppModel {
	public $name = 'School';
	public $actsAs = array('Containable');
	public $primaryKey = 'school_id';
	/*public $hasMany = array(
						'Listing' => array(
							'className' => 'Listing', 
							'foreignKey' => 'school_id'
						)
	);*/

	public $validate = array(
		'school_id' =>'alphaNumeric', 
		'sw_lat' => 'decimal',
		'sw_long' => 'decimal',
		'ne_lat' => 'decimal',
		'ne_long' => 'decimal'
	);

	public function getSchools()
	{
		return $this->find('all');
	}

	public function getTargetLatLong($school_id)
	{
		$lat_long = $this->find('first', array(
			'conditions' => array('School.school_id' => $school_id),
			'fields' => 	array('center_lat', 'center_long')));
		CakeLog::write("School", print_r($lat_long, true));
		$return_val = array(
		'latitude' => $lat_long['School']['center_lat'],
		'longitude' => $lat_long['School']['center_long']);

		return $return_val; 
	}
}

?> 
