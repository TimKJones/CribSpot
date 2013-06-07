class A2Cribs.Sublet
	constructor: (@SubletId, @UniversityId, @BuildingType, @Name, @StreetAddress, @City, @State, @StartDate, @EndDate, @Bedrooms, @PricePerBedroom, @Description, @BathroomType,@UtilityCost, @DepositAmount, @AdditionalFeesDescription, @AdditionalFeesAmount, @MarkerId, @FlexibleDates,@Furnished, @DateAdded, @Air, @Parking) ->

A2Cribs.SubletObject =
	Sublet:
		id: 0
		university_id: 0
		university_name: 0
		date_begin: 0
		date_end: 0
		number_bedrooms: 0
		price_per_bedroom: 0
		payment_type_id: 0
		short_description: 0
		description: 0
		bathroom_type_id: 0
		utility_type_id: 0
		utility_cost: 0
		deposit_amount: 0
		additional_fees_description: 0
		additional_fees_amount: 0
		unit_number: 0
		flexible_dates: 0
		furnished_type_id: 0
		ac: 0
		parking: 0
	Marker:
		marker_id: 0
		alternate_name: 0
		street_address: 0
		building_type_id: 0
		city: 0
		state: 0
		zip: 0
		latitude: 0
		longitude: 0
	Housemate:
		id: 0
		quantity: 0
		enrolled: 0
		student_type_id: 0
		major: 0
		gender_type_id: 0
		year: 0