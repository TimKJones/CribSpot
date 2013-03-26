<?php

class FavoritesController extends AppController {
	public $helpers = array('Html');
	public $uses = array('Favorite', 'Sublet');
	public $components= array('Session');

	public function beforeFilter(){
	parent::beforeFilter();
     $this->Auth->allow('LoadFavorites');
     $this->Auth->allow('AddFavorite');
     $this->Auth->allow('DeleteFavorite');
  	}
  	
	public function LoadFavorites()
	{
		if( $this->request->is('ajax') || Configure::read('debug') > 0)
		{
			$sublet_ids = $this->Favorite->GetFavoritesSubletIds($this->Auth->User('id'));
			$marker_ids = $this->Sublet->GetFavoritesMarkerIds($sublet_ids);
			$response = array();
			array_push($response, $sublet_ids, $marker_ids);
			$this->layout = 'ajax';
			$this->set('response', json_encode($response));
		}
	}

	public function AddFavorite($sublet_id)
	{
		if( $this->request->is('ajax') || Configure::read('debug') > 0)
		{
			$response = null;
			if ($this->Auth->User('id') == 0)
				$response = array('ERROR' => 'USER_NOT_LOGGED_IN');
			else
				$response = $this->Favorite->AddFavorite($sublet_id, $this->Auth->User('id'));
			$this->layout = 'ajax';
			$this->set('response', json_encode($response));
		}
	}

	public function DeleteFavorite($sublet_id)
	{
		if( $this->request->is('ajax') || Configure::read('debug') > 0)
		{
			$response = $this->Favorite->DeleteFavorite($sublet_id, $this->Auth->User('id'));
			$this->layout = 'ajax';
			$this->set('response', json_encode($response));
		}
	}

}
