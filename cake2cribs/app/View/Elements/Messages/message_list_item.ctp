<!-- app/View/Elements/message_list_item.ctp -->
<?php 
	$msg_id = $message['Message']['message_id'];
	// die(debug($message));
	
	$pic = '/img/profile_pic_placeholder.png';
	if($user_id == $message['User']['id']){
		$side = "right";	
	}else{
		$side = "left";
	}
	if($message['User']['facebook_userid']){
		$pic = "https://graph.facebook.com/".$message['User']['facebook_userid']."/picture?width=80&height=80";	
	}

	// $time_ms = strtotime($message['Message']['created'])
	// if($time_ms > 1000 * 60 * 60 * 24 * 365){
	// 	$accuracy = 'year'
	// }


	// if($me)
		
?>

<div class = 'mli mli-<?php echo $side ?>-side row-fluid' id = 'mli_<?php echo $count?>' meta = '<?php echo $msg_id?>'>
	<div class = 'span12'>
		<div class = 'participant_message_pic'>	
			<img src = "<?php echo $pic; ?>"></img>
		</div>
		<img src = '/img/messages/arrow-<?php echo $side?>.png' class = 'arrow-<?php echo $side;?>'></img>
		<div class = 'message_bubble'>
		<div>
				<span class = 'bubble-top-row'>
					<a href = '/users/view/<?php echo $message['User']['id'];?>'>
						<strong><?php echo $message['User']['first_name'];?>:</strong>
					</a>
					<span class = 'time-ago'>
						<?php echo $this->Time->timeAgoInWords($message['Message']['created'], array('accuracy' => array('year'=>'year', 'month'=>'month', 'week'=>'week','day' => 'day', 'hour'=>'hour')));?>
					</span>
				</span>
				<p class = 'message_body'><?php echo str_replace(array("\r\n","\r","\n"),'<br>',$message['Message']['body']);?></p>
			</div>
		</div>
	</div>
</div>