<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components= array('Session', 'Cookie', 'Email', 'Auth' => array(
        'authenticate' => array(
            'Form' => array(
                'fields' => array('username' => 'email')
                )
            )
    ));

	var $facebook;
	var $_jsVars = array();
	public $helpers = array(
		'Session',
    );

	public function beforeFilter()
	{
		$this->Auth->authError = "You need to login to do that.";
		$this->Auth->allow('beforeRender','__initLogin');
		App::import('WideImage', 'WideImage');
		/*App::import('Lib', 'Facebook.FB');   
    	$this->facebook = new Facebook(array(
      		'appId'  => Configure::read("FB_APP_ID"),
      		'secret' => Configure::read("FB_APP_SECRET"),
    	));*/
	}

	public function beforeRender()
	{
		// Set the jsVars array which holds the variables to be used in js
        $this->set('jsVars', $this->_jsVars);

		$flash_message = $this->Cookie->read('flash-message');
	 	$this->Cookie->delete('flash-message');
        $this->set('flash_message', json_encode($flash_message));

		if($this->Auth->User()){
			$Users = ClassRegistry::init('User');
			$safe_user = $Users->getSafe($this->Auth->User('id'));
			$this->set('AuthUser', $safe_user['User']);
		}

		/* Set default meta content */
		$title = 'Cribspot - College Housing Made Simple';
		$description = "Cribspot takes the pain out of finding college housing. We've gathered thousands of listings so " .
		"you can stop stressing about housing and get back to making the most of your college experience.";
		$this->set('meta', array(
			'title' => $title,
			'description' => $description
		));
	}

	/* 
    Logs in a user given their local user object.
    */
    protected function _login($user)
    {
    	App::import('model', 'User');
    	$User = new User();
        $User->UpdateLastLogin($user['User']['id']);
        $this->Auth->login($user['User']);
        return;
    }

	protected function _getUserId()
	{
		return $this->Auth->User('id'); 
	}

	/**
     * Method to set javascript variables
     *
     * This method puts the passed variable in an array. That array is
     * then converted to json object in layout and can be used
     * in js files
     *
     * @param string $name Name of the variable
     * @param mixed $value Value of the variable
     *
     * @return void
     */
    public function setJsVar($name, $value)
    {
        $this->_jsVars[$name] = $value;
    }

  	 function getLastQuery()
	{
		$dbo = $this->getDatasource();
		$logs = $dbo->_queriesLog;

		return end($logs);
	}

	public function SendEmail($from, $to, $subject, $template, $sendAs)
	{
		$this->Email->smtpOptions = array(
	          'port'=>'587',
	          'timeout'=>'30',
	          'host' => 'smtp.sendgrid.net',
	          'username'=>'cribsadmin',
	          'password'=>'lancPA*travMInj',
	          'client' => 'a2cribs.com'
		);
	    $this->Email->delivery = 'smtp';
	    $this->Email->from = $from;
	    $this->Email->to = $to;
	    $this->Email->subject = $subject;
	    $this->Email->template = $template;
	    $this->Email->sendAs = $sendAs;
	    $this->Email->send();
	}
}
