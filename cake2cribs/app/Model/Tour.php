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
	public function ConfirmTour($tour_id, $confirmation_code, $listing_id)
	{
		/* Verify that this (tour_id, confirmation_code) pair is legit */
		$tourExists = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				'id' => $tour_id,
				'confirmation_code' => $confirmation_code
			)
		));

		/* Get the user_id and listing_id of the user that scheduled this tour */
		$tourData = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				'id' => $tour_id 
			),
			'fields' => array('Tour.listing_id', 'Tour.user_id')
		));
		
		if ($tourExists !== null || $tourData === null || !array_key_exists('user_id', $tourData['User']) ||
			!array_key_exists('listing_id', $tourData['User']))
			return false;

		$user_id = $tourData['User']['user_id'];
		$listing_id = $tourData['User']['listing_id'];

		/* Set confirmed to 0 for all other tours for this ($user_id, $listing_id) combo */
		$this->_unconfirmAllToursForUser($user_id, $listing_id);

		/* Set confirmed to 1 for this tour */
		$this->id = $tour_id;
		$this->saveField('confirmed', true);

		return true;
	}

/* ---------------------------------- private ----------------------------------- */

	/* Set confirmed to false for all tours $user_id had requested for $listing_id */
	private function _unconfirmAllToursForUser($user_id, $listing_id)
	{
		$tours = $this->find('all', array(
			'contain' => array(),
			'conditions' => array(
				'user_id' => $user_id,
				'listing_id' => $listing_id
			)
		));

		foreach ($tours as &$tour){
			$tour['confirmed'] = 0;
		}

		$this->save($tours);
	}
}

?>