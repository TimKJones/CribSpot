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
	Add realtor and listing data to cache
	###
	@CacheMarkerData = (markerData) ->
		realtor = markerData[1][0].Realtor
		listings = markerData[0]
		A2Cribs.Map.CacheListings listings
		A2Cribs.Map.CacheRealtor  realtor
		A2Cribs.Map.CacheMarkerIdToListingsList listings
		@Clicked = true

	###
	Filters the listing_ids at the current marker according to the user's current filter settings.
	Returns list of listing_ids that should be visible in marker tooltip.
	###
	FilterVisibleListings = (listingIdList) ->
		fall    = $("#fallCheck").is(':checked')	
		spring  = $("#springCheck").is(':checked')	
		other   = $("#otherCheck").is(':checked')
		house   = $("#houseCheck").is(':checked')
		apt     = $("#aptCheck").is(':checked')	
		duplex  = $("#duplexCheck").is(':checked') 
		visibleListingIds = []

		for listingId in listingIdList
			l = A2Cribs.Map.IdToListingMap[listingId]
			l.Rent = parseInt(l.Rent)
			l.Beds = parseInt(l.Beds)
			if (((l.LeaseRange == 'fall' or l.leaseRange == null) and fall) or ((l.LeaseRange == 'spring' or l.leaseRange == null) and spring) or ((l.LeaseRange == 'other' or l.leaseRange == null) and other)) and
			  ( ((l.UnitType == 'house' or l.UnitType == null) and house) or ((l.UnitType == 'apt' or l.UnitType == null) and apt) or ((l.UnitType == 'duplex' or l.UnitType == null) and duplex)) and
			  ((l.Rent >= A2Cribs.FilterManager.MinRent and
			  l.Rent <= A2Cribs.FilterManager.MaxRent) or (l.Rent == -1)) and
			  ((l.Beds >= A2Cribs.FilterManager.MinBeds and
			  l.Beds <= A2Cribs.FilterManager.MaxBeds) or (l.Rent == -1))
				visibleListingIds.push listingId

		return visibleListingIds

	###
	Called after successful ajax call to retrieve all listing data for a specific marker_id.
	Updates UI with retrieved data
	###
	UpdateMarkerContent = (markerData) ->
		#decodedData = $.parseJSON markerData
		if (!@Clicked)
			A2Cribs.Marker.CacheMarkerData JSON.parse markerData

		visibleListingIds = FilterVisibleListings(A2Cribs.Map.MarkerIdToListingIdsMap[@MarkerId])
		A2Cribs.Map.MarkerTooltip.Display visibleListingIds, @GMarker
		#Set data in tooltip element using marker data in map.IdToListingMap[], map.IdToRealtorMap[realtor_id]

	###
	Load all listing data for this marker
	###
	LoadMarkerData: ->
		includeRealtor = true #TODO check cache for this
		$.ajax 
			url: myBaseUrl + "Listings/LoadMarkerData/" + @MarkerId + "/" + includeRealtor
			type:"GET"
			context: this
			success: UpdateMarkerContent






