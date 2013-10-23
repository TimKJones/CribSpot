<?php
class ToursController extends AppController 
{ 
	public $helpers = array('Html');
	public $uses = array('User', 'Listing', 'Tour', 'UsersInTours');
	public $components= array('RequestHandler', 'Auth', 'Session', 'Cookie');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('RequestTourTimes');
		$this->Auth->allow('RequestGenericTour');
	}

	/*
	This is only called for a listing we manage
	Logged-in user submits array of at least 3 times for which they are available to see the listing identified by $listing_id.
	Sends an email to scheduler@cribspot.com with all necessary information
	*/
	public function RequestTourTimes()
	{
		$this->layout = 'ajax';
		if(!$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

		if (!$this->Auth->loggedIn()){
			$response = array('error' => 'User not logged in');
			$this->set('response', json_encode($response));
			return;
		}

		$listing_id = $times = $note = null;
		if ($this->request->data === null || !array_key_exists('listing_id', $this->request->data) ||
			!array_key_exists('times', $this->request->data))
			return;

		$listing_id = $this->request->data['listing_id'];
		$times = $this->request->data['times'];
		if (array_key_exists('note', $this->request->data))
			$note = $this->request->data['note'];

		foreach ($times as &$time)
			$time = date('Y-m-d H:i:s', strtotime($time['date']));

CakeLog::write('times', print_r($times, true));

		/* Save times in database */
		$response = $this->Tour->SaveTour($times, $this->Auth->User('id'));
		if (array_key_exists('error', $response)) {
			$this->set('response', json_encode($response));
			return;
		}

		/* Send email to scheduler@cribspot.com with all necessary information */
		//$this->_emailInformationToScheduler($listing_id, $times, $note);

		/* Send email to student saying their times are pending and one will be assigned. */
		//$this->_emailStudentConfirmation($listing_id, $times, $note);

		$this->set('response', json_encode($response));
	}

	/*
	This is only called for a listing we don’t manage.
	Logged-in user requests a tour for $listing_id, but doesn’t give any specific dates.
	Emails property manager giving information about the student and the request, and gives instructions for how to respond to the student.
	*/
	public function RequestGenericTour($listing_id, $note=null)
	{
		$this->layout = 'ajax';
		if(!$this->request->is('ajax') && !Configure::read('debug') > 0)
			return;

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
	private function _emailInformationToScheduler($listing_id, $times, $notes=null)
	{
		$from = 'donotreply@cribspot.com';
		$to = 'scheduler@cribspot.com';
		$id = 1;	
		$subject = 'New Tour Request: ID: ' . $id;
		$template = 'tours/tour_information_for_scheduler';
		$sendAs = 'both';

		$from = array('scheduler@cribspot.com' => 'Cribspot Tour Requests');

		/* Get user information to fill into email template */
		$loggedInUser = $this->_getLoggedInUserBasicInformation();
		$to = $this->User->GetEmailFromId($loggedInUser['id']);
		if ($to === null)
			return;

		$title = $this->Listing->GetListingTitleFromId($listing_id);
		$subject = $loggedInUser['first_name'].$loggedInUser['last_name']." Has Requested to Tour Your Property at ".$title;
		
		$template = 'tours/generic_request_to_pm';
		$sendAs = 'both';

		$this->set('student_data', $loggedInUser);
		$this->set('listing_url', 'https://www.cribspot.com/listing/'.$listing_id);
		$this->set('times_requested', '');
		$this->set('notes', '');

		$this->SendEmail($from, $to, $subject, $template, $sendAs);
	}

	/*
	Emails student with information about their submitted request and next steps to expect.
	*/
	private function _emailStudentConfirmation($listing_id, $times, $notes=null)
	{
		if (!$this->Auth->loggedIn() || $listing_id === null || $times === null)
			return;

		$from = array('scheduler@cribspot.com' => 'Cribspot Tour Requests');
		$to = $this->User->GetEmailFromId($this->Auth->User('id'));
		if ($to === null)
			return;

		$title = $this->Listing->GetListingTitleFromId($listing_id);
		$subject = 'New Tour Request: ID: ' . $id;
		$template = 'tours/student_confirmation';
		$sendAs = 'both';

		$this->set('student_name', '');
		$this->set('student_email', '');
		$this->set('student_phone', '');
		$this->set('student_university', '');
		$this->set('student_year', '');
		$this->set('listing_url', '');
		$this->set('times_requested', '');
		$this->set('notes', $notes);

		$this->SendEmail($from, $to, $subject, $template, $sendAs);
	}

	/*
	Emails PM telling them about the tour request.
	Includes link that will log them in and allow them to respond directly to message.
	$parameters is of a different form based on whether the user invited friends via email or facebook.
	$parameters['invite_type'] specifies either 'INVITE_FACEBOOK', 'INVITE_EMAIL', or 'INVITE_NONE'
	$parameters['housemates'] is:
	- if INVITE_FACEBOOK: = list of facebook_ids of housemates
	- if INVITE_EMAIL: = list of names of housemates
	- if INVITE_NONE: = null
	*/
	private function _emailGenericRequestToPM($listing_id, $parameters, $notes=null)
	{
		if (!$this->Auth->loggedIn() || $listing_id === null || $times === null)
			return;

		$from = array('scheduler@cribspot.com' => 'Cribspot Tour Requests');
		$to = $this->User->GetEmailFromId($this->Auth->User('id'));
		if ($to === null)
			return;

		/* Get user information to fill into email template */
		$title = $this->Listing->GetListingTitleFromId($listing_id);

		$loggedInUser = $this->Auth->User();
		$first_name = $loggedInUser['first_name'];
		$last_name = $loggedInUser['last_name'];
		$university = $loggedInUser['registered_university']; /* will be null if user registered before october 15ish */
		$facebook_id = $loggedInUser['facebook_id'];
		$img_url = null;
		if (!empty($facebook_id))
			$img_url = "https://graph.facebook.com/".$facebook_id."/picture?width=80&height=80";

		$subject = $first_name.$last_name." Has Requested to Tour Your Property at ".$title;
		
		$template = 'tours/generic_request_to_pm';
		$sendAs = 'both';

		$this->set('message_url', '');
		$this->set('student_first_name', $first_name);
		$this->set('student_last_name', $last_name);
		$this->set('student_university', $university);
		$this->set('listing_url', 'https://www.cribspot.com/listing/'.$listing_id);
		$this->set('notes', $notes);

		$this->SendEmail($from, $to, $subject, $template, $sendAs);
	}

	private function _getLoggedInUserBasicInformation()
	{
		$userInfo = array();
		$user = $this->Auth->User();
		if ($user === null)
			return null;

		$user['img_url'] = null;
		if (!empty($user['facebook_id']))
			$user['img_url'] = "https://graph.facebook.com/".$user['facebook_id']."/picture?width=80&height=80";
		
		return $user;
	}
}