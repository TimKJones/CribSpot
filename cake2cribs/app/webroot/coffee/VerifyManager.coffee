###
Manager class for all verify functionality
###
class A2Cribs.VerifyManager
	@LoggedInUserFacebookUserId = null
	@FBIdOfListingOwner = 755192993 # Need to figure out how to get this id from Stratman
	@TwitterIdOfListingOwner = 381100229 # Need to figure out how to get this id from Stratman
	@EmailOfListingOwner = "timjones@umich.edu" # Need to figure out how to get this from Stratman
	@VerificationData = 
		TotalFriends: "?"
		MutualFriends: "?"
		TwitterFollowers: "?"
		#SchoolVerifiedWithEmail: null #this will contain the name of the school

	@GetUserVerifications: ->
		###
		Get facebook user id of logged in user.
		Get facebook user id of user that listed current property.
		fql query for total facebook friends of user that listed property.
		fql query for total mutual friends between the two users.
		###
		$.ajax
			url: myBaseUrl + "Verify/GetLoggedInUserFacebookId"
			type:"GET"
			success: A2Cribs.VerifyManager.GetLoggedInUserFacebookIdCallback

	@GetLoggedInUserFacebookIdCallback: (response) ->
		A2Cribs.VerifyManager.LoggedInUserFacebookUserId = response
		A2Cribs.VerifyManager.FindTotalFriends()

	@FindTotalFriends: ->
		query = 'SELECT friend_count FROM user WHERE uid = ' + A2Cribs.VerifyManager.FBIdOfListingOwner
		FB.api({
			method:'fql.query',
			query: query
			},
			A2Cribs.VerifyManager.FindTotalFriendsCallback)

	@FindTotalFriendsCallback: (response) ->
		if response.error_code == undefined
			A2Cribs.VerifyManager.VerificationData.TotalFriends = response[0].friend_count
		A2Cribs.VerifyManager.FindMutualFriends()

	@FindMutualFriends: ->
		me = A2Cribs.VerifyManager.LoggedInUserFacebookUserId
		owner = A2Cribs.VerifyManager.FBIdOfListingOwner
		query = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + me + ') AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + owner + ')'
		FB.api({
			method:'fql.query',
			query: query
			},
			A2Cribs.VerifyManager.FindMutualFriendsCallback)

	@FindMutualFriendsCallback: (response) ->
		if response.error_code == undefined
			A2Cribs.VerifyManager.VerificationData.MutualFriends = response.length
		A2Cribs.VerifyManager.GetTwitterAndEmailData()

	@GetTwitterAndEmailData: ->
		id = A2Cribs.VerifyManager.TwitterIdOfListingOwner
		email = A2Cribs.VerifyManager.EmailOfListingOwner
		$.ajax
			url: myBaseUrl + "Verify/GetTwitterAndEmailData/" + id + "/" + email
			type:"GET"
			success: A2Cribs.VerifyManager.GetTwitterAndEmailDataCallback

	@GetTwitterAndEmailDataCallback: (response) ->
		response = JSON.parse response
		A2Cribs.VerifyManager.VerificationData.TwitterFollowers = response.followers
		A2Cribs.VerifyManager.VerificationData.SchoolVerifiedWithEmail = response.school
		A2Cribs.VerifyManager.LoadVerificationDataComplete()

	###
	All verication data is now loaded into the object A2Cribs.VerifyManager.VerificationData.
	###
	@LoadVerificationDataComplete: ->
		x = 5