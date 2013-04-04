<?php echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('users'); ?>
<?php echo $this->Html->css('bootstrap'); ?>
<?php echo $this->Html->css('font-awesome'); ?>
<?php echo $this->Html->css('resetpassword'); ?>
<?php echo $this->Html->script("src/Account"); ?>

<?php echo $this->element('popups'); ?>
<div class="top-bar">
    <ul id="right-options" class="inline unstyled pull-right">
        <li><a href="#about-page" data-toggle="modal">About</a></li>
        <li><a href="#contact-page" data-toggle="modal">Contact</a></li>
        <li><a href="#help-page" data-toggle="modal">Help</a></li>
    </ul>
</div>

<div id="header" class="container">
    <a href="/"><div class="main-logo pull-left"></div></a>
</div>

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
<?php echo $this->Html->script('jquery.noisy.min'); ?>
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

