<?php 	

class BlacklistedIp extends AppModel {
	public $name = 'BlacklistedIp';
	public $primaryKey = 'id';
	public $validate = array(
		'id' => 'numeric',
		'ip_address' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A first name is required.'
			),
			'rule'    => array('ip', 'IPv4')
		),
		'email' => array(
			'email' => array(
        		'rule'    => array('email', true),
        		'message' => 'Please supply a valid email address.'
    		)
		),
		'created' => 'datetime',
		'modified' => 'datetime'
	);

	/*
	Inserts a new blacklisted_ip with the given ip address and email
	*/
	public function Add($ip_address, $email)
	{
		$blacklisted_ip = array('BlacklistedIp' => array(
			'ip_address' => $ip_address,
			'email' => $email
		));
		if (!$this->save($blacklisted_ip)){
			$error = null;
			$error['BlacklistedIp'] = $user;
			$error['validation'] = $this->validationErrors;
			$this->LogError($user_id, 75, $error);
			return array('error' => 'Failed to add blacklisted ip');
		}

		return array('success' => '');
	}
}
?>