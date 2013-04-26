<?php
	class UniversitiesController extends AppController {
		public $uses = array("University");
		public $components= array('Session','Auth');
		function beforeFilter(){
			parent::beforeFilter();
			$this->Auth->allow('loadAll');	
		}
		
		/*
		Returns via ajax a JSON object containing the necessary fields from all universities.
		Designed for populating the autocomplete text box in posting process.
		*/
	 	public function loadAll()
	 	{
	 		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            	return;

            $this->layout = 'ajax';
            $universities = $this->University->LoadAllUniversities();
            $response = json_encode($universities);
	 		$this->set('response', $response);
	 	}

	}