<?php 	

class PropertyManager extends AppModel {
	public $name = 'PropertyManager';
	public $primaryKey = 'property_manager_id';
	/*public $belongsTo = array('');*/

	public $validate = array(
		'property_manager_id' => 'numeric',
		'user_id' => 'numeric',
		'lease_office_address' => 'alphaNumeric',
		'contact_email' => 'email',
		'contact_phone' => array('phone', null, 'us'),
		'website' => 'url'
	);
}

?>