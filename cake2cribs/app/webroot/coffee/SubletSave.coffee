class A2Cribs.SubletSave

	@SetupUI: () ->

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
				
		return true


	@ValidateStep1: () ->
		if (!$('#formattedAddress').val())
			A2Cribs.UIManager.Alert "Please place your street address on the map using the Place On Map button."
			return false
		if (!$('#universityName').val())
			A2Cribs.UIManager.Alert "You need to select a university."
			return false
		if ($('#SubletUnitNumber').val().length >=249)
			A2Cribs.UIManager.Alert "Your unit number is too long."
			return false
		if ($('#SubletName').val().length >= 249)
			A2Cribs.UIManager.Alert "Your alternate name is too long."
			return false
		
		return true

	@ValidateStep2: () ->
		#begin the validations
		parsedBeginDate = new Date(Date.parse($('#SubletDateBegin').val()))
		parsedEndDate = new Date(Date.parse($('#SubletDateEnd').val()))
		todayDate = new Date();
		if parsedBeginDate.toString() == "Invalid Date" or parsedEndDate.toString() == "Invalid Date"
			A2Cribs.UIManager.Alert "Please enter a valid date."
			return false
		if (parsedEndDate.valueOf() <= parsedBeginDate.valueOf() && parsedBeginDate.valueOf() <= todayDate.valueOf())
			A2Cribs.UIManager.Alert "Please enter a valid date."
			return false
		if (!$('#SubletNumberBedrooms').val() || $('#SubletNumberBedrooms').val() <=0 || $('#SubletNumberBedrooms').val() >=30)
			A2Cribs.UIManager.Alert "Please enter a valid number of bedrooms."
			return false
		if (!$('#SubletPricePerBedroom').val() || $('#SubletPricePerBedroom').val() < 1 || $('#SubletPricePerBedroom').val() >=20000)
			A2Cribs.UIManager.Alert "Please enter a valid price per bedroom."
			return false
		if ($('#SubletDescription').val().length >=161)
			A2Cribs.UIManager.Alert "Please keep the description under 160 characters."
			return false
		if (!$('#SubletUtilityCost').val() || $('#SubletUtilityCost').val()<0 || $('#SubletUtilityCost').val() >=50000)
			A2Cribs.UIManager.Alert "Please enter a valid utility cost."
			return false
		if (!$('#SubletDepositAmount').val() || $('#SubletDepositAmount').val()<0 || $('#SubletDepositAmount').val() >=50000)
			A2Cribs.UIManager.Alert "Please enter a valid deposit amount."
			return false
		if ($('#SubletAdditionalFeesDescription').val().length >=161)
			A2Cribs.UIManager.Alert "Please keep the additional fees description under 160 characters."
			return false
		if (!$('#SubletAdditionalFeesAmount').val() || $('#SubletAdditionalFeesAmount').val()<0 || $('#SubletAdditionalFeesAmount').val() >=50000)
			A2Cribs.UIManager.Alert "Please enter a valid additional fees amount."
			return false
		
		return true

	@ValidateStep3: () ->
		if (!$('#HousemateQuantity').val() || $('#HousemateQuantity').val() >= 50 || $('#HousemateQuantity').val() < 0)
			A2Cribs.UIManager.Alert "Please enter a valid housemate quantity."
			return false
		else if ($('#HousemateMajor').val().length >= 254)
			A2Cribs.UIManager.Alert "Please keep the majors description under 255 characters."
			return false
		
		return true

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
	@PopulateInputFields: (subletData) ->
		@InitEditStep1(subletData)
		@InitEditStep2(subletData)
		@InitEditStep3(subletData)
		@InitEditStep4(subletData)

	###
	Populates all inputs in step 1 with loaded sublet data
	Initializes map and university input autocomplete
	###
	@InitEditStep1: (subletData) ->
		#TODO: Load all universities and initialize autocomplete dealy
		#TODO: Load all types fields from database rather than have them hard-coded.

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
		$('#SubletDescription').val(subletData.Sublet.description)
		$('#SubletBathroomTypeId').val(subletData.Sublet.bathroom_type_id)
		$('#SubletUtilityTypeId').val(subletData.Sublet.utility_type_id)
		$('#SubletUtilityCost').val(subletData.Sublet.utility_type_id)
		$('#SubletParking').prop("checked", subletData.Sublet.parking)
		$('#SubletAc').prop("checked", subletData.Sublet.ac)
		$('#SubletFurnishedTypeId').val(subletData.Sublet.furnished_type_id)
		$('#SubletDepositAmount').val(subletData.Sublet.deposit_amount)
		$('#SubletAdditionalFeesDescription').val(subletData.Sublet.additional_fees_description)
		$('#SubletAdditionalFeesAmount').val(subletData.Sublet.additional_fees_amount)

	###
	Initialize step 3 - Housemate data
	###
	@InitEditStep3: (subletData) ->
		$("#HousemateEnrolled").prop("checked", false)
		if subletData.Housemate == null or subletData.Housemate == undefined
			return 

		$("#HousemateQuantity").val(subletData.Housemate.quantity)
		$("#HousemateEnrolled").prop("checked", subletData.Housemate.enrolled)
		$("#HousemateStudentTypeId").val(subletData.Housemate.student_type_id)
		$("#HousemateMajor").val(subletData.Housemate.major)
		$("#HousemateGenderTypeId").val(subletData.Housemate.gender_type_id)
		$("#HousemateType").val(subletData.Housemate.type)

	###
	Initialize step 4 - Photos
	###
	@InitEditStep4: () ->
		A2Cribs.PhotoManager.LoadImages()

##################### End Edit Sublet Initialization #################################

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
				$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add4"});
			else
				A2Cribs.UIManager.Alert data.error

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
				id: $("#").val() #TODO: GET NAME OF HIDDEN SUBLET_ID INPUT
				university_id: $("#").val() #TODO: MAKE THIS HIDDEN FIELD IN STEP1
				university_name: $("#universityName").val()
				building_type_id: $('#SubletBuildingTypeId').val()
				date_begin: @GetMysqlDateFormat $('#SubletDateBegin').val()
				date_end: @GetMysqlDateFormat $('#SubletDateEnd').val()
				number_bedrooms: $('#SubletNumberBedrooms').val()
				price_per_bedroom: $('#SubletPricePerBedroom').val()
				payment_type_id: 1
				short_description: $('#SubletDescription').val()
				description: $('#SubletDescription').val()
				bathroom_type_id: $('#SubletBathroomTypeId').val()
				building_type_id: $('#SubletBuildingTypeId').val()
				utility_type_id: $('#SubletUtilityTypeId').val()
				utility_cost: $('#SubletUtilityCost').val()
				deposit_amount: $('#SubletDepositAmount').val()
				additional_fees_description: $('#SubletAdditionalFeesDescription').val()
				additional_fees_amount: $('#SubletDepositAmount').val()
				unit_number: $('#SubletUnitNumber').val()
				flexible_dates: $('#SubletFlexibleDates').is(':checked')
				furnished_type_id: $('#SubletFurnishedTypeId').val()
				ac: $('#SubletAc').is(':checked')
				parking: $('#SubletParking').is(':checked')
			Marker:
				alternate_name: $('#SubletName').val()
				street_address: $("#addressToMark").val()
				building_type_id: $('#SubletBuildingTypeId').val()
				city: $('#city').val()
				state: $('#state').val()
				zip: $('#postal').val()
				latitude: $('#updatedLat').val()
				longitude: $('#updatedLong').val()		
			Housemate: 
				quantity: $("#HousemateQuantity").val()
				enrolled: $("#HousemateEnrolled").is(':checked')
				student_type_id: $("#HousemateStudentTypeId").val()
				major: $("#HousemateMajor").val()
				gender_type_id: $("#HousemateGenderTypeId").val()
				type: $("#HousemateType").val()

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


