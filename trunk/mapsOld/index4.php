<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyChGyO2wCFqmDe8FNh_6GxITy7dDLQ0ZpE&sensor=false">
    </script>
    <script type="text/javascript">
        function initialize()
        {
            var myOptions = {
                center: new google.maps.LatLng(42.2808256, -83.7430378),
                zoom: 13,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
            geocoder = new google.maps.Geocoder();
            AddMarkers();
        }

        function AddMarkers()
        {
<?php 
            $con = mysql_connect('localhost', 'root');
            $result = mysql_query("select * from ubid.Houses;");
            $i = 0;
echo("alert(\"" . mysql_num_rows($result) . "\")");
        for ($i = 0; $i < mysql_num_rows($result); $i++)
        {
echo("
            var latlng = new google.maps.LatLng(\"" . mysql_result($result, $i, 'latitude') . "\", \"" . mysql_result($result, $i, 'longitude') . "\");
alert(\"(" . mysql_result($result, $i, 'latitude') . ", " . mysql_result($result, $i, 'longitude') . ")\");
            var myOptions = {
                zoom: 4,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
           }

           var address_short = \"" . mysql_result($result, $i, 'address') . "\";
           var address = address_short + \", Ann Arbor, MI\";
           var marker = new google.maps.Marker({
                position: new google.maps.LatLng(\"" . mysql_result($result, $i, 'latitude') . "\", \"" . mysql_result($result, $i, 'longitude') . "\"),
                title: address_short 
           });

           marker.setMap(map);
");
        }
?> 
            alert('addmarkers');
        }
   </script>
   </head>
    <body onload="initialize()">
        <div id="map_canvas" style="width:100%; height:100%"></div>
  </body>

</html> 
