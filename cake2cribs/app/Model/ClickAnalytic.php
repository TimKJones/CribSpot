<?php 

class ClickAnalytic extends AppModel {
	public $name = 'ClickAnalytic';
	public $primaryKey = 'click_analytic_id';
	public $belongsTo = array('FilterAnalytic');


	public $validate = array(
		'click_analytic_id' => 'alphaNumeric',
		'filter_analytic_id' => 'alphaNumeric',
		'marker_id' => 'alphaNumeric',
		'user_id' => 'alphaNumeric',
		'created' => 'date'
	);

	public function AddClick($user_id, $marker_id, $filter_id)
	{
		$conditions = array(
			'ClickAnalytic.user_id' => $user_id,
			'ClickAnalytic.marker_id' => $marker_id,
			'ClickAnalytic.filter_analytic_id' => $filter_id
		);

		$newClickAnalytic = array(
			'filter_analytic_id' => $filter_id, 
			'marker_id' => $marker_id,
			'user_id'   => $user_id,
			'count'     => 0
		);

		//die(debug($newClickAnalytic));

		$this->create();
		if ($this->save($newClickAnalytic))
		{
			// SUCCESSFUL
		}
		else
		{
			//die(debug($this->validationErrors));
		}

		CakeLog::write('clicks', $filter_id . " " . $marker_id . " " . $user_id . " " . date('Y-m-d H:i:s'));
	}
}


