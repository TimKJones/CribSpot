<?php
require 'connect.php';

$minRent = $_GET['minRent'];
$maxRent = $_GET['maxRent'];
$minBeds = $_GET['minBeds'];
$maxBeds = $_GET['maxBeds'];
$fallCheck   = $_GET['fall'];
$springCheck = $_GET['spring'];
$otherCheck  = $_GET['other'];
$houseCheck  = $_GET['house'];
$aptCheck  = $_GET['apt'];
$duplexCheck  = $_GET['duplex'];
// Escape User Input to help prevent SQL Injection
$minRent = mysql_real_escape_string($minRent);
$maxRent = mysql_real_escape_string($maxRent);
$minBeds = mysql_real_escape_string($minBeds);
$maxBeds = mysql_real_escape_string($maxBeds);

$query = "select markerid from ubid.Houses where rent >= " . $minRent . " and rent <= " . $maxRent . " and beds >= " . $minBeds . " and beds <= " . $maxBeds . ""; 

// -------------------------- lease range ---------------------------------
$lease_range_query = "";
$lease_range_applied = false;
if ($fallCheck == "true")
{
	$lease_range_query = " and (lease_range = 'Fall-Fall'";
	$lease_range_applied = true;
}
if ($springCheck == "true")
{
	if (!$lease_range_applied)
		$lease_range_query = $lease_range_query . " and (";
	else
		$lease_range_query = $lease_range_query . " or ";
	$lease_range_query = $lease_range_query . "lease_range = 'Spring-Spring'";
	$lease_range_applied = true;
}
if ($otherCheck == "true")
{
	if (!$lease_range_applied)
		$lease_range_query = $lease_range_query . " and (";
	else
		$lease_range_query = $lease_range_query . " or ";
	$lease_range_query = $lease_range_query . "lease_range = 'Other'";
	$lease_range_applied = true;
}

if ($lease_range_applied == true)
	$lease_range_query = $lease_range_query . ")";


$query = $query . $lease_range_query;

// --------------------------- house type -------------------------------------
$lease_range_query = "";
$lease_range_applied = false;
if ($houseCheck == "true")
{
	$lease_range_query = " and (unit_type = 'House'";
	$lease_range_applied = true;
}
if ($aptCheck == "true")
{
	if (!$lease_range_applied)
		$lease_range_query = $lease_range_query . " and (";
	else
		$lease_range_query = $lease_range_query . " or ";
	$lease_range_query = $lease_range_query . "unit_type = 'Apartment'";
	$lease_range_applied = true;
}
if ($duplexCheck == "true")
{
	if (!$lease_range_applied)
		$lease_range_query = $lease_range_query . " and (";
	else
		$lease_range_query = $lease_range_query . " or ";
//	$lease_range_query = $lease_range_query . "(unit_type != 'House' and unit_type != 'Apartment')";
	$lease_range_query = $lease_range_query . "unit_type = 'Duplex'";
	$lease_range_applied = true;
}

if ($lease_range_applied == true)
	$lease_range_query = $lease_range_query . ");";


$query = $query . $lease_range_query;


$properties = mysql_query($query);
//echo "<markerid>" . mysql_num_rows($properties) . "</markerid>";
echo "<markeridlist>";

/*	echo "<query>\"" . $query . "\"</query>";
	echo "<houseCheck>" . $fallCheck . "</houseCheck>";
	echo "<aptCheck>" . $aptCheck . "</aptCheck>";
	echo "<duplexCheck>" . $duplexCheck . "</duplexCheck>";*/
for ($i = 0; $i < mysql_num_rows($properties); $i++)
{
//		echo "<markerid></markerid>"; 
	echo "<markerid>" . mysql_result($properties, $i, "markerid") . "</markerid>"; 
}
echo "</markeridlist>";

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
?>
