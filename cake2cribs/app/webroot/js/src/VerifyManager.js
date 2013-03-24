// Generated by CoffeeScript 1.4.0

/*
Manager class for all verify functionality
*/


(function() {

  A2Cribs.VerifyManager = (function() {

    function VerifyManager() {}

    VerifyManager.init = function(user) {
      this.me = user;
      this.VerificationData = {};
      return window.fbAsyncInit = function() {
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
	  };
    };

    /*    
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
    */


    VerifyManager.getVerificationFor = function(user_) {
      var defered, user;
      if (!(this.VerificationData[user_.id] != null)) {
        defered = new $.Deferred();
        user = user_;
        this.VerificationData[user.id] = defered;
        $.when(this.getTotalFriends(user), this.getMutalFriends(user)).done(function(tot_friends, mut_friends) {
          var verification_info;
          verification_info = {
            'user_id': user.id,
            'fb_id': user.facebook_userid,
            'verified_email': user.verified,
            'verified_edu': user.university_verified,
            'verified_fb': tot_friends != null,
            'mut_friends': mut_friends,
            'tot_friends': tot_friends
          };
          console.log(verification_info);
          return defered.resolve(verification_info);
        });
      }
      return this.VerificationData[user_.id];
    };

    VerifyManager.getMutalFriends = function(user) {
      var defered, query;
      defered = new $.Deferred();
      if ((this.me.facebook_userid != null) && (user.facebook_userid != null)) {
        query = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + this.me.facebook_userid + ') AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + user.facebook_userid + ')';
        FB.api({
          method: 'fql.query',
          query: query
        }, function(mut_friends_res) {
          if (mut_friends_res.error_code != null) {
            console.log("Error during verification fb error: " + mut_friends_res.error_code + ".");
            defered.resolve(null);
          }
          return defered.resolve(mut_friends_res.length);
        });
        return defered.promise();
      } else {
        return defered.resolve(null);
      }
    };

    VerifyManager.getTotalFriends = function(user) {
      var defered, query;
      defered = new $.Deferred();
      if (user.facebook_userid != null) {
        query = 'SELECT friend_count FROM user WHERE uid = ' + user.facebook_userid;
        FB.api({
          method: 'fql.query',
          query: query
        }, function(tot_friends_res) {
          if (tot_friends_res.error_code != null) {
            console.log("Error during verification fb error: " + tot_friends_res.error_code + ".");
            defered.resolve(null);
          }
          return defered.resolve(parseInt(tot_friends_res[0].friend_count));
        });
        return defered.promise();
      } else {
        return defered.resolve(null);
      }
    };

    VerifyManager.GetTwitterFollowersCount = function(user_id) {
      var defered,
        _this = this;
      defered = new $.Deferred();
      $.ajax({
        url: myBaseUrl + "Users/GetTwitterFollowers/" + user_id,
        type: "GET",
        success: function() {
          return defered(response);
        }
      });
      return defered.promise();
    };

    VerifyManager.GetTwitterFollowersCallback = function(response) {
      return alert(response);
    };

    VerifyManager.getMyVerification = function() {
      var my_verif_info;
      my_verif_info = {
        'user_id': parseInt(this.me.id),
        'fb_id': parseInt(this.me.facebook_userid),
        'tw_id': this.me.twitter_userid,
        'verified_email': this.me.verified,
        'verified_edu': this.me.university_verified != null,
        'verified_fb': this.me.facebook_userid != null,
        'verified_tw': this.me.twitter_userid != null
      };
      return my_verif_info;
    };

    return VerifyManager;

  })();

}).call(this);
