class A2Cribs.Map
	@LISTING_TYPES = ['rental', 'sublet', 'parking']

	@CLUSTER_SIZE = 2


	$(document).ready =>
		if $("#map_region").length
			@Init $("#map_region").data("university-id"),
				$("#map_region").data("latitude"),
				$("#map_region").data("longitude"),
				$("#map_region").data("city"),
				$("#map_region").data("state"),
				$("#map_region").data("university-name"),
				$("#map_region").data("listing-type")


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

	@Init: (school_id, latitude, longitude, city, state, school_name, active_listing_type_id) ->
		@CurentSchoolId = school_id
		mixpanel.register
			'preferred_university': school_id
		A2Cribs.FilterManager.CurrentCity = city
		A2Cribs.FilterManager.CurrentState = state
		A2Cribs.FilterManager.CurrentSchool = school_name
		A2Cribs.FilterManager.ActiveListingType = active_listing_type_id
		@ACTIVE_LISTING_TYPE_ID = active_listing_type_id
		@ACTIVE_LISTING_TYPE = @LISTING_TYPES[active_listing_type_id]
		zoom = 14
		@MapCenter = new google.maps.LatLng(latitude, longitude);

		@MapOptions =
			zoom: zoom
			center: A2Cribs.Map.MapCenter
			mapTypeId: google.maps.MapTypeId.ROADMAP
			styles: @style
			panControl: false
			streetViewControl: false
			mapTypeControl: false
			zoomControlOptions:
				style: google.maps.ZoomControlStyle.SMALL
				position: google.maps.ControlPosition.LEFT_CENTER

		A2Cribs.Map.GMap = new google.maps.Map(document.getElementById('map_canvas'), A2Cribs.Map.MapOptions)
		google.maps.event.addListener(A2Cribs.Map.GMap, 'idle', A2Cribs.Map.ShowMarkers);

		###imageStyles = [
			{
				"url": "/img/dots/group_dot.png",
			}
		]###
		imageStyles = [
			{
				height: 39
				url: '/img/dots/dot_group.png'
				width: 39
				textColor: '#ffffff'
				textSize: 13
			}
		]
		mcOptions =
			gridSize: 60
			maxZoom: 15
			styles: imageStyles
		@GMarkerClusterer = new MarkerClusterer(A2Cribs.Map.GMap, [], mcOptions)
		@GMarkerClusterer.setIgnoreHidden true
		$("#map_region").trigger "map_initialized", [@GMap]
		
		A2Cribs.Map.InitBoundaries()
		@LoadAllMapData()
		A2Cribs.FilterManager.InitAddressSearch()

	@LoadBasicData: ->
		
		if not @BasicDataDeferred?
			@BasicDataDeferred = new $.Deferred()
		
		$.ajax 
			url: myBaseUrl + "Map/GetBasicData/#{@ACTIVE_LISTING_TYPE_ID}/#{@CurentSchoolId}"

			type: "POST"
			success: (responses) =>
				@BasicDataDeferred.resolve(responses)
			error: () =>
				@BasicDataDeferred.resolve(null)
				@BasicDataCached.resolve()

		return @BasicDataDeferred.promise()

	@LoadBasicDataCallback: (response) =>
		if response == null || response == undefined
			return
		listings = JSON.parse response
		#A2Cribs.Cache.CacheHoverData hdList
		for listing_id, listing of listings
			for key,value of listing
				A2Cribs.UserCache.Set new A2Cribs[key] value

		#everything has been cached...signal other functions waiting on this to start
		@BasicDataCached.resolve()

		# Initialize all markers and add tehm to the map
		all_markers = A2Cribs.UserCache.Get "marker"
		for marker in all_markers
			marker.Init()
			@GMarkerClusterer.addMarker marker.GMarker		

		# Set all listings to visible
		all_listings = A2Cribs.UserCache.Get "listing"
		for listing in all_listings
			listing.visible = true

		if @ACTIVE_LISTING_TYPE is 'sublet'
			@IsCluster false

		@Repaint()

	###
	Set Marker Types
	Loops through all the listings and if changes the marker
	type (icon) based on the availability of the marker
	###
	@SetMarkerTypes: ->
		all_markers = A2Cribs.UserCache.Get "marker"
		for marker in all_markers
			marker.SetType A2Cribs.Marker.TYPE.LEASED

		all_listings = A2Cribs.UserCache.Get "listing"
		for listing in all_listings
			if listing.InSidebar() or listing.IsVisible()
				marker = A2Cribs.UserCache.Get "marker", listing.marker_id
				if not listing.available? and marker.GetType() is A2Cribs.Marker.TYPE.LEASED
					marker.SetType A2Cribs.Marker.TYPE.UNKNOWN # Set to unknown
				else if listing.available? and listing.available is yes
					marker.SetType A2Cribs.Marker.TYPE.AVAILABLE # Set to true


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
		$("#loader").show()
		basicData = @LoadBasicData()
		@BasicDataCached = new $.Deferred() # resolved after basic data has been added to cache
		A2Cribs.FeaturedListings.LoadFeaturedPMListings()
		basicData
		.done(@LoadBasicDataCallback)
		.always () ->
			$("#loader").hide()
		A2Cribs.FeaturedListings.InitializeSidebar(@CurentSchoolId, @ACTIVE_LISTING_TYPE, basicData, @BasicDataCached)

	@CenterMap:(latitude, longitude)->
		if not @GMap? then return
		@GMap.setCenter new google.maps.LatLng(latitude, longitude);

	###
	Toggles visibility for the given listing_ids
	When toggled on, only these listing_ids are visible.
	When toggled off, all listings are visible 
	###
	@ToggleListingVisibility: (listing_ids, toggle_type) ->
		$(".favorite_button").removeClass "active"
		$(".featured_pm").removeClass "active"

		$(document).trigger "close_bubbles"

		all_markers = A2Cribs.UserCache.Get 'marker'
		all_listings = A2Cribs.UserCache.Get 'listing'

		is_current_toggle = @CurrentToggle is toggle_type
		if not is_current_toggle
			# make only markers that are in listing_ids visible

			# Set visibility of ALL markers to false
			for marker in all_markers
				marker.IsVisible false

			for listing in all_listings
				listing.IsVisible false

			# Set visibility of all markers with listings in listing_ids to true
			for listing_id in listing_ids
				listing = A2Cribs.UserCache.Get 'listing', listing_id
				if listing?
					marker = A2Cribs.UserCache.Get 'marker', listing.marker_id
					marker.IsVisible true
					listing.IsVisible true
			
			@CurrentToggle = toggle_type
		else
			# make all markers visible
			for marker in all_markers
				marker?.IsVisible true

			for listing in all_listings
				listing.IsVisible true

			@CurrentToggle = null

		@Repaint()
		return is_current_toggle


	###
	Checks/Sets if the map is in clusters
	Never cluster if it is sublets!
	###
	@IsCluster: (is_clustered = null) ->
		if typeof(is_clustered) is "boolean"
			if is_clustered is yes and @ACTIVE_LISTING_TYPE isnt 'sublet'
				@GMarkerClusterer.setMinimumClusterSize @CLUSTER_SIZE
			else
				@GMarkerClusterer.setMinimumClusterSize Number.MAX_VALUE
			@Repaint()
		return @GMarkerClusterer.getMinimumClusterSize() is @CLUSTER_SIZE

	###
	Repaints the map
	###
	@Repaint: ->
		@SetMarkerTypes()
		@GMarkerClusterer.repaint()


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
