class A2Cribs.Rental
	constructor:\
	(	@rental_id, @listing_id, @street_address, @city, @state, @zip, @unit_style_options, @unit_style_type, @unit_style_description, @building_name 
	,	@beds, @min_occupancy, @max_occupancy, @building_type, @rent, @rent_negotiable, @unit_count, @start_date
	,	@alternate_start_date, @lease_length, @available, @baths, @air, @parking_type, @parking_spots, @street_parking
	,	@furnished_type, @pets_type, @smoking, @square_feet, @year_built, @electric, @water, @gas, @heat, @sewage, @trash
	,	@cable, @internet, @utility_total_flat_rate, @utility_estimate_winter, @utility_estimate_summer, @deposit
	,	@highlights, @description, @waitlist, @waitlist_open_date, @lease_office_address, @contact_email, @contact_phone
	,	@website
	) ->

	@Save: () ->
		data = 
			rental_id: A2Cribs.UI_Rentals.rental_id()
			listing_id: A2Cribs.UI_Rentals.listing_id()
			street_address: A2Cribs.UI_Rentals.street_address()
			city: A2Cribs.UI_Rentals.city()
			state: A2Cribs.UI_Rentals.state()
			zipcode: A2Cribs.UI_Rentals.zipcode()
			unit_style_options: A2Cribs.UI_Rentals.unit_style_options()
			unit_style_type: A2Cribs.UI_Rentals.unit_style_type()
			unit_style_description: A2Cribs.UI_Rentals.unit_style_description()
			building_name: A2Cribs.UI_Rentals.building_name()
			beds: A2Cribs.UI_Rentals.beds()
			min_occupancy: A2Cribs.UI_Rentals.min_occupancy()
			max_occupancy: A2Cribs.UI_Rentals.max_occupancy()
			building_type: A2Cribs.UI_Rentals.building_type()
			rent: A2Cribs.UI_Rentals.rent()
			rent_negotiable: A2Cribs.UI_Rentals.rent_negotiable()
			unit_count: A2Cribs.UI_Rentals.unit_count()
			start_date: A2Cribs.UI_Rentals.start_date()
			alternate_start_date: A2Cribs.UI_Rentals.alternate_start_date()
			lease_length: A2Cribs.UI_Rentals.lease_length()
			available: A2Cribs.UI_Rentals.available()
			baths: A2Cribs.UI_Rentals.baths()
			air: A2Cribs.UI_Rentals.air()
			parking_type: A2Cribs.UI_Rentals.parking_type()
			parking_spots: A2Cribs.UI_Rentals.parking_spots()
			street_parking: A2Cribs.UI_Rentals.street_parking()
			furnished_type: A2Cribs.UI_Rentals.furnished_type()
			pets_type: A2Cribs.UI_Rentals.pets_type()
			smoking: A2Cribs.UI_Rentals.smoking()
			square_feet: A2Cribs.UI_Rentals.square_feet()
			year_built: A2Cribs.UI_Rentals.year_built()
			electric: A2Cribs.UI_Rentals.electric()
			water: A2Cribs.UI_Rentals.water()
			gas: A2Cribs.UI_Rentals.gas()
			heat: A2Cribs.UI_Rentals.heat()
			sewage: A2Cribs.UI_Rentals.sewage()
			trash: A2Cribs.UI_Rentals.trash()
			cable: A2Cribs.UI_Rentals.cable()
			internet: A2Cribs.UI_Rentals.internet()
			utility_total_flat_rate: A2Cribs.UI_Rentals.utility_total_flat_rate()
			utility_estimate_winter: A2Cribs.UI_Rentals.utility_estimate_winter()
			utility_estimate_summer: A2Cribs.UI_Rentals.utility_estimate_summer()
			deposit: A2Cribs.UI_Rentals.deposit()
			highlights: A2Cribs.UI_Rentals.highlights()
			description: A2Cribs.UI_Rentals.description()
			waitlist: A2Cribs.UI_Rentals.waitlist()
			waitlist_open_date: A2Cribs.UI_Rentals.waitlist_open_date()
			lease_office_address: A2Cribs.UI_Rentals.lease_office_address()
			contact_email: A2Cribs.UI_Rentals.contact_email()
			contact_phone: A2Cribs.UI_Rentals.contact_phone()
			website: A2Cribs.UI_Rentals.website()

		$.ajax
			url: myBaseUrl + "rentals/Save"
			type: "POST"
			data: data
			success: (response) =>
				response = JSON.parse response
				if response.success != null
					alert "Success!"
				else
					alert response.error

	SetupUI: ->
		###
		********************* TODO **********************
		###
		# Create grid and setup necessary grid code
		# Create jquery listeners for buttons on Rentals layout

	Open: (rental_ids) ->
		###
		********************* TODO **********************
		###
		# Gets rental info and saves to JS object

	Save: ->
		###
		********************* TODO **********************
		###
		# Sends array of rentals to backend

	Copy: (rental_ids) ->
		###
		********************* TODO (Not first priority) *
		###
		# Create new on backend
		# Update grid

	Delete: (rental_ids) ->
		###
		********************* TODO **********************
		###
		# Update backend and grid

	Create: ->
		###
		********************* TODO **********************
		###
		# Create newline on grid

	PopulateGrid: (rental_ids) ->
		###
		********************* TODO **********************
		###
		# Pre-populate grid based on selected address
