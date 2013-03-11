
<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8" />
<meta name="author" content="Wolfgang Pichler" />
<link rel="canonical" href="http://www.wolfpil.de" />

<title>Info Window Tabs (v3)</title>

<style type="text/css">

 body, html {
	height:100%;
	width: 100%;
	margin:0;
 }

 div.wrapper { /* Outer infowindow size */
	width:320px;
	height:240px;
	margin: 6px;
	display: none;
 }

 div.tabs { position: relative;
	top: -44px;
	left: -24px;
	margin-bottom: -15px;
}

span.activeTab, span.passiveTab, span.hoverTab {
	-moz-border-radius-topleft: 8px;
	-moz-border-radius-topright: 8px;
	-webkit-border-top-left-radius: 8px;
	-webkit-border-top-right-radius: 8px;
	border-top-left-radius: 8px;
	border-top-right-radius: 8px;
}

 span.activeTab {
	margin-right:-5px;
	padding-left:7px;
	padding-right:7px;
	font-weight:bold;
	font-size:16px;
	border:1px solid #AAA;
	color:#5D5CA0;
	background-color:#FFF;
	border-bottom:2px solid #FFF;
 }

*span.activeTab {
	/* IE border top fix */
	zoom:1;
	/* IE border bottom fix */
	position:relative;
	bottom:-1px;
}

 span.passiveTab {
	margin-right:-5px;
	padding-left:8px;
	padding-right:8px;
	border:1px solid #AAA;
	font-size:16px;
	cursor:default;
	background-color:#E9E9E9;
	color:#006;
	border-bottom:2px solid #E9E9E9;
}

 span.hoverTab {
	margin-right:-5px;
	padding-left:7px;
	padding-right:7px;
	font-size:14px;
	border:none;
	border-bottom:2px solid #DCDCDC;
	cursor:pointer;
	background-color:#DCDCDC;
	color:#5676EA;
}

 div.cardContent { /* Inner infowindow size */
	width:260px; height:200px;
	padding: 0 0 0 0;
	overflow-y:auto;
font-size: 0.9em;
	display:none;
}

</style>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB1Wwh21ce7jnB6yDbjVGN3LC5ns7OoOL4&amp;sensor=false">
</script>

</head>
<body>


<div id="map" style="width: 100%; height: 100%; min-height: 420px;"></div>


<!-- Tabs of first marker -->
<div id="wrapper1" class="wrapper">
<div id="firstTabs" class="tabs">

<span data-name="1">Details</span>
<span data-name="2">Street View</span>

</div>

<div id="firstCard1" class="cardContent">
	Details
</div>

<div id="firstCard2" class="cardContent">
</div>


</div>


<!-- Tabs of second marker -->
<div id="wrapper2" class="wrapper">
<div id="secTabs" class="tabs">

<span data-name="1">Tab 1</span>
<span data-name="2">Street View</span>

</div>

<div id="secCard1" class="cardContent">

<b>Tabbed Info Window - Marker 2</b>

<p>Content...</p>
</div>

<div id="secCard2" class="cardContent">

<!-- Street View herein -->

</div>
</div>


<script type="text/javascript">
//<![CDATA[

 // Global variables
 var map, infowindow;

 /**
 * Constructor function
 * TabCard is based on HTML 5's id and data attributes
 * and completely map independent.
 */
function TabCard(tabid, cardid, point) {

 this.tabid = tabid;
 this.cardid = cardid;
 this.handleTabs = handleTabs;
 this.point = point;
 this.handleTabs(1);
}


function handleTabs(num) {

  var me = this;
  var tabsdiv = document.getElementById(this.tabid);
  this.newcard = this.cardid + num;
  if (!this.card) this.card = this.newcard;
  // Switch cards
  document.getElementById(this.card).style.display = "none";
  document.getElementById(this.newcard).style.display = "block";

  // Store active card
  this.card = this.newcard;

  // Handle tab events
  for (var i = 0, tab; tab = tabsdiv.getElementsByTagName("span")[i]; i++) {

    // Make clicked tab active and
    // unregister event listener for active tab
    if (tab.getAttribute("data-name") == num) {
     tab.className = "activeTab";
     tab.onmouseover = null;
     tab.onmouseout = null;
     tab.onclick = null;
    }
    // Register mouse event listener for tabs
    else {

     // Reset tabs
     tab.className = "passiveTab";

     tab.onmouseover = function() {
      this.className = "hoverTab";
     };

     tab.onmouseout = function() {
      this.className = "passiveTab";
     };

     tab.onclick = function() {
      // 'this' refers to the tab here
      var tabnum = this.getAttribute("data-name");
      me.handleTabs(tabnum);
      // Displays street view in tab #2 
      if (tabnum == 2) viewStreet(me.card, me.point);
     };
    }
  }
}


function viewStreet(div, point) {

  var g = google.maps;
  var pano = new g.StreetViewPanorama(document.getElementById(div), {
    position: point });
//  map.setStreetView(pano);
  pano.setVisible(true);
}

function createMarker(point, iw_content) {

  var g = google.maps;
  var marker = new g.Marker({
    position: point, map: map,
    clickable: true, draggable: false
  });

  g.event.addListener(marker, "click", function() {
   infowindow.setContent(iw_content);
   iw_content.style.display = "block";
   infowindow.open(map, this); 
  });
  return marker;
}


function buildMap() { // Create the map

  var g = google.maps;
  var point1 = new g.LatLng(50.819839,-1.365694);
  var point2 = new g.LatLng(51.310416,3.385415);
  var map_options = {
   center: new g.LatLng(51.055, 1.647),
   zoom: 5,
   mapTypeId: "roadmap",
   streetViewControl: false,
   mapTypeControlOptions: {
    style: g.MapTypeControlStyle.DEFAULT,
    mapTypeIds: [ g.MapTypeId.ROADMAP,
     g.MapTypeId.SATELLITE,
     g.MapTypeId.HYBRID,
     g.MapTypeId.TERRAIN]
   }
  };
  map = new g.Map(document.getElementById("map"), map_options);
  infowindow = new g.InfoWindow();

  // Info window tabs of first marker
  var iw_content = document.getElementById("wrapper1");
  // Required arguments for TabCard: (tabid, cardid without number) 
  var tabs = new TabCard("firstTabs", "firstCard", point1);
  var marker1 = createMarker(point1, iw_content);

  // Info window tabs of second marker
  var iw_content = document.getElementById("wrapper2");
  var tabs = new TabCard("secTabs", "secCard", point2);
  var marker2 = createMarker(point2, iw_content);
}

window.onload = buildMap;


//]]>
</script>

</body>
</html>

