<?php

class FindSubletPositionController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array();

	public function beforeFilter() {
		$this->Auth->allow('index');
		parent::beforeFilter();
	}

	public function index()
	{

	}

}
