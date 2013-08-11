class A2Cribs.RentalFilter extends A2Cribs.FilterManager
	@Beds = ''
	@Rent = ''
	@Months = ''

	###
	Private method for loading the contents of the filter preview into the header filter
	###
	loadPreviewText = (div, text) ->
			title = $(div).closest(".filter_content").attr("data-link")
			$(title).find(".filter_preview").html text

	@CreateListeners: ->
		###
		On Change listeners for applying changed fields
		###
		###
		Bed filter click event listener
		Finds range of beds and applies the changes of bed amounts
		###
		@div.find("#bed_filter").find(".btn").click (event) =>
			selected_list = []
			min = 1000 # A large number
			max = -1 # A small number
			$(event.delegateTarget).toggleClass "active"
			button_group = $(event.delegateTarget).parent()

			# Creates selected_list of all the bedrooms
			button_group.find(".btn.active").each () ->
				val = parseInt $(this).val(), 10
				selected_list.push val
				min = Math.min min, val
				max = Math.max max, val

			# If nothing is selected then set text to blank
			if selected_list.length is 0
				loadPreviewText event.delegateTarget, ""
			# Check to see amount of beds or to make a range of beds
			else if selected_list.length is 1
				if min is 0
					text = "<div class='filter_data'>Studio</div>"
				else if min is 1
					text = "<div class='filter_data'>#{min}</div><div class='filter_label'>&nbsp;bed</div>"
				else
					text = "<div class='filter_data'>#{min}</div><div class='filter_label'>&nbsp;beds</div>"
				loadPreviewText event.delegateTarget, text
			else
				loadPreviewText event.delegateTarget, "<div class='filter_data'>#{min}-#{max}</div><div class='filter_label'>&nbsp;beds</div>"

		# Listener for start month filter
		@div.find("#start_filter").find(".btn").click (event) =>
			selected_list = []
			$(event.delegateTarget).toggleClass "active"
			button_group = $(event.delegateTarget).parent()

			# Gets list of all the months selected
			button_group.find(".btn.active").each () ->
				selected_list.push $(this).text()

			# Pluralize text depending on amount of start months
			if selected_list.length is 0
				loadPreviewText event.delegateTarget, ""
			else if selected_list.length is 1
				loadPreviewText event.delegateTarget, "<div class='filter_data'>#{selected_list[0]}</div><div class='filter_label'>&nbsp;start</div>"
			else
				loadPreviewText event.delegateTarget, "<div class='filter_data'>#{selected_list.length}</div><div class='filter_label'>&nbsp;starts</div>"
		
		# Filter for checkbox containers (Building type and more filter)
		@div.find("input[type='checkbox']").change (event) =>
			group = $(event.target).closest(".filter_content")
			selected_list = []

			# Finds all the checked boxes
			group.find("input[type='checkbox']").each () ->
				if this.checked
					selected_list.push "0"

			# Clears out text for header if nothing checked
			if selected_list.length is 0
				loadPreviewText event.delegateTarget, ""
			# Otherwise fills in header with text
			else
				if group.attr("id").indexOf("more") is -1
					if selected_list.length is 1
						loadPreviewText event.delegateTarget, "<div class='filter_data'>#{selected_list.length}</div><div class='filter_label'>&nbsp;type</div>"
					else
						loadPreviewText event.delegateTarget, "<div class='filter_data'>#{selected_list.length}</div><div class='filter_label'>&nbsp;types</div>"
				else
					loadPreviewText event.delegateTarget, "<div class='filter_data'>#{selected_list.length}</div><div class='filter_label'>&nbsp;more</div>"

	###
	Creates all listeners and jquery events for RentalFilter
	###
	@SetupUI: ->
		@div = $("#map_filter")

		# Init Sliders from bootstrap-slider.js
		@div.find(".lease_slider").slider
			min: 0
			max: 12
			step: 1
			value: [0, 12]
			tooltip: 'hide'

		# Listener for lease_slider
		.on "slide", (event) =>
			max_desc = if event.value[1] > 1 then "&nbsp;months" else "&nbsp;month"
			@div.find("#lease_min").text event.value[0]
			@div.find("#lease_min_desc").html if event.value[0] > 1 then "&nbsp;months" else "&nbsp;month"
			@div.find("#lease_max").text event.value[1]
			@div.find("#lease_max_desc").html max_desc
			if event.value[0] is event.value[1]
				loadPreviewText event.delegateTarget, "<div class='filter_data'>#{event.value[0]}</div><div class='filter_label'>#{max_desc}</div>"
			else
				loadPreviewText event.delegateTarget, "<div class='filter_data'>#{event.value[0]}-#{event.value[1]}</div><div class='filter_label'>#{max_desc}</div>"

		# Init rent_slider
		@div.find(".rent_slider").slider
			min: 0
			max: 5000
			step: 100
			value: [0, 5000]
			tooltip: 'hide'

		# Listener for the rent_slider
		.on "slide", (event) =>
			min_amount = "$#{event.value[0]}"
			max_amount = if event.value[1] is 5000 then "$#{event.value[1]}+" else "$#{event.value[1]}"
			@div.find("#rent_min").text min_amount
			@div.find("#rent_max").text max_amount
			loadPreviewText event.delegateTarget, "<div class='filter_data'>#{min_amount}-#{max_amount}</div>"


		# Dropdown listener on header filter clicks
		@div.find(".filter_link").click (event) =>
			content = $(event.delegateTarget).attr "data-filter"

			# Last tab selected will remove the label and show the preview for the filter
			lastTab = @div.find(".filter_link.active")
			@div.find(".filter_link").removeClass "active"
			if lastTab.length and lastTab.find(".filter_preview").html().length
				lastTab.find(".filter_title").hide()
				lastTab.find(".filter_preview").show()

			# Current tab display the title of the filter rather than the preview
			$(event.delegateTarget).addClass "active"
			$(event.delegateTarget).find(".filter_preview").hide()
			$(event.delegateTarget).find(".filter_title").show()			

			@div.find("#filter_dropdown").slideUp "fast", () =>
				@div.find(".filter_content").hide()
				@div.find(content).show()
				@div.find("#filter_dropdown").slideDown()

		@CreateListeners()

		

	###
	Called immediately after user applies a filter.
	Submits an ajax call with all current filter parameters
	###
	@ApplyFilter: (field, value) ->
		@[field] = value
		#A2Cribs.Map.ClickBubble.Close()
		ajaxData += "&beds=" + @GetBeds()
		ajaxData += "&rent=" + @Rent
		ajaxData += "&parking=" + 1
		ajaxData += "&dates=" + JSON.stringify @GetMonths()
		ajaxData += "&unit_types=" + JSON.stringify @GetUnitTypes()
		ajaxData += "&amenities=" + JSON.stringify @GetAmenities()
		$.ajax
			url: myBaseUrl + "Rentals/ApplyFilter"
			data: ajaxData
			type: "GET"
			context: this
			success: A2Cribs.FilterManager.UpdateMarkers

	###
	Retrieves all listing_ids for a given marker_id that fit the current filter criteria
	###
	@FilterVisibleListings: (marker_id) ->
		listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker", marker_id
		visibile_listings = []
		for listing in listings
			rent = FilterRent listing
			beds = FilterBeds listing
			baths = FilterBaths listing
			building_type = FilterBuildingType listing
			dates = FilterDates listing
			unit_features = FilterUnitFeatures listing
			parking = FilterParking listing
			pets = FilterPets listing
			amenities = FilterAmenities listing
			square_feet = FilterSquareFeet listing
			year_built = FilterYearBuilt listing
			utilities = FilterUtilities listing
			if rent && beds && baths && building_type && dates && unit_features && parking && pets && amenities && square_feet && year_built && utilities
				visibile_listings.push listing

		return visibile_listings

	@GetBeds: () ->
		beds = [3, 5, 6, 10]
		return JSON.stringify beds

	@GetRent: () ->
		return @
		rent =
			"min" : 100
			"max" : 5000
		return JSON.stringify rent

	@GetMonths: () ->
		dates = 
			"months" :
				"1" : 1
				"2" : 0
				"3" : 1
				"4" : 0
				"5" : 1
				"6" : 0
				"7" : 1
				"8" : 0
				"9" : 1
				"10": 0
				"11": 1
				"12": 0
			"curYear" : [13, 14]
			"leaseLength" :
				'min' : 2
				'max' : 4	
		return dates

	@GetUnitTypes: () ->
		unit_types = 
			"house" : 0
			"apartment" : 1
			"duplex" : 1
			"other" : 0

	@GetAmenities: () ->
		amenities =
			'elevator' : 1

	FilterRent: (listing) ->
		return true

	FilterBeds: (listing) ->
		return true

	FilterBaths: (listing) ->
		return true

	FilterBuildingType: (listing) ->
		return true

	FilterDates: (listing) ->
		return true

	FilterUnitFeatures: (listing) ->
		#a/c, furnished_type
		return true

	FilterParking: (listing) ->
		# parking type
		# parking spots
		# street_parking
		return true

	FilterPets: (listing) ->
		return true

	FilterAmenities: (listing) ->
		# smoking
		# tv
		# balcony
		# fridge
		# storage
		# pool
		# hot_tub
		# fitness_center
		# game_room
		# security_system
		# tanning_beds
		# study_lounge
		# patio_deck
		# yard_space
		# elevator
		return true

	FilterSquareFeet: (listing) ->
		return true

	FilterYearBuilt: (listing) ->
		return true

	FilterUtilities: (listing) ->
		return true