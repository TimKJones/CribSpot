<?php echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('users'); ?>
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Session->flash(); ?>


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

<?= $this->element('login'); ?>

<?php echo $this->Html->script('jquery.noisy.min'); ?>
<?php 
    $this->Js->buffer('
            $("#myModal").removeClass("hide fade").css("z-index", 0).css("top", "90px");
    ');
?>
<script>
$('body').noisy({
    'intensity' : 1, 
    'size' : 200, 
    'opacity' : 0.08, 
    'fallback' : '', 
    'monochrome' : true
}).css('background-color', '#eeecec');


if (document.URL.indexOf("password_reset_redirect") != -1)
    A2Cribs.UIManager.Alert("An email has been sent to the email address on file with a link to reset your password.");
else if (document.URL.indexOf("password_changed") != -1)
    A2Cribs.UIManager.Alert("Your password has been successfully changed. Please enter your new login credentials.");
</script>