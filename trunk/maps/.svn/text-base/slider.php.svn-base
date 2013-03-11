<script type="text/javascript">

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

function boxCanMove(element, nextPosition, className)
{
	var opposite = getOpposite(element, className);

	if (hasClass(element, "min"))
	{
		if (nextPosition <= getPosX(opposite) - FILTER_BOX_X_MIN)
			return true;
	}
	else
	{
		if (nextPosition >= getPosX(opposite) - FILTER_BOX_X_MIN)
			return true;
	}
	return false;
}

function InitDragDrop()
{
		//TOOLTIP_TAB_HEIGHT = document.getElementById("tooltipTabs").clientHeight;
	
		BAR_WIDTH = 165;
		_dragElement = null;
		
		var boxMinArray = document.getElementsByClassName("box min");
 		var boxMaxArray = document.getElementsByClassName("box max");
	
		var temp = document.getElementsByClassName("box min rent");
		_rentBoxMin = temp[0];

		FILTER_BOX_PADDING = 11;	
		temp = document.getElementById("filterBoxBackground");
		FILTER_BOX_X_MIN = temp.offsetLeft + FILTER_BOX_PADDING;
		FILTER_BOX_Y_MIN = temp.offsetTop;

		temp = document.getElementsByClassName("bar");
		var temp2 = temp[0];
		BAR_X_MIN = getPosX(temp2) - FILTER_BOX_X_MIN;
		BAR_X_MAX = BAR_X_MIN + BAR_WIDTH - BOX_WIDTH;

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
			boxMinArray[i].style.left = BAR_X_MIN + 'px';
			boxMinArray[i].style.top = getPosY(barsArray[i]) - FILTER_BOX_Y_MIN - 13 +'px';
		}
		
		for (var i = 0; i < boxMaxArray.length; i++)
		{
			boxMaxArray[i].style.left = BAR_X_MAX + 'px';
	  	boxMaxArray[i].style.top = getPosY(barsArray[i])- FILTER_BOX_Y_MIN - 13 +'px';
		}

		var rentBoxMax = document.getElementsByClassName("box max rent");
		var bedsBoxMax = document.getElementsByClassName("box max beds");

//		rentBoxMax[0].innerHTML = String(numberWithCommas(RENT_MAX));
//		bedsBoxMax[0].innerHTML = '10+';	
			
		d_min = bar.offsetLeft;
    d_max = bar.offsetLeft + bar.clientWidth;
      
		document.onmousedown = OnMouseDown;
		document.onmousemove = OnMouseMove;
    document.onmouseup = OnMouseUp;
    window.onresize = InitDragDrop;
      
	rentMin = 0;
	rentMax = RENT_MAX;
	bedsMin = 0;
	bedsMax = BEDS_MAX;
}

function getClassName(element)
{
	var className;
	if (hasClass(element, "rent"))
		className = "rent";
	else if (hasClass(element, "beds"))
		className = "beds";
	return className;
}

function InitFilters()
{
//	_rentBoxMin.innerHTML = "0";
//	_rentBoxMax.innerHTML = String(numberWithCommas(RENT_MAX)) + "+";
	document.getElementById("rentMin").value = "$0";
	document.getElementById("rentMax").value = "$" + String(numberWithCommas(RENT_MAX)) + "+";

//	_bedsBoxMin.innerHTML = "0";
//	_bedsBoxMax.innerHTML = "10+";
	document.getElementById("bedsMin").value = "0"
	document.getElementById("bedsMax").value = "10+";

	var minRent, maxRent, minBeds, maxBeds;
	var dispMinRent, dispMaxRent, dispMinBeds, dispMaxBeds;

<?php
/*		if ($_GET['minRent'])
    	$minRent = $_GET['minRent'];
    else
      $minRent = 0;
    if ($_GET['maxRent'])
      $maxRent = $_GET['maxRent']; 
    else
      $maxRent = 4000;

    if ($_GET['minBeds'])
      $minBeds = $_GET['minBeds'];
    else
      $minBeds = 0;
    if ($_GET['maxBeds'])
      $maxBeds = $_GET['maxBeds']; 
    else
      $maxBeds = 10;
    
    echo("minRent = " . $minRent . ";");
    echo("maxRent = " . $maxRent . ";");
    echo("minBeds = " . $minBeds . ";");
    echo("maxBeds = " . $maxBeds . ";");*/
?>
	// Check for strange parameter values (if user manually enters them into URL)
/*
	if (minRent > maxRent)
		minRent = maxRent;
	else if (maxRent < minRent)
		maxRent = minRent;
	if (minBeds > maxBeds)
		minBeds = maxBeds;
	else if (maxBeds < minBeds)
		maxBeds = minBeds;

	if (minRent < 0)
		minRent = 0;
	if (maxRent < 0)
		maxRent = 0;
	if (minBeds < 0)
		minBeds = 0;
	if (maxBeds < 0)
		maxBeds = 0;

	if (minRent > RENT_MAX)
		minRent = RENT_MAX;
	if (maxRent > RENT_MAX)
		maxRent = RENT_MAX;
	if (minBeds > BEDS_MAX)
		minBeds = BEDS_MAX;
	if (maxBeds > BEDS_MAX)
		maxBeds = BEDS_MAX;
*/
	minRent = 0;
	maxRent = RENT_MAX;
	minBeds = 0;
	maxBeds = BEDS_MAX;

	dispMinRent = minRent;
	dispMaxRent = maxRent;
	dispMinBeds = minBeds;
	dispMaxBeds = maxBeds;

	// RentMin
	rentIncrement = RENT_MAX/NUM_RENT_BAR_DIVISIONS;
	pixelIncrement = (BAR_WIDTH-BOX_WIDTH)/NUM_RENT_BAR_DIVISIONS;
	numDivisions = minRent/rentIncrement;
	rentBoxMin.style.left = BAR_X_MIN + pixelIncrement*numDivisions;
//	rentBoxMin.innerHTML = numberWithCommas(minRent);	
	document.getElementById("rentMin").value = "$" + numberWithCommas(minRent);
	rentMin = minRent;

	// RentMax
	numDivisions = maxRent/rentIncrement;
	rentBoxMax.style.left = BAR_X_MIN + pixelIncrement*numDivisions;
	
	if (maxRent == RENT_MAX)
		dispMaxRent = numberWithCommas(maxRent) + "+";
	else
		dispMaxRent = numberWithCommas(maxRent);
	document.getElementById("rentMax").value = "$" + dispMaxRent;
//	rentBoxMax.innerHTML = dispMaxRent;	
	rentMax = maxRent;
	
	// BedsMin
	bedsIncrement = BEDS_MAX/NUM_BEDS_BAR_DIVISIONS;
	pixelIncrement = (BAR_WIDTH-BOX_WIDTH)/NUM_BEDS_BAR_DIVISIONS;
	numDivisions = minBeds/bedsIncrement;
	bedsBoxMin.style.left = BAR_X_MIN + pixelIncrement*numDivisions;
//	bedsBoxMin.innerHTML = numberWithCommas(minBeds);
	var formattedBeds = minBeds;
	if (minBeds == 10)
		formattedBeds = "10+";	
	document.getElementById("bedsMin").value = formattedBeds;
	bedsMin = minBeds;

	// BedsMax
	bedsIncrement = BEDS_MAX/NUM_BEDS_BAR_DIVISIONS;
	pixelIncrement = (BAR_WIDTH-BOX_WIDTH)/NUM_BEDS_BAR_DIVISIONS;
	numDivisions = maxBeds/bedsIncrement;
	bedsBoxMax.style.left = BAR_X_MIN + pixelIncrement*numDivisions;
	var formattedBeds = maxBeds;
	if (maxBeds == 10)
		formattedBeds = "10+";	
	document.getElementById("bedsMax").value = formattedBeds;
//	bedsBoxMax.innerHTML = numberWithCommas(formattedBeds);
	bedsMax = maxBeds;
}

function OnMouseDown(e)
{
		oldMinRent = rentMin;
		oldMaxRent = rentMax;
		oldMinBeds = bedsMin;
		oldMaxBeds = bedsMax;

      if (e == null) 
          e = window.event; 
			initialPosition = e.clientX;

			var className = "";

      var target = e.target != null ? e.target : e.srcElement;
			var targetOpposite = null;	
			if (e.button == 0 && (hasClass(target, 'min') || hasClass(target, 'max')))
      {
					className = getClassName(target);
	
					targetOpposite = getOpposite(target, className);

					// Determine if both boxes are at the clicked position
					if (e.clientX >= getPosX(targetOpposite) && e.clientX <= (getPosX(targetOpposite) + BOX_WIDTH))
						needToDecide = true;
					else
						needToDecide = false;

	
  				elementOffset = e.clientX - e.target.offsetLeft;
					elementOppositeOffset = e.clientX - targetOpposite.offsetLeft;
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

function OnMouseMove(e)
{
  if (e == null) 
    var e = window.event; 
	
	if (_dragElement != null)
  {
		var className = getClassName(_dragElement);
		var opposite = getOpposite(_dragElement, className);

		// If boxes overlap, set clicked box based on initial movement
		if (needToDecide)
		{
			if (e.clientX > initialPosition)
			{	
					if (hasClass(_dragElement, "min"))
					{
						_dragElement = opposite;
						elementOffset = elementOppositeOffset;
					}
			}
			else
			{
				if (hasClass(_dragElement, "max"))
				{
					_dragElement = opposite;	
					elementOffset = elementOppositeOffset;
				}
			}
			needToDecide = false;
		}	

  var bar = barsArray[0];
	var bar_x = getPosX(_rentBar) - FILTER_BOX_X_MIN - 3;
  var bar_y = bar.offsetTop;
	var boxArray = document.getElementsByClassName("box");
	var box = boxArray[0];
	var boxWidth = box.clientWidth;

  // this is the actual "drag code"
  var next_loc = e.clientX - elementOffset;
 

	// check if drag would extend box past min or max box
	// set the min and max bounds for dragging
	var minBound = BAR_X_MIN;
	var maxBound = BAR_X_MAX; 

	if (hasClass(_dragElement, "min"))
	{
		// can't drag beyond max box
		maxBound = opposite.offsetLeft;
	}
	else if (hasClass(_dragElement, "max"))
	{
		// can't drag beyond min box
		minBound = opposite.offsetLeft;
	}


	// check if next_loc goes beyond min or max bounds 	
	if (next_loc < minBound)
		next_loc = minBound;
	else if (next_loc > maxBound)
		next_loc = maxBound;
    
	// Determine Box Values
  
  var percent = (next_loc - BAR_X_MIN)/(BAR_WIDTH - BOX_WIDTH);
	var boxValue = 0;
	var opposite = null;
	var className = getClassName(_dragElement); 

	var targetOutput = null;
	var targetOutputOpposite = null;
	
	var boxCanMoveVal = boxCanMove(_dragElement, next_loc, className);
	
	
	// Determine Maximum Value for current box
	// Determine target text box to update with current box movement 
	var MAX_VAL = 0;
	if (className == "rent")
	{
		MAX_VAL = RENT_MAX;
		if (hasClass(_dragElement, "min"))
			targetOutput = document.getElementById("rentMin");
		else
			targetOutput = document.getElementById("rentMax");	
	}
	else if (className == "beds")
	{
		MAX_VAL = BEDS_MAX;		
		if (hasClass(_dragElement, "min"))
			targetOutput = document.getElementById("bedsMin");
		else
			targetOutput = document.getElementById("bedsMax");	
	}

	
	boxValue =  (percent.toFixed(4) * MAX_VAL).toFixed(0);        
	if (boxCanMoveVal)
		targetOutput.value = "";

	// Round to nearest multiple of minimum increment
	if (className == "rent")	
	{
		var increment =  RENT_MAX/NUM_RENT_BAR_DIVISIONS;	
		var closestDivision = Math.round(boxValue/increment);	
		var roundedBoxValue = closestDivision * increment;
		boxValue = roundedBoxValue;
		if (hasClass(_dragElement, "min"))
			rentMin = boxValue;
		else
			rentMax = boxValue;

		var pixelIncrement = (BAR_WIDTH - BOX_WIDTH)/NUM_RENT_BAR_DIVISIONS;
		next_loc = closestDivision * pixelIncrement + BAR_X_MIN;	
	}
	else if (className == "beds")	
	{
		var increment =  BEDS_MAX/NUM_BEDS_BAR_DIVISIONS;	
		var closestDivision = Math.round(boxValue/increment);	
		var roundedBoxValue = closestDivision * increment;
		boxValue = roundedBoxValue;

		if (hasClass(_dragElement, "min"))
			bedsMin = boxValue;
		else
			bedsMax = boxValue;

		var pixelIncrement = (BAR_WIDTH - BOX_WIDTH)/NUM_BEDS_BAR_DIVISIONS;
		next_loc = closestDivision * pixelIncrement + BAR_X_MIN;	
	}	
	// format boxValue based on its category
	var isMax = false;
	if (className == "beds")
	{
		if (boxValue == 10)
			boxValue = '10+';
	}		
	else if (className == "rent")
	{
		var temp = boxValue;
		if (boxValue >= 1000)
		{
			boxValue = numberWithCommas(boxValue);
		}
		if (temp == RENT_MAX)
			boxValue += "+";	
		if (boxCanMoveVal)
				targetOutput.value = "$";
	}

	
	// update target text box with new value
	if (boxCanMoveVal)
	{	
		targetOutput.value += boxValue;
	}

	opposite = getOpposite(_dragElement, className);	

	if (boxCanMoveVal)
	{
		_dragElement.style.left = (next_loc) + 'px';  
//		_dragElement.innerHTML = boxValue;
	}
	
	}
}

function OnMouseUp(e)
{
  if (_dragElement != null)
  {
    document.onmousemove = null;

    // this is how we know we're not dragging
    _dragElement = null;
		
		ApplyFilters();
	}
}


function ApplyFilters(event)
{
	if (rentMin != oldMinRent || rentMax != oldMaxRent || bedsMin != oldMinBeds || bedsMax != oldMaxBeds || event != null)
		EraseVisibility();	
	filterMarkers();
}
function EraseVisibility()
{
/*	for (var markerid in markers)
	{
		markers[markerid].setVisibility(false);
	}*/
	infoBubble.close();
}

function submitFilter()
{
	var filterForm = document.getElementById('filterForm');	
	rentMin = String(rentMin);	
	rentMax = String(rentMax);
	bedsMin = String(bedsMin);
	bedsMax = String(bedsMax);

	if (rentMin.indexOf("+") != -1)
		rentMin = rentMin.substring(0,rentMin.length -1);
	if (rentMax.indexOf("+") != -1)
		rentMax = rentMax.substring(0,rentMax.length -1);
	if (bedsMin.indexOf("+") != -1)
		bedsMin = bedsMin.substring(0,bedsMin.length -1);
	if (bedsMax.indexOf("+") != -1)
		bedsMax = bedsMax.substring(0,bedsMax.length -1);


	

/*	filterForm.elements["minRent"].value = rentMin;
	filterForm.elements["maxRent"].value = rentMax;
	filterForm.elements["minBeds"].value = bedsMin;
	filterForm.elements["maxBeds"].value = bedsMax;
*/
//	filterForm.submit();
}

function addressTextSubmit()
{	
	var address = document.getElementById("addressText").value + " Ann Arbor MI 48104";
	var lattitude, longitude;
	geocoder.geocode( { 'address': address}, function(results, status) 
  {
  	if (status == google.maps.GeocoderStatus.OK) 
    {              
    	lattitude = parseFloat(getLat(results[0].geometry.location));
			longitude = parseFloat(getLong(results[0].geometry.location));
			/*var myOptions = {
  			center: new google.maps.LatLng(lattitude, longitude),
    		zoom: 18,
   	 		mapTypeId: google.maps.MapTypeId.ROADMAP
  		};

 			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);*/

		
    var latlng = new google.maps.LatLng(lattitude, longitude);

		map.panTo(latlng);	
		map.setZoom(25);
    } 
  });		
}

</script>

