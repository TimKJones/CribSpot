class A2Cribs.Marker extends  A2Cribs.Object
	@BuildingType = ['House','Apartment','Duplex','Condo','Townhouse','Co-Op','Dorm','Greek','Other']
	@TYPE =
		UNKNOWN: 0
		LEASED: 1
		AVAILABLE: 2
		SCHEDULE: 3

	constructor: (marker) ->
		super "marker", marker

	GetName: ->
		if @alternate_name? and @alternate_name.length then @alternate_name else @street_address

	GetBuildingType: ->
		if isNaN(parseInt(@building_type_id, 10))
			return @building_type_id
		return A2Cribs.Marker.BuildingType[@building_type_id]

	GetType: ->
		return @_type

	SetType: (@_type) ->
		switch @_type
			when A2Cribs.Marker.TYPE.UNKNOWN then marker_dot = "unknown"
			when A2Cribs.Marker.TYPE.LEASED then marker_dot = "leased"
			when A2Cribs.Marker.TYPE.AVAILABLE then marker_dot = "available"
			when A2Cribs.Marker.TYPE.SCHEDULE then marker_dot = "schedule"
		@GMarker?.setIcon "/img/dots/dot_#{marker_dot}.png"

	IsVisible: (visible = null) ->
		if typeof(visible) is "boolean"
			@GMarker?.setVisible visible
		if not @GMarker?
			return no

		return @GMarker?.getVisible()

	Init: ->
		@GMarker = new google.maps.Marker
			position: new google.maps.LatLng(@latitude, @longitude)
			icon: "/img/dots/dot_leased.png"
			id: @GetId()

		google.maps.event.addListener @GMarker, 'click', @MarkerClicked

	GetObject: ->
		return_val = super()
		return_val.GMarker = null
		return return_val

	MarkerClicked: (event) =>
		$("#map_region").trigger "marker_clicked", [this]
		$(document).trigger "track_event", ["Marker", "Marker Clicked", "", @GetId()]


	###
	Filters the listing_ids at the current marker according to the user's current filter settings.
	Returns list of listing_ids that should be visible in marker tooltip.
	###
	FilterVisibleListings = (subletIdList) ->
		if subletIdList == undefined
			return null
		house = $("#houseCheck").is(':checked')
		apt = $("#aptCheck").is(':checked')
		other = $("#otherCheck").is(':checked')
		male = $("#maleCheck").is(':checked')
		female = $("#femaleCheck").is(':checked')
		students_only = $("#studentsOnlyCheck").is(':checked')
		grad = $("#gradCheck").is(':checked')
		undergrad = $("#undergradCheck").is(':checked')
		ac = $("#acCheck").is(':checked')
		parking = $("#parkingCheck").is(':checked')
		utilities = $("#utilitiesCheck").is(':checked')
		no_security_deposit = $("#noSecurityDepositCheck").is(':checked')
		min_rent = A2Cribs.FilterManager.MinRent
		max_rent = A2Cribs.FilterManager.MaxRent
		beds = $("#bedsSelect").val()
		if beds == "2+"
			beds = "2"
		beds = parseInt beds
		start_date = new Date(A2Cribs.FilterManager.DateBegin)
		end_date = new Date(A2Cribs.FilterManager.DateEnd)
		bathroom = $("#bathSelect").val()

		visibleListingIds = []

		for subletId in subletIdList
			l = A2Cribs.Cache.IdToSubletMap[subletId]
			unitType = l.BuildingType
			bathType = l.BathroomType
			sublet_start_date = new Date(l.StartDate)
			sublet_end_date = new Date(l.EndDate)

			#Housemates
			housemate_id = A2Cribs.Cache.SubletIdToHousemateIdsMap[subletId]
			housemate = A2Cribs.Cache.IdToHousematesMap[housemate_id]
			has_males = true
			has_females = true
			has_grads = true
			has_undergrads = true
			has_students_only = false
			if housemate != undefined and housemate != null
				has_males = housemate.Gender == "Male" or housemate.Gender == "Mix" or housemate.Gender == undefined or housemate.Gender == null
				has_females = housemate.Gender == "Female" or housemate.Gender == "Mix" or housemate.Gender == undefined or housemate.Gender == null
				has_grads = housemate.GradType == "Graduate" or housemate.GradType == "Mix" or housemate.GradType == undefined or housemate.GradType == null
				has_undergrads = housemate.GradType == "Undergraduate" or housemate.GradType == "Mix" or housemate.GradType == undefined or housemate.GradType == null
				has_students_only = housemate.Enrolled == true or housemate.Enrolled == undefined or housemate.Enrolled == null

			#Extra Filters

			bathrooms_match = (l.BathroomType == bathroom) or (bathroom != "Private" and bathroom != "Shared")
			#ac_match = !ac or (ac and l.air)
			#parking_match = !parking or (parking and l.parking)
			utilities_included_match = !utilities or (utilities and l.UtilityCost == 0)
			no_security_deposit_match = !no_security_deposit or (no_security_deposit and l.DepositAmount == 0)

			if (((unitType == 'House' or unitType == null) and house) or ((unitType == 'Apartment' or unitType == null) and apt) or ((unitType == 'Duplex' or unitType == null) and other) or (unitType != 'House' && unitType != 'Duplex' && unitType != 'Apartment')) and
			  ((l.PricePerBedroom >= min_rent and
			  l.PricePerBedroom <= max_rent)) and
			  (l.Bedrooms >= beds) and
			  ((start_date >= sublet_start_date) or !A2Cribs.Marker.IsValidDate(start_date)) and ((sublet_end_date >= end_date) or !A2Cribs.Marker.IsValidDate(end_date)) and
			  ((female and has_females) or (male and has_males)) and 
			  ((undergrad and has_undergrads) or (grad and has_grads)) and 
			  (!students_only or (students_only and has_students_only)) and 
			  bathrooms_match and
			  utilities_included_match and 
			  no_security_deposit_match
				visibleListingIds.push subletId

		return visibleListingIds
	###
	Called after successful ajax call to retrieve all listing data for a specific marker_id.
	Updates UI with retrieved data
	###
	UpdateMarkerContent = (markerData) ->
		#decodedData = $.parseJSON markerData
		if (!@Clicked)
			for listing in markerData
				A2Cribs.UserCache.Set A2Cribs.Listing JSON.parse markerData 
				
			clickedMarker = A2Cribs.UserCache.Get "marker", @MarkerId
			clickedMarker.GMarker.setIcon("/img/dots/clicked_dot.png")

		@Clicked = true
		@FilterVisibleListingsAndOpenPopup()

	###
	Load all listing data for this marker
	Called when a marker is clicked
	###
	LoadMarkerData: ->
		@CorrectTooltipLocation()
		if (@Clicked)
			@FilterVisibleListingsAndOpenPopup()
		else
			$.ajax 
				url: myBaseUrl + "Listings/LoadMarkerData/" + @MarkerId
				type:"GET"
				context: this
				success: UpdateMarkerContent

	FilterVisibleListingsAndOpenPopup: () ->
		visibleListingIds = FilterVisibleListings A2Cribs.Cache.MarkerIdToSubletIdsMap[@MarkerId]
		A2Cribs.Map.ClickBubble.Open this, visibleListingIds

	@GetMarkerPixelCoordinates:(latlng) ->
		map = A2Cribs.Map.GMap
		scale = Math.pow(2, map.getZoom())
		nw = new google.maps.LatLng(map.getBounds().getNorthEast().lat(), map.getBounds().getSouthWest().lng())
		worldCoordinateNW = map.getProjection().fromLatLngToPoint(nw)
		worldCoordinate = map.getProjection().fromLatLngToPoint(latlng);
		markerLocation = new google.maps.Point(
			Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale),
			Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale)
		)
		return markerLocation

	###
	Correct the tooltip location to fit it on the screen.
	###
	CorrectTooltipLocation: ->
		#Off left edge
		leftBound = A2Cribs.Map.Bounds.CONTROL_BOX_LEFT

		markerLocation = A2Cribs.Marker.GetMarkerPixelCoordinates(@GMarker.position)
		tooltipOffset = 
			x: 0
			y: 0

		# past right edge of screen	
		if markerLocation.x + A2Cribs.MarkerTooltip.Width - A2Cribs.MarkerTooltip.ArrowOffset +  A2Cribs.MarkerTooltip.Padding > A2Cribs.Map.Bounds.RIGHT
			tooltipOffset.x = markerLocation.x + A2Cribs.MarkerTooltip.Width - A2Cribs.MarkerTooltip.ArrowOffset +  A2Cribs.MarkerTooltip.Padding - A2Cribs.Map.Bounds.RIGHT
		
		# past left edge of screen	
		if markerLocation.x - A2Cribs.MarkerTooltip.ArrowOffset - A2Cribs.MarkerTooltip.Padding < leftBound
			tooltipOffset.x = markerLocation.x - A2Cribs.MarkerTooltip.ArrowOffset - A2Cribs.MarkerTooltip.Padding - leftBound	
		
		# past top edge of screen	
		if markerLocation.y - A2Cribs.MarkerTooltip.Height -  A2Cribs.MarkerTooltip.ArrowHeight < 0
			tooltipOffset.y = markerLocation.y - A2Cribs.MarkerTooltip.Height -  A2Cribs.MarkerTooltip.ArrowHeight
		
		# past bottom edge of screen
		if markerLocation.y > A2Cribs.Map.Bounds.BOTTOM - A2Cribs.MarkerTooltip.Padding	
			tooltipOffset.y = markerLocation.y - A2Cribs.Map.Bounds.BOTTOM + A2Cribs.MarkerTooltip.Padding

		A2Cribs.Map.GMap.panBy(tooltipOffset.x, tooltipOffset.y)

	@IsValidDate: (date) ->
		return date.toString() != "Invalid Date"
