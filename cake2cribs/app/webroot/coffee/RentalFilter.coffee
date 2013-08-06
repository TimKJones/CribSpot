class A2Cribs.RentalFilter extends A2Cribs.FilterManager

	###
	Called immediately after user applies a filter.
	Submits an ajax call with all current filter parameters
	###
	@ApplyFilter: (event, ui) ->
		#A2Cribs.Map.ClickBubble.Close()	
		ajaxData = null
		###
		ajaxData += "minBeds=" + $("#minBedsSelect").val()
		ajaxData += "&maxBeds=" + $("#maxBedsSelect").val()
		ajaxData += "&minBaths=" + $("#minBathsSelect").val()
		ajaxData += "&maxBaths=" + $("#maxBathsSelect").val()
		ajaxData += "&house=" + $("#houseCheck").is(':checked')
		ajaxData += "&apt=" + $("#aptCheck").is(':checked')
		ajaxData += "&duplex=" + $("#duplexCheck").is(':checked')
		ajaxData += "&ac=" + $("#acCheck").is(':checked')
		ajaxData += "&parking=" + $("#parkingCheck").is(':checked')
		###
		ajaxData = "min_beds=" + 0
		ajaxData += "&max_beds=" + 5
		ajaxData += "&min_baths=" + 0
		ajaxData += "&max_baths=" + 3
		ajaxData += "&min_rent=" + 0
		ajaxData += "&max_rent=" + 3000
		ajaxData += "&house=" + 1
		ajaxData += "&apt=" + 1
		ajaxData += "&duplex=" + 0
		ajaxData += "&ac=" + 0
		ajaxData += "&parking=" + 1
		ajaxData += "&month_1=" + 1
		ajaxData += "&month_2=" + 0
		ajaxData += "&month_3=" + 0
		ajaxData += "&month_4=" + 1
		ajaxData += "&month_5=" + 0
		ajaxData += "&month_6=" + 1
		ajaxData += "&month_7=" + 0
		ajaxData += "&month_8=" + 0
		ajaxData += "&month_9=" + 1
		ajaxData += "&month_10=" + 0
		ajaxData += "&month_11=" + 0
		ajaxData += "&month_12=" + 0
		currentYears = [13]
		ajaxData += "&curYear=" + JSON.stringify currentYears
		ajaxData += "&leaseLength=" + 7

		$.ajax
			url: myBaseUrl + "Rentals/ApplyFilter"
			data: ajaxData
			type: "GET"
			context: this
			success: A2Cribs.FilterManager.UpdateMarkers

	###
	Retrieves all listing_ids for a given marker_id that fit the current filter criteria
	###
	@FilterVisibleListings: (marker_id) ->
		listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker", marker_id
		visibile_listings = []
		for listing in listings
			rent = FilterRent listing
			beds = FilterBeds listing
			baths = FilterBaths listing
			building_type = FilterBuildingType listing
			dates = FilterDates listing
			unit_features = FilterUnitFeatures listing
			parking = FilterParking listing
			pets = FilterPets listing
			amenities = FilterAmenities listing
			square_feet = FilterSquareFeet listing
			year_built = FilterYearBuilt listing
			utilities = FilterUtilities listing
			if rent && beds && baths && building_type && dates && unit_features && parking && pets && amenities && square_feet && year_built && utilities
				visibile_listings.push listing

		return visibile_listings

	FilterRent: (listing) ->
		return true

	FilterBeds: (listing) ->
		return true

	FilterBaths: (listing) ->
		return true

	FilterBuildingType: (listing) ->
		return true

	FilterDates: (listing) ->
		return true

	FilterUnitFeatures: (listing) ->
		#a/c, furnished_type
		return true

	FilterParking: (listing) ->
		# parking type
		# parking spots
		# street_parking
		return true

	FilterPets: (listing) ->
		return true

	FilterAmenities: (listing) ->
		# smoking
		# tv
		# balcony
		# fridge
		# storage
		# pool
		# hot_tub
		# fitness_center
		# game_room
		# security_system
		# tanning_beds
		# study_lounge
		# patio_deck
		# yard_space
		# elevator
		return true

	FilterSquareFeet: (listing) ->
		return true

	FilterYearBuilt: (listing) ->
		return true

	FilterUtilities: (listing) ->
		return true