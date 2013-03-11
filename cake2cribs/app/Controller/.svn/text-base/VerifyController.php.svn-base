<?php
class VerifyController extends AppController {
	public $helpers = array('Html', 'Js');
	public $components = array(/*'Twitteroauth.Twitter', 'Crypter' */'Session');
	public $uses = array('User');


	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');
	}
	function index()
	{
		/*
			Pull from Users table information on whether users are verified 
			Set variables to present this data in view
		*/

/* ----------------------- FACEBOOK ----------------------------*/
		$loginUrl = $this->facebook->getLoginUrl(
			array(
				'scope' => 'email',
				'redirect_uri' => 'http://localhost/verify?facebook_verified=true'
				));
		$logoutUrl = $this->facebook->getLogoutUrl();
			$this->set('loginUrl', $loginUrl);
		$this->set('logoutUrl', $logoutUrl);
		$userId = $this->facebook->getUser();
		$this->set('fb_userId', $userId);

/* -------------- TWITTER -----------------------------*/
		App::import('Vendor', 'twitter/twitteroauth');
		App::import('Vendor', 'twitter/twconfig');
		App::uses('Xml', 'Utility');

		$twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		$request_token = $twitteroauth->getRequestToken('http://dev2cribs.aws.af.cm/verify?twitter_confirmed=true');
		//echo debug($twitteroauth);
		// Requesting authentication tokens, the parameter is the URL we will be redirected to
		//$request_token = $twitteroauth->getRequestToken('');
		// Saving them into the session
		if (array_key_exists('oauth_token', $request_token) && array_key_exists('oauth_token_secret', $request_token))
		{
			$this->Session->write('oauth_token', $request_token['oauth_token']);
			$this->Session->write('oauth_token_secret', $request_token['oauth_token_secret']);
		}

		$connection = null;
		$url = null;
		if (array_key_exists('twitter_confirmed', $_GET) && array_key_exists('oauth_token', $_GET) && array_key_exists('oauth_verifier', $_GET))
		{
			if ($this->Session->read('oauth_token') && $this->Session->read('oauth_token_secret'))
			{
				$connection = new TwitterOAuth(CONSUMER_KEY, 
												CONSUMER_SECRET,
												$_REQUEST['oauth_token'],
												$this->Session->read('oauth_token_secret'));



				// get long-term credentials from twitter
				$token_credentials = $connection->getAccessToken($_REQUEST['oauth_verifier']);
				if (array_key_exists('oauth_token', $token_credentials))
				{
					echo debug($token_credentials);
					$twitterUserId = null;
					$this->TwitterVerify($token_credentials, $twitterUserId);
					$content = $connection->get('account/verify_credentials');
					$full_name = $content->name;
					$username = $content->screen_name;
					$follower_count = $content->followers_count;
					$this->set("success", true);
					$this->set("fullName", $full_name);
					$this->set("userName", $username);
					$this->set("followerCount", $follower_count);
					//echo debug($content);
				}
				else
				{
					echo "Twitter validation failed.";
					$this->set("success", false);
				}
			}
		}

		// If everything goes well..
		if ($twitteroauth->http_code == 200) 
		    $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
		else 
		    $url = null;

		$this->set('twitterLoginUrl', $url);
		//echo debug($twitteroauth);

/* ---------------------------------- Linkedin ----------------------------------- */
/*
	    App::Import("Vendor", "linkedin/linkedin");
	    $config['base_url']             =   'http://localhost/verify?linkedin_verified=true';
	    $config['callback_url']         =   'http://localhost/verify?linkedin_verified=true';
	    $config['linkedin_access']      =   '2rrh6emzm3mi';
	    $config['linkedin_secret']      =   'WMKg5Mkw2Vnfxh6C';

	    # First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
	    $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );

	    # Now we retrieve a request token. It will be set as $linkedin->request_token
	    $linkedin->getRequestToken();
	    $this->Session->write('requestToken', serialize($linkedin->request_token));

	    # With a request token in hand, we can generate an authorization URL, which we'll direct the user to
	    echo '<a href = ' . $linkedin->generateAuthorizeUrl() . '>Connect with Linkedin</a>';
	    //header("Location: " . $linkedin->generateAuthorizeUrl());

	    if (isset($_REQUEST['oauth_verifier'])){
	        $this->Session->write('oauth_verifier', $_REQUEST['oauth_verifier']);
	        $linkedin->request_token    =   unserialize($this->Session->read('requestToken'));
	        $linkedin->oauth_verifier   =   $this->Session->read('oauth_verifier');
	        $linkedin->getAccessToken($_REQUEST['oauth_verifier']);

	        $this->Session->write('oauth_access_token', serialize($linkedin->access_token));
	        //header("Location: " . $config['callback_url']);
	        exit;
	   }
	   else
	   {
	        $linkedin->request_token    =   unserialize($this->Session->read('requestToken'));
	        $linkedin->oauth_verifier   =   $this->Session->read('oauth_verifier');
	        $linkedin->access_token     =   unserialize($this->Session->read('oauth_access_token'));
	   }
	    
	   # You now have a $linkedin->access_token and can make calls on behalf of the current member
	    $xml_response = $linkedin->getProfile("~:(id, first-name,last-name,headline,picture-url)");
	    echo $xml_response;
	    # Now we retrieve a request token. It will be set as $linkedin->request_token
	    echo "here";
	    $linkedin->getRequestToken();
	    echo "there";
	    $this->Session->write('requestToken', serialize($linkedin->request_token));
*/
	}

	function index2()
	{
		/*
			Pull from Users table information on whether users are verified 
			Set variables to present this data in view
		*/


		$loginUrl = $this->facebook->getLoginUrl(
			array(
				'scope' => 'email',
				'redirect_uri' => 'http://localhost/verify?facebook_verified=true'
				));
		$logoutUrl = $this->facebook->getLogoutUrl();
			$this->set('loginUrl', $loginUrl);
		$this->set('logoutUrl', $logoutUrl);
		$userId = $this->facebook->getUser();
		$this->set('fb_userId', $userId);

		// Twitter
		//App::import('Vendor', 'twitter/twitteroauth');
		//App::import('Vendor', 'twitter/twconfig');
		$twitteroauth = new TwitterOAuth($this->Twitter->OAuth->consumer->key, 
			$this->Twitter->OAuth->consumer->secret);
		$request_token = $twitteroauth->getRequestToken('http://localhost/verify?twitter_confirmed=true');
		//echo debug($twitteroauth);
		// Requesting authentication tokens, the parameter is the URL we will be redirected to
		//$request_token = $twitteroauth->getRequestToken('');
		// Saving them into the session

		$this->Session->write('oauth_token', $request_token['oauth_token']);
		$this->Session->write('oauth_token_secret', $request_token['oauth_token_secret']);

		$connection = null;
		$url = null;
		if (array_key_exists('twitter_confirmed', $_GET))
		{
			if ($this->Session->read('oauth_token') && $this->Session->read('oauth_token_secret'))
			{
				echo debug($this->Session->read('oauth_token'));
				echo debug($this->Session->read('oauth_token_secret'));
				$connection = new TwitterOAuth($this->Twitter->OAuth->consumer->key, 
												$this->Twitter->OAuth->consumer->secret,
												$request_token['oauth_token'],
												$request_token['oauth_token_secret']);
				// get long-term credentials from twitter
				//echo debug($connection);
				$token_credentials = $connection->getAccessToken($_REQUEST['oauth_verifier']);
				echo debug($token_credentials);
				$twitterUserId = null;
				$this->TwitterVerify($token_credentials, $twitterUserId);
				//echo debug($token_credentials);
				//echo debug($connection);
				$content = $connection->get('account/verify_credentials');
				//echo debug($content);
			}
		}

		// If everything goes well..
		if ($twitteroauth->http_code == 200) 
		    $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
		else 
		    $url = null;

		$this->set('twitterLoginUrl', $url);
		//echo debug($twitteroauth);
	}

	function getCurrentUserId()
	{
		return 1;
	}

	function FacebookVerify()
	{
		$this->set("response", $this->getCurrentUserId(), $this->User->FacebookVerify($this->Session->read('user')));
	}

	function TwitterVerify($token, $twitterUserId)
	{
		//TODO: First need to verify that these are valid
		$this->User->TwitterVerify($this->getCurrentUserId(), enCrypt($token['oauth_token']), enCrypt($token['oauth_token_secret']), $twitterUserId);	
	}

	function LinkedinVerify()
	{
		$this->User->LinkedinVerify($this->getCurrentUserId());
	}

	function TwitterLogin()
	{
		require("twitter/twitteroauth.php");
		require 'config/twconfig.php';
		require 'config/functions.php';
		session_start();

		if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) 
		{
			// We've got everything we need
			$twitteroauth = new TwitterOAuth($this->Twitter->OAuth->consumer->key, $this->Twitter->OAuth->consumer->secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
			// Let's request the access token
			$access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
			// Save it in a session var
			$_SESSION['access_token'] = $access_token;
			// Let's get the user's info
			$user_info = $twitteroauth->get('account/verify_credentials');
			// Print user's info
			echo '<pre>';
			print_r($user_info);
			echo '</pre><br/>';
			if (isset($user_info->error)) {
			// Something's wrong, go back to square 1  
			header('Location: login-twitter.php');
			} 
			else 
			{
				$uid = $user_info->id;
				$username = $user_info->name;
				$user = new User();
				$userdata = $user->checkUser($uid, 'twitter', $username);
				if(!empty($userdata))
				{
					session_start();
					$_SESSION['id'] = $userdata['id'];
					$_SESSION['oauth_id'] = $uid;
					$_SESSION['username'] = $userdata['username'];
					$_SESSION['oauth_provider'] = $userdata['oauth_provider'];
					header("Location: home.php");
				}
			}
		} 
		else 
		{
			// Something's missing, go back to square 1
			header('Location: login-twitter.php');
		}
	}
}
?>