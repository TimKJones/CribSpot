class A2Cribs.Rental_Marker
	constructor: (@MarkerId, @Address, @Title, @UnitType, @Latitude, @Longitude, @City, @State) -> 
		@ListingIds = null
		@MarkerId = parseInt(@MarkerId)
		@GMarker = new google.maps.Marker
			position: new google.maps.LatLng(@Latitude, @Longitude)
			icon: "/img/dots/available_dot.png"
			id: @MarkerId
		@Clicked = false #Used to determine if data is already in the cache

	###
	Called after successful ajax call to retrieve all listing data for a specific marker_id.
	Updates UI with retrieved data
	###
	UpdateMarkerContent = (markerData) ->
		console.log JSON.parse markerData
		if (!@Clicked)
			A2Cribs.Cache.CacheMarkerData JSON.parse markerData
			A2Cribs.Cache.IdToMarkerMap[@MarkerId].GMarker.setIcon("/img/dots/clicked_dot.png")
			@Clicked = true

		#visibleListingIds = FilterVisibleListings A2Cribs.Cache.MarkerIdToSubletIdsMap[@MarkerId]
		#A2Cribs.Map.ClickBubble.Open this, visibleListingIds

	###
	Load all listing data for this marker
	Called when a marker is clicked
	###
	LoadMarkerData: ->
		#@CorrectTooltipLocation()
		if (@Clicked)
			UpdateMarkerContent null
		else
			$.ajax 
				url: myBaseUrl + "Listings/LoadMarkerData/" + A2Cribs.Types.LISTING_TYPE_RENTAL + "/" + @MarkerId
				type:"GET"
				context: this
				success: UpdateMarkerContent