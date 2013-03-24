###
Manager class for all verify functionality
###
class A2Cribs.VerifyManager

	@init:(user)->
		@me = user
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
				@getMutalFriends(user),
				@getTwitterFollowers(user),

			).done (tot_friends, mut_friends, followers_count)->
				verification_info = {
					'user_id': user.id,
					'fb_id': user.facebook_userid,
					'verified_email': user.verified,
					'verified_edu': user.university_verified,
					'tw_id': user.twitter_userid,
					'verified_fb': tot_friends?, # if tot_friends is defined the user is verified
					'mut_friends': mut_friends,
					'tot_friends': tot_friends,
					'verified_tw': followers_count?,
					'tot_followers': followers_count,
				}
				console.log(verification_info)
				defered.resolve verification_info


		return @VerificationData[user_.id]


	@getMutalFriends: (user)->
		defered = new $.Deferred();
		if @me.facebook_userid? and user.facebook_userid?
			query = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + @me.facebook_userid + ') AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + user.facebook_userid + ')'
			FB.api {method:'fql.query', query: query}, (mut_friends_res)->
				if mut_friends_res.error_code?
					console.log "Error during verification fb error: #{mut_friends_res.error_code}."
					defered.resolve(null)
				defered.resolve(mut_friends_res.length)
			return defered.promise()
		else
			# User1 or User2 were undefined so just resolve it to null
			return defered.resolve(null)

	@getTotalFriends: (user)->
		defered = new $.Deferred()
		if user.facebook_userid?
			query = 'SELECT friend_count FROM user WHERE uid = ' + user.facebook_userid
			FB.api { method:'fql.query', query: query}, (tot_friends_res)->
				if tot_friends_res.error_code?
					console.log "Error during verification fb error: #{tot_friends_res.error_code}."
					defered.resolve(null)

				defered.resolve(parseInt(tot_friends_res[0].friend_count))
			return defered.promise()
		else
			return defered.resolve(null)


	@getTwitterFollowers: (user) ->
		defered = new $.Deferred()
		if user.twitter_userid?
			$.ajax
				url: myBaseUrl + "Users/GetTwitterFollowers/" + user.id
				type:"GET"
				success: (response)=>
					data = JSON.parse(response)
					defered(data.follwers_count)
		
			return defered.promise()
		else
			return defered.resolve(null)



	@getMyVerification: ()->
		my_verif_info = {
			'user_id': parseInt(@me.id),
			'fb_id': parseInt(@me.facebook_userid),
			'tw_id': @me.twitter_userid,
			'verified_email': @me.verified
			'verified_edu': @me.university_verified?
			'verified_fb': @me.facebook_userid?,
			'verified_tw': @me.twitter_userid?,
		}

		return my_verif_info



