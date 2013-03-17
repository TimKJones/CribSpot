class A2Cribs.Login



	@setupUI:() ->
		$('#loginForm').submit (e) =>
			e.preventDefault()
			@cribspotLogin() 



			
	@cribspotLogin:() ->
		url = '/' + "users/ajaxLogin"
		request_data = {
			User: {
				email: $('#inputEmail').val()
				password: $('#inputPassword').val()
			}
			
		}
		$.post url, request_data, (response) =>
			console.log response
			data = JSON.parse response
			console.log data
			if data.loginStatus == 1
				window.location.href= '/dashboard'
			else
				$('#loginStatus').html "Invalid login."
				$('#loginStatus').effect "highlight", {color:"#FF0000"}, 3000
			



