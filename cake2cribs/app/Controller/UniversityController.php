<?php
	class UniversityController extends AppController {
		public $uses = array ("University", "User");
		public $components= array('Session','Auth');
		function beforeFilter(){
			parent::beforeFilter();
			$this->Auth->allow('schoolpage');
		}

		public function schoolpage($school_name)
		{
			if ($school_name == null)
				$this->redirect(array('controller' => 'landing', 'action' => 'index'));

			if ($school_name != null)
			{             
				$this->Session->write("currentUniversity", $school_name);
				$school_name = str_replace("_", " ", $school_name);
				$id = $this->University->getIdfromName($school_name);
				if ($id == null)
					throw new NotFoundException();

				/* store university id to enable 'back to map' button */
				if ($this->Auth->User('id') != null)
					$this->User->SavePreferredUniversity($this->Auth->User('id'), $id);
				else
					$this->Session->write('preferredUniversity', $id); 

				$this->set('school_id', $id);
				$university = $this->University->findById($id);
				if ($university == null)
					throw new NotFoundException();
	
				$this->set('university', $university);
			}
			$this->set('school_name', $school_name);
			$this->set('locations', $this->University->getSchools());
			$this->set('user_years', $this->User->GetYears());
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