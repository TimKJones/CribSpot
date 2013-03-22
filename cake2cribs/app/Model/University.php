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
			'conditions' => array('University.id' => $school_id),
			'fields' => 	array('latitude', 'longitude')));
		CakeLog::write("School", print_r($lat_long, true));
		$return_val = array(
			'latitude' => $lat_long['University']['latitude'],
			'longitude' => $lat_long['University']['longitude']
			);

		return $return_val; 
	}

	public function getIdfromName($school_name)
	{
		$id = $this->find('first', array(
			'conditions' => array('University.name' => $school_name),
			'fields' => 	array('id')));
		return $id['University']['id'];
	}

	public function getUniversityFromEmail($email)
	{
		$domain = substr($email, strrpos($email, '@') + 1);
		$university = $this->find('first', array(
			'conditions' => array('University.domain' => $domain))
		);

		return $university['University']['name'];
	}
}
?>
