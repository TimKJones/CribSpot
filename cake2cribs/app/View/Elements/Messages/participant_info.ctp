<div class = 'shadowed' id = 'participant_info'>
	<div id = 'participant_pic_large'>
		<img id = 'p_pic' src="/img/head_large.jpg"></img>
	</div>
	
	<div>
		<p id= 'participant_name' class = 'from_participant'></p>
		<p class = 'participant-university'></p>
	</div>
	
	<div id = 'verification-panel'>
		<div id = 'veri-email'>
			<i class = 'icon-envelope-alt'></i>Email Address<i class = 'unverified icon-ok-sign'></i>
		</div>
		<div id = 'veri-edu'>
			<img src = '/img/university_icon.png' class = 'icon-edu'></img><span class ='participant-university'></span><i class = 'unverified icon-ok-sign'></i>
		</div>
		<div id = 'veri-fb'>
			<i class = 'fb icon-facebook-sign'></i>Facebook <span id ='participant-friends'></span><i class = 'unverified icon-ok-sign'></i>
		</div>
		<div id = 'veri-tw'>
			<i class = 'tw icon-twitter-sign'></i>Twitter <span href = '#' id ='participant-followers'></a><i class = 'unverified icon-ok-sign'></i>
		</div>
	</div>
</div>
<div>
	<p class = 'pull-right' id = 'meaning'> What does this mean? </p>
	<br>
	<p id = 'hidden-meaning'>
		The panel shows whether the user has verified their email, their .edu address, their facebook, and their twitter.
	</p>
	<?php 

		$email = $user['User']['verified'] == 1;
		$edu = $user['User']['university_verified'] == 1;
		$fb = $user['User']['facebook_userid'] != null;
		$tw = $user['User']['twitter_userid'] != null;

		if(!($email && $edu && $fb && $tw)){
		?>
			<div id = 'user_unverified'>
				<i class = 'icon-exclamation-sign'></i>
				<div>
					<span>You have not verified your:</span>
					<br>
					<span>
						<?php 
						if(!$email){
							echo "email ";
						}
						if(!$edu){
							echo ".Edu ";
						}
						if(!$fb){
							echo "Facebook ";
						}
						if(!$tw){
							echo "Twitter ";
						}
						?>
					</span>
				</div>		
			</div>
		<?php
		}

	?>



</div>
