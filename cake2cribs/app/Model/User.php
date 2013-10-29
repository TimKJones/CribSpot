<?php 
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {
	public $hasMany = array(
		'Listing' => array(
			'className' => 'Listing',
			'foreignKey' => 'user_id'
		)
	);
	public $belongsTo = array('University');
	public $actsAs = array('Containable');
	public $primaryKey = 'id';
	public $helpers = array('Html');

	public $validate = array(
		'id' => 'numeric',
		'facebook_id' => 'numeric',
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
			'rule' => array('custom', '/^[a-z\- ]*$/i') 
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
			'rule' => array('custom', "/^[a-z\-\ \']*$/i") 
		),
		'company_name' => array(
			'between' => array(
				'rule' => array('between',0,50),
				'message' => 'Must be between 0 and 50 characters'
				),
			'rule' => array('custom', "/^[a-z0-9 \.&\'\/\_\-]*+/i")
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
    			)/*,
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'An email is required.'
				),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Someone already registered with that email.'
				)*/
			),
		'phone' => array(
			'phone' => array(
        		'rule' => array('phone', null, 'us'),
        		'message' => 'Please enter a valid phone number'
   				)/*,
   			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'A phone number is required.'
				),
   			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Someone already registered with that phone number. Try again.'
				)*/
			),
		'phone_verified' => 'boolean',
		'phone_confirmation_code' => 'alphaNumeric',
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
		'twitter_userid' => 'alphaNumeric',
		'linkedin_verified' => 'alphaNumeric',
		'last_login' => 'datetime',
		'preferred_university' => 'numeric',
		'registered_university' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'student_year' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'created' => 'datetime',
		'modified' => 'datetime',
		'password_reset_token' => 'alphaNumeric',
		'password_reset_date' => 'datetime'
	);

	/* ---------- user_type ---------- */
	const USER_TYPE_SUBLETTER = 0;
	const USER_TYPE_PROPERTY_MANAGER = 1; /* NOTE: messages/emailUserAboutMessage uses a hard-coded '1' for this */
	const USER_TYPE_NEWSPAPER_ADMIN = 2;
	const USER_TYPE_UNIVERSITY_ADMIN = 3;
	public static function user_type($value = null) {
		$options = array(
		    self::USER_TYPE_SUBLETTER => __('Subletter',true),
		    self::USER_TYPE_PROPERTY_MANAGER => __('Property Manager',true),
		    self::USER_TYPE_UNIVERSITY_ADMIN => __('University Admin',true)
		);
		return parent::enum($value, $options);
	}

	/* ---------- year ---------- */
	const USER_STUDENT_YEAR_FRESHMAN = 0;
	const USER_STUDENT_YEAR_SOPHOMORE = 1; /* NOTE: messages/emailUserAboutMessage uses a hard-coded '1' for this */
	const USER_STUDENT_YEAR_JUNIOR = 2;
	const USER_STUDENT_YEAR_SENIOR = 3;
	const USER_STUDENT_YEAR_GRADUATE_STUDENT = 4;
	const USER_STUDENT_YEAR_OTHER = 5;
	public static function year($value = null) {
		$options = array(
		    self::USER_STUDENT_YEAR_FRESHMAN => __('Freshman',true),
		    self::USER_STUDENT_YEAR_SOPHOMORE => __('Sophomore',true),
		    self::USER_STUDENT_YEAR_JUNIOR => __('Junior',true),
		    self::USER_STUDENT_YEAR_SENIOR => __('Senior',true),
		    self::USER_STUDENT_YEAR_GRADUATE_STUDENT => __('Graduate Student',true),
		    self::USER_STUDENT_YEAR_OTHER => __('Other',true)
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

	public function edit($user){
		CakeLog::write('savinguser', print_r($user, true));
		if ($user['User']['id'] === 0 || !$this->save($user)){
			$error = null;
			$error['User'] = $user;
			$error['validationErrors'] = $this->validationErrors;
			$user_id = null;
			if (array_key_exists('User', $user) && array_key_exists('id', $user['User']))
				$user_id = $user['User']['id'];

			$this->LogError($user_id, 47, $error);
			return array("error" => array('validation' => $this->validationErrors,
				'message' => 'Looks like we had an issue editing your account. If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 47.'));
		}

		return array('success' => '');
	}

	public function get($user_id){
		return $this->find('first', array('conditions'=>'User.id='.$user_id));
	}


	//Returns a user object will all the sensitive information removed
	public function getSafe($user_id){
		$options = array();
		$options['conditions'] = array('User.id'=>$user_id);
		$options['fields'] = array ('User.first_name', 'User.facebook_id', 'User.twitter_userid', 'User.university_verified', 'User.verified', 'User.university_id', 'User.user_type', 'User.company_name');
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
	Returns true if a user account exists with email=$email, false otherwise.
	*/
	public function FBIdExists($fb_id)
	{
		$idFound = $this->find('first', array(
			'fields' => array('User.facebook_id'), 
			'conditions' => array('User.facebook_id' => $fb_id)
		));

		return $idFound != null;
	}

	/*
	Sets verified to true.
	Sets university_verified to true if $university_id is not null.
	*/
	public function VerifyUserEmail($user_id, $university_id)
	{
		$user = array();
		if ($user_id === null){
			$error = null;
			$this->LogError($user_id, 64, $error);
			return array('error' => 
					'Looks like we had some issues verifying your email address...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 64.');
		}
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
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 26.');
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
		$user['verified'] = 1; /* Verify the user's email */
		$new_user = array('User' => $user);
		if (!$this->save($new_user)){
			$error = null;
			$error['User'] = $new_user;
			$error['validationErrors'] = $this->validationErrors;
			$this->LogError($id, 32, $error);
			return array("error" => array('validation' => $this->validationErrors,
			'message' => 'Looks like we had some issues changing your password...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 32.'));
		}

		return array('success'=>'');
	}

	/*
	Verify the user with the given $user_id
	*/
	public function VerifyEmail($user_id)
	{
		$this->id = $user_id;
		$this->saveField('verified', 1);
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
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 29.'));
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

	public function IsValidLoginCode($id, $login_code)
	{
		$result = $this->find('first', array(
			'fields' => array('User.id'),
			'conditions' => array(
				'User.id' => $id,
				'User.login_code' => $login_code
		)));

		return $result != null;
	}

	public function SetLoginCode($user_id, $code)
	{
		$this->id = $user_id;
		$this->saveField('login_code', $code);
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
			$this->LogError(null, 36, $error);
			return array('error' => 
					'Looks like we had some issues creating your account...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 36.');
		}

		$fields_to_strip = array('first_name', 'last_name', 'email', 'company_name', 'website', 'phone', 'street_address',
			'city');
		foreach ($fields_to_strip as $field){
			if (array_key_exists($field, $user))
				$user[$field] = rtrim($user[$field]);
		}

		$pm_fields_to_delete = array('registered_university', 'student_year');
		if (array_key_exists('user_type', $user) && $user['user_type'] == 1){
			/* Unset these fields that are causing PM save to fail validation */
			foreach ($pm_fields_to_delete as $field){
				if (array_key_exists($field, $user))
					unset($user[$field]);
			}
		}

		if (!$this->save(array('User'=>$user))) {
			$error = null;
			$error['user'] = $user;
			$error['validationErrors'] = $this->validationErrors;
			$this->LogError(null, 37, $error);
			return array('error' => 	
					'Looks like we had some issues creating your account...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 37.');
		}

		return array('success'=>$this->get($this->id));
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
			'conditions' => array('facebook_id' => $fb_id)
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
			$this->LogError(null, 59, $error);
			return array('error' => 
					'Looks like we had some issues logging you in with Facebook...but we want to help! If the problem continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 59.');
		}

		/* return the user object we just saved */
		$user = $this->find('first', array(
			'conditions' => array('User.id' => $this->id)
		));
		return array('user' => $user);
	}

	public function UpdateLastLogin($user_id)
	{
		if ($user_id === null)
			return;
		date_default_timezone_set('America/New_York');
		$db = ConnectionManager::getDataSource('default');
		$now = $db->expression('NOW()');
		$this->id = $user_id;
		$this->saveField('last_login', $now);
	}

	public function SavePreferredUniversity($user_id, $university_id)
	{
		if ($user_id === null)
			return;
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
	Used during user importing
	Returns a user with the given company name
	*/
	public function GetUserByCompanyName($company_name)
	{
		if (empty($company_name))
			return null;

		$user = $this->find('first', array(
			'conditions' => array('User.company_name' => $company_name)
		));

		if ($user != null){
			/* Unset fields that shouldn't be modified */
			unset($user['User']['password']);
		}

		return $user;
	}

	/*
	Initialize password_reset_tokens for all property managers
	*/
	public function InitializePMPasswordResetTokens($university_ids = null)
	{
		$conditions = array('User.user_type' => User::USER_TYPE_PROPERTY_MANAGER);
		if ($university_ids != null)
			$conditions['User.pm_associated_university'] = $university_ids;

		$users = $this->find('all', array(
			'contain' => array(),
			'fields' => array('User.id'),
			'conditions' => $conditions
		));

		foreach ($users as &$user){
			$user['User']['login_code'] = uniqid();
		}

		foreach ($users as $savedUser){
			$just_user = array('User' => $savedUser['User']);
			if (!$this->save($just_user)){
				CakeLog::write('failedUpdatingResetTokens', print_r($this->validationErrors, true));
				return false;
			}
		}

		return true;		
	}

	/*
	Invalidate the previous password reset token
	Called after a user has successfully reset their password from an email link
	*/	
	public function ResetPasswordToken($user_id)
	{
		$this->saveField('password_reset_token', uniqid());
	}

	/*
	Returns all property managers with pm_associated_university set to a value in $university_ids
	*/	
	public function GetPropertyManagersByAssociatedUniversity($university_ids)
	{
		$users = $this->find('all', array(
			'contain' => array(),
			'conditions' => array(
				'User.pm_associated_university' => $university_ids,
				'User.user_type' => 1
			)
		));

		return $users;
	}

	/* Set received_welcome_email to true for the email specified */
	public function ReceivedWelcomeEmail($user_id)
	{
		$this->id = $user_id;
		$this->saveField('received_welcome_email', 1);
	}

	/* Returns all student years in an array */
	public function GetYears()
	{
		return $this->year(AppModel::GET_ALL_OF_THIS_TYPE);
	}

	/*
	Updates a user's phone credentials before or after confirmation text sent to them.
	*/
	public function UpdatePhoneFields($phone, $code, $confirmed, $user_id)
	{
		$this->id = $user_id;
		$success = $this->save(array(
			'User'=>array(
				'phone' => $phone,
				'phone_confirmation_code' => $code,
				'confirmed' => 0
			)
		));
		if (!$success){
			$error = null;
			$error['phone'] = $phone;
			$error['code'] = $code;
			$error['confirmed'] = $confirmed;
			$error['validationErrors'] = $this->validationErrors;
			$this->LogError($user_id, 72, $error);
			return array("error" => array('validation' => $this->validationErrors,
				'message' => 'Looks like we had an issue verifying your phone number. If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 72.'));
		}

		return array('success' => '');
	}

	/*
	Makes sure $code is the correct phone_confirmation_code for $user_id.
	If $code is correct, set phone_verified to 1.
	If $code is incorrect, return error message.
	*/	
	public function CheckPhoneCodeValidityAndConfirm($code, $user_id)
	{
		$this->id = $user_id;
		$correct_code = $this->read('phone_confirmation_code');
		if ($correct_code === null)
			return array('error' => "Hmmm...that code doesn't seem right. Try and re-send the message if you think we messed up!");

		$correct_code = $correct_code['User']['phone_confirmation_code'];
		if (strcmp($code,$correct_code) === 0) {
			/* Set user's phone as verified */
			$this->saveField('phone_verified', 1);
			return array('success' => '');
		}
		else{
			return array('error' => "Hmmm...that code doesn't seem right. Try and re-send the message if you think we messed up!");
		}
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
			$required_fields = array('company_name', 'phone', 'street_address', 'city', 'state');
		else if ($user_type === User::USER_TYPE_SUBLETTER)
			$required_fields = array('first_name', 'last_name', 'registered_university', 'student_year');

		foreach ($required_fields as $value) {
			if (!array_key_exists($value, $user)){
				return false;
			}
				
		}

		return true;
	}
}
?>