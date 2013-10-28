<?php 	

class TourInvitation extends AppModel {
	public $name = 'TourInvitation';
	public $primaryKey = 'id';
	/*public $belongsTo = array(
        'TourRequest' => array(
            'className'    => 'TourRequest',
            'foreignKey'   => 'tour_request_id'
        )
	);*/
	public $actsAs = array('Containable');
	public $validate = array(
		'id' => 'numeric',
		'tour_request_id' => 'numeric',
		'inviter_id' => 'numeric',
		'invitee_facebook_id' => 'numeric',
		'invitee_name' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A first name is required.'
				),
			'between' => array(
				'rule' => array('between',1,50),
				'message' => 'Must be between 1 and 50 characters'
				),
			'rule' => array('custom', '/^[a-z\- ]*$/i') 
		),
		'invitee_email' => array(
			'email' => array(
        		'rule'    => array('email', true),
         		'message' => 'Please supply a valid email address.'
    		)
		),
		'created' => 'datetime',
		'modified' => 'datetime'
	);

		/*	
	Saves information on tour invitations from $user_id to their $housemates.
	$housemates is of the form {name, email}
	*/
	public function InviteHousematesToTour($user_id, $housemates, $tourRequest)
	{	
		$invitations = array();
		$tour_request_id = null;
		if (array_key_exists('id', $tourRequest))
			$tour_request_id = $tourRequest['id'];
		foreach ($housemates as $housemate){
			$nextRow = array(
				'TourInvitation' => array(
					'inviter_id' => $user_id,
					'tour_request_id' => $tour_request_id
				)
			);
			if (array_key_exists('email', $housemate) && !empty($housemate['email']))
				$nextRow['TourInvitation']['invitee_email'] = $housemate['email'];

			if (array_key_exists('name', $housemate) && !empty($housemate['name']))
				$nextRow['TourInvitation']['invitee_name'] = $housemate['name'];

			$nextRow['TourRequest'] = $tourRequest;
			array_push($invitations, $nextRow);
		}

		if (!$this->saveAll($invitations)) {
			$error = array();
			$error['Invitation'] = $invitations	;
			$error['validationErrors'] = $this->validationErrors;
			$this->LogError($user_id, 71, $error);
			return array("error" => array('validation' => $this->validationErrors,
				'message' => 'Looks like we had some issues inviting your housemates. If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 71.'));
		}

		return array('success' => '');
	}

	/*
	Returns a list of objects of the form (name, email).
	People returned are the recipients of tour invitations for the given tour request
	*/
	public function GetPeopleForConfirmationEmail($tour_request_id)
	{
		$people = $this->find('all', array(
			'conditions' => array(
				'tour_request_id' => $tour_request_id
			),
			'fields' => array(
				'TourInvitation.invitee_name',
				'TourInvitation.invitee_email'
			)
		));

		$trimmedPeople = array();
		foreach ($people as $person){
			array_push($trimmedPeople, array(
				'name' => $person['TourInvitation']['invitee_name'],
				'email' => $person['TourInvitation']['invitee_email']
			));
		}

		return $trimmedPeople;
	}
}

?>