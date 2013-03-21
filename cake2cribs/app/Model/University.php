<?php 

class University extends AppModel {
	public $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'id'
		),
		'Sublet' => array(
			'className' => 'Sublet',
			'foreignKey' => 'id'
		)
	);

	public function getSchools()
	{
		return $this->find('all', 
			array('fields' => array('name'))
			);

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
