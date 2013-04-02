<?php
class UsersController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array();
	public $components= array('Session','Auth' => array(
        'authenticate' => array(
            'Form' => array(
                'fields' => array('username' => 'email')
                )
            )
        )
        ,'Email', 'RequestHandler');


	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add');
		$this->Auth->allow('verify');
        $this->Auth->allow('resetpassword');
        $this->Auth->deny('index');
        $this->Auth->allow('getTwitterFollowers');
        $this->Auth->allow('ajaxLogin');
        $this->Auth->allow('ajaxRegister');
	}

	public function login() {
        if ($this->Auth->loggedIn())
        {
            $this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
        }
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
      
                
                if ($this->Auth->user('verified') == 0) {
                    $this->Session->setFlash(__('Verify your account to gain credibility. Please check your email'));
                    $this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
                }
                else {
                    $this->Session->setFlash(__('You were successfully logged in.'));
                    $this->redirect(array('controller' => 'dashboard', 'action' => 'index'));    
                }
                
			} else {
				$this->Session->setFlash(__('Invalid login, try again'));
			}
		}

	}

    public function ajaxLogin() {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
        $this->layout = 'ajax';
        if($this->Auth->loggedIn())
        {
            $this->Session->setFlash(__('You are already logged in.'));
            $json = json_encode(array(
                'loginStatus' => 0,
                'error'=>'You are verified'));
                $this->set('response', $json);
           // $this->redirect('/dashboard');
        }
        if(!$this->request->isPost()){
            echo "This url only accepts post requests";
            die();
        }
        if ($this->Auth->identify($this->request, $this->response))
        {
            if($this->Auth->login()) {
                if($this->Auth->user('verified')==0) {
                    $json = json_encode(array(
                    'loginStatus' => 1,
                    'error'=>'You are verified'));
                    $this->set('response', $json);
                    $this->Session->setFlash(__('Verify your account to gain credibility. Please check your email.'));
                 //   $this->redirect('/dashboard');
                }
                else {
                    $this->Session->setFlash(__('You were successfully logged in.'));
                    $json = json_encode(array(
                    'loginStatus' => 1,
                    'error'=>'You are logged in.'));
                    $this->set('response', $json);
                   // $this->redirect('/dashboard');
                }
            }
        
        } else {
            $json = json_encode(array(
                'loginStatus' => 0,
                'error'=>'Invalid login details.'));
            
            $this->set('response', $json);

        }
    }

	/*public function index() {
		$this->User->recursive = 0;
        $this->Auth->deny('index');
		$this->set('users',$this->paginate());
        $this->set('id', $this->Auth->user('id'));
        $this->User->id = $this->Auth->user('id');
        $this->set('firstName', $this->Auth->user('first_name'));
        //test email
        
	}*/

	/*public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}*/

	public function add() {

        if ($this->Auth->loggedIn())
            $this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		if ($this->request->is('post')) {
			$this->User->create();
			$this->request->data['User']['verified'] = 0;
			$this->request->data['User']['group_id'] = 1;
            $this->request->data['User']['vericode'] = uniqid();
			if ($this->User->save($this->request->data)) {
                //generate opcode

                //send verification email
                $this->Email->smtpOptions = array(
                  'port'=>'587',
                  'timeout'=>'30',
                  'host' => 'smtp.sendgrid.net',
                  'username'=>'cribsadmin',
                  'password'=>'lancPA*travMInj',
                  'client' => 'a2cribs.com'
                );
                $this->Email->delivery = 'smtp';
                $this->Email->from = 'The Cribspot Team<team@cribspot.com>';
                $this->Email->to = $this->request->data['User']['email'];
                $this->set('name', $this->request->data['User']['first_name']);
                $this->Email->subject = 'Please verify your Cribspot account';
                $this->Email->template = 'registration';
                $this->Email->sendAs = 'html';
                $this->set('vericode', $this->request->data['User']['vericode']);
                $this->set('id',$this->User->id);
                $this->Email->send();
				$this->Session->setFlash(__('The user has been registered. Please check your email for a verification link.'));
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			} else {
				$this->Session->setFlash(__('An error occurred during registration. Please try again.'));
			}
		}
	}

    public function ajaxRegister() {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
         $this->layout = 'ajax';
        if($this->Auth->loggedIn())
        {
            $json = json_encode(array(
                'registerStatus' => 0,
                'error'=>'You are already registered.'));
                $this->set('response', $json);
                return;
           // $this->redirect('/dashboard');
        }
        if(!$this->request->isPost()){
            echo "This url only accepts post requests";
            die();
        }
        $this->request->data['User']['verified'] = 0;
        $this->request->data['User']['group_id'] = 1;
        $this->request->data['User']['vericode'] = uniqid();
        if ($this->User->save($this->request->data)) {
            $this->Email->smtpOptions = array(
                  'port'=>'587',
                  'timeout'=>'30',
                  'host' => 'smtp.sendgrid.net',
                  'username'=>'cribsadmin',
                  'password'=>'lancPA*travMInj',
                  'client' => 'a2cribs.com'
                );
            $this->Email->delivery = 'smtp';
            $this->Email->from = 'The Cribspot Team<team@cribspot.com>';
            $this->Email->to = $this->request->data['User']['email'];
            $this->set('name', $this->request->data['User']['first_name']);
            $this->Email->subject = 'Please verify your Cribspot account';
            $this->Email->template = 'registration';
            $this->Email->sendAs = 'both';
            $this->set('vericode', $this->request->data['User']['vericode']);
            $this->set('id',$this->User->id);
            $this->Email->send();
            $this->Auth->login();
            $this->Session->write('Auth', $this->User->read(null, $this->Auth->User('id')));
            $this->Session->setFlash(__('The user has been registered. Please check your email for a verification link.'));
            $json = json_encode(array(
                    'registerStatus' =>1,
                    'error' => 'Registration successful.'));
            $this->set('response', $json);
        }
        else
        {
            $this->set('response', array());
            $this->User->set($this->request->data);
            //check if passes email validation
            $json = array('registerStatus' => 0,
                    'error' => 'Please check the fields below.');
            $error = $this->validateErrors($this->User);
            $json = json_encode($error);
            $this->set('response', $json);
        }
    }

    /*public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
    */

    public function resetpassword() {
        $response = null;
        if ($this->request->data['User']['email']!= '') // if id is not found in post, indicates user is using password reset form
        {
            //finding user by email
            //$this->User->read(null, $this->request->data['User']['email']);
            $user = $this->User->find('first', array( 
                'conditions' => array(
                    'User.email' => $this->request->data['User']['email'])
                ));
            $this->User->id = $user['User']['id'];


            if (!$this->User->exists()) {
                //throw new NotFoundException(__('That user does not exist.'.$this->request->data['User']['email']."."));
                $this->Session->setFlash(__('Please check your email for instructions to reset your password.'));
                $this->redirect('/users/resetpassword');
            }
            //set password reset token to a unique and random string
            $this->request->data['User']['password_reset_token'] = uniqid(rand(),true);
            //save the password reset token to the request data
            $this->User->saveField('password_reset_token', $this->request->data['User']['password_reset_token']);
            //save date of request
            $this->User->saveField('password_reset_date',  date("Y-m-d H:i:s"));
            
            //email stuff
            $this->Email->smtpOptions = array(
              'port'=>'587',
              'timeout'=>'30',
              'host' => 'smtp.sendgrid.net',
              'username'=>'cribsadmin',
              'password'=>'lancPA*travMInj',
              'client' => 'a2cribs.com'
            );
            $this->Email->delivery = 'smtp';
            $this->Email->from = 'The Cribspot Team<team@cribspot.com>';
            $this->Email->to = $this->User->field('email');
            $this->set('name', $this->User->first_name);
            $this->Email->subject = 'Please reset your password';
            $this->Email->template = 'forgotpassword';
            $this->Email->sendAs = 'both';
            
            $this->set('password_reset_token', $this->request->data['User']['password_reset_token']);
            $this->set('id',$this->User->id);
            $this->Email->send();
            //end email portion
            //$this->set('finalLink', '/users/resetpassword?id='.$this->User->id. '&password_reset_token='.$this->request->data['User']['password_reset_token']);
            $this->Session->setFlash(__('Please check your email for instructions to reset your password.'));
            //$this->redirect(array('action' => 'index'));
        }
        
        if ($this->request->query['id']!='')
        {
            $this->User->id = $this->request->query['id'];
            $password_reset_token = $this->request->query['password_reset_token'];
            $resetdate = $this->User->password_reset_date;
            if (!$this->User->exists()) {
                throw new NotFoundException(__('There was an error verifying your account.'));
                //$this->redirect('login');
                $this->redirect('/users');
            }
            else if( $password_reset_token == $this->User->field('password_reset_token'))
            {
                //$date2 = new DateTime("now");
                //$date1 = $this->User->password_reset_date;
                //$date2->sub(new DateInterval('P1D'));
                if ($this->Auth->login($this->request->query['id'])) {
                    $this->Session->write('Auth', $this->User->read(null, $this->Auth->User('id')));

                /*
                THIS IS WHAT *NOT* TO DO 
                Auth has methods to retrieve user

                //write userid to session for other controllers to use
                //writes groupid to session data, implement checks to 
                //  prevent abuse

                $this->Session->write('User.id', $this->User->id);
                $this->Session->write('User.group_id', $this->User->group_id);
                */
                //redirects to user page
                $this->Session->setFlash(__('Please change your password.'));
                $this->redirect('/users/account');
            } else {
                $this->Session->setFlash(__('Invalid login, try again'));
            }

                
                //$this->Auth->autoRedirect = false;
                //$this->request->data['User']['username'] = $this->User->username;
                //$this->request->data['User']['password'] = $this->User->password;
                //$this->Auth->login();
                //$this->redirect('account');
                //$this->redirect(array('action' => 'index'));

            }
            else {
                $this->Session->setFlash('There was a problem verifying the account.');
            }
        }
    }

    public function verify() {
    	//this functionality is completed

    	$this->User->id = $this->request->query['id'];
    	$vericode = $this->request->query['vericode'];
        if (!$this->User->exists()) {
        	throw new NotFoundException(__('There was an error verifying your account.'));
        	$this->redirect('login');
        }
        
        if ($this->User->exists() && ($vericode == $this->User->field('vericode')))
    	{
        // Update the active flag in the database
        $this->User->saveField('verified', 1);
        //check if their registration email is also a university associated email
        preg_match('/@(.*)/', $this->User->field('email'),$matches);

        $userEmailDomainString = $matches[1];
        $universities = $this->User->University->findByDomain($userEmailDomainString);
        if ($universities)
        {
            $this->User->saveField('university_verified',1);
            $this->User->saveField('university_id', $universities['University']['id']);
        }
        else
        {
            $this->User->saveField('university_verified',0);
        }

        // Let the user know they can now log in!
        $this->Session->setFlash('Your account has been activated, please log in.');
        $this->redirect('login');
    	}
    	else if ($this->User->exists() && $this->User->field('verified') == 1)
    	{
    		$this->Session->setFlash('Your user account is already confirmed.');
    		$this->redirect('login');
    	}
    	else {
    		$this->Session->setFlash('There was an error verifying your account.');
        	$this->redirect('login');
    	}
    }

    public function verifyUniversity() {
        $this->User->id = $this->Auth->user('id');
        if ($this->User->field('university_verified') == 1)
        {
            $university = $this->User->University->findById($this->User->field('university_id'));
            $this->Session->setFlash(__('You are already verified with '. $university['University']['name']));
            $this->redirect('/users');
        }
         if ($this->request->data['User']['email']!= '')
         {
            //finding user by email
                //$this->User->read(null, $this->request->data['User']['email']);
     
            //set password reset token to a unique and random string
            $this->request->data['User']['vericode'] = uniqid(rand(),true);

            //save the password reset token to the request data
            $this->User->saveField('vericode', $this->request->data['User']['vericode']);
            $this->User->id = $this->Auth->user('id');
            
            
                //send verification email
                $this->Email->smtpOptions = array(
                  'port'=>'587',
                  'timeout'=>'30',
                  'host' => 'smtp.sendgrid.net',
                  'username'=>'cribsadmin',
                  'password'=>'lancPA*travMInj',
                  'client' => 'a2cribs.com'
                );
                $this->Email->delivery = 'smtp';
                $this->Email->from = 'The Cribspot Team<team@cribspot.com>';
                $this->Email->to = $this->Auth->user('email');
                $this->set('name', $this->Auth->user('first_name'));
                $this->Email->subject = 'Please verify your Cribspot account\'s university association!';
                $this->Email->template = 'university_verification';
                $this->Email->sendAs = 'both';
                $this->set('vericode', $this->request->data['User']['vericode']);
                $this->set('email', $this->request->data['User']['email']);
                $this->set('id',$this->Auth->user('id'));
                $this->Email->send();
                $this->Session->setFlash(__('Please check your email for a verification link to verify your university.'));
                $this->redirect('/users');
         }
        
        if ($this->request->query['id']!='')
        {
            $email = $this->request->query['email'];
            $this->User->id = $this->request->query['id'];
            if ($this->User->field('id') != $this->request->query['id']) {
                throw new NotFoundException(__('There was an error verifying your account.'));
                //$this->redirect('login');
                $this->redirect('/users');
            }
             else if ($this->Auth->user('university_verified') == 1)
            {
                $this->Session->setFlash('You cannot associate yourself with more than one university. Please contact support.');
                $this->redirect('/users');
            }
            else if( $this->request->query['vericode'] == $this->User->field('vericode'))
            {
                preg_match('/@(.*)/', $this->request->query['email'],$matches);

                $userEmailDomainString = $matches[1];
                $universities = $this->User->University->findByDomain($userEmailDomainString);
                if ($universities)
                {
                    $this->User->saveField('university_verified',1);
                    $this->User->saveField('university_id', $universities['University']['id']);
                    $this->Session->setFlash('You have been associated with '. $universities['University']['name'].'.');
                }
                else
                {
                    $this->Session->setFlash('The university you are trying to associate with is not in our database. Please contact support.');
                }
            } else {
                $this->Session->setFlash(__('There was a problem associating your account.'));
            }

                
                //$this->Auth->autoRedirect = false;
                //$this->request->data['User']['username'] = $this->User->username;
                //$this->request->data['User']['password'] = $this->User->password;
                //$this->Auth->login();
                //$this->redirect('account');
                //$this->redirect(array('action' => 'index'));

        }
        else {
            $this->Session->setFlash('There was a problem verifying the account.');
        }

        
    }
   /* public function account() {
        $this->set('first_name', $this->Auth->user('first_name'));
        $this->set('last_name', $this->Auth->user('last_name'));

         $id = $this->Auth->user('id');
        $this->User->id = $id;

        // this user edit
        
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            //replace data of empty things
            /*if ($this->request->data['User']['first_name'] != '')
            {
                if($this->User->saveField('first_name', $this->request->data['User']['first_name'], true))
                {

                $this->Session->write('Auth', $this->User->read(null, $this->Auth->User('id')));
                $this->Session->setFlash(__('The user changes have been saved.'));
                $this->redirect(array('action' => 'account'));
                }
                //$this->Session->write('Auth', $this->User->read(null, $this->Auth->User('id')));
                $this->Session->setFlash(__('There was an error updating your user.'));
                $this->redirect(array('action' => 'account'));
                

            }
            else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            
            if($this->request->data['User']['first_name'] == '')
            {
                 $this->request->data['User']['first_name'] = $this->Auth->user('first_name');
            }
            if($this->request->data['User']['last_name'] == '')
            {
                 $this->request->data['User']['last_name'] = $this->Auth->user('last_name');
            }
            if($this->request->data['User']['email'] == '')
            {
                 $this->request->data['User']['email'] = $this->Auth->user('email');
            }
            //execute save based on this field
            if($this->request->data['User']['password'] == '')
            {
                $this->User->save($this->request->data, true, array('first_name','last_name','email'));
               $this->Session->setFlash('Your information was saved.');
               $this->Session->write('Auth', $this->User->read(null, $this->Auth->User('id')));
               unset($this->request->data['User']);
                $this->set('first_name', $this->Auth->user('first_name'));
                $this->set('last_name', $this->Auth->user('last_name'));
                
               
            }
            else if ($this->request->data['User']['password']!= '' )
            {
                $this->User->save($this->request->data, true, array('first_name','last_name','password'));
                $this->Session->write('Auth', $this->User->read(null, $this->Auth->User('id')));
                $this->Session->setFlash('Your information was saved.');
                unset($this->request->data['User']);
                $this->set('first_name', $this->Auth->user('first_name'));
                $this->set('last_name', $this->Auth->user('last_name'));

            }

               

            /*
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->Session->write('Auth.first_name', $this->User->first_name);
                $this->Session->write('Auth.last_name', $this->User->last_name);
                $this->Session->write('Auth.email', $this->User->email);
                
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            
            //}
        } 
        //
       
    }
*/
    public function ajaxEditUser(){
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
        $user = $this->User->get($this->Auth->User('id'));
        $first_name = $this->request->data['first_name'];
        $last_name = $this->request->data['last_name'];
        if(empty($first_name) or empty($last_name)){
            $json = json_encode(array(
                'success' => 0,
                'message' => "A first name or last name left blank"
            ));
        }else{
            $user['User']['first_name'] = $first_name;
            $user['User']['last_name'] = $last_name;  

            $data = array('id' => $user['User']['id'], 'first_name' => $first_name, 'last_name' => $last_name);
            $user = $this->User->edit($data);

            $json = json_encode(array(
                'success' => 1,
                'user' => json_encode($user)
            ));  
        }

        $this->layout = 'ajax';
        $this->set('response', $json);
        return;
        
    }

    public function getTwitterFollowers($user_id)
    {
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
        App::import('Vendor', 'twitter/twitteroauth');
        App::import('Vendor', 'twitter/twconfig');

        $user = $this->User->get($user_id);

        $connection = new TwitterOAuth(CONSUMER_KEY, 
                                        CONSUMER_SECRET,
                                        $user['User']['twitter_auth_token'],
                                        $user['User']['twitter_auth_token_secret']);

        $content = $connection->get('account/verify_credentials');
        $followers_count = $content->followers_count;
        $json_response = json_encode(array(
            'tw_id'=>$user['User']['twitter_userid'],
            'followers_count'=>$followers_count
            ));

        $this->layout = 'ajax';
        $this->set("response", $json_response);
    }



	public function Logout()
	{
		$this->autoRender = false;
		$this->facebook->destroySession();
        $this->Session->destroy();
        $this->Auth->logout();
		$this->redirect('/');
	}
}
?>