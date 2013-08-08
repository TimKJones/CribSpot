(function() {

  A2Cribs.Login = (function() {
    var createUser, validate,
      _this = this;

    function Login() {}

    Login.LANDING_URL = "cribspot.com";

    Login.HTTP_PREFIX = "http://";

    Login.setupUI = function() {
      var _this = this;
      this.div = $("#login_signup");
      this.div.find(".show_signup").click(function() {
        _this.div.find("#login_content").hide('fade');
        return _this.div.find("#signup_content").show('fade');
      });
      this.div.find(".show_login").click(function() {
        _this.div.find("#signup_content").hide('fade');
        return _this.div.find("#login_content").show('fade');
      });
      this.div.find(".user_types").click(function(event) {
        var target;
        target = $(event.target).closest("li");
        _this.div.find(".user_types").removeClass("active");
        $(target).addClass("active");
        _this.div.find(".signup").hide();
        return _this.div.find("#" + ($(target).attr("id")) + "_signup").show();
      });
      this.div.find("#login_button").click(this.cribspotLogin);
      this.div.find("#login_content").submit(this.cribspotLogin);
      this.div.find("#student_submit").click(this.CreateStudent);
      this.div.find("#student_signup").submit(this.CreateStudent);
      this.div.find("#pm_submit").click(this.CreatePropertyManager);
      return this.div.find("#pm_signup").submit(this.CreatePropertyManager);
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
      if ((request_data.User.email != null) && (request_data.User.password != null)) {
        $.post(url, request_data, function(response) {
          var data;
          data = JSON.parse(response);
          if (data.error != null) {
            A2Cribs.UIManager.CloseLogs();
            return A2Cribs.UIManager.Error(data.error);
            /*
            					TODO: GIVE USER THE OPTION TO RESEND CONFIRMATION EMAIL
            					if data.error_type == "EMAIL_UNVERIFIED"
            						A2Cribs.UIManager.Alert data.error
            */
          } else {
            return window.location.href = '/dashboard';
          }
        });
      }
      return false;
    };

    Login.ResendConfirmationEmail = function(email) {
      return $.ajax({
        url: myBaseUrl + "users/ResendConfirmationEmail/" + email,
        type: "POST",
        success: function(response) {
          response = JSON.parse(response);
          if (response.error != null) {
            return A2Cribs.UIManager.Alert(response.error);
          }
        }
      });
    };

    validate = function(user_type, required_fields) {
      var field, isValid, type_prefix, _i, _len;
      type_prefix = user_type === 0 ? "student_" : "pm_";
      A2Cribs.UIManager.CloseLogs();
      isValid = true;
      for (_i = 0, _len = required_fields.length; _i < _len; _i++) {
        field = required_fields[_i];
        if (Login.div.find("#" + type_prefix + field).val().length === 0) {
          isValid = false;
        }
      }
      if (!isValid) A2Cribs.UIManager.Error("Please fill in all of the fields!");
      return isValid;
    };

    createUser = function(user_type, required_fields) {
      var field, request_data, type_prefix, _i, _len;
      type_prefix = user_type === 0 ? "student_" : "pm_";
      if (validate(user_type, required_fields)) {
        if (Login.div.find("#" + type_prefix + "password").val() !== Login.div.find("#" + type_prefix + "confirm_password").val()) {
          return A2Cribs.UIManager.Error("Make sure passwords match!");
        } else {
          request_data = {
            User: {
              user_type: user_type
            }
          };
          for (_i = 0, _len = required_fields.length; _i < _len; _i++) {
            field = required_fields[_i];
            request_data.User[field] = Login.div.find("#" + type_prefix + field).val();
          }
          return $.post("/users/AjaxRegister", request_data, function(response) {
            var data;
            data = JSON.parse(response);
            if (data.error != null) {
              A2Cribs.UIManager.CloseLogs();
              return A2Cribs.UIManager.Error(data.error);
            } else {
              return window.location.href = '/dashboard';
            }
          });
        }
      }
    };

    Login.CreateStudent = function() {
      var required_fields;
      required_fields = ["email", "password", "first_name", "last_name"];
      createUser(0, required_fields);
      return false;
    };

    Login.CreatePropertyManager = function() {
      var required_fields;
      required_fields = ["email", "password", "company_name", "street_address", "phone", "website", "city", "state"];
      createUser(1, required_fields);
      return false;
    };

    return Login;

  }).call(this);

}).call(this);
