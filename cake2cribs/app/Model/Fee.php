<?php 	

class Fee extends AppModel {
	public $name = 'Fee';
	public $primaryKey = 'fee_id';
	/*public $belongsTo = array('');*/

	public $validate = array(
		'fee_id' => 'numeric',
		'listing_id' => 'numeric',
		'description' => array(
			'between' => array(
				'rule' => array('between',0,25),
				'message' => 'Must be less than 25 characters'
			)
		),
		'amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => false
			)
		)
	);

	/*
	Saves an array of fee objects to the fees table

	*/
	public function SaveFees($fees, $listing_id)
	{
		for ($i = 0; $i < count($fees); $i++)
		{
			$this->id = null; // reset so that the same entry is not continually overwritten in loop
			$nextFee = $fees[$i];
			
			/* 
			Remove entries from array that are null so that cakephp won't complain.
			They will be set to null by default in the fees table.
			*/
			foreach ($nextFee as $key => $value)
			{
				if ($nextFee[$key] == null)
					unset($nextFee[$key]);
			}

			$nextFee['listing_id'] = $listing_id;
			$feesWrapper['Fee'] = $nextFee;

			if (!$this->save($feesWrapper))
			{
				CakeLog::write("FeeSaveValidationErrors", print_r($this->validationErrors, true));
				return array('error' => 'Fee not saved');
			}
		}

		return array("success" => "");
	}
}

?>