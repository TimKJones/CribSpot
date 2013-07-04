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
      if (my_verification_info.verified_tw) {
        veripanel.find('#veri-tw i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign');
      } else {
        url = myBaseUrl + 'account/getTwitterVerificationUrl';
        $.get(url, function(response) {
          var twitter_verification_url;
          twitter_verification_url = JSON.parse(response).twitter_url;
          return $('#veri-tw').append("<a href = '" + twitter_verification_url + "'>Verify?</a>");
        });
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
      return $('#VerifyUniversityButton').click(function() {
        return _this.VerifyUniversity();
      });
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
            alertify.success('Please check your email for a verification link.', 1500);
          } else {
            alertify.error('Verification not successful: ' + json_response.message, 1500);
          }
          return $('#VerifyUniversityButton').removeAttr('disabled');
        });
      } else {
        return alertify.error('Please enter a university email.', 1500);
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
        'confirm_password': confirm_password,
        'id': id,
        'reset_token': reset_token
      };
      return $.post(myBaseUrl + 'users/ajaxChangePassword', data, function(response) {
        var json_response;
        json_response = JSON.parse(response);
        if (json_response.success === 1) {
          if (id === null && reset_token === null) {
            alertify.success('Password Changed', 1500);
            if (redirect !== null) window.location.href = redirect;
          } else {
            window.location.href = '/dashboard';
          }
        } else {
          alertify.error('Password Failed to Change: ' + json_response.message, 1500);
        }
        return change_password_button.removeAttr('disabled');
      });
    };

    Account.SaveAccount = function() {
      var data, first_name, last_name;
      $('#save_btn').attr('disabled', 'disabled');
      first_name = $('#first_name_input').val();
      last_name = $('#last_name_input').val();
      data = {
        'first_name': first_name,
        'last_name': last_name
      };
      return $.post(myBaseUrl + 'users/ajaxEditUser', data, function(response) {
        var json_response;
        json_response = JSON.parse(response);
        if (json_response.success === 1) {
          alertify.success('Account Saved', 1500);
        } else {
          alertify.error('Account Failed to Save: ' + json_response.message, 1500);
        }
        return $('#save_btn').removeAttr('disabled');
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

    Account.SubmitResetPassword = function(email) {
      var data,
        _this = this;
      data = 'email=' + $("#UserEmail").val();
      return $.post('/users/ajaxResetPassword', data, function(response) {
        data = JSON.parse(response);
        if (data.success === 1) {
          document.location.href = '/users/login?password_reset_redirect=true';
          return false;
        } else {
          alertify.error('Email address is invalid.', 1500);
          return false;
        }
      });
    };

    return Account;

  })();

}).call(this);
