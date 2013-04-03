
<?php
	//echo $this->Html->css('/less/login.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->script('src/Account');
	echo $this->Html->script('src/Login');
?>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-body">
		<div id="modal-top">
			<h2 id="modal-logo" class="text-center">Cribspot</h2>
		</div>
		<div id="modal-center">
			<!--<div class="facebook-login-button">Login with Facebook</div>-->
				<form id="passwordForm" action="ajaxChangePassword" method="POST" class="form-horizontal">
				  <div class="control-group">
				    <label class="control-label" for="new_password">New Password:</label>
				    <div class="controls">
				      <input type="password" id="new_password">
				    </div>
				  </div>
				  <div class="control-group">
				    <label class="control-label" for="confirm_password">Confirm Password:</label>
				    <div class="controls">
				      <input type="password" id="confirm_password">
				    </div>
				  </div>
				  <div class="control-group">
				  	    <div class="controls">
				  			<button id="ResetPasswordButton" type="button" class="btn">Change password</button>
				  		</div>
				  </div>
			</form>
		</div>
	</div>
</div>

<script>

var a = A2Cribs.Login;

a.setupUI();
$(document).ready(function () {
	var id = <?php echo $id; ?>;
	var reset_token = "<?php echo $reset_token; ?>";
	$("#ResetPasswordButton").click(function() {
		A2Cribs.Account.ChangePassword($("#ResetPasswordButton"), $("#new_password").val(), $("#confirm_password").val(), id, reset_token, '/users/login?password_changed=true');
	    //document.location.href = '/users/login?password_reset_redirect=true';
	});
});
</script>