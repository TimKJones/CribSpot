<?php
class UsersController extends AppController {
    public $helpers = array('Html', 'Js');
    public $uses = array('User', 'University');
    public $components= array('Email', 'RequestHandler', 'Cookie');
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
    }

    public function add()
    {
        $this->redirect(array('action' => 'login', "signup"));
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
        $response = $this->User->RegisterUser($user);
        if (array_key_exists('error', $response)) {
            $this->set('response', json_encode($response));
            return;
        }

        /* User record saved. Now send email to validate email address */
        /* Create a new user object and save it */
        if ($user['user_type'] == User::USER_TYPE_SUBLETTER)
            $this->set('name', $user['first_name']);
        else if ($user['user_type'] == User::USER_TYPE_PROPERTY_MANAGER)
            $this->set('name', $user['company_name']);
        
        $this->set('vericode', $user['vericode']);
        $this->set('id', $this->User->id);
        $this->_sendVerificationEmail($user);
        $this->_savePreferredUniversity($this->User->id);
        $this->set('response', json_encode(array('success'=>'')));
        //$this->redirect('/landing?registration_success=true');
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
        if(!$this->request->isPost()){
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
        }

        /* Return an error message if the user has not yet confirmed their email address. */
        $response = $this->User->EmailIsConfirmed($this->request->data['User']['email']);
        if (array_key_exists('error', $response)){
            /* Save the user's email so that we can resend the email confirmation email if they request it */
            $this->Session->write('user_email_not_verified', $this->request->data['User']['email']);
            $this->set('response', json_encode($response));
            return;
        }

        if ($this->Auth->login()) {
            $user = $this->Auth->User();
            $first_log_in_ever = ($user['last_login'] === null);
            $this->User->UpdateLastLogin($this->Auth->User('id'));
            $this->_savePreferredUniversity($this->Auth->User('id'));
            $this->set('response', json_encode(array('success'=>array(
                'first_login' => $first_log_in_ever,
                'user_id' => $this->Auth->User('id')
            ))));
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
    public function Login($signup=false)
    {
        CakeLog::write('hostname', gethostname());
        if ($this->Auth->loggedIn()){
            /* User already logged in */
            $this->User->UpdateLastLogin($this->Auth->User('id'));
            $flash_message['method'] = "Success";
            $flash_message['message'] = "You are now logged in!";
            $json = json_encode($flash_message);
            $this->Cookie->write('flash-message', $json);
            $this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
        }

        /* 
        After the user is redirected from facebook, these URL parameters will have been set.
        We'll use these to get their access token, which we'll use to query for their basic information.
        */
        if (array_key_exists('code', $_GET)){
            $redirect_uri = 'http://www.cribspot.com/login';
            if (Configure::read('CURRENT_ENVIRONMENT') === 'ENVIRONMENT_LOCAL')
                $redirect_uri = urlencode('http://localhost/login');
            else if (Configure::read('CURRENT_ENVIRONMENT') === 'ENVIRONMENT_DEVELOPMENT')
                $redirect_uri = urlencode('http://ec2-54-214-177-171.us-west-2.compute.amazonaws.com/login');
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
            parse_str($fb_user); /* Sets access token value in $access_token */
            /* 
            We have the access token.
            We now have to verify its validity
            */
            $response = $this->_verifyFBAccessToken($access_token);
            if ($response === false){
                /* TODO: HANDLE ERROR HERE */
            }  

            $userData = $this->_getUserData($access_token);
            $user = array(
                'email' => $userData->email,
                'first_name' => $userData->first_name,
                'last_name' => $userData->last_name,
                'facebook_id' => $userData->id
            );
            $this->_facebookLogin($user);
        }

        $this->set('show_signup', $signup);
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
        if ($user != null)
            $this->Auth->login($user['User']);
            
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
    Called from the dashboard to edit basic account information from the 'My Account' tab.
    */
    public function AjaxEditUser(){
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $this->layout = 'ajax';

        $editable_fields = array('first_name', 'last_name', 'company_name', 'street_address',
            'city', 'state', 'phone', 'website');

        $user = array('id' => $this->Auth->User('id'));

        foreach ($editable_fields as $field){
            if (array_key_exists($field, $this->request->data) &&
                !empty($this->request->data[$field])) {
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
                $new_user['User'] = $fb_user;
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
}
?>