<?php
	echo $this->Html->css('/less/landing.less?v=77','stylesheet/less', array('inline' => false));
	
	if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
		echo $this->Html->script('src/Login', array('inline' => false));
		echo $this->Html->script('src/Landing', array('inline' => false));
	}
	$this->set('title_for_layout', 'Off-Campus Housing & Apartments for College Students | Cribspot');
	$url = "https://cribspot.com";
	$description = "College housing can be messy. Cribspot is cleaning it up. We’ve mapped thousands of off-campus rentals so you can stop stressing and get back to “studying.”";
	$this->set('meta_description', $description);
	$this->set('canonical_url', $url);

	$this->Html->meta('description', $description, array('inline' => false));

	echo $this->Html->meta('canonical', $url, array('rel'=>'canonical', 'type'=>null, 'title'=>null, 'inline' => false));

?>


<div id="landing_page">
	<div class="fb-like" data-href="https://www.facebook.com/Cribspot" data-width="450" data-layout="button_count" data-show-faces="false" data-send="false"></div>
	<div id="mobile-div" class="text-center float">
		<img src="/img/landing/logo.png" alt="Cribspot Off-Campus Housing">
		<select id="" class="mobile_selector selector">
			<option value="">Select your University</option>
			<?php
			foreach ($locations as $university) {
				$school_name = str_replace(" ", "_", $university['University']['name']);
				echo "<option value='" . $university['University']['id'] . "'>" . $university['University']['name'] . "</option>";
			}

			?>	
		</select>
	</div>
	<div class="float" id="search-div">
		<img src="/img/landing/logo.png" height="200px" width="400px">
		<h1 id="slogan" class="text-center">
			<i class="large_font">Thousands</i>
			<i class="small_font"> of College </i><i class="large_font">Rentals.</i><br/>
			<i class="small_font">All in </i><i class="large_font">One Spot.</i>
		</h1>
		<div id="logo_zone" class="text-center">
			<h2 id="where_to_school">Stuck in your off-campus housing search? Join the movement!</h2>
			<h3>Start by selecting your University:</h3>
			<?php // will need to figure out how to redesign for sublets and parking! ?>
			<select id="school_selector" class="selector" name="">
				<option value="">Select your University</option>
			<?php
			foreach ($locations as $university) {
				$school_name = str_replace(" ", "_", $university['University']['name']);
				echo "<option value='" . $university['University']['id'] . "'>" . $university['University']['name'] . "</option>";
			}

			?>

			</select>
		</div>
	</div>
</div>

<div id="school_page">
	<?php
		foreach ($locations as $university) {
			echo '<img alt="' . $university['University']['name'] . '" data-university="' . $university['University']['id'] . '" class="school_background" src="' . $university['University']['background_image'] . '">';
		}
	?>
	<div class="school_header">
		<div class="school_logo"></div>
		<div class="name">College Housing made easy.</div>
		<img class="cribspot_logo" src="/img/landing/logo.png" alt="Cribspot Logo" height="50px" width="100px">
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
	<div id="founder_box">
		<img class="founder_photo" src="/img/founders/michigan.png" alt="Cribspot Founder">
		<p><i class="founder_name">Evan Dancer</i><br><i class="founder_title">University of Michigan Founder</i><br><i class="founder_description">Class of 2013</i><br>
		Need help or a recommendation?<br>Chat with us below or email me at <i class="founder_email">evan@cribspot.com</i></p>
	</div>
	<a href="#" class="background_source"></a>
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
						echo "<img src='" . $pic_url . "' alt='Cribspot Profile Default'>";
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

<a href="https://mixpanel.com/f/partner" id="mixpanel_link"><img src="//cdn.mxpnl.com/site_media/images/partner/badge_light.png" alt="Mixpanel Mobile Analytics" /></a>
<?php 

	echo $this->element('Login/login');
	echo $this->element('Login/signup');
	echo $this->element('Invitations/email_invite');

	$this->Js->buffer('
		A2Cribs.Landing.Init(' . json_encode($locations) . ');
		$("#login_dropdown_content input, #login_dropdown_content label").click(function(e) {
			e.stopPropagation();
		});
	');
?>
