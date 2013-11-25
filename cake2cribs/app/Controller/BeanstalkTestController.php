<?php

class BeanstalkTestController extends AppController {
	public $helpers = array('Html', 'Js');
	public $uses = array();

	public function beforeFilter() {
		$this->Auth->allow('LogTest');
		parent::beforeFilter();
	}

	public function index()
	{

	}

	/*
	Create a Beanstalk job
	*/
	public function LogTest()
	{	
		$payload = array(
			'text' => "Let's log something!"
		);
		$options = array(
			'tube' => 'log'
		);

		ClassRegistry::init('Queue.Job')->put($payload, $options);
	}
}
