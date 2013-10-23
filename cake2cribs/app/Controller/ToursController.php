<?php
class ToursController extends AppController 
{ 
	public $helpers = array('Html');
	public $uses = array('User', 'Listing');
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
Schedule confirmation text to be sent 1 hour before tour.
*/
		$this->_emailInformationToScheduler($listing_id, $times, $note);
		$this->_emailStudentConfirmation($listing_id, $times, $note);

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
		$this->_emailGenericRequestToPM($listing_id, $note);

		$response = array(
			'SUCCESS' => true,
		);
		$this->set('response', $response);
	}

	/*
	Emails all information necessary to coordinate tour scheduling to the Cribspot 
	admin managing tours.
	*/
	private function _emailTourInformationToScheduler($listing_id, $times, $note)
	{
		$from = 'donotreply@cribspot.com';
		$to = 'scheduler@cribspot.com';
		$id = 1;	
		$subject = 'New Tour Request: ID: ' . $id;
		$template = 'tours/tour_information_for_scheduler';
		$sendAs = 'both';

		$this->set('student_name', '');
		$this->set('student_email', '');
		$this->set('student_phone', '');
		$this->set('student_university', '');
		$this->set('student_year', '');
		$this->set('listing_url', '');
		$this->set('times_requested', '');
		$this->set('notes', '');

		$this->SendEmail($from, $to, $subject, $template, $sendAs);
	}

	/*
	Emails student with information about their submitted request and next steps to expect.
	*/
	private function _emailStudentConfirmation($listing_id, $times, $note)
	{
		if (!$this->Auth->loggedIn() || $listing_id === null || $times === null)
			return;

		$from = 'scheduler@cribspot.com';
		$to = $this->User->GetEmailFromId($this->Auth->User('id'));
		if ($to === null)
			return;

		$title = $this->Listing->GetListingTitleFromId($listing_id);
		$subject = 'New Tour Request: ID: ' . $id;
		$template = 'tours/tour_information_for_scheduler';
		$sendAs = 'both';

		$this->set('student_name', '');
		$this->set('student_email', '');
		$this->set('student_phone', '');
		$this->set('student_university', '');
		$this->set('student_year', '');
		$this->set('listing_url', '');
		$this->set('times_requested', '');
		$this->set('notes', '');

		$this->SendEmail($from, $to, $subject, $template, $sendAs);
	}

	/*
	Emails PM telling them about the tour request.
	Includes link that will log them in and allow them to respond directly to message.
	*/
	private function _emailGenericRequestToPM($listing_id, $note)
	{

	}

}