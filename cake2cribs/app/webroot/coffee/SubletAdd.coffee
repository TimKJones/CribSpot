class A2Cribs.SubletAdd

	@setupUI:() ->
		$('#finishSubletAdd').click (e) =>
			e.preventDefault()
			@subletAddStep3()

		$('#goToStep2').click (e) =>
			e.preventDefault()
			#$('#server-notice').dialog2("options", {content:"Sublets/ajax_add2"});

			@subletAddStep1() 

		$('#goToStep1').click (e) =>
			e.preventDefault()
			@subletAddStep2()



			
	@subletAddStep1:() ->
		url = "sublets/ajax_add_create"
		request_data = {
			Sublet: {
				university_id: parseInt(window.universitiesArray.indexOf($('#universitiesInput').val()) + parseInt(window.universitiesMap[0].University.id))
				university: $('#universitiesInput').val()
				unit_number: $('#SubletUnitNumber').val()
				address: $("#addressToMark").val()
				building_type_id: $('#SubletBuildingTypeId').val()
				name: $('#SubletName').val()
				latitude: $('#updatedLat').text()
				longitude: $('#updatedLong').text()
			}
			CurrentStep: 1
			#console.log(universitiesArray.indexOf(request_data.Sublet.university));
			
		}
		console.log(request_data)

		$.post url, request_data, (response) =>
			console.log(response)
			data = JSON.parse response
			console.log data
			$('#server-notice').dialog2("options", {content:"Sublets/ajax_add2"});
			#window.location.href= '/dashboard'
			#if data.registerStatus == 1
			#	window.location.href= '/dashboard'
			#else
			#	$('#registerStatus').empty()

	@subletAddStep2:() ->
		url = "sublets/ajax_add_create"
		request_data = {
			Sublet: {
				date_begin: $('#SubletDateBegin').val()
				date_end: $('#SubletDateEnd').val()
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

		$.post url, request_data, (response) =>
			console.log(response)
			data = JSON.parse response
			console.log data
			$('#server-notice').dialog2("options", {content:"Sublets/ajax_add3"});
			#window.location.href= '/dashboard'
			#if data.registerStatus == 1
			#	window.location.href= '/dashboard'
			#else
			#	$('#registerStatus').empty()

	@subletAddStep3:() ->
		url = "sublets/ajax_add_create"
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

		$.post url, request_data, (response) =>
			console.log(response)
			data = JSON.parse response
			console.log data
			alert "You should be finished by now."
			$('#server-notice').dialog2("options", {content:"Sublets/ajax_add4"});
			#window.location.href= '/dashboard'
			#if data.registerStatus == 1
			#	window.location.href= '/dashboard'
			#else
			#	$('#registerStatus').empty()
			


