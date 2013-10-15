
/*
Manager class for all social networking functionality
*/

(function() {

  A2Cribs.FacebookManager = (function() {

    function FacebookManager() {}

    FacebookManager.FacebookLogin = function() {
      var url;
      url = 'https://www.facebook.com/dialog/oauth?';
      url += 'client_id=450938858319396';
      url += '&redirect_uri=http://www.cribspot.com/login';
      url += '&scope=email';
      A2Cribs.MixPanel.AuthEvent('login', {
        'source': 'facebook'
      });
      return window.location.href = url;
    };

    FacebookManager.FacebookJSLogin = function() {
      return FB.getLoginStatus(function(response) {
        var accessToken, uid;
        if (response.status === 'connected') {
          if ((response != null) && (response.authResponse != null)) {
            A2Cribs.FacebookManager.AttemptLogin(response.authResponse);
          } else {
            A2Cribs.UIManager.Error("We're having trouble logging you in with facebook, but don't worry!					You can still create an account with our regular login.");
          }
          uid = response.authResponse.userID;
          return accessToken = response.authResponse.accessToken;
        } else {
          return FB.login(function(response) {
            if ((response != null) && (response.authResponse != null)) {
              return A2Cribs.FacebookManager.AttemptLogin(response.authResponse);
            } else {
              return A2Cribs.UIManager.Error("We're having trouble logging you in with facebook, but don't worry!						You can still create an account with our regular login.");
            }
          }, {
            scope: 'email'
          });
        }
      });
    };

    /*
    	Send signed request to server to finish registration
    */

    FacebookManager.AttemptLogin = function(authResponse) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + 'Users/AttemptFacebookLogin',
        data: authResponse,
        type: 'POST',
        success: function(response) {
          return console.log(response);
        },
        error: function(response) {
          return console.log(response);
        }
      });
    };

    FacebookManager.Logout = function() {
      alert('logging out');
      return $.ajax({
        url: myBaseUrl + "Users/Logout",
        type: "GET"
      });
    };

    FacebookManager.Login = function() {
      return alert('logging in');
    };

    FacebookManager.JSLogin = function() {
      return FB.login(A2Cribs.FacebookManager.JSLoginCallback);
    };

    FacebookManager.JSLoginCallback = function(response) {
      if (response.authResponse) {
        FB.api('/me', A2Cribs.FacebookManager.APICallback);
        return $.ajax({
          url: myBaseUrl + "Verify/FacebookVerify",
          type: "POST"
        });
      } else {
        return alert('failed');
      }
    };

    FacebookManager.FindMutualFriends = function() {
      var query;
      query = 'SELECT uid, first_name, last_name, pic_small FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + $("#userid_input").val() + ')';
      return FB.api({
        method: 'fql.query',
        query: query
      }, A2Cribs.FacebookManager.FindMutualFriendsCallback);
    };

    FacebookManager.FindMutualFriendsCallback = function(response) {
      return $("#numMutualFriendsVal").html(response.length);
    };

    FacebookManager.APICallback = function(response) {
      console.log(response);
      $(".facebook.unverified").toggleClass("unverified verified");
      return $(".facebook.verified").html(response.name + " is now verified.");
    };

    FacebookManager.UpdateLinkedinLogin = function(response) {
      $(".linkedin.unverified").toggleClass("unverified verified");
      $(".linkedin.verified").html(response.values[0].firstName + " " + response.values[0].lastName + " is now verified.");
      return $.ajax({
        url: myBaseUrl + "Verify/LinkedinVerify",
        type: "POST"
      });
    };

    FacebookManager.SubmitEmail = function() {
      var domain, email, emailRegEx, lastPart;
      email = $("#emailInput").val();
      emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
      if (email.search(emailRegEx) === -1) {
        alert("Email address is invalid");
        return;
      }
      domain = email.substring(email.indexOf("@") + 1);
      lastPart = domain.substring(domain.indexOf(".") + 1);
      if (lastPart.toLowerCase() === "edu") {
        $("#emailEduVerified").toggleClass("unverified verified");
        return $("#emailEduVerified").html("Verified edu email (" + domain.substring(0, domain.length - 4).toLowerCase() + ")");
      }
    };

    return FacebookManager;

  })();

}).call(this);
