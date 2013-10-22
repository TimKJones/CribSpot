<?php 	

class FeaturedPM extends AppModel {
	public $name = 'FeaturedPM';
	public $primaryKey = 'id';
	public $belongsTo = array(
		'User' => array(
            'className'    => 'User',
            'foreignKey'   => 'user_id'
        ),
        'University' => array(
            'className'    => 'University',
            'foreignKey'   => 'university_id'
        )
	);

	public $validate = array(
		'id' => 'numeric',
		'user_id' => 'numeric',
		'university_id' => 'numeric'
	);

	/*
	Returns all user_ids associated with this university_id
	*/
	public function GetPMsByUniversityID($university_id)
	{
		$users = $this->find('all', array(
			'fields' => array('FeaturedPM.user_id'),
			'contain' => array(),
			'conditions' => array('FeaturedPM.university_id' => $university_id)
		));

		$user_ids = array();
		foreach ($users as $user){
			if (array_key_exists('FeaturedPM', $user) && array_key_exists('user_id', $user['FeaturedPM']))
				array_push($user_ids, $user['FeaturedPM']['user_id']);
		}

		return $user_ids;
	}
}

?>