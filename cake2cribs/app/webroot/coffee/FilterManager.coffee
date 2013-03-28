class A2Cribs.FilterManager
	@MinRent = 0
	@MaxRent = 999999
	@MaxSliderRent = 2000
	@MinBeds = 0
	@MaxBeds = 999999
	@MaxSliderBeds = 10
	@DateBegin = 'NOT_SET'
	@DateEnd = 'NOT_SET'
	#@PlacesService = null #Google Places Service for address search
	@Geocoder = null
	#@CurrentCity = null
	#@CurrentState = null

	@UpdateMarkers: (visibleMarkerIds) ->
		visibleMarkerIds = JSON.parse visibleMarkerIds
		#map.VisibleMarkers = []
		for marker, markerid in A2Cribs.Cache.IdToMarkerMap
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

	@WheneverButtonClicked: (event) ->
		if $("#startDate").datepicker().valueOf()[0].value == "Whenever"
			A2Cribs.FilterManager.DateBegin = "NOT_SET"
		if $("#endDate").datepicker().valueOf()[0].value == "Whenever"
			A2Cribs.FilterManager.DateEnd = "NOT_SET"

		A2Cribs.FilterManager.ApplyFilter()

	###
	Called immediately after user applies a filter.
	start_date, end_date, minRent, maxRent, beds, house, apt, unit_type_other, male, female, students_only, grad, undergrad,
	bathroom_type, ac, parking, utilities_included, no_security_deposit
	###
	@ApplyFilter: (event, ui) ->
		#TODO: USE THE ACTUAL VALUES
		A2Cribs.Map.ClickBubble.Close()	
		ajaxData = null
		#if event.id == "houseCheck"
		house = $("#houseCheck").is(':checked')	
		ajaxData = "house=" + house
	#else if event.id == "aptCheck"
		apt = $("#aptCheck").is(':checked')
		ajaxData += "&apt=" + apt
	#else if event.id == "otherCheck"
		other = $("#otherCheck").is(':checked')
		ajaxData += "&unit_type_other=" + other
	#else if event.id == "maleCheck"
		male = $("#maleCheck").is(':checked')
		ajaxData += "&male=" + male
	#else if event.id == "femaleCheck"
		female = $("#femaleCheck").is(':checked')
		ajaxData += "&female=" + female
	#else if event.id == "studentsOnlyCheck"
		students_only = $("#studentsOnlyCheck").is(':checked')
		ajaxData += "&students_only=" + students_only
	#else if event.id == "gradCheck"
		grad = $("#gradCheck").is(':checked')
		ajaxData += "&grad=" + grad
	#else if event.id == "undergradCheck"
		undergrad = $("#undergradCheck").is(':checked')
		ajaxData += "&undergrad=" + undergrad
	#else if event.id == "acCheck"
		ac = $("#acCheck").is(':checked')
		ajaxData += "&ac=" + ac
	#else if event.id == "parkingCheck"
		parking = $("#parkingCheck").is(':checked')
		ajaxData += "&parking=" + parking
	#else if event.id == "utilitiesCheck"
		utilities = $("#utilitiesCheck").is(':checked')
		ajaxData += "&utilities_included=" + utilities
	#else if event.id == "noSecurityDepositCheck"
		no_security_deposit = $("#noSecurityDepositCheck").is(':checked')
		ajaxData += "&no_security_deposit=" + no_security_deposit
	#else if event.id == "bedsSelect"
		beds = $("#bedsSelect").val()
		if beds == "2+"
			beds = "2"
		ajaxData += "&beds=" + beds

		bathroom_type = $("#bathSelect").val()
		ajaxData += "&bathroom_type=" + bathroom_type

		if (event.target != undefined && event.target.id == "slider") #event is not null only when when it corresponds to a slider-value-changed event
			A2Cribs.FilterManager.MinRent = event.value[0]
			A2Cribs.FilterManager.MaxRent = event.value[1]
			if A2Cribs.FilterManager.MaxRent == A2Cribs.FilterManager.MaxSliderRent
				A2Cribs.FilterManager.MaxRent = 999999

		if (event.target != undefined && event.target.id == "startDate") #event is not null only when when it corresponds to a slider-value-changed event
			eventDate = event.valueOf().date
			if A2Cribs.FilterManager.DateEnd != "NOT_SET" and A2Cribs.FilterManager.DateEnd != "Whenever" && eventDate > new Date(A2Cribs.FilterManager.DateEnd)
				A2Cribs.UIManager.Alert "Start Date cannot occur after End Date."
				A2Cribs.FilterManager.DateBegin = new Date(A2Cribs.FilterManager.DateEnd)
				A2Cribs.FilterManager.StartDateObject.setValue(A2Cribs.FilterManager.DateBegin)
				return
			A2Cribs.FilterManager.DateBegin = A2Cribs.FilterManager.GetFormattedDate eventDate

		if (event.target != undefined && event.target.id == "endDate") #event is not null only when when it corresponds to a slider-value-changed event
			eventDate = event.valueOf().date
			if A2Cribs.FilterManager.DateBegin != "NOT_SET" and A2Cribs.FilterManager.DateBegin != "Whenever" and eventDate < new Date(A2Cribs.FilterManager.DateBegin)
				A2Cribs.UIManager.Alert "End Date cannot occur before Start Date."
				A2Cribs.FilterManager.DateEnd= new Date(A2Cribs.FilterManager.DateBegin)
				A2Cribs.FilterManager.EndDateObject.setValue(A2Cribs.FilterManager.DateEnd)
				return

			A2Cribs.FilterManager.DateEnd = A2Cribs.FilterManager.GetFormattedDate event.valueOf().date

		ajaxData += "&min_rent=" + A2Cribs.FilterManager.MinRent
		ajaxData += "&max_rent=" + A2Cribs.FilterManager.MaxRent
		ajaxData += "&start_date=" + A2Cribs.FilterManager.DateBegin
		ajaxData += "&end_date=" + A2Cribs.FilterManager.DateEnd

		$.ajax
			url: myBaseUrl + "Sublets/ApplyFilter"
			type:"GET"
			data:ajaxData
			context: this
			success: A2Cribs.FilterManager.UpdateMarkers

	@GetFormattedDate: (date) ->
		year = date.getUTCFullYear()
		month = date.getMonth() + 1
		day = date.getDate()
		return year + '-' + month + '-' + day

	@GetTodaysDate: () ->
		today = new Date()
		dd = today.getDate()
		mm = today.getMonth() + 1
		yyyy = today.getUTCFullYear()
		if dd<10
			dd='0'+dd 
		if mm<10
			mm='0'+mm
		today = mm+'-'+dd+'-'+yyyy;
		return new Date(today)

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
		address = $("#AddressSearchText").val()
		request = 
			location: A2Cribs.Map.GMap.getCenter()
			radius: 8100 # in meters (approximately 5 miles)
			types: ['street_address', 'street_number', 'postal_code', 'postal_code_prefix', 'point_of_interest', 'neighborhood', 'intersection', 'transit_station']
			keyword: address
			name: address
		A2Cribs.FilterManager.Geocoder.geocode({ 'address' : address + " " + A2Cribs.FilterManager.CurrentCity + ", " + A2Cribs.FilterManager.CurrentState}, A2Cribs.FilterManager.AddressSearchCallback)
		#@PlacesService.nearbySearch(request, @AddressSearchCallback)