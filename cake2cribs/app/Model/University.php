<?php 

class University extends AppModel {
	public $hasMany = array('User', 'Sublet');
}
?>
