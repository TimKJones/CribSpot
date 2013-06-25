<?php 	

class Fee extends AppModel {
	public $name = 'Fee';
	public $primaryKey = 'fee_id';
	/*public $belongsTo = array('');*/

	public $validate = array(
		'fee_id' => 'numeric',
		'listing_id' => 'numeric',
		'description' => 'alphaNumeric',
		'amount' => 'numeric'
	);

	/*
	Saves an array of fee objects to the fees table

	*/
	public function SaveFees($fees, $listing_id)
	{
		return array("success" => "");
	}
}

?>