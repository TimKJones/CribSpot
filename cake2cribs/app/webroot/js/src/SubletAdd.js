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
          university_id: window.universitiesArray.indexOf($('#universitiesInput').val()) + window.universitiesMap[0].University.id,
          university: $('#universitiesInput').val(),
          unit_number: $('#SubletUnitNumber').val(),
          address: $("#addressToMark").val(),
          building_type_id: $('#SubletBuildingTypeId').val(),
          name: $('#SubletName').val(),
          latitude: $('#updatedLat').text(),
          longitude: $('#updatedLong').text()
        }
      };
      return $.post(url, request_data, function(response) {
        var data;
        console.log(response);
        data = JSON.parse(response);
        console.log(data);
        return $('#server-notice').dialog2("options", {
          content: "Sublets/ajax_add2"
        });
      });
    };

    return SubletAdd;

  })();

}).call(this);
