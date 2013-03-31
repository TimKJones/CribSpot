class A2Cribs.CorrectMarker
	@Map = null;
	@Marker = null
	@Geocoder = null

	@Init: ()->
		@AnnArborCenter = new google.maps.LatLng(42.2808256, -83.7430378)
		MapOptions =
  			zoom: 15
  			center: @AnnArborCenter
  			mapTypeId: google.maps.MapTypeId.ROADMAP
		@Map = new google.maps.Map(document.getElementById('correctLocationMap'), MapOptions)
		google.maps.event.trigger(@Map, "resize");
		@Marker = new google.maps.Marker
			draggable: true
			position: @AnnArborCenter
			map: A2Cribs.CorrectMarker.Map
			visible: false
		#A2Cribs.CorrectMarker.Marker.setMap(A2Cribs.CorrectMarker.Map)
		@Geocoder = new google.maps.Geocoder()


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

				###formatted_address = response[0].formatted_address
				first_comma = formatted_address.indexOf(',')
				street = formatted_address.substring(0, first_comma)
				street_number = street.substring(0, street.indexOf(' '))
				if isNaN(parseInt(street_number))
					A2Cribs.UIManager.Alert "Entered street address is not valid."
					$("#formattedAddress").text("")
					return
				else
					A2Cribs.CorrectMarker.Map.panTo response[0].geometry.location
					A2Cribs.CorrectMarker.Map.setZoom(18)
				second_comma = formatted_address.indexOf(',', first_comma + 1)
				city = formatted_address.substring(first_comma + 2, second_comma)
				remaining = formatted_address.substring(second_comma + 2)
				state = remaining.substring(0, remaining.indexOf(" "))
				remaining = remaining.substring(remaining.indexOf(" ") + 1)
				postal = remaining.substring(0,remaining.indexOf(","))###
				#city = response[0].address_components[4].short_name
				#state = response[0].address_components[7].short_name

				$("#formattedAddress").val(street_address)
				$("#city").val(city);
				$("#state").val(state);
				$("#postal").val(zip);
				A2Cribs.CorrectMarker.Marker.setPosition(response[0].geometry.location)
				A2Cribs.CorrectMarker.Marker.setVisible(true)
				google.maps.event.addListener(A2Cribs.CorrectMarker.Marker, 'dragend', A2Cribs.CorrectMarker.UpdateLatLong)	
				$("#updatedLat").val(response[0].geometry.location.lat())
				$("#updatedLong").val(response[0].geometry.location.lng())		

	@CenterMap: (lat, long) ->
		@Map.setCenter(new google.maps.LatLng(lat, long))

	@SetMarkerAtPosition: (latLng) ->
		A2Cribs.CorrectMarker.Marker.setPosition(latLng)
		#A2Cribs.CorrectMarker.Marker.setVisible(true)

	@FindAddress: () ->
		address = $("#addressToMark").val()
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
		for university in A2Cribs.CorrectMarker.universitiesMap
			if university.University.name == selected
				A2Cribs.CorrectMarker.SelectedUniversity = university.University;
				A2Cribs.Cache.SelectedUniversity = university.University;
		if (A2Cribs.CorrectMarker.SelectedUniversity != undefined)
			u = A2Cribs.CorrectMarker.SelectedUniversity;
			A2Cribs.CorrectMarker.CenterMap(u.latitude, u.longitude);

	