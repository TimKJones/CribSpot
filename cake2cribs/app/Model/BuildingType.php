<?php 

class BuildingType extends AppModel {
	public $belongsTo = array();
	public $validate = array(
		'id' => 'alphaNumeric', 
		'name' => 'alphaNumeric',
		'description' => 'alphaNumeric'
	);
}
?>
