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
			draggable: enabled
		@Map = new google.maps.Map mapDiv, MapOptions
		@Marker = new google.maps.Marker
			draggable: enabled
			position: @center
			map: @Map
			visible: marker_visible

		#@SetEnabled enabled

		@Resize()

	CenterMap: (latitude, longitude) ->
		@center = new google.maps.LatLng latitude, longitude
		@Resize()

	Resize: () ->
		google.maps.event.trigger @Map, "resize"
		@Map.setCenter @center

	SetMarkerVisible: (value = true) ->
		if @Marker?
			@Marker.setVisible false

	SetMarkerPosition: (location) ->
		@center = location
		@Map.panTo location
		@SetZoom 18
		@Marker.setPosition(location)
		@Marker.setVisible(true)	

	SetZoom: (zoom) ->
		@Map.setZoom zoom

	GetMarkerPosition: () ->
		'latitude' : @Marker.position.lat()
		'longitude' : @Marker.position.lng()

	SetEnabled: (value = true)->
		if @Map?
			@Map.setOptions
				draggable: value
				zoomControl: value
				scrollwheel: value
				disableDoubleClickZoom: value
		if @Marker?
			@Marker.setOptions
				draggable: value

		@Enabled = value;
		