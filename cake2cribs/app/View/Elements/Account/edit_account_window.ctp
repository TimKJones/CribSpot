<div id = 'edit_account_window'>
	<form class="form-horizontal">
	  <div class="control-group">
	    <label class="control-label" for="university">University:</label>
	    <div class="controls">
	    	<p>
	      <?php 
	      if ($user['University']['id']!=NULL)
	      {
	      	echo $user['University']['name'];
	      }
	      else
	      {
	      	echo '<a href="/users/verifyUniversity">Verify your university</a>';
	      }
	      ?>
	    </div>
	  </div>
	  <div class="control-group">
	    <label class="control-label" for="first_name">First Name:</label>
	    <div class="controls">
	      <input type="text" id="first_name_input" placeholder="First Name" value = '<?php echo $user['first_name'];?>'>
	    </div>
	  </div>
	  <div class="control-group">
	    <label class="control-label" for="last_name">Last Name:</label>
	    <div class="controls">
	      <input type="text" id="last_name_input" placeholder="Last Name">
	    </div>
	  </div>
	  <div class="control-group">
	  	    <div class="controls">
	  			<button type="submit" class="btn">Sign in</button>
	  		</div>
	  </div>
	</form>
	<?php debug($user);?>
</div>