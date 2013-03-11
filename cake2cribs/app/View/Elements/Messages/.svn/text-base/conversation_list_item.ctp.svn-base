<?php
	$unread_message_count = $conversation['Conversation']['unread_message_count'];
	$conversation_id = $conversation['Conversation']['conversation_id'];
	$class = 'read_conversation';
	if($unread_message_count > 0){
		$class = 'unread_conversation';
	}

	$body = $conversation['Last_Message']['body'];
	$pos = strpos($body, "\n");
	if($pos != false){
		$body = substr($body, 0, $pos);
	}



?>

<div id = 'cli_<?php echo $conversation_id; ?>' class = 'conversation_list_item <?php echo $class;?>' convid = '<?php echo $conversation_id;?>' >
	<meta participantid='<?php echo $conversation['Participant']['id']?>'></meta>
	<div class = 'span12'>
		<div class = 'conversation_info'>
			<?php if ($conversation['Last_Message']['user_id'] != $conversation['Participant']['id']) {
					echo "<i class = 'icon-reply'></i>";		
				}
			?>
			<span class ='conversation_title'>
				<a><?php echo $conversation['Conversation']['title'];?></a>
			</span>
			<span class = 'conv_list_item_time_ago'>
				<?php echo $this->Time->timeAgoInWords($conversation['Conversation']['modified'], array('accuracy' => array('year'=>'year', 'month'=>'month', 'week'=>'week','day' => 'day', 'hour'=>'hour')));?>
			</span>
			<div>
				<p class = 'last_message'>
					<?php echo $body; ?>
				</p>
			</div>
		</div>
	</div>
</div>
