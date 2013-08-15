<?php
	class UniversityController extends AppController {
		public $uses = array ("University");
		public $components= array('Session','Auth');
		function beforeFilter(){
			parent::beforeFilter();
	    	if(!$this->Auth->user()){
	        	//$this->flash("You may not access this page until you login.", array('controller' => 'users', 'action' => 'login'));
	        	$this->Session->setFlash(__('Please login to view your dashbaord.'));
	        	$this->redirect(array('controller'=>'users', 'action'=>'login'));
	    	}
		}
		
	 	public function getAll(){
	 		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            	return;

	 		$options['fields'] = array('University.name', 'University.id', 'University.city', 'University.state', 'University.latitude', 'University.longitude');
	 		$options['recursive'] = -1;
	 		$options['orderby'] = array('University.name' => 'desc');
	 		$universities = $this->University->find('all', $options);
	 		// die(debug(json_encode($universities)));
	 		$response = json_encode($universities);
	 		$this->layout = 'ajax';
	 		$this->set('response', $response);
	 	}


	}