class A2Cribs.Cache

	@IdToSubletMap = []	#stores all listings after being loaded.
	@IdToMarkerMap = []
	@IdToUniversityMap = []
	@IdToHousematesMap = []
	@SubletIdToHousemateIdsMap = []
	@MarkerIdToHoverDataMap = []
	@MarkerIdToSubletIdsMap = [] #maps marker ids to list of sublet_ids
							#TODO: Set a maximum size for each cache.

	@IdToMarkerMap = []		#Map of MarkerIds to Marker objects
	@AddressToMarkerIdMap = [] #Used to determine if searched address is property in database
	@BuildingNameToIdMap = []
	@BathroomNameToIdMap = []

	###
	Add list of sublets to cache
	###
	@CacheSublet: (sublet) ->
		l = sublet
		l.id = parseInt l.id
		l.marker_id = parseInt l.marker_id
		@MarkerIdToSubletIdsMap[parseInt sublet.marker_id] = []
		@IdToSubletMap[l.id] = new A2Cribs.Sublet(l.id, l.university_id, l.building_type, l.name, l.street_address, l.city, l.state, l.start_date, l.end_date, l.bedrooms, l.price_per_bedroom, l.description, l.bathrooms, l.bathroom_type, l.utility_cost, l.deposit_amount, l.additional_fees_description, l.additional_fees_amount, l.marker_id, l.flexible_dates, l.furnished)

	###
	Add a list of subletIds to the MarkerIdToSubletIdsMap
	###
	@CacheMarkerIdToSubletsList: (sublets) ->
		A2Cribs.Map.MarkerIdToSubletIdsMap[sublets[0].Sublet.marker_id] = []
		for sublet in sublets
			if (sublet == undefined)
				continue
			@MarkerIdToSubletIdsMap[sublet.Sublet.marker_id].push parseInt(sublet.Sublets.sublet_id)

	@CacheUniversity: (university) ->
		if university == null
			return
		id = parseInt university.id
		@IdToUniversityMap[id] = new A2Cribs.University(university.city, university.domain, university.name, university.state)

	@CacheHoverData: (marker_id, hoverData) ->
		###h = hoverData
		@MarkerIdToHoverDataMap[marker_id] = new A2Cribs.HoverData(h.UnitType, @Beds, @Rent, @Duration)###

	@CacheHousemates: (sublet_id, housemates) ->
		if housemates == null
			return

		sublet_id = parseInt sublet_id
		@SubletIdToHousemateIdsMap[sublet_id] = []
		for h in housemates
			h.id = parseInt h.id
			@IdToHousematesMap[h.id] = new A2Cribs.Housemate(sublet_id, h.enrolled, h.major, h.seeking, h.type)
			@SubletIdToHousemateIdsMap[sublet_id].push h.id

	@CacheMarker: (id, marker) ->
		m = marker
		@IdToMarkerMap[id] =  new A2Cribs.Marker(id, m.address, m.alternate_name, m.unit_type, m.latitude, m.longitude)

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
			A2Cribs.Cache.CacheUniversity markerData.University