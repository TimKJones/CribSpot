<?= $this->Html->css('/less/post-sublet.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('listing-popup-verifications', null, array('inline' => false)); ?>
<?= $this->Html->css('datepicker', null, array('inline' => false)); ?>

<?php 
echo $this->Html->script('bootstrap-datepicker');

if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/SubletSave');
	echo $this->Html->script('src/PostSublet');
	echo $this->Html->script('src/MiniMap');
}
?>

<div id ="post-sublet-modal" class="post-popup modal hide fade container-fluid">
	<div id="sublet-id" class="hide"></div>
	<div class="modal-header">
		<i class="sublet-name title">Post Your Sublet</i>
		<div class = 'progress-wrapper'>
	    	<?php echo $this->element('post-sublet-progress');?>
		</div>
		<div id="modal-close-button" class="close" data-dismiss="modal"></div>
	</div>
	<div>
		<div class="modal-body step" id="address-step" step="1">
			<?php echo $this->element('Sublet/Steps/address-step');?>
			<div class="modal-footer">
				<button class="btn btn-inverse pull-right next-btn">Next</button>
			</div>
		</div>
		<div class="modal-body step" id="info-step" step="2">
			<?php echo $this->element('Sublet/Steps/info-step');?>
			<div class="modal-footer">
				<button class="btn btn-inverse pull-left back-btn">Back</button>
				<button class="btn btn-inverse pull-right next-btn">Next</button>
			</div>
		</div>
		<div class="modal-body step" id="addinfo-step" step="3">
			<?php echo $this->element('Sublet/Steps/addinfo-step');?>
			<div class="modal-footer">
				<button class="btn btn-inverse pull-left back-btn">Back</button>
				<button class="btn btn-inverse pull-right next-btn">Next</button>
			</div>			
		</div>
		<div class="modal-body step" id="photo-step" step="4">
			<?php echo $this->Element('photo_manager');?>
			<div class="modal-footer">
				<button class="btn btn-inverse pull-left back-btn">Back</button>
				<button class="btn btn-warning pull-right post-btn">Post</button>
			</div>			
		</div>
		<div class="modal-body step" id="share-step">
			Share Step
		</div>
	</div>
</div>


<?php 
	$this->Js->buffer('
		A2Cribs.PostSublet = new A2Cribs.PostSublet();
	');
?>
