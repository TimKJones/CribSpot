class A2Cribs.Cache

	@IdToSubletMap = []	#stores all listings after being loaded.
	@IdToMarkerMap = []
	@IdToUniversityMap = []
	@IdToHousematesMap = []
	@SubletIdToHousemateIdsMap = []
	@SubletIdToOwnerMap = []
	@MarkerIdToHoverDataMap = []
	@MarkerIdToSubletIdsMap = [] #maps marker ids to list of sublet_ids
							#TODO: Set a maximum size for each cache.

	@IdToMarkerMap = []		#Map of MarkerIds to Marker objects
	@AddressToMarkerIdMap = [] #Used to determine if searched address is property in database
	@BuildingIdToNameMap = []
	@BathroomIdToNameMap = []
	@GenderIdToNameMap = []
	@StudentTypeIdToNameMap = []

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
		l.number_bathrooms = parseInt l.number_bathrooms
		l.utility_cost = parseInt l.utility_cost
		l.deposit_amount = parseInt l.deposit_amount
		l.additional_fees_amount = parseInt l.additional_fees_amount
		l.marker_id = parseInt l.marker_id
		l.furnished_type_id = parseInt l.furnished_type_id
		l.building_type_id = parseInt l.building_type_id
		building = @BuildingIdToNameMap[l.building_type_id]
		l.bathroom_type_id = parseInt l.bathroom_type_id
		bathroom = @BathroomIdToNameMap[l.bathroom_type_id]
		l.university_id = parseInt l.university_id
		@IdToSubletMap[l.id] = new A2Cribs.Sublet(l.id, l.university_id, building, l.name, l.street_address, l.city, l.state, l.date_begin, l.date_end, l.number_bedrooms, l.price_per_bedroom, l.description, l.number_bathrooms, bathroom, l.utility_cost, l.deposit_amount, l.additional_fees_description, l.additional_fees_amount, l.marker_id, l.flexible_dates, l.furnished_type_id, l.created)

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

	@CacheHousemates: (sublet_id, housemates) ->
		if housemates == null
			return

		sublet_id = parseInt sublet_id
		@SubletIdToHousemateIdsMap[sublet_id] = []
		for h in housemates
			h.id = parseInt h.id
			@IdToHousematesMap[h.id] = new A2Cribs.Housemate(sublet_id, h.enrolled, h.major, h.seeking, parseInt h.type)
			@SubletIdToHousemateIdsMap[sublet_id].push h.id

	@CacheMarker: (id, marker) ->
		m = marker
		unitType = @BuildingIdToNameMap[parseInt m.building_type_id]
		@IdToMarkerMap[id] =  new A2Cribs.Marker(parseInt(id), m.address, m.alternate_name, unitType, m.latitude, m.longitude)

	@CacheSubletOwner: (sublet_id, user) ->
		owner = new A2Cribs.SubletOwner(user.first_name, user.facebook_userid, user.verified_university, user.twitter_followers)
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
			A2Cribs.Cache.CacheHousemates sublet.id, markerData.Housemate
		#	A2Cribs.Cache.CacheUniversity markerData.University
			A2Cribs.Cache.CacheSubletOwner parseInt(sublet.id), markerData.User