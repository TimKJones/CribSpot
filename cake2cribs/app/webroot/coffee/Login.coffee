class A2Cribs.Login

	@LANDING_URL = "cribspot.com"
	@HTTP_PREFIX = "https://"

	@setupUI:() ->
		# Div variable to have starting place to search the document
		@div = $("#login_signup")

		# Shows the signup page with a fade animation
		@div.find(".show_signup").click =>
			@div.find(".login_row").hide 'fade'
			@div.find(".signup_row").show 'fade'

		# Shows the login page with a fade animation
		@div.find(".show_login").click =>
			@div.find(".signup_row").hide 'fade'
			@div.find(".login_row").show 'fade'

		# Click on property manager
		@div.find(".show_pm").click =>
			@div.find(".student_icon").removeClass "active"
			@div.find(".pm_icon").addClass "active"
			@div.find(".fb_box").hide()
			@div.find(".student_signup").hide()
			@div.find(".pm_signup").show()

		# Click on student signup
		@div.find(".show_student").click =>
			@div.find(".pm_icon").removeClass "active"
			@div.find(".student_icon").addClass "active"
			@div.find(".pm_signup").hide()
			@div.find(".fb_box").show()
			@div.find(".student_signup").show()

		# Click and form handlers for login
		@div.find("#login_content").submit (event) => 
			@cribspotLogin event.delegateTarget

		# Click and form handlers for student user creation
		@div.find("#student_submit").click @CreateStudent
		@div.find("#student_signup").submit @CreateStudent

		# Click and form handlers for property manager user creation
		@div.find("#pm_submit").click @CreatePropertyManager
		@div.find("#pm_signup").submit @CreatePropertyManager

	@cribspotLogin:(div) ->
		url = myBaseUrl + "users/AjaxLogin"
		request_data = {
			User: {
				email: $(div).find('#inputEmail').val()
				password: $(div).find('#inputPassword').val()
			}
			
		}
		if request_data.User.email? and request_data.User.password?
			$.post url, request_data, (response) =>
				data = JSON.parse response
				if data.error?
					if data.error_type is "EMAIL_UNVERIFIED"
						A2Cribs.UIManager.Confirm "Your email address has not yet been confirmed. 
							Please click the link provided in your confirmation email. 
							Do you want us to resend you the email?", (resend) =>
							if resend then @ResendConfirmationEmail()
					else
						A2Cribs.UIManager.CloseLogs()
						A2Cribs.UIManager.Error data.error
					###
					TODO: GIVE USER THE OPTION TO RESEND CONFIRMATION EMAIL
					if data.error_type == "EMAIL_UNVERIFIED"
						A2Cribs.UIManager.Alert data.error
					###
				else
					A2Cribs.MixPanel.AuthEvent 'login',
						'source':'cribspot'
					window.location.reload()

		return false

	@ResendConfirmationEmail: (canceled=false) ->
		if canceled
			return
			
		$.ajax 
			url: myBaseUrl + "users/ResendConfirmationEmail"
			type:"POST"
			success:(response) ->
				response = JSON.parse response
				if response.error?
					A2Cribs.UIManager.Alert response.error.message
				else
					A2Cribs.UIManager.Success "Email has been sent! Click the link to verify."

	validate = (user_type, required_fields) =>
		type_prefix = if user_type is 0 then "student_" else "pm_"
		A2Cribs.UIManager.CloseLogs()
		isValid = yes
		for field in required_fields
			if @div.find("##{type_prefix}#{field}").val().length is 0
				isValid = no
		#handle the university select box separately
		if user_type is 0
			if $("#registered_university").val().length is 0
				isValid = false
		if user_type is 0
			if $("#student_year").val().length is 0
				isValid = false
		if not isValid
			A2Cribs.UIManager.Error "Please fill in all of the fields!"

		if user_type is 1
			phone_number = @div.find("##{type_prefix}phone").val().split("-").join("")
			if phone_number.length isnt 10 or isNaN phone_number
				isValid = false
				A2Cribs.UIManager.Error "Please enter a valid phone number"

		if @div.find("##{type_prefix}password").val().length < 6
			isValid = false
			A2Cribs.UIManager.Error "Please enter a password of 6 or more characters"

		return isValid


	# Static private function that creates and posts a user based on user_type
	createUser = (user_type, required_fields, fields) =>
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
				for field in fields
					if @div.find("##{type_prefix}#{field}").val().length isnt 0
						request_data.User[field] = @div.find("##{type_prefix}#{field}").val()
				# Handle select inputs separately
				request_data.User['registered_university'] = $("#registered_university").val()
				request_data.User['student_year'] = $("#student_year").val()

				# Post the request data using AjaxRegister
				$.post "/users/AjaxRegister", request_data, (response) =>
					data = JSON.parse response
					if data.error?
						A2Cribs.UIManager.CloseLogs()
						A2Cribs.UIManager.Error data.error
					else
						email = null
						if user_type == 0
							email = $("#student_email").val()
						else
							email = $("#pm_email").val()
						A2Cribs.MixPanel.AuthEvent 'signup',
							'user_id':response.success
							'user_type': user_type
							'email':email
							'source':'cribspot'
							'user_data':request_data
						mixpanel.people.set
							'user_id':response.success
							'user_type': user_type
							'email':email
							'user_data':request_data
						@div.find(".show_login").click()
						A2Cribs.UIManager.Alert "Check your email to validate your credentials!"
						

	# Creates a Student user
	@CreateStudent: ->
		required_fields = ["email", "password", "first_name", "last_name"]
		fields = required_fields.slice 0 # Used to copy the required array
		createUser 0, required_fields, fields
		return false

	# Creates a Property manager user
	@CreatePropertyManager: ->
		required_fields = ["email", "password", "company_name", "street_address", "phone", "city", "state"]
		fields = required_fields.slice 0 # Used to copy the required array
		fields.push "website"
		createUser 1, required_fields, fields
		return false
				



