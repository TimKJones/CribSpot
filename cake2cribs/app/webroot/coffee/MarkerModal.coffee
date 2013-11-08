class A2Cribs.MarkerModal	

	###
	Clear
	Removes all the values in input fields and resets
	to the first part of selecting a marker
	###
	@Clear: ->
		@modal.find("#marker_select_container").show()
		@modal.find("input").val ""
		@modal.find('select option:first-child').attr "selected", "selected" # all dropdowns to first option
		@MiniMap.SetMarkerVisible no

	###
	Marker Validate
	Iterates through the address fields and makes sure everything
	is completed and checks to make sure the text fields are not
	too long
	###
	@MarkerValidate: ->
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
			A2Cribs.UIManager.Error "Your building name is too long."
			@modal.find('#Marker_alternate_name').parent().addClass "error"
			isValid = no
		return isValid

	###

	###
	@Save: (trigger) ->
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
			A2Cribs.MixPanel.PostListing "Marker Save", marker_object

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
						A2Cribs.MixPanel.PostListing "Marker Save Complete",
							"marker id": marker_object.marker_id
						A2Cribs.UserCache.Set new A2Cribs.Marker marker_object
						trigger marker_object.marker_id

	@SetupUI: ->
		@modal.on 'shown', () =>
			@MiniMap.Resize()

		@modal.find(".required").keydown ->
			$(this).parent().removeClass "error"

		@modal.find("#place_map_button").click () =>
			marker_selected = @modal.find("#marker_select").val()
			A2Cribs.MixPanel.PostListing "Marker Selected", 
					"new marker": false
					"marker_id": marker_selected
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
				A2Cribs.MixPanel.PostListing "Marker Selected", 
					"new marker": true
				@Save()

			else if marker_selected isnt "0"
				marker = A2Cribs.UserCache.Get "marker", marker_selected
				A2Cribs.MixPanel.PostListing "Marker Selected", 
					"new marker": false
					"marker id": marker_selected
					"marker name": marker?.GetName()
					"marker address": marker?.street_address
					"marker city": marker?.city
					"marker state": marker?.state
				@modal.modal "hide"
				@TriggerMarkerAdded marker_selected

		@MiniMap = new A2Cribs.MiniMap @modal

	@Open: (listing_type, marker_id = null) ->
		if listing_type? then @ListingType = listing_type

		if not marker_id? then @NewMarker()

		@modal.modal 'show'

	@NewMarker: () ->
		@Clear()
		@modal.find('#marker_add').hide()
		@modal.find("#continue-button").addClass("disabled").text "Continue"
		@modal.find(".title").text "Create a New #{@ListingType.charAt(0).toUpperCase() + @ListingType.slice(1)}"

		markers = A2Cribs.UserCache.Get "marker"
		
		@modal.find("#marker_select").empty().append(
			'<option value="0">--</option>
			<option value="new_marker"><strong>New Location</strong></option>')

		@modal.find("#continue-button").unbind('click').click () =>
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

	@LoadMarker: (marker_id) ->
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
		latLng = new google.maps.LatLng @modal.find("#Marker_latitude").val(), @modal.find("#Marker_longitude").val()
		@MiniMap.SetMarkerPosition latLng

	@TriggerMarkerAdded: (marker_id) =>
		$("##{@ListingType}_list_content").trigger "marker_added", [marker_id]

	@TriggerMarkerUpdated: (marker_id) =>
		$('body').trigger "#{@ListingType}_marker_updated", [marker_id]		

	@FindAddress: (div) ->
		if @MarkerValidate()
			addressObj = 
				address: div.find("#Marker_street_address").val() + " " + 
					div.find("#Marker_city").val() + ", " + div.find("#Marker_state").val()
			A2Cribs.Geocoder.FindAddress(div.find("#Marker_street_address").val(), div.find("#Marker_city").val(), div.find("#Marker_state").val())
			.done (response) =>
				[street_address, city, state, zip, location] = response
				@MiniMap.SetMarkerPosition location
				div.find("#Marker_street_address").val street_address
				div.find("#Marker_latitude").val location.lat()
				div.find("#Marker_longitude").val location.lng()
				div.find('#Marker_city').val city
				div.find('#Marker_state').val state
				div.find('#Marker_zip').val zip
			.fail () =>
				A2Cribs.UIManager.Alert "Entered street address is not valid."
				$("#Marker_street_address").text ""

	$('#marker-modal').ready () =>
		@modal = $('#marker-modal')
		@SetupUI()
