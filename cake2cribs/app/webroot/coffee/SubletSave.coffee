class A2Cribs.SubletSave
	@StartNewSublet: () ->
		# Clear all old data
		$('#post-sublet-modal').find('input:text').val '' # Erase all inputs
		$('#post-sublet-modal').find('select option:first-child').attr "selected", "selected" # all dropdowns to first option

		# Clear photo

		# Clear Marker
		A2Cribs.CorrectMarker.ClearMarker()

		# Reset Progress Bar
		@ProgressBar.reset()

		# Set Current Step to first
		$('.step').eq(0).show()
		$('.step').eq(0).siblings().hide()

	@SetupUI: (initialStep) ->

		@CurrentStep = initialStep
		$('.step').eq(@CurrentStep).siblings().hide()
		@ProgressBar =  new A2Cribs.PostSubletProgress $('.post-sublet-progress'), initialStep

		$("#address-step").siblings().hide();

		$(".next-btn").click (event)=>
			
			if @Validate(@CurrentStep+1)
				$(event.currentTarget).closest(".step").hide().next(".step").show()
				@CurrentStep++
				@ProgressBar.next()
		
		
		$(".back-btn").click (event)=>
			$(event.currentTarget).closest(".step").hide().prev(".step").show()
			@CurrentStep--
			@ProgressBar.prev()

		$(".required").focus (event)=>
			$(event.target).parent().removeClass "error"

		A2Cribs.CorrectMarker.Init()

		$("#SubletShortDescription").keyup ()->
			if $(@).val().length >= 160
				$(@).val($(@).val().substr(0, 160))
			
			$("#desc-char-left").text(160 - $(@).val().length)

		$("#SubletDateBegin").datepicker();

		$("#SubletDateEnd").datepicker();

		$("#universityName").focusout ()->
			A2Cribs.CorrectMarker.FindSelectedUniversity()

		A2Cribs.Map.LoadTypeTables()
		A2Cribs.SubletSave.PopulateInputFields()
		A2Cribs.PhotoManager.SetupUI()


	###
	Called before advancing steps
	Returns true if validations pass; false otherwise
	###
	@Validate: (step_) ->
		if step_ >= 1
			if !@ValidateStep1()
				return false
		if step_ >= 2
			if !@ValidateStep2()
				return false
		if step_ >= 3
			if !@ValidateStep3()
				return false
			if !@SaveSublet()
				return false

		return true


	@ValidateStep1: () ->
		isValid = yes
		A2Cribs.UIManager.CloseLogs()
		if (!$('#formattedAddress').val())
			A2Cribs.UIManager.Error "Please place your street address on the map using the Place On Map button."
			$('#formattedAddress').parent().addClass "error"
			isValid = no
		if (!$('#universityName').val())
			A2Cribs.UIManager.Error "You need to select a university."
			$('#universityName').parent().addClass "error"
			isValid = no
		if ($('#buildingType').val().length is 0)
			A2Cribs.UIManager.Error "You need to select a building type."
			$('#buildingType').parent().addClass "error"
			isValid = no
		if ($('#SubletUnitNumber').val().length >=249)
			A2Cribs.UIManager.Error "Your unit number is too long."
			$('#SubletUnitNumber').parent().addClass "error"
			isValid = no
		if ($('#SubletName').val().length >= 249)
			A2Cribs.UIManager.Error "Your alternate name is too long."
			$('#SubletName').parent().addClass "error"
			isValid = no
		
		return isValid

	@ValidateStep2: () ->
		#begin the validations
		isValid = yes
		A2Cribs.UIManager.CloseLogs()
		parsedBeginDate = new Date Date.parse($('#SubletDateBegin').val())
		parsedEndDate = new Date Date.parse($('#SubletDateEnd').val())
		todayDate = new Date();
		if parsedBeginDate.toString() == "Invalid Date" or parsedEndDate.toString() == "Invalid Date"
			A2Cribs.UIManager.Error "Please enter a valid date."
			$('#SubletDateBegin').parent().addClass "error"
			$('#SubletDateEnd').parent().addClass "error"
			isValid = no
		else if parsedEndDate.valueOf() <= parsedBeginDate.valueOf() or parsedBeginDate.valueOf() <= todayDate.valueOf()
			A2Cribs.UIManager.Error "Please enter a valid date."
			$('#SubletDateBegin').parent().addClass "error"
			$('#SubletDateEnd').parent().addClass "error"
			isValid = no
		if (!$('#SubletNumberBedrooms').val() || isNaN(parseInt($("#SubletNumberBedrooms").val())) || $('#SubletNumberBedrooms').val() <=0 || $('#SubletNumberBedrooms').val() >=30)
			A2Cribs.UIManager.Error "Please enter a valid number of bedrooms."
			$('#SubletNumberBedrooms').parent().addClass "error"
			isValid = no
		if (!$('#SubletPricePerBedroom').val() || isNaN(parseInt($("#SubletPricePerBedroom").val())) || $('#SubletPricePerBedroom').val() < 1 || $('#SubletPricePerBedroom').val() >=20000)
			A2Cribs.UIManager.Error "Please enter a valid price per bedroom."
			$('#SubletPricePerBedroom').parent().parent().addClass "error"
			isValid = no
		if $('#SubletShortDescription').val().length is 0 
			A2Cribs.UIManager.Error "Please enter a description."
			$('#SubletShortDescription').parent().addClass "error"
			isValid = no
		if (!$('#SubletUtilityCost').val()|| isNaN(parseInt($("#SubletUtilityCost").val())) || $('#SubletUtilityCost').val()<0 || $('#SubletUtilityCost').val() >=50000)
			A2Cribs.UIManager.Error "Please enter a valid utility cost."
			$('#SubletUtilityCost').parent().addClass "error"
			isValid = no
		if (!$('#SubletDepositAmount').val() || isNaN(parseInt($("#SubletDepositAmount").val())) || $('#SubletDepositAmount').val()<0 || $('#SubletDepositAmount').val() >=50000)
			A2Cribs.UIManager.Error "Please enter a valid deposit amount."
			$('#SubletDepositAmount').parent().parent().addClass "error"
			isValid = no
		descLength = $('#SubletAdditionalFeesDescription').val().length
		if (descLength >=161)
			A2Cribs.UIManager.Error "Please keep the additional fees description under 160 characters."
			$('#SubletAdditionalFeesDescription').parent().addClass "error"
			isValid = no
		if descLength > 0 
			if (!$('#SubletAdditionalFeesAmount').val() || isNaN(parseInt($("#SubletAdditionalFeesAmount").val())) || $('#SubletAdditionalFeesAmount').val()<0 || $('#SubletAdditionalFeesAmount').val() >=50000)
				A2Cribs.UIManager.Error "Please enter a valid additional fees amount."
				$('#SubletAdditionalFeesAmount').parent().addClass "error"
				isValid = no
		if $("#SubletFurnishedType").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with the furniture."
			$('#SubletFurnishedType').parent().addClass "error"
			isValid = no
		if $("#SubletUtilityType").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with the utilities."
			$('#SubletUtilityType').parent().addClass "error"
			isValid = no
		if $("#parking").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with parking."
			$('#parking').parent().addClass "error"
			isValid = no
		if $("#SubletBathroomType").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with your bathroom."
			$('#SubletBathroomType').parent().addClass "error"
			isValid = no
		return isValid

	@ValidateStep3: () ->
		isValid = yes
		if $('#HousemateQuantity').val().length is 0 # Housemates quantity is empty
			isValid = no
		else
			if +$('#HousemateQuantity').val() isnt 0 # More than 1 Housemate
				if $('#HousemateEnrolled option:selected').text().length is 0 # Check if enrolled is selected
					isValid = no
				else if +$('#HousemateEnrolled').val() is 1 # If the students are enrolled
					if +$('#HousemateStudentType').val() is 0 # Check if student type selected
						isValid = no
					else if +$('#HousemateStudentType').val() isnt 1 # Is not Graduate
						if +$('#HousemateYear').val() is 0 # Make sure year is selected
							isValid = no
					if +$('#HousemateGenderType').val() is 0 # Gender of housemate(s)
						isValid = no
					if $('#HousemateMajor').val().length >= 255 # Major of housemate(s)
						isValid = no
		
		return isValid

	###
	Retrieves all necessary sublet data and then pulls up the edit sublet interface
	###
	@EditSublet:(sublet_id) ->
		$.ajax
			url: myBaseUrl + "Sublets/getSubletDataById/" + sublet_id
			type: "GET"
			success: (subletData) =>
				subletData = JSON.parse subletData
				A2Cribs.SubletSave.PopulateInputFields(subletData)
				###
				TODO: Open Modal Here
				###

				# Resize the modal window to fit the screen
				# NOTE: This needs to be refactored a ton
				A2Cribs.SubletAdd.resizeModal(modal_body)
				# Also setup a window handler so that when the window is resized the modal is sized too
				$(window).resize ()=>
					A2Cribs.SubletAdd.resizeModal(modal_body)

			error: ()=>
				alertify.error("An error occured while loading your sublet data, please try again.", 2000)


##########################################################################
# Initialization code for Edit
##########################################################################


	###
	Populates all fields in all steps with sublet data loaded for a sublet edit.
	###
	@PopulateInputFields: (subletData = null) ->
		if subletData == null
			# There is no data to load.
			# Initialize autocomplete and reset all fields.
			@InitUniversityAutocomplete()
			@ResetAllInputFields()
			return

		@InitEditStep1(subletData)
		@InitEditStep2(subletData)
		@InitEditStep3(subletData)
		@InitEditStep4(subletData)

	###
	Initializes map and university input autocomplete
	If subletData is not null, then we populate all inputs in step 1 with loaded sublet data
	###
	@InitEditStep1: (subletData=null) ->
		if subletData == null
			return

		#Load all universities and initialize autocomplete
		@InitUniversityAutocomplete()		

		#TODO: Load all types fields from database rather than have them hard-coded.

		#Populate all fields with loaded data

		if subletData.University != null and subletData.University != undefined
			$('#universityName').val(subletData.University.name)
			A2Cribs.CorrectMarker.FindSelectedUniversity()

		if subletData.Sublet != null and subletData.Sublet != undefined
			$('#SubletUnitNumber').val(subletData.Sublet.unit_number)

		if subletData.Marker != null and subletData.Marker != undefined
			$('#SubletBuildingTypeId').val(subletData.Marker.building_type_id)
			$('#SubletName').val(subletData.Marker.alternate_name)
			$("#formattedAddress").val(subletData.Marker.street_address)
			$('#updatedLat').val(subletData.Marker.latitude)
			$('#updatedLong').val(subletData.Marker.longitude)
			$("#city").val(subletData.Marker.city)
			$("#state").val(subletData.Marker.state)
			$("#postal").val(subletData.Marker.zip)
			$("#addressToMark").val(subletData.Marker.street_address)
			if subletData.Marker.street_address != null and subletData.Marker.street_address != undefined
				A2Cribs.CorrectMarker.FindAddress()

		# Disable the address and map fields so the user can't change the location of the sublet
		# There will also be server side logic that will also prevent this.
		A2Cribs.CorrectMarker.Disable()


	@InitEditStep2: (subletData) ->
		if subletData == null
			return

		$('#SubletDateBegin').val("")
		$('#SubletDateEnd').val("")
		$('#SubletFlexibleDates').prop("checked", true)
		$('#SubletParking').prop("checked", false)
		$('#SubletAc').prop("checked", false)
		if subletData.Sublet == null or subletData.Sublet == undefined
			return

		if subletData.Sublet.date_begin != null
			beginDate = new Date(subletData.Sublet.date_begin)
			formattedBeginDate = A2Cribs.SubletAdd.GetFormattedDate(beginDate)
		if A2Cribs.Cache.SubletEditInProgress.Sublet.date_end != null
			endDate = new Date(subletData.Sublet.date_end)
			formattedEndDate = A2Cribs.SubletAdd.GetFormattedDate(endDate)
		$('#SubletDateBegin').val(formattedBeginDate)
		$('#SubletDateEnd').val(formattedEndDate)
		if subletData.Sublet.flexible_dates != null
			$('#SubletFlexibleDates').prop('checked', subletData.Sublet.flexible_dates)
		$('#SubletNumberBedrooms').val(subletData.Sublet.number_bedrooms)
		$('#SubletPricePerBedroom').val(subletData.Sublet.price_per_bedroom)
		$('#SubletShortDescription').val(subletData.Sublet.short_description)
		$('#SubletBathroomType').val(subletData.Sublet.bathroom_type_id)
		$('#SubletUtilityTypeId').val(subletData.Sublet.utility_type_id)
		$('#SubletUtilityCost').val(subletData.Sublet.utility_type_id)
		$('#SubletParking').prop("checked", subletData.Sublet.parking)
		$('#SubletAc').prop("checked", subletData.Sublet.ac)
		$('#SubletFurnishedType').val(subletData.Sublet.furnished_type_id)
		$('#SubletDepositAmount').val(subletData.Sublet.deposit_amount)
		$('#SubletAdditionalFeesDescription').val(subletData.Sublet.additional_fees_description)
		$('#SubletAdditionalFeesAmount').val(subletData.Sublet.additional_fees_amount)

	###
	Initialize step 3 - Housemate data
	###
	@InitEditStep3: (subletData) ->
		if subletData == null
			return

		$("#HousemateEnrolled").prop("checked", false)
		if subletData.Housemate == null or subletData.Housemate == undefined
			return 

		$("#HousemateQuantity").val(subletData.Housemate.quantity)
		$("#HousemateEnrolled").prop("checked", subletData.Housemate.enrolled)
		$("#HousemateStudentType").val(subletData.Housemate.student_type_id)
		$("#HousemateMajor").val(subletData.Housemate.major)
		$("#HousemateGenderType").val(subletData.Housemate.gender_type_id)
		$("#HousemateYear").val(subletData.Housemate.year)

	###
	Initialize step 4 - Photos
	###
	@InitEditStep4: () ->
		A2Cribs.PhotoManager.LoadImages()

	###
	Reset all input fields for a new sublet posting process
	###
	@ResetAllInputFields: () ->


	@InitUniversityAutocomplete: () ->
		$.ajax
			url: myBaseUrl + "universities/loadAll"
			success :(response) ->
				A2Cribs.CorrectMarker.universitiesMap = JSON.parse response
				A2Cribs.CorrectMarker.SchoolList = []
				for university in A2Cribs.CorrectMarker.universitiesMap
					A2Cribs.CorrectMarker.SchoolList.push university.University.name
				$("#universityName").typeahead
					source: A2Cribs.CorrectMarker.SchoolList

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
	@SaveSublet: () ->
		url = "/sublets/ajax_submit_sublet"
		$.post url, A2Cribs.SubletSave.GetSubletObject(), (response) =>
			data = JSON.parse response
			console.log data.status
			if (data.status)
				A2Cribs.UIManager.Alert data.status
				A2Cribs.ShareManager.SavedListing = data.newid
				return true
			else
				A2Cribs.UIManager.Alert data.error
				return false

	###
	Called when user finishes the final step of sublet add/edit.
	Closes sublet modal and redirects user to map with sublet popup open.
	###
	@FinishSubletSave: () ->
		###
		TODO: Close Modal
		###
		if !isNaN A2Cribs.ShareManager.SavedListing
			window.location.href = "/sublet/" + A2Cribs.ShareManager.SavedListing


	###
	Returns an object containing all sublet data from all 4 steps.
	###
	@GetSubletObject: () ->
		subletObject =
			Sublet:
				id: $("#subletId").val()
				university_id: $("#universityId").val() #TODO: MAKE THIS HIDDEN FIELD IN STEP1
				university_name: $("#universityName").val()
				building_type_id: $('#buildingType').val()
				date_begin: @GetMysqlDateFormat $('#SubletDateBegin').val()
				date_end: @GetMysqlDateFormat $('#SubletDateEnd').val()
				number_bedrooms: $('#SubletNumberBedrooms').val()
				price_per_bedroom: $('#SubletPricePerBedroom').val()
				payment_type_id: 1
				short_description: $('#SubletShortDescription').val()
				description: $('#SubletLongDescription').val()
				bathroom_type_id: $('#SubletBathroomType').val()
				utility_type_id: $('#SubletUtilityType').val()
				utility_cost: $('#SubletUtilityCost').val()
				deposit_amount: $('#SubletDepositAmount').val()
				additional_fees_description: $('#SubletAdditionalFeesDescription').val()
				additional_fees_amount: $('#SubletDepositAmount').val()
				unit_number: $('#SubletUnitNumber').val()
				flexible_dates: $('#SubletFlexibleDates').is(':checked')
				furnished_type_id: $('#SubletFurnishedType').val()
				ac: $('#ac').val() == "Yes"
				parking: $('#parking').val() == "Yes"
			Marker:
				marker_id: $("#markerId").val()
				alternate_name: $('#SubletName').val()
				street_address: $("#formattedAddress").val()
				building_type_id: $('#buildingType').val()
				city: $('#city').val()
				state: $('#state').val()
				zip: $('#postal').val()
				latitude: $('#updatedLat').val()
				longitude: $('#updatedLong').val()		
			Housemate: 
				quantity: $("#HousemateQuantity").val()
				enrolled: $("#HousemateEnrolled").val()
				student_type_id: $("#HousemateStudentType").val()
				major: $("#HousemateMajor").val()
				gender_type_id: $("#HousemateGenderType").val()
				year: $("#HousemateYear").val()

	###
	Replaces '/' with '-' to make convertible to mysql datetime format
	###
	@GetMysqlDateFormat: (dateString) ->
		date = new Date(dateString)
		month = date.getMonth() + 1
		if month < 10
			month = "0" + month
		day = date.getDate()
		if day < 10
			day = "0" + day
		year = date.getUTCFullYear()
		beginDateFormatted = year + "-" + month + "-" + day

	@GetTodaysDate: () ->
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


