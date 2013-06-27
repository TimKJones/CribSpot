<?php 
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {
	public $hasMany = array(
		'Sublet' => array(
			'className' => 'Sublet',
			'foreignKey' => 'user_id'
		)
	);
	public $belongsTo = 'University'; 	
	public $primaryKey = 'id';

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
		'university_verified' => 'boolean',
		'vericode' => 'alphaNumeric',
		'facebook_userid' => 'alphaNumeric', /* userids are null if not verified */
		'twitter_userid' => 'alphaNumeric',
		// 'twitter_auth_token' => 'alphaNumeric',
		'twitter_auth_token_secret' => 'alphaNumeric',
		'linkedin_verified' => 'alphaNumeric',
		'created' => 'datetime',
		'modified' => 'datetime',
		'password_reset_token' => 'alphaNumeric',
		'password_reset_date' => 'datetime'
	);

	/* ---------- unit_style_options ---------- */
	const USER_TYPE_SUBLETTER = 0;
	const USER_TYPE_PROPERTY_MANAGER = 1;

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

	public function LinkedinVerify($user_id)
	{
		
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
}
?>