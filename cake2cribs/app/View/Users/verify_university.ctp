<?php echo $this->Html->css('bootstrap'); ?>
<?php echo $this->Html->css('font-awesome'); ?>
<?php echo $this->Html->css('users'); ?>
<?php echo $this->element('header'); ?>

<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Session->flash(); ?>
<?php //echo $this->Session->flash($authmessage) ?>
<div id="login-box">
	<div id="userLogin">
<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><h3><?php echo __('University association'); ?></h3></legend>
        <?php echo $this->Form->input('email', array('label' => 'Please enter your .edu email address'));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Associate me!')); ?>
</div>
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

