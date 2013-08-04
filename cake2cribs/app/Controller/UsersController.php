<?php
class UsersController extends AppController {
	public $helpers = array('Html', 'Js', 'Facebook.Facebook');
	public $uses = array('User');
	public $components= array('Session','Auth', 'Email', 'RequestHandler', 'Facebook.Connect');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('Register');
        $this->Auth->allow('AjaxRegister');
        $this->Auth->allow('VerifyEmailRedirect');
        $this->Auth->allow('ResetPassword');
        $this->Auth->allow('AjaxResetPassword');
        $this->Auth->allow('ResetPasswordRedirect');
        $this->Auth->allow('AjaxChangePassword');
        $this->Auth->allow('AjaxLogin');
        $this->Auth->allow('Login2');
        $this->Auth->allow('FacebookLogin');
    }

    /*
    User submits registration data here.
    Returns success, or array of columns that failed validation.
    */
    public function AjaxRegister()
    {
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

        /* Create a new user object and save it */
        $this->User->create();
        $user['verified'] = 0;
        $user['group_id'] = 1;
        $user['vericode'] = uniqid();
        if (!$this->User->save($user)){
            $response = array('error' => 'Failed to register. Contact help@cribspot.com if the error persists. Reference error code 25', 'validation' => $this->User->validationErrors);
            $this->set('response', json_encode($response));
            return;
        }

        /* User record saved. Now send email to validate email address */
        $this->set('name', $user['first_name']);
        $this->set('vericode', $user['vericode']);
        $this->set('id', $this->User->id);
        $this->_sendVerificationEmail($user);
        $this->set('response', json_encode(array('success'=>'')));
        //$this->redirect('/landing?registration_success=true');
    }

    /*
    Logs user in via ajax.
    Returns success, or array of columns that failed validation.
    */
    public function AjaxLogin()
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

        if ($this->Auth->login()){
            $this->set('response', json_encode(array('sucess'=>'')));
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
        $this->facebook->destroySession();
        $this->Auth->logout();
        $this->redirect('/');
    }

    /*
    User submits email address of account for which to reset password.
    Returns success or error message.
    */
    public function AjaxResetPassword()
    {
        $this->layout = 'ajax';
        if ($this->request == null || 
            $this->request->data == null || 
            !array_key_exists('email', $this->request->data)){
            CakeLog::write("ErrorAjaxResetPassword", "Error code: 27;" . print_r($this->request, true));
            $this->set('response', json_encode(array('error' => 
                'Failed to reset password. Contact help@cribspot.com if the error persists. Reference error code 27')));
            return;
        }

        /* Get user_id from given email address and check its validity */
        $email = $this->request->data['email'];
        $user = $this->User->GetUserFromEmail($email);
        if (!$user){
            $this->set('response', json_encode(array('error' => 
                'Failed to reset password. Contact help@cribspot.com if the error persists. Reference error code 28')));
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
    public function Login()
    {
        if ($this->Auth->loggedIn()){
            /* User already logged in */
            $this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
        }
    }

    /*
    Used to handle facebook login.
    Logs user in if they already exist.
    If they do not exist, create their record, and then log them in.
    */
    public function Login2($authorize=null)
    {
        CakeLog::write('me', 'called it');
        $user = null;
        if ($this->facebook->getUser())
        {
            try
            {
                $user = $this->facebook->api('/me');
                CakeLog::write("me", print_r($user, true));
                $this->FacebookLogin(null);
            }
            catch(FacebookApiException $e){
                $this->facebook->destroySession();
            }
        }
    }

    public function FacebookLogin($user_id=null)
    {
        $user = null;
        $cookie = preg_replace("/^\"|\"$/i", "", $_COOKIE['fbm_' . Configure::read('FB_APP_ID')]);
        parse_str($cookie, $data);
        $this->facebook->setAccessToken($data['access_token']);
        $user = $this->facebook->api('/'.$this->facebook->getUser());

        /* TODO: If user doesn't exist, create a new record for them */

        /* TODO: Log user in. */

        /* TODO: only store fb_id in fb_user session variable? */
        $this->Session->write('fb_user', $user);

        $response = array('user_info' => $user);
        $this->set('response', json_encode($user));
        return;

        /* ------------------------------------------ */

        if ($this->Session->read('fb_user'))
        {
            try
            {
                $user = $this->facebook->api('/me');

            }
            catch(FacebookApiException $e){
                $this->facebook->destroySession();
            }
        }

        $userInfo = $this->facebook->api('/me');

        if (!$userInfo) {
            // nope, login failed or something went wrong, aborting
            $this->redirect(array('action' => 'login'));
        }

        $user = array(
            'User' => array(
                'firstname'       => $userInfo['first_name'],
                'lastname'        => $userInfo['last_name'],
                'username'        => trim(parse_url($userInfo['link'], PHP_URL_PATH), '/'),
                'email'           => $userInfo['email'],
                'email_validated' => $userInfo['verified']
            ),
            'Oauth' => array(
                'provider'        => 'facebook',
                'provider_uid'    => $userInfo['id']
            )
        );

        $this->Session->write('fb_user', $userInfo['id']);
        CakeLog::write('me', print_r($user, true));
    }

    /*
    User has been logged out of facebook.
    Now log them out of our system
    */
    public function FacebookLogout()
    {
        $this->Session->destroy();
        $this->facebook->destroySession();
        $this->Logout();
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
            $this->redirect('/users/login?invalid_link=true');
        }

        $this->set('id', $id);
        $this->set('reset_token', $reset_token);
    }

    /*
    Called from /users/ ResetPasswordRedirect.
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
            !array_key_exists('confirm_password', $this->request->data) ||
            !array_key_exists('reset_token', $this->request->data) ||
            !array_key_exists('id', $this->request->data)){
            CakeLog::write("ErrorAjaxChangePassword", "error_code: 30;" . print_r($this->request->data, true));
            $response = array('error' => 'Failed to change password. Contact help@cribspot.com if the error persists. Reference error code 30');
            $this->set('response', json_encode($response));
            return;
        }

        $new_password = $this->request->data['new_password'];
        $confirm_password = $this->request->data['confirm_password'];
        $reset_token = $this->request->data['reset_token'];
        $user_id = $this->request->data['id'];
        /* Make sure that the ($id, $reset_token) pair is valid */
        if (!$this->User->IsValidResetToken($user_id, $reset_token)){
            CakeLog::write("ErrorAjaxChangePassword", $id . "; " . $reset_token);
            $response = array('error' => 'Failed to change password. Contact help@cribspot.com if the error persists. Reference error code 31');
            $this->set('response', json_encode($response));
            return;
        }

        /* Make sure new_password matches the confirmed password */
        if ($new_password != $confirm_password){
            $response = array('error' => 'Passwords do not match.', 'error_type'=>'PASSWORDS_DONT_MATCH');
            $this->set('response', $response);
            return;
        }

        /* Save new password */
        $response = $this->User->SavePassword($user_id, $new_password);
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
            $this->redirect('/users/login?invalid_link=true');
        }

        /* Check if vericode is valid */
        if (!($this->User->VericodeIsValid($vericode, $user_id))) {
            CakeLog::write("Users_Verify_Email_Redirect", $this->User->id . ' ' . $vericode . ' ' . $this->User->field('vericode'));
            $this->redirect('/users/login?invalid_link=true');
        }

        $this->User->id = $user_id;
        $email = $this->User->field('email');
        CakeLog::write("EMAIL", $email);
        /* Attempt to associate this user with a university (by checking for valid edu email) */
        $university_id = $this->User->University->GetIdFromEmail($this->User->field('email'));
        $success = $this->User->VerifyUserEmail($user_id, $university_id);
        if (array_key_exists('error', $success)){
            CakeLog::write("Verify_Email_Failed", $this->Auth->User('id') . ' ' . $university_id);
            $this->redirect('/users/login?email_verify_failed=true');
        }
        else{
            $this->redirect('/dashboard?email_verified=true');
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
    Generates a new vericode and sends an email to the currently logged-in user.
    Email allows user to verify email address.
    */
    private function _sendVerificationEmail($user)
    {
        $from = 'The Cribspot Team<team@cribspot.com>';
        $to = $user['email'];
        $subject = 'Please verify your Cribspot account';
        $template = 'registration';
        $sendAs = 'both';
        $this->SendEmail($from, $to, $subject, $template, $sendAs);
    }

    private function _sendPasswordResetEmail($email)
    {
        $from = 'The Cribspot Team<team@cribspot.com>';
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