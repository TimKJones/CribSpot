class A2Cribs.Login

	@LANDING_URL = "cribspot.com"
	@HTTP_PREFIX = "http://"

	@setupUI:() ->
		# Div variable to have starting place to search the document
		@div = $("#login_signup")

		# Shows the signup page with a fade animation
		@div.find(".show_signup").click =>
			@div.find("#login_content").hide 'fade'
			@div.find("#signup_content").show 'fade'

		# Shows the login page with a fade animation
		@div.find(".show_login").click =>
			@div.find("#signup_content").hide 'fade'
			@div.find("#login_content").show 'fade'

		# Switches between different signups (property manager/student)
		@div.find(".user_types").click (event) =>
			target = $(event.target).closest "li"
			@div.find(".user_types").removeClass "active"
			$(target).addClass "active"
			@div.find(".signup").hide()
			@div.find("##{$(target).attr("id")}_signup").show()

		# Click and form handlers for login
		@div.find("#login_button").click @cribspotLogin
		@div.find("#login_content").submit @cribspotLogin

		# Click and form handlers for student user creation
		@div.find("#student_submit").click @CreateStudent
		@div.find("#student_signup").submit @CreateStudent

		# Click and form handlers for property manager user creation
		@div.find("#pm_submit").click @CreatePropertyManager
		@div.find("#pm_signup").submit @CreatePropertyManager

			
	@cribspotLogin:() ->
		url = myBaseUrl + "users/AjaxLogin"
		request_data = {
			User: {
				email: $('#inputEmail').val()
				password: $('#inputPassword').val()
			}
			
		}
		if request_data.User.email? and request_data.User.password?
			$.post url, request_data, (response) =>
				data = JSON.parse response
				if data.error?
					A2Cribs.UIManager.CloseLogs()
					A2Cribs.UIManager.Error data.error
					###
					TODO: GIVE USER THE OPTION TO RESEND CONFIRMATION EMAIL
					if data.error_type == "EMAIL_UNVERIFIED"
						A2Cribs.UIManager.Alert data.error
					###
				else
					window.location.href = '/dashboard'

		return false

	@ResendConfirmationEmail: (email) ->
		$.ajax 
			url: myBaseUrl + "users/ResendConfirmationEmail/" + email
			type:"POST"
			success:(response) ->
				response = JSON.parse response
				if response.error?
					A2Cribs.UIManager.Alert response.error

	validate = (user_type, required_fields) =>
		type_prefix = if user_type is 0 then "student_" else "pm_"
		A2Cribs.UIManager.CloseLogs()
		isValid = yes
		for field in required_fields
				if @div.find("##{type_prefix}#{field}").val().length is 0
					isValid = no
		if not isValid
			A2Cribs.UIManager.Error "Please fill in all of the fields!"
		return isValid


	# Static private function that creates and posts a user based on user_type
	createUser = (user_type, required_fields) =>
		type_prefix = if user_type is 0 then "student_" else "pm_"
		if validate user_type, required_fields
			# Check to see if confirm password matches the actual password
			if @div.find("##{type_prefix}password").val() isnt @div.find("##{type_prefix}confirm_password").val()
				A2Cribs.UIManager.Error "Make sure passwords match!"
			else
				# Create request data
				request_data =
					User:
						user_type: user_type
				# Loop through all the required fields and grab based on id's
				for field in required_fields
					request_data.User[field] = @div.find("##{type_prefix}#{field}").val()

				# Post the request data using AjaxRegister
				$.post "/users/AjaxRegister", request_data, (response) =>
					data = JSON.parse response
					if data.error?
						A2Cribs.UIManager.CloseLogs()
						A2Cribs.UIManager.Error data.error
					else
						window.location.href = '/dashboard'

	# Creates a Student user
	@CreateStudent: ->
		required_fields = ["email", "password", "first_name", "last_name"]
		createUser 0, required_fields
		return false

	# Creates a Property manager user
	@CreatePropertyManager: ->
		required_fields = ["email", "password", "company_name", "street_address", "phone", "website", "city", "state"]
		createUser 1, required_fields
		return false
				



