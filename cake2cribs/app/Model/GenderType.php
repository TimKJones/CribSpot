<?php 

class GenderType extends AppModel {
	public $belongsTo = array();
	public $validate = array(
		'id' => 'alphaNumeric', 
		'name' => 'alphaNumeric',
		'description' => 'alphaNumeric'
	);

	public function LoadAll()
	{
		$all = $this->find('all');
		return $all;
	}
}
?>