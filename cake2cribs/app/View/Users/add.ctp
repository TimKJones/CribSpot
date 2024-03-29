<?php echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('users'); ?>

<?php
echo $this->Html->script('jquery.noisy.min');
?>

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


<div class ="side-by-side">
<?= $this->element('login'); ?>
<?= $this->element('register'); ?>
</div>

<?php 
    $this->Js->buffer('
            $("#myModal").removeClass("hide fade");
            $("#signupModal").removeClass("hide fade");
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
</script>