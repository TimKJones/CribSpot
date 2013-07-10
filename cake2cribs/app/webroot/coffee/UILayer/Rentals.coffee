class A2Cribs.UILayer.Rentals
	@rental_id: () ->
		return ""
	@listing_id: () ->
		return 2
	@street_address: () ->
		return "521 Linden St"
	@city: () ->
		return  "Ann Arbor"
	@state: () ->
		return "MI"
	@zipcode: () ->
		return "48104"
	@unit_style_options: () ->
		return 2
	@unit_style_type: () -> 
		return "NA"
	@unit_style_description: () ->
		return "NA"
	@building_name: () ->
		return ""
	@beds: () ->
		return 6
	@min_occupancy: () ->
		return null
	@max_occupancy: () ->
		return 6
	@building_type: () ->
		return 2
	@rent: () ->
		return 3600
	@rent_negotiable: () ->
		return 0
	@unit_count: () ->
		return 1
	@start_date: () ->
		return A2Cribs.UtilityFunctions.GetFormattedDate new Date("09-02-2013")
	@alternate_start_date: () ->
		return "" 
	@end_date: () ->
		return A2Cribs.UtilityFunctions.GetFormattedDate new Date("08-17-2014")
	@available: () ->
		return 1
	@baths: () ->
		return 2
	@air: () ->
		return 1
	@parking_type: () ->
		return 1
	@parking_spots: () ->
		return 6
	@street_parking: () ->
		return 0
	@furnished_type: () ->
		return 0
	@pets_type: () ->
		return 1
	@smoking: () ->
		return 1
	@square_feet: () ->
		return 2000
	@year_built: () ->
		return 1944
	@electric: () ->
		return 1
	@water: () ->
		return 1
	@gas: () ->
		return 1
	@heat: () ->
		return 1
	@sewage: () ->
		return 1
	@trash: () ->
		return 1
	@cable: () ->
		return 1
	@internet: () ->
		return 1
	@utility_total_flat_rate: () ->
		return 0
	@utility_estimate_winter: () ->
		return 250
	@utility_estimate_summer: () ->
		return 200
	@deposit: () ->
		return 900
	@highlights: () ->
		return "Its a really fun place"
	@description: () ->
		return "This is a longer description about the place"
	@waitlist: () ->
		return 1
	@waitlist_open_date: () ->
		return ""
	@lease_office_address: () ->
		return "Jonah Copi's place"
	@contact_email: () ->
		return "email@address.com"
	@contact_phone: () ->
		return "5555555555"
	@website: () ->
		return "www.cribspot.com"