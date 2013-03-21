// Generated by CoffeeScript 1.6.1
(function() {

  A2Cribs.SubletAdd = (function() {

    function SubletAdd() {}

    SubletAdd.setupUI = function() {
      var _this = this;
      return $('#goToStep2').click(function(e) {
        e.preventDefault();
        return _this.subletAddStep1();
      });
    };

    SubletAdd.subletAddStep1 = function() {
      var request_data, url,
        _this = this;
      url = "sublets/ajax_add_create";
      request_data = {
        Sublet: {
          university: $('#universitiesInput').val(),
          building_type_id: $('#SubletBuildingTypeId').val(),
          name: $('#SubletName').val()
        }
      };
      return $.post(url, request_data, function(response) {
        var data;
        console.log(request_data);
        console.log(response);
        data = JSON.parse(response);
        console.log(data);
        if (data.registerStatus === 1) {
          return window.location.href = '/dashboard';
        } else {
          $('#registerStatus').empty();
          if (typeof data.email !== 'undefined') {
            $('#inputEmail').effect("highlight", {
              color: "#FF0000"
            }, 3000);
            $('#registerStatus').append('<p>' + data['email'][0] + '<p>');
          }
          if (typeof data.first_name !== 'undefined') {
            $('#inputFirstName').effect("highlight", {
              color: "#FF0000"
            }, 3000);
            $('#registerStatus').append('<p>' + data['first_name'][0] + '<p>');
          }
          if (typeof data.last_name !== 'undefined') {
            $('#inputLastName').effect("highlight", {
              color: "#FF0000"
            }, 3000);
            $('#registerStatus').append('<p>' + data['last_name'][0] + '<p>');
          }
          if (typeof data.password !== 'undefined') {
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

    return SubletAdd;

  })();

}).call(this);
