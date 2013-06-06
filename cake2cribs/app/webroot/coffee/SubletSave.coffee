class A2Cribs.SubletSave

	setupUI: (div) ->
		if not A2Cribs.Geocoder?
			A2Cribs.Geocoder = new google.maps.Geocoder()
		@div = div

		div.find("#Sublet_short_description").keyup ()->
			if $(@).val().length >= 160
				$(@).val($(@).val().substr(0, 160))
			div.find("#desc-char-left").text(160 - $(@).val().length)

		div.find("#Sublet_utility_type_id").change =>
			if +div.find("#Sublet_utility_type_id").val() is 1 # if utilities are included
				div.find("#Sublet_utility_cost").val "0"

		div.find("#Housemate_student_type_id").change =>
			if +div.find("#Housemate_student_type_id").val() is 1 # if Graduate
				@div.find("#Housemate_year").val 0

		div.find(".required").keydown ->
			$(this).parent().removeClass "error"

		div.find(".date_field").datepicker();

		@MiniMap = new A2Cribs.MiniMap div

		@PhotoManager = new A2Cribs.PhotoManager div


		A2Cribs.Map.LoadTypeTables()



	###
	Called before advancing steps
	Returns true if validations pass; false otherwise
	###
	Validate: (step_) ->
		if step_ >= 1
			if not @ValidateStep1()
				return false
		if step_ >= 2
			if not @ValidateStep2()
				return false
		if step_ >= 3
			if not @ValidateStep3()
				return false

		return true

	ValidateStep1: () ->
		isValid = yes
		A2Cribs.UIManager.CloseLogs()
		if not @div.find('#Marker_street_address').val()
			A2Cribs.UIManager.Error "Please place your street address on the map using the Place On Map button."
			@div.find('#Marker_street_address').parent().addClass "error"
			isValid = no
		if not @div.find('#University_name').val()
			A2Cribs.UIManager.Error "You need to select a university."
			@div.find('#University_name').parent().addClass "error"
			isValid = no
		if @div.find('#Marker_building_type_id').val().length is 0
			A2Cribs.UIManager.Error "You need to select a building type."
			@div.find('#Marker_building_type_id').parent().addClass "error"
			isValid = no
		if @div.find('#Sublet_unit_number').val().length >= 249
			A2Cribs.UIManager.Error "Your unit number is too long."
			@div.find('#Sublet_unit_number').parent().addClass "error"
			isValid = no
		if @div.find('#Marker_alternate_name').val().length >= 249
			A2Cribs.UIManager.Error "Your alternate name is too long."
			@div.find('#Marker_alternate_name').parent().addClass "error"
			isValid = no
		
		return isValid

	ValidateStep2: () ->
		#begin the validations
		isValid = yes
		A2Cribs.UIManager.CloseLogs()
		parsedBeginDate = new Date Date.parse @div.find('#Sublet_date_begin').val()
		parsedEndDate = new Date Date.parse @div.find('#Sublet_date_end').val()
		todayDate = new Date();
		if parsedBeginDate.toString() == "Invalid Date" or parsedEndDate.toString() == "Invalid Date"
			A2Cribs.UIManager.Error "Please enter a valid date."
			@div.find('#Sublet_date_begin').parent().addClass "error"
			@div.find('#Sublet_date_end').parent().addClass "error"
			isValid = no
		else if parsedEndDate.valueOf() <= parsedBeginDate.valueOf() #or parsedBeginDate.valueOf() <= todayDate.valueOf()
			A2Cribs.UIManager.Error "Please enter a valid date."
			@div.find('#Sublet_date_begin').parent().addClass "error"
			@div.find('#Sublet_date_end').parent().addClass "error"
			isValid = no
		if (!@div.find('#Sublet_number_bedrooms').val() || isNaN(parseInt(@div.find("#Sublet_number_bedrooms").val())) || @div.find('#Sublet_number_bedrooms').val() <=0 || @div.find('#Sublet_number_bedrooms').val() >=30)
			A2Cribs.UIManager.Error "Please enter a valid number of bedrooms."
			@div.find('#Sublet_number_bedrooms').parent().addClass "error"
			isValid = no
		if (!@div.find('#Sublet_price_per_bedroom').val() || isNaN(parseInt(@div.find("#Sublet_price_per_bedroom").val())) || @div.find('#Sublet_price_per_bedroom').val() < 1 || @div.find('#Sublet_price_per_bedroom').val() >=20000)
			A2Cribs.UIManager.Error "Please enter a valid price per bedroom."
			@div.find('#Sublet_price_per_bedroom').parent().parent().addClass "error"
			isValid = no
		if @div.find('#Sublet_short_description').val().length is 0 
			A2Cribs.UIManager.Error "Please enter a description."
			@div.find('#Sublet_short_description').parent().addClass "error"
			isValid = no
		if (!@div.find('#Sublet_utility_cost').val()|| isNaN(parseInt(@div.find("#Sublet_utility_cost").val())) || @div.find('#Sublet_utility_cost').val()<0 || @div.find('#Sublet_utility_cost').val() >=50000)
			A2Cribs.UIManager.Error "Please enter a valid utility cost."
			@div.find('#Sublet_utility_cost').parent().addClass "error"
			isValid = no
		if (!@div.find('#Sublet_deposit_amount').val() || isNaN(parseInt(@div.find("#Sublet_deposit_amount").val())) || @div.find('#Sublet_deposit_amount').val()<0 || @div.find('#Sublet_deposit_amount').val() >=50000)
			A2Cribs.UIManager.Error "Please enter a valid deposit amount."
			@div.find('#Sublet_deposit_amount').parent().parent().addClass "error"
			isValid = no
		descLength = @div.find('#Sublet_additional_fees_description').val().length
		if (descLength >=161)
			A2Cribs.UIManager.Error "Please keep the additional fees description under 160 characters."
			@div.find('#Sublet_additional_fees_description').parent().addClass "error"
			isValid = no
		if descLength > 0 
			if (!@div.find('#Sublet_additional_fees_amount').val() || isNaN(parseInt(@div.find("#Sublet_additional_fees_amount").val())) || @div.find('#Sublet_additional_fees_amount').val()<0 || @div.find('#Sublet_additional_fees_amount').val() >=50000)
				A2Cribs.UIManager.Error "Please enter a valid additional fees amount."
				@div.find('#Sublet_additional_fees_amount').parent().addClass "error"
				isValid = no
		if @div.find("#Sublet_furnished_type_id").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with the furniture."
			@div.find('#Sublet_furnished_type_id').parent().addClass "error"
			isValid = no
		if @div.find("#Sublet_utility_type_id").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with the utilities."
			@div.find('#Sublet_utility_type_id').parent().addClass "error"
			isValid = no
		if @div.find("#Sublet_parking").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with parking."
			@div.find('#Sublet_parking').parent().addClass "error"
			isValid = no
		if @div.find("#Sublet_ac").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with parking."
			@div.find('#Sublet_ac').parent().addClass "error"
			isValid = no
		if @div.find("#Sublet_bathroom_type_id").val().length is 0
			A2Cribs.UIManager.Error "Please describe the situation with your bathroom."
			@div.find('#Sublet_bathroom_type_id').parent().addClass "error"
			isValid = no
		return isValid

	ValidateStep3: () ->
		isValid = yes
		if @div.find('#Housemate_quantity').val().length is 0 # Housemates quantity is empty
			isValid = no
		else
			if +@div.find('#Housemate_quantity').val() isnt 0 # More than 1 Housemate
				if @div.find('#Housemate_enrolled option:selected').text().length is 0 # Check if enrolled is selected
					isValid = no
				else if +@div.find('#Housemate_enrolled').val() is 1 # If the students are enrolled
					if +@div.find('#Housemate_student_type_id').val() is 0 # Check if student type selected
						isValid = no
					else if +@div.find('#Housemate_student_type_id').val() isnt 1 # Is not Graduate
						if +@div.find('#Housemate_year').val() is 0 # Make sure year is selected
							isValid = no
					if +@div.find('#Housemate_gender_type_id').val() is 0 # Gender of housemate(s)
						isValid = no
					if @div.find('#Housemate_major').val().length >= 255 # Major of housemate(s)
						isValid = no
		
		return isValid

	Reset: () ->
		@ResetAllInputFields
		@PhotoManager.Reset()

	###
	Reset all input fields for a new sublet posting process
	###
	ResetAllInputFields: () ->
		@div.find('input:text').val '' # Erase all inputs
		@div.find('input:hidden').val '' # Erase all inputs
		@div.find('select option:first-child').attr "selected", "selected" # all dropdowns to first option

	###
	Submits sublet to backend to save
	Assumes all front-end validations have been passed.
	###
	Save: (subletObject, success = null) ->
		url = "/sublets/ajax_submit_sublet"
		$.post url, subletObject, (response) =>
			data = JSON.parse response
			console.log data.status
			if data.redirect?
				window.location = data.redirect
			if data.status?
				A2Cribs.UIManager.Success data.status
				A2Cribs.ShareManager.SavedListing = data.newid
				if success?
					success data.newid
			else
				A2Cribs.UIManager.Alert data.error

	###
	Returns an object containing all sublet data from all 4 steps.
	###
	GetSubletObject: () ->
		for k,v of A2Cribs.SubletObject
			for p,q of v
				console.log k + "_" + p
				A2Cribs.SubletObject[k][p] = 0
				input = @div.find("#" + k + "_" + p)
				if input?
					if "checkbox" is input.attr "type" 
						A2Cribs.SubletObject[k][p] = input.prop "checked"
					else if input.hasClass "date_field"
						A2Cribs.SubletObject[k][p] = @GetMysqlDateFormat input.val()
					else
						A2Cribs.SubletObject[k][p] = input.val()

		A2Cribs.SubletObject.Image = @PhotoManager.GetPhotos()

		return A2Cribs.SubletObject

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


