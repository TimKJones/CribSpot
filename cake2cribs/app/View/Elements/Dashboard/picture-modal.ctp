<?= $this->Html->css('/less/Dashboard/picture-modal.less?','stylesheet/less', array('inline' => false)); ?>
<div id ="picture-modal" class="modal hide fade">
	<div class="modal-header">
		<i class="title">Photo Manager</i>
		<div id="modal-close-button" class="close" data-dismiss="modal"></div>
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
<?php 
	$this->Js->buffer('
		A2Cribs.PhotoManager = new A2Cribs.PhotoManager($("#picture-modal"));
	');
?>