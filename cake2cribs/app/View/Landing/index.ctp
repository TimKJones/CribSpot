<?php
	echo $this->Html->css('/less/landing.less?','stylesheet/less', array('inline' => false));
	
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/Landing', array('inline' => false));
	echo $this->Html->script('src/Login', array('inline' => false));
}
	$this->set('title_for_layout', 'Cribspot - Every College Rental...All In One Spot');
?>


<?php 
	$this->Js->buffer('
		A2Cribs.Landing.Init(' . json_encode($locations) . ');
		$("#school-form").submit(function() { A2Cribs.Landing.Submit(); return false; });
		$("#search-text").focus();
	');
?>

<?php
	//echo $this->element('login');
	//echo $this->element('post-sublet');
	//echo $this->element('register');
	//echo $this->element('popups');
?>

<?php //echo $this->element('header', array('show_filter' => false, 'show_user' => true)); ?>

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
		<i class="small_font">EVERY </i><i class="large_font blue_font"> COLLEGE RENTAL</i><br/>
		<i class="large_font">ALL</i><i class="small_font"><i class="small_font"> IN</i><i class="large_font blue_font"> ONE SPOT</i>
	</div>
	<div>
		<form id="school-form">
			<input id="search-text" class="typeahead" placeholder="Search By University or City" type="text" autocomplete="off">
			<div id="search-submit" onclick="$(this).submit()"><i class="icon-search"></i></div>
		</form>
		<?php
		echo $this->Html->link(
			'',
			array('controller' => 'map', 'action' => 'rental'),
			array('class' => 'hide', 'id' => 'sublet-redirect')
		);
		?>

	</div>
</div>

<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
<a id="tweet_to_school" href="https://twitter.com/intent/tweet?text=The%20%40TheCribspot%20is%20%23idealideal...Bring%20it%20to%20my%20campus%21">Tweet to bring to your school!</a>
<a href="https://mixpanel.com/f/partner" id="mixpanel_link"><img src="//cdn.mxpnl.com/site_media/images/partner/badge_light.png" alt="Mobile Analytics" /></a>
<div class="fb-like-box" data-href="https://www.facebook.com/Cribspot" data-width="292" data-colorscheme="dark" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
<?php 
	$this->Js->buffer('
		$("#login_dropdown_content input, #login_dropdown_content label").click(function(e) {
			e.stopPropagation();
		});
	');
?>