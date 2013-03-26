<?php 

class GenderType extends AppModel {
	public $hasMany = array('Housemate');
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