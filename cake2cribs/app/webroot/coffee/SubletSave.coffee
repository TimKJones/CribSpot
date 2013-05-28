class A2Cribs.SubletSave

	setupUI: (div) ->
		if not A2Cribs.Geocoder?
			A2Cribs.Geocoder = new google.maps.Geocoder()
		@div = div
		@InitUniversityAutocomplete div

		div.find("#SubletShortDescription").keyup ()->
			if $(@).val().length >= 160
				$(@).val($(@).val().substr(0, 160))
			
			div.find("#desc-char-left").text(160 - $(@).val().length)

		div.find("#SubletDateBegin").datepicker();

		div.find("#SubletDateEnd").datepicker();

		div.find("#universityName").focusout () =>
			@FindSelectedUniversity div
			@MiniMap.CenterMap @SelectedUniversity.latitude, @SelectedUniversity.longitude

		div.on "shown", () =>
			@MiniMap.Resize()

		@MiniMap = new A2Cribs.MiniMap(div)

		A2Cribs.Map.LoadTypeTables()
		A2Cribs.PhotoManager.SetupUI()


	FindSelectedUniversity: (div)->
		selected = div.find("#universityName").val()
		index = A2Cribs.Cache.SchoolList.indexOf selected
		if index >= 0
			@SelectedUniversity = A2Cribs.Cache.universitiesMap[index].University;
			div.find("#universityId").val(A2Cribs.Cache.SchoolIDList[index])

	FindAddress: () ->
		if @SelectedUniversity?
			address = @div.find("#formattedAddress").val()
			addressObj =
				'address' : address + " " + @SelectedUniversity.city + ", " + @SelectedUniversity.state
			A2Cribs.Geocoder.geocode addressObj, (response, status) =>
				if status is google.maps.GeocoderStatus.OK and response[0].address_components.length >= 2
					for component in response[0].address_components
						for type in component.types
							switch type
								when "street_number" then street_number = component.short_name
								when "route" then street_name = component.short_name
								when "locality" then @div.find('#city').val component.short_name
								when "administrative_area_level_1" then @div.find('#state').val component.short_name
								when "postal_code" then @div.find('#zip').val component.short_name

					if not street_number?
						A2Cribs.UIManager.Alert "Entered street address is not valid."
						$("#formattedAddress").text ""
						return
					
					@MiniMap.SetMarkerPosition response[0].geometry.location
					@div.find("#formattedAddress").val street_number + " " + street_name

	###
	Called before advancing steps
	Returns true if validations pass; false otherwise
	###
	Validate: (step_, div) ->
		if step_ >= 1
			if not @ValidateStep1 div
				return false
		if step_ >= 2
			if not @ValidateStep2 div
				return false
		if step_ >= 3
			if not @ValidateStep3 div
				return false

		return true


	ValidateStep1: (div) ->
		isValid = yes
		A2Cribs.UIManager.CloseLogs()
		if not div.find('#formattedAddress').val()
			A2Cribs.UIManager.Error "Please place your street address on the map using the Place On Map button."
			div.find('#formattedAddress').parent().addClass "error"
			isValid = no
		if not div.find('#universityName').val()
			A2Cribs.UIManager.Error "You need to select a university."
			div.find('#universityName').parent().addClass "error"
			isValid = no
		if div.find('#buildingType').val().length is 0
			A2Cribs.UIManager.Error "You need to select a building type."
			div.find('#buildingType').parent().addClass "error"
			isValid = no
		if div.find('#SubletUnitNumber').val().length >= 249
			A2Cribs.UIManager.Error "Your unit number is too long."
			div.find('#SubletUnitNumber').parent().addClass "error"
			isValid = no
		if div.find('#SubletName').val().length >= 249
			A2Cribs.UIManager.Error "Your alternate name is too long."
			div.find('#SubletName').parent().addClass "error"
			isValid = no
		
		return isValid

	ValidateStep2: (div) ->
		#begin the validations
		isValid = yes
		A2Cribs.UIManager.CloseLogs()
		parsedBeginDate = new Date Date.parse(div.find('#SubletDateBegin').val())
		parsedEndDate = new Date Date.parse(div.find('#SubletDateEnd').val())
		todayDate = new Date();
		if parsedBeginDate.toString() == "Invalid Date" or parsedEndDate.toString() == "Invalid Date"
			A2Cribs.UIManager.Error "Please enter a valid date."
			div.find('#SubletDateBegin').parent().addClass "error"
			div.find('#SubletDateEnd').parent().addClass "error"
			isValid = no
		else if parsedEndDate.valueOf() <= parsedBeginDate.valueOf() or parsedBeginDate.valueOf() <= todayDate.valueOf()
			A2Cribs.UIManager.Error "Please enter a valid date."
			div.find('#SubletDateBegin').parent().addClass "error"
			div.find('#SubletDateEnd').parent().addClass "error"
			isValid = no
		if (!div.find('#SubletNumberBedrooms').val() || isNaN(parseInt(div.find("#SubletNumberBedrooms").val())) || div.find('#SubletNumberBedrooms').val() <=0 || div.find('#SubletNumberBedrooms').val() >=30)
			A2Cribs.UIManager.Error "Please enter a valid number of bedrooms."
			div.find('#SubletNumberBedrooms').parent().addClass "error"
			isValid = no
		if (!div.find('#SubletPricePerBedroom').val() || isNaN(parseInt(div.find("#SubletPricePerBedroom").val())) || div.find('#SubletPricePerBedroom').val() < 1 || div.find('#SubletPricePerBedroom').val() >=20000)
			A2Cribs.UIManager.Error "Please enter a valid price per bedroom."
			div.find('#SubletPricePerBedroom').parent().parent().addClass "error"
			isValid = no
		if div.find('#SubletShortDescription').val().length is 0 
			A2Cribs.UIManager.Error "Please enter a description."
			div.find('#SubletShortDescription').parent().addClass "error"
			isValid = no
		if (!div.find('#SubletUtilityCost').val()|| isNaN(parseInt(div.find("#SubletUtilityCost").val())) || div.find('#SubletUtilityCost').val()<0 || div.find('#SubletUtilityCost').val() >=50000)
			A2Cribs.UIManager.Error "Please enter a valid utility cost."
			div.find('#SubletUtilityCost').parent().addClass "error"
			isValid = no
		if (!div.find('#SubletDepositAmount').val() || isNaN(parseInt(div.find("#SubletDepositAmount").val())) || div.find('#SubletDepositAmount').val()<0 || div.find('#SubletDepositAmount').val() >=50000)
			A2Cribs.UIManager.Error "Please enter a valid deposit amount."
			div.find('#SubletDepositAmount').parent().parent().addClass "error"
			isValid = no
		descLength = div.find('#SubletAdditionalFeesDescription').val().length
		if (descLength >=161)
			A2Cribs.UIManager.Error "Please keep the additional fees description under 160 characters."
			div.find('#SubletAdditionalFeesDescription').parent().addClass "error"
			isValid = no
		if descLength > 0 
			if (!div.find('#SubletAdditionalFeesAmount').val() || isNaN(parseInt(div.find("#SubletAdditionalFeesAmount").val())) || div.find('#SubletAdditionalFeesAmount').val()<0 || div.find('#SubletAdditionalFeesAmount').val() >=50000)
				A2Cribs.UIManager.Error "Please enter a valid additional fees amount."
				div.find('#SubletAdditionalFeesAmount').parent().addClass "error"
				isValid = no
		if div.find("#SubletFurnishedType").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with the furniture."
			div.find('#SubletFurnishedType').parent().addClass "error"
			isValid = no
		if div.find("#SubletUtilityType").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with the utilities."
			div.find('#SubletUtilityType').parent().addClass "error"
			isValid = no
		if div.find("#parking").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with parking."
			div.find('#parking').parent().addClass "error"
			isValid = no
		if div.find("#SubletBathroomType").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with your bathroom."
			div.find('#SubletBathroomType').parent().addClass "error"
			isValid = no
		return isValid

	ValidateStep3: (div) ->
		isValid = yes
		if div.find('#HousemateQuantity').val().length is 0 # Housemates quantity is empty
			isValid = no
		else
			if +div.find('#HousemateQuantity').val() isnt 0 # More than 1 Housemate
				if div.find('#HousemateEnrolled option:selected').text().length is 0 # Check if enrolled is selected
					isValid = no
				else if +div.find('#HousemateEnrolled').val() is 1 # If the students are enrolled
					if +div.find('#HousemateStudentType').val() is 0 # Check if student type selected
						isValid = no
					else if +div.find('#HousemateStudentType').val() isnt 1 # Is not Graduate
						if +div.find('#HousemateYear').val() is 0 # Make sure year is selected
							isValid = no
					if +div.find('#HousemateGenderType').val() is 0 # Gender of housemate(s)
						isValid = no
					if div.find('#HousemateMajor').val().length >= 255 # Major of housemate(s)
						isValid = no
		
		return isValid


##########################################################################
# Initialization code for Edit
##########################################################################


	###
	Populates all fields in all steps with sublet data loaded for a sublet edit.
	###
	@PopulateInputFields: (subletData, window_type) ->
		if not subletData?
			A2Cribs.UIManager.Alert  "An error occured while loading your sublet data, please try again."
			return

		div = $('#' + window_type)

		@InitEditStep1 subletData, div
		@InitEditStep2 subletData, div
		@InitEditStep3 subletData, div
		@InitEditStep4 subletData, div

	###
	Initializes map and university input autocomplete
	If subletData is not null, then we populate all inputs in step 1 with loaded sublet data
	###
	@InitEditStep1: (subletData, div) ->

		if subletData.University.name?
			div.find('#universityName').val subletData.University.name
			div.find("#universityName").prop 'disabled', true
			div.find('#universityId').val subletData.University.id

		if subletData.Sublet?
			div.find('#SubletUnitNumber').val subletData.Sublet.unit_number
			div.find('#subletId').val subletData.Sublet.id

		if subletData.Marker?
			div.find('#buildingType').val subletData.Marker.building_type_id
			div.find("#buildingType").prop 'disabled', true
			div.find('#SubletName').val subletData.Marker.alternate_name
			div.find("#SubletName").prop 'disabled', true
			div.find("#formattedAddress").val subletData.Marker.street_address
			div.find("#formattedAddress").prop 'disabled', true
			div.find('#place_map_button').addClass 'disabled'
			div.find('#updatedLat').val subletData.Marker.latitude
			div.find('#updatedLong').val subletData.Marker.longitude
			div.find("#city").val subletData.Marker.city
			div.find("#state").val subletData.Marker.state
			div.find("#postal").val subletData.Marker.zip
			div.find("#addressToMark").val subletData.Marker.street_address
			A2Cribs.CorrectMarker.CreateMap div.find('#correctLocationMap')[0], subletData.Marker.latitude, subletData.Marker.longitude, true, false


	@InitEditStep2: (subletData, div) ->
		if subletData.Sublet.date_begin? and subletData.Sublet.date_end?
			beginDate = @GetFormattedDate new Date subletData.Sublet.date_begin
			endDate = @GetFormattedDate new Date subletData.Sublet.date_end
			div.find('#SubletDateBegin').val beginDate
			div.find('#SubletDateEnd').val endDate
		div.find('#SubletFlexibleDates').prop "checked", subletData.Sublet.flexible_dates
		div.find('#SubletNumberBedrooms').val subletData.Sublet.number_bedrooms
		div.find('#SubletPricePerBedroom').val subletData.Sublet.price_per_bedroom
		div.find('#SubletShortDescription').val subletData.Sublet.short_description
		div.find('#SubletBathroomType').val subletData.Sublet.bathroom_type_id
		div.find('#SubletUtilityType').val subletData.Sublet.utility_type_id
		div.find('#SubletUtilityCost').val subletData.Sublet.utility_type_id

		div.find('#parking').val if subletData.Sublet.parking then "Yes" else "No"
		div.find('#ac').val if subletData.Sublet.ac then "Yes" else "No"

		div.find('#SubletFurnishedType').val subletData.Sublet.furnished_type_id
		div.find('#SubletDepositAmount').val subletData.Sublet.deposit_amount
		div.find('#SubletAdditionalFeesDescription').val subletData.Sublet.additional_fees_description
		div.find('#SubletAdditionalFeesAmount').val subletData.Sublet.additional_fees_amount

	###
	Initialize step 3 - Housemate data
	###
	@InitEditStep3: (subletData, div) ->
		if subletData.Housemate?
			if subletData.Housemate.length?
				subletData.Housemate = subletData.Housemate[0]
			div.find("#HousemateQuantity").val subletData.Housemate.quantity
			div.find('#HousemateId').val subletData.Housemate.id

			if +subletData.Housemate.quantity isnt 0 # More than 1 Housemate
				div.find('#HousemateEnrolled').val subletData.Housemate.enrolled
				div.find("#HousemateGenderType").val subletData.Housemate.gender_type_id
				if subletData.Housemate.enrolled
					div.find("#HousemateStudentType").val subletData.Housemate.student_type_id
					div.find("#HousemateMajor").val subletData.Housemate.major
					if subletData.Housemate.student_type_id isnt 1
						div.find("#HousemateYear").val subletData.Housemate.year
	###
	Initialize step 4 - Photos
	###
	@InitEditStep4: () ->
		A2Cribs.PhotoManager.LoadImages()

	Reset: (div) ->
		@ResetAllInputFields div

	###
	Reset all input fields for a new sublet posting process
	###
	ResetAllInputFields: (div) ->
		div.find('input:text').val '' # Erase all inputs
		div.find('input:hidden').val '' # Erase all inputs
		div.find('select option:first-child').attr "selected", "selected" # all dropdowns to first option

	InitUniversityAutocomplete: (div) ->
		if A2Cribs.Cache.SchoolList?
			div.find("#universityName").typeahead
				source: A2Cribs.Cache.SchoolList
			return
		$.ajax
			url: "/University/getAll"
			success :(response) ->
				A2Cribs.Cache.universitiesMap = JSON.parse response
				A2Cribs.Cache.SchoolList = []
				A2Cribs.Cache.SchoolIDList = []
				for university in A2Cribs.Cache.universitiesMap
					A2Cribs.Cache.SchoolList.push university.University.name
					A2Cribs.Cache.SchoolIDList.push university.University.id
				div.find("#universityName").typeahead
					source: A2Cribs.Cache.SchoolList

##################### End Edit Sublet Initialization #################################

	@UtilityChanged: () ->
		if $("#SubletUtilityType").val() == "Included" 
			$("#SubletUtilityCost").val("0")

	@StudentTypeChanged: () ->
		if $("#HousemateStudentType").val() == "Graduate" 
			$("#HousemateYear").val(0)
	

	###
	Submits sublet to backend to save
	Assumes all front-end validations have been passed.
	###
	Save: (subletObject) ->
		url = "/sublets/ajax_submit_sublet"
		$.post url, subletObject, (response) =>
			data = JSON.parse response
			console.log data.status
			if data.redirect?
				window.location = data.redirect
			if data.status?
				A2Cribs.UIManager.Success data.status
				A2Cribs.ShareManager.SavedListing = data.newid
				return true
			else
				A2Cribs.UIManager.Alert data.error
				return false

	###
	Returns an object containing all sublet data from all 4 steps.
	###
	GetSubletObject: (div) ->
		subletObject =
			Sublet:
				id: div.find("#subletId").val()
				university_id: div.find("#universityId").val() #TODO: MAKE THIS HIDDEN FIELD IN STEP1
				university_name: div.find("#universityName").val()
				building_type_id: div.find('#buildingType').val()
				date_begin: @GetMysqlDateFormat div.find('#SubletDateBegin').val()
				date_end: @GetMysqlDateFormat div.find('#SubletDateEnd').val()
				number_bedrooms: div.find('#SubletNumberBedrooms').val()
				price_per_bedroom: div.find('#SubletPricePerBedroom').val()
				payment_type_id: 1
				short_description: div.find('#SubletShortDescription').val()
				description: div.find('#SubletLongDescription').val()
				bathroom_type_id: div.find('#SubletBathroomType').val()
				utility_type_id: div.find('#SubletUtilityType').val()
				utility_cost: div.find('#SubletUtilityCost').val()
				deposit_amount: div.find('#SubletDepositAmount').val()
				additional_fees_description: div.find('#SubletAdditionalFeesDescription').val()
				additional_fees_amount: div.find('#SubletDepositAmount').val()
				unit_number: div.find('#SubletUnitNumber').val()
				flexible_dates: div.find('#SubletFlexibleDates').is(':checked')
				furnished_type_id: div.find('#SubletFurnishedType').val()
				ac: div.find('#ac').val() == "Yes"
				parking: div.find('#parking').val() == "Yes"
			Marker:
				marker_id: div.find("#markerId").val()
				alternate_name: div.find('#SubletName').val()
				street_address: div.find("#formattedAddress").val()
				building_type_id: div.find('#buildingType').val()
				city: div.find('#city').val()
				state: div.find('#state').val()
				zip: div.find('#postal').val()
				latitude: div.find('#updatedLat').val()
				longitude: div.find('#updatedLong').val()		
			Housemate:
				id: div.find("#HousemateId").val()
				quantity: div.find("#HousemateQuantity").val()
				enrolled: div.find("#HousemateEnrolled").val()
				student_type_id: div.find("#HousemateStudentType").val()
				major: div.find("#HousemateMajor").val()
				gender_type_id: div.find("#HousemateGenderType").val()
				year: div.find("#HousemateYear").val()

	###
	Replaces '/' with '-' to make convertible to mysql datetime format
	###
	GetMysqlDateFormat: (dateString) ->
		date = new Date(dateString)
		month = date.getMonth() + 1
		if month < 10
			month = "0" + month
		day = date.getDate()
		if day < 10
			day = "0" + day
		year = date.getUTCFullYear()
		beginDateFormatted = year + "-" + month + "-" + day

	GetTodaysDate: () ->
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

	GetFormattedDate:(date) ->
		month = date.getMonth() + 1
		if month < 10
			month = "0" + month
		day = date.getDate()
		if day < 10
			day = "0" + day
		year = date.getUTCFullYear()
		beginDateFormatted = month + "/" + day + "/" + year


