class A2Cribs.PostSublet extends A2Cribs.SubletSave
	constructor: () ->
		@div = $('#post-sublet-modal')
		@currentStep = 0
		### INIT STEPS ###
		@setupUI()

	setupUI: () ->
		@ProgressBar =  new A2Cribs.PostSubletProgress $('.post-sublet-progress'), @currentStep
		@div.find("#address-step").siblings().hide();

		@div.find(".next-btn").click (event)=>
			if @Validate @currentStep + 1
				$(event.currentTarget).closest(".step").hide().next(".step").show()
				@currentStep++
				@ProgressBar.next()
		
		
		@div.find(".back-btn").click (event)=>
			$(event.currentTarget).closest(".step").hide().prev(".step").show()
			@currentStep--
			@ProgressBar.prev()

		@div.on "shown", () =>
			@MiniMap.Resize()

		@div.find("#University_name").focusout () =>
			@FindSelectedUniversity @div
			if @SelectedUniversity?
				@MiniMap.CenterMap @SelectedUniversity.latitude, @SelectedUniversity.longitude

		@div.find(".post-btn").click =>
			@Save()

		@InitUniversityAutocomplete()

		super @div

	Reset: () ->
		# Reset Progress Bar
		@ProgressBar.reset()

		# Set Current Step to first
		@div.find('.step').eq(0).show()
		@div.find('.step').eq(0).siblings().hide()
		@currentStep = 0
		super @div

	Save: () ->
		if @Validate()
			super @GetSubletObject(), @SaveRedirect

	SaveRedirect: (new_id) ->
		window.location.replace "/sublet/" + new_id

	Validate: (step_ = 3) ->
		super step_, @div


	GetSubletObject: ->
		super @div

	InitUniversityAutocomplete: () ->
		if A2Cribs.Cache.SchoolList?
			@div.find("#University_name").typeahead
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
				@div.find("#University_name").typeahead
					source: A2Cribs.Cache.SchoolList

	FindAddress: () ->
		if @SelectedUniversity?
			address = @div.find("#Marker_street_address").val()
			addressObj =
				'address' : address + " " + @SelectedUniversity.city + ", " + @SelectedUniversity.state
			A2Cribs.Geocoder.geocode addressObj, (response, status) =>
				if status is google.maps.GeocoderStatus.OK and response[0].address_components.length >= 2
					for component in response[0].address_components
						for type in component.types
							switch type
								when "street_number" then street_number = component.short_name
								when "route" then street_name = component.short_name
								when "locality" then @div.find('#Marker_city').val component.short_name
								when "administrative_area_level_1" then @div.find('#Marker_state').val component.short_name
								when "postal_code" then @div.find('#Marker_zip').val component.short_name

					if not street_number?
						A2Cribs.UIManager.Alert "Entered street address is not valid."
						$("#Marker_street_address").text ""
						return
					
					@MiniMap.SetMarkerPosition response[0].geometry.location
					@div.find("#Marker_street_address").val street_number + " " + street_name
					@div.find("#Marker_latitude").val response[0].geometry.location.lat()
					@div.find("#Marker_longitude").val response[0].geometry.location.lng()

	FindSelectedUniversity: ()->
		selected = @div.find("#University_name").val()
		index = A2Cribs.Cache.SchoolList.indexOf selected
		if index >= 0
			@SelectedUniversity = A2Cribs.Cache.universitiesMap[index].University;
			@div.find("#Sublet_university_id").val(A2Cribs.Cache.SchoolIDList[index])
		else
			@SelectedUniversity = null