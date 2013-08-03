(function() {

  A2Cribs.loginTest = (function() {

    function loginTest() {}

    loginTest.FacebookLogin = function() {
      return FB.login(this.FacebookLoginCallback, {
        scope: 'email'
      });
    };

    loginTest.FacebookLoginCallback = function(response) {
      if (response.authResponse) {
        return FB.api('/me', A2Cribs.loginTest.FacebookGetUserInfoCallback);
      } else {
        return console.log('User canceled login');
      }
    };

    loginTest.FacebookGetUserInfoCallback = function(response) {
      if (response.id === void 0) return;
      return $.ajax({
        url: myBaseUrl + "Users/FacebookLogin/" + response.id,
        type: "GET",
        context: this,
        success: function(response) {
          return console.log(response);
        }
      });
    };

    loginTest.FacebookLogout = function() {
      return $.ajax({
        url: myBaseUrl + "Users/FacebookLogout",
        type: "GET",
        context: this,
        success: function(response) {
          return console.log(response);
        }
      });
    };

    return loginTest;

  })();

}).call(this);
