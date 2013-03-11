<?php

class RealtorsController extends AppController {
	public $helpers = array('Html');
	public $uses = array('Realtor');
	public $components= array('Session');

	public function beforeFilter(){
		parent::beforeFilter();
     $this->Auth->allow('LoadRealtor');
  	}
  	
	public function LoadRealtor($realtor_id)
	{
		$response = $this->Realtor->LoadRealtor($realtor_id);
		$this->layout = 'ajax';
		$this->set('response', $response);
	}
}
