<?php echo $this->Html->css('/less/Tours/schedule_tour.less?','stylesheet/less', array('inline' => false)); ?>

<div id="schedule_tour" class="tab-pane">
	<?= $this->element('Tours/calendar_picker'); ?>
	
	<?= $this->element('Tours/schedule_info'); ?>

	<?= $this->element('Tours/schedule_completed'); ?>

	<?= $this->element('email_invite'); ?>

	<?= $this->element('Tours/verify_phone'); ?>
	
</div>