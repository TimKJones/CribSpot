<?php 

class Sublet extends AppModel {
	public $name = 'Sublet';
	public $primaryKey = 'sublet_id';
	public $belongsTo = array(
		'Listing' => array(
            'className'    => 'Listing',
            'foreignKey'   => 'listing_id'
        )
	);
	public $actsAs = array('Containable');
	public $validate = array(
		'sublet_id' => 'numeric',
		'listing_id' => 'numeric',
		'rent' => array(  /*this is total rent, not per person */
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'beds' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		),
		'baths' => 'decimal',
		'bathroom_type' => 'integer',
		'parking_available' => 'boolean',
		'parking_description' => array(
			'between' => array(
				'rule' => array('between',0,1000),
				'message' => 'Must be less than 100 characters'
			)
		),
		'utilities_included' => 'boolean',
		'utilities_description' => array(
			'between' => array(
				'rule' => array('between',0,1000),
				'message' => 'Must be less than 100 characters'
			)
		),
		'start_date' => array(
			'date' => array(
				'rule' => array('date', 'ymd'),
				'required' => false
			)
		),
		'end_date' => array(
			'date' => array(
				'rule' => array('date', 'ymd'),
				'required' => false
			)
		),
		'available_now' => 'boolean',
		'deposit' => 'numeric',
		'air' => 'boolean',
		'furnished' => 'integer',
		'pets' => 'integer',
		'description' => array(
			'between' => array(
				'rule' => array('between',0,1000),
				'message' => 'Must be less than 1000 characters'
			)
		)
	);
};