<?php
	class DashboardController extends AppController {
		public $helpers = array('Html');
		public $uses = array('User');
		public $components= array('Session','Auth');

		function beforeFilter(){
			parent::beforeFilter();
	    	if(!$this->Auth->user()){
	        	//$this->flash("You may not access this page until you login.", array('controller' => 'users', 'action' => 'login'));
	        	$this->Session->setFlash(__('Please login to view your dashbaord.'));
	        	$this->redirect(array('controller'=>'users', 'action'=>'login'));
	    	}
		}
		
		//Shows the base messages page
	 	public function index(){}
	}