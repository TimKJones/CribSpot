class A2Cribs.RentalSave
	constructor: (modal) ->
		modal = $('.rental-content')
		@EditableRows = []
		@VisibleGrid = 'overview_grid'
		@SetupUI()
		@NextListing

	SetupUI: ->
		###
		********************* TODO **********************
		###
		if not A2Cribs.Geocoder?
			A2Cribs.Geocoder = new google.maps.Geocoder()

		$('body').on "Rental_SavePhoto", (event, row, images, listing_id) =>
			if listing_id?
				for image in images
					A2Cribs.UserCache.Set new Image image
			else
				data = @GridMap[@VisibleGrid].getDataItem row
				data.Image = images

		$("body").on 'click', '.rentals_list_item', (event) =>
			@Open event.target.id

		$("#rentals_edit").click (event) =>
			selected = @GridMap[@VisibleGrid].getSelectedRows()

			if @EditableRows.length
				@GridMap[@VisibleGrid].getEditorLock().commitCurrentEdit()
				$(event.target).text "Edit"
				$(".rentals_tab").removeClass "highlight-tab"
				for row in @EditableRows
					data = @GridMap[@VisibleGrid].getDataItem row
					data.editable = no
				@GridMap[@VisibleGrid].setSelectedRows @EditableRows
				@EditableRows = []
			else if selected.length
				@EditableRows = selected
				$(event.target).text "Finish Editing"
				for row in selected
					data = @GridMap[@VisibleGrid].getDataItem row
					data.editable = yes
			@GridMap[@VisibleGrid].setSelectedRows selected

		$("#rentals_delete").click () =>
			selected = @GridMap[@VisibleGrid].getSelectedRows()
			if selected.length
				listings = []
				for row in selected
					if @GridMap[@VisibleGrid].getDataItem(row).listing_id?
						listings.push @GridMap[@VisibleGrid].getDataItem(row).listing_id
				@Delete selected, listings

		$(".rentals_tab").click (event) =>
			selected = @GridMap[@VisibleGrid].getSelectedRows()
			@VisibleGrid = $(event.target).attr("href").substring(1)
			@GridMap[@VisibleGrid].setSelectedRows selected
			$(event.target).removeClass "highlight-tab"

		$(".rentals-content").on "shown", (event) =>
			width = $("##{@VisibleGrid}").width()
			for grid of @GridMap
				$("##{grid}").css "width", "#{width}px"
				@GridMap[grid].init()


		@CreateGrids()
		# Create grid and setup necessary grid code
		# Create jquery listeners for buttons on Rentals layout

		@MarkerModalSetup()

	Open: (marker_id) ->
		# Gets rental info and saves to JS object
		@ClearGrids()

		@CurrentMarker = marker_id
		marker_object = A2Cribs.UserCache.Get "marker", @CurrentMarker
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
					A2Cribs.UIManager.Success "Save successful!"
					rental_object.Listing.listing_id = response.listing_id
					rental_object.Rental.listing_id = response.listing_id
					for key, value of rental_object
						if A2Cribs[key]? and not value.length?
							A2Cribs.UserCache.Set new A2Cribs[key] value
						else if A2Cribs[key]? and value.length? # Is an array
							for i in value
								i.listing_id = response.listing_id
								A2Cribs.UserCache.Set new A2Cribs[key] i
					console.log response
				else
					A2Cribs.UIManager.Error "Save unsuccessful"
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
					for listing_id in listing_ids
						rentals = A2Cribs.UserCache.GetAllAssociatedObjects "rental", "listing", listing_id
						for rental in rentals
							A2Cribs.UserCache.Remove rental.class_name, rental.GetId()
						A2Cribs.UserCache.Remove "listing", listing_id
					for row in rows
						data.splice row, 1
					@GridMap[@VisibleGrid].updateRowCount()
					@GridMap[@VisibleGrid].render()
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
		@GridMap[@VisibleGrid].getEditorLock().commitCurrentEdit()

		data = @GridMap[@VisibleGrid].getData()

		for row in @EditableRows
			data[row].editable = no

		row_number = data.length
		@EditableRows = [row_number]
		data.push { editable: true }
		@GridMap[@VisibleGrid].setSelectedRows @EditableRows
		$("#rentals_edit").text "Finish Editing"

		# Highlight tabs
		$('a[href="#overview_grid"]').addClass "highlight-tab"
		$('a[href="#description_grid"]').addClass "highlight-tab"
		$('a[href="#contact_grid"]').addClass "highlight-tab"
		$('a[href="#' + @VisibleGrid + '"]').removeClass "highlight-tab"

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
			markers = A2Cribs.UserCache.Get "marker"
			modal.find("#marker_select").empty()
			modal.find("#marker_select").append(
				'<option value="0">--</option>
				<option value="new_marker"><strong>New Location</strong></option>')
			if markers?
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
								marker_object.marker_id = response
								A2Cribs.UserCache.Set new A2Cribs.Marker marker_object
								name = if marker_object.alternate_name? and marker_object.alternate_name.length then marker_object.alternate_name else marker_object.street_address
								list_item = $ "<li />", {
									text: name
									class: "rentals_list_item"
									id: marker_object.marker_id
								}
								$("#rentals_list").append list_item
								$("#rentals_list").slideDown()
								@Open response
								@AddNewUnit()

			else if marker_selected isnt "0"
				modal.modal "hide"
				@Open marker_selected
				@AddNewUnit()

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
		rentals = A2Cribs.UserCache.Get "rental"
		data = []
		if rentals.length
			for rental in rentals
				listing = A2Cribs.UserCache.Get "listing", rental.listing_id
				if listing.marker_id is @CurrentMarker
					data.push rental.GetObject()

		for key, grid of @GridMap
			grid.setData data
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
			"overview_grid", "features_grid", "amenities_grid", "utilities_grid", "buildingamenities_grid", "fees_grid", "description_grid", "picture_grid", "contact_grid"
		]
		@GridMap = {}
		options =
			editable: true
			enableCellNavigation: true
			asyncEditorLoading: false
			enableAddRow: false
			autoEdit: true
			forceFitColumns: true
			explicitInitialization: true
			rowHeight: 35

		data = []
		for container in containers
			columns = @GetColumns container

			checkboxSelector = new Slick.CheckboxSelectColumn
				cssClass: "grid_checkbox"
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
					Image: {}
				}
				isValid = yes
				for key in required
					isValid = isValid and args.item[key]?

				if isValid
					data.Rental = args.item
					if not data.Rental.listing_id?
						data.Listing.listing_type = 0
						data.Listing.marker_id = @CurrentMarker
						if data.Rental.Image?
							data.Image = data.Rental.Image
					else
						data.Listing = A2Cribs.UserCache.Get "listing", data.Rental.listing_id
						data.Image = A2Cribs.UserCache.GetAllAssociatedObjects "image", "listing", data.Rental.listing_id
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
					minWidth: 185
				}
				{
					id: "beds"
					name: "Beds"
					field: "beds"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.RequiredText
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
					formatter: A2Cribs.Formatters.RequiredMoney
				}
				{
					id: "rent_negotiable"
					cssClass: "grid_checkbox"
					name: "(Neg.)"
					field: "rent_negotiable"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "start_date"
					name: "Start Date"
					field: "start_date"
					editor: Slick.Editors.Date
					formatter: A2Cribs.Formatters.RequiredText
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
					formatter: A2Cribs.Formatters.RequiredText
				}
				{
					id: "available"
					name: "Availability"
					field: "available"
					editor: A2Cribs.Editors.Availability
					formatter: A2Cribs.Formatters.Availability
				}
				{
					id: "unit_count"
					name: "Unit Count"
					field: "unit_count"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.RequiredText
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
					minWidth: 185
				}
				{
					id: "baths"
					name: "Baths"
					field: "baths"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Text
				}
				{
					id: "parking_type"
					name: "Parking"
					field: "parking_type"
					editor: A2Cribs.Editors.Parking
					formatter: A2Cribs.Formatters.Parking
				}
				{
					id: "parking_spots"
					name: "Spots"
					field: "parking_spots"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Text
				}
				{
					id: "street_parking"
					cssClass: "grid_checkbox"
					name: "Street Parking"
					field: "street_parking"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "furnished_type"
					name: "Furnished"
					field: "furnished_type"
					editor: A2Cribs.Editors.Furnished
					formatter: A2Cribs.Formatters.Furnished
				}
				{
					id: "pets_type"
					name: "Pets"
					field: "pets_type"
					editor: A2Cribs.Editors.Pets
					formatter: A2Cribs.Formatters.Pets
				}
				{
					id: "smoking"
					name: "Smoking"
					field: "smoking"
					editor: A2Cribs.Editors.Smoking
					formatter: A2Cribs.Formatters.Smoking
				}
				{
					id: "square_feet"
					name: "SQ Feet"
					field: "square_feet"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Text
				}
				{
					id: "year_built"
					name: "Year Built"
					field: "year_built"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Text
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
					minWidth: 185
				}
				{
					id: "air"
					cssClass: "grid_checkbox"
					name: "A/C"
					field: "air"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "washer"
					name: "Washer/Dryer"
					field: "washer"
				}
				{
					id: "fridge"
					cssClass: "grid_checkbox"
					name: "Fridge"
					field: "fridge"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "balcony"
					cssClass: "grid_checkbox"
					name: "Balcony"
					field: "balcony"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "tv"
					cssClass: "grid_checkbox"
					name: "TV"
					field: "tv"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "storage"
					cssClass: "grid_checkbox"
					name: "Storage"
					field: "storage"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "security_system"
					cssClass: "grid_checkbox"
					name: "Security System"
					field: "security_system"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
			]

		BuildingAmenitiesColumns =->
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
					minWidth: 185
				}
				{
					id: "pool"
					cssClass: "grid_checkbox"
					name: "Pool"
					field: "pool"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "hot_tub"
					cssClass: "grid_checkbox"
					name: "Hot Tubs"
					field: "hot_tub"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "fitness_center"
					cssClass: "grid_checkbox"
					name: "Fitness Center"
					field: "fitness_center"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "game_room"
					cssClass: "grid_checkbox"
					name: "Game Room"
					field: "game_room"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "front_desk"
					cssClass: "grid_checkbox"
					name: "Front Desk"
					field: "front_desk"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "tanning_beds"
					cssClass: "grid_checkbox"
					name: "Tanning Beds"
					field: "tanning_beds"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "study_lounge"
					cssClass: "grid_checkbox"
					name: "Study Lounge"
					field: "study_lounge"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "patio_deck"
					cssClass: "grid_checkbox"
					name: "Deck/Patio"
					field: "patio_deck"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "yard_space"
					cssClass: "grid_checkbox"
					name: "Yard Space"
					field: "yard_space"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
				}
				{
					id: "elevator"
					cssClass: "grid_checkbox"
					name: "Elevator"
					field: "elevator"
					editor: Slick.Editors.Checkbox
					formatter: A2Cribs.Formatters.Check
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
					minWidth: 185
				}
				{
					id: "electric"
					name: "Electricity"
					field: "electric"
					editor: A2Cribs.Editors.Utilities
					formatter: A2Cribs.Formatters.Utilities
				}
				{
					id: "water"
					name: "Water"
					field: "water"
					editor: A2Cribs.Editors.Utilities
					formatter: A2Cribs.Formatters.Utilities
				}
				{
					id: "gas"
					name: "Gas"
					field: "gas"
					editor: A2Cribs.Editors.Utilities
					formatter: A2Cribs.Formatters.Utilities
				}
				{
					id: "heat"
					name: "Heat"
					field: "heat"
					editor: A2Cribs.Editors.Utilities
					formatter: A2Cribs.Formatters.Utilities
				}
				{
					id: "sewage"
					name: "Sewage"
					field: "sewage"
					editor: A2Cribs.Editors.Utilities
					formatter: A2Cribs.Formatters.Utilities
				}
				{
					id: "trash"
					name: "Trash"
					field: "trash"
					editor: A2Cribs.Editors.Utilities
					formatter: A2Cribs.Formatters.Utilities
				}
				{
					id: "cable"
					name: "Cable"
					field: "cable"
					editor: A2Cribs.Editors.Utilities
					formatter: A2Cribs.Formatters.Utilities
				}
				{
					id: "internet"
					name: "Internet"
					field: "internet"
					editor: A2Cribs.Editors.Utilities
					formatter: A2Cribs.Formatters.Utilities
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
					minWidth: 185
				}
				{
					id: "deposit_amount"
					name: "Deposit"
					field: "deposit_amount"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "admin_amount"
					name: "Admin"
					field: "admin_amount"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "parking_amount"
					name: "Parking"
					field: "parking_amount"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "furniture_amount"
					name: "Furniture"
					field: "furniture_amount"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "pets_amount"
					name: "Pets"
					field: "pets_amount"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "amenity_amount"
					name: "Amenity"
					field: "amenity_amount"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "upper_floor_amount"
					name: "Upper Floor"
					field: "upper_floor_amount"
					editor: Slick.Editors.Integer
					formatter: A2Cribs.Formatters.Money
				}
				{
					id: "extra_occupant_amount"
					name: "Cost for Extra Occupant"
					field: "extra_occupant_amount"
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
					minWidth: 185
				}
				{
					id: "highlights"
					name: "Highlights"
					field: "highlights"
					editor: Slick.Editors.LongText
					formatter: A2Cribs.Formatters.RequiredText
				}
				{
					id: "description"
					name: "Description"
					field: "description"
					editor: Slick.Editors.LongText
					formatter: A2Cribs.Formatters.Text
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
					minWidth: 185
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
					minWidth: 185
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
					formatter: A2Cribs.Formatters.Text
				}
				{
					id: "lease_office_address"
					name: "lease_office_address"
					field: "lease_office_address"
					editor: Slick.Editors.Text
					formatter: A2Cribs.Formatters.Text
				}
				{
					id: "contact_email"
					name: "contact_email"
					field: "contact_email"
					editor: Slick.Editors.Text
					formatter: A2Cribs.Formatters.RequiredText
				}
				{
					id: "contact_phone"
					name: "contact_phone"
					field: "contact_phone"
					editor: Slick.Editors.Text
					formatter: A2Cribs.Formatters.RequiredText
				}
				{
					id: "website"
					name: "website"
					field: "website"
					editor: Slick.Editors.Text
					formatter: A2Cribs.Formatters.RequiredText
				}
			]

		switch container
			when "overview_grid" then OverviewColumns()
			when "features_grid" then FeaturesColumns()
			when "amenities_grid" then AmenitiesColumns()
			when "utilities_grid" then UtilitiesColumns()
			when "fees_grid" then FeesColumns()
			when "description_grid" then DescriptionColumns()
			when "picture_grid" then PictureColumns()
			when "contact_grid" then ContactColumns()
			when "buildingamenities_grid" then BuildingAmenitiesColumns()
