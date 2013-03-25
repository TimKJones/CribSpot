<!--<div> BLAH</div>
<a class="ajax" href="/sublets/ajax_add" id="gotoscreen2">Go back </a> -->
<?php //$this->layout = false; ?>
<?php
echo $this->Html->css('datepicker');

	/* Datepicker and slider javascript */
	echo $this->Html->script('bootstrap-datepicker');
	?>

<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->script('src/SubletAdd'); ?>
<?php echo $this->Html->css('account'); ?>

<div id="sublets form">
<?php echo $this->Form->create('Sublet'); ?>
<fieldset>
<div id="sublet_register_screen1">
<style> .datepicker { width: 17em; padding: .2em .2em 0; z-index: 9999 !important; } </style>
<input style="height:0px; top:-1000px; position:absolute" type="text" value=""> <!-- Hidden input to take focus off date -->
<?php 
echo $this->Form->input('date_begin', array('class' => 'date-picker','data-html' => 'true', 'data-placement' =>'bottom', 'type'=>'text', 'style' => 'position: relative; z-index: 100000;', 'value' =>$savedDateBegin)); 
                       
echo $this->Form->input('date_end', array('class' => 'date-picker','data-html' => 'true', 'data-placement' =>'top', 'type'=>'text', 'style' => 'position: relative; z-index: 100000;', 'value' => $savedDateEnd));
echo $this->Form->input('flexible_dates', array('label'=>"Flexible dates?", 'value' => $savedFlexibleDates));
echo $this->Form->input('number_bedrooms', array('value' => $savedNumberBedrooms));
echo $this->Form->input('price_per_bedroom', array('value' => $savedPricePerBedroom));
echo $this->Form->input('payment_type_id', array('value' => $savedPaymentTypeID));
echo $this->Form->input('description', array('value' => $savedShortDescription));
echo $this->Form->input('number_bathrooms', array('value' => $savedNumberBathrooms));
echo $this->Form->input('bathroom_type_id', array('value' => $savedBathroomTypeID));
echo $this->Form->input('utility_type_id', array('value' => $savedUtilityTypeID));
echo $this->Form->input('utility_cost', array('value' => $savedUtilityCost)); 
echo $this->Form->input('parking', array('value' => $savedParking));
echo $this->Form->input('ac', array('value' => $savedAC));
echo $this->Form->input('furnished_type_id', array('value' => $savedFurnishedTypeID));
echo $this->Form->input('deposit_amount', array('value' => $savedDepositAmount));
echo $this->Form->input('additional_fees_description', array('value' => $savedAdditionalFeesDescription));
echo $this->Form->input('additional_fees_amount', array('value' => $savedAdditionalFeesAmount));
                        ?>

	<!--<a class="ajax" href="/sublets/ajax_add2" id="gotoscreen2">Go next </a> -->
	<a href="#" id="goToStep1">Go back </a>
</div>


</div>
<script>
var a = A2Cribs.SubletAdd;
a.setupUI();
$("#SubletDateBegin").datepicker();
$('#SubletDateEnd').datepicker();
</script>