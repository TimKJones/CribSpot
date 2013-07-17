class A2Cribs.UserCache
	@Markers = {
			Rental: []
			Parking: []
			Sublet: []
		}

	@Listings = []
	
	get = (key) =>
		items = []
		for listing in @Listings
			if listing[key]? then items.push listing
		return items

	count = (key) =>
		items = 0
		for listing in @Listings
			if listing[key]? then items++
		return items

	get_markers = (key) =>
		if @Markers? and @Markers[key]
			return @Markers[key]

	get_marker_from_id = (id) =>
		for key, field of @Markers
			for marker in field
				if marker.marker_id is id
					return marker
		return null

	load_markers = (key) =>
		items = []
		for listing in @Listings
			if listing[key]? then items[listing.Marker.marker_id] = listing.Marker
		for marker in items
			if marker? then @Markers[key].push marker

	add_marker = (key, marker) =>
		if @Markers? and @Markers[key]
			alreadyAdded = no
			for m in @Markers[key]
				if marker.marker_id is m.marker_id
					alreadyAdded = yes
			if not alreadyAdded
				@Markers[key].push marker

	delete_listing = (listing_id) =>
		for i in [0..@Listings.length - 1]
			if @Listings[i].Listing.listing_id is listing_id
				@Listings.splice i, 1


	@CacheListings : (listing_list) ->
		@Listings = listing_list
		for i in [0..@Listings.length - 1]
			listing = @Listings[i]
			for key, field of listing
				for term, value of field
					if not value?
						delete @Listings[i][key][term]
					else if typeof value is "string" and (index = value.indexOf("00:00")) isnt -1
						@Listings[i][key][term] = value.substring 0, index - 1
					else if typeof value is "boolean"
						@Listings[i][key][term] = +value

		for key of @Markers
			load_markers key
					

	@GetListings: ->
		return @Listings

	@GetRentals: ->
		return get "Rental"

	@GetParking: ->
		return get "Parking"

	@GetSublets: ->
		return get "Sublet"

	@GetListingCount: ->
		if @Listings
			return @Listings.length()
		else
			return 0

	@GetSubletCount: ->
		count "Sublet"

	@GetParkingCount: ->
		count "Parking"

	@GetRentalCount: ->
		count "Rental"

	@GetRentalMarkers: ->
		get_markers "Rental"

	@GetSubletMarkers: ->
		get_markers "Sublet"

	@GetParkingMarkers: ->
		get_markers "Parking"

	@GetListingMarkers: ->
		return {
			sublet: @GetSubletMarkers()
			parking: @GetParkingMarkers()
			rentals: @GetRentalMarkers() 
		}

	@AddSubletMarker: (marker) ->
		add_marker "Sublet", marker

	@AddParkingMarker: (marker) ->
		add_marker "Parking", marker

	@AddRentalMarker: (marker) ->
		add_marker "Rental", marker

	@DeleteListing: (listing_id) ->
		delete_listing listing_id

	@GetMarkerById: (id) ->
		get_marker_from_id id

