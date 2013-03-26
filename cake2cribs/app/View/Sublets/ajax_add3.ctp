<!--<div> BLAH</div>
<a class="ajax" href="/sublets/ajax_add" id="gotoscreen2">Go back </a> -->

<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->script('src/SubletAdd'); ?>
<?php echo $this->Html->css('account'); ?>

<div class="sublet-register">
	<?php 
	echo $this->Form->create('Housemate'); 
	echo $this->Form->input('quantity', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Housemates:'),
        	'class'=>'span9',
        	)
        ); 
	echo $this->Form->input('enrolled', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Enrolled:'),
        	'class'=>'span9',
        	)
        ); 
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
	echo $this->Form->input('description', array('value' => $savedDescription));
	?>
	<?php echo $this->Form->create('Sublet'); ?>
	<fieldset>
	
	<div id="sublet_register_screen1">
		<style> .datepicker { width: 17em; padding: .2em .2em 0; z-index: 9999 !important; } </style>
		<input style="height:0px; top:-1000px; position:absolute" type="text" value=""> <!-- Hidden input to take focus off date -->
		<?php      ?>


		<!--<a class="ajax" href="/sublets/ajax_add2" id="gotoscreen2">Go next </a> -->
		<a href="#" id="finishSubletAdd">Save</a>
	</div>
</div>

<script>
	var a = A2Cribs.SubletAdd;
	a.setupUI();
</script>