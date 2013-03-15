<?php 
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {
	public $hasMany = array(
		'Sublet' => array(
			'className' => 'Sublet',
			'foreignKey' => 'sublet_id'
		)
	);
	public $belongsTo = 'University'; 	
	public $primaryKey = 'id';

	public $validate = array (
		'id' => 'alphaNumeric', 
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
		'twitter_auth_token' => 'alphaNumeric',
		'twitter_auth_token_secret' => 'alphaNumeric',
		'linkedin_verified' => 'alphaNumeric',
		'created' => 'datetime',
		'modified' => 'datetime',
		'password_reset_token' => 'alphaNumeric',
		'password_reset_date' => 'datetime'
		);
	public function beforeSave($options = array()) {
		if(isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		
		return true;
	}


/*TODO: Figure out how to get userid of currently logged in user - necessary for table updates here */

	public function FacebookVerify($user_id)
	{
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

		$conditions = array('User.id' => $user_id);
		echo debug($fields);
		echo debug($conditions);
		$this->id = $user_id;
		$this->saveField('twitter_userid', $twitter_userid);
		$this->saveField('twitter_auth_token', $auth_token);
		$this->saveField('twitter_auth_token_secret', $auth_token_secret);
		echo debug(false);
	}

	public function getTwitterFollowersCount($user_id)
	{
		$twitter_data = null;
		/*$twitter_data = $this->find('first', array(
			'conditions' => array('User.id' => $user_id),
			'fields' => 	array('twitter_userid', 'twitter_auth_token', 'twitter_auth_token_secret')));
*/
		$twitter_userid = $twitter_data['User']['twitter_userid'];
		$twitter_auth_token = $twitter_data['User']['twitter_auth_token'];
		$twitter_auth_token_secret = $twitter_data['User']['twitter_auth_token_secret'];

		$twitter_userid = '381100229';
		$twitter_auth_token = '381100229-sua9NBNe08ZH3HtdrKb9Ks7MF8xcrUh5ISno88M1';
		$twitter_auth_token_secret = 'PkjMGkMjyztpqKV3So18EsPZpqBBrw9AF0PQWB0pBs';

		$twitter_data = array();
		array_push($twitter_data, $twitter_auth_token, $twitter_auth_token_secret);
		return $twitter_data;
	}

	public function LinkedinVerify($user_id)
	{
		
	}
	
}
?>
