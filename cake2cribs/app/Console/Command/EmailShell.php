<?php 

class EmailShell extends AppShell{
    public $uses = array('Error', 'User');

    // Fetch all the listings that will be featured over the next 3 days
    // and email them out to people.


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
}

?>