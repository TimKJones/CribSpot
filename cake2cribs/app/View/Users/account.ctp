

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
			<div>
	<div class="users form" id="userRegistration">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <h3><?php echo __('Edit your account.'); ?></h3>
        <br />
        <?php 
        
        echo $this->Form->input('first_name', array('label' => 'First name', 'placeholder'=>$first_name));
        echo $this->Form->input('last_name', array('label' => 'Last name', 'placeholder'=>$last_name));
        echo $this->Form->input('password', array('label' => 'Change your password', 'placeholder' => 'Please enter a new password...', 'type' => 'password'));
        //insert option for secondary email address
        //echo $this->Form->input('email', array('label' => 'Email address', 'placeholder' =>$email));
    ?>
    </fieldset>
    <br />
<?php echo $this->Form->end(__('Edit my account')); ?>

</div>


</div>
	</div>
</div>
</div>
</div>
<script>
$('body').css('background-color','#eeecec');
</script>`