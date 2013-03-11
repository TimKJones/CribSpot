<?php
$con = mysql_connect('localhost', 'root');
#$mysql_select_db('ubid');

$result = mysql_query("select * from ubid.Houses;");

echo("
<html>	
  <head>
	<meta name=\"viewport\" content=\"initial-scale=1.0, user-scalable=no\" />
    <style type=\"text/css\">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>

	<script type=\"text/javascript\" >
		loadScript();
		alert('load');
//		initialize();

		
		var map;

		function loadScript() 
		{
			alert('loading');
  			var script = document.createElement(\"script\");
  			script.type = \"text/javascript\";
  			script.src = \"https://maps.googleapis.com/maps/api/js?key=AIzaSyChGyO2wCFqmDe8FNh_6GxITy7dDLQ0ZpE&sensor=false&callback=initialize\";
  			document.body.appendChild(script);
		}

		function initialize()
		{
			alert(\"test\");
			var geocoder = new google.maps.Geocoder();			
			var myOptions = {
      			center: new google.maps.LatLng(42.2808, -83.7430),
      			zoom: 13,
        		mapTypeId: google.maps.MapTypeId.ROADMAP
			};
   			map = new google.maps.Map(document.getElementById(\"map_canvas\"), myOptions);
			var marker;		
			var address = \"703 S. Forest Ct, Ann Arbor, MI 48104\";	
		
			initMarkers();
	
			marker = new google.maps.Marker({
      			position: new google.maps.LatLng(42.2736993, -83.7331045),
      			map: map,
      			title:\"703 S. Forest Ct \nCampus Management\"
  			});
		}
	</script>
    <meta name=\"viewport\" content=\"initial-scale=1.0, user-scalable=no\" />
      </head>
  <body onload=\"loadScript()\">
    <div id=\"map_canvas\" style=\"width:100%; height:100%\"></div>
  </body>
</html>

");
?>

