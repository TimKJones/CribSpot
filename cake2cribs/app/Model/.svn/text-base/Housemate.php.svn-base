<?php 

class Housemate extends AppModel {
	//potentially link with users?
	public $belongsTo = 'Sublet';

	public $validate = array (
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
	
}
?>
