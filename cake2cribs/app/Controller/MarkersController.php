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
	public function Save()
	{
		$this->layout = 'ajax';
		$marker = $this->params['data'];
		CakeLog::write("savingMarker", print_r($marker, true));
		$marker_id = $this->Marker->FindMarkerId($marker, $this->_getUserId());
		$this->set('response', $marker_id);
	}
}
