###
Quick Rental

Class for quick change of rentals.
Makes it easy to toggle availablity, pick start dates,
set rent price
###

class A2Cribs.QuickRental

	###
	Filter
	Filters out the quick rentals based
	on the search bar
	###
	@Filter: ->

	###
	Create Listeners
	Creates and fires save events for that rental/
	listing
	###
	@CreateListeners: ->

		@div.on 'click', ".btn-group .btn", (event) =>
			# Check if the listing available value was changed
			if $(event.currentTarget).parent().data('value') isnt $(event.currentTarget).data('value')
				# Set the parent div
				$(event.currentTarget).parent().data('value', $(event.currentTarget).data('value'))
				# Trigger the save rental event
				$(event.currentTarget).closest(".rental_edit").trigger "save_rental", [$(event.currentTarget).parent()]

		@div.on 'save_rental', '.rental_edit', (event, input) =>
			# Get the listing id from the element 
			listing_id = $(event.currentTarget).data("listing-id")
			# Retrieve object from the cache
			a2_object = A2Cribs.Get input.data("object"), listing_id
			# Updates the cached object
			a2_object[input.data("field")] = input.data("value")
			@Save listing_id


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
		$.ajax
			url: myBaseUrl + "listings/Save/"
			type: "POST"
			data: listing_object
			success: (response) =>
				console.log response

	###
	Toggle Collapse
	Collapses all or expands all rental divs
	###
	@ToggleCollapse: ->
		# Show the loader
		A2Cribs.UIManager.ShowLoader()
		@BackgroundLoadRentals()
		.done =>
			# Check if they are all closed
			if no # TODO: KEEP IT AS FALSE FOR NOW
				# Slide all the unit_lists up
				@div.find(".unit_list").slideUp()
				# Hide the text at the bottom and show the text to show listings
				@div.find(".toggle_text").hide()
				@div.find(".show_listings").show()
			else
				# Slide all the unit_lists down
				@div.find(".unit_list").slideDown()
				# Hide the text at the bottom and show the text to hide listings
				@div.find(".toggle_text").hide()
				@div.find(".hide_listings").show()

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

		# Check if all rentals are loaded
		if @BackgroundLoadRentals().state() is "resolved"
			# Resolve with the parent div
			deferred.resolve $(event.currentTarget).parent()
		# Otherwise request the listings with that marker
		else
			# Load listing from backend
			### GETTING CLOSER 
			marker_id = $(event.currentTarget).parent().data("marker-id")
			url = "#{myBaseUrl}Listings/GetOwnedListingsByMarkerId/#{marker_id}" 
			$.ajax 
				url: url
				type:"GET"
				success: (data) =>
					# Load all of the data into the user cache
					A2Cribs.UserCache.CacheData JSON.parse data
					listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker", marker_id
					for listing in listings
						@AddRental listing, $(event.currentTarget).parent()
					deferred.resolve $(event.currentTarget).parent()
				error: =>
					deferred.reject()
			###

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
			listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker", marker_id
			for listing in listings
				@AddRental listing, $(value)

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
				
		@LoadRentalsDeferred = new $.Deferred()
		return @LoadRentalsDeferred.promise()

	###
	Add Marker
	Adds marker to the quick rentals div
	###
	@AddMarker: (marker) ->
		listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker", marker.GetId()
		marker_row = """
			<div class='rental_preview' data-marker-id='#{marker.GetId()}' data-visible-state="hidden">
				<div class='rental_title'>
					<span>
						<div class='marker_box pull-left'><i class='icon-map-marker'></i></div>&nbsp;
						<span class='building_name'>#{marker.GetName()}</span>
					</span>
					<span class='separator'>|</span>
					<span class='street_address'>#{marker.street_address}</span>
					<span class='separator'>|</span>
					<span class='building_type'>#{marker.GetBuildingType()}</span>
				</div>
				<div class='unit_list hide'>
				</div>
				<div class='rental_expand_toggle'>
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
		@div.find("#rental_preview_list").append marker_row_div

	###
	Add Rental
	Adds rental to the rental preview div
	###
	@AddRental: (listing, container) ->
		rental = A2Cribs.UserCache.Get "rental", listing.GetId()
		listing_row = """
			<div class="rental_edit" data-listing-id="#{listing.GetId()}">
				<span class="unit_description pull-left">#{rental.GetUnitStyle()} #{rental.unit_style_description} - #{rental.beds}Br</span>
				<div class="btn-group pull-left" data-toggle="buttons-radio" data-object="listing" data-value="#{if listing.available then "1" else "0"}">
					<button type="button" class="btn btn-available #{if listing.available then "active" else ""}" data-value="1">Available</button>
					<button type="button" class="btn btn-leased #{if not listing.available then "active" else ""}" data-value="0">Leased</button>
				</div>
				<input type="text" class="rent" placeholder="Rent" value="#{rental.rent}" data-object="rental">
				<input type="text" class="start_date" placeholder="Lease Start Date" value="#{rental.start_date}" data-object="rental">
				<span class="not-saved save-note hide"><i class='icon-spinner icon-spin'></i> Saving...</span>
				<span class="saved save-note hide"><i class='icon-ok-sign'></i> Saved</span>
				<button class="edit_rental pull-right btn btn-primary">Edit</button>
			</div>
			"""
		container.find(".unit_list").append $(listing_row)

	###
	On Ready
	###
	$(document).ready =>
		if $("#rental_quickedit").length
			@div = $("#rental_quickedit")
			@_markers_loaded = new $.Deferred()
			@_markers_loaded.promise()
			@BackgroundLoadRentals()
			A2Cribs.Dashboard.GetUserMarkerData()
			.done =>
				@LoadAllMarkers()
				@_markers_loaded.resolve()
			$.when(@_markers_loaded, @BackgroundLoadRentals())
			.done =>
				@LoadAllRentals()
