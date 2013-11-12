<?php
	/* Less files for style */
	/* Eventually switch to css */
	echo $this->Html->css('/less/header.less?v=72','stylesheet/less', array('inline' => false));

	/* Datepicker and slider javascript */
	// echo $this->Html->script('bootstrap-datepicker');
	echo $this->Html->script('bootstrap-slider');
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/Login', array('inline' => false));
	echo $this->Html->script('src/PageHeader');
}
	echo $this->element('popups');
	echo $this->element('Login/login');
	echo $this->element('Login/signup');
	echo $this->element('Invitations/email_invite');
?>

<div id="header" class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container" style="width: auto;">
			<a class="header_logo" href="/"><img src="/img/header/header_logo.png"></a>
			<?php if (isset($school_name) && strpos($school_name, 'Madison')) { ?>
				<button class="promotion_on_fb btn"><img src="/img/sunglasses.png"> Free Shades</button>
			<?php } else { ?>
				<button class="share_on_fb btn">Tell my friends</button>
			<?php } ?>
			<ul class="nav pull-right">
				<li>
					<a class="review_btn btn" href="http://freeonlinesurveys.com/app/rendersurvey.asp?sid=fznns9v2mw1kd33346886&amp;refer=www%2Egoogle%2Ecom" target="_blank">Review my rental or dorm</a>
				</li>
				<li class="signup_btn"><a href="#claim_listing" data-toggle="modal">Claim a Listing</a></li>
				<?php if (isset($show_user) && $show_user) { /* Next step is to check if logged in */ ?>

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
						<a href="#" id="personal_dropdown_0" role="button" class="dropdown-toggle" data-toggle="dropdown">
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
						<a href="#" id="personal_dropdown_1" role="button" class="dropdown-toggle" data-toggle="dropdown">
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
						<a href="#" id="personal_dropdown_2" role="button" class="dropdown-toggle" data-toggle="dropdown">
							<div class='user_name'><?= $name; ?></div>
							 <b class="caret"></b>
						</a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="personal_dropdown_2">
							<li role="presentation"><?php echo $this->Html->link('My Dashboard', array('controller' => 'dashboard', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
							
							<li role="presentation"><?php echo $this->Html->link('Featured Listings', array('controller' => 'FeaturedListings', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>

							<li role="presentation"><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
						</ul>
					</li>

					<?php if (isset($show_personal) && $show_personal) { ?>
					<li class="personal_buttons <?= ($signed_in) ? '' : 'hide'; ?> ">
						<div class="personal_button">
							<a class="message_button" href="/messages/"></a>
							<div class="message_count personal_count"></div>
						</div>
						<div class="personal_button">
							<a class="favorite_button" href="#" onclick="A2Cribs.FavoritesManager.ToggleFavoritesVisibility()"></a>
							<div class="favorite_count personal_count"></div>
						</div>
					</li>
				<?php } ?>
					<li class="menu dropdown signup_btn <?= ($signed_in) ? 'hide' : '' ; ?>">
						<a href="#login_modal" role="button" data-toggle="modal">Login</a>
					</li>
					<li class="signup_btn <?= ($signed_in) ? 'hide' : '' ; ?>">
						<a class="show_signup_modal" href="#">Sign Up</a>
					</li>
				<?php } ?>
				<li class="menu dropdown">
					<a href="#" id="menu_dropdown" role="button" class="dropdown-toggle" data-toggle="dropdown">Menu <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="menu_dropdown">
						<li role="presentation"><a href="#about-page" data-toggle="modal" role="menuitem" tabindex="-1">About</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="http://blog.cribspot.com" target="_blank">Blog</a></li>
						<li role="presentation"><a href="#contact-page" data-toggle="modal" role="menuitem" tabindex="-1">Contact</a></li>
						<li role="presentation"><a href="#help-page" data-toggle="modal" role="menuitem" tabindex="-1">Help</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>

<?php
	$signed_in_text = (isset($show_user) && $show_user && $signed_in) ? "true" : "false";
	$this->Js->buffer('
		A2Cribs.Login.logged_in = ' . $signed_in_text  . '
		$("#login_dropdown_content input, #login_dropdown_content label").click(function(e) {
			e.stopPropagation();
		});
	');
	if ($this->Session->read('Auth.User.id') != 0)
		$this->Js->buffer('A2Cribs.PageHeader.renderUnreadConversationsCount();');
?>
