class A2Cribs.FacebookLogin

	@FacebookLogin: () ->
		FB.login @FacebookLoginCallback, scope: 'email'

	@FacebookLoginCallback: (response) ->
		if response.authResponse
			FB.api '/me', A2Cribs.loginTest.FacebookGetUserInfoCallback
		else
			console.log 'User canceled login'

	@FacebookGetUserInfoCallback: (response) ->
		if response.id == undefined
			return

		$.ajax
			url: myBaseUrl + "Users/FacebookLogin"
			type:"GET"
			context: this
			success: (response) ->
				console.log response

	@FacebookLogout: () ->
		$.ajax
			url: myBaseUrl + "Users/FacebookLogout"
			type:"GET"
			context: this
			success: (response) ->
				console.log response
