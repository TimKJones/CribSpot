<?php 
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {
	public $hasMany = array(
		'Listing' => array(
			'className' => 'Listing',
			'foreignKey' => 'user_id'
		)
	);
	public $belongsTo = 'University'; 	
	public $primaryKey = 'id';
	public $helpers = array('Html');

	public $validate = array (
		'id' => 'numeric',
		'user_type' => 'numeric',
		'password' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A password is required.'
				),
			'between' => array(
				'rule' => array('between',5,30),
				'message' => 'Must be between 5 and 30 characters'
				)

			),
		'first_name' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A first name is required.'
				),
			'between' => array(
				'rule' => array('between',1,50),
				'message' => 'Must be between 1 and 50 characters'
				),
			'alphaNumeric' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Names must only contain letters and numbers.'
				)
			),
		'last_name' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A last name is required.'
				),
			'between' => array(
				'rule' => array('between',1,50),
				'message' => 'Must be between 1 and 50 characters'
				),
			'alphaNumeric' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Names must only contain letters and numbers.'
				)
		),
		'company_name' => array(
			'between' => array(
				'rule' => array('between',0,50),
				'message' => 'Must be between 0 and 50 characters'
				),
			'alphaNumeric' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Names must only contain letters and numbers.'
				)
		),
		'street_address' => array(
			'between' => array(
				'rule' => array('between', 0, 255)
			)
		),
		'city' => array(
			'between' => array(
				'rule' => array('between', 0, 255)
			)
		),
		'state' => array(
			'between' => array(
				'rule' => array('between',0, 2),
				'message' => 'Must be 2 characters'
			)
		),
		'zipcode' => array(
        	'rule' => array('postal', null, 'us')
    	),
    	'website' => 'url',
		'email' => array(
			'email' => array(
        		'rule'    => array('email', true),
        		'message' => 'Please supply a valid email address.'
    			),
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'An email is required.'
				),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Someone already registered with that email.'
				)
			),
		'phone' => array(
			'phone' => array(
        		'rule' => array('phone', null, 'us'),
        		'message' => 'Please enter a valid phone number'
   				),
   			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A phone number is required.'
				),
   			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Someone already registered with that phone number. Try again.'
				)
			),
		'group_id' => 'alphaNumeric', 
		'university_id' => array(
			'isNumber' => array(
				'rule' => array('naturalNumber',true),
				'message' => 'Invalid building type'
				),
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A university selection is required.'
				)

		),
		'verified' => 'boolean',
		'number_email_confirmations_sent' => 'numeric',
		'university_verified' => 'boolean',
		'vericode' => 'alphaNumeric',
		'facebook_userid' => 'alphaNumeric', /* userids are null if not verified */
		'twitter_userid' => 'alphaNumeric',
		'linkedin_verified' => 'alphaNumeric',
		'last_login' => 'datetime',
		'preferred_university' => 'numeric',
		'created' => 'datetime',
		'modified' => 'datetime',
		'password_reset_token' => 'alphaNumeric',
		'password_reset_date' => 'datetime'
	);

	/* ---------- unit_style_options ---------- */
	const USER_TYPE_SUBLETTER = 0;
	const USER_TYPE_PROPERTY_MANAGER = 1;
	const USER_TYPE_NEWSPAPER_ADMIN = 2;
	


	public static function user_type($value = null) {
		$options = array(
		    self::USER_TYPE_SUBLETTER => __('Subletter',true),
		    self::USER_TYPE_PROPERTY_MANAGER => __('Property Manager',true),
		);
		return parent::enum($value, $options);
	}

	public function beforeSave($options = array()) {
		if(isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}

		return true;
	}

	public function FacebookVerify($user_id)
	{	
		die(debug($user_id));
		$conditions = array(
			'User.facebook_user_id' => $user_id,
		);

		/* If fb user id already exists, return error message to user */
		if (!$this->hasAny($conditions)){
			/*$user_id_query = $this->find('first', array(
			'conditions' => array('Favorite.listing_id' => $listing_id,
								  'Favorite.user_id'	=> $user_id),
			'fields' => 	array('favorite_id')));*/
		}
		else
			return false;
	}

	public function TwitterVerify($user_id, $auth_token, $auth_token_secret, $twitter_userid)
	{
		$fields = array('User.twitter_userid' => $twitter_userid,
				  'User.twitter_auth_token' => $auth_token,
				  'User.twitter_auth_token_secret' => $auth_token_secret
				 );

		// $conditions = array('User.id' => $user_id);
		// echo debug($fields);
		// echo debug($conditions);
		// $this->id = $user_id;
		$user = $this->get($user_id);
		// debug($user);
		$data = array(
			'id'=>$user['User']['id'],
			'twitter_userid'=>$twitter_userid,
			'twitter_auth_token'=>$auth_token,
			'twitter_auth_token_secret'=>$auth_token_secret,
			);

		// $this->saveField('twitter_userid', $twitter_userid);
		// $this->saveField('twitter_auth_token', $auth_token);
		// $this->saveField('twitter_auth_token_secret', $auth_token_secret);

		if($this->save($data) == false){
			debug($this->validationErrors);
		}
		$this->read();
	}

	public function getTwitterFollowersCount($user_id)
	{
		$twitter_data = null;
		$user = $this->get($user_id);

		$twitter_userid = $user['User']['twitter_userid'];
		$twitter_auth_token = $user['User']['twitter_auth_token'];
		$twitter_auth_token_secret = $user['User']['twitter_auth_token_secret'];

		$twitter_data = array();
		array_push($twitter_data, $twitter_auth_token, $twitter_auth_token_secret);
		return $twitter_data;
	}

	public function edit($data){
		if (!$this->save($data))
			CakeLog::write("saveUser", print_r($this->validationErrors, true));
		else
			CakeLog::write("saveUser", print_r($data, true));
		return $this->read();
	}

	public function get($user_id){
		return $this->find('first', array('conditions'=>'User.id='.$user_id));
	}


	//Returns a user object will all the sensitive information removed
	public function getSafe($user_id){
		$options = array();
		$options['conditions'] = array('User.id'=>$user_id);
		$options['fields'] = array ('User.first_name', 'User.facebook_userid', 'User.twitter_userid', 'User.university_verified', 'User.verified', 'User.university_id');
		$options['recursive'] = -1;
		return $this->find('first', $options);
	}

	/*
	Returns true if a user account exists with email=$email, false otherwise.
	*/
	public function EmailExists($email)
	{
		$emailFound = $this->find('first', array(
			'fields' => array('User.id'), 
			'conditions' => array('User.email' => $email)
		));

		return $emailFound != null;
	}

	/*
	Sets verified to true.
	Sets university_verified to true if $university_id is not null.
	*/
	public function VerifyUserEmail($user_id, $university_id)
	{
		$user = array();
		$user['id'] = $user_id;
		$user['verified'] = true;
		if ($university_id != null){
			$user['university_verified'] = true;
			$user['university_id'] = $university_id;
		}

		$user['User'] = $user;
		if (!$this->save($user)){
			$error = null;
			$error['user'] = $user;
			$error['validation'] = $this->validationErrors;
			$this->LogError($user_id, 26, $error);
			return array('error' => 
					'Looks like we had some issues verifying your email address...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email . ' . 
					'at help@cribspot.com. Reference error code 26');
		}

		return array('success' => '');
	}

	/*
	returns true if given id already exists.
	*/
	public function IdExists($id)
	{
		$idFound = $this->find('first', array(
			'fields' => array('User.id'), 
			'conditions' => array('User.id' => $id)
		));

		return $idFound != null;
	}

	/*
	returns true if there exists a row with the given $user_id and $vericode.
	returns false otherwise.
	*/
	public function VericodeIsValid($vericode, $id)
	{
		$idFound = $this->find('first', array(
			'fields' => array('User.id'), 
			'conditions' => array(
				'User.id' => $id,
				'User.vericode' => $vericode
			)
		));

		return $idFound != null;
	}

	/*
	Saves $password for $user_id
	*/	
	public function SavePassword($user_id, $password)
	{
		$user = array();
		$user['id'] = $user_id;
		$user['password'] = $password;
		$user['User'] = $user;
		if (!$this->save($user)){
			$error = null;
			$error['User'] = $user;
			$error['validationErrors'] = $this->validationErrors;
			$this->LogError($id, 32, $error);
			return array("error" => array('validation' => $this->validationErrors,
			'message' => 'Looks like we had some issues changing your password...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email . ' . 
					'at help@cribspot.com. Reference error code 32'));
		}

		return array('success'=>'');
	}

	/*
	Given a user's email address, returns that user's id, email, and first name.
	Returns null if $email doesn't exist.
	*/
	public function GetUserFromEmail($email)
	{
		$user = $this->find('first', array(
			'fields' => array('User.id', 'User.email', 'User.first_name', 'User.verified', 'User.vericode',
				'User.number_email_confirmations_sent'),
			'conditions' => array('User.email' => $email)
		));

		if ($user != null)
			return $user['User'];

		return null;
	}

	/*
	Increment number_email_confirmations_sent by one after sending email confirmation email
	*/
	public function IncrementNumberEmailConfirmationsSent($user_id)
	{
		$this->id = $user_id;
		$this->updateAll(array(
			'User.number_email_confirmations_sent' => 'User.number_email_confirmations_sent+1'
		));
	}

	/*
	Given a user_id, returns that user's email address
	*/
	public function GetEmailFromId($user_id)
	{
		$email = $this->find('first', array(
			'fields' => array('User.email'),
			'conditions' => array('User.id' => $user_id)
		));

		if ($email != null)
			return $email['User']['email'];

		return null;
	}

	/*
	Sets the password_reset_token and password_reset_date fields for user_id = $id
	*/
	public function SetPasswordResetToken($id)
	{
		$user = array();
		$user['password_reset_token'] = uniqid();
		$user['password_reset_date'] = date("Y-m-d H:i:s");
		$user['id'] = $id;
		$user['User'] = $user;
		if (!$this->save($user)){
			$error = null;
			$error['User'] = $user;
			$error['validationErrors'] = $this->validationErrors;
			$this->LogError($id, 29, $error);
			return array("error" => array('validation' => $this->validationErrors,
			'message' => 'Looks like we had some issues resetting your password...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email . ' . 
					'at help@cribspot.com. Reference error code 29'));
			return false;
		}

		return array('password_reset_token'=>$user['password_reset_token']);
	}

	public function IsValidResetToken($id, $reset_token)
	{
		$result = $this->find('first', array(
			'fields' => array('User.id'),
			'conditions' => array(
				'User.id' => $id,
				'User.password_reset_token' => $reset_token
		)));

		return $result != null;
	}

	/*
	Ensure that all necessary fields are present based on user type.
	Then saves user object
	*/
	public function RegisterUser($user)
	{
		$error = null;
		if (!$this->_validateUserRegister($user)){
			$error = null;
			$error['user'] = $user;
			$this->LogError(null, 45, $error);
			return array('error' => 
					'Looks like we had some issues creating your account...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email . ' . 
					'at help@cribspot.com. Reference error code 36');
		}

		if (!$this->save(array('User'=>$user))) {
			$error = null;
			$error['user'] = $user;
			$error['validationErrors'] = $this->validationErrors;
			CakeLog::write('validation', print_r($this->validationErrors, true));
			$this->LogError(null, 46, $error);
			return array('error' => 	
					'Looks like we had some issues creating your account...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email . ' . 
					'at help@cribspot.com. Reference error code 37');
		}

		return array('success'=>'');
	}

	/*
	Checks if user account with $email is verified.
	Returns an array with message
	*/
	public function EmailIsConfirmed($email)
	{
		$user = $this->GetUserFromEmail($email);
		if ($user == null)
			return array('error' => 'No user exists with that email address.');

		if ($user['verified'] != true)
			return array('error' => 'Your email address has not yet been confirmed. Please click the link provided in your confirmation email.',
				'error_type' => 'EMAIL_UNVERIFIED');
	
		return array('success' => '');
	}

	/*
	Returns a user object given a facebook id.
	Returns null if user doesn't exist.
	*/
	public function GetUserFromFacebookId($fb_id)
	{
		if ($fb_id == null)
			return null;

		$local_user = $this->find('first', array(
			'conditions' => array('facebook_userid' => $fb_id)
        ));

		return $local_user;
	}

	/*
	Attempts to save a new user object after facebook registration.
	*/
	public function SaveFacebookUser($user)
	{
		if (!$this->save($user)){
			$error = null;
			$error['user'] = $user;
			$error['validation'] = $this->validationErrors;
			$this->LogError(null, 48, $error);
			return array('error' => 
					'Looks like we had some issues logging you in with Facebook...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email . ' . 
					'at help@cribspot.com. Reference error code 39');
		}

		return array('success'=>'');
	}

	public function UpdateLastLogin($user_id)
	{
		date_default_timezone_set('America/New_York');
		$db = ConnectionManager::getDataSource('default');
		$now = $db->expression('NOW()');
		$this->id = $user_id;
		$this->saveField('last_login', $now);
	}

	public function SavePreferredUniversity($user_id, $university_id)
	{
		CakeLog::write("saving_university", $user_id . " " . $university_id);
		$this->id = $user_id;
		$this->saveField('preferred_university', $university_id);
	}

	public function GetPreferredUniversity($user_id)
	{
		$this->id = $user_id;
		$university_id = $this->find('first', array(
			'fields' => array('User.preferred_university'),
			'conditions' => array('User.id' => $user_id)
		));

		if ($university_id != null)
			return $university_id['User']['preferred_university'];
		return null;
	}

	/*
	Returns true if all fields are present (based on user type).
	Returns false otherwise.
	*/
	private function _validateUserRegister($user)
	{
		if (!array_key_exists('user_type', $user))
			return false;

		$required_fields = null;
		$user_type = intval($user['user_type']);
		if ($user_type === User::USER_TYPE_PROPERTY_MANAGER)
			$required_fields = array('company_name', 'website', 'phone', 'street_address', 'city', 'state');
		else if ($user_type === User::USER_TYPE_SUBLETTER)
			$required_fields = array('first_name', 'last_name');

		foreach ($required_fields as $value) {
			if (!array_key_exists($value, $user)){
				CakeLog::write("validate", $value . "; user=" . print_r($user, true));
				return false;
			}
				
		}

		return true;
	}
}
?>