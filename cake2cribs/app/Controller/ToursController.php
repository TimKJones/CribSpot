<?php
class ToursController extends AppController 
{ 
	public $helpers = array('Html');
	public $uses = array('User', 'Listing', 'TourInvitation', 'Tour', 'UsersInTours', 'TourRequest', 'University');
	public $components= array('RequestHandler', 'Auth', 'Session', 'Cookie');

	public function beforeFilter()
	{
		parent::beforeFilter();
		//$this->Auth->allow('RequestTourTimes');
		$this->Auth->allow('ConfirmTour');
		$this->Auth->allow('Schedule');
	}

	public function Schedule($listing_id)
	{
		$directive['schedule'] = true;
        $this->Cookie->write('fullpage-directive', json_encode($directive));
        $this->redirect(array('controller' => 'listings', 'action' => 'view', $listing_id));			
	}

	/*
	Logged-in user submits array of at least 6 times for which they are available to see the listing identified by $listing_id.
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
		if (array_key_exists('notes', $this->request->data))
			$note = $this->request->data['notes'];

		$processedTimes = array();
		foreach ($times as &$time){
			/* Request times in 30-minute blocks during the student's stated availablility window */
			$date = strtotime($time['date']);
			array_push($processedTimes, date('Y-m-d H:i:s', strtotime($time['date'])));
			array_push($processedTimes, date('Y-m-d H:i:s',strtotime($time['date'])+30*60));
		}

		/* Save times in database */
		$response = $this->TourRequest->SaveTour($processedTimes, $this->Auth->User('id'), $listing_id);
		if (array_key_exists('error', $response)) {
			$this->set('response', json_encode($response));
			return;
		}

		/* Get property manager and listing data */
		$listing = $this->Listing->findByListingId($listing_id);
		if (!array_key_exists('Rental', $listing) || !array_key_exists('User', $listing)){
			/* Critical data is missing - don't schedule a tour */
			$response = array('error' => "Something went wrong trying to schedule your tour... ".
				"but we want to help! Chat with us by clicking the 'Chat with Cribspot!' button ".
				"at the bottom left of your screen.");
			$this->set('response', json_encode($response));
			return;
		}

		$rental = $listing['Rental'];
		$pm = $listing['User'];

		/* Send email to property manager with all necessary information */
		$this->_createMessageToPropertyManager($listing_id, $response['success'], $note);

		/* Send email to student saying their times are pending and one will be assigned. */
		//$this->_emailStudentConfirmation($listing_id, $response['success'], $note);

		/* Handle invitations to housemates */
		$invitationsSuccess = array('success' => '');
		if (array_key_exists('housemates', $this->request->data) && count($this->request->data['housemates']) > 0) {
			$housemates = $this->request->data['housemates'];
			$tour_request = null;
			CakeLog::write('tourrequestid', print_r($response, true));
			if (array_key_exists('TourRequest', $response['success']))
				$tour_request = $response['success']['TourRequest'];

			App::import('model', 'TourInvitation');
			$TourInvitation = new TourInvitation();
			$invitationsSuccess = $TourInvitation->InviteHousematesToTour($this->Auth->User('id'), $housemates, $tour_request);
			//$this->_emailInvitationToHousemates($listing_id, $housemates);
		}

		if (array_key_exists('error', $invitationsSuccess))
			$response = $invitationsSuccess;
		else
			$response['success'] = '';

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
	URL inserted into Cribspot Tour Scheduler email to confirm a specific tour
	*/
	public function ConfirmTour()
	{
		if (!array_key_exists('id', $this->request->query) || !array_key_exists('code', $this->request->query))
            $this->redirect('/');

        $id = $this->request->query['id'];
        $code = $this->request->query['code'];

        /* Confirm the tour specified, if the credentials are correct */
        $success = $this->Tour->ConfirmTour($id, $code);

        /* Email student and his invited housemates */
        $this->_emailStudentAfterTourConfirmed($id);

        if (!$success){
            $this->redirect('/');
        }
        else{
        	$this->set('result', 'Tour Confirmed!');
        }
	}


/* ----------------------------------------- private ------------------------------------ */

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

		/* Get user information to fill into email template */
		$loggedInUser = $this->_getLoggedInUserBasicInformation();
		/*$to = $this->User->GetEmailFromId($loggedInUser['id']);
		if ($to === null)
			return;*/

		$title = $this->Listing->GetListingTitleFromId($listing_id);
		$name = $title['name'];
		$unit_description = $title['description'];
		$subject = "Tour Request from ".$loggedInUser['first_name']." ".$loggedInUser['last_name']." (id: ".
			$loggedInUser['id'].") "."about ".$name.' - '.$unit_description;
		
		$template = 'tours/tour_information_for_scheduler';
		$sendAs = 'both';

		/* Convert data from numeric constants to their string values */
		if (!empty($loggedInUser['registered_university']))
			$loggedInUser['registered_university'] = $this->University->getNameFromId($loggedInUser['registered_university']);

		if (!empty($loggedInUser['student_year']))
			$loggedInUser['student_year'] = $this->User->year($loggedInUser['student_year']);

		$pm = $this->Listing->GetPMByListingId($listing_id);
		$pm_data = array(
			'id' => $pm['id'],
			'company_name' => $pm['company_name'],
			'email'=>$pm['email'],
			'phone'=>$pm['phone']
		);

		/* Process tour request times */
		$tourTimes = $times['Tour'];
		$tourData = array();
		foreach ($tourTimes as $tourTime){
			$confirm_link = 'https://www.cribspot.com/Tours/ConfirmTour?id='.$tourTime['id'].'&code='.$tourTime['confirmation_code'];
			$time = $tourTime['date'];
			array_push($tourData, array(
				'confirm_link' => $confirm_link,
				'time' => $time
			));
		}

		$this->set('student_data', $loggedInUser);
		$this->set('pm_data', $pm_data);
		$this->set('listing_url', 'https://www.cribspot.com/listing/'.$listing_id);
		$this->set('tour_data', $tourData);
		$this->set('notes', $notes);
		$this->SendEmail($from, $to, $subject, $template, $sendAs);
	}

	/*
	Emails all information necessary to coordinate tour scheduling to the Cribspot 
	admin managing tours.
	*/
	private function _createMessageToPropertyManager($listing_id, $times, $notes=null)
	{
		$from = 'info@cribspot.com';
		$to = 'scheduler@cribspot.com';
		$id = 1;	
		$subject = 'New Tour Request: ID: ' . $id;
		$template = 'tours/tour_information_for_scheduler';
		$sendAs = 'both';

		/* Get user information to fill into email template */
		$loggedInUser = $this->_getLoggedInUserBasicInformation();
		/*$to = $this->User->GetEmailFromId($loggedInUser['id']);
		if ($to === null)
			return;*/

		$title = $this->Listing->GetListingTitleFromId($listing_id);
		$name = $title['name'];
		$unit_description = $title['description'];
		$subject = "Tour Request from ".$loggedInUser['first_name']." ".$loggedInUser['last_name']." (id: ".
			$loggedInUser['id'].") "."about ".$name.' - '.$unit_description;
		
		$template = 'tours/tour_information_for_scheduler';
		$sendAs = 'both';

		/* Convert data from numeric constants to their string values */
		if (!empty($loggedInUser['registered_university']))
			$loggedInUser['registered_university'] = $this->University->getNameFromId($loggedInUser['registered_university']);

		if (!empty($loggedInUser['student_year']))
			$loggedInUser['student_year'] = $this->User->year($loggedInUser['student_year']);

		$pm = $this->Listing->GetPMByListingId($listing_id);
		$pm_data = array(
			'id' => $pm['id'],
			'company_name' => $pm['company_name'],
			'email'=>$pm['email'],
			'phone'=>$pm['phone']
		);

		/* Process tour request times */
		$tourTimes = $times['Tour'];
		$tourData = array();
		foreach ($tourTimes as $tourTime){
			$confirm_link = 'https://www.cribspot.com/Tours/ConfirmTour?id='.$tourTime['id'].'&code='.$tourTime['confirmation_code'];
			$time = $tourTime['date'];
			array_push($tourData, array(
				'confirm_link' => $confirm_link,
				'time' => $time
			));
		}

		$this->set('student_data', $loggedInUser);
		$this->set('pm_data', $pm_data);
		$this->set('listing_url', 'https://www.cribspot.com/listing/'.$listing_id);
		$this->set('tour_data', $tourData);
		$this->set('notes', $notes);
		$this->SendEmail($from, $to, $subject, $template, $sendAs);
	}

	/*
	Emails student with information about their submitted request and next steps to expect.
	*/
	private function _emailStudentConfirmation($listing_id, $times, $notes=null)
	{
		if (!$this->Auth->loggedIn() || $listing_id === null || $times === null)
			return;

		$from = 'Cribspot Tour Requests<scheduler@cribspot.com>';
		$to = $this->User->GetEmailFromId($this->Auth->User('id'));
		if ($to === null)
			return;

		$title = $this->Listing->GetListingTitleFromId($listing_id);
		$subject = "Your Cribspot Tour Request for " . $title['name'] . " Has Been Received";
		$template = 'tours/student_confirmation';
		$sendAs = 'both';

		$loggedInUser = $this->_getLoggedInUserBasicInformation();

		/* Convert data from numeric constants to their string values */
		if (!empty($loggedInUser['registered_university']))
			$loggedInUser['registered_university'] = $this->University->getNameFromId($loggedInUser['registered_university']);

		if (!empty($loggedInUser['student_year']))
			$loggedInUser['student_year'] = $this->User->year($loggedInUser['student_year']);

		/* Process tour request times */
		$tourTimes = $times['Tour'];
		/* 
		Only take every other tour time, starting with the first. We added extra times at each half hour that 
		we will ignore for this purpose
		*/
		$tourData = array();
		$skipEveryOther = false;
		foreach ($tourTimes as $tourTime){
			$skipEveryOther = !$skipEveryOther;
			if ($skipEveryOther)
				continue;

			$confirm_link = 'https://www.cribspot.com/Tours/ConfirmTour?id='.$tourTime['id'].'&code='.$tourTime['confirmation_code'];
			$time = $tourTime['date'];
			/* format the time in a more human readable form */
			$month = date('F', strtotime($time));
	        $day = date('j', strtotime($time));
	        $year = date('Y', strtotime($time));

	        $first_hour = $second_hour = null;
	        $first_hour_24 = date('G', strtotime($time));
	        $second_hour_24 = $first_hour_24 + 1;
	        $first_hour_12 = date('g', strtotime($time));
	        $second_hour_12 = ($first_hour_12 + 1) % 12;
	        if ($second_hour_12 === 0)
	        	$second_hour_12 = 12;
	        $am_pm_options = array('AM', 'PM');
	        $first_hour_am_pm = $am_pm_options[($first_hour_24 >= 12)];
	        $second_hour_am_pm = $am_pm_options[($second_hour_24 >= 12)];

	        $hourRange = $first_hour_12.' '.$first_hour_am_pm.' - '.$second_hour_12.' '.$second_hour_am_pm;
	       	$time = $month.' '.$day.': '.$hourRange;
			array_push($tourData, array(
				'confirm_link' => $confirm_link,
				'time' => $time
			));
		}

		$this->set('student_data', $loggedInUser);
		$this->set('listing_url', 'https://www.cribspot.com/listing/'.$listing_id);
		$this->set('tour_data', $tourData);
		$this->set('building_name', $title['name']);
		$this->set('notes', $notes);

		$this->SendEmail($from, $to, $subject, $template, $sendAs);
	}

	/*
	Emails PM telling them about the tour request.
	$housemates is a list of objects of the form (name, email)
	*/
	private function _emailGenericRequestToPM($listing_id, $housemates, $notes=null)
	{
		if (!$this->Auth->loggedIn() || $listing_id === null || $times === null)
			return;

		$from = 'Cribspot Tour Requests<scheduler@cribspot.com>';
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

	/*
	Sends an invitation to $emails inviting them to join Cribspot and tour $listing_id
	*/	
	private function _emailInvitationToHousemates($listing_id, $housemates=null)
	{
		if (!$this->Auth->loggedIn() || $listing_id === null)
			return;

		$loggedInUser = $this->_getLoggedInUserBasicInformation();
		$from = 'Cribspot Tour Requests<scheduler@cribspot.com>';
		foreach ($housemates as $housemate){
			if (!array_key_exists('email', $housemate))
				continue;

			$to = $housemate['email'];
			if ($to === null)
				return;

			$title = $this->Listing->GetListingTitleFromId($listing_id);
			$subject = $loggedInUser['first_name'].' '.$loggedInUser['last_name'].' wants you to join a tour at '.
				$title['name'];
			$template = 'tours/housemate_invitation';
			$sendAs = 'both';

			$this->set('logged_in_first_name', $loggedInUser['first_name']);
			$this->set('logged_in_last_name', $loggedInUser['last_name']);
			$this->set('housemate_name', $housemate['name']);
			$this->set('listing_url', 'https://www.cribspot.com/listing/'.$listing_id);
			$this->set('building_name', $title['name']);

			$this->SendEmail($from, $to, $subject, $template, $sendAs);
		}

	}

	/*
	Emails student and their housemates that a time has been confirmed for $tour_id
	*/	
	private function _emailStudentAfterTourConfirmed($tour_id)
	{
		/* Get housemate data from previous invitations */
		$tour_request = $this->Tour->GetTourRequestFromTourId($tour_id);
		$recipients = $this->TourInvitation->GetPeopleForConfirmationEmail($tour_request['tour_request_id']);

		/* add the user that initiated the request */
		CakeLog::write('why', print_r($tour_request, true));
		$tour_initiator = $this->User->get($tour_request['user_id']);
		CakeLog::write('initatior', print_r($tour_initiator, true));
		array_push($recipients, array(
			'name' => $tour_initiator['User']['first_name'].' '.$tour_initiator['User']['last_name'],
			'email' => $tour_initiator['User']['email']
		));
		$title = $this->Listing->GetListingTitleFromId($tour_request['listing_id']);
		$from = 'Cribspot Tour Requests<scheduler@cribspot.com>';
		foreach ($recipients as $recipient){
			if (!array_key_exists('email', $recipient))
				continue;

			$to = $recipient['email'];
			if (empty($to))
				return;

			$title = $this->Listing->GetListingTitleFromId($tour_request['listing_id']);
			/* Format tour time */
			$time = $tour_request['tour']['date'];
			/* format the time in a more human readable form */
			$month = date('F', strtotime($time));
	        $day = date('j', strtotime($time));
	        $year = date('Y', strtotime($time));
	        $hour_24 = date('G', strtotime($time));
	        $hour_12 = date('g', strtotime($time));
	        $minutes = date('i', strtotime($time));
	        $am_pm_options = array('AM', 'PM');
	        $am_pm = $am_pm_options[($hour_24 >= 12)];
	       	$time = $month.' '.$day.': '.$hour_12.':'.$minutes.$am_pm;
			$subject = "Your tour at ".$title['name']." has been confirmed";
			$template = 'tours/tour_time_confirmed';
			$sendAs = 'both';

			$this->set('name', $recipient['name']);
			$this->set('tour_time', $time);
			$this->set('listing_url', 'https://www.cribspot.com/listing/'.$tour_request['listing_id']);
			$this->set('building_name', $title['name']);

			$this->SendEmail($from, $to, $subject, $template, $sendAs);
		}
	}

	private function _getLoggedInUserBasicInformation()
	{
		$user = $this->User->get($this->Auth->User('id'));
		if ($user === null)
			return null;

		$user = $user['User'];
CakeLog::write('usersdata', print_r($user, true));
		$user['img_url'] = null;
		if (!empty($user['facebook_id']))
			$user['img_url'] = "https://graph.facebook.com/".$user['facebook_id']."/picture?width=80&height=80";
		
		return $user;
	}
}