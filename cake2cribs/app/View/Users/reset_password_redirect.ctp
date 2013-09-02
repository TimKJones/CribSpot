<?php echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('users'); ?>

<?php
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
    echo $this->Html->script("src/Account");
}

echo $this->Html->script('jquery.noisy.min');
?>

<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Session->flash(); ?>


<?php echo $this->element('header'); ?>

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