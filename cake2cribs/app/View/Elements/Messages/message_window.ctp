
<div id = 'messaging_window'>

	<div class = 'row-fluid' id = 'participant_info_short'>
		<div class ='span8'>
			<i class = 'icon-map-marker icon-large'> </i><a href = '#' id = 'listing_title'></a>
			<span id = 'conversation_toolbar'>
				<i  id = 'refresh_content' class = 'icon-refresh'></i><i id = 'delete_conversation' class = 'icon-trash'></i>
			</span>

		</div>
	</div> 
	<div class = 'row-fluid shadowed' id = 'current_conversation'>
		<div id = 'message_list' class = 'span12'></div>
	</div>
	<div class = 'row-fluid' id = 'message_reply'>
		<div class = 'span12'>
			<?php 
			if (array_key_exists('profile_img', $user['User']) && $user['User']['profile_img'] != null)
			{
				echo "<img src='/" . $user['User']['profile_img'] . "'>";
			}
			else if (array_key_exists('facebook_id', $user['User']) && $user['User']['facebook_id'] != null)
			{
				echo "<img src='https://graph.facebook.com/".$user['User']['facebook_id']."/picture?width=80&height=80'></img>";
			}else{
				echo "<img src = '/img/head_medium.jpg'></img>";
			}
			?>
			<img src = '/img/messages/arrow-right.png' class = 'bump'></img>
			<div id = 'message_text'>
				<textarea placeholder = 'Type your message here.'></textarea>
			</div>
			<button class = 'btn btn-primary' id = 'send_reply' data-loading-text="Send...">Send</button>
		</div>
	</div>

</div>