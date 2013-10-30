<div id = 'edit_account_window'>

<?php
	if ($AuthUser['user_type'] == 0) {
?>
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
	  			<button id = 'changeFirstLastNameButton' type="button" class="btn" maxlength="200" data-loading-text="Saving...">Save changes</button>
	  		</div>
	  </div>
	</form>
	<form id="emailForm" class="form-horizontal">
	  <div class="control-group">
	    <label class="control-label" for="new_password">Email:</label>
	    <div class="controls">
	      <input type="email" id="new_email" value = '<?= $user['User']['email'] ?>'>
	    </div>
	  </div>
	  <div class="control-group">
	    <div class="controls">
			<button id="changeEmailButton" type="button" class="btn" data-loading-text="Saving...">Save Email</button>
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
	  			<button id="changePasswordButton" type="button" class="btn" data-loading-text="Saving...">Change password</button>
	  		</div>
	  </div>
	</form>

<?php
	} else if ($AuthUser['user_type'] == 1) {
?>

	<form id="companyNameForm" class="form-horizontal">
	  <div class="control-group">
	    <label class="control-label" for="first_name">Company Name:</label>
	    <div class="controls">
	      <input type="text" id="company_name_input" placeholder="Company Name" value = "<?php echo $user['User']['company_name'];?>">
	    </div>
	  </div>
	  <div class="control-group">
	  	    <div class="controls">
	  			<button id = 'changeCompanyNameBtn' type="button" class="btn" data-loading-text="Saving...">Save Company Name</button>
	  		</div>
	  </div>
	</form>
	<form id="addressForm" class="form-horizontal">
	  <div class="control-group">
	    <label class="control-label" for="first_name">Leasing Office Address:</label>
	    <div class="controls address_controls">
	    	<div class="fluid-row">
	    		<input class="span12" type="text" id="street_address_input" placeholder="Street Address" value = '<?php echo $user['User']['street_address'];?>'>
	    	</div>
	    	<div class="fluid-row">
	    		<input class="span9" type="text" id="city_address_input" placeholder="City" value = '<?php echo $user['User']['city'];?>'>
	    		<select class="span3">
	    			<option>MI</option>
	    		</select>
	    	</div>

	    </div>
	  </div>
	  <div class="control-group">
	  	    <div class="controls">
	  			<button id = 'changeAddressBtn' type="button" class="btn" data-loading-text="Saving...">Save Address</button>
	  		</div>
	  </div>
	</form>
	<form id="pm_passwordForm" class="form-horizontal">
	  <div class="control-group">
	    <label class="control-label" for="phone">Phone Number:</label>
	    <div class="controls">
	      <input type="text" id="phone_input" placeholder="Phone" value = '<?php echo $user['User']['phone'];?>'>
	    </div>
	  </div>
	  <div class="control-group">
	  	    <div class="controls">
	  			<button id="changePhoneBtn" type="button" class="btn" data-loading-text="Saving...">Save Phone</button>
	  		</div>
	  </div>
	</form>
	<form id="pm_passwordForm" class="form-horizontal">
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
	  			<button id="changePasswordButton" type="button" class="btn" data-loading-text="Saving...">Change password</button>
	  		</div>
	  </div>
	</form>
<?php
	}
?>
</div>