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

	/*
	Creates a new marker if address in $marker does not yet exist.
	Otherwise, retrieves marker_id of existing marker with that address.
	Returns marker_id to user
	*/
	public function Save($marker)
	{
		$this->layout = 'ajax';
		$marker = json_decode($marker);
		$marker_id = $this->Marker->FindMarkerId($marker);
		$this->set('response', json_encode($marker_id));
	}
}
