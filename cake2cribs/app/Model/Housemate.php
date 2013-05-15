<?php 

class Housemate extends AppModel {
	//potentially link with users?
	public $belongsTo = array('Sublet','GenderType', 'StudentType');
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
		'student_type_id' => array(
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
				'rule' => array('between',0,250),
				'message' => 'Must be less than 250 characters'
				)
			),
		'gender_type_id' => array(
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid gender type.'
				)
			),
		//year (Freshman, Sophomore, etc)
		//must be between 1 and 250 characters
		//completed
		'year' => array(
			'between' => array(
				'rule' => array('between',0,250),
				'message' => 'Must be less than 250 characters'
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

	public function SaveHousemate($housemate)
	{
		if ($housemate['enrolled'] == "true")
			$housemate['enrolled'] = 1;
		else
			$housemate['enrolled'] = 0;

  		$housemate_to_save = array('Housemate' => $housemate);
  		if ($this->save($housemate_to_save))
  			return $this->id;
  		else
  		{
  			CakeLog::write("savingHousemate", "error: " . print_r($this->validationErrors, true));
  			return null;
		}	
	}

	public function BelongsToSubletId($housemate_id, $sublet_id)
	{
		$conditions = array('Housemate.id' => $housemate_id, 
							'Housemate.sublet_id' => $sublet_id);
		$this->contain();
		$has_sublet_id = $this->find('first', array('conditions' => $conditions, 'fields' => array('Housemate.id')));
		return $has_sublet_id != null;
	}
}
?>
