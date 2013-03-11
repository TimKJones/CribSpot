class A2Cribs.Map

	###
	Called when a marker is clicked
	###
	@MarkerClicked:(event) ->
		A2Cribs.Map.IdToMarkerMap[this.id].LoadMarkerData()

	###
	Add list of listings to cache
	###
	@CacheListings: (listings) ->
		A2Cribs.Map.MarkerIdToListingIdsMap[listings[0].Listing.marker_id] = []
		for listing in listings
			if (listing == undefined)
				continue
			l = listing.Listing
			l.listing_id = parseInt(l.listing_id)
			A2Cribs.Map.IdToListingMap[l.listing_id] = new A2Cribs.Listing(l.listing_id, l.marker_id, l.available, l.lease_range, l.unit_type, l.unit_description, l.beds, l.baths, l.rent, l.electric, l.water, l.heat, l.air, l.parking, l.furnished, l.url, l.realtor_id)

	###
	Add a realtor to the cache
	###
	@CacheRealtor: (realtor) ->
		realtor.realtor_id = parseInt(realtor.realtor_id)
		A2Cribs.Map.IdToRealtorMap[parseInt(realtor.realtor_id)] = new A2Cribs.Realtor(realtor.realtor_id, realtor.company, realtor.email)

	###
	Add a list of listingIds to the MarkerIdToListingIds map
	###
	@CacheMarkerIdToListingsList: (listings) ->
		A2Cribs.Map.MarkerIdToListingIdsMap[listings[0].Listing.marker_id] = []
		for listing in listings
			if (listing == undefined)
				continue
			A2Cribs.Map.MarkerIdToListingIdsMap[listing.Listing.marker_id].push parseInt(listing.Listing.listing_id)

	###
	Add a marker to the map
	###
	@AddMarker:(m) ->
		id = parseInt(m["marker_id"], 10)
		@IdToMarkerMap[id] =  new A2Cribs.Marker(id, m.address, m.alternate_name, m.unit_type, m.latitude, m.longitude)
		#@VisibleMarkers.push(@IdToMarkerMap[id].GMarker)
		@GMarkerClusterer.addMarker(@IdToMarkerMap[id].GMarker)
		google.maps.event.addListener(@IdToMarkerMap[id].GMarker, 'click', @MarkerClicked)
		A2Cribs.Map.AddressToMarkerIdMap[m['address']] = m['marker_id']
		

	###
	Add all markers in markerList to map
	###
	@InitializeMarkers:(markerList) ->
		decodedMarkerList = JSON.parse markerList
		for marker in decodedMarkerList
			@AddMarker marker["Marker"]
			#handle onClick

	###
	Load all markers from Markers table
	###
	@LoadMarkers: ->
		#TODO: Add Loading GIF Here
		$.ajax 
			url: myBaseUrl + "Map/LoadMarkers"
			type:"GET"
			context: this
			success: @InitializeMarkers	


		###defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(42.23472,-83.846283), new google.maps.LatLng(42.33322,-83.627243))
		input = $("#addressSearchBar")[0]
		options = 
			bounds: defaultBounds
		@AutoComplete = new google.maps.places.Autocomplete(input, options)
		@AutoComplete.setBounds(defaultBounds)###

	###
	Used to only show markers that are within a certain bounds based on the user's current viewport.
	https://developers.google.com/maps/articles/toomanymarkers#viewportmarkermanagement
	###
	@ShowMarkers : ->
		bounds = A2Cribs.Map.GMap.getBounds()
		
	@InitBoundaries: ->
		@Bounds = 
			LEFT: 0
			RIGHT: window.innerWidth
			BOTTOM: window.innerHeight
			TOP: 0
			FILTER_BOX_LEFT: A2Cribs.UtilityFunctions.getPosition($("#filterBoxBackground")[0]).x
			FILTER_BOX_BOTTOM: A2Cribs.UtilityFunctions.getPosition($("#filterBoxBackground")[0]).y + $("#filterBoxBackground").height()
			CONTROL_BOX_LEFT: 95

	@Init: ->
		@IdToListingMap = []	#stores all listings after being loaded.
		@IdToRealtorMap  = []  #stores all realtor data after being loaded.
		@MarkerIdToListingIdsMap = [] #maps marker ids to list of listing_ids
								#TODO: Set a maximum size for each cache.

		@IdToMarkerMap = []		#Map of MarkerIds to Marker objects
		@AddressToMarkerIdMap = [] #Used to determine if searched address is property in database

		@AnnArborCenter = new google.maps.LatLng(42.2808256, -83.7430378);
		@MapOptions =
  			zoom: 15
  			center: A2Cribs.Map.AnnArborCenter
  			mapTypeId: google.maps.MapTypeId.ROADMAP
		A2Cribs.Map.GMap = new google.maps.Map(document.getElementById('map_canvas'), A2Cribs.Map.MapOptions)
		google.maps.event.addListener(A2Cribs.Map.GMap, 'idle', A2Cribs.Map.ShowMarkers);
		mcOptions =
			gridSize: 60
			maxZoom: 15
		@GMarkerClusterer = new MarkerClusterer(A2Cribs.Map.GMap, [], mcOptions)
		@GMarkerClusterer.ignoreHidden_ = true;
		@LoadMarkers()
		@MarkerTooltip = new A2Cribs.MarkerTooltip @GMap
		A2Cribs.FilterManager.InitAddressSearch()
		A2Cribs.Map.InitBoundaries();
		A2Cribs.MarkerTooltip.Init()


	@UpdateMarkersCache: ->
		$.ajax
			url: myBaseUrl + "Markers/UpdateCache"