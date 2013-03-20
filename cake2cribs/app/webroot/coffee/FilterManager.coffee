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
	start_date, end_date, minRent, maxRent, beds, house, apt, unit_type_other, male, female, students_only, grad, undergrad,
	bathroom_type, ac, parking, utilities_included, no_security_deposit
	###
	@ApplyFilter: (event, ui) ->
		#TODO: USE THE ACTUAL VALUES	
		ajaxData = null
		if event.id == "houseCheck"
			house = $("#houseCheck").is(':checked')	
			ajaxData = "house=" + house
		else if event.id == "aptCheck"
			apt = $("#aptCheck").is(':checked')
			ajaxData = "apt=" + apt
		else if event.id == "otherCheck"
			other = $("#otherCheck").is(':checked')
			ajaxData = "unit_type_other=" + other
		else if event.id == "maleCheck"
			other = $("#maleCheck").is(':checked')
			ajaxData = "male=" + other
		else if event.id == "femaleCheck"
			other = $("#femaleCheck").is(':checked')
			ajaxData = "female=" + other
		else if event.id == "studentsOnlyCheck"
			other = $("#studentsOnlyCheck").is(':checked')
			ajaxData = "students_only=" + other
		else if event.id == "gradCheck"
			other = $("#gradCheck").is(':checked')
			ajaxData = "grad=" + other
		else if event.id == "undergradCheck"
			other = $("#undergradCheck").is(':checked')
			ajaxData = "undergrad=" + other
		else if event.id == "acCheck"
			other = $("#acCheck").is(':checked')
			ajaxData = "ac=" + other
		else if event.id == "parkingCheck"
			other = $("#parkingCheck").is(':checked')
			ajaxData = "parking=" + other
		else if event.id == "utilitiesCheck"
			other = $("#utilitiesCheck").is(':checked')
			ajaxData = "utilities_included=" + other
		else if event.id == "noSecurityDepositCheck"
			other = $("#noSecurityDepositCheck").is(':checked')
			ajaxData = "no_security_deposit=" + other

		if (event.target) #event is not null only when when it corresponds to a slider-value-changed event
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
			url: myBaseUrl + "Sublets/ApplyFilter"
			type:"GET"
			data:ajaxData
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