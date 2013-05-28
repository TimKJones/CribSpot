class A2Cribs.MiniMap
	constructor: (div, latitude = 39.8282, longitude = -98.5795, marker_visible = false, enabled = true) ->
		mapDiv = div.find('#correctLocationMap')[0]
		@center = new google.maps.LatLng latitude, longitude
		MapOptions =
			zoom: 2
			center: @center
			mapTypeId: google.maps.MapTypeId.ROADMAP
			mapTypeControl: false
			panControl: false
			zoomControl: false
			streetViewControl: false
		@Map = new google.maps.Map mapDiv, MapOptions
		@Marker = new google.maps.Marker
			draggable: enabled
			position: @center
			map: @Map
			visible: marker_visible

		if not enabled
			@Disable()

		@Resize()

	CenterMap: (latitude, longitude) ->
		@center = new google.maps.LatLng latitude, longitude
		@Resize()

	Resize: () ->
		google.maps.event.trigger @Map, "resize"
		@Map.setCenter @center

	SetMarkerPosition: (location) ->
		@Map.panTo location
		@SetZoom 18
		@Marker.setPosition(location)
		@Marker.setVisible(true)	

	SetZoom: (zoom) ->
		@Map.setZoom zoom

	GetMarkerPosition: () ->
		'latitude' : @Marker.position.lat()
		'longitude' : @Marker.position.lng()
		