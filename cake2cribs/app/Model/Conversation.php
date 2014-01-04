<?php

class Conversation extends AppModel {
	public $name = 'Conversation';	
	public $uses = array('Conversation', 'Listing', 'User', 'Message');
	public $primaryKey = 'conversation_id';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Participant1' => array(
            'className' => 'User',
            'foreignKey' => 'participant1_id'
        ),
        'Participant2' => array(
            'className' => 'User',
            'foreignKey' => 'participant2_id'
        ),
        'Listing' => array(
        	'className' => 'Listing',
        	'foreignKey' => 'listing_id',
        ),
        'Last_Message' => array(
        	'className' => 'Message',
        	'foreignKey' => 'last_message_id'
        )
    );  
	
	public $validate = array(
		'conversation_id' => 'numeric',
		'listing_id' => 'numeric',
		'participant1_id' => 'numeric',
		'participant2_id' => 'numeric',
		'last_message_id' => 'numeric',
	);

	public function getAllConversations()
	{
		return $this->find('all');/*, array(
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
		));*/
	}

	//Returns all the conversations belonging to the given user. It takes
	// an optional paramater to determine whether to only show the unread
	// conversations. It is defaulted to false which means you will be returned
	// all the conversations for that user
	public function getConversations($user_id, $only_unread = false){
		
		//Set up a temporary virtual field to get the number of unread message per conversation.
		//This normally would be a static property except for the fact that $user_id is a variable 
		//and we have no way of determining at runtime whether participant1 or participant2 will be the user.
		//So we can't make the link saying UnreadMesage.user_id = Conversation.participant1_id or .participant2_id
		$this->virtualFields = array(
    		'unread_message_count' => 'SELECT COUNT(*) FROM unread_messages as UnreadMessage WHERE UnreadMessage.conversation_id = Conversation.conversation_id AND UnreadMessage.user_id ='.$user_id,

		);

		$options['conditions'] = array("OR" => array(
   				"Conversation.participant1_id" => $user_id,
    			"Conversation.participant2_id" => $user_id,
		));
		$options['order'] = array('Conversation.modified' => 'desc');
		
		if($only_unread){
			// Fetch only conversations that are unread
			$options['joins'] = array(
				array('table' => 'unread_messages',
		        	'alias' => 'UnreadMessage',
		        	'type' => 'INNER',
		        	'conditions' => array(
		            	'UnreadMessage.conversation_id = Conversation.conversation_id',
		            	'UnreadMessage.user_id' => $user_id
		        	),
				)	
			);
		}
		
		$options['fields'] = array(
				"DISTINCT Conversation.*",
				"Conversation.unread_message_count",
				"Participant1.first_name",
				"Participant1.company_name",
				"Participant1.id",
				"Participant2.first_name",
				"Participant2.company_name",
				"Participant2.id",

				"Last_Message.body",
				"Last_Message.user_id",
		);

		//  Sets a convience key for the other 'Participant'
		//  Sets the participant key in each conversation to the 
		//  the user that does not match the user id provided

		//  We will also go through and remove and conversation from the array where
		//  the user has marked that they have deleted it. By finding what participant
		//  they are we can see if this conversation is visible to them and then deal with it

		$conversations = $this->find('all', $options);
		foreach ($conversations as $key => &$conversation){
			
			if($conversation['Participant1']['id'] == $user_id){
				$conversation['Participant'] = $conversation['Participant2'];
				if($conversation['Conversation']['visible1'] == 0){
					unset($conversations[$key]);
				}
			}

			else{
				$conversation['Participant'] = $conversation['Participant1'];
				if($conversation['Conversation']['visible2'] == 0){
					unset($conversations[$key]);
				}

			}
			unset($conversation['Participant1']);
			unset($conversation['Participant2']);
		}
		
		return $conversations;

	}

	/* 
		Use to create a new conversation
	 	data expects the following keys to exists ['listing_id', 'target_user', 'title']
	 */
	public function createConversation($data){
		$conversation_data = array(
			'Conversation' => array(
				'listing_id' => $data['listing_id'],
				'participant1_id' => $data['participant1_id'],
				'participant2_id' => $data['participant2_id'],
				'title' => $data['title'],
				)
			);
		if(!$this->save($conversation_data)){
			$this->logError($data['participant1_id'], 53, array('data'=>$data, 'valError'=>$this->validationErrors));
			die(debug($this->validationErrors));
		}
		return $this->id;
	}

	//Returns true/false whether the user is a participant in the conversation
	public function isUserParticipant($conv_id, $user){
		$conditions = array(
			'Conversation.conversation_id' => $conv_id,
			"OR" => array(
   				"Conversation.participant1_id" => $user['id'],
    			"Conversation.participant2_id" => $user['id'],
			)
		);
		$count = $this->find('count', array('conditions'=>$conditions));
		return $count == 1;
	}

	// Updates the conversation field that points to the last message 
	// sent for the conversation.

	public function addNewMessage($conversation, $message_id){
		$conversation['last_message_id'] = $message_id;
		
		//  Make the conversation visible for both users again
		//  in the case of one of the users deleting it.
		$conversation['visible1'] = 1;
		$conversation['visible2'] = 1;

		//Unset the modified field so it gets updated
		unset($conversation['modified']); 
		$this->save($conversation);
	}

	// Deletes the conversation, and all the messages and unread messages associated
	// with the conversation being deleted

	// public function deleteConversation($conv_id){
	// 		}

	// Returns a user object for the other user involved in the converation
	// if for some reason the user is not a participant at all then null
	// will be returned.
	public function getOtherParticipant($conversation, $user){
		
		if($conversation['Participant1']['id'] == $user['id']){
			return $conversation['Participant2'];
		}else if($conversation['Participant2']['id'] == $user['id']){
			return $conversation['Participant1'];
		}
		//user was not a participant
		return null;
	}

	public function hideConversation($conversation, $user){
		if($conversation['Participant1']['id'] == $user['id']){
			$conversation['Conversation']['visible1'] = 0;
		}else if($conversation['Participant2']['id'] == $user['id']){
			$conversation['Conversation']['visible2'] = 0;
		}else{
			$this->logError($user['id'], 54, $conversation);
			CakeLog::write("Conversation.php", "Conversation ".$conversation['Conversation']['conversation_id']. " was attempted to be hidden by user: ". $user['id']);	
		}
		

		if(!$this->save($conversation)){
			$this->logError($user['id'], 55, $conversation);
			 CakeLog::write("Conversation.php", "Hiding Conversation Failed: " . $this->validationErrors);
		}
	}

	/*
	Returns the conversation_id for conversation with given listing_id and participants.
	If conversation doesn't exist, creates one and returns the id for the new conversation.
	*/	
	public function GetConversationId($title, $listing_id, $participant1_id, $participant2_id)
	{
		/* Look for an existing conversation about $listing_id between these participants */
        $conversation = $this->find('first', array( 
        	'conditions' => array(
        		'Conversation.listing_id'=>$listing_id,
	            'Conversation.participant1_id'=>$participant1_id,
	            'Conversation.participant2_id'=>$participant2_id
        	),
        	'contain' => array()
        ));
        if(array_key_exists('Conversation', $conversation) && array_key_exists('conversation_id', $conversation['Conversation']))
        	return $conversation['Conversation']['conversation_id'];
        else {
            // Conversation doesn't exist so we need to create it.
			$newConversation = array();
            $newConversation['listing_id'] = $listing_id;
            $newConversation['participant1_id'] = $participant1_id;
            $newConversation['participant2_id'] = $participant2_id;
            $newConversation['title'] = $title;
            $conversation_id = $this->createConversation($newConversation);
            return $conversation_id;
        }
	}
}

?>
