<?php
	echo $this->Html->css('/less/login.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->script('src/Login');
?>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-body">
		<div id="modal-top">
			<h2 id="modal-logo" class="text-center">CribSpot</h2>
		</div>
		<div id="modal-center">
			<!--<div class="facebook-login-button">Login with Facebook</div>-->
			<div id = "loginStatus"> Please login.</div>
			<form id="loginForm">
				<input type="email" id="inputEmail" name="email" placeholder="Email">
				<input type="password" id="inputPassword" name="password" placeholder="Password">
				<button type="submit" id="submitButton" class="btn">Sign in</button>
			</form>
		</div>
		<div id="modal-bottom">
			<div id="modal-slogan">#SUBLETPROBS</div>
		</div>
	</div>
</div>

<script>

var a = A2Cribs.Login;

a.setupUI();
</script>