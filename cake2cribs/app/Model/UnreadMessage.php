<?php
class UnreadMessage extends AppModel {
	public $name = 'UnreadMessage';	
	public $uses = array('Message', 'Conversation', 'User');
	public $primaryKey = 'id';
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
		'Message' => array(
			'className' => 'Message',
			'foreignKey' => 'message_id',
		),
	);
	
	public $validate = array(
		'message_id' => 'numeric',
		'conversation_id' => 'numeric',
		'user_id' => 'numeric',
	);

	//Clear all the unread messages for the given conversation
	public function clearUnread($conv_id, $user){
		
		$this->deleteAll(array(
			'UnreadMessage.conversation_id' => $conv_id, 
			'UnreadMessage.user_id' => $user['id']), 
		false);
	}

	//Create a new unread message for the message being passed in
	public function add($message, $target_user){
		$unread_message_data = array(
			'UnreadMessage' => array(
				'conversation_id' => $message['Message']['conversation_id'],
				'user_id' => $target_user['id'],
				'message_id' => $message['Message']['message_id']
				)
			);
		
		if(!$this->save($unread_message_data)){
			die(debug($this->validationErrors));
		}

	}

	//Get the number of unread messages a user has.
	public function getUnreadMessagesCount($user){
		return $this->find('count', array(
		    'conditions' => array('UnreadMessage.user_id' => $user['id'])
		));
	}

	public function getUnreadConversationCount($user){
		return $this->find('count', array(
       		'fields' => 'DISTINCT UnreadMessage.conversation_id',
       		'conditions' => array('UnreadMessage.user_id' => $user['id'])
    	));
	}
}
?>