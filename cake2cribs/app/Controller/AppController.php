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
	public $components= array('Session',
		'Auth');
	var $facebook;
	var $_jsVars = array();
	public $helpers = array(
		'Session',
		'Html' => array('className' => 'TwitterBootstrap.BootstrapHtml'),
        'Form' => array('className' => 'TwitterBootstrap.BootstrapForm'),
        'Paginator' => array('className' => 'TwitterBootstrap.BootstrapPaginator'),
    );

	public function beforeFilter()
	{
		$this->Auth->authError = "You need to login to do that.";
		//$this->Auth->allow('pages', 'display');
		$this->Auth->allow('beforeRender','__initLogin');
		$this->Auth->allow('InitFilterValues');
		//$this->Auth->allow('map','index');
		//$this->Auth->allow('map','ViewListing');
		//$this->Auth->allow('user','confirmUser');
		//$this->Auth->allow('user','logout');
		//$this->Auth->allow('map','LoadMarkers');
		//$this->Auth->allow('listing','LoadMarkerData');
		//$this->Auth->allow('listing','ApplyFilter');
		//$this->Auth->allow('FindSubletPosition','index');
		//$this->Auth->allow('Images','index');
		//$this->Auth->allow('Images','add');
		//$this->Auth->allow('Images','add2');
		//$this->Auth->allow('Images','add3');
		$this->Auth->allow('Images','edit');
		$this->Auth->allow('Images','edit2');
		//$this->Auth->allow('Images','LoadImages');
		//$this->Auth->allow('Images','DeleteImage');
		//$this->Auth->allow('Marker', 'UpdateCache');
		//$this->Auth->allow('Landing', 'index');
		//$this->Auth->allow('Landing', 'index2');
		//$this->Auth->allow('Landing', 'index3');
		//$this->Auth->allow('Landing', 'index4');
		$this->Auth->allow('verify', 'index');
		$this->Auth->allow('verify', 'TwitterVerify');
		//$this->Auth->allow('verify', 'Callback');
		//$this->Auth->allow('user','resetpassword');
		//$this->Auth->allow('sublet', 'ajax_add');
		$this->Auth->allow('verify','map');
		App::import('Vendor', 'Facebook', array('file' => 'facebook/src/facebook.php'));     
    	$this->facebook = new Facebook(array(
      		'appId'  => Configure::read("FB_APP_ID"),
      		'secret' => Configure::read("FB_APP_SECRET"),
    	));

		$this->__initLogin();

	}

	public function beforeRender()
	{
		// Set the jsVars array which holds the variables to be used in js
        $this->set('jsVars', $this->_jsVars);

		if($this->Auth->User()){
			$Users = ClassRegistry::init('User');
			$safe_user = $Users->getSafe($this->Auth->User('id'));

			$this->set('AuthUser', $safe_user['User']);
		}
	}

	protected function _getUserId()
	{
		return 15;
	}

/*Facebook stuff - maybe should be in another controller? */
/*TODO: TEST LOGOUT FUNCTIONALITY MORE THOROUGHLY */
/*
Try and log in.
Set userid in session as well as $this->loginUrl and $this->logoutUrl
*/
	public function __initLogin() {
 
	    if (isset($this->params['url']['code']) and $this->params['url']['code'] !='' ){
	        $uid = $this->facebook->getUser();     
					/*TODO: INVESTIGATE THIS LINE BELOW */
	        //echo "<script type='text/javascript'>top.location.href = '".Configure::read('APP_URL')."';</script>";
	        exit;
	    }
     
    	$uid = $this->facebook->getUser();     
	    if ($uid) {
	      try {
	        $user_profile = $this->facebook->api('/me');
	        $this->Session->write('user', $uid);
	      } catch (FacebookApiException $e) {   
	        $this->Session->write('user', 0);
	      }
	    }
	    else
	    	$this->Session->write('user', 0);

    	$loginUrl = $this->facebook->getLoginUrl(
            array(
                'scope' => 'email'
            ),''
    	);
		$logoutUrl = $this->facebook->getLogoutUrl();
 		$this->set('loginUrl',  $loginUrl);
		$this->set('logoutUrl', $logoutUrl);
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

    public function InitFilterValues()
  	{
	    $this->Session->write('start_date', "NOT_SET");
	    $this->Session->write('end_date', "NOT_SET"); 
	    $this->Session->write('min_rent', 0); 
	    $this->Session->write('max_rent', 999999); 
	    $this->Session->write('beds', 0);
	    $this->Session->write('house', "true"); 
	    $this->Session->write('apt', "true"); 
	    $this->Session->write('unit_type_other', "true");  
	    $this->Session->write('male', "true");  
	    $this->Session->write('female', "true");  
	    $this->Session->write('students_only', "false");  
	    $this->Session->write('grad', "true"); 
	    $this->Session->write('undergrad', "true"); 
	    $this->Session->write('bathroom_type', "false"); 
	    $this->Session->write('ac', "false");
	    $this->Session->write('parking', "false");
	    $this->Session->write('utilities_included', "false");  
	    $this->Session->write('no_security_deposit', "false");
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