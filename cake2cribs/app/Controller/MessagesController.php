<?php

 class MessagesController extends AppController {
    public $helpers = array('Html');
    public $uses = array('Message', 'Conversation', 'User', 'UnreadMessage', 'University', 'Listing', 'Rental', 'LoginCode',
        'CribspotAdmin');
    public $components= array('Session','Auth','Email', 'Cookie');

    function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('contact');
        $this->Auth->allow('messageSublet');
    }

    //Shows the base messages page
    public function index($conv_id=null){
        $directive['classname'] = 'messages';
        $json = json_encode($directive);
        $this->Cookie->write('dashboard-directive', $json);
        $this->redirect('/dashboard');
    }

    /*
    Returns all data for PM dashboard used by Cribspot admins
    */
    public function GetPMAdminDashboard()
    {
        if ($this->CribspotAdmin->GetByUserId($this->Auth->User('id')) === null)
            return;

        $response = array(
            'pm_name' => '',
            'pm_email' => '',
            'pm_phone' => '',
            'unit_description',
            'student_name' => '',
            'student_email' => '',
            'date_sent_by_student' => '', /* sorted by default */
            'pm_response_date' => '',
            'pm_verified' => ''
        );

        $conversations = $this->Conversation->getAllConversations();
        $response_dates = $this->Message->getConversationIdToPMResponseDateMap($conversations);
        foreach ($conversations as $conversation){
            
        }

        /* Get all listing data */
        //$listings = $this->Listing->

        CakeLog::write("allmessages", print_r($messages, true));
        $this->set('response', json_encode($messages));
    }

    //Redirects to the dashboard automatically opening the specified
    //conversation
    public function view($conv_id){
        $user = $this->Auth->User();
        if(!$this->Conversation->isUserParticipant($conv_id, $this->Auth->User())){
            throw new NotFoundException();
        }
        $options = array();
        $options['conditions'] = array('Conversation.conversation_id'=>$conv_id);
        $conversation = $this->Conversation->find('first', $options);


        if($conversation['Participant1']['id'] == $user['id']){
            $participant_id = $conversation['Participant2']['id'];
        }else if($conversation['Participant2']['id'] == $user['id']){
            $participant_id = $conversation['Participant1']['id'];
        }else{
            throw new NotFoundException();    
        }

        $directive['classname'] = 'messages';
        $directive['data'] = array(
            'conversation_id'=>$conv_id,
            'participant_id'=>$participant_id,
            'title' => $conversation['Conversation']['title']
            );

        $json = json_encode($directive);
        $this->Cookie->write('dashboard-directive', $json);
        $this->redirect('/dashboard');
    }

    public function contact($listing_id)
    {
        $directive['contact_owner'] = true;
        $this->Cookie->write('fullpage-directive', json_encode($directive));
        $this->redirect(array('controller' => 'listings', 'action' => 'view', $listing_id));
    }

    // //Create a new conversation and the first message thats in the conversation
    // public function newConversation(){
    //  if(!$this->request->isPost()){
        //  echo "This url only accepts post requests";
    //      die();
    //  }
    //  $data = $this->request->data;
        // $conv_id = $this->Conversation->createConversation($data, $this->Auth->User());
        // $msg_id = $this->Message->createMessage($data['message_body'], $conv_id, $this->Auth->User());
    //  $this->redirect(array('controller' => 'messages', 'action' => 'index'));
    // }


    //Creates a new message in a specified conversation
    public function newMessage(){
        if(!$this->request->isPost()){
           // echo "This url only accepts post requests";
            //die();
        }
        $data = $this->request->data;
        $user = $this->Auth->User();
        //Create send reply
        $msg_id = $this->Message->createMessage($data['message_text'], $data['conversation_id'], $user);
        $message = $this->Message->read();
        $options['conditions'] = array('Conversation.conversation_id'=>$message['Message']['conversation_id']);
        $conversation = $this->Conversation->find('first', $options);
        $participant = $this->Conversation->getOtherParticipant($conversation, $this->Auth->User());
        if ($participant == null) {
            $json = json_encode(array('success'=>false));
        }else{
            $this->emailUserAboutMessage($participant, $user, $conversation, $data['message_text']);
            $json = json_encode(array('success'=>$msg_id > 0)); 
        }
        
        $this->layout = 'ajax';
        $this->set('response', $json);


    }

    //Ajax function to get all the conversations the user has going on
    public function getConversations(){
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
        $user = $this->Auth->User();
        $only_unread = 0;
        if(array_key_exists('only_unread', $this->request->query)){
            $only_unread = intval($this->request->query['only_unread']);
        }
        $conversations = $this->Conversation->getConversations($user['id'], ($only_unread==1));

        /* FIX: $con */
        $formattedConvos = array();
        foreach ($conversations as $convo)
            array_push($formattedConvos, $convo);
        
        $this->layout = 'ajax';
        $json = json_encode($formattedConvos);
        $this->set('response', $json);
    }

    // Ajax function to get a json response with the number of unread messages and conversations a user has
    public function getUnreadCount(){
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
        $user = $this->Auth->User();
        $unread_messages = $this->UnreadMessage->getUnreadMessagesCount($user);
        $unread_conversations = $this->UnreadMessage->getUnreadConversationCount($user);
        $json = json_encode(array(
                'unread_messages' => $unread_messages,
                'unread_conversations' => $unread_conversations,
            ));
        $this->layout = 'ajax';
        $this->set('response', $json);
    }

    // Ajax function to get all the messages in a conversation
    public function getMessages($conv_id, $page=1){
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $this->layout = 'ajax';
        $user = $this->Auth->User();
        $messages = null;
        $limit = 15;
        if($this->Conversation->isUserParticipant($conv_id, $user)){
            
            $this->paginate = array(
                'conditions' => array('Message.conversation_id' => $conv_id),
                'page' => $page,
                'limit' => $limit,
                'order' => array('Message.created' => 'desc'),
            );
            $messages = $this->paginate('Message');
            // die(debug($messages));
            if(empty($messages)){
                $response = array('error' => 'NO_MESSAGES_FOUND');
                $this->set('error', $response);
                return;
            }
            if($page == 1){
                // Clear the unread messages since the user just fetched the most recent 
                // messages of the conversation
                $unreadmessages = ClassRegistry::init('UnreadMessage');
                $unreadmessages->clearUnread($conv_id, $user);  
            }
        }

        $count = ($page-1) * $limit;
        
        $this->set(array('messages'=> $messages, 'count'=> $count, 'user_id'=>$user['id']));

    }

    public function getParticipantInfo($conv_id){
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;

        $this->layout = 'ajax';
        $options['conditions'] = array('Conversation.conversation_id'=>$conv_id);
        $conversation = $this->Conversation->find('first', $options);
        CakeLog::write("messagesDebug" , print_r($conversation, true));
        $participant = $this->Conversation->getOtherParticipant($conversation, $this->Auth->User());
        CakeLog::write("messagesDebug" , print_r($participant, true));


        if (intval($participant['user_type']) == 1)
            $participant['first_name'] = $participant['company_name'];
        
        unset($participant['password']);
        unset($participant['phone']);
        unset($participant['group_id']);
        unset($participant['vericode']);
        unset($participant['twitter_auth_token']);
        unset($participant['twitter_auth_token_secret']);
        unset($participant['password_reset_token']);
        unset($participant['password_reset_date']);
        unset($participant['created']);
        unset($participant['modified']);

        $this->set('response', json_encode($participant));
    }

    // Ajax function that will "Delete the conversation" basically just hides it from the user
    // responded with a json object indicating success
    public function deleteConversation(){
        
        if(!$this->request->isPost()){
            throw new NotFoundException();
        }
        $user = $this->Auth->User();
        $data = $this->request->data;

        $conv_id = $data['conv_id'];
        
        if(!$this->Conversation->isUserParticipant($conv_id, $user)){
             $json = json_encode(array(
                'success' => 0,
            ));
            $this->layout = 'ajax';
            $this->set('response', $json);
            return;
        }

        // We are going to hide the conversation from the user
        // if the other participant ever messages this user again
        // the post will be come visible again

        $options['conditions'] = array('Conversation.conversation_id'=>$conv_id);
        $conversation = $this->Conversation->find('first', $options);
        $this->Conversation->hideConversation($conversation, $user);

        $json = json_encode(array(
                'success' => 1,
            ));
        $this->layout = 'ajax';
        $this->set('response', $json);


    }

    public function messageSublet(){
        if( !$this->request->is('ajax') && !Configure::read('debug') > 0)
            return;
        $this->layout = 'ajax';

        if(!$this->request->isPost()){
            throw new NotFoundException();
        }  

        if (!$this->Auth->User()){
            /* User not logged in */
            $error = array(
                'success' => false,
                'message' => 'You must login to contact this property manager.'
            );
            $this->set('response', json_encode($error));
            return;
        }

        $listing_id = $this->request->data['listing_id'];
        $message_body = $this->request->data['message_body'];

        if(!($listing_id && $message_body)){
            $json = json_encode(array(
                'success' => false,
                'message' => "Not all parameters received in request",
            ));
            $this->layout = 'ajax';
            $this->set('response', $json);
            return;
        }

        $user = $this->User->get($this->Auth->User('id'));

        $listing = $this->Listing->find('first', array('conditions'=>'Listing.listing_id='.$listing_id));
        if($listing_id == null){
            $json = json_encode(array(
                'success' => false,
                'message' => "listing with id $listing_id does not exist",
            ));
            $this->layout = 'ajax';
            $this->set('response', $json);
            return;
        }


        $options['conditions'] = array(
            'Conversation.listing_id'=>$listing['Listing']['listing_id'],
            'Conversation.participant1_id'=>$user['User']['id'],
            'Conversation.participant2_id'=>$listing['User']['id']
            );
        $conversation = $this->Conversation->find('first', $options);
        if($conversation){
            // Conversation already exists so just add a new message to the conversation
            $this->Message->createMessage($message_body, 
                $conversation['Conversation']['conversation_id'],
                $user['User']);

            $message = $this->Message->read();
        }else{
            // Conversation doesn't exist so we need to create it.
            
            
            $data = array();
            $data['listing_id'] = $listing_id;
            $data['participant1_id'] = $user['User']['id'];
            $data['participant2_id'] = $listing['User']['id'];
            // We need to get the street address of the marker the sublet
            // Corresponds to since that will be the title of the conversation
            $data['title'] = $listing['Marker']['street_address'];

            $this->Conversation->createConversation($data);
            $conversation = $this->Conversation->read();
            $this->Message->createMessage($message_body, 
                $conversation['Conversation']['conversation_id'],
                $user['User']);

            $message = $this->Message->read();
        }   

        $this->emailUserAboutMessage($listing['User'], $user['User'], $conversation, $message_body); 
        $json = json_encode(array(
                'success' => true,
        ));
        $this->set('response', $json);
        return;
    }


    private function emailUserAboutMessage($recipient, $from_user, $conversation, $message_text){
            //send unread message email
        $this->Email->smtpOptions = array(
          'port'=>'587',
          'timeout'=>'30',
          'host' => 'smtp.sendgrid.net',
          'username'=>'cribsadmin',
          'password'=>'lancPA*travMInj',
          'client' => 'a2cribs.com'
        );  

        $from_name = $from_user['first_name'];
        $from_university = null;
        if (array_key_exists('registered_university', $from_user) && !empty($from_user['registered_university']))
            $from_university = $this->University->getNameFromId($from_user['registered_university']);

        if (intval($from_user['user_type']) === 1)
            $from_name = $from_user['company_name'];

        $this->Email->delivery = 'smtp';
        $from_full_name = $from_name;
        if (intval($from_user['user_type']) === 0)
            $from_full_name .= ' '.$from_user['last_name'];
        $this->Email->from = $from_full_name.'<info@cribspot.com>';
        $this->Email->to = $recipient['email'];
        $this->Email->bcc = 'jason@cribspot.com';
        
        $this->Email->subject = "You've received a new message from " . $from_name . " on Cribspot!";
        $this->Email->template = 'unread_message';
        $this->Email->sendAs = 'html';
        $this->set(array(
            'participant'=> $from_user,
            'conv_id'=>  $conversation['Conversation']['conversation_id'],
            'host_name' => $_SERVER['HTTP_HOST'],
            )
        );
        /* Get the data we need to fill in fields in the email */
        $listing = $this->Listing->findByListingId($conversation['Conversation']['listing_id']);
        if ($listing === null || !array_key_exists('Listing', $listing) || !array_key_exists('Marker', $listing) || 
            !array_key_exists('street_address', $listing['Marker']))
            return;

        $street_address = $listing['Marker']['street_address'];
        $is_property_manager = (intval($recipient['user_type']) === 1);

        if ($street_address !== null){
            if ($is_property_manager)
                $this->Email->subject = "I'm interested in ".$street_address;
            else
                $this->Email->subject = "You've received a response from ".$from_name." about ".$street_address;
        }

        $email_verified = $recipient['verified'];
        $reset_password_url = null;

        if (array_key_exists('id', $recipient) && array_key_exists('login_code', $recipient) &&
            !empty($recipient['id']) && !empty($recipient['login_code']))
                $reset_password_url = "https://www.cribspot.com/users/PMLogin?id=".$recipient['id'] . 
                "&code=".$recipient['login_code'];

        /* 
        Set new login code 
        Need one login code for "Message [person_name]" button and another for update availability buttons
        */
        $code_for_message = uniqid();
        $code_for_availability = uniqid(); /* 'pseudo-randomize' the second code */
        if (!strcmp($code_for_message, $code_for_availability))
            $code_for_availability .='1';

        $this->LoginCode->Add($recipient['id'], $code_for_message);
        $this->LoginCode->create();
        $this->LoginCode->Add($recipient['id'], $code_for_availability);

        $recipient['login_code'] = array(
            'message' => $code_for_message,
            'availability' => $code_for_availability
        );

        /*
        Generate URLs for:
        - log in and respond to message.
        - 1 button each for set as available, set as unavailable
        */
        $reset_password_url = "https://www.cribspot.com/users/PMLogin?id=".$recipient['id'] . 
            "&code=".$recipient['login_code']['message'];
        $availability_base_url = "https://www.cribspot.com/Listings/SetAvailabilityFromEmail?id=".$recipient['id'].
            "&code=".$recipient['login_code']['availability']."&l=".$conversation['Conversation']['listing_id']."&a=";
        $set_available_url = $availability_base_url.'1';
        $set_unavailable_url = $availability_base_url.'0';

        $this->set('to_property_manager', $is_property_manager);
        $this->set('from_name', $from_name);
        $this->set('from_university', $from_university);
        $this->set('street_address', $street_address);
        $this->set('email_verified', $email_verified);
        $this->set('reset_password_url', $reset_password_url);
        $this->set('set_available_url', $set_available_url);
        $this->set('set_unavailable_url', $set_unavailable_url);
        $this->set('message_text', $message_text);
        $this->set('conv_id', $conversation['Conversation']['conversation_id']);
        $this->set('listing_url', 'https://www.cribspot.com/listing/'.$conversation['Conversation']['listing_id']);
        $unit_description = null;
        if (array_key_exists('Rental', $listing) && array_key_exists('unit_style_options', $listing['Rental']) && 
            array_key_exists('unit_style_description', $listing['Rental']) && !empty($listing['Rental']['unit_style_options']) &&
            !empty($listing['Rental']['unit_style_description']))
                $unit_description = Rental::unit_style_options($listing['Rental']['unit_style_options']).
                    ' - '.$listing['Rental']['unit_style_description'];

        $this->set('unit_description', $unit_description);

        $year = null;
        if (array_key_exists('year', $from_user))
            $year = $from_user['year'];

        $this->set('student_year', $year);

        /* Set the students facebook img url, if facebook_id is saved */
        $img_url = "https://www.cribspot.com/img/head_large.jpg";
        if (!empty($from_user['facebook_id']))
            $img_url = "https://graph.facebook.com/".$from_user['facebook_id']."/picture?width=180&height=180";

        if (!empty($from_user['profile_img']))
            $img_url = 'https://www.cribspot.com/'.$from_user['profile_img'];

        $this->set('img_url', $img_url);

        /* Set URL to open message */
        $view_msg = "https://www.cribspot.com/users/PMLogin?id=".$recipient['id'] . 
                "&code=".$recipient['login_code']['message'].'&convid='.$conversation['Conversation']['conversation_id'];
        $this->set('view_msg', $view_msg);

        $this->Email->send();

    }
}
 ?>