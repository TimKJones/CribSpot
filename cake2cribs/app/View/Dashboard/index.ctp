<?php echo $this->Html->css('dashboard'); ?>
<?php echo $this->element('header'); ?>
<?php echo $this->Html->script('src/Dashboard'); ?>
<?php echo $this->Html->css('messages'); ?>


<div class='container-fluid' id = 'main_content'>
		<div class = 'span3' id = 'left_content'>
			<?php echo $this->element('Account/account_settings_header')?>
			<?php echo $this->element('Listings/listings_header')?>
			<?php echo $this->element('Messages/conversations_header')?>
			
		</div>
		<div class = 'span6' id = 'middle_content'>
			<div class = 'messages-content hidden'>
			 	<?php echo $this->element('Messages/message_window') ?>
			</div>
		</div>
		<div class = 'span3' id = 'right_content'>
			<div class = 'messages-content hidden'>
				<?php echo $this->element('Messages/participant_info') ?>
			</div>
		</div>
</div>

<script>
	// A2Cribs.Messages.init();
	A2Cribs.Dashboard.SetupUI();
	var a = A2Cribs.Messages
	a.init(-1);
	a.setupUI();


</script>
	
