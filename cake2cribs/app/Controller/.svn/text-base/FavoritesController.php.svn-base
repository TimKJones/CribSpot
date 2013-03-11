<?php

class FavoritesController extends AppController {
	public $helpers = array('Html');
	public $uses = array('Favorite', 'Listing');
	public $components= array('Session');

	public function beforeFilter(){
	parent::beforeFilter();
     $this->Auth->allow('*');
  	}
  	
	public function LoadFavorites()
	{
		if( $this->request->is('ajax') || Configure::read('debug') > 0)
		{
			$listingIds = $this->Favorite->GetFavoritesListingIds($this->Session->read('user'));
			$response = $listingIds;
			$response = $this->Listing->GetFavoritesListingsData($listingIds);
			$this->layout = 'ajax';
			$this->set('response', $response);
		}
	}

	public function AddFavorite($listing_id)
	{
		if( $this->request->is('ajax') || Configure::read('debug') > 0)
		{
			$response = $this->Favorite->AddFavorite($listing_id, $this->Session->read('user'));
			$this->layout = 'ajax';
			$this->set('response', $response);
		}
	}

	public function DeleteFavorite($favorite_id)
	{
		if( $this->request->is('ajax') || Configure::read('debug') > 0)
		{
			$response = $this->Favorite->DeleteFavorite($favorite_id);
			$this->layout = 'ajax';
			$this->set('response', $response);
		}
	}

}
