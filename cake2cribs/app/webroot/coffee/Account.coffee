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
			veripanel.find('#veri-email i:last-child').removeClass('unverified').addClass('verified')
		if my_verification_info.verified_edu
			veripanel.find('#veri-edu i:last-child').removeClass('unverified').addClass('verified')
		if my_verification_info.verified_fb
			veripanel.find('#veri-fb  i:last-child').removeClass('unverified').addClass('verified')
		else
			$('#veri-fb').append("<a href = '#'>Verify?</a>").click @FacebookConnect

		if my_verification_info.verified_tw
			veripanel.find('#veri-tw i:last-child').removeClass('unverified').addClass('verified')
		else
			# Generate a twitter verification link server side and add it to the twitter verification panel
			url = myBaseUrl + 'account/getTwitterVerificationUrl'
			$.get url, (response)->
				twitter_verification_url = JSON.parse(response).twitter_url
				
				# verification_link = $()
				$('#veri-tw').append("<a href = '#{twitter_verification_url}'>Verify?</a>")


		$('.veridd').each (index, element)=>
			$(element).tooltip({'title': 'Verify?', 'trigger': 'hover'})


	@Direct: (directive)->

	@SaveAccount:()->
		$('#save_btn').attr 'disabled','disabled'
		first_name = $('#first_name_input').val()
		last_name = $('#last_name_input').val()
		data = {
			'first_name': first_name,
			'last_name': last_name,
		}

		$.post myBaseUrl + 'users/ajaxEditUser', data, (response)->
			# console.log response
			json_response = JSON.parse(response)
			if json_response.success == 1
				alertify.success('Account Saved', 1500)
				# console.log JSON.parse(json_response.user)
			else
				alertify.error('Account Failed to Save: ' + json_response.message, 1500)


			$('#save_btn').removeAttr 'disabled'



	@FacebookConnect:()->
		FB.login (response)->
			$.ajax
				url: myBaseUrl + "account/verifyFacebook"
				data: {'signed_request':response.authResponse.signedRequest}
				type:"POST"
			# This may be bad if verify Facebook fails
			document.location.href = '/account'

	# @JSLoginCallback: (response) ->
	# 	if response.authResponse
	# 		FB.api('/me', A2Cribs.FacebookManager.APICallback)
	# 		$.ajax
	# 			url: myBaseUrl + "Verify/FacebookVerify"
	# 			type:"POST"
	# 		location.reload()
	# 	else
	# 		alert 'failed