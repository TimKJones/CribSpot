function LoadMarkerData(markerid)
{
	$.ajax({                    
        url: myBaseUrl + "Listings/LoadMarkerData",
        type:"GET",                                        
        data:"marker_id=" + markerid, 
				success: UpdateMarkerContent
  });	
}

function EditFavorite(listingid)
{
	$.ajax({
		url: myBaseUrl + "Favorites/EditFavorite",
		type:"GET",
		data:"listing_id=" + listingid,
			success: function(response){ alert(response) }
	});
}

/*
Send all values set from filter to the server.
*/
function ApplyFilter()
{
	/*var fall =   document.getElementById("fallCheck").checked; 	
	var spring = document.getElementById("springCheck").checked; 	
	var other =  document.getElementById("otherCheck").checked; 	
	var house =  document.getElementById("houseCheck").checked; 	
	var apt =  document.getElementById("aptCheck").checked; 	
	var duplex =  document.getElementById("duplexCheck").checked; */	
	var fall   = true;	
	var spring = true;	
	var other  = true;	
	var house  = true;
	var apt    = true;	
	var duplex = true; 	
	var minRent = 0;
	var maxRent = 999999;
	var minBeds = 0;
	var maxBeds = 999999;

	$.ajax({
		url: myBaseUrl + "Listings/ApplyFilter",
		type:"GET",
		data:"fall="     + fall   + 
			 "&spring="  + spring + 
			 "&other="    + other  +
			 "&house="    + house  +
			 "&apt="      + apt    +
			 "&duplex="   + duplex +
			 "&minRent="  + minRent+
			 "&maxRent="  + maxRent+
			 "&minBeds="  + minBeds+
			 "&maxBeds="  + maxBeds,
		success: UpdateMarkers
	});
}

/*
Receives list of markerIds that are now visible based on current filter settings.
Sets visibility of all markers based on this list.
*/
function UpdateMarkers(markerIdListResponse)
{
	alert(markerIdListResponse);
}

/*
Called after successful ajax call to retrieve all listing data for a specific marker_id.
Updates UI with retrieved data
*/
function UpdateMarkerContent(markerData)
{
	alert(markerData);
//	var decodedData = $.parseJSON(markerData);
	$("#tooltipAddressValue").html(markerData);

}
