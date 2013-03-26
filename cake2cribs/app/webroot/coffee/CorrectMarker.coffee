class A2Cribs.CorrectMarker
	@Map = null;
	@Marker = null
	@Geocoder = null

	@Init: ()->
		AnnArborCenter = new google.maps.LatLng(42.2808256, -83.7430378)
		MapOptions =
  			zoom: 15
  			center: AnnArborCenter
  			mapTypeId: google.maps.MapTypeId.ROADMAP
		@Map = new google.maps.Map(document.getElementById('correctLocationMap'), MapOptions)
		@Marker = new google.maps.Marker
			draggable: true
			position: A2Cribs.Map.AnnArborCenter
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
			A2Cribs.CorrectMarker.Map.panTo response[0].geometry.location
			A2Cribs.CorrectMarker.Map.setZoom(18)
			if (response[0].address_components.length >= 2)
				formattedAddress = response[0].address_components[0].short_name + " " + response[0].address_components[2].short_name
				city = response[0].address_components[4].short_name
				state = response[0].address_components[7].short_name
				postal = response[0].address_components[9].short_name
				$("#formattedAddress").html(formattedAddress)
				$("#city").html(city);
				$("#state").html(state);
				$("#postal").html(postal);
				A2Cribs.CorrectMarker.Marker.setPosition(response[0].geometry.location)
				A2Cribs.CorrectMarker.Marker.setVisible(true)
				google.maps.event.addListener(A2Cribs.CorrectMarker.Marker, 'dragend', A2Cribs.CorrectMarker.UpdateLatLong)	
				$("#updatedLat").html(response[0].geometry.location.lat())
				$("#updatedLong").html(response[0].geometry.location.lng())		

	@FindAddress: () ->
		address = $("#addressToMark").val()
		request = 
			location: A2Cribs.CorrectMarker.Map.getCenter()
			radius: 8100 # in meters (approximately 5 miles)
			types: ['street_address']
			keyword: address
			name: address
		A2Cribs.CorrectMarker.Geocoder.geocode({ 'address' : address + " Ann Arbor, MI 48104"}, A2Cribs.CorrectMarker.AddressSearchCallback)

