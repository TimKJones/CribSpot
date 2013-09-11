<?php echo $this->Html->css('users'); ?>
<?php echo $this->Html->css('resetpassword'); ?>
<?php echo $this->Html->script('jquery.noisy.min'); ?>
<?php
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
    echo $this->Html->script("src/Account");
}
?>

<?php echo $this->element('header', array('show_filter' => false, 'show_user' => false)); ?>

<?php //echo $this->Session->flash($authmessage) ?>
<div id="login-box">
	<div id="userLogin">
<div class="users form">
<?php echo $this->Form->create('User'); ?> 
    <fieldset>
        <legend><h3><?php echo __('Password Reset'); ?></h3></legend>
        <?php echo $this->Form->input('email', array('label' => 'Please enter your email address.'));
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Reset my password')); ?>
</div>
</div>
</div>
<script>
$('body').noisy({
    'intensity' : 1, 
    'size' : 200, 
    'opacity' : 0.08, 
    'fallback' : '', 
    'monochrome' : true
}).css('background-color', '#eeecec');

$("#UserResetpasswordForm").submit(function(e) {
    A2Cribs.Account.SubmitResetPassword($("#UserEmail").val());
    e.preventDefault();
    return false;
});
</script>

