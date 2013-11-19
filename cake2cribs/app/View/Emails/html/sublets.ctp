<style>
    .ui-helper-center {
        text-align: center;
        padding: 5px;
    }
</style>

<div id = 'sublets-wrapper'>
<?php
foreach ($sublets as $sublet){
    echo "<b>Sublet ID: " . $sublet['Sublet']['sublet_id'] . "</b><br/>";
	echo "First Name: " . $sublet['User']['first_name'] . "<br/>";
	echo "Last Name: " . $sublet['User']['last_name'] . "<br/>";
    echo "Email: " . $sublet['User']['email'] . "<br/>";
    echo "Time: " . $sublet['Listing']['created'] . "<br/>";
    echo "Address: " . $sublet['Marker']['street_address'] . "<br/>";
    echo "Building Name: " . $sublet['Marker']['alternate_name'] . "<br/>";
    echo "City: " . $sublet['Marker']['city'] . "<br/>";
    echo "State: " . $sublet['Marker']['state'] . "<br/>";
    echo "URL: www.cribspot.com/listing/".$sublet['Listing']['listing_id'] . "<br/>";
    echo "---------------<br/>";
}
?>
</div>