class A2Cribs.CorrectMarker
	@Map = null;
	@Marker = null
	@Geocoder = null
	@Enabled = true;

	@Init: ()->
		@LoadUniversities()
		div = $('#post-sublet-modal').find('#correctLocationMap')[0]
		@CreateMap div, 42.2808256, -83.7430378
		@Geocoder = new google.maps.Geocoder()

	@CreateMap: (div, latitude, longitude, marker_visible = false, enabled = true) ->
		center = new google.maps.LatLng latitude, longitude
		MapOptions =
			zoom: 15
			center: center
			mapTypeId: google.maps.MapTypeId.ROADMAP
			mapTypeControl: false
			panControl: false
			zoomControl: false
			streetViewControl: false
		@Map = new google.maps.Map div, MapOptions
		@Marker = new google.maps.Marker
			draggable: enabled
			position: center
			map: A2Cribs.CorrectMarker.Map
			visible: marker_visible

		if not enabled
			@Disable()

		google.maps.event.trigger @Map, "resize"


	# Makes the map display only, useful for cases of displaying a property location without 
	# Giving the user the option to move the map or change the lat lon, used in sublet editting
	@Disable: ()->
		if(@Map?)
			@Map.setOptions({draggable: false, zoomControl: false, scrollwheel: false, disableDoubleClickZoom: true});
		else
			@Enabled = false;

	# Reverses what was done in disable
	@Enable: ()->
		if(@Map?)
			@Map.setOptions({draggable: true, zoomControl: true, scrollwheel: true, disableDoubleClickZoom: false});		
		else
			@Enabled = true;
		


	@UpdateLatLong: (e) ->
		$("#updatedLat").html(e.latLng.lat())
		$("#updatedLong").html(e.latLng.lng())	

	@AddressSearchCallback: (response, status) ->
		# Need to detect invalid addresses
		console.log response
		if status == google.maps.GeocoderStatus.OK
			if (response[0].address_components.length >= 2)
				#formattedAddress = response[0].address_components[0].short_name + " " + response[0].address_components[2].short_name
				street_number = null
				street_name = null
				city = null
				state = null
				zip = "00000"
				for component in response[0].address_components
					for type in component.types
						if type == "street_number"
							street_number = component.short_name
						else if type == "route"
							street_name = component.short_name
						else if type == "locality"
							city = component.short_name
						else if type == "administrative_area_level_1"
							state = component.short_name
						else if type == "postal_code"
							zip = component.short_name

				if street_number == null
					A2Cribs.UIManager.Alert "Entered street address is not valid."
					$("#formattedAddress").text("")
					return
				else
					A2Cribs.CorrectMarker.Map.panTo response[0].geometry.location
					A2Cribs.CorrectMarker.Map.setZoom(18)

				street_address = street_number + " " + street_name

				$("#formattedAddress").val(street_address)
				$("#city").val(city);
				$("#state").val(state);
				$("#postal").val(zip);
				A2Cribs.CorrectMarker.Marker.setPosition(response[0].geometry.location)
				A2Cribs.CorrectMarker.Marker.setVisible(true)
				google.maps.event.addListener(A2Cribs.CorrectMarker.Marker, 'dragend', A2Cribs.CorrectMarker.UpdateLatLong)	
				$("#updatedLat").val(response[0].geometry.location.lat())
				$("#updatedLong").val(response[0].geometry.location.lng())		

	@CenterMap: (lat, lng) ->
		@Map.setCenter(new google.maps.LatLng(lat, lng))

	@SetMarkerAtPosition: (latLng) ->
		A2Cribs.CorrectMarker.Marker.setPosition(latLng)
		#A2Cribs.CorrectMarker.Marker.setVisible(true)

	@FindAddress: () ->
		address = $("#formattedAddress").val()
		request = 
			location: A2Cribs.CorrectMarker.Map.getCenter()
			radius: 8100 # in meters (approximately 5 miles)
			types: ['street_address']
			keyword: address
			name: address
		if A2Cribs.CorrectMarker.SelectedUniversity != undefined
			u = A2Cribs.CorrectMarker.SelectedUniversity
			A2Cribs.CorrectMarker.Geocoder.geocode({ 'address' : address + " " + u.city + ", " + u.state}, A2Cribs.CorrectMarker.AddressSearchCallback)
		else
			A2Cribs.UIManager.Alert "Please select a university."

	@FindSelectedUniversity: () ->
		selected = $("#universityName").val()
		index = @SchoolList.indexOf selected
		if index >= 0
			A2Cribs.CorrectMarker.SelectedUniversity = @universitiesMap[index].University;
			A2Cribs.Cache.SelectedUniversity = @universitiesMap[index].University;
			u = A2Cribs.CorrectMarker.SelectedUniversity;
			A2Cribs.CorrectMarker.CenterMap(u.latitude, u.longitude);
			$("#universityId").val(A2Cribs.CorrectMarker.SchoolIDList[index])


	@LoadUniversities: () ->
		$.ajax
			url: "/University/getAll"
			success :(response) ->
				A2Cribs.CorrectMarker.universitiesMap = JSON.parse response
				A2Cribs.CorrectMarker.SchoolList = []
				A2Cribs.CorrectMarker.SchoolIDList = []
				for university in A2Cribs.CorrectMarker.universitiesMap
					A2Cribs.CorrectMarker.SchoolList.push university.University.name
					A2Cribs.CorrectMarker.SchoolIDList.push university.University.id
				$("#universityName").typeahead
					source: A2Cribs.CorrectMarker.SchoolList

	@ClearMarker: () ->
		if A2Cribs.CorrectMarker.Marker?
			A2Cribs.CorrectMarker.Marker.setVisible false
				
