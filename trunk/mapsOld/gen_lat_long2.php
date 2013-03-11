<?php
echo("loading");
define("MAPS_HOST", "maps.google.com");
define("KEY", "AIzaSyChGyO2wCFqmDe8FNh_6GxITy7dDLQ0ZpE");

// Opens a connection to a MySQL server
$connection = mysql_connect("localhost", 'root');
if (!$connection) {
      die("Not connected : " . mysql_error());
}
echo("connected");
// Set the active MySQL database
$db_selected = mysql_select_db('ubid', $connection);
if (!$db_selected) {
      die("Can\'t use db : " . mysql_error());
}
echo("selected db");
// Select all the rows in the markers table
$query = "SELECT * FROM ubid.Houses";
$result = mysql_query($query);
if (!$result) {
      die("Invalid query: " . mysql_error());
}

// Initialize delay in geocode speed
$delay = 0;
$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;

// Iterate through the rows, geocoding each address
while ($row = @mysql_fetch_assoc($result)) {
    $geocode_pending = true;

    while ($geocode_pending) {
        $address = $row["address"];
        $address_long = $address . " Ann Arbor MI 48104";
        echo $address_long;
        $request_url = $base_url . "&q=" . urlencode($address_long);
        $xml = simplexml_load_file($request_url) or die("url not loading");

        $status = $xml->Response->Status->code;
        if (strcmp($status, "200") == 0) {
              // Successful geocode
            $geocode_pending = false;
            $coordinates = $xml->Response->Placemark->Point->coordinates;
            $coordinatesSplit = split(",", $coordinates);
              // Format: Longitude, Latitude, Altitude
            $lat = $coordinatesSplit[1];
            $lng = $coordinatesSplit[0];

            $query = sprintf("UPDATE Houses " .
            " SET latitude = '%s', longitude = '%s' " .
            " WHERE address = '%s' LIMIT 1;",
            mysql_real_escape_string($lat),
            mysql_real_escape_string($lng),
            mysql_real_escape_string($address));
            $update_result = mysql_query($query);
            if (!$update_result) {
                die("Invalid query: " . mysql_error());
            }
        } 
        else if (strcmp($status, "620") == 0) 
        {
            // sent geocodes too fast
            $delay += 100000;
        } 
        else 
        {
            // failure to geocode
            $geocode_pending = false;
            echo "Address " . $address . " failed to geocoded. ";
            echo "Received status " . $status . "\n";
        }
        usleep($delay);
    }
}
?>
