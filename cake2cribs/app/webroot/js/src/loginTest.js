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
        console.log(response);
        return window.location.reload();
      } else {
        return console.log('User canceled login');
      }
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
