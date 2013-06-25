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

	@GetFormattedDate: (date) ->
		year = date.getUTCFullYear()
		month = date.getMonth() + 1
		day = date.getDate()
		return year + '-' + month + '-' + day

	@Save: () ->
		data = 
			rental_id: 1
			listing_id: 2
			street_address: "521 Linden St"
			city: "Ann Arbor"
			state: "MI"
			zipcode: "48104"
			unit_style_options: 2
			unit_style_type: "NA"
			unit_style_description: "NA"
			building_name: ""
			beds: 6
			min_occupancy:""
			max_occupancy:6
			building_type: 2
			rent: 3600
			rent_negotiable: 0
			unit_count: 1
			start_date: @GetFormattedDate new Date("09-02-2013")
			alternate_start_date:"" 
			lease_length: 12
			available: 1
			baths: 2
			air: 1
			parking_type:1
			parking_spots: 6
			street_parking: 0
			furnished_type: 0
			pets_type:1
			smoking:1
			square_feet: 2000
			year_built: 1944
			electric: 1
			water: 1
			gas: 1
			heat: 1
			sewage: 1
			trash: 1
			cable: 1
			internet: 1
			utility_total_flat_rate: 0
			utility_estimate_winter: 250
			utility_estimate_summer: 200
			deposit: 900
			highlights: "Its a really fun place"
			description: "This is a longer description about the place"
			waitlist: 1
			waitlist_open_date: ""
			lease_office_address: "Jonah Copi's place"
			contact_email: "email@address.com"
			contact_phone: "5555555555"
			website: "www.cribspot.com"

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
