<?php

class MarkersController extends AppController {

	public function beforeFilter() {
		$this->Auth->allow('UpdateCache');
		parent::beforeFilter();
	}

	public function UpdateCache()
	{
		$this->Marker->UpdateCache();
	}
}
