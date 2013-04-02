class A2Cribs.Register



	@setupUI:() ->
		$('#registerForm').submit (e) =>
			e.preventDefault()
			@cribspotRegister() 



			
	@cribspotRegister:() ->
		url = "/users/ajaxRegister"
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
			console.log request_data
			console.log response
			data = JSON.parse response
			console.log data
			if data.registerStatus == 1
				window.location.href= '/dashboard'
			else
				$('#registerStatus').empty()
			
				#add selective field highlighting here based on error message
				if(typeof data.email != 'undefined')
					$('#inputEmail').effect "highlight", {color:"#FF0000"}, 3000
					$('#registerStatus').append '<p>' + data['email'][0] + '<p>'
				if(typeof data.first_name != 'undefined')
					$('#inputFirstName').effect "highlight", {color:"#FF0000"}, 3000
					$('#registerStatus').append '<p>' + data['first_name'][0] + '<p>'
				if(typeof data.last_name != 'undefined')
					$('#inputLastName').effect "highlight", {color:"#FF0000"}, 3000
					$('#registerStatus').append '<p>' + data['last_name'][0] + '<p>'
				if(typeof data.password != 'undefined')
					$('#registerStatus').append '<p>' + data['password'][0] + '<p>'
					$('#inputPassword').effect "highlight", {color:"#FF0000"}, 3000
					$('#confirmPassword').effect "highlight", {color:"#FF0000"}, 3000		
				$('#loginStatus').effect "highlight", {color:"#FF0000"}, 3000
			



