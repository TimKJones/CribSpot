<?php 

class Error extends AppModel {
	public $name = 'Error';
	public $primaryKey = 'error_id';

	public $validate = array(
		'error_id' => 'numeric',
		'user_id' => 'numeric',
		'error_code' => 'numeric'
	);	

	public function AddError($user_id, $error_code, $debug_info)
	{
		$error = array(
			'Error'=>array(
				'user_id' => $user_id,
				'error_code' => $error_code,
				'debug_info' => $debug_info
		));

		$error['Error'] = $this->_removeNullEntries($error['Error']);
		if (!$this->save($error))
			CakeLog::write("ErrorFailed", print_r($this->validationErrors, true));
	}
}
?>
