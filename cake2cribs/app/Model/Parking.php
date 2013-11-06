 <?php

 class Parking extends AppModel{

 	public $name = 'spot';
 	public $primaryKey = 'spot_id';
 	public $belongsTo = array(
 		'Listing' => array(
 			'className' => 'Listing',
 			'foreignKey' => 'listing_id'
 			)
		);	

 	public $actsAs = array('Containable');

 	public $validate  array(
 		'spot_id' => 'numeric',
 		'listing_id' => 'numeric',
 		'price' => 'numeric',

 		'space_count' => array(  // Not required, it will defualt to one
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		), 
 		
 		'start_date' => array(
			'date' => array(
				'rule' => array('date', 'ymd'),
				'required' => true
			),
		'end_date' => array(
			'date' => array(
				'rule' => array('date', 'ymd'),
				'required' => true
			),
		'description' => array(
				'between' => array(
				'rule' => array('between',0,1000),
				'message' => 'Must be less than 1000 characters'
			)
		),

		'is_complete' => 'boolean',
		'created' => 'datetime',
		'modified' => 'datetime'
	};