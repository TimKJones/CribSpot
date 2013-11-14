<?php
	echo $this->Html->css('/less/University/schoolpage.less?v=70','stylesheet/less', array('inline' => false));
	
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/Login', array('inline' => false));
}

	echo $this->Html->meta('canonical', 'https://cribspot.com/university/' . str_replace(" ", "_", $school_name), array('rel'=>'canonical', 'type'=>null, 'title'=>null, 'inline' => false));

	$this->set('title_for_layout', $school_name . ' Off-Campus Housing - Cribspot');

	$this->Html->meta('description', "Cribspot takes the pain out of finding off-campus housing on college campuses.  We display thousands of listings on a map so you can stop stressing and get back to ...studying.", array('inline' => false));
?>

<div id="school_page" style="background-image:url(<?= $university['background_image']; ?>)">
	<div>
		<div class="school_logo">
			<img src="<?= $university['logo_path']; ?>">
		</div>
		<div class="name"><?= $university['name']; ?></div>
	</div>
	
	<div class="school_info">
		<div class="welcome_message"><?= $university['welcome_message']; ?></div>
		<div class="description"><?= $university['description']; ?></div>
		<div class="separator"></div>
		<div class="school_facts">
			AVG RENT: $<div class="avg_rent"><?= $university['avg_rent']; ?></div>
			&nbsp;&nbsp;AVAILABLE UNITS (2014): <div class="available_units"><?= $university['available_units']; ?></div>
		</div>
	</div>
	<div class="getting_started">
		<div class="banner">Let's Get You Started...</div>
		<a href="/rental/<?= str_replace(" ", "_", $school_name); ?>" id="map_link" class="btn"><i class="icon-search icon-large"></i> See all <i class="unit_count"><?= $university['available_units']; ?></i> College Rentals</a>
		<button id="friends_invite" class="btn">Invite Your Friends or Group</button>
		<a href="/login" class="btn">Login or Sign Up</a>
	</div>
	<?php 
	if (!empty($university['founder_image']) && strlen($university['founder_image']) !== 0)
	{
	?>
	<div id="founder_box">
		<img class="founder_photo" src="<?= $university['founder_image']; ?>">
		<p><i class="founder_name"><?= $university['founder_name']; ?></i><br><i class="founder_title"><?= $university['name']; ?> Founder</i><br><i class="founder_description"><?= $university['founder_description']; ?></i><br>
		Need help or a recommendation?<br>Chat with us below or email me at <i class="founder_email"><?= $university['founder_email']; ?></i></p>
	</div>
	<? } ?>
	<img class="cribspot_logo" src="/img/landing/logo.png" height="50px" width="100px">
	<a href="<?= $university['background_source']; ?>" class="background_source"><?= $university['background_source']; ?></a>
</div>


<div id="header" class="navbar">
	<div class="navbar-inner">
		<div class="container" style="width: auto;">
			<ul class="nav pull-right">
				<?php
					$pic_url = "/img/head_large.jpg";
					$name = "Me";
					$user_type = -1;
					$signed_in = false;
					if (isset($AuthUser))
					{
						$signed_in = true;
						$user_type = $AuthUser['user_type'];
						if(array_key_exists('facebook_id', $AuthUser) && isset($AuthUser['facebook_id']))
							$pic_url = "https://graph.facebook.com/".$AuthUser['facebook_id']."/picture?width=80&height=80";
						if ($user_type == 0)
							$name = $AuthUser['first_name'];
						else
							$name = $AuthUser['company_name'];

					}
			
					?> 
				<!-- Dropdown for user type 0 -->
				<li class="personal_menu personal_menu_0 dropdown <?= ($user_type == 0) ? '' : 'hide' ; ?>">
					<a href="#" id="personal_dropdown_0" role="button" class="dropdown-toggle nav-btn" data-toggle="dropdown">
						<?php
						echo "<img src='" . $pic_url . "' >";
						echo "<div class='user_name'>" . $name . "</div>";
						?>
						 <b class="caret"></b>
					</a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="personal_dropdown_0">
						<li role="presentation"><?php echo $this->Html->link('My Dashboard', array('controller' => 'dashboard', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>

						<li role="presentation"><?php echo $this->Html->link('My Account', array('controller' => 'users', 'action' => 'accountinfo'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>

						<li role="presentation"><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
					</ul>
				</li>


				<!-- Dropdown for user type 1 -->
				<li class="personal_menu personal_menu_1 dropdown <?= ($user_type == 1) ? '' : 'hide' ; ?>">
					<a href="#" id="personal_dropdown_1" role="button" class="dropdown-toggle nav-btn" data-toggle="dropdown">
						<div class='user_name'><?= $name; ?></div>
						 <b class="caret"></b>
					</a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="personal_dropdown_1">
						<li role="presentation"><?php echo $this->Html->link('My Dashboard', array('controller' => 'dashboard', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
						<li role="presentation"><?php echo $this->Html->link('My Rentals', array('controller' => 'rentals', 'action' => 'view'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>

						<li role="presentation"><?php echo $this->Html->link('My Account', array('controller' => 'users', 'action' => 'accountinfo'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>

						<li role="presentation"><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
					</ul>
				</li>

				<!-- Dropdown for user type 2 -->
				<li class="personal_menu personal_menu_2 dropdown <?= ($user_type == 2) ? '' : 'hide' ; ?>">
					<a href="#" id="personal_dropdown_2" role="button" class="dropdown-toggle nav-btn" data-toggle="dropdown">
						<div class='user_name'><?= $name; ?></div>
						 <b class="caret"></b>
					</a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="personal_dropdown_2">
						<li role="presentation"><?php echo $this->Html->link('My Dashboard', array('controller' => 'dashboard', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
						
						<li role="presentation"><?php echo $this->Html->link('Featured Listings', array('controller' => 'FeaturedListings', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>

						<li role="presentation"><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
					</ul>
				</li>

				<li class="menu dropdown signup_btn <?= ($signed_in) ? 'hide' : '' ; ?>">
					<a class="nav-btn" href="#login_modal" role="button" data-toggle="modal">Login</a>
				</li>
				<li class="nav-text <?= ($signed_in) ? 'hide' : '' ; ?>">
					or
				</li>
				<li class="signup_btn <?= ($signed_in) ? 'hide' : '' ; ?>">
					<a class="nav-btn show_signup_modal" href="#">Sign Up</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("#friends_invite").click(function() {
		A2Cribs.MixPanel.Event("Invite Friends", null);
		FB.ui({
			method: 'apprequests',
			message: 'Join the Movement. All the College Rentals. All in One Spot.'
		});
	});
</script>

<?php
	echo $this->element('Login/login');
	echo $this->element('Login/signup', array('locations' => $locations, 'user_years' => $user_years));
?>