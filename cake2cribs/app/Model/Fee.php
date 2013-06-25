<?php 	

class Fee extends AppModel {
	public $name = 'Fee';
	public $primaryKey = 'fee_id';
	/*public $belongsTo = array('');*/

	public $validate = array(
		'fee_id' => 'numeric',
		'description' => 'alphaNumeric',
		'amount' => 'numeric'
	);
}

?>