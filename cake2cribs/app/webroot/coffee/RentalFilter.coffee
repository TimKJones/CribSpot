class A2Cribs.RentalFilter extends A2Cribs.FilterManager
	@Beds = ''
	@Rent = ''
	@Months = ''

	###
	Called immediately after user applies a filter.
	Submits an ajax call with all current filter parameters
	###
	@ApplyFilter: (field, value) ->
		@[field] = value
		#A2Cribs.Map.ClickBubble.Close()
		ajaxData += "&beds=" + @GetBeds()
		ajaxData += "&rent=" + @Rent
		ajaxData += "&parking=" + 1
		ajaxData += "&dates=" + JSON.stringify @GetMonths()
		ajaxData += "&unit_types=" + JSON.stringify @GetUnitTypes()
		ajaxData += "&amenities=" + JSON.stringify @GetAmenities()
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

	@GetBeds: () ->
		beds = [3, 5, 6, 10]
		return JSON.stringify beds

	@GetRent: () ->
		return @
		rent =
			"min" : 100
			"max" : 5000
		return JSON.stringify rent

	@GetMonths: () ->
		dates = 
			"months" :
				"1" : 1
				"2" : 0
				"3" : 1
				"4" : 0
				"5" : 1
				"6" : 0
				"7" : 1
				"8" : 0
				"9" : 1
				"10": 0
				"11": 1
				"12": 0
			"curYear" : [13, 14]
			"leaseLength" :
				'min' : 2
				'max' : 4	
		return dates

	@GetUnitTypes: () ->
		unit_types = 
			"house" : 0
			"apartment" : 1
			"duplex" : 1
			"other" : 0

	@GetAmenities: () ->
		amenities =
			'elevator' : 1

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