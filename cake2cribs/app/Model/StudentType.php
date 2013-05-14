<?php 

class StudentType extends AppModel {
	public $name = 'StudentType';
	public $hasMany = array('Housemate');
	public $validate = array(
		'id' => 'alphaNumeric', 
		'name' => 'alphaNumeric',
		'description' => 'alphaNumeric'
	);
	public $actsAs = array('Containable');

	public function LoadAll()
	{
		$all = $this->find('all');
		return $all;
	}
}
?>