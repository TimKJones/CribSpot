<?php
class UsersController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array('User');
	public $components= array('Session','Auth' => array(
        'authenticate' => array(
            'Form' => array(
                'fields' => array('username' => 'email')
                )
            )
        )
        ,'Email', 'RequestHandler'
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('Register');
        $this->Auth->allow('AjaxRegister');
        $this->Auth->allow('VerifyEmailRedirect');
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

    }

    /*
    User submits reset password data here.
    Returns success or error message.
    */
    public function AjaxResetPassword()
    {

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

    /*
    Returns true if a user account exists with email=$email, false otherwise.
    */
    private function _emailAlreadyRegistered($email)
    {
        return $this->User->EmailExists($email);
    }
}
?>