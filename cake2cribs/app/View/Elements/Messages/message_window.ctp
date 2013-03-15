<div id = 'messaging_window'>

	<div class = 'row-fluid' id = 'participant_info_short'>
		<div class ='span8'>
			<a href = 'view that listing' id = 'listing_title'></a><i class = 'icon-map-marker icon-large'></i>
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
			<img src="/img/messages/me_small.png"></img>
			<img src = '/img/messages/arrow-right.png' class = 'bump'></img>
			<div id = 'message_text'>
				<textarea placeholder = 'Type your message here.'></textarea>
			</div>
			<button class = 'btn btn-primary' id = 'send_reply'>Send</button>
		</div>
	</div>

</div>