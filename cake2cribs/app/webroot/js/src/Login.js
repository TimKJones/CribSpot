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
        _this.div.find(".login_row").hide('fade');
        return _this.div.find(".signup_row").show('fade');
      });
      this.div.find(".show_login").click(function() {
        _this.div.find(".signup_row").hide('fade');
        return _this.div.find(".login_row").show('fade');
      });
      this.div.find(".show_pm").click(function() {
        _this.div.find(".student_icon").removeClass("active");
        _this.div.find(".pm_icon").addClass("active");
        _this.div.find(".fb_box").hide();
        _this.div.find(".student_signup").hide();
        return _this.div.find(".pm_signup").show();
      });
      this.div.find(".show_student").click(function() {
        _this.div.find(".pm_icon").removeClass("active");
        _this.div.find(".student_icon").addClass("active");
        _this.div.find(".pm_signup").hide();
        _this.div.find(".fb_box").show();
        return _this.div.find(".student_signup").show();
      });
      this.div.find("#login_content").submit(function(event) {
        return _this.cribspotLogin(event.delegateTarget);
      });
      this.div.find("#student_submit").click(this.CreateStudent);
      this.div.find("#student_signup").submit(this.CreateStudent);
      this.div.find("#pm_submit").click(this.CreatePropertyManager);
      return this.div.find("#pm_signup").submit(this.CreatePropertyManager);
    };

    Login.InitSignupSchoolSelect = function(locations) {
      var location, that, _i, _len;
      this.schoolList = Array();
      for (_i = 0, _len = locations.length; _i < _len; _i++) {
        location = locations[_i];
        this.schoolList.push(location.University.name);
      }
      that = this;
      return $(function() {
        return $(".typeahead").typeahead({
          source: that.schoolList
        });
      });
    };

    Login.cribspotLogin = function(div) {
      var request_data, url,
        _this = this;
      url = myBaseUrl + "users/AjaxLogin";
      request_data = {
        User: {
          email: $(div).find('#inputEmail').val(),
          password: $(div).find('#inputPassword').val()
        }
      };
      if ((request_data.User.email != null) && (request_data.User.password != null)) {
        $.post(url, request_data, function(response) {
          var data;
          data = JSON.parse(response);
          if (data.error != null) {
            if (data.error_type === "EMAIL_UNVERIFIED") {
              return A2Cribs.UIManager.Confirm("Your email address has not yet been confirmed. 							Please click the link provided in your confirmation email. 							Do you want us to resend you the email?", function(resend) {
                if (resend) return _this.ResendConfirmationEmail();
              });
            } else {
              A2Cribs.UIManager.CloseLogs();
              return A2Cribs.UIManager.Error(data.error);
            }
            /*
            					TODO: GIVE USER THE OPTION TO RESEND CONFIRMATION EMAIL
            					if data.error_type == "EMAIL_UNVERIFIED"
            						A2Cribs.UIManager.Alert data.error
            */
          } else {
            A2Cribs.MixPanel.AuthEvent('login', {
              'source': 'cribspot'
            });
            return window.location.reload();
          }
        });
      }
      return false;
    };

    Login.ResendConfirmationEmail = function(canceled) {
      if (canceled == null) canceled = false;
      if (canceled) return;
      return $.ajax({
        url: myBaseUrl + "users/ResendConfirmationEmail",
        type: "POST",
        success: function(response) {
          response = JSON.parse(response);
          if (response.error != null) {
            return A2Cribs.UIManager.Alert(response.error.message);
          } else {
            return A2Cribs.UIManager.Success("Email has been sent! Click the link to verify.");
          }
        }
      });
    };

    validate = function(user_type, required_fields) {
      var field, isValid, phone_number, type_prefix, _i, _len;
      type_prefix = user_type === 0 ? "student_" : "pm_";
      A2Cribs.UIManager.CloseLogs();
      isValid = true;
      for (_i = 0, _len = required_fields.length; _i < _len; _i++) {
        field = required_fields[_i];
        if (Login.div.find("#" + type_prefix + field).val().length === 0) {
          isValid = false;
        }
      }
      if (user_type === 0) {
        if ($("#registered_university").val().length === 0) isValid = false;
      }
      if (user_type === 0) {
        if ($("#student_year").val().length === 0) isValid = false;
      }
      if (!isValid) A2Cribs.UIManager.Error("Please fill in all of the fields!");
      if (user_type === 1) {
        phone_number = Login.div.find("#" + type_prefix + "phone").val().split("-").join("");
        if (phone_number.length !== 10 || isNaN(phone_number)) {
          isValid = false;
          A2Cribs.UIManager.Error("Please enter a valid phone number");
        }
      }
      if (Login.div.find("#" + type_prefix + "password").val().length < 6) {
        isValid = false;
        A2Cribs.UIManager.Error("Please enter a password of 6 or more characters");
      }
      return isValid;
    };

    createUser = function(user_type, required_fields, fields) {
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
          for (_i = 0, _len = fields.length; _i < _len; _i++) {
            field = fields[_i];
            if (Login.div.find("#" + type_prefix + field).val().length !== 0) {
              request_data.User[field] = Login.div.find("#" + type_prefix + field).val();
            }
          }
          request_data.User['registered_university'] = $("#registered_university").val();
          request_data.User['student_year'] = $("#student_year").val();
          return $.post("/users/AjaxRegister", request_data, function(response) {
            var data, email;
            data = JSON.parse(response);
            if (data.error != null) {
              A2Cribs.UIManager.CloseLogs();
              return A2Cribs.UIManager.Error(data.error);
            } else {
              email = null;
              if (user_type === 0) {
                email = $("#student_email").val();
              } else {
                email = $("#pm_email").val();
              }
              A2Cribs.MixPanel.AuthEvent('signup', {
                'user_id': response.success,
                'user_type': user_type,
                'email': email,
                'source': 'cribspot',
                'user_data': request_data
              });
              mixpanel.people.set({
                'user_id': response.success,
                'user_type': user_type,
                'email': email,
                'user_data': request_data
              });
              Login.div.find(".show_login").click();
              return A2Cribs.UIManager.Alert("Check your email to validate your credentials!");
            }
          });
        }
      }
    };

    Login.CreateStudent = function() {
      var fields, required_fields;
      required_fields = ["email", "password", "first_name", "last_name"];
      fields = required_fields.slice(0);
      createUser(0, required_fields, fields);
      return false;
    };

    Login.CreatePropertyManager = function() {
      var fields, required_fields;
      required_fields = ["email", "password", "company_name", "street_address", "phone", "city", "state"];
      fields = required_fields.slice(0);
      fields.push("website");
      createUser(1, required_fields, fields);
      return false;
    };

    return Login;

  }).call(this);

}).call(this);
