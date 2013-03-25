<?php
	class AccountController extends AppController {
		public $helpers = array('Html');
		public $uses = array('User');
		public $components= array('Session','Auth', 'Cookie');

		function beforeFilter(){
			parent::beforeFilter();
	    	if(!$this->Auth->user()){
	        	//$this->flash("You may not access this page until you login.", array('controller' => 'users', 'action' => 'login'));
	        	$this->Session->setFlash(__('Please login to view your dashbaord.'));
	        	$this->redirect(array('controller'=>'users', 'action'=>'login'));
	    	}
		}
		
	 	public function index(){
			$directive['classname'] = 'account';
        	$json = json_encode($directive);
			$this->Cookie->write('dashboard-directive', $json);
			$this->redirect('/dashboard');
			
	 	}

	 	public function verifyTwitter(){

			App::import('Vendor', 'twitter/twitteroauth');
			App::import('Vendor', 'twitter/twconfig');
			App::uses('Xml', 'Utility');

			$twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

			// NOTE: this is a workaround so that I (Michael Stratman) can get the redirect url to work
			// I run the application out of port 8888 since I have multiple application servers running on my machine at once
			// I need to specify a port number
			if(array_key_exists('SERVER_PORT', $_SERVER)){
				$request_token = $twitteroauth->getRequestToken('http://127.0.0.1:'.$_SERVER['SERVER_PORT'].'/account/verifyTwitter');	
			}else{
				$request_token = $twitteroauth->getRequestToken('http://127.0.0.1/account/verifyTwitter');
			}
			
			//echo debug($twitteroauth);
			// Requesting authentication tokens, the parameter is the URL we will be redirected to
			//$request_token = $twitteroauth->getRequestToken('');
			// Saving them into the session

			$success = false;
			if (array_key_exists('oauth_token', $request_token) && array_key_exists('oauth_token_secret', $request_token))
			{
				$this->Session->write('oauth_token', $request_token['oauth_token']);
				$this->Session->write('oauth_token_secret', $request_token['oauth_token_secret']);
			}
			
			$connection = null;
			$url = null;
			if (array_key_exists('oauth_token', $_GET) && array_key_exists('oauth_verifier', $_GET))
			{
				if ($this->Session->read('oauth_token') && $this->Session->read('oauth_token_secret'))
				{
					$connection = new TwitterOAuth(CONSUMER_KEY, 
													CONSUMER_SECRET,
													$_REQUEST['oauth_token'],
													$this->Session->read('oauth_token_secret'));



					// get long-term credentials from twitter
					$token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
					if (array_key_exists('oauth_token', $token))
					{
						$content = $connection->get('account/verify_credentials');
						$full_name = $content->name;
						$username = $content->screen_name;
						$follower_count = $content->followers_count;
						$user_id = $content->id;
						$this->User->TwitterVerify($this->Auth->User('id'), $token['oauth_token'], $token['oauth_token_secret'], $content->id);	
						$success = true;
					}
				}

				if($success){
					$this->redirect('/account');
				}else{
					echo "Twitter Validation Failed";
				}
			}
		}

	 	// Ajax function that will return a json response with a url that'll will let the user verify their account
	 	public function getTwitterVerificationUrl(){
	 		App::import('Vendor', 'twitter/twitteroauth');
			App::import('Vendor', 'twitter/twconfig');
			App::uses('Xml', 'Utility');
	 		$twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
			
			if(array_key_exists('SERVER_PORT', $_SERVER)){
				$request_token = $twitteroauth->getRequestToken('http://127.0.0.1:'.$_SERVER['SERVER_PORT'].'/account/verifyTwitter');	
			}else{
				$request_token = $twitteroauth->getRequestToken('http://127.0.0.1/account/verifyTwitter');
			}

			if (array_key_exists('oauth_token', $request_token) && array_key_exists('oauth_token_secret', $request_token))
			{	
				$this->Session->write('oauth_token', $request_token['oauth_token']);
				$this->Session->write('oauth_token_secret', $request_token['oauth_token_secret']);
			}
			
			$url = null;
			if ($twitteroauth->http_code == 200) 
		    	$url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);

		   	$json_response = json_encode(array(
		   		'twitter_url'=>$url
		   	));

		   	$this->layout = 'ajax';
 			$this->set('response', $json_response);

	 	}

	}