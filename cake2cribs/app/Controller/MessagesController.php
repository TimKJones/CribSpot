<?php

 class MessagesController extends AppController {
 	public $helpers = array('Html');
	public $uses = array('Message', 'Conversation', 'User', 'UnreadMessage', 'University', 'Sublet');
	public $components= array('Session','Auth','Email', 'Cookie');

	function beforeFilter(){
		parent::beforeFilter();
	    if(!$this->Auth->user()){
	        //$this->flash("You may not access this page until you login.", array('controller' => 'users', 'action' => 'login'));
	        $this->Session->setFlash(__('Please login to view messages.'));
	        $this->redirect(array('controller'=>'users', 'action'=>'login'));
	    }
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

        $conversation = $this->Conversation->find('first', array('conditions', 'conversation.conversation_id = $conv_id'));

        if($conversation['Participant1']['id'] = $user['id']){
            $participant_id = $conversation['Participant2']['id'];
        }else{
            $participant_id = $conversation['Participant1']['id'];
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

 	// //Create a new conversation and the first message thats in the conversation
 	// public function newConversation(){
 	// 	if(!$this->request->isPost()){
		// 	echo "This url only accepts post requests";
 	// 		die();
 	// 	}
 	// 	$data = $this->request->data;
		// $conv_id = $this->Conversation->createConversation($data, $this->Auth->User());
		// $msg_id = $this->Message->createMessage($data['message_body'], $conv_id, $this->Auth->User());
 	// 	$this->redirect(array('controller' => 'messages', 'action' => 'index'));
 	// }


 	//Creates a new message in a specified conversation
 	public function newMessage(){
		if(!$this->request->isPost()){
			echo "This url only accepts post requests";
 			die();
 		}
 		$data = $this->request->data;
 		$user = $this->Auth->User();
 		//Create send reply
 		$msg_id = $this->Message->createMessage($data['message_text'], $data['conversation_id'], $user);
 		
 		$message = $this->Message->read();
 		$options['condtions'] = array('Conversation.conversation_id'=>$message['Message']['conversation_id']);
 		$conversation = $this->Conversation->find('first', $options);
 		$participant = $this->Conversation->getOtherParticipant($conversation, $this->Auth->User());

 		$this->emailUserAboutMessage($participant['email'], $user, $conversation);


 		$json = json_encode(array('success'=>$msg_id > 0));
 		$this->layout = 'ajax';
 		$this->set('response', $json);


 	}

 	//Ajax function to get all the conversations the user has going on
 	public function getConversations(){
 		$user = $this->Auth->User();
 		$only_unread = 0;
 		if(array_key_exists('only_unread', $this->request->query)){
 			$only_unread = intval($this->request->query['only_unread']);
 		}
 		$conversations = $this->Conversation->getConversations($user['id'], ($only_unread==1));
        
 		$this->layout = 'ajax';
 		$this->set(array('conversations'=> $conversations));	
 	}

 	// Ajax function to get a json response with the number of unread messages and conversations a user has
 	public function getUnreadCount(){
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

 // 	public $paginate = array(
 //        'limit' => 5,
	// );

 	// Ajax function to get all the messages in a conversation
	public function getMessages($conv_id, $page=1){
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
    			throw new NotFoundException();
    		}
    		if($page == 1){
    			// Clear the unread messages since the user just fetched the most recent 
    			// messages of the conversation
    			$unreadmessages = ClassRegistry::init('UnreadMessage');
 				$unreadmessages->clearUnread($conv_id, $user);	
    		}
 			
 		}

 		$count = ($page-1) * $limit;

 		$this->layout = 'ajax';
 		$this->set(array('messages'=> $messages, 'count'=> $count, 'user_id'=>$user['id']));	
 	}

 	public function getParticipantInfo($conv_id){
 		$options['conditions'] = array('Conversation.conversation_id'=>$conv_id);
 		$conversation = $this->Conversation->find('first', $options);
 		$participant = $this->Conversation->getOtherParticipant($conversation, $this->Auth->User());
 			
 		$options['conditions'] = array('University.id'=>$participant['university_id']);
 		// $options['fields'] = array('University.name');
 		// $options['contain'] = true;
 		$this->University->recursive = -1;
 		$university = $this->University->find('first', $options);
 		$participant['University'] = $university['University'];
 		$this->layout = 'ajax';
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

        $options['condtions'] = array('Conversation.conversation_id'=>$conv_id);
        $conversation = $this->Conversation->find('first', $options);
        $this->Conversation->hideConversation($conversation, $user);

        $json = json_encode(array(
                'success' => 1,
            ));
        $this->layout = 'ajax';
        $this->set('response', $json);


 	}

    public function msgSubTest(){}

    
    /*
        Function expects a post request, 404 response will be given otherwise
        post parameters need to be {'sublet_id':int, 'message_body':string}

        valid reponse is json, with an object notation as follows
        {
        'successs': boolean
        'message': string (only if error occured)
        }

    */
    public function messageSublet(){
        if(!$this->request->isPost()){
            throw new NotFoundException();
        }  

        $sublet_id = $this->request->data['sublet_id'];
        $message_body = $this->request->data['message_body'];

        if(!($sublet_id && $message_body)){
            $json = json_encode(array(
                'success' => false,
                'message' => "Not all parameters received in request",
            ));
            $this->layout = 'ajax';
            $this->set('response', $json);
            return;
        }

        $user = $this->User->get($this->Auth->User('id'));

        $sublet = $this->Sublet->find('first', array('conditions'=>'Sublet.id='.$sublet_id));
        if($sublet == null){
            $json = json_encode(array(
                'success' => false,
                'message' => "sublet with id $sublet_id does not exist",
            ));
            $this->layout = 'ajax';
            $this->set('response', $json);
            return;
        }



        $options['conditions'] = array(
            'Conversation.sublet_id'=>$sublet['Sublet']['id'],
            'Conversation.participant1_id'=>$user['User']['id'],
            'Conversation.participant2_id'=>$sublet['User']['id']
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
            $data['sublet_id'] = $sublet_id;
            $data['participant1_id'] = $user['User']['id'];
            $data['participant2_id'] = $sublet['Sublet']['user_id'];
            // We need to get the street address of the marker the sublet
            // Corresponds to since that will be the title of the conversation
            $data['title'] = $sublet['Marker']['street_address'];

            $this->Conversation->createConversation($data);
            $conversation = $this->Conversation->read();
            $this->Message->createMessage($message_body, 
                $conversation['Conversation']['conversation_id'],
                $user['User']);

            $message = $this->Message->read();

        }   

        $this->emailUserAboutMessage($sublet['User']['email'], $user['User'], $conversation);
        $json = json_encode(array(
                'success' => true,
        ));
        $this->layout = 'ajax';
        $this->set('response', $json);
        return;

    }


    private function emailUserAboutMessage($email_addr, $from_user, $conversation){
            //send unread message email
        $this->Email->smtpOptions = array(
          'port'=>'587',
          'timeout'=>'30',
          'host' => 'smtp.sendgrid.net',
          'username'=>'cribsadmin',
          'password'=>'lancPA*travMInj',
          'client' => 'a2cribs.com'
        );


        $this->Email->delivery = 'smtp';
        $this->Email->from = 'The A2Cribs Team<team@a2cribs.com>';
        $this->Email->to = $email_addr;
        
        $this->Email->subject = 'New message received from ' . $from_user['first_name'];
        $this->Email->template = 'unread_message';
        $this->Email->sendAs = 'html';
        $this->set(array(
            'participant'=> $from_user,
            'conv_id'=>  $conversation['Conversation']['conversation_id'],
            'host_name' => $_SERVER['HTTP_HOST'],
            )
        );
        

        $this->Email->send();

    }
}
 ?>