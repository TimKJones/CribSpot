<?php
class LandingController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array('School');

	public function beforeFilter() {
		$this->Auth->allow('index');
		parent::beforeFilter();
	}

	function index()
	{
		$this->set('locations', $this->School->getSchools());
	}
}
?>