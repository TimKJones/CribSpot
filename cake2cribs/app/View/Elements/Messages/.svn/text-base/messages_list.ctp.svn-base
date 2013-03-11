<?php 

$count += (count($messages)-1);
foreach(array_reverse($messages) as $message){
	// debug($message);
	// if($message['User']['id'] !== $user_id){
	// 	$user_id = $message['User']['id'];
	// 	echo "<div class = 'msg_user'><strong>" . $message['User']['username'] . "</strong></div>";
	// }
	echo $this->element('Messages/message_list_item', array('message'=>$message, 'count'=>$count, 'user_id'=>$user_id));
	$count--;
}
?>