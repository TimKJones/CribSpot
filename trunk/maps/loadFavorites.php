<?php
require 'fbaccess.php';
require 'connect.php';
if ($userid)
{

//$groupid = $_GET['groupid'];
// Escape User Input to help prevent SQL Injection
$userid = mysql_real_escape_string($userid);
//$groupid = mysql_real_escape_string($groupid);

// check if user has already added this property as a favorite
$favorites = mysql_query("select houseid from ubid.Favorites where userid='" . $userid . "'");
$count = mysql_num_rows($favorites);
$query = "select * from ubid.Houses where markerid = ";
for ($i = 0; $i < mysql_num_rows($favorites) - 1; $i++)
{
	$query = $query . "'" . mysql_result($favorites, $i, "houseid") . "' or markerid = ";
} 

if (mysql_num_rows($favorites) > 0)
	$query = $query . "'" . mysql_result($favorites, mysql_num_rows($favorites) - 1, "houseid") . "'";

$houseInfo = mysql_query($query);

echo "<addressList>";
echo "<count>" . $count . "</count>";
echo "<query>" . $query . "</query>";
for ($i = 0; $i < mysql_num_rows($houseInfo); $i++)
{
	echo "<houseInfo>";
	echo	"<address>" . mysql_result($houseInfo, $i, "address") . "</address>" .
"<lease_range>" . mysql_result($houseInfo, $i, "lease_range") . "</lease_range>" .
"<unit_type>" . mysql_result($houseInfo, $i, "unit_type") . "</unit_type>" .
"<unit_description>" . mysql_result($houseInfo, $i, "unit_description") . "</unit_description>" .
"<beds>" . mysql_result($houseInfo, $i, "beds") . "</beds>" .
"<bathrooms>" . mysql_result($houseInfo, $i, "bathrooms") . "</bathrooms>" .
"<rent>" . mysql_result($houseInfo, $i, "rent") . "</rent>" .
"<company>" . mysql_result($houseInfo, $i, "company") . "</company>" .
"<electric>" . mysql_result($houseInfo, $i, "electric") . "</electric>" .
"<water>" . mysql_result($houseInfo, $i, "water") . "</water>" .
"<heat>" . mysql_result($houseInfo, $i, "heat") . "</heat>" .
"<air>" . mysql_result($houseInfo, $i, "air") . "</air>" .
"<parking>" . mysql_result($houseInfo, $i, "parking") . "</parking>" .
"<furnished>" . mysql_result($houseInfo, $i, "furnished") . "</furnished>" .
"<url>" . mysql_result($houseInfo, $i, "url") . "</url>" .
"<listingid>" . mysql_result($houseInfo, $i, "markerid") . "</listingid>";
	echo "</houseInfo>";
}
echo "</addressList>";
}
?>
