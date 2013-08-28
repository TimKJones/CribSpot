class A2Cribs.Account
	@setupUI:()->
		# Get all the universities data
		url = myBaseUrl + "university/getAll/"
		$.get url, (data)=>
			@UniversityData = JSON.parse data
			@UniversityNames = []
			@UniversityID = []
			_.each @UniversityData, (value, key, list)=>
				@UniversityNames[key] = value['University']['name']
				@UniversityID[key] = value['University']['id']

			$('#university').typeahead {source: @UniversityNames}
			$('#save_btn').click =>
				@SaveAccount()

		my_verification_info = A2Cribs.VerifyManager.getMyVerification()
		veripanel = $('#my-verification-panel')
		
		if my_verification_info.verified_email
			veripanel.find('#veri-email i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign')
		if my_verification_info.verified_edu
			veripanel.find('#veri-edu i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign')
		if my_verification_info.verified_fb
			veripanel.find('#veri-fb  i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign')
		else
			$('#veri-fb').append("<a href = '#'>Verify?</a>").click @FacebookConnect

		if my_verification_info.verified_tw
			veripanel.find('#veri-tw i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign')
		else
			# Generate a twitter verification link server side and add it to the twitter verification panel
			url = myBaseUrl + 'account/getTwitterVerificationUrl'
			$.get url, (response)->
				twitter_verification_url = JSON.parse(response).twitter_url
				
				# verification_link = $()
				$('#veri-tw').append("<a href = '#{twitter_verification_url}'>Verify?</a>")


		$('.veridd').each (index, element)=>
			$(element).tooltip({'title': 'Verify?', 'trigger': 'hover'})

		$('#changePasswordButton').click =>
			@ChangePassword($('#changePasswordButton'), $('#new_password').val(), $('#confirm_password').val())
		$('#VerifyUniversityButton').click =>
			@VerifyUniversity()
		$('#changePhoneBtn').click =>
			@SavePhone()
		$('#changeAddressBtn').click =>
			@SaveAddress()

	@SavePhone: () ->
		phone = $("#phone_input").val()
		if @ValidatePhone phone
			pair = 
				'phone':phone
			@SaveAccount pair
		else
			A2Cribs.UIManager.Error "Invalid phone number"

	@ValidatePhone: (phone) ->
		phone = phone.replace(/[^0-9]/g, '')
		return phone.length == 10


	@SaveAddress: () ->
		street_address = $("#street_address_input").val()
		city = $("#city_address_input").val()
		pair = 
			'street_address':street_address
			'city':city
		@SaveAccount pair

	@Direct: (directive)->

	@VerifyUniversity: () ->
		$('#VerifyUniversityButton').attr 'disabled', 'disabled'
		university_email = $('#university_email').val() 
		data = {
			'university_email': university_email
		}
		if ( university_email.search('.edu') != -1)
			
			$.post myBaseUrl + 'users/verifyUniversity', data, (response) ->
				console.log(data)
				json_response = JSON.parse(response)

				if json_response.success == 1
					A2Cribs.UIManager.Error 'Please check your email for a verification link.'
				else
					A2Cribs.UIManager.Error 'Verification not successful: ' + json_response.message

				$('#VerifyUniversityButton').removeAttr 'disabled'
		else
			A2Cribs.UIManager.Error 'Please enter a university email.'
			

		

	@ChangePassword: (change_password_button, new_password, confirm_password, id=null, reset_token=null, redirect=null) ->
		change_password_button.attr 'disabled','disabled'
		data =
			'new_password' : new_password,
			'confirm_password': confirm_password
		if id != null and reset_token != null
			data['id'] = id
			data['reset_token'] = reset_token

		if new_password != confirm_password
			A2Cribs.UIManager.Alert "Passwords do not match."
			return

		$.post myBaseUrl + 'users/AjaxChangePassword', data, (response) ->
			response = JSON.parse(response)

			if response.error == undefined
				if id == null and reset_token == null
					alertify.success 'Password Changed', 3000
					if redirect != null
						window.location.href = redirect
				else
					#redirect user to dashboard
					window.location.href = '/dashboard'
			else
				A2Cribs.UIManager.Alert response.error

			change_password_button.removeAttr 'disabled'

	@SaveAccount:(keyValuePairs = null)->
		###$('#save_btn').attr 'disabled','disabled'
		first_name = $('#first_name_input').val()
		last_name = $('#last_name_input').val()
		data = {
			'first_name': first_name,
			'last_name': last_name,
		}
		###

		$.post myBaseUrl + 'users/AjaxEditUser', keyValuePairs, (response)->
			# console.log response
			json_response = JSON.parse(response)
			if json_response.error == undefined
				alertify.success('Account Saved', 3000)
				# console.log JSON.parse(json_response.user)
			else
				A2Cribs.UIManager.Error 'Account Failed to Save: ' + json_response.error.message


			$('#save_btn').removeAttr 'disabled'


	@FacebookConnect:()->
		FB.login (response)->
			$.ajax
				url: myBaseUrl + "account/verifyFacebook"
				data: {'signed_request':response.authResponse.signedRequest}
				type:"POST"
			# This may be bad if verify Facebook fails
			document.location.href = '/account'

	###
	Submits email address for which to reset password.
	###
	@SubmitResetPassword: (email) ->
		data = 'email=' + $("#UserEmail").val()
		$.post '/users/AjaxResetPassword', data, (response) =>
			data = JSON.parse response
			if data.success?
				A2Cribs.UIManager.Alert "Email sent to reset password!"
				return false
			else
	        	A2Cribs.UIManager.Error data.error
	        	return false

	# @JSLoginCallback: (response) ->
	# 	if response.authResponse
	# 		FB.api('/me', A2Cribs.FacebookManager.APICallback)
	# 		$.ajax
	# 			url: myBaseUrl + "Verify/FacebookVerify"
	# 			type:"POST"
	# 		location.reload()
	# 	else
	# 		alert 'failed