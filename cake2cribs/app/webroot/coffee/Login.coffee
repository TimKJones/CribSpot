class A2Cribs.Login

	@LANDING_URL = "cribspot.com"
	@HTTP_PREFIX = "http://"


	@setupUI:() ->
		$('#loginForm').submit (e) =>
			e.preventDefault()
			@cribspotLogin()
			
	@cribspotLogin:() ->
		url = myBaseUrl + "users/AjaxLogin"
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
			if data.error == undefined
				url = document.URL
				if url == @LANDING_URL or url == @HTTP_PREFIX + @LANDING_URL or url == @HTTP_PREFIX + @LANDING_URL + '/'
					window.location.href = '/dashboard'
				else
					window.location.href= document.URL
			else
				if data.error_type == "EMAIL_UNVERIFIED"
					A2Cribs.UIManager.Alert data.error
					$('#loginStatus').html "<a href='users/verify'>Resend verification email</a>"
				else
					$('#loginStatus').html data.error
					$('#loginStatus').effect "highlight", {color:"#FF0000"}, 3000
			



