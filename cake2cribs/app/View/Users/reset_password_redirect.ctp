<?php echo $this->Html->script('src/Account'); ?>
<form id="passwordForm" class="form-horizontal">
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
	  			<button id="changePasswordButton" type="button" class="btn">Change password</button>
	  		</div>
	  </div>
</form>


<?php
$this->Js->buffer('
	$("#changePasswordButton").click(function(){
		A2Cribs.Account.ChangePassword($("#changePasswordButton"), $("#new_password").val(), $("#confirm_password").val(), "'
			. $id . '","' . $reset_token . '")})'
);

?>