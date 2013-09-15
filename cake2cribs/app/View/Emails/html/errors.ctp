<style>
    .ui-helper-center {
        text-align: center;
        padding: 5px;
    }
</style>

<div id = 'errors-wrapper'>
<?php
foreach ($errors as $error){
    echo "<b>Error ID: " . $error['Error']['error_id'] . "</b><br/>";
    echo "Error Code: " . $error['Error']['error_code'] . "<br/>";
    echo "User ID: " . $error['Error']['user_id'] . "<br/>";
    echo "Debug Info: " . $error['Error']['debug_info'] . "<br/>";
    echo "---------------<br/>";
}
?>
</div>