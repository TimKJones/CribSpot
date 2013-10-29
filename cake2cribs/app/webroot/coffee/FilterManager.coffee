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

	@UpdateListings: (visibleListingIds) ->
		visible_listings = JSON.parse visibleListingIds

		A2Cribs.HoverBubble?.Close()
		A2Cribs.ClickBubble?.Close()

		# Make all of the listings hidden
		all_listings = A2Cribs.UserCache.Get "listing"
		for listing in all_listings
			listing.visible = false

		# Make only the listings visible with id's in visibleListingIds
		visible_markers = {}
		for listing_id in visible_listings
			listing = A2Cribs.UserCache.Get "listing", listing_id
			if listing?
				listing.visible = true
				visible_markers[+listing.marker_id] = true

		# Set the markers to visible
		all_markers = A2Cribs.UserCache.Get "marker"
		for marker in all_markers
			if visible_markers[+marker.marker_id]
				marker.GMarker.setVisible true
			else
				marker.GMarker.setVisible false

		A2Cribs.Map.Repaint()

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

	@SearchForAddress: (div) ->
		if not A2Cribs.FilterManager.Geocoder? then A2Cribs.FilterManager.Geocoder = new google.maps.Geocoder()
		address = $(div).val()
		request = 
			location: A2Cribs.Map.GMap.getCenter()
			radius: 8100 # in meters (approximately 5 miles)
			types: ['street_address', 'street_number', 'postal_code', 'postal_code_prefix', 'point_of_interest', 'neighborhood', 'intersection', 'transit_station']
			keyword: address
			name: address
		A2Cribs.FilterManager.Geocoder.geocode { 'address' : address + " " + A2Cribs.FilterManager.CurrentCity + ", " + A2Cribs.FilterManager.CurrentState },
			(response, status) =>
				if status == google.maps.GeocoderStatus.OK && response[0].types[0] != "postal_code"
					$(div).effect("highlight", {color: "#5858FA"}, 2000)
					A2Cribs.Map.GMap.panTo response[0].geometry.location
					A2Cribs.Map.GMap.setZoom(18)
				else
					$(div).effect("highlight", {color: "#FF0000"}, 2000)
