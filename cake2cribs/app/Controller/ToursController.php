<?php
class ToursController extends AppController 
{ 
	public $helpers = array('Html');
	public $uses = array();
	public $components= array('RequestHandler', 'Auth', 'Session', 'Cookie');

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	/*
	This is only called for a listing we manage
	Logged-in user submits array of at least 3 times for which they are available to see the listing identified by $listing_id.
	Sends an email to scheduler@cribspot.com with all necessary information
	*/
	public function RequestTourTimes($listing_id, $times, $note=null)
	{
/*
Send email to scheduler@cribspot.com with all necessary information
Send email to student saying their times are pending and one will be assigned.
*/
		$response = array(
			'SUCCESS' => true,
		);

		$this->set('response', $response);
	}

	/*
	This is only called for a listing we don’t manage.
	Logged-in user requests a tour for $listing_id, but doesn’t give any specific dates.
	Emails property manager giving information about the student and the request, and gives instructions for how to respond to the student.
	*/
	public function RequestGenericTour($listing_id, $note=null)
	{
		$response = array(
			'SUCCESS' => true,
		);
		$this->set('response', $response);
	}
}