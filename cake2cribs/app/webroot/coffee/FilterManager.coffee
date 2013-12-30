class A2Cribs.FilterManager

	@UpdateListings: (visibleListingIds) ->
		visible_listings = JSON.parse visibleListingIds
		sidebar_visible_listings = []

		$("#map_region").trigger 'close_bubbles'

		# Make all of the listings hidden
		all_listings = A2Cribs.UserCache.Get "listing"
		for listing in all_listings
			listing.visible = false

		# Make only the listings visible with id's in visibleListingIds
		visible_markers = {}
		for listing_id in visible_listings
			listing = A2Cribs.UserCache.Get "listing", listing_id
			if listing?
				listing.visible = true
				sidebar_visible_listings.push(listing.listing_id)
				visible_markers[+listing.marker_id] = true

		# Set the markers to visible
		all_markers = A2Cribs.UserCache.Get "marker"
		for marker in all_markers
			if visible_markers[+marker.marker_id]
				marker.GMarker.setVisible true
			else
				marker.GMarker.setVisible false

		A2Cribs.FeaturedListings.UpdateSidebar(sidebar_visible_listings)
		A2Cribs.Map.Repaint()

	###
	Initialize the underlying google maps functionality of the address search bar
	###
	@InitAddressSearch: ->
		#@PlacesService = new google.maps.places.PlacesService(@GMap)
		A2Cribs.FilterManager.Geocoder = new google.maps.Geocoder()

	@SearchForAddress: (div) ->
		if not A2Cribs.FilterManager.Geocoder? then A2Cribs.FilterManager.Geocoder = new google.maps.Geocoder()
		address = $(div).val()
		request = 
			location: A2Cribs.Map.GMap.getCenter()
			radius: 8100 # in meters (approximately 5 miles)
			types: ['street_address', 'street_number', 'postal_code', 'postal_code_prefix', 'point_of_interest', 'neighborhood', 'intersection', 'transit_station']
			keyword: address
			name: address
		A2Cribs.FilterManager.Geocoder.geocode { 'address' : address + " " + A2Cribs.FilterManager.CurrentCity + ", " + A2Cribs.FilterManager.CurrentState },
			(response, status) =>
				if status == google.maps.GeocoderStatus.OK && response[0].types[0] != "postal_code"
					$(div).effect("highlight", {color: "#5858FA"}, 2000)
					A2Cribs.Map.GMap.panTo response[0].geometry.location
					A2Cribs.Map.GMap.setZoom(18)
				else
					$(div).effect("highlight", {color: "#FF0000"}, 2000)
