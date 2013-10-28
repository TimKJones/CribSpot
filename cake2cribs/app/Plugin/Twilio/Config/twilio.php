<?php
	/**
	* Name:  Twilio
	*
	* Author: Ben Edmunds
	*		  ben.edmunds@gmail.com
	*         @benedmunds
	*
	* Location:
	*
	* Created:  03.29.2011
	*
	* Description:  Twilio configuration settings.
	*
	*
	*/

	/**
	 * Mode ("sandbox" or "prod")
	 **/
	$config['Twilio']['mode']   = Configure::read('TWILIO_MODE');

	/**
	 * Account SID
	 **/
	$config['Twilio']['account_sid']   = Configure::read('TWILIO_SID');

	/**
	 * Auth Token
	 **/
	$config['Twilio']['auth_token']    = Configure::read('TWILIO_AUTH_TOKEN');

	/**
	 * API Version
	 **/
	$config['Twilio']['api_version']   = '2010-04-01';

	/**
	 * Twilio Phone Number
	 **/
	$config['Twilio']['number']        = Configure::read('TWILIO_PHONE_NUMBER');


/* End of file twilio.php */