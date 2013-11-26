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
	Toggle Collapse
	Collapses all or expands all rental divs
	###
	@ToggleCollapse: ->
		# Show the loader
		A2Cribs.UIManager.ShowLoader()
		@BackgroundLoadRentals()
		.done =>
			lol = "lol"
			# Open all of them
		.always =>
			# Removed the loader
			A2Cribs.UIManager.HideLoader()

	###
	Toggle Show Listings
	Collapses all for that individual listing
	###
	@ToggleShowListings: ->
		# Show the loader
		A2Cribs.UIManager.ShowLoader()
		# Check if all rentals are loaded
		if @BackgroundLoadRentals().state() is "resolved"
			# Open that rental
			lol = "lol"
		# Otherwise request the listings with that marker
		else
			lol = "lol"
		# Hide the loader
		A2Cribs.UIManager.HideLoader()


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
		if @LoadRentalsDeferred?
			return @LoadRentalsDeferred
		url = myBaseUrl + "Listings/GetListing"
		$.ajax 
			url: url
			type:"GET"
			success: (data) =>
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
			<div class='rental_preview' data-marker-id='#{marker.GetId()}'>
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
				<div class='unit_list'>
				</div>
				<div class='rental_expand_toggle'>
					<div class='show_listings'>
						<span><i class='icon-chevron-sign-down'></i> Click to view</span>
						<span class='unit_count'>#{listings.length}</span>
						<span> Listings</span>
					</div>
					<div class='hide_listings hide'>
						<span><i class='icon-chevron-sign-up'></i> Hide these Listings</span>
					</div>
				</div>
			</div>
			"""

		@div.find("#rental_preview_list").append $(marker_row)

	###
	Add Rental
	Adds rental to the rental preview div
	###
	@AddRental: (listing, container) ->
		rental = A2Cribs.UserCache.Get "rental", listing.GetId()
		listing_row = """
			<div class="rental_edit">
				<span class="unit_description pull-left">#{rental.GetUnitStyle()} #{rental.unit_style_description} - #{rental.beds}Br</span>
				<div class="btn-group pull-left" data-toggle="buttons-radio">
					<button type="button" class="btn btn-available">Available</button>
					<button type="button" class="btn btn-leased">Leased</button>
				</div>
				<input type="text" class="rent" placeholder="Rent" value="#{rental.rent}">
				<input type="text" class="start_date" placeholder="Lease Start Date" value="#{rental.start_date}">
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
