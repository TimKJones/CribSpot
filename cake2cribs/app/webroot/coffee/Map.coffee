class A2Cribs.Map
	###
	Called when a marker is clicked
	###
	@MarkerClicked:(event) ->
		A2Cribs.Cache.IdToMarkerMap[this.id].LoadMarkerData()

	@MarkerMouseIn: (event) ->
		A2Cribs.Map.HoverBubble.Open A2Cribs.Cache.IdToMarkerMap[this.id]

	@MarkerMouseOut: (event) ->
		A2Cribs.Map.HoverBubble.Close()

	###
	Add a marker to the map
	###
	@AddMarker:(m) ->
		id = parseInt(m.marker_id, 10)
		A2Cribs.Cache.CacheMarker id, m
		#@VisibleMarkers.push(@IdToMarkerMap[id].GMarker)
		@GMarkerClusterer.addMarker(A2Cribs.Cache.IdToMarkerMap[id].GMarker)
		google.maps.event.addListener(A2Cribs.Cache.IdToMarkerMap[id].GMarker, 'click', @MarkerClicked)
		google.maps.event.addListener(A2Cribs.Cache.IdToMarkerMap[id].GMarker, 'mouseover', @MarkerMouseIn)
		google.maps.event.addListener(A2Cribs.Cache.IdToMarkerMap[id].GMarker, 'mouseout', @MarkerMouseOut)
		A2Cribs.Cache.AddressToMarkerIdMap[m.address] = parseInt m.marker_id

	###
	Add all markers in markerList to map
	###
	@InitializeMarkers:(markerList) ->
		decodedMarkerList = JSON.parse markerList
		for marker in decodedMarkerList
			@AddMarker marker.Marker
			#handle onClick

		A2Cribs.Map.LoadHoverData()

	###
	Load all markers from Markers table
	###
	@LoadMarkers: ->
		#TODO: Add Loading GIF Here
		$.ajax 
			url: myBaseUrl + "Map/LoadMarkers/" + A2Cribs.Map.CurentSchoolId
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

	@Init: (school_id, latitude, longitude) ->
		@CurentSchoolId = school_id
		@MapCenter = new google.maps.LatLng(latitude, longitude);
		style = [
			{
				"featureType": "landscape",
				"stylers": [
					{ "hue": "#005eff" }
				]
			},{
				"featureType": "road",
				"stylers": [
					{ "hue": "#00ff19" }
				]
			},{
				"featureType": "water",
				"stylers": [
					{ "saturation": 99 }
				]
			},{
				"featureType": "poi",
				"stylers": [
					{ "hue": "#0044ff" },
					{ "lightness": 32 }
				]
			}
		]
		@MapOptions =
  			zoom: 15
  			center: A2Cribs.Map.MapCenter
  			mapTypeId: google.maps.MapTypeId.ROADMAP
  			styles: style
  			panControl: false
  			streetViewControl: false
  			mapTypeControl: false
		A2Cribs.Map.GMap = new google.maps.Map(document.getElementById('map_canvas'), A2Cribs.Map.MapOptions)
		google.maps.event.addListener(A2Cribs.Map.GMap, 'idle', A2Cribs.Map.ShowMarkers);
		mcOptions =
			gridSize: 60
			maxZoom: 15
		@GMarkerClusterer = new MarkerClusterer(A2Cribs.Map.GMap, [], mcOptions)
		@GMarkerClusterer.ignoreHidden_ = true;
		@LoadTypeTables()
		@ClickBubble = new A2Cribs.ClickBubble @GMap
		@HoverBubble = new A2Cribs.HoverBubble @GMap
		@ListingPopup = new A2Cribs.ListingPopup()
		A2Cribs.FilterManager.InitAddressSearch()
		A2Cribs.Map.InitBoundaries();
		A2Cribs.MarkerTooltip.Init()

	@LoadTypeTables: ->
		$.ajax
			url: myBaseUrl + "Map/LoadTypeTables"
			type: "POST"
			success: @LoadTypeTablesCallback

	@LoadHoverData: ->
		$.ajax 
			url: myBaseUrl + "Map/LoadHoverData"
			type: "POST"
			success: @LoadHoverDataCallback

	@LoadHoverDataCallback: (response) ->
		hdList = JSON.parse response
		A2Cribs.Cache.CacheHoverData hdList

	@LoadTypeTablesCallback: (types) ->
		types = JSON.parse types
		buildings = types[0]
		bathrooms = types[1]
		genders = types[2]
		student_types = types[3]
		for type in buildings
			A2Cribs.Cache.BuildingIdToNameMap[parseInt type.BuildingType.id] = type.BuildingType.name

		for type in bathrooms
			A2Cribs.Cache.BathroomIdToNameMap[parseInt type.BathroomType.id] = type.BathroomType.name

		for type in genders
			A2Cribs.Cache.GenderIdToNameMap[parseInt type.GenderType.id] = type.GenderType.name

		for type in student_types
			A2Cribs.Cache.StudentTypeIdToNameMap[parseInt type.StudentType.id] = type.StudentType.name

		A2Cribs.Map.LoadMarkers()
	###
	EVAN:
		marker_id is the id of the marker to open
		sublet_data is an object containing all the data needed to populate a tooltip
	###
	@OpenMarker: (marker_id, sublet_data) ->
		if (marker_id == -1)
			#Invalid URL was given
			alert "This listing either has been removed or is invalid."
			return
		if (marker_id == -2)
			#no sublet_id was given in url - don't do anything
			return
		alert marker_id