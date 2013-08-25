<?php
	/* Less files for style */
	/* Eventually switch to css */
	echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false));

	echo $this->element('popups');

	echo $this->Html->script('src/PageHeader');
?>
<style> 
@font-face {
    font-family: 'ReklameScriptRegularDEMORg';
    src: url('/font/ReklameScript-Regular_DEMO-webfont.eot');
    src: url('/font/ReklameScript-Regular_DEMO-webfont.eot?#iefix') format('embedded-opentype'),
         url('/font/ReklameScript-Regular_DEMO-webfont.woff') format('woff'),
         url('/font/ReklameScript-Regular_DEMO-webfont.ttf') format('truetype'),
         url('/font/ReklameScript-Regular_DEMO-webfont.svg#ReklameScriptRegularDEMORg') format('svg');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'ReklameScriptRegular';
    src: url('/font/reklamescript_regular_macroman/ReklameScript-Regular-webfont.eot');
    src: url('/font/reklamescript_regular_macroman/ReklameScript-Regular-webfont.eot?#iefix') format('embedded-opentype'),
         url('/font/reklamescript_regular_macroman/ReklameScript-Regular-webfont.woff') format('woff'),
         url('/font/reklamescript_regular_macroman/ReklameScript-Regular-webfont.ttf') format('truetype'),
         url('/font/reklamescript_regular_macroman/ReklameScript-Regular-webfont.svg#ReklameScriptRegularRegular') format('svg');
    font-weight: normal;
    font-style: normal;
}


@font-face {
    font-family: 'ReklameScriptMedium';
    src: url('/font/reklamescript_medium_macromanReklameScript-Medium-webfont.eot');
    src: url('/font/reklamescript_medium_macromanReklameScript-Medium-webfont.eot?#iefix') format('embedded-opentype'),
         url('/font/reklamescript_medium_macromanReklameScript-Medium-webfont.woff') format('woff'),
         url('/font/reklamescript_medium_macromanReklameScript-Medium-webfont.ttf') format('truetype'),
         url('/font/reklamescript_medium_macromanReklameScript-Medium-webfont.svg#ReklameScriptMediumRegular') format('svg');
    font-weight: normal;
    font-style: normal;

}



</style>
<div id="header" class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container" style="width: auto;">
			<a class="header_logo" href="/"><img src="/img/header/header_logo.png"></a>
			<ul class="nav pull-right">
				<li class="signup_btn"><a href="#claim_listing" data-toggle="modal">Claim a Listing</a></li>
				<?php if (isset($show_user) && $show_user) { /* Next step is to check if logged in */ 
					if ($this->Session->read('Auth.User.id') != 0) { ?>
					<li class="personal_menu dropdown">
						<?
						$pic_url = "/img/head_large.jpg";
						if($AuthUser['facebook_id'])
							$pic_url = "https://graph.facebook.com/".$AuthUser['facebook_id']."/picture?width=80&height=80";
						?>
						<a href="#" id="personal_dropdown" role="button" class="dropdown-toggle" data-toggle="dropdown">
							<?php
							if (intval($AuthUser["user_type"]) == 0)
							{
								echo '<img src="' . $pic_url . '">';
								echo $AuthUser['first_name'];
							}
							else
								echo $AuthUser['company_name'];
							?>
							 <b class="caret"></b>
						</a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="personal_dropdown">
							<li role="presentation"><?php echo $this->Html->link('My Dashboard', array('controller' => 'dashboard', 'action' => 'index'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
							<li role="presentation"><?php echo $this->Html->link('My Rentals', array('controller' => 'rentals', 'action' => 'view'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
							<li role="presentation"><?php echo $this->Html->link('My Account', array('controller' => 'users', 'action' => 'accountinfo'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
							<li role="presentation"><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('tabindex' => '-1', 'role' => 'menuitem')); ?></li>
						</ul>
					</li>
					<?php if (isset($show_personal) && $show_personal) { ?>
					<li class="personal_buttons">
						<div class="personal_button">
							<a href="/messages/"><i class="icon-comment icon-large"></i></a>
							<div class="message_count personal_count"></div>
						</div>
						<div class="personal_button">
							<a class="favorite_button" href="#" onclick="A2Cribs.FavoritesManager.ToggleFavoritesVisibility(this)"><i class="icon-heart icon-large"></i></a>
							<div class="favorite_count personal_count"></div>
						</div>
					</li>
					<?php } ?>
				<?php } 
					else { ?>
					<li class="menu dropdown signup_btn">
						<a href="#" id="login_dropdown" role="button" class="dropdown-toggle" data-toggle="dropdown">Login</a>
						<div id="login_dropdown_content" class="dropdown-menu" role="menu" aria-labelledby="menu_dropdown">
							<form id="loginForm" onsubmit="return A2Cribs.Login.cribspotLogin(this);">
								<a href="#" class="fb_login" onclick="A2Cribs.FacebookManager.FacebookLogin()"><img src="/img/user/btn-facebook-login.png"></a>
								<input type="email" id="inputEmail" name="email" placeholder="Email">
								<input type="password" id="inputPassword" name="password" placeholder="Password">
								<button type="submit" id="submitButton" class="btn">Sign in</button>
								<?php echo $this->Html->link('Forgot Password?', array('controller' => 'users', 'action' => 'resetpassword'), array('class' => 'forgot_password')); ?>
							</form>
						</div>
					</li>
					<li class="signup_btn">
						<?= $this->Html->link('Sign Up', array('controller' => 'users', 'action' => 'add'), array('tabindex' => '-1', 'role' => 'menuitem')); ?>
					</li>
				<?php
					}
				}
				?>

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
	$this->Js->buffer('
		$("#login_dropdown_content input, #login_dropdown_content label").click(function(e) {
			e.stopPropagation();
		});
	');
	if ($this->Session->read('Auth.User.id') != 0)
		$this->Js->buffer('A2Cribs.PageHeader.renderUnreadConversationsCount();');
?>
