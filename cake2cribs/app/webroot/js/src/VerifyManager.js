
/*
Manager class for all verify functionality
*/

(function() {

  A2Cribs.VerifyManager = (function() {

    function VerifyManager() {}

    VerifyManager.LoggedInUserFacebookUserId = null;

    VerifyManager.FBIdOfListingOwner = 755192993;

    VerifyManager.TwitterIdOfListingOwner = 381100229;

    VerifyManager.EmailOfListingOwner = "timjones@umich.edu";

    VerifyManager.VerificationData = {
      TotalFriends: "?",
      MutualFriends: "?",
      TwitterFollowers: "?"
    };

    VerifyManager.GetUserVerifications = function() {
      /*
      		Get facebook user id of logged in user.
      		Get facebook user id of user that listed current property.
      		fql query for total facebook friends of user that listed property.
      		fql query for total mutual friends between the two users.
      */      return $.ajax({
        url: myBaseUrl + "Verify/GetLoggedInUserFacebookId",
        type: "GET",
        success: A2Cribs.VerifyManager.GetLoggedInUserFacebookIdCallback
      });
    };

    VerifyManager.GetLoggedInUserFacebookIdCallback = function(response) {
      A2Cribs.VerifyManager.LoggedInUserFacebookUserId = response;
      return A2Cribs.VerifyManager.FindTotalFriends();
    };

    VerifyManager.FindTotalFriends = function() {
      var query;
      query = 'SELECT friend_count FROM user WHERE uid = ' + A2Cribs.VerifyManager.FBIdOfListingOwner;
      return FB.api({
        method: 'fql.query',
        query: query
      }, A2Cribs.VerifyManager.FindTotalFriendsCallback);
    };

    VerifyManager.FindTotalFriendsCallback = function(response) {
      if (response.error_code === void 0) {
        A2Cribs.VerifyManager.VerificationData.TotalFriends = response[0].friend_count;
      }
      return A2Cribs.VerifyManager.FindMutualFriends();
    };

    VerifyManager.FindMutualFriends = function() {
      var me, owner, query;
      me = A2Cribs.VerifyManager.LoggedInUserFacebookUserId;
      owner = A2Cribs.VerifyManager.FBIdOfListingOwner;
      query = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + me + ') AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + owner + ')';
      return FB.api({
        method: 'fql.query',
        query: query
      }, A2Cribs.VerifyManager.FindMutualFriendsCallback);
    };

    VerifyManager.FindMutualFriendsCallback = function(response) {
      if (response.error_code === void 0) {
        A2Cribs.VerifyManager.VerificationData.MutualFriends = response.length;
      }
      return A2Cribs.VerifyManager.GetTwitterAndEmailData();
    };

    VerifyManager.GetTwitterAndEmailData = function() {
      var email, id;
      id = A2Cribs.VerifyManager.TwitterIdOfListingOwner;
      email = A2Cribs.VerifyManager.EmailOfListingOwner;
      return $.ajax({
        url: myBaseUrl + "Verify/GetTwitterAndEmailData/" + id + "/" + email,
        type: "GET",
        success: A2Cribs.VerifyManager.GetTwitterAndEmailDataCallback
      });
    };

    VerifyManager.GetTwitterAndEmailDataCallback = function(response) {
      response = JSON.parse(response);
      A2Cribs.VerifyManager.VerificationData.TwitterFollowers = response.followers;
      A2Cribs.VerifyManager.VerificationData.SchoolVerifiedWithEmail = response.school;
      return A2Cribs.VerifyManager.LoadVerificationDataComplete();
    };

    /*
    	All verication data is now loaded into the object A2Cribs.VerifyManager.VerificationData.
    */

    VerifyManager.LoadVerificationDataComplete = function() {
      var x;
      return x = 5;
    };

    /*
    		This function is used to see if the user is verified, and as well get some
    		additional information about the relationship
    		 between user1 and user2 (mutual friends & total friends)
    
    
    		fb_ids is an object that {'user1': int or null, 'user2': int or null}
    		the callback function takes a parameter data that 
    		will have the form {'verified': boolean, 'total_friends': int or null, 'mutual_friends' int or null}
    		
    		Note: that total_friends is the total friends of user2
    
    		In context this structure will be cached client side for getting a users
    		verification state.
    */

    VerifyManager.a = function() {
      return this.getVerificationInfo({
        'user1': 1249680161,
        'user2': 1354124203
      }, 381100229, this.samplecallback);
    };

    VerifyManager.getVerificationInfo = function(fb_ids, twitter_id, callback) {
      var fb_data,
        _this = this;
      fb_data = {
        verified: false,
        mutual_friends: null,
        total_friends: null
      };
      if (!(fb_ids.user1 != null)) {
        callback(fb_data);
        return;
      }
      return $.when(this.getMutalFriends(fb_ids), this.getTotalFriends(fb_ids.user2), this.GetTwitterFollowersCount(twitter_id)).then(function(mut_friends, tot_friends, tot_followers) {
        return callback({
          verified: true,
          mutual_friends: mutual_friends,
          total_friends: tot_friends,
          twitter_followers: tot_followers
        });
      });
    };

    VerifyManager.getMutalFriends = function(fb_ids) {
      var defered, query;
      query = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + fb_ids.user1 + ') AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + fb_ids.user2 + ')';
      defered = new $.Deferred();
      if ((fb_ids.user1 != null) && (fb_ids.user2 != null)) {
        FB.api({
          method: 'fql.query',
          query: query
        }, function(response) {
          return defered.resolve(mut_friends_res.length);
        });
        return defered.promise();
      } else {
        return defered.resolve(null);
      }
    };

    VerifyManager.getTotalFriends = function(fb_id) {
      var defered, query;
      query = 'SELECT friend_count FROM user WHERE uid = ' + fb_id;
      defered = new $.Deferred();
      if (fb_id != null) {
        FB.api({
          method: 'fql.query',
          query: query
        }, function(response) {
          return defered.resolve(tot_friends_res[0].friend_count);
        });
        return defered.promise();
      } else {
        return defered.resolve(null);
      }
    };

    VerifyManager.samplecallback = function(fb_data) {
      return console.log(fb_data);
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

    return VerifyManager;

  })();

}).call(this);
