<?php 

class Favorite extends AppModel {
	public $name = 'Favorite';
	public $primaryKey = 'favorite_id';
	public $belongsTo = array('Sublet');

	public $validate = array(
		'favorite_id' => 'alphaNumeric',
		'sublet_id' => 'alphaNumeric',
		'user_id' => 'alphaNumeric'
	);	

	public function GetFavoritesSubletIds($user_id)
	{
		$subletIds = $this->find('all', array(
			'conditions' => array('Favorite.user_id' => $user_id),
			'fields' => 'Favorite.sublet_id'));

		return $subletIds;
	}

	public function AddFavorite($sublet_id, $user_id){
		$conditions = array(
			'Favorite.user_id' => $user_id,
			'Favorite.sublet_id' => $sublet_id);

		if (!$this->hasAny($conditions)){
			/* Add new  favorite for this $listing_id */	
			//return $this->Session;
			$newFavorite = array(
				'sublet_id' => $sublet_id, 
				'user_id' => $user_id
			);

			$this->create();
			if (!$this->save($newFavorite))
				return array("ERROR" => "ERROR_ADDING_FAVORITE");
			else
				return array("SUCCESS" => "SUCCESS");
		}

		return array("ERROR" => "FAVORITE_ALREADY_EXISTS");
		/*TODO: Need error handling response here. */
	}

	public function DeleteFavorite($sublet_id, $user_id)
	{
		$conditions = array(
			'Favorite.user_id' => $user_id,
			'Favorite.sublet_id' => $sublet_id);

		if (!$this->hasAny($conditions)){
			return array('ERROR' => 'FAVORITE_DOES_NOT_EXIST');
		}
		
		/* Favorite already exists
		 * Find favorite_id and delete the row */
		$favorite_id_query = $this->find('first', array(
			'conditions' => array('Favorite.sublet_id' => $sublet_id,
								  'Favorite.user_id'	=> $user_id),
			'fields' => 	array('favorite_id')));
		$this->delete($favorite_id_query['Favorite']['favorite_id']);
		return array("SUCCESS" => "SUCCESS");
	}

/*
If record for current user and requested sublet_id does no exists, then creates one;
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
