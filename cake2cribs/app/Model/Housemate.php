<?php 

class Housemate extends AppModel {
	//potentially link with users?
	public $belongsTo = array('Sublet', 'GenderType', 'StudentType');
	/*	'Sublet' => array(
			'className' => 'Sublet',
			'foreignKey' => 'sublet_id'
			)
	);*/
	public $primaryKey = 'id';

	public $validate = array (
		'id' => 'alphaNumeric', 
		//section for sublet_id
		// required, must be natural number.
		//completed
		'sublet_id' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A sublet ID is required.'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid sublet ID'
				)
			),
		'quantity' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A quantity of housemates must be entered.'
				),
			'isNumber' => array(
				'rule' => array('naturalNumber', true),
				'message' => 'Invalid number of housemates'
				)
			),
		//section for enrolled
		//is required.
		//completed
		'enrolled' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'You must state whether the housemate is enrolled in university or not.'
				)
			),
		//section for student type
		//must be a natural number.
		//completed
		'student_type' => array(
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid student type.'
				)
			),
		//section for major
		//must be between 1 and 250 characters.
		//completed
		'major' => array(
			'between' => array(
				'rule' => array('between',1,250),
				'message' => 'Must be between 1 and 250 characters'
				)
			),
		//section for seeking
		//must be between 1 and 250 characters
		//completed
		'seeking' => array(
			'between' => array(
				'rule' => array('between',1,250),
				'message' => 'Must be between 1 and 250 characters'
				)
			),
		'gender' => array(
			'between' => array(
				'rule' => array('between',4,6),
				'message' => 'Invalid gender.'
				)
			),
		//type
		//must be between 1 and 250 characters, I have no idea what this is lol
		//completed
		'type' => array(
			'between' => array(
				'rule' => array('between',1,250),
				'message' => 'Must be between 1 and 250 characters'
				)
			)
	);
	
	public function getHousematesForSublet($sublet_id)
	{
		$housemates = $this->find('all', array(
			'conditions' => array('Housemate.sublet_id' => $sublet_id)
		));

		return $housemates;
	}

}
?>
