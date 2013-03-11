<?php 

class FilterAnalytic extends AppModel {
	public $name = 'FilterAnalytic';
	public $primaryKey = 'filter_analytic_id';
	public $hasMany = array(
						'ClickAnalytic' => array(
								'className' => 'ClickAnalytic', 
								'foreignKey' => 'filter_analytic_id'
						)
	);
	
	public $validate = array(
		'filter_analytic_id' => 'alphaNumeric',
		'min_rent' => 'alphaNumeric',
		'max_rent' => 'alphaNumeric',
		'min_beds' => 'alphaNumeric',
		'max_beds' => 'alphaNumeric',
		'fall' => 'boolean',
		'spring' => 'boolean',
		'other' => 'boolean',
		'house' => 'boolean',
		'apartment' => 'boolean',
		'duplex' => 'boolean',
		'count' => 'alphaNumeric'
	);	

	public function AddFilter($filter, $marker_id)
	{
		$conditions = array(
			'FilterAnalytic.min_rent' => $filter['minRent'],
			'FilterAnalytic.max_rent' => $filter['maxRent'],
			'FilterAnalytic.min_beds' => $filter['minBeds'],
			'FilterAnalytic.max_beds' => $filter['maxBeds'],
			'FilterAnalytic.fall' => $filter['lease_fall'],
			'FilterAnalytic.spring' => $filter['lease_spring'],
			'FilterAnalytic.other' => $filter['lease_other'],
			'FilterAnalytic.house' => $filter['house'],
			'FilterAnalytic.apartment' => $filter['apartment'],
			'FilterAnalytic.duplex' => $filter['duplex']
		);

		$filter_id = null;

		if (!$this->hasAny($conditions)){
			// Increment the count
			$newFilter = array(
				'min_rent' => $filter['minRent'],
				'max_rent' => $filter['maxRent'],
				'min_beds' => $filter['minBeds'],
				'max_beds' => $filter['maxBeds'],
				'fall' => $filter['lease_fall'],
				'spring' => $filter['lease_spring'],
				'other' => $filter['lease_other'],
				'house' => $filter['house'],
				'apartment' => $filter['apartment'],
				'duplex' => $filter['duplex'],
				'count' => 0
			);

			//die(debug($newFilter));

			$this->create();
			if ($this->save($newFilter))
				$filter_id =  $this->id;
			else
			{
				//die(debug($this->validationErrors)); 
				$filter_id =  -1;				
			}

		}
		else
		{
			$this->updateAll(array('FilterAnalytic.count'=>'FilterAnalytic.count+1'),
							 $conditions);
			$filter_id_query = $this->find('first', array(
				'conditions' => $conditions,
				'fields' => 	array('filter_analytic_id')));
			$filter_id = $filter_id_query['FilterAnalytic']['filter_analytic_id'];
		}

		CakeLog::write('filters', $filter['minRent'] . " " . $filter['maxRent'] . " " . $filter['minBeds'] . " " .
			$filter['maxBeds'] . " " .$filter['lease_fall'] . " " .$filter['lease_spring'] . " " .$filter['lease_other'] . " " . 
			$filter['house'] . " " .$filter['apartment'] . " " . $filter['duplex'] . " " . $filter_id);

		return $filter_id;

	}	
}


