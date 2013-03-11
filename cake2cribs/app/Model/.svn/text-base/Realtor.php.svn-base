<?php 

class Realtor extends AppModel {
	public $name = 'Realtor';
	public $primaryKey = 'realtor_id';
	public $actsAs = array('Containable');
	public $hasMany =  array(
						'Listing' => array(
								'className' => 'Listing', 
								'foreignKey' => 'realtor_id'
						)
	);
	public $validate = array(
		'realtor_id' => 'alphaNumeric',
		'company' => array(
						'rule'=>array('maxLength', '255'), 
						'message'=>array('Company name must be no larger than 255 characters long.')
		), 
		'username' => array(
						'usernameRule-1'=>array(
							'rule'=>array('between', 8, 15),
							'message'=>array("Username must be at least 8 characters.")
						)
		), 
		'password' => array(
						'passwordRule-1'=>array(
							'rule'=>array('between', 8, 15),
							'message'=>array("Password must be at least 8 characters.")
						)
		), 
		'email' => array(
						'rule' => array('email', true),
						'message' => array("Please supply a valid email address.")
		)
	);	

	public function LoadRealtor($realtor_id)
	{
		$this->contain();
		$realtorQuery = $this->find('all', array(
			'conditions' => array(
				'Realtor.realtor_id' => $realtor_id)));

		return $realtorQuery;
	}	
}
?>
