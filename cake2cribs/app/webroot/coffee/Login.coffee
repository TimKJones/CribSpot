class A2Cribs.Login

	@LANDING_URL = "localhost"
	@HTTP_PREFIX = "http://"


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
				url = document.URL
				if url == @LANDING_URL or url == @HTTP_PREFIX + @LANDING_URL or url == @HTTP_PREFIX + @LANDING_URL + '/'
					window.location.href = '/dashboard'
				else
					window.location.href= document.URL
			else
				$('#loginStatus').html "Invalid login."
				$('#loginStatus').effect "highlight", {color:"#FF0000"}, 3000
			



