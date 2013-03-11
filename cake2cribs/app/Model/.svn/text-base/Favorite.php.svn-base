<?php 

class Favorite extends AppModel {
	public $name = 'Favorite';
	public $primaryKey = 'favorite_id';
	public $belongsTo = array('Listing');

	public $validate = array(
		'favorite_id' => 'alphaNumeric',
		'listing_id' => 'alphaNumeric',
		'user_id' => 'alphaNumeric'
	);	

	public function GetFavoritesListingIds($user_id)
	{
		$listingIds = $this->find('all', array(
			'conditions' => array('Favorite.user_id' => $user_id),
			'fields' => 'Favorite.listing_id'));

		return $listingIds;
	}

	public function AddFavorite($listing_id, $user_id){
		$conditions = array(
			'Favorite.user_id' => $user_id,
			'Favorite.listing_id' => $listing_id);

		if (!$this->hasAny($conditions)){
			/* Add new  favorite for this $listing_id */	
			//return $this->Session;
			$newFavorite = array(
				'listing_id' => $listing_id, 
				'user_id' => $user_id
			);
			$this->create();
			if ($this->save($newFavorite))
				return $this->id;
		}

		return -1;
		/*TODO: Need error handling response here. */
	}

	public function DeleteFavorite($favorite_id)
	{
		$this->delete($favorite_id);
	}

/*
If record for current user and requested listing_id does no exists, then creates one;
Otherwise, deletes this record.
*/
	public function EditFavorite($listing_id, $user_id){
	/*TODO: HANDLE CASES OF FAILURE WITH DATABASE UPDATES */
		$conditions = array(
			'Favorite.user_id' => $user_id,
			'Favorite.listing_id' => $listing_id);

		if (!$this->hasAny($conditions)){
			/* Add new  favorite for this $listing_id */	
			//return $this->Session;
			$newFavorite = array(
				'listing_id' => $listing_id, 
				'user_id' => $user_id
			);
			$this->create();
			if ($this->save($newFavorite))
				return;
			else
			{
				/*TODO: Need error handling response here. */
			}
		}
		
		/* Favorite already exists
		 * Find favorite_id and delete the row */
		$favorite_id_query = $this->find('first', array(
			'conditions' => array('Favorite.listing_id' => $listing_id,
								  'Favorite.user_id'	=> $user_id),
			'fields' => 	array('favorite_id')));
		$this->delete($favorite_id_query['Favorite']['favorite_id']);
	}
}
?>
