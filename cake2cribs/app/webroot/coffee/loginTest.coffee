class A2Cribs.loginTest

	@FacebookLogin: () ->
		FB.login @FacebookLoginCallback, scope: 'email'

	@FacebookLoginCallback: (response) ->
		if response.authResponse
			console.log response
			window.location.reload()
		else
			console.log 'User canceled login'

	@FacebookLogout: () ->
		$.ajax
			url: myBaseUrl + "Users/FacebookLogout"
			type:"GET"
			context: this
			success: (response) ->
				console.log response
