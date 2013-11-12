<?= $this->Html->css('/less/Dashboard/picture-modal.less?','stylesheet/less', array('inline' => false)); ?>
<div id ="picture-modal" class="modal hide fade" data-backdrop="static">
	<div class="modal-header">
		<i class="title">Photo Picker</i>
	</div>
	<div>
		<div class="modal-body step">
			<?php echo $this->element('photo_manager'); ?>
		</div>
		<div class="modal-footer">
			<button id="finish_photo" class="btn btn-primary pull-right next-btn">Finish</button>
		</div>
	</div>
</div>
