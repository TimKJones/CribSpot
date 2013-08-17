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

		$ids = array();
		foreach ($listingIds as $listingId){
			array_push($ids, $listingId['Favorite']['listing_id']);
		}

		return $ids;
	}

	public function AddFavorite($listing_id, $user_id){
		$conditions = array(
			'Favorite.user_id' => $user_id,
			'Favorite.listing_id' => $listing_id);

		if (!$this->hasAny($conditions)){
			/* Add new  favorite for this $listing_id */
			$newFavorite = array('Favorite' => array(
				'listing_id' => $listing_id, 
				'user_id' => $user_id
			));

			if (!$this->save($newFavorite)){
				$error = null;
				$error['favorite'] = $newFavorite;
				$error['validationErrors'] = $this->validationErrors;
				$this->LogError($user_id, 41, $error);
				return array("error" => array('validation' => $this->validationErrors,
				'message' => 'Looks like we had some problems adding your favorite...but we want to help! If the issue continues, ' .
				'chat with us directly by clicking the tab along the bottom of the screen or send us an email ' . 
					'at help@cribspot.com. Reference error code 41.'));
			}
			else
				return array("success" => "");
		}

		$error = null;
		$error['conditions'] = $conditions;
		$this->LogError($user_id, 43, $error);
		return array("error" => array('message'=>'Looks like you\'ve already favorited that property. ' .
			'If you think we messed up, let us know!  Chat with us by clicking the tab along the bottom of the screen. ' .
			'Reference error code 43.'));
	}

	public function DeleteFavorite($listing_id, $user_id)
	{
		$conditions = array(
			'Favorite.user_id' => $user_id,
			'Favorite.listing_id' => $listing_id
		);

		if (!$this->hasAny($conditions)) {
			$error = null;
			$error['conditions'] = $conditions;
			$this->LogError($user_id, 42, $error);
			return array('error' => array('message' => 'Looks like there was an issue deleting your favorite...' .
				'but we want to help! You can chat with us directly by clicking the tab along the bottom of the screen ' .
				'or by sending us an email at help@cribspot.com. Reference error code 42.'));
		}
		
		/* 
		Favorite already exists
		Find favorite_id and delete the row 
		*/
		$favorite_id_query = $this->find('first', array(
			'conditions' => array('Favorite.listing_id' => $listing_id,
								  'Favorite.user_id'	=> $user_id),
			'fields' => 	array('favorite_id')));
		$this->delete($favorite_id_query['Favorite']['favorite_id']);
		return array('success' => '');
	}
}
?>
