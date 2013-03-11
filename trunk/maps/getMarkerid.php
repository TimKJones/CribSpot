<?php
require 'connect.php';

$address = $_GET['address'];
// Escape User Input to help prevent SQL Injection
$address = mysql_real_escape_string($address);

$houseInfo = mysql_query("select * from ubid.Houses where address='" . $address . "'");

echo "<houseList>";

for ($i = 0; $i < mysql_num_rows($houseInfo); $i++)
{
echo
"<houseData>" .
"<address>" . mysql_result($houseInfo, $i, "address") . "</address>" .
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
"</houseData>"
;
}
echo "</houseList>";

?>
