<?php 

class University extends AppModel {
	public $actsAs = array('Containable');
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
			'fields' => 	array('latitude', 'longitude', 'city', 'state'
		)));
		CakeLog::write("School", print_r($lat_long, true));
		if (!array_key_exists('University', $lat_long))
			throw new NotFoundException();

		$return_val = array(
			'latitude' => $lat_long['University']['latitude'],
			'longitude' => $lat_long['University']['longitude'],
			'city' => $lat_long['University']['city'],
			'state' => $lat_long['University']['state']
		);

		return $return_val; 
	}

	public function getIdfromName($school_name)
	{
		$id = $this->find('first', array(
			'conditions' => array('University.name' => $school_name),
			'fields' => 	array('id')));
		if (!array_key_exists('University', $id))
			throw new NotFoundException();
		
		return $id['University']['id'];
	}

	public function getUniversityFromEmail($email)
	{
		$domain = substr($email, strrpos($email, '@') + 1);
		$university = $this->find('first', array(
			'conditions' => array('University.domain' => $domain))
		);

		if ($university != null)
			return $university['University']['name'];
		else
			return "";
	}

	public function LoadAllUniversities()
	{
		$options['fields'] = array('University.name', 'University.id', 'University.city', 'University.state', 'University.latitude', 'University.longitude');
 		$options['recursive'] = -1;
 		$options['orderby'] = array('University.name' => 'desc');
 		$universities = $this->find('all', $options);
 		return $universities;
	}

	/*
	Returns the university_id associated with $email
	Returns null if no match found.
	*/
	public function GetIdFromEmail($email)
	{
		$domain = substr($email, strrpos($email, '@') + 1);
		$university = $this->find('first', array(
			'conditions' => array('University.domain' => $domain),
			'fields' => array('University.id')
		));

		if ($university != null && array_key_exists('University', $university))
			return $university['University']['id'];
		
		return null;
	}

	public function getUniversitiesAround($lat, $lon, $radius){
		$this->contain();
		$this->virtualFields = array(
    		'distance' => "( 3959 * acos( cos( radians($lat) ) * cos( radians( University.latitude ) ) * cos( radians( University.longitude ) - radians($lon) ) + sin( radians($lat) ) * sin( radians( University.latitude ) ) ) )"
		);
		$data = $this->find('all', array('fields' => array('distance', 'name', 'id'), 'conditions' => array('distance <' => $radius)));
		return $data;

	}
}
?>
