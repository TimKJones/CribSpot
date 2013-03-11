<?php
include 'fbaccess.php';
include 'connect.php';

if ($userid)
{

$houseid = $_GET['markerid'];
$groupid = $_GET['groupid'];
$op      = $_GET['op'];
// Escape User Input to help prevent SQL Injection
$houseid = mysql_real_escape_string($houseid);
$userid = mysql_real_escape_string($userid);
$groupid = mysql_real_escape_string($groupid);
$op = mysql_real_escape_string($op);

// check if user has already added this property as a favorite
$favorites = mysql_query("select count(*) from ubid.Favorites where houseid='" . $houseid . "' and userid ='" . $userid . "'");
$success = "false";
if (mysql_result($favorites, 0, 0) == 0)
{
	$result = mysql_query("insert into ubid.Favorites values (0," . $groupid . ", " . $houseid . ", " . $userid . ")");	
	$success = "true";
}

echo "<success>" . $success . "</success>";

// respond with whether it was successful so UI can be updated

/*
$houseInfo = mysql_query("select * from ubid.Houses where houseid='" . $houseid . "'");

echo
"<houseData>" .
"<address>" . mysql_result($houseInfo, 0, "address") . "</address>" .
"<availableMonth>" . mysql_result($houseInfo, 0, "availableMonth") . "</availableMonth>" .
"<availableYear>" . mysql_result($houseInfo, 0, "availableYear") . "</availableYear>" .
"<unit_type>" . mysql_result($houseInfo, 0, "unit_type") . "</unit_type>" .
"<rent>" . mysql_result($houseInfo, 0, "rent") . "</rent>" .
"<beds>" . mysql_result($houseInfo, 0, "minBeds") . "-" . mysql_result($houseInfo, 0, "maxBeds") . "</beds>" .
"<baths>" . mysql_result($houseInfo, 0, "bathrooms") . "</baths>" .
"<furnished>" . mysql_result($houseInfo, 0, "furnished") . "</furnished>" .
"<company>" . mysql_result($houseInfo, 0, "company") . "</company>" .
"</houseData>"
;
*/

}
?>
