<?php
require 'fbaccess.php';
require 'connect.php';
if ($userid)
{

$houseid = $_GET['houseid'];
//$groupid = $_GET['groupid'];
$op 	 = $_GET['op'];
// Escape User Input to help prevent SQL Injection
$houseid = mysql_real_escape_string($houseid);
$userid = mysql_real_escape_string($userid);
//$groupid = mysql_real_escape_string($groupid);
$op = mysql_real_escape_string($op);

// check if user has already added this property as a favorite
$favorites = mysql_query("select count(*) from ubid.Favorites where houseid='" . $houseid . "' and userid ='" . $userid . "'");
$success = "false";
if (mysql_result($favorites, 0, 0) == 0)
{
	if ($op == "toggle")
		$op = "add";
	if ($op == "add")
	{
		$result = mysql_query("insert into ubid.Favorites values (0," . $houseid . ", " . $userid . ")");	
		$success = "true";
	}
}
else if (mysql_result($favorites, 0, 0) != 0)
{
	if ($op == "toggle")
		$op = "delete";
	if ($op == "delete")
	{
		$result = mysql_query("delete from ubid.Favorites where houseid='" . $houseid . "' and userid='" . $userid . "'");	
		$success = "true";	
	}
}

$countResult = mysql_query("select count(*) from ubid.Favorites where userid='" . $userid . "'"); 

echo "<favorite>";
echo "<success>" . $success . "</success>";
echo "<count>" . mysql_result($countResult, 0, 0) . "</count>";
echo "<new_op>" . $op . "</new_op>";

if ($op == "add")
{
$houseInfo = mysql_query("select * from ubid.Houses where markerid='" . $houseid . "'");
echo "<address>" . mysql_result($houseInfo, 0, "address") . "</address>" .
"<lease_range>" . mysql_result($houseInfo, 0, "lease_range") . "</lease_range>" .
"<unit_type>" . mysql_result($houseInfo, 0, "unit_type") . "</unit_type>" .
"<unit_description>" . mysql_result($houseInfo, 0, "unit_description") . "</unit_description>" .
"<beds>" . mysql_result($houseInfo, 0, "beds") . "</beds>" .
"<bathrooms>" . mysql_result($houseInfo, 0, "bathrooms") . "</bathrooms>" .
"<rent>" . mysql_result($houseInfo, 0, "rent") . "</rent>" .
"<company>" . mysql_result($houseInfo, 0, "company") . "</company>" .
"<electric>" . mysql_result($houseInfo, 0, "electric") . "</electric>" .
"<water>" . mysql_result($houseInfo, 0, "water") . "</water>" .
"<heat>" . mysql_result($houseInfo, 0, "heat") . "</heat>" .
"<air>" . mysql_result($houseInfo, 0, "air") . "</air>" .
"<parking>" . mysql_result($houseInfo, 0, "parking") . "</parking>" .
"<furnished>" . mysql_result($houseInfo, 0, "furnished") . "</furnished>" .
"<url>" . mysql_result($houseInfo, 0, "url") . "</url>";
}
echo "</favorite>";
}
else
{
echo "<favorite>";
echo "<success>'false'</success>";
echo "<errorCode>notloggedin</errorCode>";
echo "</favorite>";
}
?>
