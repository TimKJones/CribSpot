<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	/**
	 * static enums
	 * @access static
	 */
	public static function enum($value, $options, $default = '') {
	    if ($value !== null) {
	        if (array_key_exists($value, $options)) {
	            return $options[$value];
	        }
	        return $default;
	    }
	    return $options;
	}

	/*
	Log an error to the errors table.
	*/
	public function LogError($user_id, $error_code, $debug_info)
	{
		App::import('Model', 'Error');
		$Error = new Error();
		$Error->AddError($user_id, $error_code, $debug_info);
	}

	function getLastQuery()
	{
	    $dbo = $this->getDatasource();	
	    $logs = $dbo->getLog(false);
	    return ($logs);
	}

	/* 
	USED WHEN TRYING TO SAVE NULL VALUES TO A TABLE.
	Remove (key,value) pairs from array where value is null so that cakephp won't complain.
	They will be set to null by default after being saved to the table.
	*/

	protected function _removeNullEntries($rental)
	{
		foreach ($rental as $key => $value)
		{
			if ($rental[$key] == null || $rental[$key] == "")
				unset($rental[$key]);
		}

		return $rental;
	}

	/*
	Used when saving a row that is labeled as incomplete (is_complete = 0)
	Removes the keys from the array that are in $keysFailed
	*/

	protected function _removeFailedKeys($array, $keysFailed)
	{
		foreach ($keysFailed as $key => $value)
			unset($array[$key]);

		return $array;
	}
}
