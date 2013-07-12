class A2Cribs.RentalSave
	constructor: (modal) ->
		modal = $('.rental-content')
		@SetupUI()

	SetupUI: ->
		###
		********************* TODO **********************
		###
		if not A2Cribs.Geocoder?
			A2Cribs.Geocoder = new google.maps.Geocoder()
		@CreateGrids()
		# Create grid and setup necessary grid code
		# Create jquery listeners for buttons on Rentals layout

		@MarkerModalSetup()

	Open: (rental_ids) ->
		###
		********************* TODO **********************
		###
		# Gets rental info and saves to JS object
		@ClearGrids()

	# Sends rental to server including all associated tables (fees, etc.)
	Save: ->
		$.ajax
			url: myBaseUrl + "rentals/Save"
			type: "POST"
			data: A2Cribs.Rental.Template
			success: (response) =>
				response = JSON.parse response
				if response.success != null && response.success != undefined
					alert "Success!"
					console.log response
				else
					alert "Save unsuccessful"
					console.log response

	Copy: (rental_ids) ->
		###
		********************* TODO (Not first priority) *
		###
		# Create new on backend
		# Update grid


	# Sends array of listing_ids to delete
	# IMPORTANT - sends listing_ids, not rental_ids
	Delete: (listing_ids) ->
		$.ajax
			url: myBaseUrl + "listings/Delete/" + JSON.stringify listing_ids
			type: "POST"
			success: (response) =>
				response = JSON.parse response
				if response.success != null && response.success != undefined
					alert "Success!"
				else
					alert "Delete unsuccessful"
					console.log response

	Create: (marker_id)->
		###
		********************* TODO **********************
		###
		@CurrentMarker = marker_id
		# Go into cache and look for marker
		# Set Address area with marker name and picture
		# If listings with this marker show them
		# Open new line on grid (AddNewUnit)

	###
	Called when user adds a new row for the existing marker
	Adds a new row to the grid, with a new row_id.
	Sets the row_id hidden field.
	###
	AddNewUnit: ->
		# Create newline on grid
		data = @GridMap["overview_grid"].getData()
		data.push {}

		for container,grid of @GridMap
			grid.updateRowCount()
			grid.render()

	MarkerModalSetup: ->
		modal = $('#marker-modal')

		clear = () =>
			modal.find("input").val ""
			modal.find('select option:first-child').attr "selected", "selected" # all dropdowns to first option
			@MiniMap.SetMarkerVisible no

		modal.on 'show', () ->
			clear()
			modal.find('#marker_add').hide()
			modal.find("#continue-button").addClass "disabled"
			modal.find("#marker_select").val "0"

		modal.on 'shown', () =>
			@MiniMap.Resize()

		modal.find(".required").keydown ->
			$(this).parent().removeClass "error"

		modal.find("#University_name").focusout () =>
			@FindSelectedUniversity modal
			if @SelectedUniversity?
				@MiniMap.CenterMap @SelectedUniversity.latitude, @SelectedUniversity.longitude

		modal.find("#place_map_button").click () =>
			@FindAddress modal

		modal.find("#marker_select").change () =>
			marker_selected = modal.find("#marker_select").val()
			if marker_selected is "0"
				modal.find("#continue-button").addClass "disabled"
			else
				modal.find("#continue-button").removeClass "disabled"

			if marker_selected is "new_marker"
				modal.find('#marker_add').show()
				@MiniMap.Resize()
			else
				modal.find('#marker_add').hide()

		marker_validate = () ->
			isValid = yes
			if not modal.find('#Marker_street_address').val()
				A2Cribs.UIManager.Error "Please place your street address on the map using the Place On Map button."
				modal.find('#Marker_street_address').parent().addClass "error"
				isValid = no
			if not modal.find('#University_name').val()
				A2Cribs.UIManager.Error "You need to select a university."
				modal.find('#University_name').parent().addClass "error"
				isValid = no
			if modal.find('#Marker_building_type_id').val().length is 0
				A2Cribs.UIManager.Error "You need to select a building type."
				modal.find('#Marker_building_type_id').parent().addClass "error"
				isValid = no
			if modal.find('#Sublet_unit_number').val().length >= 249
				A2Cribs.UIManager.Error "Your unit number is too long."
				modal.find('#Sublet_unit_number').parent().addClass "error"
				isValid = no
			if modal.find('#Marker_alternate_name').val().length >= 249
				A2Cribs.UIManager.Error "Your alternate name is too long."
				modal.find('#Marker_alternate_name').parent().addClass "error"
				isValid = no
			return isValid

		modal.find("#continue-button").click () =>
			marker_selected = modal.find("#marker_select").val()
			if marker_selected is "new_marker"
				if marker_validate()
					# Make new marker ajax and call create
					modal.modal "hide"
					@Create 1

			else if marker_selected isnt "0"
				modal.modal "hide"
				@Create +marker_selected

		@MiniMap = new A2Cribs.MiniMap modal

		if A2Cribs.Cache.SchoolList?
			modal.find("#University_name").typeahead
				source: A2Cribs.Cache.SchoolList
			return
		$.ajax
			url: "/University/getAll"
			success :(response) =>
				A2Cribs.Cache.universitiesMap = JSON.parse response
				A2Cribs.Cache.SchoolList = []
				A2Cribs.Cache.SchoolIDList = []
				for university in A2Cribs.Cache.universitiesMap
					A2Cribs.Cache.SchoolList.push university.University.name
					A2Cribs.Cache.SchoolIDList.push university.University.id
				modal.find("#University_name").typeahead
					source: A2Cribs.Cache.SchoolList

	FindSelectedUniversity: (div) ->
		selected = div.find("#University_name").val()
		index = A2Cribs.Cache.SchoolList.indexOf selected
		if index >= 0
			@SelectedUniversity = A2Cribs.Cache.universitiesMap[index].University;
		else
			@SelectedUniversity = null

	FindAddress: (div) ->
		if @SelectedUniversity?
			address = div.find("#Marker_street_address").val()
			addressObj =
				'address' : address + " " + @SelectedUniversity.city + ", " + @SelectedUniversity.state
			A2Cribs.Geocoder.geocode addressObj, (response, status) =>
				if status is google.maps.GeocoderStatus.OK and response[0].address_components.length >= 2
					for component in response[0].address_components
						for type in component.types
							switch type
								when "street_number" then street_number = component.short_name
								when "route" then street_name = component.short_name
								when "locality" then div.find('#Marker_city').val component.short_name
								when "administrative_area_level_1" then div.find('#Marker_state').val component.short_name
								when "postal_code" then div.find('#Marker_zip').val component.short_name

					if not street_number?
						A2Cribs.UIManager.Alert "Entered street address is not valid."
						$("#Marker_street_address").text ""
						return
					
					@MiniMap.SetMarkerPosition response[0].geometry.location
					div.find("#Marker_street_address").val street_number + " " + street_name
					div.find("#Marker_latitude").val response[0].geometry.location.lat()
					div.find("#Marker_longitude").val response[0].geometry.location.lng()

	PopulateGrid: (rental_ids) ->
		###
		********************* TODO **********************
		###
		# Pre-populate grid based on selected address

	ClearGrids: ->
		for container,grid of @GridMap
			data = []
			grid.setData data
			grid.render()

	CreateGrids: ->
		# Method to create grids for each tab
		containers = [
			"overview_grid", "features_grid", "amenities_grid", "utilites_grid", "fees_grid", "description_grid"
		]
		@GridMap = {}
		options =
			editable: true
			enableCellNavigation: true
			asyncEditorLoading: false
			enableAddRow: false
			autoEdit: false

		data = []
		data.push {}
		for container in containers
			columns = @GetColumns container

			checkboxSelector = new Slick.CheckboxSelectColumn
				cssClass: "slick-cell-checkboxsel"
			columns[0] = checkboxSelector.getColumnDefinition()

			grid = new Slick.Grid "##{container}", data, columns, options
			grid.setSelectionModel new Slick.RowSelectionModel
				selectActiveRow: false

			grid.registerPlugin checkboxSelector
			@GridMap[container] = grid
			columnpicker = new Slick.Controls.ColumnPicker columns, grid, options

	GetColumns: (container) ->
		NumericRangeFormatter = (row, cell, value, columnDef, dataContext) ->
			return dataContext.from + " - " + dataContext.to

		NumericRangeEditor = (args) ->
			$to = $from = null
			@.init = =>
				$from = $("<INPUT type=text style='width:40px' />")
				$from.appendTo args.container
				$from.bind "keydown", @.handleKeyDown

				$(args.container).append "&nbsp; to &nbsp;"

				$to = $("<INPUT type=text style='width:40px' />")
				$to.appendTo args.container
				$to.bind "keydown", @.handleKeyDown

				@.focus()

			@.handleKeyDown = (e) =>
				if e.keyCode is $.ui.keyCode.LEFT or e.keyCode is $.ui.keyCode.RIGHT or e.keyCode is $.ui.keyCode.TAB
					e.stopImmediatePropagation()

			@.destroy = =>
				$(args.container).empty()

			@.applyValue = (item, state) =>

			@.serializeValue = =>
				return {from: parseInt($from.val(), 10), to: parseInt($to.val(), 10)}

			@.focus = ->
				###
				$from.focus()
				###

			@.loadValue = (item) ->
				$from.val item.from
				$to.val item.to

			@.isValueChanged = ->
				return args.item.from != parseInt($from.val(), 10) || args.item.to != parseInt($from.val(), 10);

			@.validate = ->
				if (isNaN(parseInt($from.val(), 10)) || isNaN(parseInt($to.val(), 10)))
					return {valid: false, msg: "Please type in valid numbers."};

				if (parseInt($from.val(), 10) > parseInt($to.val(), 10))
					return {valid: false, msg: "'from' cannot be greater than 'to'"};

				return {valid: true, msg: null};

			@.init()

		OverviewColumns = ->
			columns = [
				{
					# Use for Checkbox
				}
				{
					id: "title"
					name: "Unit/Style - Name"
					field: "title"
					editor: A2Cribs.Editors.Unit
					formatter: A2Cribs.Formatters.Unit
				}
				{
					id: "beds"
					name: "Beds"
					field: "beds"
					editor: Slick.Editors.Integer
				}
				{
					id: "occupancy"
					name: "Occupancy"
					field: "occupancy"
					formatter: A2Cribs.Formatters.Range
					editor: A2Cribs.Editors.Range
				}
				{
					id: "rent"
					name: "Total Rent"
					field: "rent"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "rent_negotiable"
					name: "(Neg.)"
					field: "rent_negotiable"
					editor: Slick.Editors.Checkbox
					formatter: Slick.Formatters.Checkmark
				}
				{
					id: "start_date"
					name: "Start Date"
					field: "start_date"
					editor: Slick.Editors.Date
				}
				{
					id: "alternate_start_date"
					name: "Alt. Start Date"
					field: "alternate_start_date"
					editor: Slick.Editors.Date 
				}
				{
					id: "end_date"
					name: "End Date"
					field: "end_date"
					editor: Slick.Editors.Date 
				}
				{
					id: "available"
					name: "Availability"
					field: "available"
					editor: A2Cribs.Editors.Availability
				}
			]

		FeaturesColumns = ->
			columns = [
				{
					# Use for Checkbox
				}
				{
					id: "title"
					name: "Unit/Style - Name"
					field: "title"
					editor: A2Cribs.Editors.Unit
					formatter: A2Cribs.Formatters.Unit
				}
				{
					id: "baths"
					name: "Baths"
					field: "baths"
					editor: Slick.Editors.Integer
				}
				{
					id: "air"
					name: "A/C"
					field: "air"
					editor: A2Cribs.Editors.AC
				}
				{
					id: "parking_type"
					name: "Parking"
					field: "parking_type"
					editor: A2Cribs.Editors.Parking
				}
				{
					id: "parking_spots"
					name: "Spots"
					field: "parking_spots"
					editor: Slick.Editors.Integer
				}
				{
					id: "street_parking"
					name: "Street Parking"
					field: "street_parking"
					editor: Slick.Editors.Checkbox
					formatter: Slick.Formatters.Checkmark
				}
				{
					id: "furnished_type"
					name: "Furnished"
					field: "furnished_type"
					editor: A2Cribs.Editors.Furnished
				}
				{
					id: "pets_type"
					name: "Pets"
					field: "pets_type"
					editor: A2Cribs.Editors.Pets
				}
				{
					id: "smoking"
					name: "Smoking"
					field: "smoking"
					editor: A2Cribs.Editors.Smoking
				}
				{
					id: "fridge"
					name: "Fridge"
					field: "fridge"
					editor: Slick.Editors.Checkbox
					formatter: Slick.Formatters.Checkmark
				}
				{
					id: "balcony"
					name: "Balcony"
					field: "balcony"
					editor: Slick.Editors.Checkbox
					formatter: Slick.Formatters.Checkmark
				}
				{
					id: "tv"
					name: "TV"
					field: "tv"
					editor: Slick.Editors.Checkbox
					formatter: Slick.Formatters.Checkmark
				}
				{
					id: "storage"
					name: "Storage"
					field: "storage"
					editor: Slick.Editors.Checkbox
					formatter: Slick.Formatters.Checkmark
				}
				{
					id: "square_feet"
					name: "SQ Feet"
					field: "square_feet"
					editor: Slick.Editors.Integer
				}
				{
					id: "year_built"
					name: "Year Built"
					field: "year_built"
					editor: Slick.Editors.Integer
				}
			]

		AmenitiesColumns = ->
			columns = [
				{
					# Use for Checkbox
				}
				{
					id: "title"
					name: "Unit/Style - Name"
					field: "title"
					editor: A2Cribs.Editors.Unit
					formatter: A2Cribs.Formatters.Unit
				}
			]

		UtilitiesColumns = ->
			columns = [
				{
					# Use for Checkbox
				}
				{
					id: "title"
					name: "Unit/Style - Name"
					field: "title"
					editor: A2Cribs.Editors.Unit
					formatter: A2Cribs.Formatters.Unit
				}
				{
					id: "electric"
					name: "Electricity"
					field: "electric"
					editor: A2Cribs.Editors.Utilities
				}
				{
					id: "water"
					name: "Water"
					field: "water"
					editor: A2Cribs.Editors.Utilities
				}
				{
					id: "gas"
					name: "Gas"
					field: "gas"
					editor: A2Cribs.Editors.Utilities
				}
				{
					id: "heat"
					name: "Heat"
					field: "heat"
					editor: A2Cribs.Editors.Utilities
				}
				{
					id: "sewage"
					name: "Sewage"
					field: "sewage"
					editor: A2Cribs.Editors.Utilities
				}
				{
					id: "trash"
					name: "Trash"
					field: "trash"
					editor: A2Cribs.Editors.Utilities
				}
				{
					id: "cable"
					name: "Cable"
					field: "cable"
					editor: A2Cribs.Editors.Utilities
				}
				{
					id: "internet"
					name: "Internet"
					field: "internet"
					editor: A2Cribs.Editors.Utilities
				}
				{
					id: "utility_total_flat_rate"
					name: "Total Flat Rate"
					field: "utility_total_flat_rate"
					editor: A2Cribs.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "utility_estimate_winter"
					name: "Est. Winter Utility Cost"
					field: "utility_estimate_winter"
					editor: A2Cribs.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "utility_estimate_summer"
					name: "Est. Summer Utility Cost"
					field: "utility_estimate_summer"
					editor: A2Cribs.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
			]

		FeesColumns = ->
			columns = [
				{
					# Use for Checkbox
				}
				{
					id: "title"
					name: "Unit/Style - Name"
					field: "title"
					editor: A2Cribs.Editors.Unit
					formatter: A2Cribs.Formatters.Unit
				}
				{
					id: "beds"
					name: "Deposit"
					field: "beds"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "occupancy"
					name: "Admin"
					field: "occupancy"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "rent"
					name: "Parking"
					field: "rent"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "start_date"
					name: "Furniture"
					field: "start_date"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "alt_start_date"
					name: "Pets"
					field: "alt_start_date"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "lease_length"
					name: "Amenity"
					field: "lease_length"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "availability"
					name: "Upper Floor"
					field: "availability"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "extra_occupant"
					name: "Cost for Extra Occupant"
					field: "extra_occupant"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "other_fee_description"
					name: "Other Fees"
					field: "other_fee_description"
					editor: Slick.Editors.Text
				}
				{
					id: "other_fee_cost"
					name: "Fee"
					field: "other_fee_cost"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
			]

		DescriptionColumns = ->
			columns = [
				{
					# Use for Checkbox
				}
				{
					id: "title"
					name: "Unit/Style - Name"
					field: "title"
					editor: A2Cribs.Editors.Unit
					formatter: A2Cribs.Formatters.Unit
				}
				{
					id: "beds"
					name: "Highlights"
					field: "beds"
					editor: Slick.Editors.LongText
				}
				{
					id: "occupancy"
					name: "Description"
					field: "occupancy"
					editor: Slick.Editors.LongText
				}
			]

		switch container
			when "overview_grid" then OverviewColumns()
			when "features_grid" then FeaturesColumns()
			when "amenities_grid" then AmenitiesColumns()
			when "utilites_grid" then UtilitiesColumns()
			when "fees_grid" then FeesColumns()
			when "description_grid" then DescriptionColumns()
