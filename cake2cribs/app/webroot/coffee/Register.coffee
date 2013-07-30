class A2Cribs.Register
	@RedirectUrl = null

	@setupUI:() ->
		$('#registerForm').submit (e) =>
			e.preventDefault()
			@cribspotRegister() 

	###
	Open register modal and feed a specific url to redirect to after register is successful
	###
	@InitRegister: (url=null) ->
		$("#signupModal").modal("show")
		A2Cribs.Register.RedirectUrl = '/dashboard?post_redirect=true'

			
	@cribspotRegister:() ->
		url = "/users/AjaxRegister"
		request_form =  $('#registerForm').serializeArray()
		request_data = {
			User: {
				email: $.trim(request_form[0]['value'])
				password: $.trim(request_form[1]['value'])
				first_name: $.trim(request_form[3]['value'])
				last_name: $.trim(request_form[4]['value'])
			}
			
		}
		$.post url, request_data, (response) =>
			data = JSON.parse response
			console.log data
			if data.success != undefined and data.success != null
				window.location.href = '/users/login?register_success=true'
			else if data.error_type == 'EMAIL_EXISTS'
				A2Cribs.UIManager.Alert data.error
				$('#inputEmail').val("")
			else
				#add selective field highlighting here based on error message
				if(typeof data.validation.email != 'undefined')
					$('#inputEmail').effect "highlight", {color:"#FF0000"}, 3000
					$('#registerStatus').append '<p>' + data['email'][0] + '<p>'
				if(typeof data.validation.first_name != 'undefined')
					$('#inputFirstName').effect "highlight", {color:"#FF0000"}, 3000
					$('#registerStatus').append '<p>' + data['first_name'][0] + '<p>'
				if(typeof data.validation.last_name != 'undefined')
					$('#inputLastName').effect "highlight", {color:"#FF0000"}, 3000
					$('#registerStatus').append '<p>' + data['last_name'][0] + '<p>'
				if(typeof data.validation.password != 'undefined')
					$('#registerStatus').append '<p>' + data['password'][0] + '<p>'
					$('#inputPassword').effect "highlight", {color:"#FF0000"}, 3000
					$('#confirmPassword').effect "highlight", {color:"#FF0000"}, 3000		
				$('#loginStatus').effect "highlight", {color:"#FF0000"}, 3000
			



