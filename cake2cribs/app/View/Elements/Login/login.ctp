<?php echo $this->Html->css('/less/signup_modal.less?','stylesheet/less', array('inline' => false)); ?>

<div class="modal register_modal hide fade" id="login_modal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Log In to Cribspot</h3>
	</div>
	<div class="modal-body fluid-container">
		<div class="row-fluid">
			<button class="span12 button fb-login" data-loading-text="Loading FB info..."><i class="icon-facebook-sign icon-large"></i>&nbsp;&nbsp;Log in with Facebook</button>
		</div>
		<div class="row-fluid">
			<p class="login-separator">Or log in using your email</p>
		</div>
		<form id="loginForm" onsubmit="return A2Cribs.Login.cribspotLogin(this);">
			<div class="row-fluid">
				<input class="span12" id="inputEmail" type="email" placeholder="Email">
			</div>
			<div class="row-fluid">
				<input class="span12" id="inputPassword" type="password" placeholder="Password">
			</div>
			<div class="row-fluid">
				<button type="submit" class="span12 button signup-button" data-loading-text="Logging in...">Log in</button>
			</div>
		</form>

	</div>
	<div class="modal-footer fluid-container">
		<div class="row-fluid">
			<p>Don't have an account? <a class="show_signup_modal" href="#">Sign up!</a></p>
		</div>
		<div class="row-fluid">
			<p><a href="/users/resetpassword">Forgot your password?</a></p>
		</div>
	</div>
</div>

<?php 
    $this->Js->buffer('
        A2Cribs.Login.LoginModalSetupUI();
    ');
?>