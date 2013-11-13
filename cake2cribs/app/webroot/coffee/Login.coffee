class A2Cribs.Login

	@LANDING_URL = "cribspot.com"
	@HTTP_PREFIX = "https://"

	$(document).ready =>
		$.when(window.fbInit).then =>
			@CheckLoggedIn()

		if $("#signup_modal").length
			@SignupModalSetupUI()

		if $("#login_modal").length
			@LoginModalSetupUI()

		if $("#login_signup").length
			@LoginPageSetupUI()

		if $("#user_welcome_page").length
			$(document).on "logged_in", () ->
				location.reload()

	###
	Private function setup facebook signup modal
	Given a user, hides the facebook signup button
	and populates that area with the users profile
	picture and populates the input fields with
	first name and last name
	###
	setup_facebook_signup_modal = (user) ->
		# Hide facebook button
		$(".fb-name").text user.first_name
		$(".fb-image").attr "src", user.img_url					
		$("#signup_modal").find(".login-separator").fadeOut()
		$("#signup_modal").find(".fb-login").fadeOut 'slow', () ->
			$(".fb-signup-welcome").fadeIn()

		# Fill in infomation in the form from facebook
		$("#student_first_name").val user.first_name
		$("#student_last_name").val user.last_name
		$("#student_email").focus()

	###
	Check Logged In
	Trys to fetch the user information from the backend
	If logged in populates the header
	Called when the document is ready
	###
	@CheckLoggedIn: ->
		deferred = new $.Deferred()
		$.ajax
			url: myBaseUrl + 'Users/IsLoggedIn'
			success: (response) =>
				response = JSON.parse response
				if response.error?
					return  deferred.reject()

				if response.success is "LOGGED_IN"
					@logged_in = true
					@PopulateHeader response.data
					@PopulateFavorites response.data?.favorites

				else if response.success is "NOT_LOGGED_IN"
					@logged_in = false
					@ResetHeader()
					
				return deferred.resolve response
			error: (response) =>
				console.log response
				return deferred.reject()

		return deferred.promise()

	###
	Signup Modal SetupUI
	Adds click listeners to the show signup buttons, fb signup
	and submit for the new user form
	###
	@SignupModalSetupUI: () ->
		# Shows the signup modal
		$(".show_signup_modal").click () =>
			$("#login_modal").modal "hide"
			$("#signup_modal").modal("show").find(".signup_message").text "Sign up for Cribspot."

		# Submits the new user to the backend
		$("#signup_modal").find("form").submit (event) =>
			$("#signup_modal").find(".signup-button").button 'loading'
			@CreateStudent(event.delegateTarget)
			.done ->
				A2Cribs.ShareManager.ShowShareModal("Almost done!",
				"You'll need to invite your housing group to take advantage of all the features Cribspot has to offer.",
				"after signup"
				)
			.always () =>
				$("#signup_modal").find(".signup-button").button 'reset'
			return false

		# Retrieves the facebook user information and
		# signs in the user if account already created
		$("#signup_modal").find(".fb-login").click () =>
			$(".fb-login").button('loading')
			@FacebookJSLogin()
			.done (response) =>
				if response.success is "NOT_LOGGED_IN"
					setup_facebook_signup_modal response.data

				else if response.success is "LOGGED_IN"
					$(".modal").modal('hide')
					@logged_in = true
					A2Cribs.MixPanel.Event "Logged In",
						"name" : response.data?.name
						"email" : response.data?.email
					# Populate the header
					@PopulateHeader response.data
					@PopulateFavorites response.data?.favorites
					if response.account_exists is no
						A2Cribs.ShareManager.ShowShareModal("Almost done!",
						"You'll need to invite your housing group to take advantage of all the features Cribspot has to offer.",
						"after signup"
						)
					$(document).trigger("logged_in")

			.always () =>
				$(".fb-login").button('reset')

	###
	Login Modal SetupUI
	Adds Listeners to open login modal, submit login,
	and fb login
	###
	@LoginModalSetupUI: () ->

		# Shows the login modal
		$(".show_login_modal").click () =>
			$("#signup_modal").modal "hide"
			$("#login_modal").modal "show"

		# Submits the login credentials to the backend
		$("#login_modal").find("form").submit (event) =>
			$("#login_modal").find(".signup-button").button 'loading'
			@cribspotLogin(event.delegateTarget)
			.always () ->
				$("#login_modal").find(".signup-button").button 'reset'
			return false

		# Trys to log user in with facebook
		$("#login_modal").find(".fb-login").click () =>
			$(".fb-login").button('loading')
			@FacebookJSLogin()
			.done (response) =>
				if response.success is "NOT_LOGGED_IN"
					# If login is showing but the user has never created a profile
					# before
					$("#login_modal").modal('hide')
					$("#signup_modal").modal('show')
					setup_facebook_signup_modal response.data

				else if response.success is "LOGGED_IN"
					$(".modal").modal('hide')
					@logged_in = true
					A2Cribs.MixPanel.Event "Logged In",
						"name" : response.data?.name
						"email" : response.data?.email
					# Populate the header
					@PopulateHeader response.data
					@PopulateFavorites response.data?.favorites
					if response.account_exists is no
						A2Cribs.ShareManager.ShowShareModal("Almost done!",
						"You'll need to invite your housing group to take advantage of all the features Cribspot has to offer.",
						"after signup"
						)
					$(document).trigger("logged_in")
			.always () =>
				$(".fb-login").button('reset')

	###
	Login Page SetupUI
	Sets up listeners for full page login
	###
	@LoginPageSetupUI: () ->
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

		@div.find(".fb_login_btn").click =>
			$(".fb-login").button('loading')
			@FacebookJSLogin()
			.done (response) =>
				if response.success is "NOT_LOGGED_IN"
					# populate the info
					@div.find("#student_first_name").val response.data.first_name
					@div.find("#student_last_name").val response.data.last_name
					@div.find(".fb-image").attr "src", response.data.img_url
					$(".fb-name").text response.data.first_name

					# hide the login if displayed
					# make sure the signup is displayed
					@div.find(".show_signup").first().click()
					@div.find(".show_student").first().click()

					@div.find(".email_login_message").fadeOut 'slow', () =>
						@div.find(".fb-signup-welcome").fadeIn()

					@div.find("#student_email").focus()

				else if response.success is "LOGGED_IN"
					@logged_in = true
					A2Cribs.MixPanel.Event "Logged In",
						"name" : response.data?.name
						"email" : response.data?.email
					$(document).trigger("logged_in")
					location.reload()
			.always () =>
				$(".fb-login").button('reset')

		# Click and form handlers for login
		@div.find("#login_content").submit (event) => 
			@cribspotLogin(event.delegateTarget)
			.done () =>
				location.reload()
			return false

		# Click and form handlers for student user creation
		@div.find("#student_signup").submit () =>
			@CreateStudent()
			.done () =>
				location.reload()
			return false

		# Click and form handlers for property manager user creation
		@div.find("#pm_signup").submit () =>
			@CreatePropertyManager()
			.done () =>
				location.reload()
			return false

	###
	Reset header
	Shows Login and signup buttons and removes
	the user information from the header
	###
	@ResetHeader: ->
		# Hide user dropdown
		$(".personal_menu").hide()

		# Hide favorites and messages buttons
		$(".personal_buttons").hide()

		# Show signup and login buttons
		$(".signup_btn").show()
		# Show or btn
		$(".nav-text").show()

	###
	Populate the header
	Fill in dropdowns and show picture of the user
	###
	@PopulateHeader: (user) ->
		example = user
		# Hide sign up stuff
		$(".signup_btn").hide()
		# Hide or btn
		$(".nav-text").hide()

		# Favorites
		$(".personal_buttons").show()
		A2Cribs.FavoritesManager.InitializeFavorites user.favorites

		# name
		$(".personal_menu").find(".user_name").text user.name

		# image
		$(".personal_menu").find("img").attr "src", user.img_url

		# message count
		if user.num_messages isnt 0
			$(".personal_buttons").find(".message_count").show().text user.num_messages

		# show user dropdown
		$(".personal_menu_#{user.user_type}").show()

		# Change user email
		$(".user_email").text user.email

	###
	Populate favorites
	Highlight or un-highlight favorites
	###
	@PopulateFavorites: (favorites) ->
		for listing_id in favorites
			$(".favorite_listing*[data-listing-id='#{listing_id}']").addClass("active")

	###
	Facebook JS Login
	###
	@FacebookJSLogin: ->
		@fb_login_deferred = new $.Deferred()
		FB.getLoginStatus (response) =>
			if response.status == 'connected'
				if response? and response.authResponse?
					@AttemptFacebookLogin response.authResponse
				else
					A2Cribs.UIManager.Error "We're having trouble logging you in with facebook, but don't worry!
					You can still create an account with our regular login."
					return @fb_login_deferred.reject()
			else
			# user logged in, but hasn't authorized us
				FB.login (response) =>
					if response? and response.authResponse?
						@AttemptFacebookLogin response.authResponse
					else
						A2Cribs.UIManager.Error "We're having trouble logging you in with facebook, but don't worry!
						You can still create an account with our regular login."
						return @fb_login_deferred.reject()
				, {scope:'email'}

		return @fb_login_deferred.promise()

	###
	Send signed request to server to finish registration

	###
	@AttemptFacebookLogin: (authResponse) ->
		$.ajax
			url: myBaseUrl + 'Users/AttemptFacebookLogin'
			data: authResponse
			type: 'POST'
			success: (response) =>
				response = JSON.parse response
				if response.error?
					return  @fb_login_deferred.reject()

				return @fb_login_deferred.resolve response
			error: (response) =>
				console.log response
				return @fb_login_deferred.reject()

	@cribspotLogin:(div) ->
		@_login_deferred = new $.Deferred()
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
				console.log data
				if data.error?
					if data.error_type is "EMAIL_UNVERIFIED"
						A2Cribs.UIManager.Confirm "Your email address has not yet been confirmed. 
							Please click the link provided in your confirmation email. 
							Do you want us to resend you the email?", (resend) =>
							if resend then @ResendConfirmationEmail()
					else
						A2Cribs.UIManager.CloseLogs()
						A2Cribs.UIManager.Error data.error
					return @_login_deferred.reject()
				else
					A2Cribs.MixPanel.AuthEvent 'login',
						'source':'cribspot'
					$(".modal").modal('hide')
					@PopulateHeader data.data
					@PopulateFavorites data.data?.favorites
					@logged_in = yes
					A2Cribs.MixPanel.Event "Logged In",
						"name" : data.data?.name
						"email" : data.data?.email
					$(document).trigger("logged_in")
					return @_login_deferred.resolve()

		return @_login_deferred.promise()

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

	validate = (user_type, required_fields, div) =>
		type_prefix = if user_type is 0 then "student_" else "pm_"
		A2Cribs.UIManager.CloseLogs()
		isValid = yes
		for field in required_fields
			if div.find("##{type_prefix}#{field}").val().length is 0
				isValid = no
		if not isValid
			A2Cribs.UIManager.Error "Please fill in all of the fields!"

		if user_type is 1
			phone_number = div.find("##{type_prefix}phone").val().split("-").join("")
			if phone_number.length isnt 10 or isNaN phone_number
				isValid = false
				A2Cribs.UIManager.Error "Please enter a valid phone number"

		if div.find("##{type_prefix}password").val().length < 6
			isValid = false
			A2Cribs.UIManager.Error "Please enter a password of 6 or more characters"

		return isValid


	# Static private function that creates and posts a user based on user_type
	createUser = (user_type, required_fields, fields, div) =>
		@_create_user_deferred = new $.Deferred()

		type_prefix = if user_type is 0 then "student_" else "pm_"
		if validate user_type, required_fields, div
			# Check to see if confirm password matches the actual password
			if div.find("##{type_prefix}confirm_password").val()?
				if div.find("##{type_prefix}password").val() isnt div.find("##{type_prefix}confirm_password").val()
					A2Cribs.UIManager.Error "Make sure passwords match!"
					return
			
			# Create request data
			request_data =
				User:
					user_type: user_type
			# Loop through all the required fields and grab based on id's
			for field in fields
				if div.find("##{type_prefix}#{field}").val().length isnt 0
					request_data.User[field] = div.find("##{type_prefix}#{field}").val()

			# Post the request data using AjaxRegister
			$.post "/users/AjaxRegister", request_data, (response) =>
				data = JSON.parse response
				if data.error?
					A2Cribs.UIManager.CloseLogs()
					A2Cribs.UIManager.Error data.error
					return @_create_user_deferred.reject()
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
					@PopulateHeader data.data
					@PopulateFavorites data.data?.favorites
					@logged_in = yes
					A2Cribs.MixPanel.Event "Logged In",
						"name" : data.data?.name
						"email" : data.data?.email
					$(".modal").modal('hide')

					return @_create_user_deferred.resolve()
			
			return @_create_user_deferred.promise()

		return @_create_user_deferred.reject()

	# Creates a Student user
	@CreateStudent: (div) ->
		div = if not div? then @div else $(div)
		required_fields = ["email", "password", "first_name", "last_name"]
		fields = required_fields.slice 0 # Used to copy the required array
		return createUser 0, required_fields, fields, div

	# Creates a Property manager user
	@CreatePropertyManager: ->
		required_fields = ["email", "password", "company_name", "street_address", "phone", "city", "state"]
		fields = required_fields.slice 0 # Used to copy the required array
		fields.push "website"
		return createUser 1, required_fields, fields, @div
				



