<?php echo $this->Html->css('bootstrap'); ?>
<?php echo $this->Html->css('font-awesome'); ?>
<?php echo $this->Html->css('users'); ?>
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
	<div id="helloMessage">
			<h1> Welcome to Cribspot, <?php echo $firstName; ?>.</h1>
			<p> Please select an item to the left.</p>
	</div>
</div>
</div>
</div>
<script>
$('body').css('background-color','#eeecec');
</script>