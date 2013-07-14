class A2Cribs.RentalSave
	constructor: (modal) ->
		modal = $('.rental-content')
		@ListingIds = []
		@EditableRows = []
		@VisibleGrid = 'overview_grid'
		@SetupUI()

	SetupUI: ->
		###
		********************* TODO **********************
		###
		if not A2Cribs.Geocoder?
			A2Cribs.Geocoder = new google.maps.Geocoder()

		$("body").on 'click', '.rentals_list_item', (event) =>
			@Open event.target.id

		$("#rentals_edit").click (event) =>
			selected = @GridMap[@VisibleGrid].getSelectedRows()
			for row in selected
				data = @GridMap[@VisibleGrid].getDataItem row
				data.editable = not @EditableRows.length

			if @EditableRows.length
				@GridMap[@VisibleGrid].getEditorLock().commitCurrentEdit()
				@EditableRows = []
				$(event.target).text "Edit"
			else
				@EditableRows = selected
				$(event.target).text "Save"
			@GridMap[@VisibleGrid].setSelectedRows selected

		$("#rentals_delete").click () =>
			selected = @GridMap[@VisibleGrid].getSelectedRows()	
			listings = []
			for row in selected
				listings.push +@GridMap[@VisibleGrid].getDataItem(row).listing_id
			@Delete selected, listings

		$(".rentals_tab").click (event) =>
			selected = @GridMap[@VisibleGrid].getSelectedRows()
			@VisibleGrid = $(event.target).attr("href").substring(1)
			@GridMap[@VisibleGrid].setSelectedRows selected


		@CreateGrids()
		# Create grid and setup necessary grid code
		# Create jquery listeners for buttons on Rentals layout

		@MarkerModalSetup()

	Open: (marker_id) ->
		# Gets rental info and saves to JS object
		@ClearGrids()
		@ListingIds = []

		@CurrentMarker = marker_id
		marker_object = A2Cribs.UserCache.GetMarkerById @CurrentMarker
		name = if marker_object.alternate_name? and marker_object.alternate_name.length then marker_object.alternate_name else marker_object.street_address
		$("#rentals_address").html "<strong>#{name}</strong><br>"
		A2Cribs.Dashboard.ShowContent $(".rentals-content"), true

		@PopulateGrid marker_id

	# Sends rental to server including all associated tables (fees, etc.)
	Save: (row, rental_object) ->
		$.ajax
			url: myBaseUrl + "listings/Save/"
			type: "POST"
			data: rental_object
			success: (response) =>
				response = JSON.parse response
				if response.listing_id?
					alert "Success!"
					@ListingIds[row] = response.listing_id
					console.log response
				else
					alert "Save unsuccessful"
					console.log response

	###
	Test function for Listings/GetListing.
	Retrieves the listing specified by listing_id.
	If listing_id is null, retrieves all listings owned by the logged-in user.
	###
	GetListing: (listing_id = null) ->
		url = myBaseUrl + 'listings/GetListing/'
		if listing_id != null
			url = url + listing_id
		$.ajax
			url: url
			type: "POST"
			success: (response) =>
				console.log JSON.parse response

	Copy: (rental_ids) ->
		###
		********************* TODO (Not first priority) *
		###
		# Create new on backend
		# Update grid


	# Sends array of listing_ids to delete
	# IMPORTANT - sends listing_ids, not rental_ids
	Delete: (rows, listing_ids) ->
		$.ajax
			url: myBaseUrl + "listings/Delete/" + JSON.stringify listing_ids
			type: "POST"
			success: (response) =>
				response = JSON.parse response
				if response.success != null && response.success != undefined
					A2Cribs.UIManager.Success "Listings deleted!"
					data = @GridMap[@VisibleGrid].getData()
					for row in rows
						data.splice row, 1
				else
					A2Cribs.UIManager.Error "Delete unsuccessful"
					console.log response

	Create: (marker_id)->
		###
		********************* TODO **********************
		###
		@CurrentMarker = marker_id
		A2Cribs.Dashboard.ShowContent $(".rentals-content"), true
		for key, grid of @GridMap
			grid.init()

		data = @GridMap["overview_grid"].getData()

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
			markers = A2Cribs.UserCache.GetRentalMarkers()
			modal.find("#marker_select").empty()
			modal.find("#marker_select").append(
				'<option value="0">--</option>
				<option value="new_marker"><strong>New Location</strong></option>')
			for marker in markers
				name = if marker.alternate_name? and marker.alternate_name.length then marker.alternate_name else marker.street_address
				option = $ "<option />",
					{
						text: name
						value: marker.marker_id
					}
				modal.find("#marker_select").append option

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
					marker_object = {
						alternate_name: modal.find('#Marker_alternate_name').val()
						building_type_id: modal.find('#Marker_building_type_id').val()
						street_address: modal.find('#Marker_street_address').val()
						city: modal.find('#Marker_city').val()
						state: modal.find('#Marker_state').val()
						zip: modal.find('#Marker_zip').val()
						latitude: modal.find('#Marker_latitude').val()
						longitude: modal.find('#Marker_longitude').val()
					}
					$.ajax
						url: "/Markers/Save/"
						type: "POST"
						data: marker_object
						success :(response) =>
							if response.error
								UIManager.Error response.error
							else
								modal.modal "hide"
								marker_object.marker_id = parseInt response, 10
								A2Cribs.UserCache.AddRentalMarker marker_object
								name = if marker_object.alternate_name? and marker_object.alternate_name.length then marker_object.alternate_name else marker_object.street_address
								list_item = $ "<li />", {
									text: name
									class: "rentals_list_item"
									id: marker_object.marker_id
								}
								$("#rentals_list").append list_item
								$("#rentals_list").slideDown()
								@Open +response
								@AddNewUnit()

			else if marker_selected isnt "0"
				modal.modal "hide"
				@Open +marker_selected

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

	PopulateGrid: (marker_id) ->
		###
		********************* TODO **********************
		###
		# Pre-populate grid based on selected address
		rentals = A2Cribs.UserCache.GetRentals()
		data = []
		if rentals.length
			for i in [0..rentals.length - 1]
				if rentals[i].Marker.marker_id is @CurrentMarker
					data.push rentals[i].Rental
					@ListingIds[i] = rentals[i].Listing.listing_id

		for key, grid of @GridMap
			grid.setData data
			grid.init()
			grid.autosizeColumns()
			grid.updateRowCount()
			grid.render()

	ClearGrids: ->
		for container,grid of @GridMap
			data = []
			grid.setData data
			grid.render()

	CreateGrids: ->
		# Method to create grids for each tab
		containers = [
			"overview_grid", "features_grid", "amenities_grid", "utilites_grid", "fees_grid", "description_grid", "picture_grid", "contact_grid"
		]
		@GridMap = {}
		options =
			editable: true
			enableCellNavigation: true
			asyncEditorLoading: false
			enableAddRow: false
			autoEdit: true
			explicitInitialization: true

		data = []
		for container in containers
			columns = @GetColumns container

			checkboxSelector = new Slick.CheckboxSelectColumn
				cssClass: "slick-cell-checkboxsel"
			columns[0] = checkboxSelector.getColumnDefinition()

			@GridMap[container] = new Slick.Grid "##{container}", data, columns, options
			@GridMap[container].setSelectionModel new Slick.RowSelectionModel
				selectActiveRow: false

			@GridMap[container].registerPlugin checkboxSelector
			columnpicker = new Slick.Controls.ColumnPicker columns, @GridMap[container], options

			@GridMap[container].onBeforeEditCell.subscribe (e, args) =>
				if @EditableRows.indexOf(args.row) isnt -1
					console.log  "lol"
					return true
				else
					return false

			@GridMap[container].onCellChange.subscribe (e, args) =>
				columns = @GridMap[container].getColumns()
				required = A2Cribs.Rental.Required_Fields
				data = {
					Rental: {}
					Listing: {}
					Fees: []
				}
				isValid = yes
				for key in required
					isValid = isValid and args.item[key]?

				if isValid
					for desc, amount of args.item
						index = desc.indexOf("Fee_")
						if index != -1
							data.Fees.push {
								description: desc.split("_").join(" ")
								amount: amount
							}

					data.Rental = args.item
					data.Listing.listing_type = 0
					if @ListingIds[args.row]?
						data.Listing.listing_id = @ListingIds[args.row]
					data.Listing.marker_id = @CurrentMarker
					@Save args.row, data

	GetColumns: (container) ->
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
					formatter: A2Cribs.Formatters.Text
				}
				{
					id: "alternate_start_date"
					name: "Alt. Start Date"
					field: "alternate_start_date"
					editor: Slick.Editors.Date 
					formatter: A2Cribs.Formatters.Text
				}
				{
					id: "end_date"
					name: "End Date"
					field: "end_date"
					editor: Slick.Editors.Date 
					formatter: A2Cribs.Formatters.Text
				}
				{
					id: "available"
					name: "Availability"
					field: "available"
					editor: A2Cribs.Editors.Availability
				}
				{
					id: "unit_count"
					name: "Unit Count"
					field: "unit_count"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Text
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
					id: "deposit_fee"
					name: "Deposit"
					field: "Fee_Deposit"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "admin_fee"
					name: "Admin"
					field: "Fee_Admin"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "parking_fee"
					name: "Parking"
					field: "Fee_Parking"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "furniture_fee"
					name: "Furniture"
					field: "Fee_Furniture"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "pets_fee"
					name: "Pets"
					field: "Fee_Pets"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "amenity_fee"
					name: "Amenity"
					field: "Fee_Amenity"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "upper_floor_fee"
					name: "Upper Floor"
					field: "Fee_Upper_Floor"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "extra_occupant_fee"
					name: "Cost for Extra Occupant"
					field: "Fee_Extra_Occupant"
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
					id: "highlights"
					name: "Highlights"
					field: "highlights"
					editor: Slick.Editors.LongText
				}
				{
					id: "description"
					name: "Description"
					field: "description"
					editor: Slick.Editors.LongText
				}
			]

		PictureColumns = ->
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
					id: "pictures"
					name: "Pictures"
					formatter: A2Cribs.Formatters.Button
				}
			]

		ContactColumns = ->
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
					id: "waitlist"
					name: "Waitlist"
					field: "waitlist"
					editor: Slick.Editors.YesNoSelect
					formatter: Slick.Formatters.YesNo
				}
				{
					id: "waitlist_open_date"
					name: "waitlist_open_date"
					field: "waitlist_open_date"
					editor: Slick.Editors.Date 
				}
				{
					id: "lease_office_address"
					name: "lease_office_address"
					field: "lease_office_address"
					editor: Slick.Editors.Text
				}
				{
					id: "contact_email"
					name: "contact_email"
					field: "contact_email"
					editor: Slick.Editors.Text
				}
				{
					id: "contact_phone"
					name: "contact_phone"
					field: "contact_phone"
					editor: Slick.Editors.Text
				}
				{
					id: "website"
					name: "website"
					field: "website"
					editor: Slick.Editors.Text
				}
			]

		switch container
			when "overview_grid" then OverviewColumns()
			when "features_grid" then FeaturesColumns()
			when "amenities_grid" then AmenitiesColumns()
			when "utilites_grid" then UtilitiesColumns()
			when "fees_grid" then FeesColumns()
			when "description_grid" then DescriptionColumns()
			when "picture_grid" then PictureColumns()
			when "contact_grid" then ContactColumns()
