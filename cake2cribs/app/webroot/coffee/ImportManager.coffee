class A2Cribs.ImportManager

	@GetListingsFromCSV: (filename=null) ->
		url = myBaseUrl + "Import/GetListings"
		if filename != null
			url += "/" + filename
		$.ajax 
			url: url
			type:"GET"
			context: this
			success: (response) ->
				@ProcessAndSubmitListings(response)

	@ProcessAndSubmitListings:(listings) ->
		console.log JSON.parse listings
		listings = JSON.parse listings
		processedListings = []
		for l in listings[0]
			listing = []
			listing['Marker'] = []
			listing['Listing'] = []
			listing['Rental'] = []
			listing['User'] = []
			###
			The order of the fields is known from the excel template.
			Go through each field, placing them in their correct container (listing, rental, or user)
			Do any processing we can on each field
			###

			listing['Marker']['street_address'] = l[0]
			listing['Rental']['unit_description'] = l[1]
			listing['Rental']['rent'] = @GetRent l[2], l[3]
			listing['Rental']['beds'] = l[4]
			listing['Rental']['baths'] = l[5]
			###
			#??? Need to format dates???
			###
			listing['Rental']['start_date'] = l[6]
			listing['Rental']['end_date'] = l[7]
			listing['Rental']['electric'] = l[8]
			listing['Rental']['water'] = l[9]
			listing['Rental']['gas'] = l[10]
			listing['Rental']['heat'] = l[11]
			listing['Rental']['sewage'] = l[12]
			listing['Rental']['trash'] = l[13]
			listing['Rental']['cable'] = l[14]
			listing['Rental']['internet'] = l[15]
			listing['Rental']['utility_total_flat_rate'] = l[16]
			listing['Rental']['square_feet'] = l[17]
			listing['Rental']['air'] = l[18]
			listing['Rental']['pets'] = l[19]
			private_parking = l[21]
			parking_type = l[22]
			parking_cost_type = l[23]
			parking_cost = l[24]
			listing['Rental']['parking_type'] = l[21]
			listing['Rental']['street_parking'] = l[20]
			listing['Rental']['utility_total_flat_rate'] = l[23]
			listing['Rental']['parking_description'] = l[22]
			listing['Rental']['parking_amount'] = l[24]
			listing['Rental']['furnished_type'] = l[26]
			listing['Marker']['building_type'] = l[27]
			listing['Marker']['building_name'] = l[28]
			listing['User']['company_name'] = l[29]
			listing['User']['phone'] = l[30]
			listing['User']['email'] = l[31]
			listing['User']['website'] = l[32]
			listing['Rental']['tv'] = l[33]
			listing['Rental']['balcony'] = 
			listing['Rental']['fridge']
			listing['Rental']['storage']
			listing['Rental']['pool']
			listing['Rental']['hot_tub']
			listing['Rental']['fitness_center']
			listing['Rental']['game_room']
			listing['Rental']['front_desk']
			listing['Rental']['security_system']
			listing['Rental']['tanning_beds']
			listing['Rental']['study_lounge']
			listing['Rental']['patio_deck']
			listing['Rental']['yard_space']
			listing['Rental']['elevator']
			listing['Rental']['deposit']
			listing['Rental']['admin_amount']
			listing['Rental']['furniture_amount']
			listing['Rental']['pets_amount']
			listing['Rental']['upper_floor_amount']
			listing['Rental']['extra_occupant_amount']
			listing['Rental']['amenity_amount']
			###
			#???Add something for "other" fee???
			###
			listing['Rental']['year_built']
			listing['Rental']['min_occupancy']
			listing['Rental']['max_occupancy']
			listing['Rental']['unit_count']
			listing['Rental']['smoking']
			listing['Rental']['laundry'] # New field - tell Evan
			listing['User']['street_address']
			listing['Rental']['street_address']
			listing['User']['street_address']
			processedListings.push listing
		$.ajax 
			url: myBaseUrl + "Import/SaveListings/" + JSON.stringify listings
			type:"GET"
			context: this
			success: (response) ->
				console.log response

	@GetRent: (min_rent, max_rent) ->
		if max_rent != undefined && max_rent != null
			return max_rent
		else
			return min_rent
