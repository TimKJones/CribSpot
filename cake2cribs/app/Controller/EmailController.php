<?php
/*
This is a temporary class containing functionality used to initialize the users that pre-signed up on google docs.
*/
class EmailController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array();
	public $components= array('Email', 'RequestHandler');

    public function InitializeNewUsers()
    {
        $this->resetpassword(); // all users now have a password_reset_token
    }

    public function resetpassword() 
    {
        // get all user_ids
        $user_ids_list = $this->User->find('all', array(
            'fields' => array("id")
        ));

        for ($i = 0; $i < count($user_ids_list); $i++)
        {
            $next_user = $user_ids_list[$i]['User']['id'];
            $this->User->id = $next_user;
            if (!$this->User->exists()) {
                CakeLog::write("InitResetPassword", "failed for user: " . $this->User->id);
                return;
            }


            $this->User->id = $user['User']['id'];
            //set password reset token to a unique and random string
            $reset_token = uniqid(rand(),true);
            //save the password reset token to the request data
            $this->User->saveField('password_reset_token', $reset_token);
            //save date of request
            $this->User->saveField('password_reset_date',  date("Y-m-d H:i:s"));
        }
    }

    public function sendResetPasswordEmail()
    {
        
    }
}
?>