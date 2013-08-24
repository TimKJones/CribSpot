###
Manager class for all social networking functionality
###
class A2Cribs.FacebookManager

	@FacebookLogin: () ->
		url = 'https://www.facebook.com/dialog/oauth?'
		url += 'client_id=488039367944782'
		url += '&redirect_uri=http://localhost/users/login'
		url += '&scope=email'
		window.location.href = url 

	@Logout: ->
		alert 'logging out'
		$.ajax 
			url: myBaseUrl + "Users/Logout"
			type:"GET"

	@Login: ->
		alert 'logging in'

	@JSLogin: ->
		FB.login(A2Cribs.FacebookManager.JSLoginCallback)

	@JSLoginCallback: (response) ->
		if response.authResponse
			FB.api('/me', A2Cribs.FacebookManager.APICallback)
			$.ajax
				url: myBaseUrl + "Verify/FacebookVerify"
				type:"POST"
			# location.reload()
		else
			alert 'failed'

	@FindMutualFriends: ->
		query = 'SELECT uid, first_name, last_name, pic_small FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + $("#userid_input").val() + ')'
		FB.api({
			method:'fql.query',
			query: query
			},
			A2Cribs.FacebookManager.FindMutualFriendsCallback)

	@FindMutualFriendsCallback: (response) ->
		$("#numMutualFriendsVal").html(response.length)

	@APICallback: (response) ->
		console.log(response)
		$(".facebook.unverified").toggleClass("unverified verified")
		$(".facebook.verified").html(response.name + " is now verified.")

	@UpdateLinkedinLogin: (response) ->
		$(".linkedin.unverified").toggleClass("unverified verified")
		$(".linkedin.verified").html(response.values[0].firstName + " "  + response.values[0].lastName + " is now verified.")
		$.ajax
			url: myBaseUrl + "Verify/LinkedinVerify"
			type:"POST"

	@SubmitEmail: ->
		email = $("#emailInput").val()
		emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
		if email.search(emailRegEx) == -1
			alert "Email address is invalid"
			return
		domain = email.substring(email.indexOf("@")+1)
		lastPart = domain.substring(domain.indexOf(".") + 1)
		if lastPart.toLowerCase() == "edu"
			$("#emailEduVerified").toggleClass("unverified verified")
			$("#emailEduVerified").html("Verified edu email (" + domain.substring(0, domain.length-4).toLowerCase() + ")")