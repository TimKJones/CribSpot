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

		$message_data = array(
			'Message' => array(
				'conversation_id' => $conv_id,
				'user_id' => $user['id'],
				'body' => $body
			)
		);

		if(!$this->save($message_data)){
			$this->logError($user['id'], 56, $message_data);
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
		));	
	}

	/*
	Returns a map of user_id to # of messages they've received
	*/
	public function GetUserIdToReceivedMessagesMap()
	{
		$messages = $this->find('all', array(
			'contain' => array('Conversation')
		));
		$userIdToDailyMessageCountMap = array();
		$userIdToTotalMessageCountMap = array();
		foreach ($messages as $message)
		{
			$user_id = $message['Conversation']['participant2_id'];
			$listing_id = $message['Conversation']['listing_id'];
			if (!array_key_exists($user_id, $userIdToTotalMessageCountMap)) {
				$userIdToTotalMessageCountMap[$user_id] = 0;
				$userIdToDailyMessageCountMap[$user_id] = 0;
			}
		

			$userIdToTotalMessageCountMap[$user_id] += 1;
			$message_date = $message['Message']['created'];
			$today = date('Y-m-d');
			CakeLog::write('dates', $message_date . ' ' . $today);
			if ($message_date >= $today)
				$userIdToDailyMessageCountMap[$user_id] += 1;
		}

		return array(
			'daily' => $userIdToDailyMessageCountMap,
			'total' => $userIdToTotalMessageCountMap
		);
	}
}
?>