(function() {

  A2Cribs.Account = (function() {

    function Account() {}

    Account.setupUI = function() {
      var my_verification_info, url, veripanel,
        _this = this;
      url = myBaseUrl + "university/getAll/";
      $.get(url, function(data) {
        _this.UniversityData = JSON.parse(data);
        _this.UniversityNames = [];
        _this.UniversityID = [];
        _.each(_this.UniversityData, function(value, key, list) {
          _this.UniversityNames[key] = value['University']['name'];
          return _this.UniversityID[key] = value['University']['id'];
        });
        $('#university').typeahead({
          source: _this.UniversityNames
        });
        return $('#save_btn').click(function() {
          return _this.SaveAccount();
        });
      });
      my_verification_info = A2Cribs.VerifyManager.getMyVerification();
      veripanel = $('#my-verification-panel');
      if (my_verification_info.verified_email) {
        veripanel.find('#veri-email i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign');
      }
      if (my_verification_info.verified_edu) {
        veripanel.find('#veri-edu i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign');
      }
      if (my_verification_info.verified_fb) {
        veripanel.find('#veri-fb  i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign');
      } else {
        $('#veri-fb').append("<a href = '#'>Verify?</a>").click(this.FacebookConnect);
      }
      $('.veridd').each(function(index, element) {
        return $(element).tooltip({
          'title': 'Verify?',
          'trigger': 'hover'
        });
      });
      $('#changePasswordButton').click(function() {
        return _this.ChangePassword($('#changePasswordButton'), $('#new_password').val(), $('#confirm_password').val());
      });
      $('#VerifyUniversityButton').click(function() {
        return _this.VerifyUniversity();
      });
      $('#changePhoneBtn').click(function() {
        return _this.SavePhone();
      });
      $('#changeAddressBtn').click(function() {
        return _this.SaveAddress();
      });
      $('#changeCompanyNameBtn').click(function() {
        return _this.SaveCompanyName();
      });
      return $('#changeFirstLastNameButton').click(function() {
        return _this.SaveFirstLastName();
      });
    };

    Account.SaveFirstLastName = function() {
      var pair;
      pair = {
        'first_name': $("#first_name_input").val(),
        'last_name': $("#last_name_input").val()
      };
      return this.SaveAccount(pair);
    };

    Account.SaveCompanyName = function() {
      var pair;
      pair = {
        'company_name': $("#company_name_input").val()
      };
      return this.SaveAccount(pair);
    };

    Account.SavePhone = function() {
      var pair, phone;
      phone = $("#phone_input").val();
      if (this.ValidatePhone(phone)) {
        pair = {
          'phone': phone
        };
        return this.SaveAccount(pair);
      } else {
        return A2Cribs.UIManager.Error("Invalid phone number");
      }
    };

    Account.ValidatePhone = function(phone) {
      phone = phone.replace(/[^0-9]/g, '');
      return phone.length === 10;
    };

    Account.SaveAddress = function() {
      var city, pair, street_address;
      street_address = $("#street_address_input").val();
      city = $("#city_address_input").val();
      pair = {
        'street_address': street_address,
        'city': city
      };
      return this.SaveAccount(pair);
    };

    Account.Direct = function(directive) {};

    Account.VerifyUniversity = function() {
      var data, university_email;
      $('#VerifyUniversityButton').attr('disabled', 'disabled');
      university_email = $('#university_email').val();
      data = {
        'university_email': university_email
      };
      if (university_email.search('.edu') !== -1) {
        return $.post(myBaseUrl + 'users/verifyUniversity', data, function(response) {
          var json_response;
          console.log(data);
          json_response = JSON.parse(response);
          if (json_response.success === 1) {
            A2Cribs.UIManager.Error('Please check your email for a verification link.');
          } else {
            A2Cribs.UIManager.Error('Verification not successful: ' + json_response.message);
          }
          return $('#VerifyUniversityButton').removeAttr('disabled');
        });
      } else {
        return A2Cribs.UIManager.Error('Please enter a university email.');
      }
    };

    Account.ChangePassword = function(change_password_button, new_password, confirm_password, id, reset_token, redirect) {
      var data;
      if (id == null) id = null;
      if (reset_token == null) reset_token = null;
      if (redirect == null) redirect = null;
      change_password_button.attr('disabled', 'disabled');
      data = {
        'new_password': new_password,
        'confirm_password': confirm_password
      };
      if (id !== null && reset_token !== null) {
        data['id'] = id;
        data['reset_token'] = reset_token;
      }
      if (new_password !== confirm_password) {
        A2Cribs.UIManager.Alert("Passwords do not match.");
        return;
      }
      return $.post(myBaseUrl + 'users/AjaxChangePassword', data, function(response) {
        response = JSON.parse(response);
        if (response.error === void 0) {
          if (id === null && reset_token === null) {
            alertify.success('Password Changed', 3000);
            if (redirect !== null) window.location.href = redirect;
          } else {
            window.location.href = '/dashboard';
          }
        } else {
          A2Cribs.UIManager.Alert(response.error);
        }
        return change_password_button.removeAttr('disabled');
      });
    };

    Account.SaveAccount = function(keyValuePairs) {
      if (keyValuePairs == null) keyValuePairs = null;
      return $.post(myBaseUrl + 'users/AjaxEditUser', keyValuePairs, function(response) {
        var json_response;
        json_response = JSON.parse(response);
        if (json_response.error === void 0) {
          alertify.success('Account Saved', 3000);
        } else {
          A2Cribs.UIManager.Error('Account Failed to Save: ' + json_response.error.message);
        }
        return $('.save_btn').removeAttr('disabled');
      });
    };

    Account.FacebookConnect = function() {
      return FB.login(function(response) {
        $.ajax({
          url: myBaseUrl + "account/verifyFacebook",
          data: {
            'signed_request': response.authResponse.signedRequest
          },
          type: "POST"
        });
        return document.location.href = '/account';
      });
    };

    /*
    	Submits email address for which to reset password.
    */

    Account.SubmitResetPassword = function(email) {
      var data,
        _this = this;
      data = 'email=' + $("#UserEmail").val();
      return $.post('/users/AjaxResetPassword', data, function(response) {
        data = JSON.parse(response);
        if (data.success != null) {
          A2Cribs.UIManager.Alert("Email sent to reset password!");
          return false;
        } else {
          A2Cribs.UIManager.Error(data.error);
          return false;
        }
      });
    };

    return Account;

  })();

}).call(this);
