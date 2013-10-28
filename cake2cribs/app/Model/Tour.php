<?php 	

class Tour extends AppModel {
	public $name = 'Tour';
	public $primaryKey = 'id';
	public $belongsTo = array(
		'TourRequest' => array(
            'className'    => 'TourRequest',
            'foreignKey'   => 'tour_request_id',
            'dependent'    => true
        )
	);

	public $validate = array(
		'id' => 'numeric',
		'tour_request_id' => 'numeric',
		'date' => 'datetime', /* time this tour request is scheduled for */
		'confirmed' => 'boolean', /* whether or not this specific tour time has been confirmed */
		'confirmation_code' => 'alphaNumeric', /* Used in request from scheduler to confirm this tour */
		'created' => 'datetime',
		'modified' => 'datetime'
	);

	/*
	If the ($tour_id, $confirmation_code) pair is legit, set confirmed to true for this tour.
	*/
	public function ConfirmTour($tour_id, $confirmation_code)
	{
		/* Verify that this (tour_id, confirmation_code) pair is legit */
		$tourExists = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				'Tour.id' => $tour_id,
				'confirmation_code' => $confirmation_code
			)
		));

		$tourExists = array_key_exists('Tour', $tourExists);
		/* Get the user_id and listing_id of the user that scheduled this tour */
		$tourData = $this->find('first', array(
			'contain' => array('TourRequest'),
			'conditions' => array(
				'Tour.id' => $tour_id 
			),
			'fields' => array('TourRequest.listing_id', 'TourRequest.user_id')
		));		

		if (!$tourExists || $tourData === null || !array_key_exists('user_id', $tourData['TourRequest']) ||
			!array_key_exists('listing_id', $tourData['TourRequest']))
			return false;

		$user_id = $tourData['TourRequest']['user_id'];
		$listing_id = $tourData['TourRequest']['listing_id'];

		/* Set confirmed to 0 for all other tours for this ($user_id, $listing_id) combo */
		$this->_unconfirmAllToursForUser($user_id, $listing_id);

		/* Set confirmed to 1 for this tour */
		$this->id = $tour_id;
		$this->saveField('confirmed', true);

		return true;
	}

	/*
	Returns the tour object with the given id
	*/
	public function GetTourRequestFromTourId($tour_id)
	{
		$tour = $this->find('first', array(
			'conditions' => array('Tour.id' => $tour_id)
		));

		if (array_key_exists('Tour', $tour))
			return array(
				'tour_request_id' => $tour['Tour']['tour_request_id'],
				'listing_id' => $tour['TourRequest']['listing_id'],
				'user_id' => $tour['TourRequest']['user_id'],
				'tour' => $tour['Tour']
			);
	}

/* ---------------------------------- private ----------------------------------- */

	/* Set confirmed to false for all tours $user_id had requested for $listing_id */
	private function _unconfirmAllToursForUser($user_id, $listing_id)
	{
		$tours = $this->find('all', array(
			'contain' => array(),
			'conditions' => array(
				'TourRequest.user_id' => $user_id,
				'TourRequest.listing_id' => $listing_id
			)
		));

		foreach ($tours as &$tour){
			$tour['Tour']['confirmed'] = 0;
		}

		CakeLog::write('tours', print_r($tours, true));
		if (!$this->saveAll($tours))
			CakeLog::write('tourfailed', print_r($this->validationErrors, true));
	}
}

?>