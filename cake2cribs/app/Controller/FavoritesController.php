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
     $this->Auth->allow('GetFavoritesListingIds');
  	}
  	
	public function LoadFavorites()
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		$this->layout = 'ajax';
		$listing_ids = $this->Favorite->GetFavoritesListingIds($this->Auth->User('id'));
		$this->set('response', json_encode($listing_ids));
	}

	/*
	Add $listing_id to the logged-in user's favorites
	*/	
	public function AddFavorite($listing_id)
	{
		if(!$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		$response = null;
		if ($this->Auth->User('id') == 0)
			$response = array('error' => array('message'=>"You must log in to add favorites."));
		else
			$response = $this->Favorite->AddFavorite($listing_id, $this->Auth->User('id'));

		$this->layout = 'ajax';
		$this->set('response', json_encode($response));
	}

	/*
	Delete $listing_id from the logged-in user's favorites
	*/
	public function DeleteFavorite($listing_id)
	{
		if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		$response = $this->Favorite->DeleteFavorite($listing_id, $this->Auth->User('id'));
		$this->layout = 'ajax';
		$this->set('response', json_encode($response));
	}

/* --------------------------------- API ------------------------------------------------- */
	public function GetFavoritesListingIds($user_id)
	{
		$this->layout = 'ajax';
		$listing_ids = null;
		if (array_key_exists('token', $this->request->query) &&
			!strcmp($this->request->query['token'], Configure::read('IPHONE_API_TOKEN'))) {
			header('Access-Control-Allow-Origin: *');
			$listing_ids = $this->Favorite->GetFavoritesListingIds($user_id);
			$listing_ids = json_encode($listing_ids);
		}
	
		$this->set('response', $listing_ids);
	}
}
