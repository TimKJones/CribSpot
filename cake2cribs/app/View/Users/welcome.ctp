<div id="user_welcome_page">
	<div class="background">
		<div class="row-fluid">
			<div class="span7 text-center span">
				<div class="slogan">
					<span>A <span class="large"><strong>FREE</strong></span> Service for Property Managers to Reach <span class="blue large"><strong>27,265</strong></span> College Renters</span>
				</div>
			</div>
			<div class="span5 span">
				<div class="password_box">
					<div class="welcome_box">
						<div class="welcome_text">Welcome!</div>
						<div class="message_block">Join the <span class="pm_count">365</span> Property Managers already using Cribspot by creating you password below:</div>
					</div>
					<div class="user_form">
						<input id="u_id" type="hidden" value="<?= $id ?>">
						<input id="reset_token" type="hidden" value="<?= $reset_token ?>">
						<input id="new_password" type="password" placeholder="Password">
						<input id="confirm_password" type="password" placeholder="Confirm Password">
						<button id="changePasswordButton" type="submit" class="btn" data-loading-text="Saving...">SET PASSWORD</button>
					</div>
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
<?= $this->Html->css('/less/User/welcome.less?v=5','stylesheet/less', array('inline' => false));?>
<?= $this->Html->script('src/Account', array('inline' => false)); ?>
<?php $this->Js->buffer('A2Cribs.Account.setupUI();'); ?>
