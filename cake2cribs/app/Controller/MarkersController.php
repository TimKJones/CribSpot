<?php

class MarkersController extends AppController {

	public function beforeFilter() {
		$this->Auth->allow('UpdateCache');
		parent::beforeFilter();
	}

	public function UpdateCache()
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
		$this->Marker->UpdateCache();
	}

	
	public function Save()
	{

	}
}
