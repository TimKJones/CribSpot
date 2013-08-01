<?php

class MarkersController extends AppController {
	public $uses = array('Marker', 'User', 'Listing');
	public $components= array('RequestHandler', 'Auth', 'Session');

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
	If user is a PROPERTY_MANAGER, overwrites existing marker.
	Returns marker_id to user
	*/
	public function Save()
	{
		$this->layout = 'ajax';
		$marker = $this->params['data'];
		
		/* 
		Only PM can overwrite fields in existing marker.
		Make sure the current PM owns at least one listing at this marker, 
		or they will not be allowed to overwrite.
		*/
		$user_type = $this->Auth->User('user_type');
		if ($user_type == $this->User->USER_TYPE_PROPERTY_MANAGER){
			if (array_key_exists('marker_id', $marker)){
				if (!$this->Listing->UserOwnsAListingAtMarkerId($this->_getUserId(), $marker['marker_id'])){
					CakeLog::write("ErrorMarkerConrollerSave", "Error code: 33; user: " . $this->_getUserId() . 
						"; marker = " . print_r($marker, true));
		            $this->set('response', json_encode(array('error' => 
		                'Failed to reset password. Contact help@cribspot.com if the error persists. Reference error code 33')));
		            return;
				}
			}
		}
		
		$marker_id = $this->Marker->FindMarkerId($marker, $this->_getUserId(), $user_type);
		$this->set('response', $marker_id);
	}
}
