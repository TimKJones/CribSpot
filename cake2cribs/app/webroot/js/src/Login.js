(function() {

  A2Cribs.Login = (function() {

    function Login() {}

    Login.LANDING_URL = "cribspot.com";

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
      url = myBaseUrl + "users/AjaxLogin";
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
        if (data.error === void 0) {
          url = document.URL;
          if (url === _this.LANDING_URL || url === _this.HTTP_PREFIX + _this.LANDING_URL || url === _this.HTTP_PREFIX + _this.LANDING_URL + '/') {
            return window.location.href = '/dashboard';
          } else {
            return window.location.href = document.URL;
          }
        } else {
          if (data.error_type === "EMAIL_UNVERIFIED") {
            A2Cribs.UIManager.Alert(data.error);
            return $('#loginStatus').html("<a href='users/verify'>Resend verification email</a>");
          } else {
            $('#loginStatus').html(data.error);
            return $('#loginStatus').effect("highlight", {
              color: "#FF0000"
            }, 3000);
          }
        }
      });
    };

    return Login;

  })();

}).call(this);
