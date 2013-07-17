class A2Cribs.Cache

	@IdToSubletMap = []	#stores all listings after being loaded.
	@IdToMarkerMap = []
	@IdToUniversityMap = []
	@IdToHousematesMap = []
	@SubletIdToHousemateIdsMap = []
	@SubletIdToOwnerMap = []
	@SubletIdToImagesMap = []
	@MarkerIdToHoverDataMap = []
	@MarkerIdToSubletIdsMap = [] #maps marker ids to list of sublet_ids
							#TODO: Set a maximum size for each cache.

	@IdToMarkerMap = []		#Map of MarkerIds to Marker objects
	@AddressToMarkerIdMap = [] #Used to determine if searched address is property in database
	@BuildingIdToNameMap = []
	@BathroomIdToNameMap = []
	@GenderIdToNameMap = []
	@StudentTypeIdToNameMap = []
	@FavoritesSubletIdsList = []
	@FavoritesMarkerIdsList = []

	
	@IdToRentalMap = []
	@IdToParkingMap = []
	@ListingIdToUserMap = [] #Only contains public user data

	@SubletEditInProgress = null

	###
	Add list of sublets to cache
	###
	@CacheSublet: (sublet) ->
		l = sublet
		l.id = parseInt l.id
		l.marker_id = parseInt l.marker_id
		@MarkerIdToSubletIdsMap[parseInt sublet.marker_id].push l.id
		l.number_bedrooms = parseInt l.number_bedrooms
		l.price_per_bedroom = parseInt l.price_per_bedroom
		l.number_bedrooms = parseInt l.number_bedrooms
		l.utility_cost = parseInt l.utility_cost
		l.deposit_amount = parseInt l.deposit_amount
		l.additional_fees_amount = parseInt l.additional_fees_amount
		l.marker_id = parseInt l.marker_id
		l.furnished_type_id = parseInt l.furnished_type_id
		building = @IdToMarkerMap[l.marker_id].UnitType
		l.bathroom_type_id = parseInt l.bathroom_type_id
		bathroom = @BathroomIdToNameMap[l.bathroom_type_id]
		l.university_id = parseInt l.university_id
		@IdToSubletMap[l.id] = new A2Cribs.Sublet(l.id, l.university_id, building, l.name, l.street_address, l.city, l.state, l.date_begin, l.date_end, l.number_bedrooms, l.price_per_bedroom, l.short_description, bathroom, l.utility_cost, l.deposit_amount, l.additional_fees_description, l.additional_fees_amount, l.marker_id, l.flexible_dates, l.furnished_type_id, l.created, l.ac, l.parking)

	###
	Add a list of subletIds to the MarkerIdToSubletIdsMap
	###
	@CacheMarkerIdToSubletsList: (sublets) ->
		A2Cribs.Map.MarkerIdToSubletIdsMap[parseInt sublets[0].Sublet.marker_id] = []
		for sublet in sublets
			if (sublet == undefined)
				continue
			@MarkerIdToSubletIdsMap[parseInt sublet.Sublet.marker_id].push parseInt(sublet.Sublets.sublet_id)

	@CacheUniversity: (university) ->
		if university == null
			return
		id = parseInt university.id
		@IdToUniversityMap[id] = new A2Cribs.University(university.city, university.domain, university.name, university.state)

	@CacheHoverData: (hoverDataList) ->
		###
		TODO: find min and max dates
		###
		markerIdToHd = []

		for hd in hoverDataList
			marker_id = null
			if hd != null
				marker_id = parseInt hd.Sublet.marker_id
				if @IdToMarkerMap[marker_id] == undefined #Only cache for markers currently loaded on map.
					continue
				else
					if markerIdToHd[marker_id] == undefined
						markerIdToHd[marker_id] = []
					markerIdToHd[marker_id].push hd
			else
				continue

		for marker_id, hdList of markerIdToHd
			numListings = hdList.length
			sublet = hdList[0].Sublet
			if sublet == undefined  || sublet == null
				return

			unitType = @IdToMarkerMap[marker_id].UnitType
				
			#find min and max for remaining fields
			minBeds = parseInt sublet.number_bedrooms
			maxBeds = parseInt sublet.number_bedrooms
			minRent = parseInt sublet.price_per_bedroom
			maxRent = parseInt sublet.price_per_bedroom
			minDate = sublet.date_begin
			maxDate = sublet.date_end

			for hd in hdList
				sublet = hd.Sublet
				building_type_id = parseInt sublet.building_type_id
				beds = parseInt sublet.number_bedrooms
				price = parseInt sublet.price_per_bedroom
				if beds < minBeds
					minBeds = beds
				if beds > maxBeds 
					maxBeds = beds
				if price < minRent
					minRent = price
				if price > maxRent
					maxRent = price
			hd = new A2Cribs.HoverData(numListings, unitType, minBeds, maxBeds, minRent, maxRent, minDate, maxDate)
			@MarkerIdToHoverDataMap[marker_id] = hd

	@CacheHousemates: (housemates) ->
		if not housemates?
			return

		sublet_id = null
		if housemates.sublet_id?
			sublet_id = parseInt housemates.sublet_id
		else
			return
			
		@SubletIdToHousemateIdsMap[sublet_id] = []
		id = parseInt housemates.id
		grad_status = @StudentTypeIdToNameMap[parseInt housemates.student_type_id]
		gender = @GenderIdToNameMap[parseInt housemates.gender_type_id]
		sublet_id = parseInt housemates.sublet_id
		quantity = parseInt housemates.quantity
		@IdToHousematesMap[id] = new A2Cribs.Housemate sublet_id, housemates.enrolled, housemates.major, housemates.seeking, grad_status, gender, quantity
		@SubletIdToHousemateIdsMap[sublet_id].push id

	@CacheImages: (imageList) ->
		if imageList == undefined or imageList == null or imageList[0] == undefined
			return

		first_image = imageList[0]
		if first_image == undefined or first_image.sublet_id == undefined
			return

		sublet_id = parseInt first_image.sublet_id
		A2Cribs.Cache.SubletIdToImagesMap[sublet_id] = []
		for image in imageList
			sublet_id = parseInt image.sublet_id
			path = "/" + image.image_path
			is_primary = image.is_primary
			caption = image.caption
			A2Cribs.Cache.SubletIdToImagesMap[sublet_id].push new A2Cribs.Image(sublet_id, path, is_primary, caption)

	@CacheMarker: (id, marker) ->
		m = marker
		unitType = @BuildingIdToNameMap[parseInt m.building_type_id]
		@IdToMarkerMap[id] =  new A2Cribs.Marker(parseInt(id), m.street_address, m.alternate_name, unitType, m.latitude, m.longitude, m.city, m.state)

	@CacheSubletOwner: (sublet_id, user) ->
		owner = new A2Cribs.SubletOwner(user)
		@SubletIdToOwnerMap[sublet_id] = owner

	###
	Add sublet data to cache
	###
	@CacheMarkerData = (markerDataList) ->
		#Initialize @MarkerIdToSubletIdsMap for this marker_id
		if markerDataList[0] != undefined && markerDataList[0].Sublet != undefined 
			marker_id = parseInt markerDataList[0].Sublet.marker_id
			@MarkerIdToSubletIdsMap[marker_id] = []

		for markerData in markerDataList
			sublet = markerData.Sublet
			A2Cribs.Cache.CacheSublet sublet
			A2Cribs.Cache.CacheHousemates markerData.Housemate
		#	A2Cribs.Cache.CacheUniversity markerData.University
			A2Cribs.Cache.CacheSubletOwner parseInt(sublet.id), markerData.User
			A2Cribs.Cache.CacheImages markerData.Image

	@CacheSubletAddStep1: (data) ->
		A2Cribs.Cache.Step1Data = data

	@CacheSubletAddStep2: (data) ->
		A2Cribs.Cache.Step2Data = data

	@CacheSubletAddStep3: (data) ->
		A2Cribs.Cache.Step3Data = data


	###
	Adds new rental object to IdToRentalMap
	###
	@AddRental:(rental) ->
		rental.air = parseInt rental.air
		rental.beds = parseInt rental.beds
		rental.baths = parseInt rental.baths
		rental.building_type = parseInt rental.building_type
		rental.cable = parseInt rental.cable
		rental.deposit = parseInt rental.deposit
		rental.electric = parseInt rental.electric
		rental.furnished_type = parseInt rental.furnished_type
		rental.gas = parseInt rental.gas
		rental.heat = parseInt rental.heat
		rental.internet = parseInt rental.internet
		rental.listing_id = parseInt rental.listing_id
		rental.min_occupancy = parseInt rental.min_occupancy
		rental.max_occupancy = parseInt rental.max_occupancy
		rental.parking_spots = parseInt rental.parking_spots
		rental.parking_type = parseInt rental.parking_type
		rental.pets_type = parseInt rental.pets_type
		rental.rent = parseInt rental.rent
		rental.rental_id = parseInt rental.rental_id
		rental.sewage = parseInt rental.sewage
		rental.square_feet = parseInt rental.square_feet
		rental.trash = parseInt rental.trash
		rental.unit_style_options = parseInt rental.unit_style_options
		rental.utility_estimate_summer = parseInt rental.utility_estimate_summer
		rental.utility_estimate_winter = parseInt rental.utility_estimate_winter
		rental.water = parseInt rental.water
		rental.year_built = parseInt rental.year_built
		@IdToRentalMap[rental.rental_id] = rental
	###
	Creates a new Rental object from rental
	Adds new rental object to IdToRentalMap
	###

	###
	Adds new parking object to IdToParkingMap
	###
	@AddParking:(parking) ->
		@IdToParkingMap[parseInt parking.parking_id] = parking

	###
	Adds new user object to RentalIdToUserMap
	IMPORTANT: only contains public, non-sensitive user data
	###
	@AddUser:(listing_id, user) ->
		@ListingIdToUserMap[listing_id] = user

	###
	Adds listing to the appropriate cache based on listing_type
	###
	@AddListing: (listing) ->
		if listing == undefined || listing == null
			return

		if listing.Rental != undefined
			@AddRental listing.Rental
		else if listing.Parking != undefined
			@AddParking listing.Parking
		
		@AddUser parseInt(listing.Listing.listing_id), listing.User

	###
	Returns listing object specified by listing_id
	###
	@GetListing:(listing_id) ->
		#See if listing is already cached
		if listing_id in @IdToRentalMap
			return @IdToRentalMap[listing_id]

		#Listing not in cache. Fetch it from database
		listing = null
		$.ajax
			url: myBaseUrl + "Listings/GetListing/" + listing_id
			type:"GET"
			context: this
			async: false
			success: (response) ->
				listing = JSON.parse response
				@AddListing listing[0]

		if listing != null
			return listing[0]
		else
			return null

	###
	Loads all listings owned by logged-in user
	Loads PUBLIC user data for user into cache
	Returns array of listings
	###
	@GetListingsByLoggedInUser:() ->
		listings = null
		$.ajax
			url: myBaseUrl + "Listings/GetListingsByLoggedInUser"
			type:"GET"
			context: this
			async: false
			success: (response) ->
				listings = JSON.parse response
				for listing in listings
					@AddListing listing
		return listings