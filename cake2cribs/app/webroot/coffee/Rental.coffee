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

	@Template =
		Rental:
			rental_id: 0
			listing_id: 0
			street_address: 0
			city: 0
			state: 0
			zipcode: 0
			unit_style_options: 0
			unit_style_type: 0
			unit_style_description: 0
			building_name: 0
			beds: 0
			min_occupancy: 0
			max_occupancy: 0
			building_type: 0
			rent: 0
			rent_negotiable: 0
			unit_count: 0
			start_date: 0
			alternate_start_date: 0
			lease_length: 0
			available: 0
			baths: 0
			air: 0
			parking_type: 0
			parking_spots: 0
			street_parking: 0
			furnished_type: 0
			pets_type: 0
			smoking: 0
			square_feet: 0
			year_built: 0
			electric: 0
			water: 0
			gas: 0
			heat: 0
			sewage: 0
			trash: 0
			cable: 0
			internet: 0
			utility_total_flat_rate: 0
			utility_estimate_winter: 0
			utility_estimate_summer: 0
			deposit: 0
			highlights: 0
			description: 0
			waitlist: 0
			waitlist_open_date: 0
			lease_office_address: 0
			contact_email: 0
			contact_phone: 0
			website: 0
		Fees:
			0
