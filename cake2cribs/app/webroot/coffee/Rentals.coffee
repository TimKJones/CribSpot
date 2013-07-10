class A2Cribs.Rentals
	SetupUI: ->
		###
		********************* TODO **********************
		###
		@CreateGrids()
		# Create grid and setup necessary grid code
		# Create jquery listeners for buttons on Rentals layout

	Open: (rental_ids) ->
		###
		********************* TODO **********************
		###
		# Gets rental info and saves to JS object

	Save: ->
		###
		********************* TODO **********************
		###
		# Sends array of rentals to backend

	Copy: (rental_ids) ->
		###
		********************* TODO (Not first priority) *
		###
		# Create new on backend
		# Update grid

	Delete: (rental_ids) ->
		###
		********************* TODO **********************
		###
		# Update backend and grid

	Create: ->
		###
		********************* TODO **********************
		###
		# Create newline on grid

	PopulateGrid: (rental_ids) ->
		###
		********************* TODO **********************
		###
		# Pre-populate grid based on selected address

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
		OverviewColumns = ->
			columns = [
				{
					# Use for Checkbox
				}
				{
					id: "title"
					name: "Unit/Style - Name"
					field: "title"
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
					###
					formatter: (row, cell, value, columnDef, dataContext) ->
						return "#{dataContext.from} - #{dataContext.to}"
					editor: (args) ->
						scope = @
						$from = $to = null
						@init = ->
							$from = $("<INPUT type=text style='width:40px' />")
								.appendTo(args.container)
								.bind("keydown", scope.handleKeyDown)

							$(args.container).append "&nbsp; to &nbsp;"

							$to = $("<INPUT type=text style='width:40px' />")
								.appendTo(args.container)
								.bind("keydown", scope.handleKeyDown)

							scope.focus()

						@handleKeyDown = (e) ->
							if e.keyCode is $.ui.keyCode.LEFT or e.keyCode is $.ui.keyCode.RIGHT or e.keyCode is $.ui.keyCode.TAB
								e.stopImmediatePropagation()

						@destroy = ->
							$(args.container).empty()

						@focus = ->
							$from.focus()

						@serializeValue = ->
							from: parseInt $from.val(), 10
							to: parseInt $to.val(), 10

						@applyValue = (item, state) ->
							item.from = state.from
							item.to = state.to

						@loadValue = (item) ->
							$from.val item.from
							$to.val item.to

						@isValueChanged = ->
							return args.item.from != parseInt $from.val(), 10 || args.item.to != parseInt $from.val(), 10

						@validate = ->
							if isNaN(parseInt($from.val(), 10)) || isNaN(parseInt($to.val(), 10))) {
							return {valid: false, msg: "Please type in valid numbers."};
							}

							if (parseInt($from.val(), 10) > parseInt($to.val(), 10)) {
							return {valid: false, msg: "'from' cannot be greater than 'to'"};
							}

							return {valid: true, msg: null};
							};

						@init()
					###
				}
				{
					id: "rent"
					name: "Total Rent"
					field: "rent"
				}
				{
					id: "start_date"
					name: "Start Date"
					field: "start_date"
				}
				{
					id: "alt_start_date"
					name: "Alt. Start Date"
					field: "alt_start_date"
				}
				{
					id: "lease_length"
					name: "Lease Length"
					field: "lease_length"
				}
				{
					id: "availability"
					name: "Availability"
					field: "availability"
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
				}
				{
					id: "baths"
					name: "Baths"
					field: "baths"
				}
				{
					id: "occupancy"
					name: "A/C"
					field: "occupancy"
				}
				{
					id: "rent"
					name: "Parking"
					field: "rent"
				}
				{
					id: "start_date"
					name: "Spots"
					field: "start_date"
				}
				{
					id: "alt_start_date"
					name: "Furnished"
					field: "alt_start_date"
				}
				{
					id: "lease_length"
					name: "Pets"
					field: "lease_length"
				}
				{
					id: "availability"
					name: "Smoking"
					field: "availability"
				}
				{
					id: "sq_feet"
					name: "SQ Feet"
					field: "sq_feet"
				}
				{
					id: "year_built"
					name: "Year Built"
					field: "year_built"
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
				}
				{
					id: "beds"
					name: "Electricity"
					field: "beds"
				}
				{
					id: "occupancy"
					name: "Water"
					field: "occupancy"
				}
				{
					id: "rent"
					name: "Gas"
					field: "rent"
				}
				{
					id: "start_date"
					name: "Heat"
					field: "start_date"
				}
				{
					id: "alt_start_date"
					name: "Sewage"
					field: "alt_start_date"
				}
				{
					id: "lease_length"
					name: "Trash"
					field: "lease_length"
				}
				{
					id: "availability"
					name: "Cable"
					field: "availability"
				}
				{
					id: "internet"
					name: "Internet"
					field: "internet"
				}
				{
					id: "flat_rate"
					name: "Total Flat Rate"
					field: "flat_rate"
				}
				{
					id: "winter_cost"
					name: "Est. Winter Utility Cost"
					field: "winter_cost"
				}
				{
					id: "summer_cost"
					name: "Est. Summer Utility Cost"
					field: "summer_cost"
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
				}
				{
					id: "beds"
					name: "Deposit"
					field: "beds"
				}
				{
					id: "occupancy"
					name: "Admin"
					field: "occupancy"
				}
				{
					id: "rent"
					name: "Parking"
					field: "rent"
				}
				{
					id: "start_date"
					name: "Furniture"
					field: "start_date"
				}
				{
					id: "alt_start_date"
					name: "Pets"
					field: "alt_start_date"
				}
				{
					id: "lease_length"
					name: "Amenity"
					field: "lease_length"
				}
				{
					id: "availability"
					name: "Upper Floor"
					field: "availability"
				}
				{
					id: "extra_occupant"
					name: "Cost for Extra Occupant"
					field: "extra_occupant"
				}
				{
					id: "other_fee_description"
					name: "Other Fees"
					field: "other_fee_description"
				}
				{
					id: "other_fee_cost"
					name: "Fee"
					field: "other_fee_cost"
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
				}
				{
					id: "beds"
					name: "Highlights"
					field: "beds"
				}
				{
					id: "occupancy"
					name: "Description"
					field: "occupancy"
				}
			]

		switch container
			when "overview_grid" then OverviewColumns()
			when "features_grid" then FeaturesColumns()
			when "amenities_grid" then AmenitiesColumns()
			when "utilites_grid" then UtilitiesColumns()
			when "fees_grid" then FeesColumns()
			when "description_grid" then DescriptionColumns()
