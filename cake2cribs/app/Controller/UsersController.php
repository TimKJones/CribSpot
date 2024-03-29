<?php
class UsersController extends AppController {
    public $helpers = array('Html', 'Js');
    public $uses = array('User', 'University', 'UnreadMessage', 'Favorite', 'LoginCode', 'Listing');
    public $components= array('Email', 'RequestHandler', 'Cookie', 'Twilio.Twilio');
    private $MAX_NUMBER_EMAIL_CONFIRMATIONS_SENT = 3; /* max # of email confirmations to send */

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add');
        $this->Auth->allow('Login');
        $this->Auth->allow('Register');
        $this->Auth->allow('AjaxRegister');
        $this->Auth->allow('VerifyEmailRedirect');
        $this->Auth->allow('ResetPassword');
        $this->Auth->allow('AjaxResetPassword');
        $this->Auth->allow('ResetPasswordRedirect');
        $this->Auth->allow('AjaxChangePassword');
        $this->Auth->allow('AjaxLogin');
        $this->Auth->allow('AjaxEditUser');
        $this->Auth->allow('ResendConfirmationEmail');
        $this->Auth->allow('AttemptFacebookLogin');
        $this->Auth->allow('PropertyManagerSignup');
        $this->Auth->allow('IsLoggedIn');
        $this->Auth->allow('PMLogin');
        $this->Auth->allow('welcome');
        $this->Auth->allow('sublet');
        $this->Auth->allow('PMAdmin');
        $this->Auth->allow('interestedInAgent');
    }

		public function interestedInAgent()
		{
			if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
				return;
			if(!$this->request->isPost())
				return;

      $email = $this->request->data['email'];
      $phone = $this->request->data['phone'];
			$this->set('email', $email);
			$this->set('phone', $phone);
      $this->SendEmail('noreply@cribspot.com', 'alex@cribspot.com', 'A user wants your D Alex', 'interested_in_agent', 'both');
      $this->set('response', json_encode(array()));
		}

    /* 
    Share a listing with another user.
    this function is called by dragging a listing to the user's hotlist.
    it is different from InvitationsController, but has similar logic.
    */ 
    public function share()
    {
        $friend_id = $this->request->data['friend'];
        $listing_id = $this->request->data['listing'];

        // $response = $this->User->shareListing($friend_id, $listing_id);

        $friend = $this->User->find('first', array('conditions' => array('User.id' => $friend_id)));
        if (empty($friend)) {
            $this->response->statusCode('404');
            $this->set('response', json_encode(array('success' => false)));
        }
        else {
            $first_name = $this->Auth->User('first_name');
            $last_name = $this->Auth->User('last_name');

            $this->set('first_name', $first_name);
            $this->set('last_name', $last_name);

            // the template wants these variables named so
            $this->set('inviter_first_name', $first_name);
            $this->set('inviter_last_name', $last_name);

            $this->set('listing', $listing_id);

            $subject = "Check out this property on Cribspot!";

            // checking for this might not be necessary, 
            // since GetListing() will log an error if it cannot find the listing.
            if (!is_null($listing_id)) {
                $listing_obj = $this->Listing->GetListing($listing_id);
                if(isset($listing_obj[0]['Image'][0]['image_path'])) {
                    $img_url = 'http://www.cribspot.com/' . $listing_obj[0]['Image'][0]['image_path'];
                }
                if(!is_null($listing_obj)) {
                    $listing_name = $listing_obj[0]['Marker']['street_address'];
                    $subject = "$first_name  $last_name wants you to check out $listing_name on Cribspot!";
                }
                else {
                    $subject = "$first_name  $last_name wants you to check out a property on Cribspot!";
                }
                $this->set('listing_name', $listing_name);
                $this->set('listing', $listing_id);
            }
            else {
                $this->set('listing_name', 'check out this listing!');
                $this->set('listing', $listing_id);
            }

            // template name and a few other things are defined in here
            $this->_sendShareEmail($this->Auth->User(), $friend['User']['email'], $subject);

            $this->set('response', json_encode(array('success' => true)));
        }

        $this->render('json_response');
    }

    /*
    Get all users that match a given name, provided by query string
    called for the typeahead when adding users to hotlist.
    */
    public function getAllForName()
    {
        $name = $this->request->query['name'];
        $users = $this->User->findByNameFuzzy($name);
        // CakeLog::write('HOTLIST', print_r($users));
        $this->set('response', json_encode($users));
        $this->render('json_response');
    }

    /*
    Get Hotlist for current user
    */
    public function hotlist()
    {
        $user_id = $this->Auth->User('id');
        $response = $this->User->getHotlist($user_id);
        $this->set('response', json_encode($response));
    }

    /*
    Remove a user from the hotlist.
    The record in users_friends is kept, but the 'friend' flag is set to 0
    */
    public function removeFromHotlist() 
    {
        $user_id = $this->Auth->User('id');
        $friend_id = $this->request->data['friend'];
        $response = $this->User->removeFromHotlist($user_id, $friend_id);

        $this->set('response', json_encode($response));
        $this->render('hotlist');
    }

    public function add()
    {
        $this->redirect(array('action' => 'login', "signup"));
    }

    public function PropertyManagerSignup()
    {
        //$this->redirect(array('action' => 'login', "signup"));
        $this->redirect(array('action' => 'login', "signup", "pm"));
    }

    // Sets the directive to view account information
    public function AccountInfo()
    {
        $directive['classname'] = 'account';
        $json = json_encode($directive);
        $this->Cookie->write('dashboard-directive', $json);
        $this->redirect('/dashboard');
    }

    /*
    */
    public function welcome($user_type = "student")
    {
        if (!array_key_exists('id', $this->request->query) || !array_key_exists('reset_token', $this->request->query))
            $this->redirect('/');

        $id = $this->request->query['id'];
        $reset_token = $this->request->query['reset_token'];
        if (!$this->User->IsValidResetToken($id, $reset_token)){
            CakeLog::write("ErrorResetPasswordRedirect", $id . "; " . $reset_token);
            $flash_message['method'] = "Error";
            $flash_message['message'] = "That reset password link does not seem to be legitimate!";
            $json = json_encode($flash_message);
            $this->Cookie->write('flash-message', $json);
            $this->redirect('/users/login?invalid_link=true');
        }

        $this->set('id', $id);
        $this->set('reset_token', $reset_token);
    }

    public function sublet()
    {
        $user = $this->Auth->User();
        if ($user !== null)
            $this->redirect(array('controller' => 'sublets', 'action' => 'create'));
    }

    /*
    Given facebook access_token, which we use to get facebook id.
    Attempts to log in the current user by checking for a local user with the returned facebook id.
    If user exists, log in the user and return 'LOGGED_IN'.
    If user doesn't exist, store the facbeook id in the 'FB.id' session variable and return 'NOT_LOGGED_IN'
    */
    public function AttemptFacebookLogin()
    {
        if ($this->request === null || $this->request->data === null || !array_key_exists('signedRequest', $this->request->data)){
            $error = null;
            $error['request'] = $this->request;
            $this->LogError(null, 69, $error);
            $response = array('error' => "Looks like we had some problems logging you in with Facebook, but don't worry! You can still use our regular login to create an account in under 30 seconds.");
            $this->set('response', json_encode($response));
            return;
        }

        /* Get user's facebook id using the access token we got from the client */
        $accessToken = $this->request->data['accessToken'];
        $url = 'https://graph.facebook.com/me?access_token=' . $accessToken;
        $fb_user = urldecode(file_get_contents($url));
        $fb_user = json_decode($fb_user);
        $fb_id = $fb_user->id;

        /* See if there is already a user with the given facebook id */
        $local_user = $this->User->GetUserFromFacebookId($fb_id);
        if ($local_user != null && array_key_exists('User', $local_user) && array_key_exists('email', $local_user['User'])) {
            $this->_login($local_user);
            $data = $this->_getUserDataForAjaxLogin($local_user['User']);
            $response = array(
                'success' => 'LOGGED_IN',
                'account_exists' => true,
                'data' => $data
            );
            $this->set('response', json_encode($response));
            return;
        }

        /* 
        User has not yet created an account with this facebook id.
        Check to see if email has been returned to facebook.
        */
        if (empty($fb_user->email)){
            $response = array(
                'success' => 'NOT_LOGGED_IN',
                'account_exists' => false,
                'data' => array(
                    'first_name' => $fb_user->first_name,
                    'last_name' => $fb_user->last_name,
                    'img_url' => 'https://graph.facebook.com/'.$fb_id.'/picture?width=80&height=80'
                )
            );

            $this->set('response', json_encode($response));
            return;
        }

        /*
        facebook email exists.
        Give them a random password and log them in.
        */
        $user['user_type'] = 0;
        $user['verified'] = 0;
        $user['group_id'] = 1;
        $user['vericode'] = uniqid();
        $user['login_code'] = uniqid();     
        $user['first_name'] = $fb_user->first_name;
        $user['last_name'] = $fb_user->last_name;
        $user['facebook_id'] = $fb_user->id;
        $user['password'] = uniqid();
        $user['email'] = $fb_user->email;

        $response = $this->User->RegisterUser($user);
        $savedUser = null;
        if (array_key_exists('error', $response)) {
            $this->set('response', json_encode($response));
            return;
        }
        else if (array_key_exists('success', $response)) {
            $savedUser = $response['success'];
            $this->_login($savedUser);
        }

        $this->_savePreferredUniversity($this->Auth->User('id'));
        
        /* Get their img url to throw into login modal */
        $img_url = "/img/head_large.jpg";
        if (!empty($fb_id))
            $img_url = "https://graph.facebook.com/".$fb_id."/picture?width=80&height=80";

        $data = $this->_getUserDataForAjaxLogin($savedUser['User']);
        $response = array(
            'success' => 'LOGGED_IN',
            'data' => $data,
            'account_exists' => false
        );

        $this->_sendWelcomeEmail($user);
        $this->set('response', json_encode($response));
    }



    /*
    User submits registration data here.
    Returns success, or array of columns that failed validation.
    */
    public function AjaxRegister()
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
                return;

        $this->layout = 'ajax';
        if(!$this->request->isPost()){
            CakeLog::write('SECURITY', 'User called AjaxRegister without a post request');
            $this->redirect(array('controller' => 'landing', 'action' => 'index'));
            return;
        }

        /* Make sure data was submitted properly */
        if (!$this->request || !$this->request->data || !array_key_exists('User', $this->request->data)){
            $response = array('error' => 'Failed to register. Contact help@cribspot.com if the error persists. Reference error code 24');
            $this->set('response', json_encode($response));
            return;
        }

        $user = $this->request->data['User'];
        /* Check if email has already been registered */
        if ($this->_emailAlreadyRegistered($user['email'])){
            $response = array(
                'error' => 'This email has already been registered. If you already have an account, please login.',
                'error_type' => 'EMAIL_EXISTS'
            );
            $this->set('response', json_encode($response));
            return;
        }

        $user['verified'] = 0;
        $user['group_id'] = 1;
        $user['vericode'] = uniqid();
        $user['login_code'] = uniqid();     

        $response = $this->User->RegisterUser($user);
        $savedUser = null;
        if (array_key_exists('error', $response)) {
            $this->set('response', json_encode($response));
            return;
        }
        else if (array_key_exists('success', $response)) {
            $savedUser = $response['success'];
            $this->_login($savedUser);
        }

        /* Create a new user object and save it */

        $data = $this->_getUserDataForAjaxLogin($savedUser['User']);
        $response = array(
            'success' => 'LOGGED_IN',
            'data' => $data
        );

        $this->_sendWelcomeEmail($user);
        $this->set('response', json_encode($response));
        $this->_savePreferredUniversity($this->User->id);
    }

    /*
    Checks if the current user is logged in.
    Returns a code indicating their state.
    If logged in, also returns the data necessary to update page elements following an ajax login.
    */  
    public function IsLoggedIn()
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
                return;

        $data = null;
        $user = $this->Auth->User();
        if ($user !== null)
            $data = $this->_getUserDataForAjaxLogin($user);

        $success = 'NOT_LOGGED_IN';
        if ($this->Auth->loggedIn())
            $success = 'LOGGED_IN';

        $response = array(
            'success' => $success,
            'data' => $data
        );

        $this->set('response', json_encode($response));
    }

    /*
    Logs user in via ajax.
    Returns success, or array of columns that failed validation.
    */
    public function AjaxLogin($user=null)
    {
        if(!$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $this->layout = 'ajax';
        if(!$this->request->isPost()) {
            /* User cannot login without using a post request. Redirect to landing page */
            CakeLog::write('SECURITY', 'User called AjaxLogin without a post request');
            $this->set('response', '');
            return;
        }

        /* Ensure that email address is included in $this->request->data */
        if (!array_key_exists('User', $this->request->data) ||
            !array_key_exists('email', $this->request->data['User'])) {
            CakeLog::write("LogInErrors", "Email was not given in AjaxLogin data.");
            $response = array('error' => 'Login failed. Contact help@cribspot.com if the error persists. Reference error code 38');
            $this->set('response', json_encode($response));
            return;
        }

        if ($this->Auth->login()) {
            $user = $this->Auth->User();
            $first_log_in_ever = ($user['last_login'] === null);
            $this->User->UpdateLastLogin($this->Auth->User('id'));
            $this->_savePreferredUniversity($this->Auth->User('id'));
            $data = $this->_getUserDataForAjaxLogin($user);
            $response = array(
                'success' => 'LOGGED_IN',
                'data' => $data
            );
            $this->set('response', json_encode($response));
            return;
        }

        $response = array('error' => 'Invalid username or password.');
        $this->set('response', json_encode($response));
        return;
    }

    /*
    Attempt to logout the current user.
    */
    public function Logout()
    {
        $this->autoRender = false;
        $this->Session->destroy();
        $this->Auth->logout();
        $this->redirect('/');
    }

    /*
    User submits email address of account for which to reset password.
    Returns success or error message.
    */
    public function AjaxResetPassword()
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
                return;

        $this->layout = 'ajax';
        if ($this->request == null || 
            $this->request->data == null || 
            !array_key_exists('email', $this->request->data)){
            CakeLog::write("ErrorAjaxResetPassword", "Error code: 27;" . print_r($this->request, true));
            $this->set('response', json_encode(array('error' => 
                'Hmmm...something went wrong when trying to reset your password. ' .
                'If the error continues, message us via the tab along the bottom of the screen or ' . 
                'contact help@cribspot.com.. Reference error code 27.')));
            return;
        }

        /* Get user_id from given email address and check its validity */
        $email = $this->request->data['email'];
        $user = $this->User->GetUserFromEmail($email);
        if (!$user){
            $this->set('response', json_encode(array('error' => 
                "We couldn't find anyone signed up with that email address!")));
            return;
        }

        /* Set password_reset_token and password_reset_date for this user_id */
        $response = $this->User->SetPasswordResetToken($user['id']);
        if (array_key_exists('error', $response)){
            $this->set('response', json_encode($response));
            return;
        }

        /* Send password reset email to user */
        $this->set('name', $user['first_name']);
        $this->set('id', $user['id']);
        $this->set('password_reset_token', $response['password_reset_token']);
        $this->_sendPasswordResetEmail($user['email']);
        $this->set('response', json_encode(array('success'=>'')));
    }

    /*
    Action for register page.
    */
    public function Register()
    {
        if ($this->Auth->loggedIn()){
            /* User already logged in */
            $this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
        }
    }

    /*
    Action for login page.
    */
    public function Login($signup = false, $propertymanager = false)
    {
        $this->set('locations', $this->University->getSchools());
        $this->set('user_years', $this->User->GetYears());
        if ($this->Auth->loggedIn()){
            /* User already logged in */
            $this->User->UpdateLastLogin($this->Auth->User('id'));
            $flash_message['method'] = "Success";
            $flash_message['message'] = "You are now logged in!";
            $json = json_encode($flash_message);
            $this->Cookie->write('flash-message', $json);
            $user = $this->Auth->User();
            if (array_key_exists('user_type', $user) && !empty($user['user_type']) && $user['user_type'] == 2)
                $this->redirect('/FeaturedListings');

            $this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
        }

        /* 
        After the user is redirected from facebook, these URL parameters will have been set.
        We'll use these to get their access token, which we'll use to query for their basic information.
        */
        if (array_key_exists('code', $_GET)) {
            $redirect_uri = Configure::read('HTTP_TYPE').'://www.cribspot.com/login';
            if (Configure::read('CURRENT_ENVIRONMENT') === 'ENVIRONMENT_LOCAL')
                $redirect_uri = urlencode('http://localhost/login');
            
            /*else if (Configure::read('CURRENT_ENVIRONMENT') === 'ENVIRONMENT_DEVELOPMENT')
                $redirect_uri = urlencode('http://ec2-54-214-177-171.us-west-2.compute.amazonaws.com/login');*/
            $client_id = Configure::read('FB_APP_ID');
            $client_secret = Configure::read('FB_APP_SECRET');
            $code = urlencode($_GET['code']);
            $url = 'https://graph.facebook.com/oauth/access_token?';
            $url .= '&redirect_uri=' . $redirect_uri;
            $url .= '&client_id=' . urlencode($client_id);
            $url .= '&client_secret=' . urlencode($client_secret);
            $url .= '&code=' . $code;
            CakeLog::write('url', $url);
            $fb_user = urldecode(file_get_contents($url));
            CakeLog::write('fb_user', print_r($fb_user, true));
            parse_str($fb_user); /* Sets access token value in $access_token */
            /* 
            We have the access token.
            We now have to verify its validity
            */
            $response = $this->_verifyFBAccessToken($access_token);
            CakeLog::write('at_response', print_r($response, true));
            if ($response === false){
                /* TODO: HANDLE ERROR HERE */
            }  

            $userData = $this->_getUserData($access_token);
            CakeLog::write('userdata', print_r($userData, true));
            $email = null;
            if (property_exists($userData, 'email'))
                $email = $userData->email;

            $user = array(
                'email' => $email,
                'first_name' => $userData->first_name,
                'last_name' => $userData->last_name,
                'facebook_id' => $userData->id
            );

            /* 
            First, check if we have a user with this facebook_id. 
            If so, log them in.
            If not, redirect them to /signup with first and last names filled in, and store fb_id in session
            */

            /* Didn't receive critical info from facebook - something went wrong */
            if ($userData->id === null) {
                $error = null;
                $error['user'] = $user;
                $this->User->LogError(null, 66, $error);
                $flash_message['method'] = "Error";
                $flash_message['message'] = "Looks like we had trouble logging you in from Facebook..." .
                "but don't worry! You can still create an account right here. It'll take less than 30 seconds.";
                $json = json_encode($flash_message);
                $this->Cookie->write('flash-message', $json);
                $this->redirect(array('action' => 'login', 'signup'));
                return;
            }

            /* Check if we have a user with this facebook id */
            if ($userData->id){
                $local_user = $this->User->GetUserFromFacebookId($userData->id);
                if (array_key_exists('User', $local_user) && array_key_exists('email', $local_user['User'])) {
                    /* Check if local user has verified their email yet */
                    $response = $this->User->EmailIsConfirmed($local_user['User']['email']);
                    if (array_key_exists('error', $response)){
                        /* Save the user's email so that we can resend the email confirmation email if they request it */
                        $this->Session->write('user_email_not_verified', $local_user['User']['email']);
                        $flash_message['method'] = "Error";
                        $flash_message['callback'] = "A2Cribs.Login.ResendConfirmationEmail";
                        return;
                    }

                    $user['email'] = $local_user['User']['email'];
                    $this->_facebookLogin($user);
                    return;
                }
            }

            /* 
            No user exists with this facebook_id. Send them to /signup with first & last names filled in, 
            and store fb_id in session
            */
            $this->Session->write('FB.first_name', $userData->first_name); 
            $this->Session->write('FB.last_name', $userData->last_name); 
            $this->Session->write('FB.id', $userData->id);
            $this->redirect(array('action' => 'login', 'signup'));
            return;

            /* Check for strange characters in first and last names of users */
        }

        $this->set('show_signup', $signup);
        $this->set('show_pm', $propertymanager);
    }

    /*
    Action for Reset Password page.
    */
    public function ResetPassword() 
    {

    }

    /*
    User is redirected here from the "reset password" link in their email
    */
    public function ResetPasswordRedirect()
    {
        if (!array_key_exists('id', $this->request->query) || !array_key_exists('reset_token', $this->request->query))
            $this->redirect('/');

        $id = $this->request->query['id'];
        $reset_token = $this->request->query['reset_token'];
        if (!$this->User->IsValidResetToken($id, $reset_token)){
            CakeLog::write("ErrorResetPasswordRedirect", $id . "; " . $reset_token);
            $flash_message['method'] = "Error";
            $flash_message['message'] = "That reset password link does not seem to be legitimate!";
            $json = json_encode($flash_message);
            $this->Cookie->write('flash-message', $json);
            $this->redirect('/users/login?invalid_link=true');
        }

        $this->set('id', $id);
        $this->set('reset_token', $reset_token);
    }

    /*
    Called from /users/ResetPasswordRedirect (user is logged out).
    Verifies the submitted user_id and password reset_token
    If the passwords match, sets the user's password.
    */
    public function AjaxChangePassword()
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return; 

        $this->layout = 'ajax';
        if (!$this->request || !$this->request->data || 
            !array_key_exists('new_password', $this->request->data) ||
            !array_key_exists('confirm_password', $this->request->data)) {
            CakeLog::write("ErrorAjaxChangePassword", "error_code: 30;" . print_r($this->request->data, true));
            $response = array('error' => 'Failed to change password. Contact help@cribspot.com if the error persists. Reference error code 30');
            $this->set('response', json_encode($response));
            return;
        }

        /* If no $_GET parameter exists for reset_token and id, then user must be logged in */
        $user_id = null;
        if (array_key_exists('reset_token', $this->params->data) &&
            array_key_exists('id', $this->params->data)) {
                /* Make sure that the ($id, $reset_token) pair is valid */
                $user_id = $this->params->data['id'];
                $reset_token = $this->params->data['reset_token'];
                if (!$this->User->IsValidResetToken($user_id, $reset_token)){
                    CakeLog::write("ErrorAjaxChangePassword", $id . "; " . $reset_token);
                    $response = array('error' => 'Failed to change password. Contact help@cribspot.com if the error persists. Reference error code 31');
                    $this->set('response', json_encode($response));
                    return;
                }
        }
        else if ($this->Auth->User())
            $user_id = $this->Auth->User('id');
        else{
            CakeLog::write("ErrorAjaxChangePassword", 'Error Code: 46');
            $response = array('error' => 'Failed to change password. Contact help@cribspot.com if the error persists. Reference error code 46');
            $this->set('response', json_encode($response));
            return;
        }

        $new_password = $this->request->data['new_password'];
        $confirm_password = $this->request->data['confirm_password'];

        /* Make sure new_password matches the confirmed password */
        if ($new_password != $confirm_password){
            $response = array('error' => 'Passwords do not match.', 'error_type'=>'PASSWORDS_DONT_MATCH');
            $this->set('response', $response);
            return;
        }

        /* Save new password */
        $response = $this->User->SavePassword($user_id, $new_password);
        $user = $this->User->get($user_id);
        if ($user != null && !array_key_exists('error', $response)) {
            /* Change the reset_token to something random so the old link is no longer valid */
            $this->User->ResetPasswordToken($this->Auth->User('id'));
            $this->Auth->login($user['User']);
        }

        $this->set('response', json_encode($response));
        return;
    }

    /*
    User is redirected here from the link in the email for verifying their email.
    Checks vericode in order to validate email.
    */
    public function VerifyEmailRedirect()
    {
        $user_id = $this->request->query['id'];
        $vericode = $this->request->query['vericode'];

        /* Check if user exists */
        if (!$this->User->IdExists($user_id)){
            CakeLog::write("Users_Verify_Email_Redirect", $this->request->query['id']);
            $flash_message['method'] = "Error";
            $flash_message['message'] = "Email failed to validate user. Please sign up.";
            $json = json_encode($flash_message);
            $this->Cookie->write('flash-message', $json);
            $this->redirect(array('action' => 'login', 'signup'));
        }

        /* Check if vericode is valid */
        if (!($this->User->VericodeIsValid($vericode, $user_id))) {
            CakeLog::write("Users_Verify_Email_Redirect", $this->User->id . ' ' . $vericode . ' ' . $this->User->field('vericode'));
            $flash_message['method'] = "Error";
            $flash_message['message'] = "Validation code is not legit! Please check your email.";
            $json = json_encode($flash_message);
            $this->Cookie->write('flash-message', $json);
            $this->redirect(array('action' => 'login'));
        }

        $this->User->id = $user_id;
        $email = $this->User->field('email');
        /* Attempt to associate this user with a university (by checking for valid edu email) */
        $university_id = $this->User->University->GetIdFromEmail($this->User->field('email'));
        $success = $this->User->VerifyUserEmail($user_id, $university_id);
        if (array_key_exists('error', $success)){
            CakeLog::write("Verify_Email_Failed", $this->Auth->User('id') . ' ' . $university_id);
            $flash_message['method'] = "Error";
            $flash_message['message'] = "Gosh darn it. Failed to verify email.";
            $json = json_encode($flash_message);
            $this->Cookie->write('flash-message', $json);
            $this->redirect(array('action' => 'login'));
        }
        else{
            $flash_message['method'] = "Success";
            $flash_message['message'] = "Email successfully verified!";
            $json = json_encode($flash_message);
            $this->Cookie->write('flash-message', $json);
            $this->redirect(array('action' => 'login'));
        }
    }

    /*
    Attempts to associate a user's email with a given university.
    */
    public function VerifyUniversityEmail()
    {
        preg_match('/@(.*)/', $this->User->field('email'), $matches);

        if (array_key_exists(1, $matches)){
            $userEmailDomainString = $matches[1];
            $universities = $this->User->University->findByDomain($userEmailDomainString);
            if ($universities)
            {
                $this->User->saveField('university_verified',1);
                $this->User->saveField('university_id', $universities['University']['id']);
            }
        }
    }

    /*
    Redirect from "Verify University Email" email.
    */
    public function VerifyUniversityEmailRedirect()
    {

    }

    /*
    TODO: exclude this action in robots.txt
    PM Admin interface to login as any property manager.
    Generates a login link for every PM
    */
    public function PMAdmin()
    { 
      /* Ensure this request is coming from localhost via ssh tunnel */
      $ip_address = $this->request->clientIp();
      if (strcmp($ip_address, '127.0.0.1'))
        throw new NotFoundException();

      /* Get login URLs for all property managers */
      App::Import('model', 'User');
      $User = new User();
      $propertyManagers = $User->find('all', array(
          'conditions' => array(
              'User.user_type' => 1,
              'LoginCode.is_permanent' => 1
          ),
          'contain' => array('LoginCode'),
          'joins' => array(
              array('table' => 'login_codes',
                  'alias' => 'LoginCode',
                  'type' => 'INNER',
                  'conditions' => array(
                      'LoginCode.user_id = User.id'
                  ),
              )   
          )
      ));
      $loginLinks = array();
      foreach ($propertyManagers as $pm){
          if (!array_key_exists('User', $pm) || !array_key_exists('LoginCode', $pm) ||
              !array_key_exists('id', $pm['User']) || !array_key_exists(0, $pm['LoginCode']) || 
              !array_key_exists('code', $pm['LoginCode'][0]))
              continue;

          $nextLink = array(
              'link' => 'https://www.cribspot.com/users/PMLogin?id='.$pm['User']['id'].'&code='.$pm['LoginCode'][0]['code'].'&admin=true',
              'company_name' => $pm['User']['company_name'],
              'city' => $pm['User']['city'],
              'state' => $pm['User']['state']
          );
          array_push($loginLinks, $nextLink);
      }

      $this->set('loginLinks', $loginLinks);
    }

    /*
    Automatically logs in a property manager if the supplied URL credentials are correct
    */
    public function PMLogin()
    {
        if (!array_key_exists('id', $this->request->query) || !array_key_exists('code', $this->request->query))
            $this->redirect('/');

        $id = $this->request->query['id'];
        $code = $this->request->query['code'];
        $response = $this->LoginCode->IsValidLoginCode($id, $code);
        if (array_key_exists('error', $response)){
            $message = "That reset password link does not seem to be legitimate!";
            if (!strcmp($response['error'], 'LOGIN_CODE_EXPIRED'))
                $message = "That link is over 2 weeks old and has expired. You can still login here with your email and password!";
        
            $flash_message['method'] = "Error";
            $flash_message['message'] = $message;
            $json = json_encode($flash_message);
            $this->Cookie->write('flash-message', $json);
            $this->redirect('/users/login?invalid_link=true');
        }
        
        /* 
        credentials were correct - log the user in. 
        If conversation id present, redirect user to that conversation
        */
				$user = $this->User->get($id);

				/* Determine if this user is a Cribspot admin rather than the user themself */
				$is_admin = array_key_exists('admin', $this->request->query);
				if (!$is_admin)
					$this->User->VerifyEmail($id);

        $this->_login($user, $is_admin);
        $this->LoginCode->InvalidateCode($id);
        if (array_key_exists('convid', $this->request->query)){
            $conv_id = $this->request->query['convid'];
            CakeLog::write('convid', $conv_id);
            $this->redirect('/messages/view/'.$conv_id);
        } else {
            $this->redirect('/dashboard');
        }
    }

    /*
    Called from the dashboard to edit basic account information from the 'My Account' tab.
    */
    public function AjaxEditUser()
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $this->layout = 'ajax';

        $editable_fields = array('email', 'first_name', 'last_name', 'company_name', 'street_address',
            'city', 'state', 'phone', 'website');

        $user = array('id' => $this->Auth->User('id'));

        foreach ($editable_fields as $field){
            if (array_key_exists($field, $this->request->data) &&
                !empty($this->request->data[$field])) {
                    if (!strcmp($field, 'email') && $this->User->EmailExists($this->request->data[$field])){
                        /* Email already exists */
                        $response = array('error' => 'An account with this email address already exists');
                        $this->set('response', json_encode($response));
                        return;
                    }

                    $user[$field] = $this->request->data[$field];
            }
        }

        $response = $this->User->edit(array('User' => $user));
        $this->set('response', json_encode($response));
        return;
    }

    public function ResendConfirmationEmail()
    {
        $email = $this->Session->read('user_email_not_verified');
        if ($email === null){
            $response = array('error' => array('message'=> "Oops! We're having trouble re-sending you " .
                "that email to confirm your email address. You can message us by clicking the tab along the bottom of the screen, " . 
                "or by emailing us at help@cribspot.com"));
            $this->set('response', $response);
            return;
        }

        $user = $this->User->GetUserFromEmail($email);
        /* The confirmation email can only be sent 3 times total */
        if ($user['number_email_confirmations_sent'] >= $this->MAX_NUMBER_EMAIL_CONFIRMATIONS_SENT){
            $response = array('error' => array('message'=> "Oops! Looks like we've already sent a few emails to " . 
            "help confirm your email address. Contact us and we'll help you out! You can message us " . 
            "by clicking the tab along the bottom of the screen or by emailing us at help@cribspot.com"));
            $this->set('response', json_encode($response));
            return;
        }

        $this->User->IncrementNumberEmailConfirmationsSent($user['id']);
        $this->set('vericode', $user['vericode']);
        $this->set('id', $user['id']);
        $this->_sendVerificationEmail(array('email' => $email));
        $this->set('response', json_encode(array('success'=>'')));
    }
    
    public function GetBackToMapUrl()
    {
        $preferred_university = $this->User->GetPreferredUniversity($this->Auth->User('id'));
        $university_name = $this->University->GetNameFromId($preferred_university);
        $url = null;
        if (empty($university_name))
            $url = Router::url('/', true);
        else
            $url = Router::url('/map/rental/'.str_replace(" ", "_", $university_name), true);

        return $url;
    }

    /*
    Sends a randomly generated verification code to $phone_number.
    Save this code in the database to check against later.
    Save this user's phone number as unverified.
    */
    public function SendPhoneVerificationCode()
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
                return;

        $this->layout = 'ajax';
        if (!array_key_exists('phone', $this->request->data))
            return;

        $phone = $this->request->data['phone'];
        $random = uniqid();
        $code = substr($random, strlen($random)-5);
        $text = "Here is your Cribspot verification code: " . $code;
        $response = $this->Twilio->sms(Configure::read('TWILIO_PHONE_NUMBER'), $phone, $text);
CakeLog::write('twiliodebug', print_r($response, true));
        /* Store this code to be able to verify later */
        $this->User->UpdatePhoneFields($phone, $code, false, $this->Auth->User('id'));

        $this->set("response", json_encode(array('success' => '')));
    }

    /*
    Verify that $code is correct code for this user
    Set their phone number as verified in DB
    */
    public function ConfirmPhoneVerificationCode()
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $this->layout = 'ajax';
        if (!array_key_exists('code', $this->request->data))
            return;

        $code = $this->request->data['code'];
        $response = $this->User->CheckPhoneCodeValidityAndConfirm($code, $this->Auth->User('id'));
        CakeLog::write('fuckit', print_r($response, true));
        $this->set("response", json_encode($response));
    }   

/* ------------------------------------ private functions -------------------------------------- */

    /*
    Gets the user data necessary to set up the UI following a successful ajax login from the map page.
    Data:
    - name
    - number of messages
    - favorites listing ids
    - user type
    - image url
    */
    private function _getUserDataForAjaxLogin($user)
    {
        if (!array_key_exists('id', $user))
            return null;

        /* name is first_name if student and company_name if PM */
        CakeLog::write('usershit', print_r($user, true));
        $name = "";
        if (array_key_exists('user_type', $user)){
            if ($user['user_type'] == 0 && array_key_exists('first_name', $user))
                $name = $user['first_name'];
            else if ($user['user_type'] == 1 && array_key_exists('company_name', $user))
                $name = $user['company_name'];
        }

        $num_messages = $this->UnreadMessage->getUnreadMessagesCount($user);
        $favorites = $this->Favorite->GetFavoritesListingIds($user['id']);

        /* Get the image url from facebook if facebook_id is set */ 
        $img_url = "/img/head_large.jpg";
        if (!empty($user['facebook_id']))
            $img_url = "https://graph.facebook.com/".$user['facebook_id']."/picture?width=80&height=80";

        $user_type = 0;
        if (array_key_exists('user_type', $user))
            $user_type = $user['user_type'];
        $user_email = "";
        if (array_key_exists('email', $user))
            $user_email = $user["email"];
        $user_phone = "";
        if (array_key_exists('phone_verified', $user) && $user['phone_verified'] && array_key_exists('phone', $user))
            $user_phone = $user['phone'];


        $data = array(
            'name' => preg_replace_callback('/\\\\u([0-9a-f]{4})/i', array($this, 'replace_unicode_escape_sequence'), $name),
            'email' => $user_email,
            'num_messages' => $num_messages,
            'favorites' => $favorites,
            'user_type' => $user_type,
            'img_url' => $img_url,
            'phone' => $user_phone
        );

        return $data;
    }   

    /*
    Verifies that the access_token retrieved from FB belongs to the person who is logging in,
    and that our app generated the token
    */
    private function _verifyFBAccessToken($access_token)
    {
        $client_id = Configure::read('FB_APP_ID');
        $client_secret = Configure::read('FB_APP_SECRET');
        $url = 'https://graph.facebook.com/debug_token?';
        $url .= 'input_token=' . $access_token;
        $url .= '&access_token=' . $client_id . '|' . $client_secret;
        $response = json_decode(file_get_contents($url));
        if ($response->data->app_id !== Configure::read('APP_ID')){
            return false;
        }

        return $response;
    }

    private function _getUserData($access_token)
    {
        $client_id = Configure::read('FB_APP_ID');
        $client_secret = Configure::read('FB_APP_SECRET');
        $url = 'https://graph.facebook.com/me?';
        $url .= '&access_token=' . $access_token;
        $response = json_decode(file_get_contents($url));
        return $response;
    }

    private function _savePreferredUniversity($user_id)
    {
        $preferred_university = $this->Session->read('preferredUniversity');
        if ($preferred_university)
            $this->User->SavePreferredUniversity($this->Auth->User('id'), $preferred_university);
    }

    /*
    Receives user data as given by facebook.
    Attempts to log user in with this data.
    */
    private function _facebookLogin($fb_user)
    {
        if ($fb_user){
            $local_user = $this->User->GetUserFromFacebookId($fb_user['facebook_id']);
            /* User exists, so log them in. */
            if ($local_user){
                $this->User->UpdateLastLogin($local_user['User']['id']);
                $this->Auth->login($local_user['User']);
                $this->redirect('/dashboard');
            } 

            /* User doesn't exist, so create a new user. */
            else {
                $new_user = array('User' => $fb_user); 
                $new_user['User']['verified'] = 1;
                $new_user['User']['user_type'] = User::USER_TYPE_SUBLETTER;
                $new_user['User']['password'] = uniqid();
                
                $response = $this->User->SaveFacebookUser($new_user);

                if (array_key_exists('error', $response)){
                    return $response;
                }

                /* After they have registered, log them in and redirect to the dashboard */
                $this->Auth->login($response['user']['User']);

                /* This is the first time the user has logged in, so register them with mixpanel */
                //$this->Js->buffer("mixpanel.alias(" . $this->Auth->User('id') . ");");

                $this->redirect('/dashboard');
            }
        }

        else{
            
        }
    }

    private function _sendShareEmail($user, $friend_email, $subject)
    {
        $from = $user['first_name'] . ' ' . $user['last_name'] . ' <info@cribspot.com>';
        $to = $friend_email;
        $template = 'share_listing';
        $sendAs = 'both';
        $this->SendEmail($from, $to, $subject, $template, $sendAs);
    }

    /*
    Generates a new vericode and sends an email to the currently logged-in user.
    Email allows user to verify email address.
    */
    private function _sendVerificationEmail($user)
    {
        $from = 'The Cribspot Team<info@cribspot.com>';
        $to = $user['email'];
        $subject = 'Please verify your Cribspot account';
        $template = 'registration';
        $sendAs = 'both';
        $this->SendEmail($from, $to, $subject, $template, $sendAs);
    }

    private function _sendPasswordResetEmail($email)
    {
        $from = 'The Cribspot Team<info@cribspot.com>';
        $to = $email;
        $subject = 'Please reset your password';
        $template = 'forgotpassword';
        $sendAs = 'both';
        $this->SendEmail($from, $to, $subject, $template, $sendAs);
    }

    /*
    Returns true if a user account exists with email=$email, false otherwise.
    */
    private function _emailAlreadyRegistered($email)
    {
        return $this->User->EmailExists($email);
    }

    /*
    Send welcome email to student after successful signup
    */
    private function _sendWelcomeEmail($user)
    {
        CakeLog::write('userobj', print_r($user, true));
        if (!array_key_exists('user_type', $user) || intval($user['user_type']) !== User::USER_TYPE_SUBLETTER)
            return;
        
        $this->set('first_name', $user['first_name']);
        $from = 'The Cribspot Team<info@cribspot.com>';
        $to = $user['email'];
        $subject = 'Welcome to Cribspot!';
        $template = 'welcome_student';
        $sendAs = 'both';
        CakeLog::write('sending', $to);
        $this->SendEmail($from, $to, $subject, $template, $sendAs);
    }

}
?>
