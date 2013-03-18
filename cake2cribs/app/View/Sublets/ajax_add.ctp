<?php //$this->layout = false; ?>
<?php echo $this->Html->css('ajax_sublet'); ?>
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
	<a class="ajax" href="/sublets/ajax_add2" id="gotoscreen2">Go next </a>
</div>


</div>
<?php
/*$this->Js->buffer('
	A2Cribs.CorrectMarker.Init();
');*/

?>