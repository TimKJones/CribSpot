###
Manager class for all verify functionality
###
class A2Cribs.VerifyManager

	@init:()->
		@my_verification_info = {
			'facebook_id': 1249680161,
		}
		@VerificationData = {} # Cache to hold users verification data

		#init fb api

		`window.fbAsyncInit = function() {
	    // init the FB JS SDK
	    FB.init({
	      appId      : '148187588666959', // App ID from the App Dashboard
	      channelUrl : 'http://localhost/channel.html', // Channel File for x-domain communication
	      status     : true, // check the login status upon init?
	      cookie     : true, // set sessions cookies to allow your server to access the session?
	      xfbml      : true  // parse XFBML tags on this page?
	    });

	    // Additional initialization code such as adding Event Listeners goes here

	  };

	  // Load the SDK's source Asynchronously
	  // Note that the debug version is being actively developed and might 
	  // contain some type checks that are overly strict. 
	  // Please report such bugs using the bugs tool.
	  (function(d, debug){
	     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement('script'); js.id = id; js.async = true;
	     js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
	     ref.parentNode.insertBefore(js, ref);
	   }(document, /*debug*/ false));

	  function onLinkedInLoad() {
	    IN.Event.on(IN, "auth", onLinkedInAuth);
	  }

	  function onLinkedInAuth() {
	     IN.API.Profile("me").result(A2Cribs.FacebookManager.UpdateLinkedinLogin);
	  }`



	###    
	Returns a JQuery defered object. Example way to call the function is

	@getVerificationFor(user).then (verification_info)->
	  # Do what you want with the data

	the verification info object has the following key value pairs
	{
		'user_id': int
		'fb_id': int or null
		'tw_id': int or null
		'verified_email': bool,
		'verificed_edu': bool,
		'verified_fb': bool,
		'verified_tw': bool,
		'mutual_friends': int or null, #depends if the user is verified on fb and if you are verified on fb
		'total_friends': int or null, #depends on if the user is verified on fb
		'total_followers' int or null, #depends on if the user is verified ob tw
	}

	You do not need to worry about caching the data as this function already provides this functionality

	Jquery deferred      http://api.jquery.com/category/deferred-object/

	###



	@getVerificationFor: (user_)->
		
		if not @VerificationData[user_.id]?
			# Create a new deferred object and store it in the cache. 
			# When we have gotten all the users verification info 
			# resolve the deferred and pass along all the verification data
			defered = new $.Deferred()
			user = user_
			@VerificationData[user.id] = defered 
			$.when(
				@getTotalFriends(user),
				@getMutalFriends(user)
			).done (tot_friends, mut_friends)->
				verification_info = {
					'user_id': user.id,
					'fb_id': user.facebook_id,
					# 'tw_id': user.twitter_id,
					'verified_fb': tot_friends?, # if tot_friends is defined the user is verified
					'mut_friends': mut_friends,
					'tot_friends': tot_friends,
					# 'verified_tw': followers?,
					# 'tot_followers': null,
				}

				defered.resolve verification_info


		return @VerificationData[user_.id]


	@getMutalFriends: (user)->
		defered = new $.Deferred();
		if @my_verification_info.facebook_id? and user.facebook_id?
			query = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + @my_verification_info.facebook_id + ') AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + user.facebook_id + ')'
			FB.api {method:'fql.query', query: query}, (mut_friends_res)->
				defered.resolve(mut_friends_res.length)
			return defered.promise()
		else
			# User1 or User2 were undefined so just resolve it to null
			return defered.resolve(null)

	@getTotalFriends: (user)->
		defered = new $.Deferred()
		if user.facebook_id?
			query = 'SELECT friend_count FROM user WHERE uid = ' + user.facebook_id
			FB.api { method:'fql.query', query: query}, (tot_friends_res)->
				defered.resolve(parseInt(tot_friends_res[0].friend_count))
			return defered.promise()
		else
			return defered.resolve(null)



	@samplecallback:(fb_data)->
		console.log(fb_data)


	@GetTwitterFollowersCount: (user_id) ->
		defered = new $.Deferred()
		$.ajax
			url: myBaseUrl + "Users/GetTwitterFollowers/" + user_id
			type:"GET"
			success: ()=>
				defered(response)
		
		return defered.promise()

	@GetTwitterFollowersCallback: (response) ->
		alert response



