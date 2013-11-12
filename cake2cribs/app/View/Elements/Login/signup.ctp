<?php echo $this->Html->css('/less/signup_modal.less?','stylesheet/less', array('inline' => false)); ?>

<div class="modal register_modal hide fade" id="signup_modal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 class="signup_message">Sign up for Cribspot</h3>
	</div>
	<div class="modal-body fluid-container">
		<div class="row-fluid">
			<button class="span12 button fb-login" data-loading-text="Loading FB info..."><i class="icon-facebook-sign icon-large"></i>&nbsp;&nbsp;Sign up with Facebook</button>
		</div>
		<div class="fb-signup-welcome row-fluid hide">
			<img class="fb-image pull-left" src="https://graph.facebook.com/552918161/picture?width=80&amp;height=80">
			<div class="fb-complete-signup">
				Welcome <i class="fb-name">Billy</i>!
				<br>
				Please complete the fields.
			</div>
		</div>
		<div class="row-fluid">
			<p class="login-separator">Or signup using your email</p>
		</div>
		<form>
			<div class="row-fluid">
				<input id="student_first_name" class="span6" type="text" placeholder="First Name">
				<input id="student_last_name" class="span6" type="text" placeholder="Last Name">
			</div>
			<div class="row-fluid">
				<input id="student_email" class="span12" type="email" placeholder="Email">
			</div>
			<div class="row-fluid">
				<input id="student_password" class="span12" type="password" placeholder="Password">
			</div>
			<div class="row-fluid">
				<p>By signing up you confirm that you accept the <a href="/TermsOfUse" target="_blank">Terms of Use</a> and the <a href="/PrivacyPolicy" target="_blank">Privacy Policy</a></p>
			</div>
			<div class="row-fluid">
				<button type="submit" class="span12 button signup-button" data-loading-text="Signing up...">Sign Up</button>
			</div>
		</form>
	</div>
	<div class="modal-footer fluid-container">
		<div class="row-fluid">
			<p>Property Manager? Create an account <a href="/signup/pm">here.</a><br>
				Already have an account? <a class="show_login_modal" href="#">Login!</a></p>
		</div>
	</div>
</div>