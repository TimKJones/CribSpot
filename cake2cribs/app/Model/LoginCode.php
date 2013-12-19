<?php 	

class LoginCode extends AppModel {
	public $name = 'LoginCode';
	public $primaryKey = 'id';
	public $actsAs = array('Containable');

	public $belongsTo = array(
		'User' => array(
            'className'    => 'User',
            'foreignKey'   => 'user_id',
            'dependent'    => true
    ));

	public $validate = array(
		'id' => 'numeric',
		'user_id' => 'numeric',
		'code' => 'alphaNumeric',
		'is_permanent' => 'boolean',
		'created' => 'datetime',
	);

	public function Add($user_id, $code)
	{
		$this->save(array(
			'LoginCode' => array(
				'user_id' => $user_id,
				'code' => $code
			)
		));
	}

	public function InvalidateCode($user_id)
	{
		$this->id = $user_id;
		$this->code = uniqid();
	}

	public function IsValidLoginCode($id, $login_code)
	{
		$result = $this->find('first', array(
			'conditions' => array(
				'LoginCode.user_id' => $id,
				'LoginCode.code' => $login_code
		)));

		if ($result === null || !array_key_exists('LoginCode', $result) || !array_key_exists('code', $result['LoginCode']))
			return array('error' => '');

		/* If this code is not permanent, make sure its created date is within 2 weeks of today */
		if (!array_key_exists('is_permanent', $result['LoginCode']) || intval($result['LoginCode']['is_permanent']) !== 1){
			/* login_code is date sensitive */
			$now = date('Y-m-d G:i:s');
			$created = strtotime($result['LoginCode']['created']);
			$diff = abs(strtotime($now) - $created);

			/* Invalidate code if older than 2 weeks */
			if ($diff > (2 * 7 * 24 * 60 * 60)) {
				$this->set('code', uniqid());
				return array('error' => 'LOGIN_CODE_EXPIRED');
			}
		}

		/* It's valid.*/
		return array('success' => '');
	}

	/*
	Initialize login_code for all property managers
	*/
	public function InitializePMLoginCodes($user_ids = null)
	{
		foreach ($user_ids as $user_id) {
			$user = array(
				'user_id' => $user_id,
				'code' => uniqid()
			);
			if (!$this->save($user)){
				CakeLog::write('failedUpdatingResetTokens', print_r($this->validationErrors, true));
				return false;
			}

			$this->create();
		}

		return true;		
	}

	/*
	Returns a login code for the given user_id
	*/
	public function GetCodeByUserId($user_id)
	{	
		$user = $this->find('first', array(
			'contain' => array(),
			'fields' => array('LoginCode.code'),
			'conditions' => array(
				'LoginCode.user_id' => $user_id
			)
		));	
		
		if (array_key_exists('LoginCode', $user) && array_key_exists('code', $user['LoginCode']))
			return $user['LoginCode']['code'];		

		return null;				
	}

}