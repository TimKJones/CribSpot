###
Quick Rental

Class for quick change of rentals.
Makes it easy to toggle availablity, pick start dates,
set rent price
###

class QuickRental

	###
	Filter
	Filters out the quick rentals based
	on the search bar
	###
	@Filter: (event) =>
		@div.find(".rental_preview").each (index, value) ->
			# Find if searched by building name
			if $(value).find(".building_name").text().toLowerCase().indexOf($(event.currentTarget).val().toLowerCase()) isnt -1
				if not $(value).is(":visible")
					$(value).fadeIn()
				return
			# Find text in street address
			if $(value).find(".street_address").text().toLowerCase().indexOf($(event.currentTarget).val().toLowerCase()) isnt -1
				if not $(value).is(":visible")
					$(value).fadeIn()
				return
			# Hide if no string match
			$(value).fadeOut()
			return
	###
	Sort Availability
	Sorts the listings by availability 
	###
	@SortAvailability: (show_available) ->
		@div.find(".rental_preview").each (index, value) ->
			# Show all if show_available is null
			if not show_available?
				$(value).fadeIn()
			else if $(value).find(".available_listing_count").hasClass("leased")
				if show_available then $(value).fadeOut() else $(value).fadeIn()
			else
				if show_available then $(value).fadeIn() else $(value).fadeOut()

	###
	Format Rent
	Private method to update the rent value and
	format the rent correctly and cleanly
	###
	format_rent = (rent_div) ->
		rent_amount = parseInt(rent_div.val()?.replace(/\D/g, ''), 10)
		if isNaN rent_amount
			rent_amount = 0
		rent_amount = rent_amount.toString()
		rent_div.data "value", rent_amount
		j = if (j = rent_amount.length) > 3 then j % 3 else 0
		rent_string = "$" + if j then rent_amount.substr(0, j) + "," else ""
		rent_string += rent_amount.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + ",")
		rent_div.val if rent_amount isnt "0" and rent_amount.length isnt 0 then rent_string else ""

	###
	Validate Date
	Private method to update the date value and
	validate
	###
	validate_date = (date_div) ->
		# Highlight the input as red to start
		date_div.addClass "error"
		# Get the date string
		date = date_div.val()
		date_split = date.split("-")
		# Check if there is mm-dd-yyyy
		if date_split.length isnt 3
			# Show the error condition
			return false
		# Check to make sure they are all numbers
		for date_val in date_split
			if isNaN(date_val)
				# Show the error condition
				return false
		# Check month
		if date_split[0] < 1 or date_split[0] > 12
			# Show the error condition
			return false
		# Check date
		if date_split[1] < 1 or date_split[1] > 31
			return false
		# Check year
		if date_split[2] < 2000
			return false

		if date_split[0].length is 1
			date_split[0] = "0#{date_split[0]}"
		if date_split[1].length is 1
			date_split[1] = "0#{date_split[1]}"

		date_div.data("value", "#{date_split[2]}-#{date_split[0]}-#{date_split[1]}")
		date_div.removeClass "error"
		return true

	###
	Check Marker Availabilty
	Takes a rental_preview div and finds the availablity
	of each listing attached to the marker and updates
	the UI to show the count
	###
	@CheckMarkerAvailabilty: (rental_preview) ->
		# Find all listings associated with this marker_id
		listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker",
			rental_preview.data("marker-id")
		available_count = 0
		for listing in listings
			if listing.available
				available_count++
		if available_count is 0
			rental_preview.find(".available_listing_count").text("Leased").addClass "leased"
		else
			rental_preview.find(".available_listing_count").text("#{available_count} of #{listings.length} Available").removeClass "leased"

	###
	Create Listeners
	Creates and fires save events for that rental/
	listing
	###
	@CreateListeners: ->
		@rent_timeouts = {}

		@div.on 'click', ".btn-group .btn", (event) =>
			# Check if the listing available value was changed
			if $(event.currentTarget).parent().data('value') isnt $(event.currentTarget).data('value')
				# Set the parent div
				$(event.currentTarget).parent().data('value', $(event.currentTarget).data('value'))
				# Trigger the save rental event
				$(event.currentTarget).closest(".rental_edit").trigger "save_rental", [$(event.currentTarget).parent()]
				
				# Check availablity of all listings for the marker
				@CheckMarkerAvailabilty $(event.currentTarget).closest(".rental_preview")

		@div.on 'keyup', ".rent", (event) =>
			# Format rent
			format_rent $(event.currentTarget)
			listing_id = $(event.currentTarget).parent().data("listing-id")
			clearTimeout @rent_timeouts[listing_id]
			$(event.currentTarget).parent().find(".save-note").hide()
			$(event.currentTarget).parent().find(".not-saved").show()
			@rent_timeouts[listing_id] = setTimeout () =>
				$(event.currentTarget).closest(".rental_edit").trigger "save_rental", [$(event.currentTarget)]
			, 1000
			# Set timeout for one second to minimize saves
		
		@div.on 'keyup', ".start_date", (event) =>
			date = $(event.currentTarget).data("value")
			# Checks if valid and updates value
			if validate_date $(event.currentTarget)
				# If the date has been changed
				if date isnt $(event.currentTarget).data("value")
					$(event.currentTarget).closest(".rental_edit").trigger "save_rental", [$(event.currentTarget)]

		@div.on 'save_rental', '.rental_edit', (event, input) =>
			# Get the listing id from the element 
			listing_id = $(event.currentTarget).data("listing-id")
			# Retrieve object from the cache
			a2_object = A2Cribs.UserCache.Get input.data("object"), listing_id
			# Updates the cached object
			a2_object[input.data("field")] = input.data("value")
			$(event.currentTarget).find(".save-note").hide()
			$(event.currentTarget).find(".not-saved").show()
			@Save(listing_id)
			.always () =>
				$(event.currentTarget).find(".save-note").hide()
				$(event.currentTarget).find(".saved").show()

		# Key up on the filter text enter
		@div.on 'keyup', '.search_rentals', @Filter

		# Fixed scroll to top when clicking on the label
		# explained link
		@div.on 'click', '.label_explained', (event) ->
			event.preventDefault()
			event.stopPropagation()
			return false

		# Opens the photopicker with the images	
		@div.on 'click', '.open_photos', (event) =>
			listing_id = $(event.currentTarget).parent().data("listing-id")
			image_array = A2Cribs.UserCache.Get("image", listing_id)?.GetObject()
			A2Cribs.PhotoPicker.Open(image_array)
			.done (photos) =>
				for image in photos
					image.listing_id = listing_id
				A2Cribs.UserCache.Set new A2Cribs.Image photos, listing_id
				$(event.currentTarget).parent().find(".save-note").hide()
				$(event.currentTarget).parent().find(".not-saved").show()
				# Save the photos
				@Save(listing_id)
				.done ->
					$(event.currentTarget).parent().find(".save-note").hide()
					$(event.currentTarget).parent().find(".saved").show()

		# Listener for the link on the Toggle show all listings
		@div.find(".toggle_all_listings").click @ToggleCollapse

		# Change listener for availability toggle
		@div.find("#sort_availablity").change (event) =>
			value = parseInt $(event.currentTarget).val(), 10
			if isNaN value
				value = null
			@SortAvailability value

	###
	Save
	Sends a listing to the backend to be saved
	Depends on many different deferreds. Will
	reject a deferred if it is trying to be resaved
	before the save is completed
	###
	@Save: (listing_id) ->
		listing = A2Cribs.UserCache.Get "listing", listing_id
		listing_object = listing.GetConnectedObject()
		return $.ajax
			url: myBaseUrl + "listings/Save/"
			type: "POST"
			data: listing_object
			success: (response) =>
				console.log response

	###
	Toggle Collapse
	Collapses all or expands all rental divs
	###
	@ToggleCollapse: =>
		# Show the loader
		A2Cribs.UIManager.ShowLoader()
		$.when(@BackgroundLoadRentals())
		.done =>
			# Check if they are all closed or all open
			if @div.find(".unit_list:visible").length is @div.find(".rental_preview").length
				# Slide all the unit_lists up
				@div.find(".unit_list").slideUp()
				# Hide the text at the bottom and show the text to show listings
				@div.find(".toggle_text").hide()
				@div.find(".show_listings").show()
				# All listings are closed change toggle text
				@div.find(".toggle_all_listings").text "Open all listings"
			else
				# Slide all the unit_lists down
				@div.find(".unit_list").slideDown()
				# Hide the text at the bottom and show the text to hide listings
				@div.find(".toggle_text").hide()
				@div.find(".hide_listings").show()
				# All listings are shown change toggle text
				@div.find(".toggle_all_listings").text "Collapse all listings"

			# Open all of them
		.always =>
			# Removed the loader
			A2Cribs.UIManager.HideLoader()

	###
	Toggle Show Listings
	Collapses all for that individual listing
	###
	@ToggleShowListings: (event) =>
		# If the listings are currently shown
		# Hide them and exit the function
		if $(event.currentTarget).parent().find(".unit_list").is(":visible")
			# Slide up the unit list
			$(event.currentTarget).parent().find(".unit_list").slideUp()
			# Hide the text at the bottom and show the text to show listings
			$(event.currentTarget).parent().find(".toggle_text").hide()
			$(event.currentTarget).parent().find(".show_listings").show()
			# Reattach listener to the toggle
			$(event.currentTarget).one('click', @ToggleShowListings)
			# Change text of toggle to open all listings
			@div.find(".toggle_all_listings").text "Open all listings"
			# exit the function
			return

		# Show the loader
		A2Cribs.UIManager.ShowLoader()
		# Start deferred object
		deferred = $.Deferred()
		deferred.done (element) =>
			# Hide the loader
			A2Cribs.UIManager.HideLoader()
			# Slide down the unit list
			element.find(".unit_list").slideDown()
			# Hide the text at the bottom and show the text to show listings
			$(event.currentTarget).parent().find(".toggle_text").hide()
			$(event.currentTarget).parent().find(".hide_listings").show()
			# Reattach listener to the toggle
			element.find(".rental_expand_toggle").one('click', @ToggleShowListings)
			# Check if all listings are open
			if @div.find(".unit_list:visible").length is @div.find(".rental_preview").length
				# Change text to collapse all listings for toggle listings
				@div.find(".toggle_all_listings").text "Collapse all listings"

		# Check if all rentals are loaded
		if @BackgroundLoadRentals().state() is "resolved"
			# Resolve with the parent div
			deferred.resolve $(event.currentTarget).parent()
		# Otherwise request the listings with that marker
		else
			# Load listing from backend
			# GETTING CLOSER 
			marker_id = $(event.currentTarget).parent().data("marker-id")
			url = "#{myBaseUrl}Listings/GetOwnedListingsByMarkerId/#{marker_id}"
			$.ajax
				url: url
				type:"GET"
				success: (data) =>
					# Load all of the data into the user cache
					A2Cribs.UserCache.CacheData JSON.parse data
					A2Cribs.UserCache.Get("marker", marker_id)?.listings_loaded.resolve(marker_id, $(event.currentTarget).parent())
					deferred.resolve $(event.currentTarget).parent()

	###
	Load All Markers
	Loads up all the marker owned by the property
	manager into the quick rental view
	###
	@LoadAllMarkers: ->
		# Get all the markers from the cache
		# The loaded markers in the cache (in the dashboard)
		# are only the ones owned by the user
		markers = A2Cribs.UserCache.Get "marker"
		for marker in markers
			@AddMarker marker

	###
	Load All Rentals
	Creates the UI for all the rentals in the
	quick rental view by looping through all
	the marker objects in the quick rental
	view
	###
	@LoadAllRentals: ->
		@div.find(".rental_preview").each (index, value) =>
			marker_id = $(value).data "marker-id"
			A2Cribs.UserCache.Get("marker", marker_id)?.listings_loaded.resolve(marker_id, value)
			
	###
	Background Load Rentals
	Loads all the rentals in the background to appear
	to property manager that the data is ready to 
	use
	###
	@BackgroundLoadRentals: ->
		# Link to the deferred if the method
		# has already been called
		if @LoadRentalsDeferred?
			return @LoadRentalsDeferred

		@LoadRentalsDeferred = $.Deferred()

		# Get listing with no listing id gets all
		# listings owned by the property manager
		url = myBaseUrl + "Listings/GetListing"
		$.ajax
			url: url
			type:"GET"
			success: (data) =>
				# Load all of the data into the user cache
				A2Cribs.UserCache.CacheData JSON.parse data
				@LoadRentalsDeferred.resolve()
			error: =>
				@LoadRentalsDeferred.reject()
				
		return @LoadRentalsDeferred.promise()

	###
	Add Marker
	Adds marker to the quick rentals div
	###
	@AddMarker: (marker) ->
		listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker", marker.GetId()
		marker_row = """
			<div class='rental_preview' data-marker-id='#{marker.GetId()}' data-visible-state="hidden">
				<div class='rental_title rental_expand_toggle'>
					<span>
						<span class='building_name'>#{marker.GetName()}</span>
					</span>
					<span class='separator'>|</span>
					<span class='street_address'>#{marker.street_address}</span>
					<span class='separator'>|</span>
					<span class='building_type'>#{marker.GetBuildingType()}</span>
					<span class='pull-right available_listing_count'></span>
				</div>
				<div class='unit_list hide'>
					<div class='fields_label'>
						<div class='pull-left text-center listing_label'>Listing</div>
						<div class='pull-left text-center available_label'>Availablity</div>
						<div class='pull-left text-center rent_label'>Rent</div>
						<div class='pull-left text-center start_date_label'>Start Date</div>
						<a href="#" class='pull-right label_explained' data-toggle='popover' data-content="We have simplified things a bit. If you would like to update a field that is not listed below, please click on the rentals or sublet tab on the left.">Where's the rest? <i class='icon-info-sign'></i></a>
					</div>
				</div>
				<div class='rental_expand_toggle rental_expand_toggle_div'>
					<div class='show_listings toggle_text'>
						<span><i class='icon-chevron-sign-down'></i> Click to view</span>
						<span class='unit_count'>#{listings.length}</span>
						<span> Listings</span>
					</div>
					<div class='hide_listings hide toggle_text'>
						<span><i class='icon-chevron-sign-up'></i> Hide these Listings</span>
					</div>
				</div>
			</div>
			"""
		marker_row_div = $(marker_row)
		marker_row_div.find(".rental_expand_toggle").one 'click', @ToggleShowListings
		marker_row_div.find(".label_explained").popover()
		@div.find("#rental_preview_list").append marker_row_div
		# Set deferred callback for when listings are loaded
		# for the marker
		marker.listings_loaded = $.Deferred()
		marker.listings_loaded.promise()
		marker.listings_loaded.done (marker_id, value) =>
			listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker", marker_id
			for listing in listings
				@AddRental listing, $(value)
			@CheckMarkerAvailabilty marker_row_div

	###
	Add Rental
	Adds rental to the rental preview div
	###
	@AddRental: (listing, container) ->
		listing_type = listing.GetListingType()
		rental = A2Cribs.UserCache.Get listing_type.toLowerCase(), listing.GetId()
		if rental?
			date_split = rental.start_date?.split("-")
			date_string = if date_split?.length is 3 then "#{date_split[1]}-#{date_split[2]}-#{date_split[0]}" else ""
			if rental.GetUnitStyle?
				unit_description = "#{rental.GetUnitStyle()} #{rental.unit_style_description} - #{rental.beds}Br"
			else
				unit_description = "#{rental.beds}Br - #{rental.baths}Bath"
			listing_row = """
				<div class="rental_edit" data-listing-id="#{listing.GetId()}">
					<a href="/listing/#{listing.GetId()}" target="_blank" class="unit_description pull-left">#{unit_description}</a>
					<div class="btn-group pull-left" data-toggle="buttons-radio" data-object="listing" data-field="available" data-value="#{if listing.available then "1" else "0"}">
						<button type="button" class="btn btn-available #{if listing.available then "active" else ""}" data-value="1">Available</button>
						<button type="button" class="btn btn-leased #{if not listing.available then "active" else ""}" data-value="0">Leased</button>
					</div>
					<input type="text" class="rent pull-left" placeholder="Rent" data-object="#{rental.class_name}" data-field="rent" data-value="#{rental.rent}" value="#{rental.rent}">
					<input type="text" class="start_date pull-left" maxlength="10" value="#{date_string}" data-object="#{rental.class_name}" data-field="start_date" data-value="#{rental.start_date}" placeholder="MM-DD-YYYY">
					<button type="button pull-left" class="open_photos btn btn-primary">Add Photos</button>
					<span class="not-saved save-note hide"><i class='icon-spinner icon-spin'></i> Saving...</span>
					<span class="saved save-note hide"><i class='icon-ok-sign'></i> Saved</span>
				</div>
				"""
			div = $(listing_row)
			# Format the rent correctly
			format_rent div.find(".rent")
			# Append the newly created div the marker div
			container.find(".unit_list").append div

	###
	On Ready
	###
	$(document).ready =>
		if $("#rental_quickedit").length
			@div = $("#rental_quickedit")
			@_markers_loaded = $.Deferred()
			@_markers_loaded.promise()
			@BackgroundLoadRentals()
			A2Cribs.Dashboard.GetUserMarkerData()
			.done =>
				@LoadAllMarkers()
				@_markers_loaded.resolve()
			$.when(@_markers_loaded, @BackgroundLoadRentals())
			.done =>
				@LoadAllRentals()
			@CreateListeners()
