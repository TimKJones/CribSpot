class A2Cribs.MarkerModal	
	constructor: ->
		@modal = $('#marker-modal')
		@setupUI()

		# For now this is hard-coded
		# In the future you will choose Sublet, Rental, or Parking
		@ListingType = "Rental"

	Clear: ->
		@modal.find("#marker_select_container").show()
		@modal.find("input").val ""
		@modal.find('select option:first-child').attr "selected", "selected" # all dropdowns to first option
		@MiniMap.SetMarkerVisible no

	MarkerValidate: ->
		isValid = yes
		addressFields = ["street_address", "city", "state"]
		addressOK = yes
		for field in addressFields
			if not @modal.find("#Marker_#{field}").val()? or @modal.find("#Marker_#{field}").val().length is 0
				@modal.find("#Marker_#{field}").parent().addClass "error"
				addressOK = no
		if not addressOK
			A2Cribs.UIManager.Error "Fill in the full address please."
			isValid = no
		if @modal.find('#Marker_building_type_id').val().length is 0
			A2Cribs.UIManager.Error "You need to select a building type."
			@modal.find('#Marker_building_type_id').parent().addClass "error"
			isValid = no
		if @modal.find('#Marker_alternate_name').val().length >= 249
			A2Cribs.UIManager.Error "Your alternate name is too long."
			@modal.find('#Marker_alternate_name').parent().addClass "error"
			isValid = no
		return isValid

	Save: (trigger) ->
		if @MarkerValidate()
			if not @modal.find('#Marker_latitude').val()
				A2Cribs.UIManager.Error "Please place your street address on the map using the Place On Map button."
				return
			marker_id = @modal.find("#Marker_marker_id").val()
			latLng = @MiniMap.GetMarkerPosition()
			marker_object = {
				alternate_name: @modal.find('#Marker_alternate_name').val()
				building_type_id: @modal.find('#Marker_building_type_id').val()
				street_address: @modal.find('#Marker_street_address').val()
				city: @modal.find('#Marker_city').val()
				state: @modal.find('#Marker_state').val()
				zip: @modal.find('#Marker_zip').val()
				latitude: latLng['latitude']
				longitude: latLng['longitude']
			}
			if marker_id?.length isnt 0
				marker_object.marker_id = marker_id
			$.ajax
				url: "/Markers/Save/"
				type: "POST"
				data: marker_object
				success :(response) =>
					if response.error
						UIManager.Error response.error
					else
						@modal.modal "hide"
						marker_object.marker_id = response
						A2Cribs.UserCache.Set new A2Cribs.Marker marker_object
						trigger marker_object.marker_id

	setupUI: ->
		@modal.on 'shown', () =>
			@MiniMap.Resize()

		@modal.find(".required").keydown ->
			$(this).parent().removeClass "error"

		@modal.find("#place_map_button").click () =>
			@FindAddress @modal

		@modal.find("#marker_select").change () =>
			marker_selected = @modal.find("#marker_select").val()
			if marker_selected is "0"
				@modal.find("#continue-button").addClass "disabled"
			else
				@modal.find("#continue-button").removeClass "disabled"

			if marker_selected is "new_marker"
				@modal.find('#marker_add').show()
				@MiniMap.Resize()
			else
				@modal.find('#marker_add').hide()

		@modal.find("#continue-button").click () =>
			marker_selected = @modal.find("#marker_select").val()
			if marker_selected is "new_marker"
				@Save()

			else if marker_selected isnt "0"
				@modal.modal "hide"
				@TriggerMarkerAdded marker_selected

		@MiniMap = new A2Cribs.MiniMap @modal

	Open: ->
		@modal.modal 'show'

	NewMarker: () ->
		@Clear()
		@modal.find('#marker_add').hide()
		@modal.find("#continue-button").addClass "disabled"
		@modal.find("#continue-button").text "Continue"
		@modal.find(".title").text "Create a New Listing"
		markers = A2Cribs.UserCache.Get "marker"
		@modal.find("#marker_select").empty()
		@modal.find("#marker_select").append(
			'<option value="0">--</option>
			<option value="new_marker"><strong>New Location</strong></option>')
		@modal.find("#continue-button").unbind 'click'
		@modal.find("#continue-button").click () =>
			marker_selected = @modal.find("#marker_select").val()
			if marker_selected is "new_marker"
				@Save @TriggerMarkerAdded
			else if marker_selected isnt "0"
				@modal.modal "hide"
				@TriggerMarkerAdded marker_selected

		if markers?
			for marker in markers
				name = if marker.alternate_name? and marker.alternate_name.length then marker.alternate_name else marker.street_address
				option = $ "<option />",
					{
						text: name
						value: marker.marker_id
					}
				@modal.find("#marker_select").append option

		@modal.find("#marker_select").val "0"

	LoadMarker: (marker_id) ->
		@Clear()
		@modal.find('#marker_add').show()
		@modal.find("#marker_select_container").hide()
		marker = A2Cribs.UserCache.Get "marker", marker_id
		@modal.find("#continue-button").removeClass "disabled"
		@modal.find("#continue-button").text "Save"
		@modal.find(".title").text "Edit Listing Address"
		@modal.find("#marker_select").val "new_marker"
		for key, value of marker
			@modal.find("#Marker_#{key}").val value
		@modal.find("#continue-button").unbind 'click'
		@modal.find("#continue-button").click () =>
			@Save @TriggerMarkerUpdated
		@FindAddress @modal

	TriggerMarkerAdded: (marker_id) =>
		$('body').trigger "#{@ListingType}_marker_added", [marker_id]

	TriggerMarkerUpdated: (marker_id) =>
		$('body').trigger "#{@ListingType}_marker_updated", [marker_id]		

	FindAddress: (div) ->
		if @MarkerValidate()
			if div.find("#Marker_latitude").val() and div.find("#Marker_longitude").val()
				latLng = new google.maps.LatLng div.find("#Marker_latitude").val(), div.find("#Marker_longitude").val()
				@MiniMap.SetMarkerPosition latLng
				return
			addressObj = 
				address: div.find("#Marker_street_address").val() + " " + 
					div.find("#Marker_city").val() + ", " + div.find("#Marker_state").val()
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
