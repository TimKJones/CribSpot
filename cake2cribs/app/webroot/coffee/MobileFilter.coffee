class A2Cribs.MobileFilter extends A2Cribs.FilterManager
	@FilterData = {}

	###
	Creates all listeners and jquery events for MobileFilter
	###
	@SetupUI: ->
		@div = $("#mobile_filter")

		@div.find('select#listing_type').change (e) =>
			console.log $(e.target).val()
			school_name = @div.data('university-name')
			switch $(e.target).val()
				when "Rentals"
					window.location.href = "/rentals/#{school_name}"
				when "Sublets"
					window.location.href = "/sublet/#{school_name}"

		@div.find('select#bedrooms').change (e) =>
			$opt = $(e.target).find('option:selected')
			min = parseInt $opt.data('min'), 10
			max = parseInt $opt.data('max'), 10
			@ApplyFilter "Beds", [min..max]

		@div.find('select#rent').change (e) =>
			$opt = $(e.target).find('option:selected')
			@ApplyFilter "Rent", {
				min: parseInt $opt.data("min"), 10
				max: parseInt $opt.data("max"), 10
			}

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