class A2Cribs.SubletAdd
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
				@subletAddStep1()

		$("#backToStep2").click (e) =>
			@backToStep2()

		$("#backToStep3").click (e) =>
			@backToStep3()

		$('#goToStep1').click (e) =>
			@backToStep1()

		$("#goToStep3").click (e) ->	
			#begin the validations
			parsedBeginDate = new Date(Date.parse($('#SubletDateBegin').val()))
			parsedEndDate = new Date(Date.parse($('#SubletDateEnd').val()))
			todayDate = new Date()
			if parsedBeginDate.toString() == "Invalid Date" or parsedEndDate.toString() == "Invalid Date"
				A2Cribs.UIManager.Alert "Please enter a valid date."
			else if (parsedEndDate.valueOf() <= parsedBeginDate.valueOf() && parsedBeginDate.valueOf() <= todayDate.valueOf())
				A2Cribs.UIManager.Alert "Please enter a valid date."
			else if (!$('#SubletNumberBedrooms').val() || $('#SubletNumberBedrooms').val() <=0 || $('#SubletNumberBedrooms').val() >=30)
				A2Cribs.UIManager.Alert "Please enter a valid number of bedrooms."
			else if (!$('#SubletPricePerBedroom').val() || $('#SubletPricePerBedroom').val() < 0 || $('#SubletPricePerBedroom').val() >=20000)
				A2Cribs.UIManager.Alert "Please enter a valid price per bedroom."
			else if ($('#SubletDescription').val().length >=161)
				A2Cribs.UIManager.Alert "Please keep the short description under 160 characters."
			else if (!$('#SubletUtilityCost').val() || $('#SubletUtilityCost').val()<0 || $('#SubletUtilityCost').val() >=50000)
				A2Cribs.UIManager.Alert "Please enter a valid utility cost."
			else if (!$('#SubletDepositAmount').val() || $('#SubletDepositAmount').val()<0 || $('#SubletDepositAmount').val() >=50000)
				A2Cribs.UIManager.Alert "Please enter a valid deposit amount."
			else if ($('#SubletAdditionalFeesDescription').val().length >=161)
				A2Cribs.UIManager.Alert "Please keep the additional fees description under 160 characters."
			else if (!$('#SubletAdditionalFeesAmount').val() || $('#SubletAdditionalFeesAmount').val()<0 || $('#SubletAdditionalFeesAmount').val() >=50000)
				A2Cribs.UIManager.Alert "Please enter a valid additional fees amount."
			else
				A2Cribs.SubletAdd.subletAddStep2()				
 
		$('#goToStep4').click (e) =>
			if (!$('#HousemateQuantity').val() || $('#HousemateQuantity').val() >= 50 || $('#HousemateQuantity').val() < 0)
				A2Cribs.UIManager.Alert "Please enter a valid housemate quantity."
			else if ($('#HousemateMajor').val().length >= 254)
				A2Cribs.UIManager.Alert "Please keep the majors description under 255 characters."
			else
				A2Cribs.SubletEdit.CacheStep3Data()
				e.preventDefault()
				@subletAddStep3()

		$('#goToStep5').click (e) =>
			@subletAddStep4()

		$("#finishShare").click (e) =>
			$('#server-notice').dialog2("close");

		#refresh UI dates
		oldBeginDate = new Date($('#SubletDateBegin').val())
		$('#SubletDateBegin').val(oldBeginDate.toDateString())
		oldEndDate = new Date($('#SubletDateEnd').val())
		$('#SubletDateEnd').val(oldEndDate.toDateString())



	@InitPostingProcess:(e=null) ->
		A2Cribs.Cache.SubletEditInProgress = new A2Cribs.SubletInProgress()
		@OpenStep1()

		if (e != null)
			e.preventDefault();

	@backToStep1: () ->
		@OpenStep1()

	@backToStep2: () ->
		@OpenStep2()

	@backToStep3: () ->
		@OpenStep3()

	@subletAddStep1:() ->
		@OpenStep2()
		A2Cribs.SubletEdit.CacheStep1Data()
		@OpenStep2()

	@subletAddStep2:() ->
		@OpenStep3()
		#validations go here
		A2Cribs.SubletEdit.CacheStep2Data()
		@OpenStep3()

	@subletAddStep3:() ->
		url = "/sublets/ajax_submit_sublet"

		#validations go here
		$.post url, A2Cribs.Cache.SubletEditInProgress, (response) =>
			data = JSON.parse response
			console.log data.status
			if data.status
				A2Cribs.ShareManager.SavedListing = data.newid
				@OpenStep4()
				A2Cribs.PhotoManager.LoadImages A2Cribs.ShareManager.SavedListing
			else
				A2Cribs.UIManager.Alert data.error
				$('#server-notice').dialog2("close");

	@subletAddStep4:() ->
		@OpenStep5()

	# Disable the address and map fields so the user can't change the location of the sublet
	# There will also be server side logic that will also prevent this.
	@DisableMarkerFields: () ->
		$("#addressToMark").attr 'disabled', 'disabled'
		A2Cribs.CorrectMarker.Disable()

	@GetFormattedDate:(date) ->
		month = date.getMonth() + 1
		if month < 10
			month = "0" + month
		day = date.getDate()
		if day < 10
			day = "0" + day
		year = date.getUTCFullYear()
		beginDateFormatted = month + "/" + day + "/" + year

	###
	For all open() functions, re-use existing modal if it has been created. Otherwise, create and save it.
	###
	@OpenStep1: () ->
		if A2Cribs.Cache.Step1Modal != undefined and A2Cribs.Cache.Step1Modal != null
			A2Cribs.Cache.Step1Modal.dialog2('open')
			alert "already exists"
		else
			A2Cribs.Cache.Step1Modal = $("<div/>").dialog2({
				title: "Post a sublet", 
				content: "/Sublets/ajax_add", 
				id: "server-notice"
			});

		#A2Cribs.Cache.NextModal = A2Cribs.Cache.Step1Modal


	@OpenStep2: () ->
		if A2Cribs.Cache.Step2Modal != undefined and A2Cribs.Cache.Step2Modal != null
			A2Cribs.Cache.Step2Modal.dialog2('open')
		else
			A2Cribs.Cache.Step2Modal = $('#server-notice').dialog2("options", {
				content:"/Sublets/ajax_add2",
				removeOnClose: false
			});

		#A2Cribs.Cache.NextModal = A2Cribs.Cache.Step2Modal

	@OpenStep3: () ->
		if A2Cribs.Cache.Step3Modal != undefined and A2Cribs.Cache.Step3Modal != null
			A2Cribs.Cache.Step3Modal.dialog2('open')
		else
			A2Cribs.SubletAdd.Step3Modal = $('#server-notice').dialog2("options", {
				content:"/Sublets/ajax_add3",
				removeOnClose: false
			});

		#A2Cribs.Cache.NextModal = A2Cribs.Cache.Step3Modal

	@OpenStep4: () ->
		if A2Cribs.Cache.Step4Modal != undefined and A2Cribs.Cache.Step4Modal != null
			A2Cribs.Cache.Step4Modal.dialog2('open')
		else
			A2Cribs.SubletAdd.Step4Modal = $('#server-notice').dialog2("options", {
				content:"/Sublets/ajax_add4",
				removeOnClose: false
			})

		#A2Cribs.Cache.NextModal = A2Cribs.Cache.Step4Modal

	@OpenStep5: () ->	
		if A2Cribs.Cache.Step5Modal != undefined and A2Cribs.Cache.Step5Modal != null
			A2Cribs.Cache.Step5Modal.dialog2('open')
		else	
			A2Cribs.SubletAdd.Step5Modal = $('#server-notice').dialog2("options", {
				content:"/Sublets/ajax_add5",
				removeOnClose: false
			});

		#A2Cribs.Cache.NextModal = A2Cribs.Cache.Step5Modal

	@ClosePreviousModal: () ->
		#if A2Cribs.Cache.CurrentModal != undefined and A2Cribs.Cache.CurrentModal != null
			#A2Cribs.Cache.CurrentModal.dialog2('close')
			#A2Cribs.Cache.CurrentModal = A2Cribs.Cache.NextModal