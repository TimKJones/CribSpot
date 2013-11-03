<?php 

class EmailShell extends AppShell{
    public $uses = array('Error', 'User', 'University', 'LoginCode');

    /*
    Sends welcome email to all property managers with pm_associated_university set as a school in $university_ids

    */
    public function welcome_property_managers_by_associated_university($university_ids = array(4))
    {
        $counter = 0;
        /* Map of university_id to university object */
        $universityMap = $this->University->GetIdToUniversityMap($university_ids);
        CakeLog::write('universitymap', print_r($universityMap, true));

        /* Initialize password_reset_tokens*/
        $user_ids = $this->User->GetPMUserIdsByAssociatedUniversity($university_ids);
        CakeLog::write('user_ids', print_r($user_ids, true));
        if (!$this->LoginCode->InitializePMLoginCodes($user_ids))
            return;

        /* Get all property managers that will be emailed */
        $users = $this->User->GetPropertyManagersByAssociatedUniversity($university_ids);
        CakeLog::write('PMs', print_r($users, true));

        /*
        For each user, set university-dependent template variables, set received_welcome_email = 1,
        and send them an email
        */
        foreach ($users as $user){
            if (!array_key_exists('User', $user) || !array_key_exists('id', $user['User']))
                continue;

            if (!array_key_exists('email', $user['User']) || empty($user['User']['email']))
                continue;

            /* Don't send email if this user has already verified their email */
            if (array_key_exists('verified', $user['User']) && $user['User']['verified'] == 1)
                continue;

            CakeLog::write('nextuser', print_r($user, true));

            $this->User->ReceivedWelcomeEmail($user['User']['id']);
            if (!array_key_exists('pm_associated_university', $user['User']) || 
                !array_key_exists($user['User']['pm_associated_university'], $universityMap))
                continue;


            $usersUniversity = $universityMap[$user['User']['pm_associated_university']];
            $school_abbreviation = $usersUniversity['abbreviation'];
            $school_full_name = $usersUniversity['full_name'];
            $code = $this->LoginCode->GetCodeByUserId($user['User']['id']);
            if ($code === null)
                continue;

            $reset_password_url = "www.cribspot.com/users/PMLogin?id=".$user['User']['id']."&code=".$code;
            $templateData = array(
                'school_abbreviation' => $school_abbreviation,
                'school_full_name' => $school_full_name,
                'reset_password_url' => $reset_password_url
            );

            CakeLog::write('templatedata', print_r($templateData, true));

            if ((Configure::read('EMAIL_DEBUG_MODE') === 'DEBUG' && $counter < 2) ||
                Configure::read('EMAIL_DEBUG_MODE') === 'PRODUCTION') {
                if (Configure::read('EMAIL_DEBUG_MODE') === 'DEBUG')
                    $user['User']['email'] = 'tjones4413@gmail.com';


                $from = array('jason@cribspot.com' => 'Cribspot Founder');
                $subject = "Welcome to Cribspot at the " . $school_full_name . "!";
                $template = 'WelcomePropertyManagers';
                $this->_emailUser($user['User']['email'], $subject, $template, $templateData, $from);
            }

            $counter ++;
        }
    }

    public function email_error_report(){
        // Set timezone
        date_default_timezone_set('UTC');
        // Start date
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($today)));
        $errors = $this->Error->find('all', array(
            'conditions' => array('Error.created >=' => $yesterday)
        ));

        $template_data = array("errors"=>$errors);
        $month = date('F');
        $day = date('j') - 1;
        $year = date('Y');
        $subject = "Error Report for " . $month.' '.$day.', '.$year;
        $recipient = Configure::read('ERROR_REPORT_RECIPIENT');
        $this->_emailUser($recipient, $subject, "errors", $template_data);  
    }

    public function email_daily_user_report(){
        // Set timezone
        date_default_timezone_set('UTC');
        // Start date
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($today)));
        $users = $this->User->find('all', array(
            'conditions' => array('User.created >=' => $yesterday)
        ));

        foreach ($users as &$user){
            $user['User']['registered_university'] = $this->University->getNameFromId($user['User']['registered_university']);
            $user['User']['student_year'] = $this->User->year($user['User']['student_year']);
        }

        $template_data = array("users"=>$users);
        $month = date('F');
        $day = date('j') - 1;
        $year = date('Y');
        $subject = "New User Report for " . $month.' '.$day.', '.$year;
        $recipients = Configure::read('USER_REPORT_RECIPIENTS');
        foreach ($recipients as $recipient){
            $this->_emailUser($recipient, $subject, "users", $template_data);  
        }
    }

    private function _EmailWelcomedPropertyManagers($person, $tempateData)
    {
        $from = 'Cribspot Founder<alex@cribspot.com>';
        $to = $person['email'];
        $subject = "Welcome to Cribspot at" . $school_full_name . "!";
        $template = 'WelcomePropertyManagers';
        $sendAs = 'both';
        $this->set('reset_password_url', "www.cribspot.com/users/ResetPasswordRedirect?id=".$person['id'] . 
            "&reset_token=".$person['password_reset_token']);
        $this->SendEmail($from, $to, $subject, $template, $sendAs);
    }
}

?>