<?php
class LandingController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array('University', 'User');

	public function beforeFilter() {
		$this->Auth->allow('index');
		parent::beforeFilter();
	}

	function index()
	{
		$this->set('locations', $this->University->getSchools());
        $this->set('user_years', $this->User->GetYears());
	}
}
?>