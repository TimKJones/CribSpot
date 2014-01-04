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
CakeLog::write('createmessage', print_r($conv_id, true));
CakeLog::write('createmessage', print_r($user, true));
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
CakeLog::write('createmessage', '2');
		if(!$this->save($message_data)){
			$this->logError($user['id'], 56, $message_data);
			die(debug($this->validationErrors));
		}
		//Get the full data for the message
		$message = $this->read();
CakeLog::write('createmessage', '3');
		// Update the conversation with the last message id
		$conversation = $this->Conversation->find('first',  array(
		    'conditions' => array('Conversation.conversation_id' => $conv_id)
		    )
		);
CakeLog::write('createmessage', '4');
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
	Returns a map of listing_id to # of messages received for that listing_id
	*/
	public function GetListingIdToReceivedMessagesMap()
	{
		$messages = $this->find('all', array(
			'contain' => array('Conversation'),
			'conditions' => array('Listing.visible' => 1),
			'joins' => array(
				array(
			        'table' => 'conversations',
			        'alias' => 'C',
			        'type' => 'inner',
			        'foreignKey' => false,
			        'conditions'=> array('Message.conversation_id = C.conversation_id')
			    ),
			    array(
			        'table' => 'listings',
			        'alias' => 'Listing',
			        'type' => 'inner',
			        'foreignKey' => false,
			        'conditions'=> array(
			            'Listing.listing_id = C.listing_id'
			        )
			    )
			)
		));
		CakeLog::write('joinstest', print_r($messages, true));
		$listingIdToDailyMessageCountMap = array();
		$listingIdToTotalMessageCountMap = array();
		foreach ($messages as $message)
		{
			$user_id = $message['Conversation']['participant2_id'];
			$listing_id = $message['Conversation']['listing_id'];
			if (!array_key_exists($listing_id, $listingIdToTotalMessageCountMap)) {
				$listingIdToTotalMessageCountMap[$listing_id] = 0;
				$listingIdToDailyMessageCountMap[$listing_id] = 0;
			}
		

			$listingIdToTotalMessageCountMap[$listing_id] += 1;
			$message_date = $message['Message']['created'];
			$yesterday = date('Y-m-d', time() - 60 * 60 * 24);
			if ($message_date >= $yesterday)
				$listingIdToDailyMessageCountMap[$listing_id] += 1;
		}

		return array(
			'daily' => $listingIdToDailyMessageCountMap,
			'total' => $listingIdToTotalMessageCountMap
		);
	}

	/* Returns all messages */
	public function getAllMessagesSentByStudents()
	{
		return $this->find('all', array(
			'conditions' => array(
				'User.user_type' => 0
			),
			'joins' => array(
				array(
			        'table' => 'conversations',
			        'alias' => 'C',
			        'type' => 'inner',
			        'foreignKey' => false,
			        'conditions'=> array('Message.conversation_id = C.conversation_id')
			    ),
			    array(
			        'table' => 'listings',
			        'alias' => 'Listing',
			        'type' => 'inner',
			        'foreignKey' => false,
			        'conditions'=> array(
			            'Listing.listing_id = C.listing_id'
			        )
			    )
			)
		));
	}


	public function getConversationIdToPMResponseDateMap($conversations)
	{
		$pm_id_conversation_id_pairs = array();
		$allMessages = $this->find('all');
		/* Get a */
		foreach ($conversations as $conversation){
			if (!array_key_exists('Conversation', $conversation))
				continue;

			$pm_id = $conversation['Conversation']['participant2_id'];
			$conversation_id = $conversation['Conversation']['conversation_id'];
			array_push($pm_id_conversation_id_pairs, array(
				'pm_id' => $pm_id,
				'conversation_id' => $conversation_id
			));
		}

		
	}
}
?>