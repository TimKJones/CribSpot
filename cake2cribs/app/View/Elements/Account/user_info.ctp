
<div class = 'shadowed' id = 'user_info'>
	<div id = 'user_pic_large'>
		<?php 

		if($user['User']['facebook_id'] != null){
			echo "<img class = '' src='https://graph.facebook.com/". $user['User']['facebook_id'] ."/picture?width=480'></img>";
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
		<i class = 'icon-envelope-alt'></i>Email Address<i class = 'veridd unverified icon-remove-sign'></i>
	</div>
</div>
