class SubletSave

	###
	Setup UI
	Creates the listeners and all the UI for the
	Sublet window
	###
	@SetupUI: (@div) ->
		# Open sublet on marker added event
		$('#sublet_list_content').on "marker_added", (event, marker_id) =>
			@Open marker_id

		# Click on side bar sublet
		$('#sublet_list_content').on 'click', '.sublet_list_item', (event) =>
			@Open event.currentTarget.id

		# Save Button Click Listener
		@div.find("#sublet_save_button").click =>
			@Save()

		# Listener for button groups
		# Sets value depending on btn that is active
		@div.find(".btn-group.sublet_fields .btn").click (event) =>
			$(event.currentTarget).parent().val($(event.currentTarget).val())

		@div.find("#find_address").click =>
			@FindAddress()

		# Date Picker Init
		@div.find('.date-field').datepicker()

		# Create new sublet button clicked
		$(".create-listing").find("a").click (event) =>
			listing_type = $(event.currentTarget).data "listing-type"
			if listing_type is "sublet"
				@Open()

	###
	Validate
	Called before advancing steps
	Returns true if validations pass; false otherwise
	###
	@Validate: ->
		# Starts as valid
		isValid = yes

		# Check each btn-group for a value
		@div.find(".btn-group").each (index, value) ->
			if $(value).find(".active").size() is 0 then isValid = no

		# Check each date-field for a value
		@div.find(".date-field").each (index, value) ->
			if $(value).val().length is 0 then isValid = no
		return isValid

	###
	Reset
	Erases all the fields and resets
	the Sublet window and sublet object
	###
	@Reset: ->

		# Unpress each btn-group
		@div.find(".btn-group").each (index, value) ->
			$(value).find(".active").removeClass "active"

	###
	Open
	Opens up an existing sublet from a marker_id
	###
	@Open: (marker_id = null) ->
		if marker_id?
			listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker",  marker_id
			A2Cribs.UserCache.GetListing("sublet", listings[0].listing_id)
			.done (sublet) =>
				@PopulateMarker A2Cribs.UserCache.Get "marker", marker_id
				@Populate sublet
		else
			@Reset()
		A2Cribs.Dashboard.Direct 
			"classname": "sublet"
			"data": {}

	###
	Populate Marker
	Populates the fields based on the marker
	###
	@PopulateMarker: (marker) ->
		# Loop through all the location fields
		# These fields are used to find address
		$(".location_fields").each (index, value) =>

			# Set value equal to marker attribute
			input_val = marker[$(value).data("field-name")]
			if typeof marker[$(value).data("field-name")] is "boolean"
				input_val = +input_val
			$(value).val input_val

		# Populate Marker Card
		# Fill in the values of the marker fields
		# TODO: Make marker card



	###
	Populate
	Populates the sublet fields in the dom
	###
	@Populate: (sublet_object) ->
		# Get all fields from dom
		# Loop through them and populate
		$(".sublet_fields").each (index, value) =>

			# Set value equal to sublet_object attribute
			input_val = sublet_object[$(value).data("field-name")]
			if typeof sublet_object[$(value).data("field-name")] is "boolean"
				input_val = +input_val
			$(value).val input_val

			# If the sublet_field is a btn-group
			if $(value).hasClass "btn-group"
				$(value).find("button[value='#{input_val}']")
				.addClass "active"

			# Format to more readable date
			else if $(value).hasClass "date-field"
				$(value).val @GetFormattedDate sublet_object[$(value).data("field-name")]

	###
	Save
	Submits sublet to backend to save
	Assumes all front-end validations have been passed.
	###
	@Save: ->
		if @Validate()
			return $.ajax
				url: myBaseUrl + "listings/Save/"
				type: "POST"
				data: @GetSubletObject()
				success: (response) =>
					console.log response
		else
			return new $.Deferred().reject()

	###
	GetSubletObject
	Returns an object containing all sublet data from all 4 steps.
	###
	@GetSubletObject: ->
		sublet_object = {}

		# Loop through each sublet field
		@div.find(".sublet_fields").each (index, value) =>

			# Find the value associated with each field
			field_value = $(value).val()

			# If the field is a date format for backend
			if $(value).hasClass "date-field"
				field_value = @GetBackendDateFormat field_value

			# Add the field to the sublet_object
			sublet_object[$(value).data("field-name")] = field_value

		# Return the object that is sent to backend
		return {
			# TODO: NEED TO CHECK IF LISTING ALREADY EXISTS
			# listing_type is 1 for Sublets
			'Listing': {
				listing_type: 1
				marker_id: @div.find(".marker_id").val()
			}
			'Sublet': sublet_object
			# TODO: NEED TO UPLOAD IMAGE ARRAY
			'Image': [] 
		}

	###
	Find Address
	Finds the geocode address and searches the backend
	for the correct address
	###
	@FindAddress: ->
		location_object = {}
		isValid = yes
		$(".location_fields").each (index, value) =>

			# Checks if the field is completed
			if $(value).val().length is 0 then isValid = no

			# Set location object attribute to the value of the div
			location_object[$(value).data("field-name")] = $(value).val()

		# Error message displayed if not validated and returns
		if not isValid
			A2Cribs.UIManager.Error "Please complete all fields to find address"
			return

		# Find the formatted address from google geocoder
		A2Cribs.Geocoder.FindAddress(location_object.street_address, location_object.city, location_object.state)
		.done (response) =>
			[street_address, city, state, zip, location] = response

			# TODO: NEED TO WRITE METHOD ON BACKEND TO RETURN MARKER ID
			@div.find(".marker_id").val "1"

	@FindMarkerTest:() ->
		street_address = '114 N Division St'
		city = 'Ann Arbor'
		state = 'MI'
		return $.ajax
			url: myBaseUrl + "Markers/FindMarkerByAddress/"+street_address+"/"+city+"/"+state
			type: "GET"
			success: (response) =>
				console.log response


	###
	Get Backend Date Format
	Replaces '/' with '-' to make convertible to db format
	###
	@GetBackendDateFormat: (dateString) ->
		date = new Date(dateString)
		month = date.getMonth() + 1
		if month < 10
			month = "0" + month
		day = date.getDate()
		if day < 10
			day = "0" + day
		year = date.getUTCFullYear()
		beginDateFormatted = year + "-" + month + "-" + day

	###
	Get Formatted Date
	Returns date in readable front-end syntax
	###
	@GetFormattedDate: (dateString) ->
		date_array = dateString.split "-"
		return "#{date_array[1]}/#{date_array[2]}/#{date_array[0]}"

	$("#sublet_window").ready =>
		@SetupUI($("#sublet_window"))