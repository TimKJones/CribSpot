<?= $this->Html->css('/less/edit_sublet.less?','stylesheet/less', array('inline' => false)); ?>
<?= $this->Html->script('src/EditSublet'); ?>
<div id="edit_sublet_window">
	<div class="row-fluid" style="text-align:center;">
		<div class="btn-group">
			<button class="btn active step-button" step="0">Basic Info</button>
			<button class="btn step-button" step="1">Details</button>
			<button class="btn step-button" step="2">Housemates</button>
			<button class="btn step-button" step="3">Photos</button>
		</div>
	</div>
	<div class="row-fluid">
		<div class="step">
			<?php echo $this->element('Sublet/Steps/address-step');?>
		</div>
		<div class="step">
			<?php echo $this->element('Sublet/Steps/info-step');?>
		</div>
		<div class="step">
			<?php echo $this->element('Sublet/Steps/addinfo-step');?>
		</div>
		<div class="step">
			<?php echo $this->element('Sublet/Steps/photo-step');?>
		</div>
	</div>
	<div class="row-fluid">
		<button class="btn" onclick="A2Cribs.EditSublet.Close()">Cancel</button>
		<button class="btn btn-primary" onclick="A2Cribs.EditSublet.Save()">Save Changes</button>
	</div>
</div>
<?php 
	$this->Js->buffer('
		A2Cribs.EditSublet = new A2Cribs.EditSublet();
	');
?>