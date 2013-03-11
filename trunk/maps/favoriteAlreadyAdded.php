<?php
require 'fbaccess.php';
require 'connect.php';
if ($userid)
{

$houseid = $_GET['houseid'];
// Escape User Input to help prevent SQL Injection
$houseid = mysql_real_escape_string($houseid);
$userid = mysql_real_escape_string($userid);

// check if user has already added this property as a favorite
$favorites = mysql_query("select count(*) from ubid.Favorites where houseid='" . $houseid . "' and userid ='" . $userid . "'");
$result = "false";
if (mysql_result($favorites, 0, 0) == 1)
{
	$result = "true";
}

echo "<favoriteAlreadyAdded>";
echo $result;
echo "</favoriteAlreadyAdded>";
}
?>
