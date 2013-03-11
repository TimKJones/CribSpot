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
		var minRent, maxRent, minBeds, maxBeds;
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
            $size = mysql_num_rows($result);
            echo("Properties = [];");
            for ($i = 0; $i < $size; $i++)
            {
                echo("var property = {
                    \"address\": \"" . mysql_result($result, $i, "address") . "\", 
                    \"lat\": " . mysql_result($result, $i, "latitude") . ", 
                    \"long\": " . mysql_result($result, $i, "longitude") . ", 
                    \"type\": \"" . mysql_result($result, $i, "unit_type") . "\", 
                    \"minBeds\": " . mysql_result($result,$i, "minBeds") . ", 
                    \"maxBeds\": " . mysql_result($result, $i, "maxBeds") . ",
                    \"units\": " . mysql_result($result, $i, "units") . ",
                    \"units_avail\": " . mysql_result($result, $i, "units_avail") . ",
                    \"rent\": " . mysql_result($result, $i, "rent") . ",
                    \"company\": \"" . mysql_result($result, $i, "company") . "\"
                };"); 
                echo("
                    Properties.push(property);
                    ");
            }
            $i = 0;
?> 

<?php 
        if ($_GET['rentMin'])
            $minRent = $_GET['rentMin'];
        else
            $minRent = 0;
        if ($_GET['rentMax'])
            $maxRent = $_GET['rentMax']; 
        else
            $maxRent = 999999999;

        if ($_GET['bedsMin'])
            $minBeds = $_GET['bedsMin'];
        else
            $minBeds = 0;
        if ($_GET['bedsMax'])
            $maxBeds = $_GET['bedsMax']; 
        else
            $maxBeds = 999999999;
        
        echo("minRent = " . $minRent . ";");
        echo("maxRent = " . $maxRent . ";");
        echo("minBeds = " . $minBeds . ";");
        echo("maxBeds = " . $maxBeds . ";");
?>
	    for (var i = 0; i < Properties.length; i++)
    {
        var next = Properties[i];
        if (next["rent"] < minRent || next["rent"] > maxRent)
            continue;    
        if (next["minBeds"] < minBeds || next["maxBeds"] > maxBeds)
            continue;    

        var latlng = new google.maps.LatLng(next["lat"], next["long"]);
//alert(\"(" . mysql_result($result, $i, 'latitude') . ", " . mysql_result($result, $i, 'longitude') . ")\");
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
                title: title 
           });

           marker.setMap(map);
  
    } 
   }
   </script>
	<LINK href="mapsIndex.css" title="compact" rel="stylesheet" type="text/css">
   </head>
    <body onload="initialize()">
        <div id="pageContainer"> 
            <div id="filterBarContainer">
                <form id="filterForm" method="get" action="index.php">
                   <div class="leftAligned">Rent</div><div class="rightAligned" id="rentOutput"><span id="rentOutputMin">$0</span> - <span id="rentOutputMax">$10,000</span></div>
										<table>
											 <tr>
												 <td>
												 		<div>
                         			<hr class="bar rent" size=5px />
                         			<div class="drag box min rent" id = "rentBoxMin"></div>
						 						 			<input type="hidden" name="rentMin" type="text"/>
                         			<div class="drag box max rent" id = "rentBoxMax"></div>
						 									<input type="hidden" name="rentMax" type="text"/>
                       		  </div> 
												 </td>
											  </tr> 
										</table>
                   <div class="leftAligned">Bedrooms</div><div class="rightAligned" id="bedsOutput"><span id="bedsOutputMin">0</span> - <span id="bedsOutputMax">10+</span></div>
										<table>
											 <tr>
												 <td>
												 		<div>
                         			<hr class="bar beds" size=5px />
                         			<div class="drag box min beds" id = "bedsBoxMin"></div>
						 						 			<input type="hidden" name="bedsMin" type="text"/>
                         			<div class="drag box max beds" id = "bedsBoxMax"></div>
						 									<input type="hidden" name="bedsMax" type="text"/>
                       		  </div> 
												 </td>
											  </tr>
											 <tr> 
                         <td><button onclick="submitFilter()">Filter</button></td>
                       </tr>
                    </table>
                </form>
            </div>
            <div id="map_canvas">
            </div>
        </div>
  </body>

<script type="text/javascript" src="../jquery.js"></script>
<script type="text/javascript">

var _startX;    // mouse starting positions 
var _startY; 
var _offsetX = 0;       // current element offset 
var _offsetY = 0; 
var _dragElement;       // needs to be passed from OnMouseDown to OnMouseMove 
var barsArray;
var _rentBar;
var _rentBoxMin;
var _rentBoxMax;
var _bedsBoxMin;
var _bedsBoxMax;

var _clickElement; 
var _oldZIndex = 0;     // we temporarily increase the z-index during drag 
//var _debug = $('debug');        // makes life easier 
var output; 
var d_min; 
var d_max; 
var box_current;
var BAR_WIDTH;
var BAR_X_MIN;
var BOX_WIDTH = 43;
var RENT_MAX = 10000;
var BEDS_MAX = 10;
var value_rent_min = 0;
var value_rent_max = 10000;
var value_beds_min = 0;
var value_beds_max = 10;

//if boxes overlap
var needToDecide = false;
var initialPosition;

var elementOffset = 0;

InitDragDrop(); 
InitFilters();
 
function $(id) 
{ 
  return document.getElementById(id); 
}

function getPosX(el) {
    for (var lx=0;
         el != null;
         lx += el.offsetLeft, el = el.offsetParent);
    return lx;
}

function getPosY(el) {
    for (var ly=0;
         el != null;
         ly += el.offsetTop, el = el.offsetParent);
    return ly;
}

function hasClass(element, className)
{
	var classNameArray = element.className;
	var nextClass = "";
	for (var i = 0; i < classNameArray.length; i++)
	{
		while (i < classNameArray.length && classNameArray[i] != " ")
		{
			nextClass += classNameArray[i];
			i++;
		}

		if (nextClass == className)
			return true;
		
		nextClass = "";
	}

	return false;
}

function getOpposite(element, className)
{
	if (hasClass(element, "min"))
	{
		var eltArray = document.getElementsByClassName("max " + className);
		return eltArray[0];
	}
	else
	{
		var eltArray = document.getElementsByClassName("min " + className);
		return eltArray[0];
	}	
}

function getCurrentValue(element)
{
	var MAX_VAL = 0;
	var MIN_VAL = 0;	
	if (hasClass(element, "rent"))
	{
		MAX_VAL = RENT_MAX;
	}	
	else if (hasClass(element, "beds"))
	{
		MAX_VAL = BEDS_MAX;
	}

	var bar_x_min = _rentBar.offsetLeft;
	BAR_X_MIN = bar_x_min;
	var bar_x_max = bar_x_min + BAR_WIDTH;
	var currentPos = getPosX(element) - elementOffset;
	var percent = (currentPos - bar_x_min)/BAR_WIDTH;
	var value = (percent.toFixed(4) * MAX_VAL).toFixed(0);        
	if (value < 0)
		value = 0;
	return value;
}

function boxCanMove(element, nextPosition, className)
{
	var opposite = getOpposite(element, className);


	if (hasClass(element, "min"))
	{
		if (nextPosition <= getPosX(opposite))
			return true;
	}
	else
	{
		if (nextPosition >= getPosX(opposite))
			return true;
	}
	return false;
}

function InitFilters()
{
		var boxArray = document.getElementsByClassName("box rent min");	
		var rentBoxMin = boxArray[0];	
		boxArray = document.getElementsByClassName("box rent max");	
		var rentBoxMax = boxArray[0];	
		boxArray = document.getElementsByClassName("box beds min");	
		var bedsBoxMin = boxArray[0];	
		boxArray = document.getElementsByClassName("box beds max");	
		var bedsBoxMax = boxArray[0];	

		var RENT_MAX = 10000;
		var BEDS_MAX = 10;
		var BAR_WIDTH = 150;
		temp = document.getElementsByClassName("bar");
		var temp2 = temp[0];
		BAR_X_MIN = temp2.offsetLeft;
	var minRent, maxRent, minBeds, maxBeds;

<?php
		if ($_GET['rentMin'])
            $minRent = $_GET['rentMin'];
        else
            $minRent = 0;
        if ($_GET['rentMax'])
            $maxRent = $_GET['rentMax']; 
        else
            $maxRent = 10000;

        if ($_GET['bedsMin'])
            $minBeds = $_GET['bedsMin'];
        else
            $minBeds = 0;
        if ($_GET['bedsMax'])
            $maxBeds = $_GET['bedsMax']; 
        else
            $maxBeds = 10;
    
        echo("minRent = " . $minRent . ";");
        echo("maxRent = " . $maxRent . ";");
        echo("minBeds = " . $minBeds . ";");
        echo("maxBeds = " . $maxBeds . ";");
?>
		rentBoxMin.style.left = BAR_X_MIN + parseInt(minRent/RENT_MAX*BAR_WIDTH) + 'px';
		rentBoxMin.innerHTML = numberWithCommas(minRent);
		document.getElementById("rentOutputMin").innerHTML = "$" + numberWithCommas(minRent);
		
		rentBoxMax.style.left = BAR_X_MIN + parseInt(maxRent/RENT_MAX*BAR_WIDTH) + 'px';
		rentBoxMax.innerHTML = numberWithCommas(maxRent);
		document.getElementById("rentOutputMax").innerHTML = "$" + numberWithCommas(maxRent);

}


function InitDragDrop()
{	
		BAR_WIDTH = 150;
		_dragElement = null;
		
		var boxMinArray = document.getElementsByClassName("box min");
 		var boxMaxArray = document.getElementsByClassName("box max");

	
	
		var temp = document.getElementsByClassName("box min rent");
		_rentBoxMin = temp[0];

		temp = document.getElementsByClassName("bar");
		var temp2 = temp[0];
		BAR_X_MIN = temp2.offsetLeft;

		temp = document.getElementsByClassName("box max rent");
		_rentBoxMax = temp[0];
		
		temp = document.getElementsByClassName("box min beds");
		_bedsBoxMin = temp[0];

		temp = document.getElementsByClassName("box max beds");
		_bedsBoxMax = temp[0];


	  _startX = 0;
    _startY = 0;
    
		barsArray = document.getElementsByClassName("bar");
		var bar = barsArray[0];	
		_rentBar = bar;
	
		for (var i = 0; i < boxMinArray.length; i++)
		{
			boxMinArray[i].style.left=(getPosX(barsArray[i]))+'px';
			boxMinArray[i].style.top =(getPosY(barsArray[i])-4)+'px';
			boxMinArray[i].innerHTML = '0';
		}
		
		for (var i = 0; i < boxMaxArray.length; i++)
		{
			boxMaxArray[i].style.left=(getPosX(barsArray[i]) + BAR_WIDTH)+'px';
	  		boxMaxArray[i].style.top=(getPosY(barsArray[i])-4)+'px';
		}

		var rentBoxMax = document.getElementsByClassName("box max rent");
		var bedsBoxMax = document.getElementsByClassName("box max beds");

		rentBoxMax[0].innerHTML = '10,000';
		bedsBoxMax[0].innerHTML = '10+';	
			
		d_min = bar.offsetLeft;
    	d_max = bar.offsetLeft + bar.clientWidth;
      
		document.onmousedown = OnMouseDown;
      document.onmouseup = OnMouseUp;
      window.onresize = InitDragDrop;
      
	rentMin = 0;
	rentMax = 10000;
	bedsMin = 0;
	bedsMax = 10;
}


function OnMouseDown(e)
{
      if (e == null) 
          e = window.event; 
			initialPosition = e.clientX;

			var className = "";

      var target = e.target != null ? e.target : e.srcElement;
			var targetOpposite = null;	
			if (e.button == 0 && (hasClass(target, 'min') || hasClass(target, 'max')))
      {
					
					if (hasClass(target, "rent"))
						className = "rent";
					else if (hasClass(target, "beds"))
						className = "beds";
					targetOpposite = getOpposite(target, className);

					if (e.clientX >= targetOpposite.offsetLeft && e.clientX <= (targetOpposite.offsetLeft + BOX_WIDTH))
						needToDecide = true;
					else
						needToDecide = false;
	
  				elementOffset = e.clientX - e.target.offsetLeft;
				   _startX = e.clientX;
          _startY = e.clientY;

          // grab the clicked element's position
          _offsetX = ExtractNumber(target.style.left);
          _offsetY = ExtractNumber(target.style.top); 

          // we need to access the element in OnMouseMove
          _dragElement = target;

          // tell our code to start moving the element with the mouse
          document.onmousemove = OnMouseMove; 

          // cancel out any text selections
          document.body.focus();      
          
					return false;
      }
}


function ExtractNumber(value)
{
  var n = parseInt(value);
  return n == null || isNaN(n) ? 0 : n;
}


function numberWithCommas(x)
{
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function OnMouseMove(e)
{
  if (e == null) 
    var e = window.event; 

	if (_dragElement != null)
  {
		var className = "";
		if (hasClass(_dragElement, "rent"))
			className = "rent";
		else if (hasClass(_dragElement, "beds"))
			className = "beds";
	
		var opposite = getOpposite(_dragElement, className);

		if (needToDecide)
		{
			if (e.clientX > initialPosition)
			{	
					if (hasClass(_dragElement, "min"))
					{
						_dragElement = opposite;
					}
			}
			else
			{
				if (hasClass(_dragElement, "max"))
					_dragElement = opposite;
			}
			needToDecide = false;
		}	


	
		if (hasClass(_dragElement, "min"))
		{
			if (getPosX(_dragElement) > getPosX(opposite))
			{
				_dragElement.style.left = opposite.offsetLeft;
			}
		}

		if (hasClass(_dragElement, "max"))
		{
			if (getPosX(_dragElement) < getPosX(opposite))
			{
				_dragElement.style.left = opposite.offsetLeft;
			}
		}
	}

  var bar = barsArray[0];
  var bar_x = bar.offsetLeft;
  var bar_y = bar.offsetTop;
	var boxArray = document.getElementsByClassName("box");
	var box = boxArray[0];
	var boxWidth = box.clientWidth;

	var pastEdgeOfBar = false;

  // this is the actual "drag code"
  var next_loc = e.clientX - elementOffset;
  if (next_loc < bar_x)
	{
		pastEdgeOfBar = true;
    next_loc = bar_x;
	}
  else if (next_loc > (bar_x + bar.clientWidth))
	{
    next_loc =  bar_x + bar.clientWidth;
		pastEdgeOfBar = true;
	}
	else if (hasClass(_dragElement, "min"))
		{
			if (next_loc > getPosX(opposite))
			{
				next_loc = opposite.offsetLeft;
			}
		}
	else if (hasClass(_dragElement, "max"))
		{
			if (next_loc < getPosX(opposite))
			{
				next_loc = opposite.offsetLeft;
			}
		}

      
  var percent = (next_loc - bar_x)/bar.clientWidth;
	var boxValue = 0;
	var opposite = null;
	var className = ""; 
	var value_temp = 0;

var targetOutput = null;
var targetOutputOpposite = null;
	
	if (hasClass(_dragElement, "rent"))
		className = "rent";
	else if (hasClass(_dragElement, "beds"))
		className = "beds";

	var boxCanMoveVal = boxCanMove(_dragElement, next_loc, className);
	if (hasClass(_dragElement, "rent"))
	{	
		boxValue =  (percent.toFixed(4) * RENT_MAX).toFixed(0);        
		if (boxValue >= 1000)
		{
			boxValue = numberWithCommas(boxValue);
		}
		if (hasClass(_dragElement, "min"))
		{
			value_temp = value_rent_min;
			value_rent_min = boxValue;
			if (boxCanMoveVal)
			{
				targetOutput = document.getElementById("rentOutputMin");
				targetOutput.innerHTML = "$" + boxValue;	
			}
		}
		else
		{
			value_temp = value_rent_max;
			value_rent_max = boxValue;
			if (boxCanMoveVal)
			{
				targetOutput = document.getElementById("rentOutputMax");
				targetOutput.innerHTML = "$" + boxValue;	
			}
		}
	}
	else if (hasClass(_dragElement, "beds"))
	{
		boxValue =  (percent.toFixed(4) * BEDS_MAX).toFixed(0);        
		if (boxValue == 10)
			boxValue = '10+';

		if (hasClass(_dragElement, "min"))
		{
			value_temp = value_beds_min;
			value_beds_min = boxValue;
			if (boxCanMoveVal)
			{
				targetOutput = document.getElementById("bedsOutputMin");
				targetOutput.innerHTML = boxValue;	
			}
		}
		else
		{
			value_temp = value_beds_max;
			value_beds_max = boxValue;
			if (boxCanMoveVal)
			{
				targetOutput = document.getElementById("bedsOutputMax");
				targetOutput.innerHTML = boxValue;	
			}
		}
	}

	opposite = getOpposite(_dragElement, className);	

		if (boxCanMove(_dragElement, next_loc, className))
		{
			_dragElement.style.left = (next_loc + 3) + 'px';  
			_dragElement.innerHTML = boxValue;
		}
		else
		{
			if (hasClass(_dragElement, "rent"))
			{
				if (hasClass(_dragElement, "min"))
					value_rent_min = value_temp;
				else
					value_rent_max = value_temp;
			}
			else if (hasClass(_dragElement, "beds"))
			{
				if (hasClass(_dragElement, "min"))
					value_beds_min = value_temp;
				else
					value_beds_max = value_temp;
			}	
		}
}

function OnMouseUp(e)
{
  if (_dragElement != null)
  {
    // we're done with these events until the next OnMouseDown
    document.onmousemove = null;

    // this is how we know we're not dragging
    _dragElement = null;
  	rentMin = parseInt((_rentBoxMin.offsetLeft - BAR_X_MIN)/BAR_WIDTH * RENT_MAX); 
		if (rentMin < 0)
			rentMin = 0;
	 	rentMax = parseInt((_rentBoxMax.offsetLeft - BAR_X_MIN)/BAR_WIDTH * RENT_MAX);
		if (rentMax < 0)
			rentMax = 0;
		bedsMin = parseInt((_bedsBoxMin.offsetLeft - BAR_X_MIN)/BAR_WIDTH * BEDS_MAX); 
		if (bedsMin < 0)
			bedsMin = 0;
	 	bedsMax = parseInt((_bedsBoxMax.offsetLeft - BAR_X_MIN)/BAR_WIDTH * BEDS_MAX);
		if (bedsMax < 0)
			bedsMax = 0;
  }
}

function submitFilter()
{
	var filterForm = document.getElementById('filterForm');
	filterForm.elements["rentMin"].value = "'" + rentMin + "'";
	filterForm.elements["rentMax"].value = "'" + rentMax + "'";
	filterForm.elements["bedsMin"].value = "'" + bedsMin + "'";
	filterForm.elements["bedsMax"].value = "'" + bedsMax + "'";

	filterForm.submit();
}

</script>


</html> 
