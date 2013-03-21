class A2Cribs.Marker
	constructor: (@MarkerId, @Address, @Title, @UnitType, @Latitude, @Longitude) -> 
		@ListingIds = null
		@MarkerId = parseInt(@MarkerId)
		@GMarker = new google.maps.Marker
			position: new google.maps.LatLng(@Latitude, @Longitude)
			icon: "/img/dots/available_dot.png"
			id: @MarkerId
		@Clicked = false #Used to determine if data is already in the cache

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
		min_rent = 0
		max_rent = 999999
		beds = 0
		start_date = 0
		end_date = 999999

		visibleListingIds = []

		for subletId in subletIdList
			l = A2Cribs.Cache.IdToSubletMap[subletId]
			unitType = null
			bathType = null
			if l.BuildingType != undefined
				unitType = A2Cribs.Cache.BuildingIdToNameMap[l.BuildingType]

			if l.BathroomType != undefined
				bathType = A2Cribs.Cache.BathroomIdToNameMap[l.BathroomType] 

			if (((unitType == 'House' or unitType == null) and house) or ((unitType == 'Apartment' or unitType == null) and apt) or ((unitType == 'Duplex' or unitType == null) and other) or (unitType != 'House' && unitType != 'Duplex' && unitType != 'Apartment')) and
			  ((l.PricePerBedroom >= min_rent and
			  l.PricePerBedroom <= max_rent)) and
			  (l.Bedrooms >= beds)
				visibleListingIds.push subletId

		return visibleListingIds
	###
	Called after successful ajax call to retrieve all listing data for a specific marker_id.
	Updates UI with retrieved data
	###
	UpdateMarkerContent = (markerData) ->
		#decodedData = $.parseJSON markerData
		if (!@Clicked)
				A2Cribs.Cache.CacheMarkerData JSON.parse markerData
				A2Cribs.Cache.IdToMarkerMap[@MarkerId].GMarker.setIcon("/img/dots/clicked_dot.png")

		@Clicked = true
		visibleListingIds = FilterVisibleListings A2Cribs.Cache.MarkerIdToSubletIdsMap[@MarkerId]
		A2Cribs.Map.MarkerTooltip.Display visibleListingIds, @GMarker

	###
	Load all listing data for this marker
	Called when a marker is clicked
	###
	LoadMarkerData: ->
		@CorrectTooltipLocation()
		if (@Clicked)
			visibleListingIds = FilterVisibleListings A2Cribs.Cache.MarkerIdToSubletIdsMap[@MarkerId]
			A2Cribs.Map.MarkerTooltip.Display visibleListingIds, @GMarker
		else
			$.ajax 
				url: myBaseUrl + "Sublets/LoadMarkerData/" + @MarkerId
				type:"GET"
				context: this
				success: UpdateMarkerContent

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
		leftBound = ($("#favoritesBar").css('display') == 'block') * $("#favoritesBar").width()
		if leftBound == 0
			leftBound = A2Cribs.Map.Bounds.CONTROL_BOX_LEFT
		else
			leftBound += A2Cribs.MarkerTooltip.Padding

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

		# past filter box region
		if (markerLocation.x + tooltipOffset.x + A2Cribs.MarkerTooltip.Width - A2Cribs.MarkerTooltip.ArrowOffset + A2Cribs.MarkerTooltip.Padding > A2Cribs.Map.Bounds.FILTER_BOX_LEFT) &&
				 (markerLocation.y + tooltipOffset.y - A2Cribs.MarkerTooltip.Height - A2Cribs.MarkerTooltip.ArrowHeight < A2Cribs.Map.Bounds.FILTER_BOX_BOTTOM)
			oldX = tooltipOffset.x
			oldY = tooltipOffset.y
			tooltipOffset.x = markerLocation.x + A2Cribs.MarkerTooltip.Width - A2Cribs.MarkerTooltip.ArrowOffset + A2Cribs.MarkerTooltip.Padding - A2Cribs.Map.Bounds.FILTER_BOX_LEFT
			tooltipOffset.y = markerLocation.y - A2Cribs.MarkerTooltip.Height - A2Cribs.MarkerTooltip.ArrowHeight - A2Cribs.Map.Bounds.FILTER_BOX_BOTTOM
			if Math.abs(tooltipOffset.x) > Math.abs(tooltipOffset.y)
				tooltipOffset.x = oldX
			else
				tooltipOffset.y = oldY

		A2Cribs.Map.GMap.panBy(tooltipOffset.x, tooltipOffset.y)


