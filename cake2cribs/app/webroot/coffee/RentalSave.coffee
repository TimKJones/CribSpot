class A2Cribs.RentalSave
	constructor: (dropdown_content, @user_email, @user_phone) ->
		@div = $('.rentals-content')
		@EditableRows = []
		@Editable = false
		@VisibleGrid = 'overview_grid'
		@SetupUI dropdown_content
		@NextListing

	SetupUI: (dropdown_content) ->
		$('#middle_content').height()
		@div.find("grid-pane").height

		$(".create-listing").find("a").click (event) =>
			listing_type = $(event.currentTarget).data "listing-type"
			if listing_type is "rental"
				A2Cribs.MarkerModal.Open listing_type
				 
		@CreateCallbacks()
		@CreateGrids dropdown_content

	CreateCallbacks: () ->
		$('#rental_list_content').on "marker_added", (event, marker_id) =>
			@Open(marker_id)
			.done => @AddNewUnit()

		$('body').on "Rental_marker_updated", (event, marker_id) =>
			if $("#rental_list_content").find("##{marker_id}").length is 1
				list_item = $("#rental_list_content").find("##{marker_id}")
				name = A2Cribs.UserCache.Get("marker", marker_id).GetName()
				list_item.text name
				@CreateListingPreview marker_id

		$("body").on 'click', '.rental_list_item', (event) =>
			if @Editable
				A2Cribs.UIManager.ConfirmBox "By selecting a new address, all unsaved changes will be lost.",
					{
						"ok": "Abort Changes"
						"cancel": "Return to Editor"
					}, (success) =>
						if success
							@CancelEditing()
							@Open event.target.id
			else
				@Open event.target.id

		@div.find(".edit_marker").click () =>
			A2Cribs.MixPanel.PostListing "Started", {}
			A2Cribs.MarkerModal.Open()
			A2Cribs.MarkerModal.LoadMarker @CurrentMarker

		$("#rentals_edit").click (event) =>
			selected = @GridMap[@VisibleGrid].getSelectedRows()
			if @Editable
				@FinishEditing()
			else
				if selected?.length is 0
					A2Cribs.UIManager.CloseLogs()
					A2Cribs.UIManager.Error "Please select the row you wish to edit!"
					return
				@Edit selected

			@GridMap[@VisibleGrid].setSelectedRows selected

		$("#rentals_delete").click () =>
			selected = @GridMap[@VisibleGrid].getSelectedRows()
			if selected.length
				if @GridMap[@VisibleGrid].getEditorLock().isActive()
					active_row = @GridMap[@VisibleGrid].getActiveCell().row
				if selected.indexOf(active_row) isnt -1
					return @GridMap[@VisibleGrid].getEditorLock().cancelCurrentEdit()
				listings = []
				for row in selected
					if @GridMap[@VisibleGrid].getDataItem(row).listing_id?
						listings.push @GridMap[@VisibleGrid].getDataItem(row).listing_id
					if (index = @EditableRows.indexOf(row)) isnt -1
						@EditableRows.splice index, 1
				@Delete selected, listings
				if @EditableRows.length is 0
					@FinishEditing()

		$(".rentals_tab").click (event) =>
			if @CommitSlickgridChanges()
				selected = @GridMap[@VisibleGrid].getSelectedRows()
				@VisibleGrid = $(event.target).attr("data-target").substring(1)
				A2Cribs.MixPanel.PostListing "#{@VisibleGrid} selected",
					"marker id": @CurrentMarker
				@GridMap[@VisibleGrid].setSelectedRows selected
				for row in @EditableRows
					@Validate row
				$(event.target).removeClass "highlight-tab"
				$(event.delegateTarget).tab 'show'

		$(".rentals-content").on "shown", (event) =>
			width = $("##{@VisibleGrid}").width()
			height = $('#add_new_unit').position().top - $("##{@VisibleGrid}").position().top
			@Map?.Resize()
			for grid of @GridMap
				$("##{grid}").css "width", "#{width}px"
				$("##{grid}").css "height", "#{height}px"
				@GridMap[grid].init()

	CommitSlickgridChanges: ->
		return @GridMap[@VisibleGrid].getEditorLock()?.commitCurrentEdit()

	Edit: (rows) ->
		@EditableRows = rows
		$("#rentals_edit").text "Finish Editing"
		for row in rows
			data = @GridMap[@VisibleGrid].getDataItem row
			if data?
				data.editable = yes
		@Editable = true

	CancelEditing: ->
		# Cancel current editor
		@GridMap[@VisibleGrid].getEditorLock()?.cancelCurrentEdit()

		# Delete the unsaved rows
		for row in @EditableRows
			data = @GridMap[@VisibleGrid].getDataItem row
			data.editable = no
			if not data?.listing_id?
				@GridMap[@VisibleGrid].getData().splice row, 1
		@GridMap[@VisibleGrid].updateRowCount()
		@GridMap[@VisibleGrid].render()
		@GridMap[@VisibleGrid].getSelectionModel().setSelectedRanges([])
		@EditableRows = []
		@Editable = false
		$("#rentals_edit").text "Edit"
		$(".rentals_tab").removeClass "highlight-tab"

	FinishEditing: () ->
		if @CommitSlickgridChanges()
			isValid = yes
			for row in @EditableRows
				isValid = isValid and @Validate row 
			if isValid
				$("#rentals_edit").text "Edit"
				$(".rentals_tab").removeClass "highlight-tab"
				for row in @EditableRows
					data = @GridMap[@VisibleGrid].getDataItem row
					data.editable = no
				@GridMap[@VisibleGrid].setSelectedRows @EditableRows
				@EditableRows = []
				@Editable = false
			else
				A2Cribs.UIManager.CloseLogs()
				A2Cribs.UIManager.Error "Please complete all required fields to finish editing!"

	Open: (marker_id) ->	
		# Gets rental info and saves to JS object
		# First, retrieve all data for this marker
		$("#loader").show()
		deferred = new $.Deferred()
		$.ajax
			url: myBaseUrl + "listings/GetOwnedListingsByMarkerId/" + marker_id
			type: "GET"
			success: (response) =>
				response = JSON.parse response
				for item in response
					for key, value of item
						if A2Cribs[key]?
							A2Cribs.UserCache.Set new A2Cribs[key] value
						else if A2Cribs[key]? and value.length? # Is an array
							for i in value
								A2Cribs.UserCache.Set new A2Cribs[key] i

				@ClearGrids()

				@CurrentMarker = marker_id
				@CreateListingPreview marker_id
				
				A2Cribs.Dashboard.ShowContent $(".rentals-content"), true

				@PopulateGrid marker_id
				deferred.resolve()
				$("#loader").hide()

		return deferred.promise()

	CreateListingPreview: (marker_id) ->
		marker_object = A2Cribs.UserCache.Get "marker", marker_id
		name = marker_object.GetName()
		$("#rentals_address").html "<strong>#{name}</strong><br>"
		if not @Map?
			@Map = new A2Cribs.MiniMap $("#rentals_preview")
		if marker_object.latitude? and marker_object.longitude?
			@Map.SetMarkerPosition new google.maps.LatLng marker_object.latitude, marker_object.longitude

	Validate: (row) ->
		required = A2Cribs.Rental.Required_Fields
		data = @GridMap[@VisibleGrid].getDataItem row
		highlighted_tabs = {}

		isValid = yes
		for key, tab of required
			if not data[key]?
				isValid = no
				highlighted_tabs[tab] = yes

		$(".rentals_tab").removeClass "highlight-tab"
		for tab, value of highlighted_tabs
			$("a[data-target='##{tab}']").addClass "highlight-tab"
		$("a[data-target='##{@VisibleGrid}']").removeClass "highlight-tab"

		return isValid

	GetObjectByRow: (row) ->
		data = @GridMap[@VisibleGrid].getDataItem row

		if data.listing_id?
			image_object = A2Cribs.UserCache.Get("image", data.listing_id)?.GetObject()
		if not image_object?
			image_object = []

		rental_object = {
			Rental: data
			Listing: if data.listing_id? then A2Cribs.UserCache.Get("listing", data.listing_id).GetObject()
			Image: image_object
		}
		if not rental_object.Listing?
			rental_object.Listing = {
				listing_type: 0
				marker_id: @CurrentMarker
			}

		rental_object.Listing.available = data.available
		
		if rental_object.Image?.length is 0 and data.Image?
			rental_object.Image = data.Image

		return rental_object

	# Sends rental to server including all associated tables (fees, etc.)
	Save: (row) ->
		if @Validate row
			rental_object = @GetObjectByRow row
			A2Cribs.MixPanel.PostListing "Listing Save",
				"save type": if rental_object.listing_id? then "edit" else "save"
				"marker id": @CurrentMarker
				"listing id": rental_object.listing_id
			$("#loader").show()
			$.ajax
				url: myBaseUrl + "listings/Save/"
				type: "POST"
				data: rental_object
				success: (response) =>
					response = JSON.parse response
					if response.listing_id?
						A2Cribs.MixPanel.PostListing "Listing Save Completed",
							"listing id": response.listing_id
							"marker id": @CurrentMarker
						A2Cribs.UIManager.Success "Save successful!"
						rental_object.Listing.listing_id = response.listing_id
						rental_object.Rental.listing_id = response.listing_id
						for image in rental_object.Image
							image.listing_id = response.listing_id
						for key, value of rental_object
							if A2Cribs[key]?
								A2Cribs.UserCache.Set new A2Cribs[key] value
						console.log response
					else
						A2Cribs.UIManager.Error response.error.message
						console.log response

					$("#loader").hide()

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
	Grabs all the images based on a row and loads them into A2Cribs.PhotoManager
	###
	LoadImages: (row) ->
		data = @GridMap[@VisibleGrid].getDataItem row
		if data.listing_id?
			image_array = A2Cribs.UserCache.Get("image", data.listing_id)?.GetImages()
		else
			image_array = data.Image

		A2Cribs.MixPanel.PostListing "Start Photo Editing",
			"marker id": @CurrentMarker
			"number of images": image_array?.length
		A2Cribs.PhotoManager.LoadImages image_array, row, @SaveImages


	###
	Saves the images in either the cache or temp object in slickgrid
	###
	SaveImages: (row, images) =>
		data = @GridMap[@VisibleGrid].getDataItem row
		if data.listing_id? # If the listing has been saved already cache it
			for image in images
				image.listing_id = data.listing_id
			A2Cribs.UserCache.Set new A2Cribs.Image images
		else
			data.Image = images

		@Save row

	###
	Called when user adds a new row for the existing marker
	Adds a new row to the grid, with a new row_id.
	Sets the row_id hidden field.
	###
	AddNewUnit: ->
		# Create newline on grid
		A2Cribs.MixPanel.PostListing "Add New Unit",
			"marker id": @CurrentMarker

		data = @GridMap[@VisibleGrid].getData()

		row_number = data.length
		@EditableRows.push row_number
		data.push { 
			editable: true 
			contact_email: @user_email
			contact_phone: @user_phone
			unit_style_description: row_number + 1
		}
		@GridMap[@VisibleGrid].setSelectedRows @EditableRows
		$("#rentals_edit").text "Finish Editing"
		@Editable = true

		@Validate row_number

		for container,grid of @GridMap
			grid.updateRowCount()
			grid.render()

	PopulateGrid: (marker_id) ->
		# Pre-populate grid based on selected marker
		@GridMap[@VisibleGrid].getSelectionModel().setSelectedRanges([])
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

	CreateGrids: (dropdown_content) ->
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
			columns = @GetColumns container, dropdown_content

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
					return true
				return false

			@GridMap[container].onCellChange.subscribe (e, args) =>
				@Save args.row

			@GridMap[container].onValidationError.subscribe (e, args) =>
				A2Cribs.UIManager.CloseLogs()
				A2Cribs.UIManager.Error args.validationResults.msg

	GetColumns: (container, dropdown_content) ->
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
					headerCssClass: "slickgrid_header"
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
					formatter: A2Cribs.Formatters.Date true
				}
				{
					id: "alternate_start_date"
					name: "Alt. Start Date"
					field: "alternate_start_date"
					editor: Slick.Editors.Date 
					formatter: A2Cribs.Formatters.Date()
				}
				{
					id: "lease_length"
					name: "Lease Length"
					field: "lease_length"
					editor: A2Cribs.Editors.Dropdown([null, "1 month", "2 months", "3 months", "4 months", "5 months", "6 months", "7 months", "8 months", "9 months", "10 months", "11 months", "12 months"])
					formatter: A2Cribs.Formatters.Dropdown(["", "1 month", "2 months", "3 months", "4 months", "5 months", "6 months", "7 months", "8 months", "9 months", "10 months", "11 months", "12 months"], true)
				}
				{
					id: "available"
					name: "Availability"
					field: "available"
					editor: A2Cribs.Editors.Dropdown(["Leased", "Available"])
					formatter: A2Cribs.Formatters.Dropdown(["Leased", "Available"], true)
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
					minWidth: 185
				}
				{
					id: "baths"
					name: "Baths"
					field: "baths"
					editor: A2Cribs.Editors.Float
					formatter: A2Cribs.Formatters.RequiredText
				}
				{
					id: "parking_type"
					name: "Parking"
					field: "parking_type"
					editor: A2Cribs.Editors.Dropdown(dropdown_content["parking"]),
					formatter: A2Cribs.Formatters.Dropdown(dropdown_content["parking"]),
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
					editor: A2Cribs.Editors.Dropdown(dropdown_content["furnished"])
					formatter: A2Cribs.Formatters.Dropdown(dropdown_content["furnished"])
				}
				{
					id: "pets_type"
					name: "Pets"
					field: "pets_type"
					editor: A2Cribs.Editors.Dropdown(dropdown_content["pets"])
					formatter: A2Cribs.Formatters.Dropdown(dropdown_content["pets"])
				}
				{
					id: "smoking"
					name: "Smoking"
					field: "smoking"
					editor: A2Cribs.Editors.Dropdown(["Prohibited", "Allowed"])
					formatter: A2Cribs.Formatters.Dropdown(["Prohibited", "Allowed"])
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
					editor: A2Cribs.Editors.Year
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
					id: "washer_dryer"
					name: "Washer/Dryer"
					field: "washer_dryer"
					editor: A2Cribs.Editors.Dropdown(dropdown_content["washer_dryer"])
					formatter: A2Cribs.Formatters.Dropdown(dropdown_content["washer_dryer"])
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
					editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"])
					formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
				}
				{
					id: "water"
					name: "Water"
					field: "water"
					editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"])
					formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
				}
				{
					id: "gas"
					name: "Gas"
					field: "gas"
					editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"])
					formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
				}
				{
					id: "heat"
					name: "Heat"
					field: "heat"
					editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"])
					formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
				}
				{
					id: "trash"
					name: "Trash"
					field: "trash"
					editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"])
					formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
				}
				{
					id: "cable"
					name: "Cable"
					field: "cable"
					editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"])
					formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
				}
				{
					id: "internet"
					name: "Internet"
					field: "internet"
					editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"])
					formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
				}
				{
					id: "utility_total_flat_rate"
					name: "Total Flat Rate"
					field: "utility_total_flat_rate"
					editor: Slick.Editors.Integer
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
					editor: A2Cribs.Editors.LongText 160, "Highlights"
					formatter: A2Cribs.Formatters.Text
				}
				{
					id: "description"
					name: "Description"
					field: "description"
					editor: A2Cribs.Editors.LongText 1000, "Description"
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
					name: "Waitlist Open Date"
					field: "waitlist_open_date"
					editor: Slick.Editors.Date 
					formatter: A2Cribs.Formatters.Date()
				}
				{
					id: "lease_office_address"
					name: "Leasing Office Address"
					field: "lease_office_address"
					editor: Slick.Editors.Text
					formatter: A2Cribs.Formatters.Text
				}
				{
					id: "contact_email"
					name: "Contact Email"
					field: "contact_email"
					editor: A2Cribs.Editors.Email
					formatter: A2Cribs.Formatters.RequiredText
				}
				{
					id: "contact_phone"
					name: "Contact Phone"
					field: "contact_phone"
					editor: A2Cribs.Editors.Phone
					formatter: A2Cribs.Formatters.RequiredText
				}
				{
					id: "website"
					name: "Website"
					field: "website"
					editor: Slick.Editors.Text
					formatter: A2Cribs.Formatters.Text
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
