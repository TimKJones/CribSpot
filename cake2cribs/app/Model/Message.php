<?php
class Message extends AppModel {
	public $name = 'Message';	
	public $uses = array('Message', 'Conversation', 'User', 'UnreadMessage');
	public $primaryKey = 'message_id';
	public $actsAs = array('Containable');
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Conversation' => array(
			'className' => 'Conversation',
			'foreignKey' => 'conversation_id',
		),
	);
	
	public $validate = array(
		'message_id' => 'numeric',
		'conversation_id' => 'numeric',
		'user_id' => 'numeric',
		'date_created' => 'datetime',
	);

	public function createMessage($body, $conv_id, $user){
		
		//See if the user is actually a participant in the conversation
		if(!$this->Conversation->isUserParticipant($conv_id, $user)){
			echo "User not a participant";
			return -1;
		}

		$message_data = Array(
			'Message' => Array(
				'conversation_id' => $conv_id,
				'user_id' => $user['id'],
				'body' => $body
			)
		);

		if(!$this->save($message_data)){
			die(debug($this->validationErrors));
		}
		//Get the full data for the message
		$message = $this->read();
		// Update the conversation with the last message id
		$conversation = $this->Conversation->find('first',  array(
		    'conditions' => array('Conversation.conversation_id' => $conv_id)
		    )
		);
		$this->Conversation->addNewMessage($conversation['Conversation'], $this->id);
		
		//Create an unread message notification for this message
		$target_user = $this->Conversation->getOtherParticipant($conversation, $user);
		$unreadmessages = ClassRegistry::init('UnreadMessage');
		$unreadmessages->add($message, $target_user);
		
		return $this->id;
	}

	//Returns all the messages for a given conversation
	public function getMessages($conv_id){
		return $this->find('all', array(
		    'conditions' => array('Message.conversation_id' => $conv_id),
		));	}
}
?>