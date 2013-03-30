

<?php echo $this->Html->css('bootstrap'); ?>
<?php echo $this->Html->css('font-awesome'); ?>
<?php echo $this->Html->css('users'); ?>
<?php echo $this->Html->css('sublet_edit'); ?>
<?php echo $this->element('header'); ?>
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Session->flash(); ?>
<div class="container-fluid">
	<div class="row-fluid">
<div class="span2" id="left_content">
	<div class="dashboard-item">
		<a href="/users/account"><p>My account</p></a>
	</div>
	<div class="dashboard-item">
		<a href="/messages/index"><p> Messages </p></a>
	</div>
	<div class="dashboard-item">
		<a href="/users/sublets"><p>My sublets </p></a>
	</div>
	<div class="dashboard-item">
		<a href="/"><p> Find a sublet </p></a>
</div>
</div>
<div class="span10" id="accountContainer">
	<div class="row-fluid">

	<div id="helloMessage">
	<div class="users form" id="userRegistration">
<script>
var subletData = 
<?php 
echo json_encode($subletData); 
?>;
var universities = 
<?php
echo $universities;
?>;
$('<div/>').dialog2({
    title: "Edit " + subletData.Marker.street_address, 
    content: "/Sublets/ajax_add", 
    id: "server-notice"
});

schoolList = [];
        for (var i = 0; i < universities.length; i++)
            schoolList.push(universities[i].University.name);

        $("#universityName").typeahead({
            source: schoolList
        });

        $("#universityName").focusout(function() {
          A2Cribs.CorrectMarker.FindSelectedUniversity();
        });
</script>
<?php echo $this->Form->create('Sublet'); ?>
    <fieldset>
        <h3><?php echo __('Edit your sublet at '. $subletData['Marker']['street_address']); ?></h3>
        <br />
        <div class="span5">



       <?php
        echo $this->Form->input('university_id');
        //pass the buildtype array key-value through the sublets view controller
        //also need to make this db table
        echo $this->Form->input('building_type_id');
        //use jquery to make autocomplete
        echo $this->Form->input('name');
        ?>
        
        <?php
        echo $this->Form->input('city');
        //use jquery for the state list
        echo $this->Form->input('state');
        echo $this->Form->input('zip');

        echo $this->Form->input('date_begin', array('id' => 'datepicker', 'type'=>'text')); 
     
        echo $this->Form->input('date_end');
        

        echo $this->Form->input('number_bedrooms');
         ?>
         </div>
        <div class="span5">
        <?php
        echo $this->Form->input('price_per_bedroom');
        echo $this->Form->input('payment_type_id');
        echo $this->Form->input('description');
        echo $this->Form->input('number_bathrooms');
        echo $this->Form->input('bathroom_type_id');
        echo $this->Form->input('utility_type_id');
        echo $this->Form->input('utility_cost');
        echo $this->Form->input('deposit_amount');
        echo $this->Form->input('additional_fees_descriptions');
        echo $this->Form->input('additional_fees_amount');
    ?>
    </fieldset>
    <br />
<?php echo $this->Form->end(__('Edit my sublet')); ?>
</div>
</div>


</div>
</div>
	</div>
</div>
</div>
<script>
$('body').css('background-color','#eeecec');
</script>

<?php 
    $this->Js->buffer('
    ');
?>

