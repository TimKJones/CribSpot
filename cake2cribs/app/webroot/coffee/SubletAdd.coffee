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
		A2Cribs.SubletEdit.CacheStep1Data()
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
		#validations go here
		A2Cribs.SubletEdit.CacheStep2Data()
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

		#validations go here
		$.post url, A2Cribs.Cache.SubletEditInProgress, (response) =>
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
