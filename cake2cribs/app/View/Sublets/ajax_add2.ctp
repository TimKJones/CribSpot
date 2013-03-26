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


<div class = 'sublet-register container-fluid'>
	<?php echo $this->Form->create('Sublet'); ?>
	<fieldset class ='row-fluid'>
	<style> .datepicker { width: 17em; padding: .2em .2em 0; z-index: 9999 !important; } </style>
	<input style="height:0px; top:-1000px; position:absolute" type="text" value=""> <!-- Hidden input to take focus off date -->
	<?php  
                echo "<div class = 'row-fluid'>";
		echo $this->Form->input('date_begin', array(
                        'div'=>'subin',
                        'label'=> array('class'=>'span3','text'=>'Start Date:'),
                        'class' => 'date-picker span3',
			'data-html' => 'true', 
			'data-placement' =>'bottom', 
			'type'=>'text', 
			'style' => 'position: relative; z-index: 100000;', 
			'value' =>$savedDateBegin
			)
		); 

		echo $this->Form->input('date_end', array(
			'div'=>'subin',
                        'label'=>array('class'=>'span3','text'=>'End Date:'),
                        'class' => 'date-picker span3',
			'data-html' => 'true', 
			'data-placement' =>'top', 
			'type'=>'text', 
			'style' => 'position: relative; z-index: 100000;', 
			'value' => $savedDateEnd
			)
		);

                echo "</div>";
		echo $this->Form->input('flexible_dates', array('label'=>"Flexible dates?", /*'value' => $savedFlexibleDates,*/ 'type' => 'checkbox'));

		echo $this->Form->input('number_bedrooms', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Number of Bedrooms:'),
        	'class'=>'span9',
        	'value'=> $savedNumberBedrooms
        	)
        );


		echo $this->Form->input('price_per_bedroom', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Price Per Bedroom:'),
        	'class'=>'span9',
        	'value'=> $savedPricePerBedroom
        	)
        );


		echo $this->Form->input('payment_type_id', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Payment Type:'),
        	'class'=>'span9',
        	'value'=> $savedPaymentTypeID
        	)
        );

        echo "<div class = 'row-fluid'>";
        echo $this->Form->label('short_description', 'Short Description:', 'span3');
        echo $this->Form->textarea('short_description', array(
                'div'=>'subin',
                'placeholder'=>"Short description of the property",
                'class'=>'span9',
                'value'=> $savedShortDescription
                )
        );
        echo "</div>";

		echo $this->Form->input('number_bathrooms', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Number of Bathrooms:'),
        	'class'=>'span9',
        	'value'=> $savedNumberBathrooms
        	)
        );
		echo $this->Form->input('bathroom_type_id', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Number of Bedrooms:'),
        	'class'=>'span9',
        	'value'=> $savedBathroomTypeID
        	)
        );
		echo $this->Form->input('utility_type_id', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Utility Type:'),
        	'class'=>'span9',
        	'value'=> $savedUtilityTypeID
        	)
        );
		echo $this->Form->input('utility_cost', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Utility Cost:'),
        	'class'=>'span9',
        	'value'=> $savedUtilityCost
        	)
        ); 
		echo $this->Form->input('parking', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Parking:'),
        	'class'=>'span9',
        	'value'=> $savedParking
        	)
        );
		echo $this->Form->input('ac', array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'AC:'),
        	'class'=>'span9',
        	'value'=> $savedAC
        	)
        ); 

		echo $this->Form->input('furnished_type_id',  array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Furnishing Type:'),
        	'class'=>'span9',
        	'value'=> $savedFurnishedTypeID
        	)
        ); 

		echo $this->Form->input('deposit_amount',  array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Deposit Ammount:'),
        	'class'=>'span9',
        	'value'=> $savedDepositAmount
        	)
        ); 
        echo "<div class = 'row-fluid'>";
        echo $this->Form->label('additional_fees_description', 'Additional Fees Description:', 'span3');
        echo $this->Form->textarea('additional_fees_description', array(
                'div'=>'subin',
                'placeholder'=>"Describe any additional fees here.",
                'class'=>'span9',
                'value'=> $savedAdditionalFeesDescription
                )
        );
		echo $this->Form->input('additional_fees_amount',  array(
        	'div'=>'row-fluid subin',
        	'label'=> array('class'=>'span3','text'=>'Additional Fees Ammount:'),
        	'class'=>'span9',
        	'value'=> $savedAdditionalFeesAmount
        	)
        ); 
	?>
	<a href="#" id="goToStep1">Go back </a>

</div>
<script>
var a = A2Cribs.SubletAdd;
a.setupUI();
$("#SubletDateBegin").datepicker();
$('#SubletDateEnd').datepicker();
</script>