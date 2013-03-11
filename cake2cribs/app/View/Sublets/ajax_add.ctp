<?php $this->layout = false; ?>
<?php //echo $this->Html->css('ajax_sublet'); ?>
<div id="sublets form">
<?php echo $this->Form->create('Sublet'); ?>
<fieldset>

<div id="sublet_register_screen1">
	<legend>Post a sublet</legend>
	<?php echo $this->Form->input('university_id'); ?>
	<?php echo $this->Form->input('building_type_id'); ?>
	<?php echo $this->Form->input('name'); ?>
	<div id="container" style="position:relative;">
        <?php echo $this->element('correctPinLocationMap'); ?>
     </div>
	<a href="#sublet_register_screen2" id="gotoscreen2">Go next </a>
</div>

<div id="sublet_register_screen2">
	<h1>Sublet step 2</h1>
	<h1> BIG </h1>
	 <a href="#sublet_register_screen3" id="gotoscreen3">Go next </a>
</div>

<div id="sublet_register_screen3">

	<h1> Sublet step 3 </h1>
	<?php echo $this->Form->end('Submit Sublet'); ?>
	<!-- Submit using javascript -->
	
</div>

</div>
