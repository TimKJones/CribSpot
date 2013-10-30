<?php
class InvitationsController extends AppController {
    public $helpers = array('Html');
    public $components = array('Auth');
    public $uses = array('EmailInvitation');
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function InviteFriends()
    {
        if (!$this->request->is('ajax') && !Configure::read('debug') > 0)
                return;

        if ($this->request->data === null || !array_key_exists('emails', $this->request->data))
            return;

        $emails = $this->request->data['emails'];
        CakeLog::write('emails', print_r($emails, true));

        if ($this->Auth->loggedIn()){
            $this->EmailInvitation->InviteFriends($this->Auth->User('id'), $emails);
            $this->_sendEmailsToInvitees($emails);
        }

        $this->set('response', json_encode(array('success'=>'')));
    }

    /*
    Send emails to the friends invited to join Cribspot after a $user signed up
    */
    private function _sendEmailsToInvitees($emails)
    {
        $loggedInUser = $this->Auth->User();
        $first_name = $loggedInUser['first_name'];
        $last_name = $loggedInUser['last_name'];
        foreach ($emails as $email){
            $this->set('inviter_first_name', $first_name);
            $this->set('inviter_last_name', $last_name);
            $from = $first_name.' '.$last_name.'<info@cribspot.com>';
            $to = $email;    
            $subject = 'Join me on Cribspot! Off-campus housing made simple.'
            $template = 'tours/tour_information_for_scheduler';
            $sendAs = 'both';
            $this->SendEmail($from, $to, $subject, $template, $sendAs);
        }
    }
}