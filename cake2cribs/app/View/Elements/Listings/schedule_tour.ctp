<?php echo $this->Html->css('/less/Tours/schedule_tour.less?','stylesheet/less', array('inline' => false)); ?>

<div id="schedule_tour" class="tab-pane">
	<?= $this->element('Tours/calendar_picker'); ?>
	
	<?= $this->element('Tours/schedule_info'); ?>
	
</div>