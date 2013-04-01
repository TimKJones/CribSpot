<div id = 'edit_account_window'>
	<form id="accountInfoForm" class="form-horizontal">
	  <div class="control-group">
	    <label class="control-label" for="first_name">First Name:</label>
	    <div class="controls">
	      <input type="text" id="first_name_input" placeholder="First Name" value = '<?php echo $user['User']['first_name'];?>'>
	    </div>
	  </div>
	  <div class="control-group">
	    <label class="control-label" for="last_name">Last Name:</label>
	    <div class="controls">
	      <input type="text" id="last_name_input" placeholder="Last Name"  value='<?php echo $user['User']['last_name']; ?>'>
	    </div>
	  </div>
	  <div class="control-group">
	  	    <div class="controls">
	  			<button id = 'save_btn' type="button" class="btn">Save changes</button>
	  		</div>
	  </div>
	</form>
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
</div>