<?php echo $this->Html->css('bootstrap'); ?>
<?php echo $this->Html->css('font-awesome'); ?>
<?php echo $this->Html->css('users'); ?>
<?php echo $this->element('header'); ?>
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Session->flash(); ?>

<div id="registration-box">
<div class="users form" id="userRegistration">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <h3><?php echo __('Join Cribspot today.'); ?></h3>
        <br />
        <?php 
        echo $this->Form->input('email', array('label' => 'Email address'));
        echo $this->Form->input('password', array('label' => 'Create your password'));
        echo $this->Form->input('first_name', array('label' => 'First name'));
        echo $this->Form->input('last_name', array('label' => 'Last name'));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Create my account')); ?>

</div>
</div>
<?php echo $this->Html->script('jquery.noisy.min'); ?>
<script>
$('body').noisy({
    'intensity' : 1, 
    'size' : 200, 
    'opacity' : 0.08, 
    'fallback' : '', 
    'monochrome' : true
}).css('background-color', '#eeecec');
</script>