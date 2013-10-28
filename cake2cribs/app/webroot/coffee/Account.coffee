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


		$('.veridd').each (index, element)=>
			$(element).tooltip({'title': 'Verify?', 'trigger': 'hover'})

		$('#changePasswordButton').click (event) =>
			$(event.delegateTarget).button('loading')
			@ChangePassword($('#changePasswordButton'), $('#new_password').val(), $('#confirm_password').val())
			.always ->
				$(event.delegateTarget).button('reset')
		$('#VerifyUniversityButton').click (event) =>
			@VerifyUniversity()
		$('#changePhoneBtn').click (event) =>
			$(event.delegateTarget).button('loading')
			@SavePhone()
			.always ->
				$(event.delegateTarget).button('reset')
		$('#changeAddressBtn').click (event) =>
			$(event.delegateTarget).button('loading')
			@SaveAddress()
			.always ->
				$(event.delegateTarget).button('reset')
		$('#changeCompanyNameBtn').click (event) =>
			$(event.delegateTarget).button('loading')
			@SaveCompanyName()
			.always ->
				$(event.delegateTarget).button('reset')
		$('#changeFirstLastNameButton').click (event) =>
			$(event.delegateTarget).button('loading')
			@SaveFirstLastName()
			.always ->
				$(event.delegateTarget).button('reset')

	@SaveFirstLastName: () ->
			pair = 
				'first_name':$("#first_name_input").val()
				'last_name':$("#last_name_input").val()
			return @SaveAccount pair, $("#changeFirstLastNameButton")

	@SaveCompanyName: () ->
			pair = 
				'company_name':$("#company_name_input").val()
			return @SaveAccount pair, $("#changeCompanyNameButton")

	@SavePhone: () ->
		phone = $("#phone_input").val()
		if @ValidatePhone phone
			pair = 
				'phone':phone
			return @SaveAccount pair, $("#changePhoneBtn")
		else
			A2Cribs.UIManager.Error "Invalid phone number"
			return (new $.Deferred()).reject()

	@ValidatePhone: (phone) ->
		phone = phone.replace(/[^0-9]/g, '')
		return phone.length == 10


	@SaveAddress: () ->
		street_address = $("#street_address_input").val()
		city = $("#city_address_input").val()
		pair = 
			'street_address':street_address
			'city':city
		return @SaveAccount pair, $("#changeAddressBtn")

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
		@_change_password_deferred = new $.Deferred()

		data =
			'new_password' : new_password,
			'confirm_password': confirm_password
		if id != null and reset_token != null
			data['id'] = id
			data['reset_token'] = reset_token

		if new_password.length < 5
			A2Cribs.UIManager.Alert "Password must be at least 6 characters long."
			return  @_change_password_deferred.reject()

		if new_password isnt confirm_password
			A2Cribs.UIManager.Alert "Passwords do not match."
			return @_change_password_deferred.reject()

		$.ajax
			url: myBaseUrl + 'users/AjaxChangePassword'
			data: data
			type: "POST"
			success: (response) =>
				response = JSON.parse(response)
				if response.error?
					A2Cribs.UIManager.Alert response.error
					return @_change_password_deferred.reject()
				else
					if id == null and reset_token == null
						alertify.success 'Password Changed', 3000
						if redirect != null
							window.location.href = redirect
					else
						#redirect user to dashboard
						window.location.href = '/dashboard'
					return @_change_password_deferred.resolve()

			error: () =>
				return @_change_password_deferred.reject()

		return @_change_password_deferred.promise()

	@SaveAccount:(keyValuePairs = null, button=null)->
		return $.post myBaseUrl + 'users/AjaxEditUser', keyValuePairs, (response)->
			# console.log response
			json_response = JSON.parse(response)
			if json_response.error == undefined
				alertify.success('Account Saved', 3000)
				# console.log JSON.parse(json_response.user)
			else
				A2Cribs.UIManager.Error 'Account Failed to Save: ' + json_response.error.message

			if button?
				button.removeAttr 'disabled'


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