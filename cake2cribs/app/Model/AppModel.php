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

	const GET_ALL_OF_THIS_TYPE = -1;

	/**
	 * static enums
	 * @access static
	 */
	public static function enum($value, $options, $default = '') {
		if ($value !== null) {
	        if (array_key_exists($value, $options)) {
	            return $options[$value];
	        }
	        else if ($value === AppModel::GET_ALL_OF_THIS_TYPE)
	        	return $options;
	        
	        return $default;
	    }

	    return $default;
	}

	/*
	Used for enum types.
	Returns a numeric value for the string given as $stringToMatch
	*/
	public static function StringToInteger($stringToMatch, $options, $default =''){
		if ($stringToMatch !== null) {
			if (array_key_exists($stringToMatch, $options)){
				return $options[$stringToMatch];
			}

	        return $default;
	    }
	    
	    return null;
	}

	/*
	Log an error to the errors table.
	*/
	public function LogError($user_id, $error_code, $debug_info)
	{
		App::import('Model', 'Error');
		$Error = new Error();
		$Error->AddError($user_id, $error_code, json_encode($debug_info));
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
			if ($rental[$key] === null || $rental[$key] === "")
				unset($rental[$key]);
		}

		return $rental;
	}

	/*	
	Input: User object
	Removes all sensitive fields that shouldn't be returned to client.
	Returns modified User object with sensitive fields removed.
	Returns null on error.
	*/
	protected function _removeSensitiveUserFields($user)
	{
		unset($user['user_type']);
		unset($user['password']);
		unset($user['email']);
		unset($user['phone']);
		unset($user['vericode']);
		unset($user['created']);
		unset($user['modified']);
		unset($user['password_reset_token']);
		unset($user['password_reset_date']);
		unset($user['twitter_auth_token']);
		unset($user['twitter_auth_token_secret']);
		return $user;
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
