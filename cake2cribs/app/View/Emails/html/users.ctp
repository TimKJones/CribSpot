<style>
    .ui-helper-center {
        text-align: center;
        padding: 5px;
    }
</style>

<div id = 'users-wrapper'>
<?php
foreach ($users as $user){
    echo "<b>User ID: " . $user['User']['id'] . "</b><br/>";
    echo "Email: " . $user['User']['email'] . "<br/>";
    if ($user['User']['user_type'] == 0){
    	echo "User Type: Student<br/>";
    	echo "First Name: " . $user['User']['first_name'] . "<br/>";
    	echo "Last Name: " . $user['User']['last_name'] . "<br/>";
        echo "Time: " . $user['User']['created'] . "<br/>";
    }
    else if ($user['User']['user_type'] == 1){
    	echo "User Type: Property Manager<br/>";
    	echo "Company Name: " . $user['User']['company_name'] . "<br/>";
    	echo "Phone Number: " . $user['User']['phone'] . "<br/>";
    }
    echo "---------------<br/>";
}
?>
</div>