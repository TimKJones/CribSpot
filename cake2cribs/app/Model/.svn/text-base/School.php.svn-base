<?php

class School extends AppModel {
	public $name = 'School';
	public $actsAs = array('Containable');
	public $primaryKey = 'school_id';
	/*public $hasMany = array(
						'Listing' => array(
							'className' => 'Listing', 
							'foreignKey' => 'school_id'
						)
	);*/

	public $validate = array(
		'school_id' =>'alphaNumeric', 
		'sw_lat' => 'decimal',
		'sw_long' => 'decimal',
		'ne_lat' => 'decimal',
		'ne_long' => 'decimal'
	);
}

?> 
