<?php
class InvitationsController extends AppController {
    public $helpers = array('Html');
    public $components = array('Auth');
    public $uses = array('EmailInvitation', 'User');
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function InviteFriends()
    {
        $this->layout = 'ajax';
        CakeLog::write('emails', 'hello');

        if (!$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        if ($this->request->data === null || !array_key_exists('emails', $this->request->data))
            return;

        $emails = $this->request->data['emails'];
        CakeLog::write('emails', print_r($emails, true));

        $response = null;
        if ($this->Auth->loggedIn()){
            $response = $this->EmailInvitation->InviteFriends($this->Auth->User('id'), $emails);
            if (array_key_exists('error', $response)){
                $this->set('response', json_encode($response));
                return;
            }

            if (array_key_exists('listing', $this->request->data)) {
                $listing = $this->request->data['listing'];
            }
            else {
                $listing = null;
            }
            
            $this->_sendEmailsToInvitees($emails, $listing);
        }

        $this->set('response', json_encode(array('success'=>true)));
    }

    public function InviteFBFriend()
    {
        $this->layout = 'ajax';

        if (!$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        if ($this->request->data === null || !array_key_exists('friend', $this->request->data))
            return;

        $friend = $this->request->data['friend'];
        CakeLog::write('emails', 'inviting facebook friend: ' . $friend);

        if($this->Auth->loggedIn()) {
            $loggedInUser = $this->Auth->User();

            $f = $this->User->GetUserFromFacebookId($friend['facebook_id']);

            if(!is_null($f) && !empty($f)) {
                Cakelog::write("HOTLIST", "got user from facebook id" . print_r($f, true));
                $this->User->addToHotlistFB($loggedInUser['id'], $f['User']['facebook_id']);
                $this->set('response', json_encode(array('success' => true)));
                return;
            }
            else {
                Cakelog::write("HOTLIST", "creating new user" . print_r($friend, true));
                $new_user = array('User' => $friend); 
                $new_user['User']['verified'] = 1;
                $new_user['User']['user_type'] = User::USER_TYPE_SUBLETTER;
                $new_user['User']['password'] = uniqid();

                $friendObj = $this->User->SaveFacebookUser($new_user);
                Cakelog::write("HOTLIST", 'friendObj: ' . print_r($friendObj, true));
                if (array_key_exists('error', $friendObj)) {
                    $this->set('response', json_encode(array('success' => false, 'error' => $friendObj['error'])));
                    return;
                }
                else {
                    $this->User->addToHotlistFB($loggedInUser['id'], $friendObj['user']['User']['facebook_id']);
                    $this->set('response', json_encode(array('success' => true)));
                    return;
                }
            }
        }
    }

    /*
    Send emails to the friends invited to join Cribspot after a $user signed up
    */
    private function _sendEmailsToInvitees($emails, $listing = null)
    {
        $loggedInUser = $this->Auth->User();
        $first_name = $loggedInUser['first_name'];
        $last_name = $loggedInUser['last_name'];
        $img_url = null;
        if (!empty($loggedInUser['facebook_id']))
            $img_url = "https://graph.facebook.com/".$loggedInUser['facebook_id']."/picture?width=140&height=140";

        foreach ($emails as $email){
            // if email exists already
            $subject = '';
            $template = 'email_invitation';
            CakeLog::write('invitations', 'inviting ' . $email);

            if ($this->User->hasAny(array('User.email' => $email))) {
                $friend = $this->User->find('first', array('User.email' => $email));

                CakeLog::write('invitations', 'user exists');
                // add user to hotlist
                $this->User->addToHotlist($loggedInUser['id'], $email);

                // set email template to one explaining the addition
                $subject = 'Join my group at Cribspot!';
                $template = 'email_invitation';
            }
            else {
                // create pre-registered user
                $friend = $this->User->RegisterUser(array('email' => $email), false);
                CakeLog::write('invitations', print_r($friend, true));
                if ($friend['success']) {
                    // add new user to hotlist
                    $this->User->addToHotlist($loggedInUser['id'], $email);

                    // send invitation with crafted invite link
                    $friend_id = $friend['success']['User']['id'];
                    $token = $this->User->setPasswordResetToken($friend_id);
                    $this->set('password_reset_token', $token['password_reset_token']);
                    $this->set('id', $friend_id);
                    if (isset($token)) {
                        CakeLog::write('invitations', 'set password reset token: ' . $token['password_reset_token']);
                    }
                    $subject = 'Join my group on Cribspot! Off-campus housing made simple';
                    $template = 'email_invitation';
                }
                else {
                    CakeLog::write('invitations', 'there was an error with the user creation' . print_r($friend['success'], true));
                }
            }

            CakeLog::write("HOTLIST", print_r($friend, true));

            if (!is_null($listing) && $this->User->inHotlist($loggedInUser['id'], $friend['User']['id'])) {
                $subject = $first_name . ' ' . $last_name . ' has shared a listing with you.';
                $template = 'email_invitation';
            }

            $this->set('listing', $listing);
            $this->set('inviter_first_name', $first_name);
            $this->set('inviter_last_name', $last_name);
            $this->set('img_url', $img_url);
            $from = $first_name.' '.$last_name.'<info@cribspot.com>';
            $to = $email;    
            $sendAs = 'both';
            $this->SendEmail($from, $to, $subject, $template, $sendAs);
        }
    }
}