<?php 

class FurnishedType extends AppModel {
	public $belongsTo = array('Sublet','Listing');
	public $actsAs = array('Containable');
}
?>
