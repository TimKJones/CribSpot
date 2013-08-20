class A2Cribs.Map
	###
	Add all markers in markerList to map
	###
	@InitializeMarkers:(markerList) =>
		if markerList?
			markerList = JSON.parse markerList
			for marker_object in markerList
				marker = new A2Cribs.Marker marker_object.Marker
				marker.Init()
				A2Cribs.UserCache.Set marker
				@GMarkerClusterer.addMarker marker.GMarker
		

	###
	Load all markers from Markers table
	###
	@LoadMarkers: ->
		deferred = new $.Deferred
		if A2Cribs.Map.CurentSchoolId == undefined
			deferred.resolve(null)
			return
			
		$.ajax 
			url: myBaseUrl + "Map/LoadMarkers/" + A2Cribs.Map.CurentSchoolId + "/" + 0
			type:"GET"
			context: this
			success: (response) ->
				deferred.resolve(response, this)
			error: () ->
				deferred.resolve(null)

		return deferred.promise()

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
			CONTROL_BOX_LEFT: 95

	@Init: (school_id, latitude, longitude, city, state, school_name, active_listing_type) ->
		@CurentSchoolId = school_id
		A2Cribs.FilterManager.CurrentCity = city
		A2Cribs.FilterManager.CurrentState = state
		A2Cribs.FilterManager.CurrentSchool = school_name
		@ACTIVE_LISTING_TYPE = active_listing_type
		zoom = 15
		@MapCenter = new google.maps.LatLng(latitude, longitude);

		@MapOptions =
  			zoom: zoom
  			center: A2Cribs.Map.MapCenter
  			mapTypeId: google.maps.MapTypeId.ROADMAP
  			styles: @style
  			panControl: false
  			streetViewControl: false
  			mapTypeControl: false
		A2Cribs.Map.GMap = new google.maps.Map(document.getElementById('map_canvas'), A2Cribs.Map.MapOptions)
		google.maps.event.addListener(A2Cribs.Map.GMap, 'idle', A2Cribs.Map.ShowMarkers);
		google.maps.event.addListener A2Cribs.Map.GMap, 'center_changed', () => A2Cribs.ClickBubble.Close()
		###imageStyles = [
			{
				"url": "/img/dots/group_dot.png",
			}
		]###
		imageStyles = [
			{
				height: 48
				url: '/img/dots/group_dot.png'
				width: 48
				textColor: '#ffffff'
				textSize: 13
			}
		]
		mcOptions =
			gridSize: 60
			maxZoom: 15
			styles: imageStyles
		@GMarkerClusterer = new MarkerClusterer(A2Cribs.Map.GMap, [], mcOptions)
		@GMarkerClusterer.ignoreHidden_ = true;
		A2Cribs.ClickBubble.Init @GMap
		A2Cribs.HoverBubble.Init @GMap
		
		A2Cribs.Map.InitBoundaries();
		@LoadAllMapData()
		A2Cribs.MarkerTooltip.Init()
		A2Cribs.FavoritesManager.LoadFavorites()
		A2Cribs.FilterManager.InitAddressSearch()

	@LoadBasicData: ->
		deferred = new $.Deferred
		$.ajax 
			url: myBaseUrl + "Map/GetBasicData/#{@ACTIVE_LISTING_TYPE}/#{@CurentSchoolId}"
			type: "POST"
			success: (responses) ->
				deferred.resolve(responses)
			error: () ->
				deferred.resolve(null)

		return deferred.promise()

	@LoadBasicDataCallback: (response) ->
		if response == null || response == undefined
			return
		listings = JSON.parse response
		#A2Cribs.Cache.CacheHoverData hdList
		for listing in listings
			for key,value of listing
				A2Cribs.UserCache.Set new A2Cribs[key] value

		# Set all listings to visible
		all_listings = A2Cribs.UserCache.Get "listings"
		for listing in all_listings
			listing.visible = true

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

	###
	Load markers and hover data.
	Use JQuery Deferred object to load all data asynchronously
	###
	@LoadAllMapData: () ->
		markersPromise = @LoadMarkers()
		basicData = @LoadBasicData()
		$.when(markersPromise).then(@InitializeMarkers)
		$.when(basicData).then(@LoadBasicDataCallback)

	@style = [
		{
			"featureType": "road.highway"
			"elementType": "geometry.fill"
			"stylers": [
				{ "visibility": "on" }
				{ "color": "#ffffff" }
			]
		}
		{
			"featureType": "road.arterial"
			"elementType": "geometry.fill"
			"stylers": [
				{ "color": "#ffffff" }
			]
		}
		{
			"elementType": "labels.text.fill"
			"stylers": [
				{ "color": "#3b393a" }
			]
		}
		{
			"featureType": "poi.sports_complex"
			"elementType": "geometry"
			"stylers": [
				{ "color": "#e9ddbc" }
			]
		}
		{
			"featureType": "road"
			"elementType": "labels.text.stroke"
			"stylers": [
				{ "color": "#ffffff" }
			]
		}
		{
			"featureType": "road.highway"
			"elementType": "geometry.stroke"
			"stylers": [
				{ "color": "#868080" }
				{ "lightness": 55 }
			]
		}
		{
			"featureType": "road.local"
			"elementType": "geometry.stroke"
			"stylers": [
				{ "color": "#808080" }
				{ "lightness": 53 }
			]
		}
		{
			"featureType": "poi.place_of_worship"
			"elementType": "labels"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "poi.attraction"
			"elementType": "labels"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "road.highway"
			"elementType": "labels.icon"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "road"
		}
		{
			"featureType": "transit.station.airport"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "poi.government"
			"elementType": "labels"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "poi.business"
			"elementType": "labels"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "poi.government"
			"elementType": "labels"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "poi.medical"
			"elementType": "labels"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "poi.park"
			"elementType": "labels.icon"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "poi"
			"elementType": "labels.icon"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "poi.park"
			"elementType": "labels.text.fill"
			"stylers": [
				{ "lightness": 23 }
				{ "color": "#83b243" }
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "poi.park"
			"elementType": "labels.text.stroke"
			"stylers": [
				{ "color": "#f4f6f1" }
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "poi.school"
			"elementType": "labels.text"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "water"
			"elementType": "labels"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "road.highway"
			"elementType": "labels.icon"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "poi.medical"
			"stylers": [
				{ "color": "#ce979e" }
				{ "lightness": 26 }
			]
		}
		{
			"featureType": "road.arterial"
			"elementType": "labels.icon"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "transit.station.rail"
			"elementType": "labels.icon"
			"stylers": [
				{ "lightness": 39 }
			]
		}
		{
			"featureType": "poi.park"
			"elementType": "geometry.fill"
			"stylers": [
				{ "color": "#d6e0c6" }
			]
		}
		{
			"featureType": "water"
			"stylers": [
				{ "color": "#c2d6ec" }
			]
		}
		{
			"featureType": "landscape.man_made"
			"stylers": [
				{ "color": "#efece2" }
			]
		}
		{
			"featureType": "poi.medical"
			"stylers": [
				{ "color": "#edcece" }
			]
		}
		{
			"featureType": "road.arterial"
			"elementType": "labels.icon"
			"stylers": [
				{ "visibility": "off" }
			]
		}
		{
			"featureType": "road.local"
			"elementType": "labels.text.fill"
			"stylers": [
				{ "lightness": 16 }
			]
		}
		{
			"featureType": "road.arterial"
			"stylers": [
				{ "lightness": 15 }
			]
		}
		{
			"featureType": "landscape.man_made"
			"elementType": "geometry.stroke"
			"stylers": [
				{ "visibility": "on" }
				{ "lightness": 78 }
				{ "color": "#b8b7b8" }
			]
		}
		{
			"featureType": "poi.business"
			"elementType": "geometry.fill"
			"stylers": [
				{ "visibility": "on" }
				{ "lightness": 25 }
				{ "saturation": -17 }
			]
		}
	]