<!-- app/View/Messages/index.ctp -->
<?php echo $this->Html->css('bootstrap'); ?>
<?php echo $this->Html->css('font-awesome'); ?>
<?php echo $this->Html->css('messages'); ?>
<?php echo $this->element('header'); ?>

<div class='container-fluid' id = 'main_content'>
	<div class = 'row-fluid' id = 'messages_widget'>
		<div class = 'span3' id = 'left_content'>
			<div id = 'conversations_list_header'>
				<span>Messages</span><a id = 'toggle-conversations' href = '#'><i id = 'a' class = 'icon-caret-right'></i></a><div id = 'unread_conversations_count'><span>0</span></div>
			</div>
			<div id = 'conversation_drop_down'>
				<div id = 'conversation_controls'>
						<small>Show only unread </small><input type = 'checkbox' name = 'view_unread' id = 'view_unread_cb'></input>
				</div>

				<div class = 'conversation_list'></div>

			</div>
		</div>
		<div class = 'span6' id = 'middle_content'>

			<div id = 'messaging_window'>

				<div class = 'row-fluid' id = 'participant_info_short'>
					<div class ='span8'>
						<a href = 'view that listing' id = 'listing_title'></a><i class = 'icon-map-marker icon-large'></i>
						<span id = 'conversation_toolbar'>
							<i  id = 'refresh_content' class = 'icon-refresh'></i><i class = 'icon-trash'></i>
						</span>

					</div>
					<!-- <div class = 'hr'></div> -->
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
		</div>
		<div class = 'span3' id = 'right_content'>
			<div class = 'row-fluid'>
				<div class = 'span12'>
					<div class = 'shadowed' id = 'participant_info'>
						<div id = 'participant_pic_large'>
							<img class = '' src="/img/messages/archer_big.png"></img>
						</div>
						<p><a id = 'participant_name' class = 'from_participant'></a></p>
						<p id = 'participant_university'><br></p>
					</div>
					<div id = 'verification-panel'>
						<div id = 'veri-email'>
							<i class = 'icon-envelope-alt'></i>Email Address<i class = 'verified icon-ok-sign'></i>
						</div>
						<div id = 'veri-edu'>
							<i></i> @umich.edu<i class = 'verified icon-ok-sign'></i>
						</div>
						<div id = 'veri-fb'>
							<i class = 'fb icon-facebook-sign'></i>Facebook - <a href='#' id ='participant-followers'>211 Mutual</a><i class = 'verified icon-ok-sign'></i>
						</div>
						<div id = 'veri-tw'>
							<i class = 'tw icon-twitter-sign'></i>Twitter - <a href = '#' id ='participant-followers'>1071 Followers</a><i class = 'verified icon-ok-sign'></i>
						</div>
					</div>
					<div>
						<p class = 'pull-right' id = 'meaning'> What does this mean? </p>
						<br>
						<p id = 'hidden-meaning'>
							The panel shows whether the user has verified their email, their .edu address, their facebook, and their twitter.
						</div>
					</div>
				</div>
			</div>
		</div>
	
	<?php #echo $this->element('messages/new_conversation_form'); ?>

</div>

<script>
	// A2Cribs.Messages.init();
	var view_conversation = <?php echo $view_conversation;?>;
	var a = A2Cribs.Messages
	a.init(view_conversation);
	a.setupUI();
</script>
	
