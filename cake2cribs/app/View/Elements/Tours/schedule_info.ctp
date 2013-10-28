<?php echo $this->Html->css('/less/Tours/schedule_info.less?','stylesheet/less', array('inline' => false)); ?>

<div id="schedule_info" class="schedule_page">
	<h3>Just a few more clicks away...</h3>
	<div class="row-fluid">
		<div class="input-label span4">My Phone Number</div>
		<div class="input-append span8">
			<input id="phone_verified" type="hidden">
			<input id="verify_phone_number" class="span8" type="text" maxlength="10" placeholder="e.g. 0123456789">
			<button id="verify_phone_btn" class="btn" type="btn"><!--<i class="icon-ok-sign"></i> -->Click to Verify</button>
		</div>
	</div>
	<div class="row-fluid">
		<div class="comment">
			<div class="comment-arrow"></div>
			<div class="comment-inner"><strong>Why do we need your phone number?</strong> A text message will be sent an hour before the tour with your response required. If no response your tour will be canceled.</div>
		</div>
	</div>
	<br>
	<br>
	<div class="row-fluid">
		<div class="input-label span12">Invite My Housemates</div>
	</div>
	<div id="email_invite_list">
	<?php
		for ($i=0; $i < 3; $i++) { 
			echo "
			<div data-email-row='" . $i ."' class='row-fluid email_row'>
				<input class='roommate_input roommate_name' type='text' placeholder='Name'>
				<input class='roommate_input roommate_email' type='email' placeholder='Email'>
				<span class='complete_email'><i class='icon-ok-sign icon-large'></i></span>
			</div>
			";
		}
	?>
	</div>
	<div class="row-fluid">
		<a id="add_roommate_email" href="#add_roommate_email"><i class="icon-plus-sign"></i> Add more roomies!</a>
	</div>
	<br>
	<br>
	<div class="row-fluid">
		<div class="input-label span12">Add a Note</div>
	</div>
	<div class="row-fluid">
		<textarea id="tour_notes" class="span12" rows="3"></textarea>
	</div>
	<div class="btn-row">
		<button id="complete_tour_request" class="btn" data-loading-text="Requesting...">Request My Tour</button>
	</div>
</div>