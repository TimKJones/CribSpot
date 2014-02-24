<?php echo $this->element('header', array('show_filter' => false, 'show_user' => true)); ?>
<?php echo $this->element('Dashboard/marker-modal', $dropdowns);?>
<?php echo $this->element('Dashboard/picture-modal');?>
<?php echo $this->Html->css('messages.css?v=2'); ?>
<?php echo $this->Html->css('account.css?v=2'); ?>
<?php echo $this->Html->css('dashboard.css?v=2'); ?>

<?php
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/Dashboard');
	echo $this->Html->script('src/Account');
	echo $this->Html->script('src/Message');
	echo $this->Html->script('src/PropertyManagement');
	echo $this->Html->script('src/VerifyManager');
	echo $this->Html->script('src/SubletSave');
	echo $this->Html->script('src/QuickRental');
	echo $this->Html->script('src/UserCache', array('inline' => false));
}

echo $this->Html->script('underscore');
?>

<?php $this->set('title_for_layout', 'Dashboard'); ?>

<div class='container-fluid' id = 'main_content'>
	<div class = 'row-fluid'>
		<div id = 'left_content' class = 'span3'>
			<?php echo $this->element('Dashboard/navigation-bar'); ?>			
		</div>

		<div id = 'middle_content' class = 'span9'>
			<div class = 'overview-content'>
				<?php echo $this->element('Dashboard/rental_quickedit', $user) ?>
			</div>

			<div class = 'row-fluid account-content hidden'>
				<div class = 'span9'>
					<?php echo $this->element('Account/edit_account_window', $user) ?>
				</div>
				<div class = 'span3'>
					<?php echo $this->element('Account/user_info') ?>
				</div>

			</div>

			<div class = 'row-fluid messages-content hidden'>
				<div class = 'span9 message-display'>
					<?php echo $this->element('Messages/message_window') ?>
				</div>
				<div class = 'span3'>
					<?php echo $this->element('Messages/participant_info') ?>
				</div>
			</div>

			<div class = 'rental-content hidden'>
				<?php echo $this->element('Dashboard/rentals_window', $user) ?>
			</div>

			<div class = 'sublet-content hidden'>
				<?php echo $this->element('Dashboard/sublets_window', $user) ?>
			</div>

			<div class = 'row-fluid featuredlisting-content hidden'>
				<?php echo $this->element('FeaturedListings/dashview') ?>
			</div>

		</div>
	</div>
</div>
<input type="hidden" name="" id="user_info_json" value="<?= htmlspecialchars($user_json) ?>" />

<script>

	var directive = <?php echo $directive;?>;

</script>
	
