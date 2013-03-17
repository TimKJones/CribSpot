// Generated by CoffeeScript 1.6.1
(function() {

  A2Cribs.Login = (function() {

    function Login() {}

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
          return window.location.href = '/dashboard';
        } else {
          return $('#loginStatus').html("Invalid login.");
        }
      });
    };

    return Login;

  })();

}).call(this);
