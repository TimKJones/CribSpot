class A2Cribs.RentalFilter extends A2Cribs.FilterManager
	@FilterData = {}

	###
	Private method for loading the contents of the filter preview into the header filter
	###
	loadPreviewText = (div, text) ->
			title = $(div).closest(".filter_content").attr("data-link")
			$(title).find(".filter_preview").html text

	@CreateListeners: ->
		$("#filter_search_content").keyup (event) =>
			if event.keyCode is 13
				A2Cribs.FilterManager.SearchForAddress event.delegateTarget
				$(event.delegateTarget).select()

		###
		On Change listeners for applying changed fields
		###
		@div.find(".lease_slider").on "slideStop", (event) =>
			@ApplyFilter "LeaseRange", 
				min: parseInt event.value[0], 10
				max: parseInt event.value[1], 10

		@div.find(".rent_slider").on "slideStop", (event) =>
			@ApplyFilter "Rent", 
				min: parseInt event.value[0], 10
				max: parseInt event.value[1], 10
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
				@ApplyFilter "Beds", null
			# Check to see amount of beds or to make a range of beds
			else 
				@ApplyFilter "Beds", selected_list
				if selected_list.length is 1
					if min is 0
						text = "<div class='filter_data'>Studio</div>"
					else if min is 1
						text = "<div class='filter_data'>#{min}</div><div class='filter_label'>&nbsp;bed</div>"
					else
						text = "<div class='filter_data'>#{min}</div><div class='filter_label'>&nbsp;beds</div>"
					loadPreviewText event.delegateTarget, text
				else
					loadPreviewText event.delegateTarget, "<div class='filter_data'>#{min}-#{max}</div><div class='filter_label'>&nbsp;beds</div>"

		@div.find("#year_filter").change (event) =>
			dates = @FilterData.Dates
			year = $(event.delegateTarget).val()
			if dates?
				dates.year = year
				@ApplyFilter "Dates", dates
			else
				@ApplyFilter "Dates", 
					months: []
					year: year

		# Listener for start month filter
		@div.find("#start_filter").find(".btn").click (event) =>
			selected_list = []
			$(event.delegateTarget).toggleClass "active"
			button_group = $(event.delegateTarget).parent()
			monthText = ""

			# Gets list of all the months selected
			button_group.find(".btn.active").each () ->
				selected_list.push $(this).attr "data-month"
				monthText = $(this).text()

			# Pluralize text depending on amount of start months
			if selected_list.length is 0
				loadPreviewText event.delegateTarget, ""
				@ApplyFilter 'Dates', null
			else
				@ApplyFilter 'Dates', 
					months: selected_list
					year: @div.find("#year_filter").val()
				if selected_list.length is 1
					loadPreviewText event.delegateTarget, "<div class='filter_data'>#{monthText}</div><div class='filter_label'>&nbsp;start</div>"
				else
					loadPreviewText event.delegateTarget, "<div class='filter_data'>#{selected_list.length}</div><div class='filter_label'>&nbsp;starts</div>"
		
		# Filter for checkbox containers (Building type and more filter)
		@div.find("input[type='checkbox']").change (event) =>
			group = $(event.target).closest(".filter_content")
			filterType = $(event.delegateTarget).attr "data-filter"
			selected_list = []

			# Finds all the checked boxes
			group.find("input[type='checkbox']").each () ->
				if this.checked
					selected_list.push $(this).attr "data-value"

			if filterType is "UnitTypes"
				@ApplyFilter filterType, selected_list
			else
				@ApplyFilter filterType, +event.delegateTarget.checked

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

		@div.find(".hidden_input").change (event) =>
			console.log event.currentTarget.value
			date = @GetBackendDateFormat event.currentTarget.value
			filter = $(event.currentTarget).data("filter")
			@ApplyFilter filter, date
			date_split = event.currentTarget.value.split("/")
			if filter.indexOf("Start") isnt -1
				$(event.currentTarget).parent().find(".filter_title").text "Starts: #{date_split[0]}/#{date_split[1]}"
			else if filter.indexOf("End") isnt -1
				$(event.currentTarget).parent().find(".filter_title").text "Ends: #{date_split[0]}/#{date_split[1]}"

	###
	Creates all listeners and jquery events for RentalFilter
	###
	@SetupUI: ->
		@div = $("#map_filter")

		$(".hidden_input").datepicker
			onClose: (date) ->
				$(".filter_link").removeClass "active"


		$("#start_date_filter_link, #end_date_filter_link").click (event) ->
			$(event.currentTarget).find(".hidden_input").datepicker('show')


		$("#filter_search_btn").click =>
			if $("#filter_search_content").is(":visible")
				$("#filter_search_content").hide 'slide', {direction: 'left'}, 300
			else
				$("#filter_search_content").show 'slide', {direction: 'left'}, 300
				$("#filter_search_content").focus()

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
			if $(lastTab).attr('id') != $(event.delegateTarget).attr('id')
				$(event.delegateTarget).addClass "active"
				$(event.delegateTarget).find(".filter_preview").hide()
				$(event.delegateTarget).find(".filter_title").show()			

			@div.find("#filter_dropdown").slideUp "fast", () =>
				@div.find(".filter_content").hide()
				# If last tab and current tab are the same, then hide dropdown and return 
				if $(lastTab).attr('id') != $(event.delegateTarget).attr('id')
					@div.find(content).show()
					@div.find("#filter_dropdown").slideDown()

		@div.find('#rentals-filter-label').click (event) =>
			lastTab = @div.find(".filter_link.active")
			@div.find("#filter_dropdown").slideUp "fast"
			if lastTab.length and lastTab.find(".filter_preview").html().length
				lastTab.find(".filter_title").hide()
				lastTab.find(".filter_preview").show()
			@div.find(".filter_link").removeClass "active"

		@CreateListeners()

		

	###
	Called immediately after user applies a filter.
	Submits an ajax call with all current filter parameters
	###
	@ApplyFilter: (field, value) ->
		if value?
			@FilterData[field] = value
		else
			delete @FilterData[field]
		ajaxData = ''
		first = true
		for key,value of @FilterData
			if !first
				ajaxData += "&"
			first = false
			ajaxData += key + "=" + JSON.stringify value
		$("#loader").show()
		$.ajax
			url: myBaseUrl + "Listings/ApplyFilter/#{A2Cribs.FilterManager.ActiveListingType}"
			data: ajaxData
			type: "GET"
			context: this
			success: A2Cribs.FilterManager.UpdateListings
			complete: () ->
				$("#loader").hide()

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