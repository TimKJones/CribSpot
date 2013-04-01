(function() {

  A2Cribs.Login = (function() {

    function Login() {}

    Login.LANDING_URL = "localhost";

    Login.HTTP_PREFIX = "http://";

    Login.setupUI = function() {
      var _this = this;
      return $('#loginForm').submit(function(e) {
        e.preventDefault();
        return _this.cribspotLogin();
      });
    };

    Login.cribspotLogin = function() {
      var request_data, url,
        _this = this;
      url = '/' + "users/ajaxLogin";
      request_data = {
        User: {
          email: $('#inputEmail').val(),
          password: $('#inputPassword').val()
        }
      };
      return $.post(url, request_data, function(response) {
        var data;
        console.log(response);
        data = JSON.parse(response);
        console.log(data);
        if (data.loginStatus === 1) {
          url = document.URL;
          if (url === _this.LANDING_URL || url === _this.HTTP_PREFIX + _this.LANDING_URL || url === _this.HTTP_PREFIX + _this.LANDING_URL + '/') {
            return window.location.href = '/dashboard';
          } else {
            return window.location.href = document.URL;
          }
        } else {
          $('#loginStatus').html("Invalid login.");
          return $('#loginStatus').effect("highlight", {
            color: "#FF0000"
          }, 3000);
        }
      });
    };

    return Login;

  })();

}).call(this);
