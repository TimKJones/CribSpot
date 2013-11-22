<?php
class LandingController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array('University');

	public function beforeFilter() {
		$this->Auth->allow('index');
		parent::beforeFilter();
	}

	function index()
	{
		$locations = array();
		foreach ($this->University->getSchools() as $school) {
			if (!empty($school['University']['visible'])) {
				array_push($locations, $school);
			}
		}
		$this->set('locations', $locations);
	}
}
?>