class A2Cribs.Geocoder
	@FindAddress: (street_address, city, state) ->
		deferred = new $.Deferred()
		if not @_geocoder? then @_geocoder = new google.maps.Geocoder()
		@_geocoder.geocode { address: "#{street_address} #{city}, #{state}" }, (response, status) =>
			if status is google.maps.GeocoderStatus.OK and response[0].address_components.length >= 2
				for component in response[0].address_components
					for type in component.types
						switch type
							when "street_number" then street_number = component.short_name
							when "route" then street_name = component.short_name
							when "locality" then city = component.short_name
							when "administrative_area_level_1" then state = component.short_name
							when "postal_code" then zip = component.short_name

				location = response[0].geometry.location

				if not street_number? then return deferred.reject()

				return deferred.resolve ["#{street_number} #{street_name}", city, state, zip, location]
			else
				return deferred.reject()
				
		return deferred.promise()
