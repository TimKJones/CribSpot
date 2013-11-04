<?php echo $this->Html->css('/less/Invitations/email_invite.less?','stylesheet/less', array('inline' => false)); ?>
<div id="email_invite" class="modal hide fade">
	<div class="modal-body">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 class="modal_subject">Almost done!</h3>
		<h4 class="modal_message">You'll need to invite your housing group to take advantage of all the features Cribspot has to offer.</h4>
		<h6>Enter an email address</h6>
		<div class="email_invite_list">
			<div class="roommate_row">
				<input data-roommate-count="1" class='roommate_email' type='email' placeholder='E.g. myhousem@te.com'>
				<i class="icon-ok-sign"></i>
			</div>
		</div>
		<a id="email_invite_add_roommate" class="add_roommate pull-right" href="#email_invite_add_roommate"><i class="icon-plus-sign"></i> Add another roommate</a>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-link pull-left" data-dismiss="modal" aria-hidden="true">SKIP</a>
		<a href="#" class="btn btn-primary" id="send_email_invite" data-loading-text="Sending...">SEND INVITATIONS</a>
	</div>
</div>