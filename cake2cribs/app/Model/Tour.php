<?php 	

class Tour extends AppModel {
	public $name = 'Tour';
	public $primaryKey = 'id';
	/*public $hasMany = array(
		'UsersInTours' => array(
			'className' => 'UsersInTours',
			'foreignKey' => 'tour_id'
		),
	);*/

	public $validate = array(
		'id' => 'numeric',
		'user_id' => 'numeric', /* user that initiated this tour request */
		'date' => 'datetime', /* time this tour request is scheduled for */
		'confirmed' => 'boolean', /* whether or not this specific tour time has been confirmed */
		'created' => 'datetime',
		'modified' => 'datetime'
	);

	/*
	Saves a new row for each $time in $times
	*/
	public function SaveTour ($times, $user_id)
	{
		$formattedTimes = array();
		foreach ($times as $time){
			$newTime = array(
				'user_id' => $user_id,
				'date' => $time,
				'confirmed' => 0
			);
			if (!$this->save($newTime)) {
				$error = null;
				$error['formattedTimes'] = $formattedTimes;
				$error['validationErrors'] = $this->validationErrors;
				$this->LogError($user_id, 70, $error);
				return array("error" => array(
					'message' => 'Looks like we had an issue scheduling your tour. If the issue continues, ' .
					'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
						'at help@cribspot.com. Reference error code 70.'));
			}

			$this->create();
		}

		return array('success' => '');
	}
}

?>