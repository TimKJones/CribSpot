
/*
Manager class for all verify functionality
*/

(function() {

  A2Cribs.VerifyManager = (function() {

    function VerifyManager() {}

    VerifyManager.init = function(user) {
      if (user == null) user = null;
      this.me = user;
      return this.VerificationData = {};
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
        $.when(this.getTotalFriends(user), this.getMutalFriends(user), this.getTwitterFollowers(user)).done(function(tot_friends, mut_friends, followers_count) {
          var verification_info;
          verification_info = {
            'user_id': user.id,
            'fb_id': user.facebook_id,
            'verified_email': user.verified === true,
            'verified_edu': user.university_verified === true,
            'tw_id': user.twitter_userid,
            'verified_fb': tot_friends,
            'mut_friends': mut_friends,
            'tot_friends': tot_friends,
            'verified_tw': followers_count != null,
            'tot_followers': followers_count
          };
          return defered.resolve(verification_info);
        });
      }
      return this.VerificationData[user_.id];
    };

    VerifyManager.getMutalFriends = function(user) {
      var defered, query, _ref;
      defered = new $.Deferred();
      if ((((_ref = this.me) != null ? _ref.facebook_id : void 0) != null) && (user.facebook_id != null)) {
        query = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + this.me.facebook_id + ') AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + user.facebook_id + ')';
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
      if (user.facebook_id != null) {
        query = 'SELECT friend_count FROM user WHERE uid = ' + user.facebook_id;
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

    VerifyManager.getTwitterFollowers = function(user) {
      var defered,
        _this = this;
      defered = new $.Deferred();
      if (user.twitter_userid != null) {
        $.ajax({
          url: myBaseUrl + "Users/GetTwitterFollowers/" + user.id,
          type: "GET",
          success: function(response) {
            var data;
            data = JSON.parse(response);
            return defered.resolve(data.followers_count);
          }
        });
        return defered.promise();
      } else {
        return defered.resolve(null);
      }
    };

    VerifyManager.getMyVerification = function() {
      var my_verif_info;
      if (!(this.me != null)) return null;
      my_verif_info = {
        'user_id': parseInt(this.me.id),
        'fb_id': parseInt(this.me.facebook_id),
        'tw_id': this.me.twitter_userid,
        'verified_email': this.me.verified === true,
        'verified_edu': this.me.university_verified === true,
        'verified_fb': this.me.facebook_id != null,
        'verified_tw': this.me.twitter_userid != null
      };
      return my_verif_info;
    };

    return VerifyManager;

  })();

}).call(this);
