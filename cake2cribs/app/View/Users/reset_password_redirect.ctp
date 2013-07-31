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

<?= $this->element('reset_password_redirect'); ?>

<?php echo $this->Html->script('jquery.noisy.min'); ?>
<?php 
    $this->Js->buffer('
            $("#myModal").removeClass("hide fade").css("z-index", 0);
            $("#changePasswordButton").click(function(){
                A2Cribs.Account.ChangePassword($("#changePasswordButton"), $("#new_password").val(), $("#confirm_password").val(), "' . $id . '","' . $reset_token . '")
            })
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



<?php
$this->Js->buffer('
    $("#changePasswordButton").click(function(){
        A2Cribs.Account.ChangePassword($("#changePasswordButton"), $("#new_password").val(), $("#confirm_password").val(), "'
            . $id . '","' . $reset_token . '")})'
);

?>