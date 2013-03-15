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




	###
		This function is used to see if the user is verified, and as well get some
		additional information about the relationship between user1 and user2 (mutual friends & total friends)

		fb_ids is an object that {'user1': int or null, 'user2': int or null}
		the callback function takes a parameter data that 
		will have the form {'verified': boolean, 'total_friends': int or null, 'mutual_friends' int or null}
		
		Note: that total_friends is the total friends of user2

		In context this structure will be cached client side for getting a users
		verification state.


	###
	@getFacebookVerification: (fb_ids, callback)->
		fb_data = {
			verified: false,
			mutual_friends: null,
			total_friends: null,
		}
		
		if not fb_ids.user1?
			# the users fb id doesn't exist so they have not verified
			callback(fb_data)
			return

		$.when(
			
			@getMutalFriends(fb_ids), 
			@getTotalFriends(fb_ids)
			
		).then (mut_friends_res, tot_friends_res)=>
			fb_data = {}

			if not mut_friends_res.error_code?
				fb_data.mutual_friends = mut_friends_res.length
			else
				console.log mut_friends_res

			if not tot_friends_res.error_code?
				fb_data.total_friends = tot_friends_res[0].friend_count
			else
				console.log total_friends
			callback {
				verified: true,
				mutual_friends: mut_friends_res?.length,
				total_friends: tot_friends_res[0]?.friend_count,
			}



	@getMutalFriends: (fb_ids)->
		query = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + fb_ids.user1 + ') AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + fb_ids.user2 + ')'
		defered = new $.Deferred();
		FB.api {method:'fql.query', query: query}, (response)->
			defered.resolve(response)
		defered.promise()

	@getTotalFriends: (fb_id)->
		query = 'SELECT friend_count FROM user WHERE uid = ' + fb_id
		defered = new $.Deferred()
		FB.api { method:'fql.query', query: query}, (response)->
			defered.resolve(response)
		defered.promise()



	@samplecallback:(fb_data)->
		console.log(fb_data)


	@GetTwitterFollowersCount: (user_id) ->
		$.ajax
			url: myBaseUrl + "Users/GetTwitterFollowers/" + user_id
			type:"GET"
			success: A2Cribs.VerifyManager.GetTwitterFollowersCallback

	@GetTwitterFollowersCallback: (response) ->
		alert response



