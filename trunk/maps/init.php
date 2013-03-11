<script type="text/javascript">

// used to offset the tooltip position
function CanvasProjectionOverlay() {}

function initialize()
{
	nextMarkers = [];
	nextListingIds = [];
	uid = null;
	
	CanvasProjectionOverlay.prototype = new google.maps.OverlayView();
	CanvasProjectionOverlay.prototype.constructor = CanvasProjectionOverlay;
	CanvasProjectionOverlay.prototype.onAdd = function(){};
	CanvasProjectionOverlay.prototype.draw = function(){};
	CanvasProjectionOverlay.prototype.onRemove = function(){};

	var pos = new google.maps.LatLng(42.2808256, -83.7430378)
  var myOptions = {
	  center: pos,
    zoom: 15,
    mapTypeId: google.maps.MapTypeId.ROADMAP, 
		streetViewControl: false
  };
  map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);	
	
	google.maps.event.addListener(map, 'zoom_changed', function() {
		if (map.getZoom() < 12)
			map.setZoom(12);
	});

	

	infoBubble = new InfoBubble({
  	map: map,
		arrowStyle: 0, 
		arrowPosition: 20, 
		backgroundColor:'#333333', 
		shadowStyle: 1,
		borderRadius: 5,
		arrowSize: 17,
		borderWidth: 0,
		minWidth:250,
		minHeight:250,
		maxWidth:250, 
		maxHeight:250, 
		disableAutoPan:true,
		padding: 7,
		content: "Hello"
	});

	content = ""; // InfoBubble HTML source for details tab.

	geocoder = new google.maps.Geocoder();
	InitMarkers();


/*google.maps.event.addListener(map, 'tilesloaded', function()
{
	northeastBound = map.getBounds().ca.b;
	southwestBound = map.getBounds().ca.a;
	var strictBounds = new google.maps.LatLngBounds(
     northeastBound, 
     southwestBound 
  );	

	// Listen for the dragend event
   google.maps.event.addListener(map, 'dragend', function() {
     if (strictBounds.contains(map.getCenter())) return;

     // We're out of bounds - Move the map back within the bounds

     var c = map.getCenter(),
         x = c.lng(),
         y = c.lat(),
         maxX = strictBounds.getNorthEast().lng(),
         maxY = strictBounds.getNorthEast().lat(),
         minX = strictBounds.getSouthWest().lng(),
         minY = strictBounds.getSouthWest().lat();

     if (x < minX) x = minX;
     if (x > maxX) x = maxX;
     if (y < minY) y = minY;
     if (y > maxY) y = maxY;

     map.setCenter(new google.maps.LatLng(y, x));
   });
});
*/
}

function InitMarkers()
{

IdToMarkerMap = [];

<?php 
	$con = mysql_connect('localhost', 'root', 'root');
  $result = mysql_query("select * from ubid.Houses;");
  $size = mysql_num_rows($result);
  echo("Properties = [];");
  echo("AddressToPropertiesMap = [];");
	echo("ListingIdToAddressMap = [];");
	echo("var nextMarkerId=0;");
  for ($i = 0; $i < $size; $i++)
  {
  	echo("var property = {
  		\"address\": \"" . mysql_result($result, $i, "address") . "\", 
    	\"lat\": " . mysql_result($result, $i, "latitude") . ", 
      \"long\": " . mysql_result($result, $i, "longitude") . ", 
      \"lease_range\": \"" . mysql_result($result, $i, "lease_range") . "\", 
      \"unit_type\": \"" . mysql_result($result, $i, "unit_type") . "\", 
      \"unit_description\": \"" . mysql_result($result, $i, "unit_description") . "\", 
      \"beds\": " . mysql_result($result,$i, "beds") . ", 
      \"bathrooms\": " . mysql_result($result,$i, "bathrooms") . ", 
      \"rent\": " . mysql_result($result, $i, "rent") . ",
      \"company\": \"" . mysql_result($result, $i, "company") . "\", 
      \"electric\": \"" . mysql_result($result, $i, "electric") . "\", 
      \"water\": \"" . mysql_result($result, $i, "water") . "\", 
      \"heat\": \"" . mysql_result($result, $i, "heat") . "\", 
      \"air\": \"" . mysql_result($result, $i, "air") . "\", 
      \"parking\": \"" . mysql_result($result, $i, "parking") . "\", 
      \"furnished\": \"" . mysql_result($result, $i, "furnished") . "\", 
      \"listingid\": " . mysql_result($result, $i, "markerid") . ",
      };"); 
     
    echo("
   			if (property[\"address\"] in AddressToPropertiesMap)
				{
					property[\"markerid\"] = AddressToPropertiesMap[property[\"address\"]][0][\"markerid\"];
					AddressToPropertiesMap[property[\"address\"]].push(property);	
				}
				else
				{
					AddressToPropertiesMap[property[\"address\"]] = new Array();
					property[\"markerid\"] = nextMarkerId;
					nextMarkerId += 1;
					AddressToPropertiesMap[property[\"address\"]].push(property);
				}
				ListingIdToAddressMap[property[\"listingid\"]] = property[\"address\"];
				nextListingIds.push(parseInt(property[\"listingid\"]));
		");
  }
  $i = 0;
?> 

<?php 
/*	if ($_GET['minRent'])
  	$minRent = $_GET['minRent'];
  else
    $minRent = 0;
  if ($_GET['maxRent'])
    $maxRent = $_GET['maxRent']; 
  else
    $maxRent = 999999999;
  if ($_GET['minBeds'])
    $minBeds = $_GET['minBeds'];
  else
    $minBeds = 0;
  if ($_GET['maxBeds'])
    $maxBeds = $_GET['maxBeds']; 
  else
    $maxBeds = 999999999;
 */    
  echo("minRent = 0;");
  echo("maxRent = 999999;");
  echo("minBeds = 0;");
  echo("maxBeds = 999999;");
?>
	for (var address in AddressToPropertiesMap)
  {
  	var next = AddressToPropertiesMap[address];
//TODO: SOMETHING WITH DISPLAYING MULTIPLE LISTINGS FORMAT HERE IF next IS OF LENGTH > 1
		AddMarker(next[0]);
    
/*		google.maps.event.addListener(map, 'center_changed', function() {
			infoBubble.close();	
		});*/
		
		google.maps.event.addListener(map, 'click', function() {
			document.getElementById("tooltipWrapper").style.display = "none";
		}); 
  } 
}

function filterMarkers()
{
	var xmlhttp;
	nextMarkers = new Array(); // stores the markers that will be visible after applying the current filter
	if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  	xmlhttp=new XMLHttpRequest();
  }
	else
  {// code for IE6, IE5
  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }

	xmlhttp.onreadystatechange=function()
  {
  	if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
			var xml = xmlhttp.responseText;
			var xmlDoc;
			if (window.DOMParser)
			{
				var parser = new DOMParser();
				xmlDoc=parser.parseFromString(xml,"text/xml");
			}		
			else // Internet explorer
  		{
  			xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
  			xmlDoc.async=false;
  			xmlDoc.loadXML(xml); 
  		}

			var listingIdList = xmlDoc.getElementsByTagName("markeridlist");
			nextMarkers = [];		
			nextListingIds = [];
	
			for (var i = 0; i < listingIdList[0].childNodes.length; i++)
			{
				var listingId = listingIdList[0].childNodes[i].firstChild.nodeValue;
				nextListingIds.push(parseInt(listingId));
				if (nextMarkers.indexOf(AddressToPropertiesMap[ListingIdToAddressMap[parseInt(listingId)]][0]["markerid"]) == -1)
					nextMarkers.push(AddressToPropertiesMap[ListingIdToAddressMap[parseInt(listingId)]][0]["markerid"]);
			}

			for (var markerid in IdToMarkerMap)
			{
				if ($.inArray(parseInt(markerid), nextMarkers) != -1)
					IdToMarkerMap[markerid].setVisibility(true);
				else
					IdToMarkerMap[markerid].setVisibility(false);
			}

			/* Close infobubble if no longer visible
				 update list of units if some are now filtered out */
			var numListingsOnMarker = currentMarkerListingIds.length;
			var currentMarkerListingIdsChanged = false;
			for (var i = 0; i < currentMarkerListingIds.length; i++)
			{
				if ($.inArray(parseInt(currentMarkerListingIds[i]), nextMarkers) == -1)
				{
					currentMarkerListingsIdsChanged = true;
					numListingsOnMarker --;
				}
			}

			if (numListingsOnMarker == 0)
			{
				infoBubble.close();
				currentMarkerListingIds = [];
			}
			else if (numListingsOnMarker != currentMarkerListingIds.length)
			{		
				var property = AddressToPropertiesMap[ListingIdToAddressMap[currentMarkerListingIds[0]]];
				CreateInfoBubble(property, AddressToPropertiesMap[ListingIdToAddressMap[currentMarkerListingIds[i]]]);	
			}
		}
	}
	var fall =   document.getElementById("fallCheck").checked; 	
	var spring = document.getElementById("springCheck").checked; 	
	var other =  document.getElementById("otherCheck").checked; 	
	var house =  document.getElementById("houseCheck").checked; 	
	var apt =  document.getElementById("aptCheck").checked; 	
	var duplex =  document.getElementById("duplexCheck").checked; 	
	if (fall)
		fall = "true";
	else
		fall = "false";
	if (spring)
		spring = "true";
	else
		spring = "false";
	if (other)
		other = "true";
	else
		other = "false";
	if (house)
		house = "true";
	else
		house = "false";
	if (apt)
		apt = "true";
	else
		apt = "false";
	if (duplex)
		duplex = "true";
	else
		duplex = "false";

	xmlhttp.open("GET","filter.php?minRent=" + rentMin + "&maxRent=" + rentMax + "&minBeds=" + bedsMin + "&maxBeds=" + bedsMax + "&fall=" + fall + "&spring=" + spring + "&other=" + other + "&house=" + house + "&apt=" + apt + "&duplex=" + duplex, true);
	xmlhttp.send();
}

function AddMarker(next)
{
	nextMarkers.push(next["markerid"]);
/////////
    var latlng = new google.maps.LatLng(next["lat"], next["long"]);
    var myOptions = {
    	zoom: 4,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
    }

    var address_short = next["address"];
    var address = address_short + ", Ann Arbor, MI";
    var title = address_short + "\nCampus Management\n" + "$" + next["rent"] + "\nMin bedrooms: " + next["minBeds"] + "\nMax bedrooms: " + next["maxBeds"];
    var marker = new google.maps.Marker({
    	position: latlng,
			markerid: next["markerid"],
			address:  next["address"],
			icon: "Dots/available_dot.png"	
    });

    marker.setMap(map);
		google.maps.event.addListener(marker, 'click', function(event)
		{
			currentMarkerListingIds = [];
//			var tooltipOffset = getToolTipOffset(event.latlng);		
	
			//var tooltipWrap = document.getElementById("tooltipWrapper");
			var tooltip = document.getElementById("tooltip");
/*			var streetview = document.getElementById("streetviewDiv");

			tooltipWrap.style.display = "block";
			tooltipWrap.style.zIndex=3;
			tooltipWrap.style.left = pixelOffset.x + 10 + 'px';
			tooltipWrap.style.top = pixelOffset.y + 30 + 'px';	
		
			tooltip.style.display = "block";
			tooltip.style.zIndex=3;
			
			streetview.style.display = "none";
			streetview.style.zIndex=0;

			FirstTabClicked();
*/	
			// Update data values
		var xmlhttp;
	if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  	xmlhttp=new XMLHttpRequest();
  }
	else
  {// code for IE6, IE5
  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }

	xmlhttp.onreadystatechange=function()
  {
  	if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
			var xml = xmlhttp.responseText;
			var xmlDoc;
			if (window.DOMParser)
			{
				var parser = new DOMParser();
				xmlDoc=parser.parseFromString(xml,"text/xml");
			}		
			else // Internet Explorer
  		{
  			xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
  			xmlDoc.async=false;
  			xmlDoc.loadXML(xml); 
  		}
			
			var houseList = xmlDoc.getElementsByTagName("houseList");

			var houseData = houseList[0].getElementsByTagName("houseData");

// TODO: MAKE THIS A FOR LOOP TO LOOP THROUGH ALL CHILDNODES OF HOUSELIST
			var nextProperty = {}

			var address, lease_range, unit_type, unit_description, beds, bathrooms, rent, company, electric, water, heat, air, parking, furnished, url; 
			var utilities = "";

			address = houseData[0].getElementsByTagName("address")[0].firstChild.nodeValue;
			
			lease_range = houseData[0].getElementsByTagName("lease_range")[0].firstChild.nodeValue;
			if (lease_range == "Other")
				lease_range = "N/A";
			
			unit_type = houseData[0].getElementsByTagName("unit_type")[0].firstChild.nodeValue;
			if (unit_type == "?")
				unit_type = "N/A";
			
			unit_description = houseData[0].getElementsByTagName("unit_description")[0].firstChild.nodeValue;
			beds = houseData[0].getElementsByTagName("beds")[0].firstChild.nodeValue;
			if (beds == "?")
				beds = "N/A";
			bathrooms = houseData[0].getElementsByTagName("bathrooms")[0].firstChild.nodeValue;	
			if (bathrooms == "?")
				bathrooms = "N/A";
			rent = "$" + numberWithCommas(houseData[0].getElementsByTagName("rent")[0].firstChild.nodeValue);
			company = houseData[0].getElementsByTagName("company")[0].firstChild.nodeValue;
			
			electric = houseData[0].getElementsByTagName("electric")[0].firstChild.nodeValue;
			if (electric == "?")
				electric = "N/A";
			if (electric.indexOf("Y") != -1)
				utilities += "Electricity,";
			
			water = houseData[0].getElementsByTagName("water")[0].firstChild.nodeValue;
			if (water == "?")
				water = "N/A";
			if (water.indexOf("Y") != -1)
				utilities += "Water,";

			heat = houseData[0].getElementsByTagName("heat")[0].firstChild.nodeValue;
			if (heat == "?")
				heat = "N/A";
			if (heat.indexOf("Y") != -1)
				utilities += "Heat,";

			air = houseData[0].getElementsByTagName("air")[0].firstChild.nodeValue;
			if (air == "?")
				air = "N/A";

			parking = houseData[0].getElementsByTagName("parking")[0].firstChild.nodeValue;
			if (parking == "?")
				parking = "N/A";

			furnished = houseData[0].getElementsByTagName("furnished")[0].firstChild.nodeValue;
			if (furnished == "?")
				furnished="N/A";

			url = houseData[0].getElementsByTagName("url")[0].firstChild.nodeValue;

			utilities = (utilities.length == 0) ? "N/A" : utilities.substring(0, utilities.length - 1);

			nextProperty["address"] = address;
			nextProperty["lease_range"] = lease_range;
			nextProperty["unit_type"] = unit_type;
			nextProperty["unit_description"] = unit_description;
			nextProperty["beds"] = beds;
			nextProperty["bathrooms"] = bathrooms;
			nextProperty["rent"] = rent;
			nextProperty["company"] = company;
			nextProperty["electric"] = electric;
			nextProperty["water"] = water;
			nextProperty["heat"] = heat;
			nextProperty["air"] = air;
			nextProperty["parking"] = parking;
			nextProperty["furnished"] = furnished;
			nextProperty["url"] = url;
			nextProperty["listingid"] = next["listingid"];
			nextProperty["utilities"] = utilities;

			CreateInfoBubble(nextProperty, marker.markerid);	

		}
  }

	xmlhttp.open("GET","getMarkerid.php?address=" + marker.address, true);
	xmlhttp.send();

			//document.getElementById("tooltipPrice").
	
		});
		
	IdToMarkerMap[next["markerid"]] = marker;
	IdToMarkerMap[next["markerid"]].setVisibility = marker.setVisible;
}


function CreateInfoBubble(property, markerid)
{
	var listingid = property["listingid"];
	var marker = IdToMarkerMap[markerid];
	var starSource = "images/star.png";		
	if (favoritesListingIds.indexOf(parseInt(listingid)) != -1)
		starSource = "images/star_pushed.png";

	
	var content = "";
	if (AddressToPropertiesMap[property["address"]].length == 1)
	{	
		currentMarkerListingIds.push(parseInt(listingid));
		SetContentSingleListing(property, listingid, markerid);
	}	
	else
	{
		var marker = IdToMarkerMap[markerid];
		currentMarkerListingIds.push(parseInt(listingid));
		SetContentMultipleListings(property["address"], listingid, markerid, marker);		
	}
			
		infoBubble.open(map, marker);
		for (var i = 0; i < AddressToPropertiesMap[property["address"]].length; i++)
			$("#multiListing" + AddressToPropertiesMap[property["address"]][i]["listingid"]).click(function(event){alert(event.target.id)});

			
		var getPan = getToolTipOffset(IdToMarkerMap[markerid].position);
		if (getPan["needsToPan"])
			map.panTo(getPixelOffset(marker.position, getPan["tooltipOffset"]));

}

function UpdateInfoBubble()
{
	// determine if listings in infobubble have changed.
	for (var j = 0; j < currentMarkerListingIds.length; j++)
	{
		if (nextMarkers.indexOf(currentMarkerListingIds[i]) == -1)
		{
			var property = AddressToPropertiesMap[ListingIdToAddressMap[currentMarkerListingIds[i]]];
			CreateInfoBubble(property, AddressToPropertiesMap[ListingIdToAddressMap[currentMarkerListingIds[i]]]);	
		}
	}	
}

function SetContentSingleListing(property, listingid, markerid)
{
	var starSource = "images/star.png";		
			if (favoritesListingIds.indexOf(parseInt(listingid)) != -1)
				starSource = "images/star_pushed.png";
	var formattedRent = property["rent"];
	if (formattedRent[0] != "$")
		formattedRent = "$" + formattedRent;	

	var formattedBeds = property["beds"];
	if (formattedBeds == "?")
		formattedBeds = "N/A";
	else if (formattedBeds == 1)
		formattedBeds += " Bed";
	else
		formattedBeds += " Beds";

	var formattedBaths = property["bathrooms"];
	if (formattedBaths == "?")
		formattedBaths = "N/A";
	else if (formattedBaths == "1")
		formattedBaths += " Bath";
	else
		formattedBaths += " Baths";

	
		var urlContent = "";	
		if (property["url"] != "?" && property["url"] != "")
				urlContent = '<a href="' + property["url"] + '" target="_blank" id="property_link"><span class="tooltipData" id="tooltipCompany">' + property["company"] + '</span></a><br>	';
		else
				urlContent = '<span class="tooltipData" id="tooltipCompany">' + property["company"] + '</span><br>';
/*
TODO: GET DORM NAME IN TITLE INSTEAD OF ADDRESS
	var title = property["address"];
	if (property["unit_type"] == "Dorm")
		title = property["unit_description"];	*/


	var	content = '<div id="tooltip">' + 
					'<div id="addressRow">' + 
					'	<div id="tooltipAddress" class="tooltipLabel">' + property["address"] + '</div>' + 	
					'	<div id="addressRowButtons">' + 
					'		<img id="addFavoriteImg" src=' + starSource + ' onclick="EditFavorite(' + listingid + ', \'toggle\')"/>' + 		
					'	</div>' + 
					'</div>' + 
					'<div id="houseDataDiv">' + 
					'	<div id="dataLeftColumn" class="tooltipLabel">Price:<br>Beds:<br>Baths:<br>Lease:<br>Type:<br>Furnished:<br>Realtor:<br>Utilites:<br>Parking:<br>A/C:' +
					'	</div>' + 
					'	<div id="dataRightColumn">' + 							
					'		<span class="tooltipData" id="tooltipPrice">' + formattedRent + '</span><br>	' + 
					'		<span class="tooltipData" id="tooltipBeds">' + property["beds"] + '</span><br>	' + 
				'			<span class="tooltipData" id="tooltipBaths">' + property["bathrooms"] + '</span><br>	' +
					'		<span class="tooltipData">' + property["lease_range"] + '</span><br>' + 
					'		<span class="tooltipData" id="tooltipType"></span>' + property["unit_type"] + '<br>	' + 
					'		<span class="tooltipData" id="tooltipFurnished">' + property["furnished"] + '</span><br>	' + 
					urlContent + 	
					'		<span class="tooltipData" id="tooltipUtilities">' + property["utilities"] + '</span><br>	' + 
					'		<span class="tooltipData" id="tooltipParking">' + property["parking"] + '</span><br>	' + 
					'		<span class="tooltipData" id="tooltipAir">' + property["air"] + '</span><br>	' + 
					'	</div>' + 	
				'	</div>' + 
			'	</div>';

	
		infoBubble.setContent(content);

}

function SetContentMultipleListings(address, listingid, markerid, marker)
{
	
			/* Here is the multilisting tooltip */
			content = '<div id="tooltip">' + 
					'<div id="addressRow">' + 
					'	<div id="tooltipAddress" class="tooltipLabel">' + address + '</div>' + 	
					'</div>' + 
					'<div class="multiBubbleContainer">';
					for (var i = 0; i < AddressToPropertiesMap[address].length; i++)
					{
						property = AddressToPropertiesMap[address][i];
						if ($.inArray(property["listingid"], nextListingIds) == -1)
						{
							continue;	
						}

						var formattedRent = property["rent"];
						if (formattedRent[0] != "$")
							formattedRent = "$" + formattedRent;	

						var formattedBeds = property["beds"];
						if (formattedBeds == "?")
							formattedBeds = "N/A";
						else if (formattedBeds == 1)
							formattedBeds += " Bed";
						else
							formattedBeds += " Beds";

						var formattedBaths = property["bathrooms"];
						if (formattedBaths == "?")
							formattedBaths = "N/A";
						else if (formattedBaths == "1")
							formattedBaths += " Bath";
						else
							formattedBaths += " Baths";

						var formattedDescription= property["unit_description"];
						if (formattedDescription == "NA")
							formattedDescription = "Unit " + String(i + 1);
						var id="multiListing" + String(AddressToPropertiesMap[address][i]["listingid"]);
						content += '<div class="multiBubble" id=' + id + '><div class="multiBubbleText"><b>' + formattedDescription + '</b></br>' + formattedRent + ',  ' + formattedBeds + ', ' + formattedBaths + ', ' + property["lease_range"] + '</div></div>';			
					}
					content += '</div>' +
			'	</div>';
		
		infoBubble.setContent(content);
	
	
}

function ListingClicked(ev)
{
	alert(ev);
}

function getToolTipOffset(latlng)
{
	var scale = Math.pow(2, map.getZoom());
			var nw = new google.maps.LatLng(
    		map.getBounds().getNorthEast().lat(),
    		map.getBounds().getSouthWest().lng()
			);
			
			var worldCoordinateNW = map.getProjection().fromLatLngToPoint(nw);
			var worldCoordinate = map.getProjection().fromLatLngToPoint(latlng);
			var markerLocation = new google.maps.Point(
    		Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale),
    		Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale)
			);		
		
	
			var needsToPan = false;
			var tooltipOffset = new google.maps.Point(0, 0); //amount to shift center of map
			var width = $(document).width();
			var height = $(document).height();
			var TOOLTIP_HEIGHT = 215;
			var TOOLTIP_WIDTH_RIGHT = TOOLTIP_WIDTH * .8;
			var TOOLTIP_WIDTH_LEFT = TOOLTIP_WIDTH * .2;
			var MAP_CONTROL_BUFFER = 55;
			var TAB_HEIGHT = 80;
			var ARROW_HEIGHT = 30;
			var BOTTOM_BUFFER = 60; 
			// past right edge of screen	
			if (markerLocation.x + TOOLTIP_WIDTH_RIGHT + TOOLTIP_PADDING > width)
			{
				needsToPan = true;	
				tooltipOffset.x = markerLocation.x + TOOLTIP_WIDTH_RIGHT + TOOLTIP_PADDING - width;
			}
		
			// past left edge of screen	
			if (markerLocation.x - TOOLTIP_WIDTH_LEFT - MAP_CONTROL_BUFFER - TOOLTIP_PADDING < 0)
			{
				needsToPan = true;	
				tooltipOffset.x = markerLocation.x - TOOLTIP_WIDTH_LEFT - MAP_CONTROL_BUFFER - TOOLTIP_PADDING;
			}				
		
			// past top edge of screen	
			if (markerLocation.y - TOOLTIP_HEIGHT - TAB_HEIGHT - ARROW_HEIGHT < 0)
			{
				needsToPan = true;	
				tooltipOffset.y = markerLocation.y - TOOLTIP_HEIGHT - TAB_HEIGHT - ARROW_HEIGHT;
			}

			// past bottom edge of screen
			if (markerLocation.y > (height - BOTTOM_BUFFER))
			{
				needsToPan = true;	
				tooltipOffset.y = markerLocation.y - height + BOTTOM_BUFFER;
			}

			// past filter box region
			if ( (markerLocation.x + tooltipOffset.x + TOOLTIP_WIDTH_RIGHT + TOOLTIP_PADDING > FILTER_BOX_X_MIN) &&
					 (markerLocation.y + tooltipOffset.y - TOOLTIP_HEIGHT - TAB_HEIGHT - ARROW_HEIGHT < FILTER_BOX_Y_MIN + FILTER_BOX_HEIGHT))
			{
				needsToPan = true;	
				var oldX = tooltipOffset.x;
				var oldY = tooltipOffset.y;
				tooltipOffset.x = markerLocation.x + TOOLTIP_WIDTH_RIGHT + TOOLTIP_PADDING - FILTER_BOX_X_MIN;
				tooltipOffset.y = (markerLocation.y - TOOLTIP_HEIGHT - TAB_HEIGHT - ARROW_HEIGHT) - (FILTER_BOX_Y_MIN + FILTER_BOX_HEIGHT) + TOOLTIP_PADDING + 30;
				if (Math.abs(tooltipOffset.x) > Math.abs(tooltipOffset.y))
					tooltipOffset.x = oldX;
				else
					tooltipOffset.y = oldY;	
			}

		var retVal = {}
		retVal["needsToPan"] = needsToPan;
		retVal["tooltipOffset"] = tooltipOffset;
		return retVal;
}

function getPixelOffset(latlng, pixelOffset) 
{
	var canvasProjectionOverlay;
	canvasProjectionOverlay = new CanvasProjectionOverlay();
	canvasProjectionOverlay.setMap(map);	

	var centerX = $(document).width()/2;
	var centerY = ($(document).height() - TOP_MENU_HEIGHT)/2;

	var proj = canvasProjectionOverlay.getProjection();
	var markerPoint = proj.fromLatLngToContainerPixel(latlng);
	markerPoint.x = centerX + pixelOffset.x;
	markerPoint.y = centerY + pixelOffset.y;
	return proj.fromContainerPixelToLatLng(markerPoint);
}

</script>

