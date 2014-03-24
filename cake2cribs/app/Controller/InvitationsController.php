<?php
class InvitationsController extends AppController {
    public $helpers = array('Html');
    public $components = array('Auth');
    public $uses = array('EmailInvitation', 'User', 'Listing');
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function InviteFriends()
    {
        $this->layout = 'ajax';

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

    /*
    Invites a friend from facebook.
    The inviting does not happen directly (so no emails are sent), rather through the frontend FB API.
    This method simply adds the user associated with this facebook ID to the current user's hotlist,
    and creates a pre-registered user if it does not exist already.
    */
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
            $subject = '';
            $template = 'email_invitation';
            CakeLog::write('invitations', 'inviting ' . $email);

            if ($email == $loggedInUser['email']) {
                continue;
            }

            if ($this->User->hasAny(array('User.email' => $email))) {
                // email exists already. Add the user to the logged-in user's hotlist

                $friend = $this->User->find('first', array('User.email' => $email));

                CakeLog::write('invitations', 'user exists');
                // add user to hotlist
                $this->User->addToHotlist($loggedInUser['id'], $email);

                // set email template to one explaining the addition
                $subject = 'Join my housing group at Cribspot!';
                $template = 'email_invitation';
            }
            else {
                // Email does not exist. Create pre-registered user that we can add to the hotlist
                // do not use validations here, because we cannot provide a name yet.
                $friend = $this->User->RegisterUser(array('email' => $email), false);

                CakeLog::write('invitations', print_r($friend, true));

                if ($friend['success']) {
                    // add the newly created user to hotlist
                    $this->User->addToHotlist($loggedInUser['id'], $email);

                    // craft an invite link to display in the email.
                    // We are repurposing the 'forgot password' mechanism here to keep from writing new code.
                    // This is a bit of a hack.
                    $friend_id = $friend['success']['User']['id'];
                    $token = $this->User->setPasswordResetToken($friend_id);
                    $this->set('password_reset_token', $token['password_reset_token']);
                    $this->set('id', $friend_id);
                    if (isset($token)) {
                        CakeLog::write('invitations', 'set password reset token: ' . $token['password_reset_token']);
                    }

                    // subject has text more conducive to onboarding new users
                    $subject = 'Join my housing group on Cribspot! Off-campus housing made simple';
                    $template = 'email_invitation';
                }
                else {
                    CakeLog::write('invitations', 'there was an error with the user creation' . print_r($friend['success'], true));
                }
            }

            CakeLog::write("HOTLIST", print_r($friend, true));

            // if a listing is provided, then we are sharing a listing instead of just inviting a user.
            if (!is_null($listing)) {
                $listing_obj = $this->Listing->GetListing($listing);
                if(isset($listing_obj[0]['Image'][0]['image_path'])) {
                    // make the image in the email that of the listing, if it has one. 
                    // Otherwise it will be the user's profile image that was set at the beginning of the function.
                    $img_url = 'http://www.cribspot.com/' . $listing_obj[0]['Image'][0]['image_path'];
                }
                if(!is_null($listing_obj)) {
                    $listing_name = $listing_obj[0]['Marker']['street_address'];
                    $subject = "$first_name  $last_name wants you to check out $listing_name on Cribspot!";
                }
                else {
                    $subject = "$first_name  $last_name wants you to check out a property on Cribspot!";
                }
                CakeLog::write("HOTLIST", print_r($listing_obj, true));
                $template = 'share_listing';

                $this->set('listing_name', $listing_name);
                $this->set('listing', $listing);
            }

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
