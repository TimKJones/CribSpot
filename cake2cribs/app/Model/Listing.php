<?php 

class Listing extends AppModel {
	public $name = 'Listing';
	public $primaryKey = 'listing_id';
	/*public hasOne = array('Rental', 'Sublet', 'Parking');*/
	public $validate = array(
		'listing_id' => 'alphaNumeric',
		'listing_type' => 'alphaNumeric'
	);
}	

?>