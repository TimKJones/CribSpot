class A2Cribs.FilterManager
	@MinRent = 0
	@MaxRent = 999999
	@MaxSliderRent = 4000
	@MinBeds = 0
	@MaxBeds = 999999
	@MaxSliderBeds = 10
	#@PlacesService = null #Google Places Service for address search
	@Geocoder = null

	@UpdateMarkers: (visibleMarkerIds) ->
		visibleMarkerIds = JSON.parse visibleMarkerIds
		#map.VisibleMarkers = []
		for marker, markerid in A2Cribs.Map.IdToMarkerMap
			if markerid.toString() in visibleMarkerIds
				if (marker)
					#map.VisibleMarkers.push(marker.GMarker)
					marker.GMarker.setVisible true
			else
				if (marker)
					marker.GMarker.setVisible false
		
		#map.GMarkerClusterer.setMap(null)
		#map.GMarkerClusterer = new MarkerClusterer(@GMap, map.VisibleMarkers, mcOptions)
		A2Cribs.Map.GMarkerClusterer.repaint()

	###
	Called immediately after user applies a filter.
	###
	@ApplyFilter: (event, ui) ->
		#TODO: USE THE ACTUAL VALUES	
		fall    = $("#fallCheck").is(':checked')	
		spring  = $("#springCheck").is(':checked')	
		other   = $("#otherCheck").is(':checked')
		house   = $("#houseCheck").is(':checked')
		apt     = $("#aptCheck").is(':checked')	
		duplex  = $("#duplexCheck").is(':checked') 	

		if (event) #event is not null only when when it corresponds to a slider-value-changed event
			if (event.target.id == "rentSlider")
				A2Cribs.FilterManager.MinRent = ui.values[0]
				A2Cribs.FilterManager.MaxRent = ui.values[1]
				if A2Cribs.FilterManager.MaxRent == A2Cribs.FilterManager.MaxSliderRent
					A2Cribs.FilterManager.MaxRent = 999999
			else
				A2Cribs.FilterManager.MinBeds = ui.values[0]
				A2Cribs.FilterManager.MaxBeds = ui.values[1]
				if A2Cribs.FilterManager.MaxBeds == A2Cribs.FilterManager.MaxSliderBeds
					A2Cribs.FilterManager.MaxBeds = 999999
		$.ajax
			url: myBaseUrl + "Listings/ApplyFilter"
			type:"GET"
			data:"fall="      + fall   + 
				 "&spring="   + spring + 
				 "&other="    + other  +
				 "&house="    + house  +
				 "&apt="      + apt    +
				 "&duplex="   + duplex +
				 "&minRent="  + A2Cribs.FilterManager.MinRent+
				 "&maxRent="  + A2Cribs.FilterManager.MaxRent+
				 "&minBeds="  + A2Cribs.FilterManager.MinBeds+
				 "&maxBeds="  + A2Cribs.FilterManager.MaxBeds
			context: this
			success: A2Cribs.FilterManager.UpdateMarkers

	###
	Initialize the underlying google maps functionality of the address search bar
	###
	@InitAddressSearch: ->
		#@PlacesService = new google.maps.places.PlacesService(@GMap)
		A2Cribs.FilterManager.Geocoder = new google.maps.Geocoder()

	@AddressSearchCallback: (response, status) ->
		# Need to detect invalid addresses
		if status == google.maps.GeocoderStatus.OK && response[0].types[0] != "postal_code"
			$("#addressSearchBar").effect("highlight", {color: "#5858FA"}, 2000)
			A2Cribs.Map.GMap.panTo response[0].geometry.location
			A2Cribs.Map.GMap.setZoom(18)
			if (response[0].address_components.length >= 2)
				formattedAddress = response[0].address_components[0].short_name + " " + response[0].address_components[1].short_name
				if A2Cribs.Map.AddressToMarkerIdMap[formattedAddress]
					alert A2Cribs.Map.AddressToMarkerIdMap[formattedAddress]
		else
			$("#addressSearchBar").effect("highlight", {color: "#FF0000"}, 2000)

	@SearchForAddress: ->
		address = $("#addressSearchBar").val()
		request = 
			location: A2Cribs.Map.GMap.getCenter()
			radius: 8100 # in meters (approximately 5 miles)
			types: ['street_address', 'street_number', 'postal_code', 'postal_code_prefix', 'point_of_interest', 'neighborhood', 'intersection', 'transit_station']
			keyword: address
			name: address
		A2Cribs.FilterManager.Geocoder.geocode({ 'address' : address + " Ann Arbor, MI 48104"}, A2Cribs.FilterManager.AddressSearchCallback)
		#@PlacesService.nearbySearch(request, @AddressSearchCallback)