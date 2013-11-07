class SubletSave

	###
	Setup UI
	Creates the listeners and all the UI for the
	Sublet window
	###
	@SetupUI: (@div) ->
		# Date pickers
		# Save button
		$('#sublet_list_content').on "marker_added", (event, marker_id) =>
			@Open marker_id

		$('#sublet_list_content').on 'click', '.sublet_list_item', (event) =>
			@Open event.currentTarget.id

		@div.find("#sublet_save_button").click =>
			@Save()


	###
	Validate
	Called before advancing steps
	Returns true if validations pass; false otherwise
	###
	@Validate: ->
		isValid = yes
		@div.find(".btn-group").each (index, value) ->
			if $(value).find(".active").size() is 0 then isValid = no

	###
	Reset
	Erases all the fields and resets
	the Sublet window and sublet object
	###
	@Reset: ->
		@div.find(".btn-group").each (index, value) ->
			$(value).find(".active").removeClass "active"

	###
	Open
	Opens up an existing sublet from a marker_id
	###
	@Open: (marker_id) ->
		sublets = A2Cribs.UserCache.GetAllAssociatedObjects "sublet", "marker",  marker_id
		if sublets.length isnt 0
			@Populate sublets[0]
		else
			@Reset()

		@div.show()

	###
	###
	@Populate: (sublet_object) ->
		# Get all fields from dom
		# Loop through them and populate
		$(".sublet_fields").each (index, value) ->
			# If button group
			if $(value).hasClass "btn-group"
				lol = "lol"
			else if $(value).hasClass "date-field"
				lol = "lol"
			else if $(value).hasClass "text-field"
				lol = "lol"

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
		sublet_object = {
			'rent': 9999
			'beds': 1
			'baths': 1
			'bathroom_type': 1
			'parking_available': 1
			'parking_description': 'lol'
			'utilities_included': 1
			'utilities_description': "LOL"
			'start_date': '2013-09-02'
			'end_date': '2013-09-03'
			'available_now': 1
			'air': 1
			'furnished': 1
			'description': 1
		}
		return {
			'Listing': {
				listing_type: 1
				marker_id: 1
			}
			'Sublet': sublet_object
			'Image': [] 
		}

	###
	Replaces '/' with '-' to make convertible to mysql datetime format
	###
	@GetMysqlDateFormat: (dateString) ->
		date = new Date(dateString)
		month = date.getMonth() + 1
		if month < 10
			month = "0" + month
		day = date.getDate()
		if day < 10
			day = "0" + day
		year = date.getUTCFullYear()
		beginDateFormatted = year + "-" + month + "-" + day

	@GetTodaysDate: () ->
		today = new Date()
		dd = today.getDate()
		mm = today.getMonth()+1
		yyyy = today.getFullYear()
		if(dd<10)
			dd='0'+dd
		if(mm<10)
			mm='0'+mm
		today = mm+'/'+dd+'/'+yyyy
		return today

	@GetFormattedDate:(date) ->
		month = date.getMonth() + 1
		if month < 10
			month = "0" + month
		day = date.getDate()
		if day < 10
			day = "0" + day
		year = date.getUTCFullYear()
		beginDateFormatted = month + "/" + day + "/" + year


	$("#sublet_window").ready =>
		@SetupUI($("#sublet_window"))