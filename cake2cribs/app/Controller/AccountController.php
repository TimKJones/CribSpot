<?php
	class AccountController extends AppController {
		public $helpers = array('Html');
		public $uses = array('User');
		public $components= array('Session','Auth', 'Cookie');

		function beforeFilter(){
			parent::beforeFilter();
	    	if(!$this->Auth->user()){
	        	//$this->flash("You may not access this page until you login.", array('controller' => 'users', 'action' => 'login'));
	        	$this->Session->setFlash(__('Please login to view your dashbaord.'));
	        	$this->redirect(array('controller'=>'users', 'action'=>'login'));
	    	}
		}
		
	 	public function index(){
			$directive['classname'] = 'account';
        	$json = json_encode($directive);
			$this->Cookie->write('dashboard-directive', $json);
			$this->redirect('/dashboard');
			
	 	}
	}