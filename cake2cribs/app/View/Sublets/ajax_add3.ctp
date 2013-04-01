<!--<div> BLAH</div>
<a class="ajax" href="/sublets/ajax_add" id="gotoscreen2">Go back </a> -->
<?php echo $this->Html->css('SubletAddEditCommon'); ?>
<?php echo $this->Html->css('jquery-ui'); ?>
<?php //echo $this->Html->script('src/SubletAdd'); ?>
<?php echo $this->Html->css('account'); ?>
<?php echo $this->Html->css('ajax_add3'); ?>

<div class="sublet-register">
	<?php 
	echo $this->Form->create('Housemate'); 
	echo $this->Form->input('quantity', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Housemates:'),
        	'class'=>'span9',
        	)
        ); 
	echo '<input type="checkbox" name="data[Housemate][enrolled]" id="HousemateEnrolled"><label for="HousemateEnrolled">Enrolled</label>';

	echo $this->Form->input('student_type_id', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Student Type:'),
        	'class'=>'span9',
        	)
        ); 
	echo $this->Form->input('major', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Majors:'),
        	'class'=>'span9',
        	)
        );

	echo $this->Form->input('type', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Standing:'),
        	'class'=>'span9',
        	'placeholder' => 'ex. Sophomore, Senior...'
        	)
        );
	
	echo "<div class = 'row-fluid'>";
	echo $this->Form->label('seeking', 'Seeking:', 'span3');
	echo $this->Form->textarea('seeking', array(
        	'div'=>'subin',
        	'placeholder'=>"What do you look for in a subletter?",
        	'class'=>'span9'
        	)
        );
	echo "</div>";
	
	echo $this->Form->input('gender_type_id', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Gender Type:'),
        	'class'=>'span9',
        	)
        ); 
	//echo $this->Form->input('description', array('value' => $savedDescription));
	?>
	<?php echo $this->Form->create('Sublet'); ?>
	<fieldset>
	
	<div id="sublet_register_screen1">
		<style> .datepicker { width: 17em; padding: .2em .2em 0; z-index: 9999 !important; } </style>
		<input style="height:0px; top:-1000px; position:absolute" type="text" value=""> <!-- Hidden input to take focus off date -->
		<?php      ?>


		<!--<a class="ajax" href="/sublets/ajax_add2" id="gotoscreen2">Go next </a> -->
                <a href="#" id="backToStep2">Back</a>
		<a href="#" id="finishSubletAdd" style="float:right">Finish</a>
	</div>
</div>

<script>
	var a = A2Cribs.SubletAdd;
	a.setupUI();
	A2Cribs.SubletEdit.InitStep3();
</script>