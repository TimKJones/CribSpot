// Generated by CoffeeScript 1.4.0
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
        veripanel.find('#veri-email i:last-child').removeClass('unverified').addClass('verified');
      }
      if (my_verification_info.verified_edu) {
        veripanel.find('#veri-edu i:last-child').removeClass('unverified').addClass('verified');
      }
      if (my_verification_info.verified_fb) {
        veripanel.find('#veri-fb  i:last-child').removeClass('unverified').addClass('verified');
      }
      if (my_verification_info.verified_tw) {
        veripanel.find('#veri-tw i:last-child').removeClass('unverified').addClass('verified');
      }
      return $('.veridd').each(function(index, element) {
        return $(element).tooltip({
          'title': 'Verify?',
          'trigger': 'hover'
        });
      });
    };

    Account.Direct = function(directive) {};

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
          Alertify.log.success('Account Saved');
          console.log(JSON.parse(json_response.user));
        } else {
          Alertify.log.error('Account Failed to Save: ' + json_response.message);
        }
        return $('#save_btn').removeAttr('disabled');
      });
    };

    return Account;

  })();

}).call(this);
