class A2Cribs.Rental
	constructor:\
	(	@rental_id, @listing_id, @street_address, @city, @state, @zip, @unit_style_options, @unit_style_type, @unit_style_description, @building_name 
	,	@beds, @min_occupancy, @max_occupancy, @building_type, @rent, @rent_negotiable, @unit_count, @start_date
	,	@alternate_start_date, @end_date, @dates_negotiable, @available, @baths, @air, @parking_type, @parking_spots, @street_parking
	,	@furnished_type, @pets_type, @smoking, @tv, @balcony, @fridge, @storage, @square_feet, @year_built 
	,	@pool, @hot_tub, @fitness_center, @game_room, @front_desk, @security_system, @tanning_beds, @study_lounge
	,	@patio_deck, @yard_space, @elevator, @electric, @water, @gas, @heat, @sewage, @trash
	,	@cable, @internet, @utility_total_flat_rate, @utility_estimate_winter, @utility_estimate_summer, @deposit
	,	@highlights, @description, @waitlist, @waitlist_open_date, @lease_office_address, @contact_email, @contact_phone
	,	@website
	) ->

	@Template =
		data =
			Listing:
				listing_type: 0
			Rental:
				street_address: A2Cribs.UILayer.Rentals.street_address()
				city: A2Cribs.UILayer.Rentals.city()
				state: A2Cribs.UILayer.Rentals.state()
				zipcode: A2Cribs.UILayer.Rentals.zipcode()
				unit_style_options: A2Cribs.UILayer.Rentals.unit_style_options()
				unit_style_type: A2Cribs.UILayer.Rentals.unit_style_type()
				unit_style_description: A2Cribs.UILayer.Rentals.unit_style_description()
				building_name: A2Cribs.UILayer.Rentals.building_name()
				beds: A2Cribs.UILayer.Rentals.beds()
				min_occupancy: A2Cribs.UILayer.Rentals.min_occupancy()
				max_occupancy: 100
				building_type: A2Cribs.UILayer.Rentals.building_type()
				rent: A2Cribs.UILayer.Rentals.rent()
				rent_negotiable: A2Cribs.UILayer.Rentals.rent_negotiable()
				unit_count: A2Cribs.UILayer.Rentals.unit_count()
				start_date: A2Cribs.UILayer.Rentals.start_date()
				alternate_start_date: A2Cribs.UILayer.Rentals.alternate_start_date()
				end_date: A2Cribs.UILayer.Rentals.end_date()
				dates_negotiable: 0
				available: A2Cribs.UILayer.Rentals.available()
				baths: 555
				air: A2Cribs.UILayer.Rentals.air()
				parking_type: A2Cribs.UILayer.Rentals.parking_type()
				parking_spots: A2Cribs.UILayer.Rentals.parking_spots()
				street_parking: A2Cribs.UILayer.Rentals.street_parking()
				furnished_type: A2Cribs.UILayer.Rentals.furnished_type()
				pets_type: A2Cribs.UILayer.Rentals.pets_type()
				smoking: A2Cribs.UILayer.Rentals.smoking()
				tv: 1
				balcony: 1
				fridge: 1
				storage: 1
				square_feet: A2Cribs.UILayer.Rentals.square_feet()
				year_built: A2Cribs.UILayer.Rentals.year_built()
				pool: 1
				hot_tub: 1
				fitness_center: 1
				game_room: 1
				front_desk: 1
				security_system: 1
				tanning_beds: 1
				study_lounge: 1
				patio_deck: 1
				yard_space: 1
				elevator: 1
				electric: A2Cribs.UILayer.Rentals.electric()
				water: A2Cribs.UILayer.Rentals.water()
				gas: A2Cribs.UILayer.Rentals.gas()
				heat: A2Cribs.UILayer.Rentals.heat()
				sewage: A2Cribs.UILayer.Rentals.sewage()
				trash: A2Cribs.UILayer.Rentals.trash()
				cable: A2Cribs.UILayer.Rentals.cable()
				internet: A2Cribs.UILayer.Rentals.internet()
				utility_total_flat_rate: A2Cribs.UILayer.Rentals.utility_total_flat_rate()
				utility_estimate_winter: A2Cribs.UILayer.Rentals.utility_estimate_winter()
				utility_estimate_summer: A2Cribs.UILayer.Rentals.utility_estimate_summer()
				deposit: A2Cribs.UILayer.Rentals.deposit()
				highlights: A2Cribs.UILayer.Rentals.highlights()
				description: "Its a new listing!!!!!"
				waitlist: A2Cribs.UILayer.Rentals.waitlist()
				waitlist_open_date: A2Cribs.UILayer.Rentals.waitlist_open_date()
				lease_office_address: A2Cribs.UILayer.Rentals.lease_office_address()
				contact_email: A2Cribs.UILayer.Rentals.contact_email()
				contact_phone: A2Cribs.UILayer.Rentals.contact_phone()
				website: A2Cribs.UILayer.Rentals.website()
			Fee:
				A2Cribs.UILayer.Fees.GetFees()
