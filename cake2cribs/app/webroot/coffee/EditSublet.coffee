class A2Cribs.EditSublet extends A2Cribs.SubletSave
	constructor: () ->
		@div = $('#edit_sublet_window')
		@setupUI()

	setupUI: () ->
		@div.find(".step-button").click (event) =>
			@div.find(".step-button").removeClass "active"
			$(event.currentTarget).closest(".step-button").addClass "active"
			@GotoStep $(event.currentTarget).closest(".step-button").attr("step")
		super @div

	Reset: () ->
		@div.find('.step').eq(0).show().siblings().hide()
		@div.find(".step-button").removeClass "active"
		@div.find('.step-button').eq(0).addClass "active"
		super @div

	Edit: (sublet_id) ->
		$.ajax
			url: myBaseUrl + "Sublets/getSubletDataById/" + sublet_id
			type: "GET"
			success: (subletData) =>
				@Close()
				subletData = JSON.parse subletData
				if subletData.redirect? then window.location = subletData.redirect
				@MiniMap.SetMarkerPosition new google.maps.LatLng subletData.Marker.latitude, subletData.Marker.longitude
				@PopulateInputFields subletData
				@PhotoManager.LoadImages subletData.Image
				@DisableInputFields()
				@Open()

			error: ()=>
				A2Cribs.UIManager.Alert  "An error occured while loading your sublet data, please try again."

	Save: () ->
		if @Validate()
			super @GetSubletObject()

	GetSubletObject: () ->
		super @div

	Close: () ->
		@Reset()
		@div.parent().hide()

	Open: () ->
		@div.parent().show 'slow', =>
			@MiniMap.Resize()

	PopulateInputFields: (subletData) ->
		for k,v of subletData
			for p,q of v
				console.log k + "_" + p
				input = @div.find("#" + k + "_" + p)
				# If input exists
				if input?
					# Fill in value
					if "checkbox" is input.attr "type" 
						input.prop "checked", q
					else if input.hasClass "date_field"
						# Convert to acceptable date
						input.val @GetFormattedDate new Date q
					else if typeof q is 'boolean'
					# Booleans need to be converted to integers for value of select
						input.val +q
					else
						input.val q

					# Disable Marker values
					if k is "Marker"
						input.prop 'disabled', true

	DisableInputFields: () ->
		# Disable Map
		@MiniMap.SetEnabled false

		# Disable Map Button
		@div.find('#place_map_button').prop 'disabled', true
		
		# Disable University Field
		@div.find('#University_name').prop 'disabled', true

	GotoStep: (step) ->
		if @Validate()
			@div.find('.step').eq(step).show().siblings().hide()

	Validate: () ->
		super 3

