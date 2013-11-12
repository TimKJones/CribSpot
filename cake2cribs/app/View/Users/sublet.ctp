<div id="user_welcome_page">
	<div class="background">
		<div class="row-fluid">
			<div class="span7 text-center span">
				<div class="slogan">
					<span>A <span class="large"><strong>FREE</strong></span> Service for Sublets to Reach <span class="blue large"><strong>17,558</strong></span> Renters</span>
				</div>
			</div>
			<div class="span5 span">
				<div id="login_signup" class="login_box">
					<div class="welcome_box">
						<div class="welcome_text">Welcome!</div>
						<div class="message_block">Create an account or log in to post your sublet today:</div>
					</div>
					<form id="student_signup" class="user_form">
						<div class="row-fluid fb-row">
							<button class="btn fb_login_btn" data-loading-text="Saving..." type="button"><i class="icon-facebook-sign icon-large"></i>&nbsp;&nbsp;SIGN UP WITH FACEBOOK</button>
						</div>
						<div class="row-fluid fb-divider text-center"><em>&nbsp;or&nbsp;</em></div>
						<div class="row-fluid">
							<input id="student_first_name" class="span6" type="text" placeholder="First Name">
							<input id="student_last_name" class="span6" type="text" placeholder="Last Name">
						</div>
						<div class="row-fluid">
							<input id="student_email" class="span12" type="email" placeholder="Email">
						</div>
						<div class="row-fluid">
							<input id="student_password" class="span12" type="password" placeholder="Password">
						</div>
						<div class="row-fluid">
							<p>By signing up you confirm that you accept the <a href="/TermsOfUse" target="_blank">Terms of Use</a> and the <a href="/PrivacyPolicy" target="_blank">Privacy Policy</a></p>
						</div>
						<div class="row-fluid">
							<button type="submit" class="btn" data-loading-text="Saving...">SIGN UP</button>
						</div>
						<div class="row-fluid welcome_footer text-center">
							Already have an account? <a class="show_login_modal" href="#">Login!</a></p>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="pm_display">
		<div class="pm_display_header">See who else is already using Cribspot...</div>
		<div class="pm_list">
			<div class="pm_card">
				<img src="">
				<div class="pm_name">Wickfield Properties</div>
			</div>
			<div class="pm_card">
				<img src="">
				<div class="pm_name">Wickfield Properties</div>
			</div>
			<div class="pm_card">
				<img src="">
				<div class="pm_name">Wickfield Properties</div>
			</div>
			<div class="pm_card">
				<img src="">
				<div class="pm_name">Wickfield Properties</div>
			</div>
		</div>
	</div>-->
</div>

<?= $this->element('header', array('show_filter' => false, 'show_user' => false)); ?>
<?= $this->Html->css('/less/User/welcome.less?v=6','stylesheet/less', array('inline' => false));?>
<?php
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	$this->Html->script('src/VerifyManager', array('inline' => false));
	$this->Html->script('src/Account', array('inline' => false));
}
?>
<?php $this->Js->buffer('A2Cribs.Account.setupUI();'); ?>
