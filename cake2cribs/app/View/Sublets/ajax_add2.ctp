<!--<div> BLAH</div>
<a class="ajax" href="/sublets/ajax_add" id="gotoscreen2">Go back </a> -->
<?php //$this->layout = false; ?>
<?php
echo $this->Html->css('datepicker');

	/* Datepicker and slider javascript */
	echo $this->Html->script('bootstrap-datepicker');
	?>

<?php echo $this->Html->css('account'); ?>


<div id="sublets form">
<?php echo $this->Form->create('Sublet'); ?>
<fieldset>
<div id="sublet_register_screen1">
<style> .datepicker { width: 17em; padding: .2em .2em 0; z-index: 9999 !important; } </style>
<input style="height:0px; top:-1000px; position:absolute" type="text" value=""> <!-- Hidden input to take focus off date -->
<?php 
 						echo $this->Form->input('date_begin', array('class' => 'date-picker','data-html' => 'true', 'data-placement' =>'bottom', 'type'=>'text', 'style' => 'position: relative; z-index: 100000;')); 
                       
                        echo $this->Form->input('date_end', array('class' => 'date-picker','data-html' => 'true', 'data-placement' =>'top', 'type'=>'text', 'style' => 'position: relative; z-index: 100000;'));
                        echo $this->Form->input('flexible_dates', array('label'=>"Flexible dates?"));
                        echo $this->Form->input('number_bedrooms');
                        echo $this->Form->input('price_per_bedroom');
                        echo $this->Form->input('payment_type_id');
                        echo $this->Form->input('description');
                        echo $this->Form->input('number_bathrooms');
                        echo $this->Form->input('bathroom_type_id');
                        echo $this->Form->input('utility_type_id');
                        echo $this->Form->input('utility_cost'); ?>

	<!--<a class="ajax" href="/sublets/ajax_add2" id="gotoscreen2">Go next </a> -->
	<a href="#" id="goToStep2">Go next </a>
</div>


</div>
<script>
$("#SubletDateBegin").datepicker();
$('#SubletDateEnd').datepicker();
</script>