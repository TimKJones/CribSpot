<?php
	echo $this->Html->css('/less/landing.less?v=69','stylesheet/less', array('inline' => false));
	
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/Login', array('inline' => false));
	echo $this->Html->script('src/Landing', array('inline' => false));
}
	$this->set('title_for_layout', 'Cribspot - Every College Rental...All In One Spot');

	$this->Html->meta('keywords', 
			"off campus housing, student housing, campus apartments, college apartments, college housing, college rental, college sublet, college parking, college sublease", array('inline' => false)
		);

	$this->Html->meta('description', "Cribspot takes the pain out of finding off-campus housing on college campuses.  We display thousands of listings on a map so you can stop stressing and get back to ...studying.", array('inline' => false));

	echo $this->element('Login/login');
	echo $this->element('Login/signup', array('locations' => $locations, 'user_years' => $user_years));
?>


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

<div id="landing_page">
	<div class="fb-like" data-href="https://www.facebook.com/Cribspot" data-width="450" data-layout="button_count" data-show-faces="false" data-send="false"></div>
	<div class="float" id="search-div">
		<img src="/img/landing/logo.png" height="200px" width="400px">
		<div id="slogan" class="text-center">
			<i class="small_font">All the </i><i class="large_font">College Rentals.</i><br/>
			<i class="small_font">All in </i><i class="large_font">One Spot.</i>
		</div>
		<div id="logo_zone">
			<div id="where_to_school">Join the Movement! Start by Selecting your University:</div>
			<!-- School Logo's go here -->
			<?php // will need to figure out how to redesign for sublets and parking! ?>
			<?php
			foreach ($locations as $university) {
				$school_name = str_replace(" ", "_", $university['University']['name']);
				echo $this->Html->link("<img class='unselected_university' src='" . $university['University']['logo_unselected_path'] . "''><img class='university_icon' src='" . $university['University']['logo_path'] . "''>", "#", array('escape' => false, 'data-university' => $university['University']['id'], "class" => "university_link"));
			}

			?>

		</div>
	</div>
</div>

<div id="school_page">
	<?php
		foreach ($locations as $university) {
			echo '<img data-university="' . $university['University']['id'] . '" class="school_background" src="' . $university['University']['background_image'] . '">';
		}
	?>
	<div>
		<div class="school_logo"></div>
		<div class="name">College Housing made easy.</div>
		<img class="cribspot_logo" src="/img/landing/logo.png" height="50px" width="100px">
	</div>
	
	<div class="school_info">
		<div class="welcome_message">Welcome to Cribspot!</div>
		<div class="description"></div>
		<div class="separator"></div>
		<div class="school_facts">
			AVG RENT: $<div class="avg_rent">NA</div>
			&nbsp;&nbsp;AVAILABLE UNITS (2014): <div class="available_units">NA</div>
		</div>
	</div>
	<div class="getting_started">
		<div class="banner">Let's Get You Started...</div>
		<a href="#" id="map_link" class="btn"><i class="icon-search icon-large"></i> See all <i class="unit_count"></i> College Rentals</a>
		<button id="friends_invite" class="btn">Invite Your Friends or Group</button>
		<a href="/login" class="btn">Login or Sign Up</a>
	</div>
	<a href="#" class="background_source"></a>
</div>

<a href="https://mixpanel.com/f/partner" id="mixpanel_link"><img src="//cdn.mxpnl.com/site_media/images/partner/badge_light.png" alt="Mobile Analytics" /></a>
<?php 
	$this->Js->buffer('
		A2Cribs.Landing.Init(' . json_encode($locations) . ');
		$("#login_dropdown_content input, #login_dropdown_content label").click(function(e) {
			e.stopPropagation();
		});
	');
?>