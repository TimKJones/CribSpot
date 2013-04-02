<?php echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('users'); ?>
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Session->flash(); ?>

<div class="top-bar">
    <ul id="left-options" class="inline unstyled pull-left">

    <?php 
    if ($this->Session->read('Auth.User.id') == 0)
        echo '<li><a href="#myModal" data-toggle="modal">Login</a></li>';
    else
        echo '<li><a href="/dashboard">My Dashboard</a></li>';
    ?>
    </ul>
    <ul id="right-options" class="inline unstyled pull-right">
        <li><a href="#about-page" data-toggle="modal">About</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="#help">Help</a></li>
    </ul>
</div>

<?= $this->element('login'); ?>

<?php echo $this->Html->script('jquery.noisy.min'); ?>
<?php 
    $this->Js->buffer('
            $("#myModal").removeClass("hide fade");
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