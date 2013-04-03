

<?php echo $this->Html->css('bootstrap'); ?>
<?php echo $this->Html->css('font-awesome'); ?>
<?php echo $this->Html->css('users'); ?>
<?php echo $this->Html->css('sublet_edit'); ?>
<?php echo $this->Html->script('src/SubletEdit') ?>
<?php echo $this->Html->script('src/SubletInProgress') ?>
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

</script>
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


<script>
$(document).ready(function() {
    A2Cribs.Cache.SubletData = subletData;
    A2Cribs.SubletEdit.Init();
});
</script>
<?php 
    $this->Js->buffer('
    ');
?>

