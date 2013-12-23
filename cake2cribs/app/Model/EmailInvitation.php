<?php 	

class EmailInvitation extends AppModel {
	public $name = 'EmailInvitation';
	public $primaryKey = 'id';
	public $actsAs = array('Containable');
	public $validate = array(
		'id' => 'numeric',
		'email' => array(
			'email' => array(
        		'rule'    => array('email', true),
        		'message' => 'Please supply a valid email address.'
    			)
		),
		'inviter_id' => 'numeric',
		'created' => 'datetime',
		'modified' => 'datetime'
	);

	/*	
	Saves information on invitations from $user_id to their $housemates.
	*/
	public function InviteFriends($user_id, $emails)
	{	
		$invitations = array();
		foreach ($emails as $email){
			$nextRow = array(
				'EmailInvitation' => array(
					'inviter_id' => $user_id,
					'email' => $email
				)
			);

			// $nextRow['TourRequest'] = $tourRequest;
			array_push($invitations, $nextRow);
		}

		if (!$this->saveAll($invitations)) {
			$error = array();
			$error['Invitation'] = $invitations	;
			$error['validationErrors'] = $this->validationErrors;
			$this->LogError($user_id, 73, $error);
			return array("error" => array('validation' => $this->validationErrors,
				'message' => 'Looks like we had some issues sending your invitations. If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 73.'));
		}

		return array('success' => '');
	}
}

?>