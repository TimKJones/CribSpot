class A2Cribs.SubletAdd
	#@Step1Data = null

	@setupUI:() ->
		$('#goToStep2').click (e) =>
			#begin the validations
			if (!$('#formattedAddress').val())
				A2Cribs.UIManager.Alert "Please place your street address on the map using the Place On Map button."
			else if (!$('#universityName').val())
				A2Cribs.UIManager.Alert "You need to select a university."
			else if ($('#SubletUnitNumber').val().length >=249)
				A2Cribs.UIManager.Alert "Your unit number is too long."
			else if ($('#SubletName').val().length >= 249)
				A2Cribs.UIManager.Alert "Your alternate name is too long."
			else
				e.preventDefault()
				#$('#server-notice').dialog2("options", {content:"Sublets/ajax_add2"});
				@subletAddStep1()

		$("#backToStep2").click (e) =>
			@backToStep2()

		$('#goToStep1').click (e) =>
			@backToStep1()

		$("#goToStep3").click (e) ->
			#begin the validations
			parsedBeginDate = new Date(Date.parse($('#SubletDateBegin').val()))
			parsedEndDate = new Date(Date.parse($('#SubletDateEnd').val()))
			todayDate = new Date();
			if parsedBeginDate.toString() == "Invalid Date" or parsedEndDate.toString() == "Invalid Date"
				A2Cribs.UIManager.Alert "Please enter a valid date."
			else if (parsedEndDate.valueOf() <= parsedBeginDate.valueOf() && parsedBeginDate.valueOf() <= todayDate.valueOf())
				A2Cribs.UIManager.Alert "Please enter a valid date."
			else if (!$('#SubletNumberBedrooms').val() || $('#SubletNumberBedrooms').val() <=0 || $('#SubletNumberBedrooms').val() >=30)
				A2Cribs.UIManager.Alert "Please enter a valid number of bedrooms."
			else if (!$('#SubletPricePerBedroom').val() || $('#SubletPricePerBedroom').val() < 0 || $('#SubletPricePerBedroom').val() >=20000)
				A2Cribs.UIManager.Alert "Please enter a valid price per bedroom."
			else if ($('#SubletShortDescription').val().length >=161)
				A2Cribs.UIManager.Alert "Please keep the short description under 160 characters."
			else if (!$('#SubletNumberBathrooms').val() || $('#SubletNumberBathrooms').val()<0 || $('#SubletNumberBathrooms').val() >=30)
				A2Cribs.UIManager.Alert "Please enter a valid number of bathrooms."
			else if (!$('#SubletUtilityCost').val() || $('#SubletUtilityCost').val()<0 || $('#SubletUtilityCost').val() >=50000)
				A2Cribs.UIManager.Alert "Please enter a valid utility cost."
			else if (!$('#SubletDepositAmount').val() || $('#SubletDepositAmount').val()<0 || $('#SubletDepositAmount').val() >=50000)
				A2Cribs.UIManager.Alert "Please enter a valid deposit amount."
			else if ($('#SubletAdditionalFeesDescription').val().length >=161)
				A2Cribs.UIManager.Alert "Please keep the additional fees description under 160 characters."
			else if ($('#SubletAdditionalFeesAmount').val()<0 || $('#SubletAdditionalFeesAmount').val() >=50000)
				A2Cribs.UIManager.Alert "Please enter a valid additional fees amount."
			else
				A2Cribs.SubletAdd.subletAddStep2()
 
		$('#finishSubletAdd').click (e) =>
			if (!$('#HousemateQuantity').val() || $('#HousemateQuantity').val() >= 50 || $('#HousemateQuantity').val() < 0)
				A2Cribs.UIManager.Alert "Please enter a valid housemate quantity."
			else if ($('#HousemateMajor').val().length >= 254)
				A2Cribs.UIManager.Alert "Please keep the majors description under 255 characters."
			else if ($('#HousemateSeeking').val().length >= 254)
				A2Cribs.UIManager.Alert "Please keep the description of who you're seeking under 255 characters."
			e.preventDefault()
			@subletAddStep3()
		#refresh UI dates
		oldBeginDate = new Date($('#SubletDateBegin').val())
		$('#SubletDateBegin').val(oldBeginDate.toDateString())
		oldEndDate = new Date($('#SubletDateEnd').val())
		$('#SubletDateEnd').val(oldEndDate.toDateString())



	@backToStep1: () ->
		$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add"});

	@backToStep2: () ->
		$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add2"});

	@subletAddStep1:() ->
		url = "/	sublets/ajax_add_create"
		request_data = {
			Sublet: {
				university_id: parseInt(A2Cribs.CorrectMarker.SelectedUniversity)
				university: $('#universitiesInput').val()
				unit_number: $('#SubletUnitNumber').val()
				address: $("#formattedAddress").text()
				building_type_id: $('#SubletBuildingTypeId').val()
				name: $('#SubletName').val()
				latitude: $('#updatedLat').text()
				longitude: $('#updatedLong').text()
			}
			Marker: {
				street_address: $("#formattedAddress").text()
				city: $("#city").text()
				state: $("#state").text()
				zip: $("#postal").text()
			}
			CurrentStep: 1
			#console.log(universitiesArray.indexOf(request_data.Sublet.university));
		}

		A2Cribs.Cache.CacheSubletAddStep1 request_data
		$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add2"});
		#validations go here
		#A2Cribs.SubletAdd.Step1Data = request_data

		###$.post url, request_data, (response) =>
			console.log(response)
			data = JSON.parse response
			console.log data
			$('#server-notice').dialog2("options", {content:"Sublets/ajax_add2"});
			#window.location.href= '/dashboard'
			#if data.registerStatus == 1
			#	window.location.href= '/dashboard'
			#else
			#	$('#registerStatus').empty()###

	@subletAddStep2:() ->
		url = "sublets/ajax_add_create"
		parsedBeginDate = new Date(Date.parse($('#SubletDateBegin').val()))
		parsedEndDate = new Date(Date.parse($('#SubletDateEnd').val()))
		request_data = {
			Sublet: {
				date_begin: parsedBeginDate.toISOString()
				date_end: parsedEndDate.toISOString()
				flexible_dates: $('#SubletFlexibleDates').val()
				number_bedrooms: $('#SubletNumberBedrooms').val()
				price_per_bedroom: $('#SubletPricePerBedroom').val()
				payment_type_id: $('#SubletPaymentTypeId').val()
				short_description: $('#SubletShortDescription').val()
				number_bathrooms: $('#SubletNumberBathrooms').val()
				bathroom_type_id: $('#SubletBathroomTypeId').val()
				utility_type_id: $('#SubletUtilityTypeId').val()
				utility_cost: $('#SubletUtilityCost').val()
				parking: $('#SubletParking').val()
				ac: $('#SubletAc').val()
				furnished_type_id: $('#SubletFurnishedTypeId').val()
				deposit_amount: $('#SubletDepositAmount').val()
				additional_fees_description: $('#SubletAdditionalFeesDescription').val()
				additional_fees_amount: $('#SubletAdditionalFeesAmount').val()

			}
			CurrentStep: 2
			#console.log(universitiesArray.indexOf(request_data.Sublet.university));

		}
		#validations go here
		A2Cribs.Cache.CacheSubletAddStep2 request_data
		$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add3"});

		###$.post url, request_data, (response) =>
			console.log(response)
			data = JSON.parse response
			console.log data
			console.log "Done with step 2"
			$('#server-notice').dialog2("options", {content:"Sublets/ajax_add3"});
			#window.location.href= '/dashboard'
			#if data.registerStatus == 1
			#	window.location.href= '/dashboard'
			#else
			#	$('#registerStatus').empty()###

	@subletAddStep3:() ->
		url = "/sublets/ajax_submit_sublet"
		step1 = A2Cribs.Cache.Step1Data
		step2 = A2Cribs.Cache.Step2Data

		request_data = 	{
			Sublet: {
				university_id: parseInt(A2Cribs.Cache.SelectedUniversity.id)
				building_type_id: step1.Sublet.building_type_id
				date_begin: A2Cribs.FilterManager.GetFormattedDate new Date(step2.Sublet.date_begin)
				date_end: A2Cribs.FilterManager.GetFormattedDate new Date(step2.Sublet.date_end)
				number_bedrooms: step2.Sublet.number_bedrooms
				price_per_bedroom: step2.Sublet.price_per_bedroom
				payment_type_id: "0"
				short_description: "NA"
				description: step2.Sublet.short_description
				number_bathrooms: step2.Sublet.number_bathrooms
				bathroom_type_id: step2.Sublet.bathroom_type_id
				utility_type_id: step2.Sublet.utility_type_id
				utility_cost: step2.Sublet.utility_cost
				deposit_amount: step2.Sublet.deposit_amount
				additional_fees_description: step2.Sublet.additional_fees_description
				additional_fees_amount: step2.Sublet.additional_fees_amount
				unit_number: step1.Sublet.unit_number
				flexible_dates: step2.Sublet.flexible_dates
				furnished_type_id: step2.Sublet.furnished_type_id
				ac: step2.Sublet.ac
				parking: step2.Sublet.parking
			}

			Marker: {
				alternate_name: step1.Sublet.name
				street_address: step1.Sublet.address
				city: step1.Marker.city
				state: step1.Marker.state
				zip: step1.Marker.zip
				latitude: step1.Sublet.latitude
				longitude: step1.Sublet.longitude
			}			

			Housemate: {
				quantity: $('#HousemateQuantity').val()
				enrolled: $('#HousemateEnrolled').val()
				student_type_id: $('#HousemateStudentTypeId').val()
				major: $('#HousemateMajor').val()
				seeking: $('#HousemateSeeking').val()
				gender_type_id: $('#HousemateGenderTypeId').val()
				type: "Senior, Sophomore"
			}
		}

		#validations go here
		$.post url, request_data, (response) =>
			console.log(response)
			data = JSON.parse response
			console.log data.status
			if (data.status)
				A2Cribs.UIManager.Alert data.status
			else
				A2Cribs.UIManager.Alert data.error
			$('#server-notice').dialog2("close");
			#window.location.href= '/dashboard'
			#if data.registerStatus == 1
			#	window.location.href= '/dashboard'
			#else
			#	$('#registerStatus').empty()

	@GetFormattedDate:(date) ->
		month = date.getMonth() + 1
		if month < 10
			month = "0" + month
		day = date.getDate()
		if day < 10
			day = "0" + day
		year = date.getUTCFullYear()
		beginDateFormatted = month + "/" + day + "/" + year
