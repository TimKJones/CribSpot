(function() {

  A2Cribs.Login = (function() {
    var createUser, setup_facebook_signup_modal, validate,
      _this = this;

    function Login() {}

    Login.LANDING_URL = "cribspot.com";

    Login.HTTP_PREFIX = "https://";

    $(document).ready(function() {
      return Login.CheckLoggedIn();
    });

    /*
    	Private function setup facebook signup modal
    	Given a user, hides the facebook signup button
    	and populates that area with the users profile
    	picture and populates the input fields with
    	first name and last name
    */

    setup_facebook_signup_modal = function(user) {
      $(".fb-name").text(user.first_name);
      $(".fb-image").attr("src", user.img_url);
      $("#signup_modal").find(".login-separator").fadeOut();
      $("#signup_modal").find(".fb-login").fadeOut('slow', function() {
        return $(".fb-signup-welcome").fadeIn();
      });
      $("#student_first_name").val(user.first_name);
      $("#student_last_name").val(user.last_name);
      return $("#student_email").focus();
    };

    /*
    	Check Logged In
    	Trys to fetch the user information from the backend
    	If logged in populates the header
    	Called when the document is ready
    */

    Login.CheckLoggedIn = function() {
      var deferred,
        _this = this;
      deferred = new $.Deferred();
      $.ajax({
        url: myBaseUrl + 'Users/IsLoggedIn',
        success: function(response) {
          var _ref;
          response = JSON.parse(response);
          if (response.error != null) return deferred.reject();
          if (response.success === "LOGGED_IN") {
            _this.logged_in = true;
            _this.PopulateHeader(response.data);
            _this.PopulateFavorites((_ref = response.data) != null ? _ref.favorites : void 0);
          } else if (response.success === "NOT_LOGGED_IN") {
            _this.logged_in = false;
            _this.ResetHeader();
          }
          return deferred.resolve(response);
        },
        error: function(response) {
          console.log(response);
          return deferred.reject();
        }
      });
      return deferred.promise();
    };

    /*
    	Signup Modal SetupUI
    	Adds click listeners to the show signup buttons, fb signup
    	and submit for the new user form
    */

    Login.SignupModalSetupUI = function() {
      var _this = this;
      $(".show_signup_modal").click(function() {
        $("#login_modal").modal("hide");
        return $("#signup_modal").modal("show").find(".signup_message").text("Signup for Cribspot.");
      });
      $("#signup_modal").find("form").submit(function(event) {
        $("#signup_modal").find(".signup-button").button('loading');
        _this.CreateStudent(event.delegateTarget).always(function() {
          return $("#signup_modal").find(".signup-button").button('reset');
        });
        return false;
      });
      return $("#signup_modal").find(".fb-login").click(function() {
        $(".fb-login").button('loading');
        return _this.FacebookJSLogin().done(function(response) {
          var _ref;
          if (response.success === "NOT_LOGGED_IN") {
            return setup_facebook_signup_modal(response.data);
          } else if (response.success === "LOGGED_IN") {
            $(".modal").modal('hide');
            _this.logged_in = true;
            A2Cribs.MixPanel.Event("Logged In", null);
            _this.PopulateHeader(response.data);
            return _this.PopulateFavorites((_ref = response.data) != null ? _ref.favorites : void 0);
          }
        }).always(function() {
          return $(".fb-login").button('reset');
        });
      });
    };

    /*
    	Login Modal SetupUI
    	Adds Listeners to open login modal, submit login,
    	and fb login
    */

    Login.LoginModalSetupUI = function() {
      var _this = this;
      $(".show_login_modal").click(function() {
        $("#signup_modal").modal("hide");
        return $("#login_modal").modal("show");
      });
      $("#login_modal").find("form").submit(function(event) {
        $("#login_modal").find(".signup-button").button('loading');
        _this.cribspotLogin(event.delegateTarget).always(function() {
          return $("#login_modal").find(".signup-button").button('reset');
        });
        return false;
      });
      return $("#login_modal").find(".fb-login").click(function() {
        $(".fb-login").button('loading');
        return _this.FacebookJSLogin().done(function(response) {
          var _ref;
          if (response.success === "NOT_LOGGED_IN") {
            $("#login_modal").modal('hide');
            $("#signup_modal").modal('show');
            return setup_facebook_signup_modal(response.data);
          } else if (response.success === "LOGGED_IN") {
            $(".modal").modal('hide');
            _this.logged_in = true;
            A2Cribs.MixPanel.Event("Logged In", null);
            _this.PopulateHeader(response.data);
            return _this.PopulateFavorites((_ref = response.data) != null ? _ref.favorites : void 0);
          }
        }).always(function() {
          return $(".fb-login").button('reset');
        });
      });
    };

    /*
    	Login Page SetupUI
    	Sets up listeners for full page login
    */

    Login.LoginPageSetupUI = function() {
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
      this.div.find(".fb_login_btn").click(function() {
        $(".fb-login").button('loading');
        return _this.FacebookJSLogin().done(function(response) {
          if (response.success === "NOT_LOGGED_IN") {
            _this.div.find("#student_first_name").val(response.data.first_name);
            _this.div.find("#student_last_name").val(response.data.last_name);
            _this.div.find(".fb-image").attr("src", response.data.img_url);
            $(".fb-name").text(response.data.first_name);
            _this.div.find(".show_signup").first().click();
            _this.div.find(".show_student").first().click();
            _this.div.find(".email_login_message").fadeOut('slow', function() {
              return _this.div.find(".fb-signup-welcome").fadeIn();
            });
            return _this.div.find("#student_email").focus();
          } else if (response.success === "LOGGED_IN") {
            _this.logged_in = true;
            A2Cribs.MixPanel.Event("Logged In", null);
            return location.reload();
          }
        }).always(function() {
          return $(".fb-login").button('reset');
        });
      });
      this.div.find("#login_content").submit(function(event) {
        _this.cribspotLogin(event.delegateTarget).done(function() {
          return location.reload();
        });
        return false;
      });
      this.div.find("#student_signup").submit(function() {
        _this.CreateStudent().done(function() {
          return location.reload();
        });
        return false;
      });
      return this.div.find("#pm_signup").submit(function() {
        _this.CreatePropertyManager().done(function() {
          return location.reload();
        });
        return false;
      });
    };

    /*
    	Reset header
    	Shows Login and signup buttons and removes
    	the user information from the header
    */

    Login.ResetHeader = function() {
      $(".personal_menu").hide();
      $(".personal_buttons").hide();
      $(".signup_btn").show();
      return $(".nav-text").show();
    };

    /*
    	Populate the header
    	Fill in dropdowns and show picture of the user
    */

    Login.PopulateHeader = function(user) {
      var example;
      example = user;
      $(".signup_btn").hide();
      $(".nav-text").hide();
      $(".personal_buttons").show();
      A2Cribs.FavoritesManager.InitializeFavorites(user.favorites);
      $(".personal_menu").find(".user_name").text(user.name);
      $(".personal_menu").find("img").attr("src", user.img_url);
      if (user.num_messages !== 0) {
        $(".personal_buttons").find(".message_count").show().text(user.num_messages);
      }
      return $(".personal_menu_" + user.user_type).show();
    };

    /*
    	Populate favorites
    	Highlight or un-highlight favorites
    */

    Login.PopulateFavorites = function(favorites) {
      var listing_id, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = favorites.length; _i < _len; _i++) {
        listing_id = favorites[_i];
        _results.push($(".favorite_listing*[data-listing-id='" + listing_id + "']").addClass("active"));
      }
      return _results;
    };

    /*
    	Facebook JS Login
    */

    Login.FacebookJSLogin = function() {
      var _this = this;
      this.fb_login_deferred = new $.Deferred();
      FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
          if ((response != null) && (response.authResponse != null)) {
            return _this.AttemptFacebookLogin(response.authResponse);
          } else {
            A2Cribs.UIManager.Error("We're having trouble logging you in with facebook, but don't worry!					You can still create an account with our regular login.");
            return _this.fb_login_deferred.reject();
          }
        } else {
          return FB.login(function(response) {
            if ((response != null) && (response.authResponse != null)) {
              return _this.AttemptFacebookLogin(response.authResponse);
            } else {
              A2Cribs.UIManager.Error("We're having trouble logging you in with facebook, but don't worry!						You can still create an account with our regular login.");
              return _this.fb_login_deferred.reject();
            }
          }, {
            scope: 'email'
          });
        }
      });
      return this.fb_login_deferred.promise();
    };

    /*
    	Send signed request to server to finish registration
    */

    Login.AttemptFacebookLogin = function(authResponse) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + 'Users/AttemptFacebookLogin',
        data: authResponse,
        type: 'POST',
        success: function(response) {
          response = JSON.parse(response);
          if (response.error != null) return _this.fb_login_deferred.reject();
          return _this.fb_login_deferred.resolve(response);
        },
        error: function(response) {
          console.log(response);
          return _this.fb_login_deferred.reject();
        }
      });
    };

    Login.cribspotLogin = function(div) {
      var request_data, url,
        _this = this;
      this._login_deferred = new $.Deferred();
      url = myBaseUrl + "users/AjaxLogin";
      request_data = {
        User: {
          email: $(div).find('#inputEmail').val(),
          password: $(div).find('#inputPassword').val()
        }
      };
      if ((request_data.User.email != null) && (request_data.User.password != null)) {
        $.post(url, request_data, function(response) {
          var data, _ref;
          data = JSON.parse(response);
          console.log(data);
          if (data.error != null) {
            if (data.error_type === "EMAIL_UNVERIFIED") {
              A2Cribs.UIManager.Confirm("Your email address has not yet been confirmed. 							Please click the link provided in your confirmation email. 							Do you want us to resend you the email?", function(resend) {
                if (resend) return _this.ResendConfirmationEmail();
              });
            } else {
              A2Cribs.UIManager.CloseLogs();
              A2Cribs.UIManager.Error(data.error);
            }
            return _this._login_deferred.reject();
          } else {
            A2Cribs.MixPanel.AuthEvent('login', {
              'source': 'cribspot'
            });
            $(".modal").modal('hide');
            _this.PopulateHeader(data.data);
            _this.PopulateFavorites((_ref = data.data) != null ? _ref.favorites : void 0);
            _this.logged_in = true;
            A2Cribs.MixPanel.Event("Logged In", null);
            return _this._login_deferred.resolve();
          }
        });
      }
      return this._login_deferred.promise();
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

    validate = function(user_type, required_fields, div) {
      var field, isValid, phone_number, type_prefix, _i, _len;
      type_prefix = user_type === 0 ? "student_" : "pm_";
      A2Cribs.UIManager.CloseLogs();
      isValid = true;
      for (_i = 0, _len = required_fields.length; _i < _len; _i++) {
        field = required_fields[_i];
        if (div.find("#" + type_prefix + field).val().length === 0) {
          isValid = false;
        }
      }
      if (!isValid) A2Cribs.UIManager.Error("Please fill in all of the fields!");
      if (user_type === 1) {
        phone_number = div.find("#" + type_prefix + "phone").val().split("-").join("");
        if (phone_number.length !== 10 || isNaN(phone_number)) {
          isValid = false;
          A2Cribs.UIManager.Error("Please enter a valid phone number");
        }
      }
      if (div.find("#" + type_prefix + "password").val().length < 6) {
        isValid = false;
        A2Cribs.UIManager.Error("Please enter a password of 6 or more characters");
      }
      return isValid;
    };

    createUser = function(user_type, required_fields, fields, div) {
      var field, request_data, type_prefix, _i, _len;
      Login._create_user_deferred = new $.Deferred();
      type_prefix = user_type === 0 ? "student_" : "pm_";
      if (validate(user_type, required_fields, div)) {
        if (div.find("#" + type_prefix + "confirm_password").val() != null) {
          if (div.find("#" + type_prefix + "password").val() !== div.find("#" + type_prefix + "confirm_password").val()) {
            A2Cribs.UIManager.Error("Make sure passwords match!");
            return;
          }
        }
        request_data = {
          User: {
            user_type: user_type
          }
        };
        for (_i = 0, _len = fields.length; _i < _len; _i++) {
          field = fields[_i];
          if (div.find("#" + type_prefix + field).val().length !== 0) {
            request_data.User[field] = div.find("#" + type_prefix + field).val();
          }
        }
        $.post("/users/AjaxRegister", request_data, function(response) {
          var data, email, _ref;
          data = JSON.parse(response);
          if (data.error != null) {
            A2Cribs.UIManager.CloseLogs();
            A2Cribs.UIManager.Error(data.error);
            return Login._create_user_deferred.reject();
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
            Login.PopulateHeader(data.data);
            Login.PopulateFavorites((_ref = data.data) != null ? _ref.favorites : void 0);
            Login.logged_in = true;
            A2Cribs.MixPanel.Event("Logged In", null);
            $(".modal").modal('hide');
            return Login._create_user_deferred.resolve();
          }
        });
        return Login._create_user_deferred.promise();
      }
      return Login._create_user_deferred.reject();
    };

    Login.CreateStudent = function(div) {
      var fields, required_fields;
      div = !(div != null) ? this.div : $(div);
      required_fields = ["email", "password", "first_name", "last_name"];
      fields = required_fields.slice(0);
      return createUser(0, required_fields, fields, div);
    };

    Login.CreatePropertyManager = function() {
      var fields, required_fields;
      required_fields = ["email", "password", "company_name", "street_address", "phone", "city", "state"];
      fields = required_fields.slice(0);
      fields.push("website");
      return createUser(1, required_fields, fields, this.div);
    };

    return Login;

  }).call(this);

}).call(this);
