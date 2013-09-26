<?php
	echo $this->Html->css('/less/landing.less?','stylesheet/less', array('inline' => false));
	
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/Login', array('inline' => false));
}
	$this->set('title_for_layout', 'Cribspot - Every College Rental...All In One Spot');

	$this->Html->meta('keywords', 
			"off campus housing, student housing, campus apartments, college apartments, college housing, college rental, college sublet, college parking, college sublease", array('inline' => false)
		);

	$this->Html->meta('description', "Cribspot takes the pain out of finding off-campus housing on college campuses.  We display thousands of listings on a map so you can stop stressing and get back to ...studying.", array('inline' => false));
?>


<div id="header" class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container" style="width: auto;">
			<ul class="nav pull-right">
				<?php if ($this->Session->read('Auth.User.id') != 0) { ?>
					<li class="personal_menu dropdown">
						<a href="#" id="login_dropdown" role="button" class="dropdown-toggle nav-btn" data-toggle="dropdown">
							Hi <?= (intval($AuthUser['user_type']) == 0) ? $AuthUser['first_name'] : $AuthUser['company_name'] ; ?> 
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="personal_dropdown">
							<li role="presentation"><?php echo $this->Html->link('My Dashboard', array('controller' => 'dashboard', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
							<li role="presentation"><?php echo $this->Html->link('My Rentals', array('controller' => 'rentals', 'action' => 'view'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
							<li role="presentation"><?php echo $this->Html->link('My Account', array('controller' => 'users', 'action' => 'accountinfo'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
							<li role="presentation"><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
						</ul>
					</li>
				<?php } else { ?>
					<li class="menu dropdown signup_btn">
						<a href="#" id="login_dropdown" role="button" class="dropdown-toggle nav-btn" data-toggle="dropdown">Login</a>
						<div id="login_dropdown_content" class="dropdown-menu" role="menu" aria-labelledby="menu_dropdown">
							<form id="loginForm" onsubmit="return A2Cribs.Login.cribspotLogin(this);">
								<a href="#" class="fb_login" onclick="A2Cribs.FacebookManager.FacebookLogin()"><img src="/img/user/btn-facebook-login.png"></a>
								<p>** Facebook login is available for students only!</p>
								<input type="email" id="inputEmail" name="email" placeholder="Email">
								<input type="password" id="inputPassword" name="password" placeholder="Password">
								<button type="submit" id="submitButton" class="btn">Sign in</button>
								<?php echo $this->Html->link('Forgot Password?', array('controller' => 'users', 'action' => 'resetpassword'), array('class' => 'forgot_password')); ?>
							</form>
						</div>
					</li>
					<li class="nav-text">
						or
					</li>
					<li>
						<?= $this->Html->link('Sign Up', array('controller' => 'users', 'action' => 'add'), array('tabindex' => '-1', 'role' => 'menuitem', 'class' => 'nav-btn')); ?>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>

<div class="float" id="search-div">
	<img src="/img/landing/logo.png" height="200px" width="400px">
	<div id="slogan" class="text-center">
		<i class="small_font">All the </i><i class="large_font">College Rentals.</i><br/>
		<i class="small_font">All in </i><i class="large_font">One Spot.</i>
	</div>
	<div id="logo_zone">
		<div id="where_to_school">What School Do You Attend:</div>
		<!-- School Logo's go here -->
		<?php // will need to figure out how to redesign for sublets and parking! ?>
		<?php
		foreach ($locations as $university) {
			$school_name = str_replace(" ", "_", $university['University']['name']);
			if (strpos($university['University']['name'], 'Michigan') !== false) {
				echo $this->Html->link($this->Html->div('university_icon', '', array('id' => 'michigan_logo')), array('controller' => 'map', 'action' => 'rental', $school_name), array('escape' => false));
			}
			if (strpos($university['University']['name'], 'Indiana') !== false) {
				echo $this->Html->link($this->Html->div('university_icon', '', array('id' => 'indiana_logo')), array('controller' => 'map', 'action' => 'rental', $school_name), array('escape' => false));
			}
		}

		?>

	</div>
</div>

<a href="https://mixpanel.com/f/partner" id="mixpanel_link"><img src="//cdn.mxpnl.com/site_media/images/partner/badge_light.png" alt="Mobile Analytics" /></a>
<?php 
	$this->Js->buffer('
		$("#login_dropdown_content input, #login_dropdown_content label").click(function(e) {
			e.stopPropagation();
		});
	');
?>