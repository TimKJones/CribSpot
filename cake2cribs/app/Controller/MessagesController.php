<?php

 class MessagesController extends AppController {
    public $helpers = array('Html');
    public $uses = array('Message', 'Conversation', 'User', 'UnreadMessage', 'University', 'Listing', 'Rental');
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
            $this->emailUserAboutMessage($participant, $user, $conversation);
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
        
        $this->layout = 'ajax';
        $json = json_encode($conversations);
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
                $this->set('messages', $response);
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

        $this->emailUserAboutMessage($listing['User'], $user['User'], $conversation); 
        $json = json_encode(array(
                'success' => true,
        ));
        $this->set('response', $json);
        return;
    }


    private function emailUserAboutMessage($recipient, $from_user, $conversation){
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
        if (intval($from_user['user_type']) === 1)
            $from_name = $from_user['company_name'];

        $this->Email->delivery = 'smtp';
        $this->Email->from = 'The Cribspot Team<info@cribspot.com>';
        $this->Email->to = $recipient['email'];
        
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
        $street_address = $this->Listing->GetStreetAddressFromListingId($conversation['Conversation']['listing_id']);

        if ($street_address !== null)
            $this->Email->subject = "You've received a message from ".$from_name." about ".$street_address;

        $is_property_manager = (intval($recipient['user_type']) === 1);
        $email_verified = $recipient['verified'];
        $reset_password_url = null;
//You've received a new message on Cribspot about your property at address!
//You've received a new message on Cribspot about address!
        $intro_greeting = "You've received a new message on Cribspot";
        if ($street_address){
            if ($is_property_manager)
                $intro_greeting .= " about your property at ";
            else
                $intro_greeting .= " about ";
            $intro_greeting .= $street_address;
        }

        $intro_greeting .= "!";

        if (array_key_exists('id', $recipient) && array_key_exists('password_reset_token', $recipient) &&
            !empty($recipient['id']) && !empty($recipient['password_reset_token']))
                $reset_password_url = "www.cribspot.com/users/ResetPasswordRedirect?id=".$recipient['id'] . 
                "&reset_token=".$recipient['password_reset_token'];
        $this->set('is_property_manager', $is_property_manager);
        $this->set('street_address', $street_address);
        $this->set('email_verified', $email_verified);
        $this->set('reset_password_url', $reset_password_url);
        $this->set('intro_greeting', $intro_greeting);
        $this->Email->send();

    }
}
 ?>