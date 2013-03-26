<!--<div> BLAH</div>
<a class="ajax" href="/sublets/ajax_add" id="gotoscreen2">Go back </a> -->

<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->script('src/SubletAdd'); ?>
<?php echo $this->Html->css('account'); ?>

<div id="sublets form">
	<?php echo $this->Form->create('Housemate'); 
	echo $this->Form->input('quantity');
	echo $this->Form->input('enrolled');
	echo $this->Form->input('student_type_id');
	echo $this->Form->input('major');
	echo $this->Form->input('seeking');
	echo $this->Form->input('gender_type_id'); ?>
<?php echo $this->Form->create('Sublet'); ?>
<fieldset>
<div id="sublet_register_screen1">
<style> .datepicker { width: 17em; padding: .2em .2em 0; z-index: 9999 !important; } </style>
<input style="height:0px; top:-1000px; position:absolute" type="text" value=""> <!-- Hidden input to take focus off date -->
<?php 

echo $this->Form->input('description', array('value' => $savedDescription));

                        ?>

	<!--<a class="ajax" href="/sublets/ajax_add2" id="gotoscreen2">Go next </a> -->
	<a href="#" id="finishSubletAdd">Save</a>
</div>


</div>
<script>
var a = A2Cribs.SubletAdd;
a.setupUI();

</script>