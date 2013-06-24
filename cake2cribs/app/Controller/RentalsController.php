<?php
class RentalsController extends AppController 
{	
	public $helpers = array('Html');
	public $uses = array('Rental');
	public $components= array('');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('LoadFavorites');
		$this->Auth->allow('AddFavorite');
		$this->Auth->allow('DeleteFavorite');
  	}

  	/*
	Save each rental object in $rentals
	REQUIRES: each rental object is in the form cake expects for a valid save.
  	*/
  	public function Save($rentals = null)
  	{
      
  	}

  	/*
	Delete each rental with an id in $rental_ids
  	*/
  	public function Delete($rental_ids = null)
  	{

  	}

  	/*
	Returns JSON encoded array of all rentals with ids in $rental_ids.
	If $rental_ids is null, returns all rentals owned by the logged-in user.
  	*/
  	public function Get($rental_ids = null)
  	{

  	}

  	/*
	Save an additional row for each rental with an id in $rental_ids
	TODO: This will be implemented later.
  	*/
  	public function Copy($rental_ids = null)
  	{

  	}
}