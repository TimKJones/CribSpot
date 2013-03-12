<?php

 class MessagesController extends AppController {
 	public $helpers = array('Html');
	public $uses = array('Message', 'Conversation', 'User', 'UnreadMessage', 'University');
	public $components= array('Session','Auth','Email');

	function beforeFilter(){
		parent::beforeFilter();
	    if(!$this->Auth->user()){
	        //$this->flash("You may not access this page until you login.", array('controller' => 'users', 'action' => 'login'));
	        $this->Session->setFlash(__('Please login to view messages.'));
	        $this->redirect(array('controller'=>'users', 'action'=>'login'));
	    }
	}
	
	//Shows the base messages page
 	public function index(){
 		// die(debug($_SERVER['HTTP_HOST']));
 		$view_conversation = -1;
 		if(array_key_exists('view_conversation', $this->request->query)){
 			$view_conversation = intval($this->request->query['view_conversation']);
 		}
 		$this->set(array('view_conversation'=>$view_conversation));

 	}

 	//Create a new conversation and the first message thats in the conversation
 	public function newConversation(){
 		if(!$this->request->isPost()){
			echo "This url only accepts post requests";
 			die();
 		}
 		$data = $this->request->data;
		$conv_id = $this->Conversation->createConversation($data, $this->Auth->User());
		$msg_id = $this->Message->createMessage($data['message_body'], $conv_id, $this->Auth->User());
 		$this->redirect(array('controller' => 'messages', 'action' => 'index'));
 	}


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
        $this->Email->to = $participant['email'];
        
        $this->Email->subject = 'New message received from ' . $user['first_name'];
        $this->Email->template = 'unread_message';
        $this->Email->sendAs = 'html';
        $this->set(array(
        	'participant'=> $user,
        	'conv_id'=>  $conversation['Conversation']['conversation_id'],
        	'host_name' => $_SERVER['HTTP_HOST'],
        	)
        );
        

        $this->Email->send();


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
 		$options['condtions'] = array('Conversation.conversation_id'=>$conv_id);
 		$conversation = $this->Conversation->find('first', $options);
 		$participant = $this->Conversation->getOtherParticipant($conversation, $this->Auth->User());
 			
 		$options['condtions'] = array('University.university_id'=>$participant['university_id']);
 		// $options['fields'] = array('University.name');
 		// $options['contain'] = true;
 		$this->University->recursive = -1;
 		$university = $this->University->find('first', $options);
 		$participant['University'] = $university['University'];
 		$this->layout = 'ajax';
 		$this->set('response', json_encode($participant));

 	}

 	public function deleteConversation($conv_id){
 		if(!$this->request->isPost()){
			echo "This url only accepts post requests";
 			die();
 		}

 		// Do we want to actually delete the messages or just hide them?

 	}

 }

 ?>