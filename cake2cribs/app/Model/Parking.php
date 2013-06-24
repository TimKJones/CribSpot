<?php 	

class Parking extends AppModel {
	public $name = 'Parking';
	public $primaryKey = 'parking_id';
	/*public $belongsTo = array('');*/

	public $validate = array(
		'parking_id' => 'numeric',
		'listing_id' => 'numeric'
	);
}

?>