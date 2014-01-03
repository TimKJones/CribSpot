###
Class Analytics
Wrapper class to handle the interactions
with google analytics event tracking
###
class A2Cribs.Analytics
	$(document).on "logged_in", (event, user) =>
		@Event "Logged In",
			"name" : user?.name
			"email" : user?.email
