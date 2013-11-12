class SubletSave

	###
	Setup UI
	Creates the listeners and all the UI for the
	Sublet window
	###
	@SetupUI: (@div) ->
		# Set up Mini Map preview
		@MiniMap = new A2Cribs.MiniMap @div.find(".mini_map")

		$(".sublet-content").on "shown", =>
			@MiniMap.Resize()

		# Click on side bar sublet
		$('#sublet_list_content').on 'click', '.sublet_list_item', (event) =>
			@Open event.currentTarget.id

		# Save Button Click Listener
		@div.find("#sublet_save_button").click =>
			@div.find("#sublet_save_button").button 'loading'
			@Save()
			.always =>
				@div.find("#sublet_save_button").button 'reset'

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

		$("#sublet_list_content").on "marker_updated", (event, marker_id) =>
			@PopulateMarker A2Cribs.UserCache.Get "marker", marker_id

		@SetupShareButtons()

		# Popup add photo manager when clicked
		@div.find(".photo_adder").click () =>
			listing_id = @div.find(".listing_id").val()
			if listing_id?.length isnt 0
				image_array = A2Cribs.UserCache.Get("image", listing_id)?.GetObject()
			else
				image_array = @_temp_images

			A2Cribs.PhotoManager.Open image_array, @PhotoAddedCallback

	###
	Setup Share Buttons
	Links share manager functions to the share buttons
	when a sublet is posted
	###
	@SetupShareButtons: ->
		@div.find('.fb_sublet_share').click =>
			sublet = A2Cribs.UserCache.Get "sublet", @div.find(".listing_id").val()
			images = A2Cribs.UserCache.Get "image", @div.find(".listing_id").val()
			marker = A2Cribs.UserCache.Get "marker", @div.find(".marker_id").val()
			A2Cribs.ShareManager.ShareSubletOnFB(marker, sublet, images)

		@div.find('.google_sublet_share').click =>
			sublet = A2Cribs.UserCache.Get "sublet", @div.find(".listing_id").val()
			images = A2Cribs.UserCache.Get "image", @div.find(".listing_id").val()
			marker = A2Cribs.UserCache.Get "marker", @div.find(".marker_id").val()
			A2Cribs.ShareManager.ShareSubletOnFB(marker, sublet, images)

		@div.find('.twitter_sublet_share').click =>
			A2Cribs.ShareManager.ShareSubletOnTwitter(@div.find(".listing_id").val())

	###
	Photo Added
	When photos have been added, decides whether to cache if sublet
	has been saved and save in temp_images
	###
	@PhotoAddedCallback: (photos) =>
		listing_id = @div.find(".listing_id").val()
		if listing_id?.length isnt 0
			for image in photos
				image.listing_id = listing_id
			A2Cribs.UserCache.Set new A2Cribs.Image photos
			@Save()
		@_temp_images = photos


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
			if isValid and $(value).find(".active").size() is 0 
				isValid = no
				A2Cribs.UIManager.Error $(value).data("error-message")

		# Check each text-field/date-fields for a value
		@div.find(".text-field").each (index, value) ->
			if isValid and $(value).val().length is 0
				isValid = no
				A2Cribs.UIManager.Error $(value).data("error-message")

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

		# Reset all input fields
		@div.find("input").val ""

		# Reset all textarea fields
		@div.find("textarea").val ""

	###
	Open
	Opens up an existing sublet from a marker_id if marker_id
	is defined. Otherwise will start a new sublet
	###
	@Open: (marker_id = null) ->
		@div.find(".done_section").fadeOut 'fast', () =>
			@div.find(".sublet_section").fadeIn()

		if marker_id?
			# Find all cached listings with marker_id
			# Should only be users cached because it is only in the dashboard
			listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker",  marker_id

			# Set the hidden field for listing id
			@div.find(".listing_id").val listings[0].listing_id

			# Set the hidden field for marker id
			@div.find(".marker_id").val marker_id

			# Fetch the sublet object from the cache
			A2Cribs.UserCache.GetListing("sublet", listings[0].listing_id)
			.done (sublet) =>
				# Populate the marker fields
				@PopulateMarker A2Cribs.UserCache.Get "marker", marker_id
				# Populate based on the retrieved sublet
				@Populate sublet
		else
			# Clear out the sublet_window if no marker defined
			@Reset()
	
			# Reset mini map
			@MiniMap.Reset()

			# Slide up more info
			@div.find(".more_info").slideUp()

			# Add search box to get started
			@div.find(".marker_card").fadeOut 'fast', () =>
				@div.find(".marker_searchbox").fadeIn()

		# Direct the dashboard to show sublets
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

		@div.find(".marker_id").val marker.GetId()

		# Populate Marker Card
		# Fill in the values of the marker fields
		@MiniMap.SetMarkerPosition new google.maps.LatLng(marker.latitude, marker.longitude)
		@div.find(".building_name").text marker.GetName()
		@div.find(".building_type").text marker.GetBuildingType()
		@div.find(".full_address").html "<i class='icon-map-marker'></i> #{marker.street_address}, #{marker.city}, #{marker.state}"

		@div.find(".marker_searchbox").fadeOut 'fast', () =>
			@div.find(".marker_card").fadeIn()
			@div.find(".more_info").slideDown()



	###
	Populate
	Populates the sublet fields in the dom
	###
	@Populate: (sublet_object) ->
		# Reset the sublet form first
		@Reset()
		
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
			sublet_object = @GetSubletObject()
			return $.ajax
				url: myBaseUrl + "listings/Save/"
				type: "POST"
				data: sublet_object
				success: (response) =>
					response = JSON.parse response
					if response.error?.message?
						A2Cribs.UIManager.Error response.error.message
					else
						# Check to see if it is a new listing
						# Trigger an event to notify the dashboard
						if not sublet_object.Listing.listing_id?
							$('#sublet_list_content').trigger "marker_added", [sublet_object.Listing.marker_id]
						
						@div.find(".sublet_section").fadeOut 'slow', () =>
							@div.find(".done_section").fadeIn()

						A2Cribs.UserCache.CacheData response.listing
						@div.find(".listing_id").val response.listing.Listing.listing_id
						A2Cribs.UIManager.Success "Your listing has been saved!"
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

		listing_id = if @div.find(".listing_id").val().length isnt 0 then @div.find(".listing_id").val()
		sublet_object.listing_id = listing_id

		# Return the object that is sent to backend
		return {
			# listing_type is 1 for Sublets
			'Listing': {
				listing_type: 1
				marker_id: @div.find(".marker_id").val()
				listing_id: listing_id
			}
			'Sublet': sublet_object
			'Image': @_temp_images
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
			@FindMarkerByAddress(street_address, city, state)
			.done (marker) =>
				@PopulateMarker(marker)
			.fail =>
				A2Cribs.MarkerModal.OpenLocation('sublet', street_address, city, state)
		.fail =>
			A2Cribs.MarkerModal.OpenLocation('sublet', location_object.street_address, location_object.city, location_object.state)

	@FindMarkerByAddress: (street_address, city, state) ->
		deferred = new $.Deferred()
		$.ajax
			url: myBaseUrl + "Markers/FindMarkerByAddress/"+street_address+"/"+city+"/"+state
			type: "GET"
			success: (response) =>
				response = JSON.parse response
				if response?
					marker = new A2Cribs.Marker(response)
					A2Cribs.UserCache.Set marker
					return deferred.resolve marker
				else
					return deferred.reject()

		return deferred.promise()


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
		date_array = dateString.split " " # Remove the 00:00:00 if necessary
		date_array = date_array[0].split "-" # Split the date
		return "#{date_array[1]}/#{date_array[2]}/#{date_array[0]}"

	$("#sublet_window").ready =>
		@_temp_images = []
		@SetupUI($("#sublet_window"))