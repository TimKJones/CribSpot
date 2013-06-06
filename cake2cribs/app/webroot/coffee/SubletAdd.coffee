class A2Cribs.SubletAdd
	@setupUI:() ->
		
		# modal_total_height = $('.modal').height()
		# page_height = window.innerHeight
		# # We want some margin on the top and bottom of the modal to be around 20px
		
		# dif = page_height - modal_total_height - (margin * 2)
		# console.log("window height: #{dif} modal_total_height: #{modal_total_height}")
		# modal_body.css 'height', dif + 'px'


		# $('sublet-register').css('height', '20px').css('overflow', 'hidden')



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

		$('#goToStep1').click (e) =>
			@backToStep1()

		$("#goToStep3").click (e) ->
			#begin the validations
			parsedBeginDate = new Date(Date.parse($('#SubletDateBegin').val()))
			parsedEndDate = new Date(Date.parse($('#SubletDateEnd').val()))
			todayDate = new Date();
			if parsedBeginDate.toString() == "Invalid Date" or parsedEndDate.toString() == "Invalid Date"
				A2Cribs.UIManager.Alert "Please enter a valid date."
			else if (parsedEndDate <= parsedBeginDate || parsedBeginDate.valueOf() <= todayDate.valueOf())
				A2Cribs.UIManager.Alert "Please enter a valid date."
			else if (!$('#SubletNumberBedrooms').val() || $('#SubletNumberBedrooms').val() <=0 || $('#SubletNumberBedrooms').val() >=30)
				A2Cribs.UIManager.Alert "Please enter a valid number of bedrooms."
			else if (!$('#SubletPricePerBedroom').val() || $('#SubletPricePerBedroom').val() < 1 || $('#SubletPricePerBedroom').val() >=20000)
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
 
		$('#finishSubletAdd').click (e) =>
			if (!$('#HousemateQuantity').val() || $('#HousemateQuantity').val() >= 50 || $('#HousemateQuantity').val() < 0)
				A2Cribs.UIManager.Alert "Please enter a valid housemate quantity."
			else if ($('#HousemateMajor').val().length >= 254)
				A2Cribs.UIManager.Alert "Please keep the majors description under 255 characters."
			else
				A2Cribs.SubletEdit.CacheStep3Data()
				e.preventDefault()
				@subletAddStep3()

		$("#finishShare").click (e) =>
			$('#server-notice').dialog2("close");
			if !isNaN A2Cribs.ShareManager.SavedListing
				window.location.href = "/sublets/show/" + A2Cribs.ShareManager.SavedListing

		#refresh UI dates
		oldBeginDate = new Date($('#SubletDateBegin').val())
		$('#SubletDateBegin').val(oldBeginDate.toDateString())
		oldEndDate = new Date($('#SubletDateEnd').val())
		$('#SubletDateEnd').val(oldEndDate.toDateString())



	@InitPostingProcess:(e=null) ->
		A2Cribs.Cache.SubletEditInProgress = new A2Cribs.SubletInProgress()
		subletmodal = $("<div/>").dialog2({
			title: "Post a sublet", 
			content: "/Sublets/ajax_add", 
			id: "server-notice",
			closeOnOverlayClick: false,
			closeOnEscape: false,
			removeOnClose: true
		});

		@resizeModal(subletmodal)

		$(window).resize ()=>
			@resizeModal(subletmodal)

		if (e != null)
			e.preventDefault();

	@resizeModal:(modal_body)->
		# We ened to size the modal window in a way that the full modal is displayed
		# and the contents inside of it just scroll.

		parent_modal = modal_body.parent('.modal')
		margin = 20
		
		target_modal_size = window.innerHeight - (2 * margin)
		header_footer_size = parent_modal.height() - modal_body.height()
		new_body_height = target_modal_size-header_footer_size
		modal_body.css 'height', new_body_height + 'px'


	@backToStep1: () ->
		$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add"});

	@backToStep2: () ->
		$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add2"});

	@backToStep3: () ->
		$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add3"});

	@subletAddStep1:() ->
		A2Cribs.SubletEdit.CacheStep1Data()
		$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add2"});

	@subletAddStep2:() ->
		#validations go here
		A2Cribs.SubletEdit.CacheStep2Data()
		$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add3", });

	@subletAddStep3:() ->
		url = "/sublets/ajax_submit_sublet"

		#validations go here

		# use a flag to make sure we don't allow the user to double post listing 
		if not @postingDataInProgress? or @postingDataInProgress == false
			@postingDataInProgress = true
			
			$.post url, A2Cribs.Cache.SubletEditInProgress, (response) =>
				data = JSON.parse response
				console.log data.status
				if (data.status)
					A2Cribs.UIManager.Alert data.status
					A2Cribs.ShareManager.SavedListing = data.newid
					$('#server-notice').dialog2("options", {content:"/Sublets/ajax_add4"});
				else
					A2Cribs.UIManager.Alert data.error

				@postingDataInProgress = true

		else
			false # prevents double clicks the finish button

	@GetFormattedDate:(date) ->
		month = date.getMonth() + 1
		if month < 10
			month = "0" + month
		day = date.getDate()
		if day < 10
			day = "0" + day
		year = date.getUTCFullYear()
		beginDateFormatted = month + "/" + day + "/" + year
