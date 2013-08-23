class A2Cribs.ImportManager
	@Indices = {
		marker_street_address: 0
		city:1
		state:2
		unit_style_options: 3
		unit_description: 4
		min_rent:5
		max_rent:6
		beds :7
		baths :8
		start_date:9
		end_date:10
		alternate_start_date: 11
		electric:13
		water:14
		gas:15
		trash:16
		cable:17
		internet:18
		utility_total_flat_rate: 19
		square_feet: 20
		air:21
		pets:22
		street_parking:23
		private_parking:24
		parking_type:25
		parking_cost:26
		furnished_type:27
		building_type:28
		alternate_name:29
		company_name:30
		phone:31
		email:32
		website:33
		tv:34
		balcony:35
		fridge:36
		storage:37
		pool:38
		hot_tub:39
		fitness_center:40
		game_room:41
		front_desk:42
		security_system:43
		tanning_beds:44
		study_lounge:45
		patio_deck:46
		yard_space:47
		elevator:48
		deposit:49
		admin_amount:50
		furniture_amount:52
		pets_amount:53
		amenity_amount:54
		upper_floor_amount:55
		extra_occupant_amount:56
		year_built:58
		min_occupancy:59
		max_occupancy:59
		unit_count:60
		smoking:61
		laundry:62
		user_street_address:63
		description: 64
	}


	@GetListingsFromCSV: (filename=null) ->
		url = myBaseUrl + "Import/GetListings/"
		if filename != null
			url += "/" + filename
		$.ajax 
			url: url
			type:"GET"
			context: this
			success: (response) ->
				@ProcessAndSubmitListings(response)
			error: (response) ->
				console.log response

	@ProcessAndSubmitListings:(listings) ->
		console.log JSON.parse listings
		listings = JSON.parse listings
		processedListings = []
		for l in listings[0]
			listing = {}
			listing['Marker'] = {}
			listing['Listing'] = {
				listing_type: 0
				visible: 1
			}
			listing['Rental'] = {}
			listing['User'] = {}
			###
			The order of the fields is known from the excel template.
			Go through each field, placing them in their correct container (listing, rental, or user)
			Do any processing we can on each field
			###

			listing['Marker']['street_address'] = l[@Indices['marker_street_address']]
			listing['Marker']['city'] = l[@Indices['city']]
			listing['Marker']['state'] = l[@Indices['state']]
			listing['Rental']['unit_description'] = l[@Indices['unit_description']]
			listing['Rental']['rent'] = @GetRent l[@Indices['min_rent']], l[@Indices['max_rent']]
			listing['Rental']['beds'] = l[@Indices['beds']]
			listing['Rental']['baths'] = l[@Indices['baths']]
			###
			#??? Need to format dates???
			###
			listing['Rental']['start_date'] = l[@Indices['start_date']]
			listing['Rental']['end_date'] = l[@Indices['end_date']]
			listing['Rental']['electric'] = l[@Indices['electric']]
			listing['Rental']['water'] = l[@Indices['water']]
			listing['Rental']['gas'] = l[@Indices['gas']]
			listing['Rental']['heat'] = l[@Indices['heat']]
			listing['Rental']['trash'] = l[@Indices['trash']]
			listing['Rental']['cable'] = l[@Indices['cable']]
			listing['Rental']['internet'] = l[@Indices['internet']]
			listing['Rental']['utility_total_flat_rate'] = l[@Indices['utility_total_flat_rate']]
			listing['Rental']['square_feet'] = l[@Indices['square_feet']]
			listing['Rental']['air'] = l[@Indices['air']]
			listing['Rental']['pets'] = l[@Indices['pets']]
			private_parking = l[@Indices['private_parking']]
			parking_type = l[@Indices['parking_type']]
			parking_cost_type = l[@Indices['parking_cost_type']]
			parking_cost = l[@Indices['parking_cost']]
			listing['Rental']['parking_type'] = l[@Indices['parking_type']]
			listing['Rental']['street_parking'] = l[@Indices['street_parking']]
			listing['Rental']['utility_total_flat_rate'] = l[@Indices['utility_total_flat_rate']]
			listing['Rental']['parking_description'] = l[@Indices['parking_description']]
			listing['Rental']['parking_amount'] = l[@Indices['parking_amount']]
			listing['Rental']['furnished_type'] = l[@Indices['furnished_type']]
			listing['Marker']['building_type'] = l[@Indices['building_type']]
			listing['Marker']['alternate_name'] = l[@Indices['alternate_name']]
			listing['User']['company_name'] = l[@Indices['company_name']]
			listing['User']['phone'] = l[@Indices['phone']]
			listing['User']['email'] = l[@Indices['email']]
			listing['Rental']['website'] = l[@Indices['website']]
			listing['Rental']['tv'] = l[@Indices['tv']]
			listing['Rental']['balcony'] = l[@Indices['balcony']]
			listing['Rental']['fridge'] = l[@Indices['fridge']]
			listing['Rental']['storage'] = l[@Indices['storage']]
			listing['Rental']['pool'] = l[@Indices['pool']]
			listing['Rental']['hot_tub'] = l[@Indices['hot_tub']]
			listing['Rental']['fitness_center'] = l[@Indices['fitness_center']]
			listing['Rental']['game_room'] = l[@Indices['game_room']]
			listing['Rental']['front_desk'] = l[@Indices['front_desk']]
			listing['Rental']['security_system'] = l[@Indices['security_system']]
			listing['Rental']['tanning_beds'] = l[@Indices['tanning_beds']]
			listing['Rental']['study_lounge'] = l[@Indices['study_lounge']]
			listing['Rental']['patio_deck'] = l[@Indices['patio_deck']]
			listing['Rental']['yard_space'] = l[@Indices['yard_space']]
			listing['Rental']['elevator'] = l[@Indices['elevator']]
			listing['Rental']['deposit'] = l[@Indices['deposit']]
			listing['Rental']['admin_amount'] = l[@Indices['admin_amount']]
			listing['Rental']['furniture_amount'] = l[@Indices['furniture_amount']]
			listing['Rental']['pets_amount'] = l[@Indices['pets_amount']]
			listing['Rental']['upper_floor_amount'] = l[@Indices['upper_floor_amount']]
			listing['Rental']['extra_occupant_amount'] = l[@Indices['extra_occupant_amount']]
			listing['Rental']['amenity_amount'] = l[@Indices['amenity_amount']]
			listing['Rental']['year_built'] = l[@Indices['year_built']]
			listing['Rental']['min_occupancy'] = l[@Indices['min_occupancy']]
			listing['Rental']['max_occupancy'] = l[@Indices['max_occupancy']]
			listing['Rental']['unit_count'] = l[@Indices['unit_count']]
			listing['Rental']['smoking'] = l[@Indices['smoking']]
			listing['User']['street_address'] = l[@Indices['user_street_address']]
			processedListings.push listing
		#nextListingList = [] #send back in groups of 10
		#for listing in processedListings
			#if nextListingList.length < 1
			#	nextListingList.push listing
			#	continue
		#jsonString = JSON.stringify processedListings
		#escapedJSON = @escapeJSON jsonString
			$.ajax 
				url: myBaseUrl + "Import/SaveListings"
				type:"POST"
				data: listing
				context: this
				async: false
				success: (response) ->
					console.log response

	@GetRent: (min_rent, max_rent) ->
		if max_rent != undefined && max_rent != null
			return max_rent
		else
			return min_rent

	@delay: (ms, func) ->
		setTimeout func, ms

	@escapeJSON : (str) ->
		return str.replace(/[\\]/g, '\\\\').replace(/[\"]/g, '\\\"').replace(/[\/]/g, '\\/').replace(/[\b]/g, '\\b').replace(/[\f]/g, '\\f').replace(/[\n]/g, '\\n').replace(/[\r]/g, '\\r').replace(/[\t]/g, '\\t');