<?php
	echo $this->Html->css('/less/login.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->script('src/Register');
	echo $this->Html->css('loginModal');
?>

<!-- Modal -->
<div id="signupModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-body">
		<div id="modal-top">
			<h2 id="modal-logo" class="text-center">Cribspot</h2>
		</div>
		<div id="modal-center">
			<!--<div class="facebook-login-button">Register with Facebook</div>-->
			<div id = "registerStatus"> Join Cribspot today!</div>
			<form id="registerForm">
				<input type="email" id="inputEmail" name="email" placeholder="Email">
				<input type="password" id="inputPassword" name="password" placeholder="Password">
				<input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password">
				<input type="text" id="inputFirstName" name="first_name" placeholder="First name">
				<input type="text" id="inputLastName" name="last_name" placeholder="Last name">
				<button type="submit" id="submitButton" class="btn">Register</button>
			</form>
			<?= $this->Html->link('Already Have an Account? Sign In' , array('controller' => 'users', 'action' => 'login')); ?>
		</div>
		<div id="modal-bottom">
			<div id="modal-slogan"><a href="https://twitter.com/intent/tweet?button_hashtag=SubletProbs&text=Turn%20your%20sublet%20problems%20into%20sublet%20solutions%20at%20%40TheCribspot!%20%20" class="twitter-hashtag-button" data-related="TheCribspot">Tweet #SubletProbs</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
		</div>
	</div>
</div>

<script>

var a = A2Cribs.Register;

a.setupUI();
</script>