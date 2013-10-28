<?php 	

class TourRequest extends AppModel {
	public $name = 'TourRequest';
	public $primaryKey = 'id';
	public $hasMany = array(
		'Tour' => array(
			'className' => 'Tour',
			'foreignKey' => 'tour_request_id'
		),
		/*'TourInvitation' => array(
			'className' => 'TourRequest',
			'foreignKey' => 'tour_request_id'
		),*/
	);

	public $validate = array(
		'id' => 'numeric',
		'user_id' => 'numeric', /* user that initiated this tour request */
		'listing_id' => 'numeric',
		'created' => 'datetime',
		'modified' => 'datetime'
	);

	/*
	Saves a new row for each $time in $times
	*/
	public function SaveTour ($times, $user_id, $listing_id)
	{
		$formattedTimes = array();
		$tourRequest = array(
			'TourRequest' => array(
				'user_id' => $user_id,
				'listing_id' => $listing_id
			),
			'Tour' => array(),
			'TourInvitation' => array()
		);
		foreach ($times as $time){
			$newTime = array(
				'user_id' => $user_id,
				'listing_id' => $listing_id,
				'date' => $time,
				'confirmed' => 0,
				'confirmation_code' => uniqid()
			);
			array_push($tourRequest['Tour'], $newTime);
		}
CakeLog::write('tourrequest', print_r($tourRequest, true));
		if (!$this->saveAll($tourRequest)) {
			$error = null;
			$error['formattedTimes'] = $formattedTimes;
			$error['validationErrors'] = $this->validationErrors;
			$this->LogError($user_id, 70, $error);
			return array("error" => array(
				'message' => 'Looks like we had an issue scheduling your tour. If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 70.'));
		}

		return array('success' => $this->read());
	}

/* ---------------------------------- private ----------------------------------- */

}

?>