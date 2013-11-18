<?php
	class UniversitiesController extends AppController {
		public $uses = array("University");
		public $components= array('Session','Auth');
		function beforeFilter(){
			parent::beforeFilter();
			$this->Auth->allow('loadAll');	
			$this->Auth->allow('GetUniversities');	
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

/* ----------------------------------- API --------------------------------------------- */
		public function GetUniversities()
		{
			$this->layout = 'ajax';
			$universities = null;
			if (array_key_exists('token', $this->request->query) &&
				!strcmp($this->request->query['token'], Configure::read('IPHONE_API_TOKEN'))) {
				header('Access-Control-Allow-Origin: *');
				$universities = $this->University->getSchools();
				$universities = json_encode($universities);
			}
		
			$this->set('response', $universities);
		}
	}