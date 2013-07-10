class A2Cribs.SubletAdd
	#@CurrentDataObject = null 
	@setupUI:() ->
		$("#goToStep2").click() =>
			#begin the validations
			if (!$('#formattedAddress').text())
				A2Cribs.UIManager.Alert "Please place your street address on the map using the Place On Map button."
			else if (!$('#universitiesInput').val())
				A2Cribs.UIManager.Alert "You need to select a university."
			else if ($('#SubletUnitNumber').val().length >=249)
				A2Cribs.UIManager.Alert "Your unit number is too long."
			else if ($('#SubletName').val().length >= 249)
				A2Cribs.UIManager.Alert "Your alternate name is too long."
			else
				$('#server-notice').dialog2("options", {content:"Sublets/ajax_add2"});
				A2Cribs.SubletAdd.subletAddStep1()

		$('#goToStep3').click () =>
			#begin the validations
			parsedBeginDate = new Date(Date.parse($('#SubletDateBegin').val()))
			parsedEndDate = new Date(Date.parse($('#SubletDateEnd').val()))
			todayDate = new Date();
			if (parsedEndDate.valueOf() <= parsedBeginDate.valueOf() && parsedBeginDate.valueOf() <= todayDate.valueOf())
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
				@subletAddStep2()
 
		$('#finishSubletAdd').click () =>
			###if ($('#SubletDescription').val().length >= 254)
				A2Cribs.UIManager.Alert "Please keep the sublet description under 255 characters."###
			if (!$('#HousemateQuantity').val() || $('#HousemateQuantity').val() >= 50 || $('#HousemateQuantity').val() < 0)
				A2Cribs.UIManager.Alert "Please enter a valid housemate quantity."
			if ($('#HousemateMajor').val().length >= 254)
				A2Cribs.UIManager.Alert "Please keep the majors description under 255 characters."
			if ($('#HousemateSeeking').val().length >=254)
				A2Cribs.UIManager.Alert "Please keep the description of who you're seeking under 255 characters."
			@subletAddStep3()
		#refresh UI dates
		oldBeginDate = new Date($('#SubletDateBegin').val())
		$('#SubletDateBegin').val(oldBeginDate.toDateString())
		oldEndDate = new Date($('#SubletDateEnd').val())
		$('#SubletDateEnd').val(oldEndDate.toDateString())



			
	@subletAddStep1:() ->
		url = "/sublets/ajax_add_create"
		request_data = {
			Sublet: {
				university_id: parseInt(window.universitiesArray.indexOf($('#universitiesInput').val()) + parseInt(window.universitiesMap[0].University.id))
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
		#validations go here
		A2Cribs.SubletAdd.Step1Data = request_data

		$.post url, request_data, (response) =>
			A2Cribs.SubletAdd.Step1Callback(response)

	@Step1Callback: (response) ->
		console.log(response)
		data = JSON.parse response
		console.log "Done with step 1."
		console.log data
		console.log "Still done with step 1"
		#A2Cribs.SubletAdd.CurrentDataObject = data
		$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add2"});
		#window.location.href= '/dashboard'
		#if data.registerStatus == 1
		#	window.location.href= '/dashboard'
		#else
		#	$('#registerStatus').empty()

	@subletAddStep2:() ->
		url = "/sublets/ajax_add_create"
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

		$.post url, request_data, (response) =>
			console.log(response)
			data = JSON.parse response
			console.log data
			#2Cribs.SubletAdd.CurrentDataObject = data
			console.log "Done with step 2"
			$('#server-notice').dialog2("options", {content:"Sublets/ajax_add3"});
			#window.location.href= '/dashboard'
			#if data.registerStatus == 1
			#	window.location.href= '/dashboard'
			#else
			#	$('#registerStatus').empty()

	@subletAddStep3:() ->
		url = "/sublets/ajax_add_create"
		request_data = {
			Sublet: {
				description: $('#SubletDescription').val()
			}

			Housemate: {
				quantity: $('#HousemateQuantity').val()
				enrolled: $('#HousemateEnrolled').val()
				student_type_id: $('#HousemateStudentTypeId').val()
				major: $('#HousemateMajor').val()
				seeking: $('#HousemateSeeking').val()
				gender_type_id: $('#HousemateGenderTypeId').val()
			}
			CurrentStep: 3
			Finish: 1
			#console.log(universitiesArray.indexOf(request_data.Sublet.university));
			
		}
		#validations go here
		$.post url, request_data, (response) =>
			console.log(response)
			data = JSON.parse response
			#A2Cribs.SubletAdd.CurrentDataObject = data
			console.log data.status
			###if (data.status)
				A2Cribs.UIManager.Alert data.status
			else
				A2Cribs.UIManager.Alert data.error###
			$('#server-notice').dialog2("close");
			#window.location.href= '/dashboard'
			#if data.registerStatus == 1
			#	window.location.href= '/dashboard'
			#else
			#	$('#registerStatus').empty()
			


