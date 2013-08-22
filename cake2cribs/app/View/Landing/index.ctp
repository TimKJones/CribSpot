<?php
	echo $this->Html->css('/less/landing.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->script('src/Landing');
	$this->set('title_for_layout', 'Cribspot - Simple and Secure Subletting.');
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
				<li clas="">
					<li class="menu dropdown signup_btn">
						<a href="#" id="login_dropdown" role="button" class="dropdown-toggle nav-btn" data-toggle="dropdown">Login</a>
						<div id="login_dropdown_content" class="dropdown-menu" role="menu" aria-labelledby="menu_dropdown">
							<form id="loginForm" onsubmit="return A2Cribs.Login.cribspotLogin(this);">
								<input type="email" id="inputEmail" name="email" placeholder="Email">
								<input type="password" id="inputPassword" name="password" placeholder="Password">
								<button type="submit" id="submitButton" class="btn">Sign in</button>
							</form>
						</div>
					</li>
					<li class="nav-text">
						or
					</li>
					<li>
						<?= $this->Html->link('Sign Up', array('controller' => 'users', 'action' => 'add'), array('tabindex' => '-1', 'role' => 'menuitem', 'class' => 'nav-btn')); ?>
					</li>
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="float" id="search-div">
	<img src="/img/landing/logo.png" height="200px" width="400px">
	<div id="slogan" class="text-center">
		<i class="small_font">Every</i><i class="large_font blue_font"> college rental</i>
		<i class="large_font">All</i><i class="small_font"> in</i><i class="large_font blue_font"> one place</i>
	</div>
	<div>
		<form id="school-form">
			<input id="search-text" class="typeahead" placeholder="Search By University or City" type="text" autocomplete="off">
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
<a id="tweet_to_school" href="https://twitter.com/intent/tweet?text=This%20%40TheCribspot%20is%20%23idealideal...Please%20bring%20it%20to%20my%20campus%20%23makeithappen">Tweet to bring to your school!</a>
<div class="fb-facepile" data-href="http://facebook.com/FacebookDevelopers" data-action="Comma separated list of action of action types" data-width="300" data-max-rows="2" data-colorscheme="dark"></div>

<?php 
	$this->Js->buffer('
		$("#login_dropdown_content input, #login_dropdown_content label").click(function(e) {
			e.stopPropagation();
		});
	');
?>