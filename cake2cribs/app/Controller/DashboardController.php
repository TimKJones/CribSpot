<?php
	class DashboardController extends AppController {
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
		
		/*
		 
		 Dashboard Directive

		 The dashboard takes a directive. A directive is json serialized object that
		 has been set in the cookie. This would be set by another controller's action and 
		 then redirected to the dashboard page. An example of this is if you want to 
		 open the dashboard to a certain conversation, you can provide data inside the 
		 directive to help the UI direct it's actions

		 the form of the object is 
		 {
		 	'classname': string (messages, listings, account)
		 	'data': {
						'key': values
				 	}

		 }
		 
		 */



	 	public function index(){
	 		$directive = $this->Cookie->read('dashboard-directive');
	 		$this->Cookie->delete('dashboard-directive');
	 		// die();
	 		if($directive == null){
	 			$directive = array('classname'=>null);
	 		}


	 		$user = $this->User->get($this->Auth->User('id'));
	 		// die(debug($user));
	 		$this->User->University->id = $user['User']['university_id'];
	 		$this->Session->write('Auth.User.University.name', $this->User->University->field('name'));
	 		$this->set(array('directive'=> json_encode($directive), 'user' => $user));
	 	}
	}