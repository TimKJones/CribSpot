<?php
	echo $this->Html->css('/less/login.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->script('src/Register');
?>

<!-- Modal -->
<div id="signupModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-body">
		<div id="modal-top">
			<h2 id="modal-logo" class="text-center">CribSpot</h2>
		</div>
		<div id="modal-center">
			<!--<div class="facebook-login-button">Register with Facebook</div>-->
			<div id = "registerStatus"> Join CribSpot today!</div>
			<form id="registerForm">
				<input type="email" id="inputEmail" name="email" placeholder="Email">
				<input type="password" id="inputPassword" name="password" placeholder="Password">
				<input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password">
				<input type="text" id="inputFirstName" name="first_name" placeholder="First name">
				<input type="text" id="inputLastName" name="last_name" placeholder="Last name">
				<button type="submit" id="submitButton" class="btn">Register</button>
			</form>
		</div>
		<div id="modal-bottom">
			<div id="modal-slogan">#SUBLETPROBS</div>
		</div>
	</div>
</div>

<script>

var a = A2Cribs.Register;

a.setupUI();
</script>