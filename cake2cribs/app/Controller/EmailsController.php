<?php
/*
This is a temporary class containing functionality used to initialize the users that pre-signed up on google docs.
*/
class EmailsController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array('User');
	public $components= array('Email', 'RequestHandler');

    public function beforeFilter(){
    parent::beforeFilter();
    $this->Auth->allow('InitializeCorrectionEmail');
    }
    


    public function InitializeNewUsers()
    {
        // get all user_ids
        $user_ids_list = $this->User->find('all', array(
            'fields' => array("id")
        ));

        for ($i = 0; $i < count($user_ids_list); $i++)
        {
            $this->resetpassword($user_ids_list[$i]['User']['id']); // all users now have a password_reset_token
            $this->sendResetPasswordEmail($user_ids_list[$i]['User']['id']);
        }
    }

    public function InitializeCorrectionEmail()
    {
        // get all user_idsss
        $user_ids_list = $this->User->find('all', array(
            'fields' => array("id")
        ));

        for ($i = 0; $i < count($user_ids_list); $i++)
        {
            $this->sendCorrectionEmail($user_ids_list[$i]['User']['id']);
        }
    }

    public function resetpassword($user_id) 
    {

        $this->User->id = $user_id;
        if (!$this->User->exists()) {
            CakeLog::write("InitResetPassword", "failed for user: " . $this->User->id);
            return;
        }

        //set password reset token to a unique and random string
        $reset_token = uniqid(rand(),true);
        //save the password reset token to the request data
        $this->User->saveField('password_reset_token', $reset_token);
        //save date of request
        $this->User->saveField('password_reset_date',  date("Y-m-d H:i:s"));
    }

    public function sendResetPasswordEmail($user_id)
    {
        //send verification email
        $this->Email->smtpOptions = array(
          'port'=>'587',
          'timeout'=>'30',
          'host' => 'smtp.sendgrid.net',
          'username'=>'cribsadmin',
          'password'=>'lancPA*travMInj',
          'client' => 'a2cribs.com'
        );

        $user = $this->User->get($user_id);
        $this->Email->delivery = 'smtp';
        $this->Email->from = 'The Cribspot Team<team@cribspot.com>';
        $this->Email->to = $user['User']['email'];
        $this->set('name', $user['User']['first_name']);
        $this->Email->subject = 'Thanks for posting on Cribspot ' . $user['User']['first_name'] . '! Register to view your listing';
        //$this->Email->template = 'registration';
        $message = "Hi " . $user['User']['first_name'] . ",<br/><br/>You recently filled out a Google doc containing your sublet information for priority access on Cribspot. To view and edit your sublet, you must finish registering.  Click <a href='www.cribspot.com/users/ResetPasswordRedirect?id=" . $user["User"]['id'] . "&reset_token=" . $user["User"]['password_reset_token'] . "'>here</a> to set your password.";
        $this->Email->sendAs = 'html';
        $this->set('id',$this->User->id);
        $this->Email->send($message);
    }

    public function sendCorrectionEmail($user_id)
    {
        //send verification email
        $this->Email->smtpOptions = array(
          'port'=>'587',
          'timeout'=>'30',
          'host' => 'smtp.sendgrid.net',
          'username'=>'cribsadmin',
          'password'=>'lancPA*travMInj',
          'client' => 'a2cribs.com'
        );

        $user = $this->User->get($user_id);
        $this->Email->delivery = 'smtp';
        $this->Email->from = 'The Cribspot Team<team@cribspot.com>';
        $this->Email->to = $user['User']['email'];
        $this->set('name', $user['User']['first_name']);
        $this->Email->subject = 'Information regarding setting up your account';
        //$this->Email->template = 'registration';
        $message = "Hi " . $user['User']['first_name'] . ",<br/><br/>It appears that some users recently received our welcome email twice. If you experience issues resetting your password using the link we provided, you are probably using the expired link. Simply open the second email and use the link contained inside. We apologize for any confusion this may have caused.";
        $this->Email->sendAs = 'html';
        $this->set('id',$this->User->id);
        $this->Email->send($message);
    }
}
?>