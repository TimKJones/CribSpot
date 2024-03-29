// Generated by CoffeeScript 1.6.3
(function() {
  A2Cribs.Register = (function() {
    function Register() {}

    Register.RedirectUrl = null;

    Register.setupUI = function() {
      var _this = this;
      return $('#registerForm').submit(function(e) {
        e.preventDefault();
        return _this.cribspotRegister();
      });
    };

    /*
    	Open register modal and feed a specific url to redirect to after register is successful
    */


    Register.InitRegister = function(url) {
      if (url == null) {
        url = null;
      }
      $("#signupModal").modal("show");
      return A2Cribs.Register.RedirectUrl = '/dashboard?post_redirect=true';
    };

    Register.cribspotRegister = function() {
      var request_data, request_form, url,
        _this = this;
      url = "/users/AjaxRegister";
      request_form = $('#registerForm').serializeArray();
      request_data = {
        User: {
          email: $.trim(request_form[0]['value']),
          password: $.trim(request_form[1]['value']),
          first_name: $.trim(request_form[3]['value']),
          last_name: $.trim(request_form[4]['value'])
        }
      };
      return $.post(url, request_data, function(response) {
        var data;
        data = JSON.parse(response);
        console.log(data);
        if (data.success !== void 0 && data.success !== null) {
          return window.location.href = '/users/login?register_success=true';
        } else if (data.error_type === 'EMAIL_EXISTS') {
          A2Cribs.UIManager.Alert(data.error);
          return $('#inputEmail').val("");
        } else {
          if (typeof data.validation.email !== 'undefined') {
            $('#inputEmail').effect("highlight", {
              color: "#FF0000"
            }, 3000);
            $('#registerStatus').append('<p>' + data['email'][0] + '<p>');
          }
          if (typeof data.validation.first_name !== 'undefined') {
            $('#inputFirstName').effect("highlight", {
              color: "#FF0000"
            }, 3000);
            $('#registerStatus').append('<p>' + data['first_name'][0] + '<p>');
          }
          if (typeof data.validation.last_name !== 'undefined') {
            $('#inputLastName').effect("highlight", {
              color: "#FF0000"
            }, 3000);
            $('#registerStatus').append('<p>' + data['last_name'][0] + '<p>');
          }
          if (typeof data.validation.password !== 'undefined') {
            $('#registerStatus').append('<p>' + data['password'][0] + '<p>');
            $('#inputPassword').effect("highlight", {
              color: "#FF0000"
            }, 3000);
            $('#confirmPassword').effect("highlight", {
              color: "#FF0000"
            }, 3000);
          }
          return $('#loginStatus').effect("highlight", {
            color: "#FF0000"
          }, 3000);
        }
      });
    };

    return Register;

  })();

}).call(this);
