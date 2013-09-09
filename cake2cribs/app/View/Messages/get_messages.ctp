<?php 
if (array_key_exists('error', $messages)){
	echo json_encode($messages);
} else {
	$messages = $this->element('Messages/messages_list', array('messages'=>$messages, 'user_id'=>$user_id)); 
	echo json_encode($messages);
}
?>