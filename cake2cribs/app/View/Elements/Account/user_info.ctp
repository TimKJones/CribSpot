
<div class = 'shadowed' id = 'user_info'>
	<div id = 'user_pic_large'>
		<?php 

		if($user['User']['facebook_userid'] != null){
			echo "<img class = '' src='https://graph.facebook.com/". $user['User']['facebook_userid'] ."/picture?width=480'></img>";
		}
		else{
			// echo "<img class = 'sprite-head_large'></img>";
			echo "<img src = '/img/head_large.jpg'></img>";
		}

		?>
		
	</div>
	
</div>

<div id = 'my-verification-panel'>
	<div id = 'veri-email'>
		<i class = 'icon-envelope-alt'></i>Email Address<i class = 'veridd unverified icon-ok-sign'></i>
	</div>
	<div class = 'veri-dropdown'>
		<a>Send Verification Email Again?</a>
	</div>
	<div id = 'veri-edu'>
		<img src = '/img/university_icon.png' class = 'icon-edu'><?php 
		if($user['University']['id']){
			echo $user['University']['name'];
		}
		else{
			echo 'University <a href="/users/verifyUniversity">Verify?</a>';
	      	

		}
		?><i class = 'unverified icon-ok-sign'></i>
	</div>
	<div id = 'veri-fb'>
		<i class = 'fb icon-facebook-sign'></i>Facebook<i class = 'unverified icon-ok-sign'></i>
	</div>
	<div id = 'veri-tw'>
		<i class = 'tw icon-twitter-sign'></i>Twitter <i class = 'unverified icon-ok-sign'></i>
	</div>
</div>
