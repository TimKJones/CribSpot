// Generated by CoffeeScript 1.6.1
(function() {

  A2Cribs.SubletAdd = (function() {

    function SubletAdd() {}

    SubletAdd.setupUI = function() {
      var _this = this;
      $('#finishSubletAdd').click(function(e) {
        e.preventDefault();
        return _this.subletAddStep3();
      });
      $('#goToStep2').click(function(e) {
        e.preventDefault();
        return _this.subletAddStep1();
      });
      return $('#goToStep1').click(function(e) {
        e.preventDefault();
        return _this.subletAddStep2();
      });
    };

    SubletAdd.subletAddStep1 = function() {
      var request_data, url,
        _this = this;
      url = "sublets/ajax_add_create";
      request_data = {
        Sublet: {
          university_id: parseInt(window.universitiesArray.indexOf($('#universitiesInput').val()) + parseInt(window.universitiesMap[0].University.id)),
          university: $('#universitiesInput').val(),
          unit_number: $('#SubletUnitNumber').val(),
          address: $("#formattedAddress").text(),
          building_type_id: $('#SubletBuildingTypeId').val(),
          name: $('#SubletName').val(),
          latitude: $('#updatedLat').text(),
          longitude: $('#updatedLong').text()
        },
        Marker: {
          street_address: $("#formattedAddress").text(),
          city: $("#city").text(),
          state: $("#state").text(),
          zip: $("#postal").text()
        },
        CurrentStep: 1
      };
      console.log(request_data);
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

    SubletAdd.subletAddStep2 = function() {
      var request_data, url,
        _this = this;
      url = "sublets/ajax_add_create";
      request_data = {
        Sublet: {
          date_begin: $('#SubletDateBegin').val(),
          date_end: $('#SubletDateEnd').val(),
          flexible_dates: $('#SubletFlexibleDates').val(),
          number_bedrooms: $('#SubletNumberBedrooms').val(),
          price_per_bedroom: $('#SubletPricePerBedroom').val(),
          payment_type_id: $('#SubletPaymentTypeId').val(),
          short_description: $('#SubletShortDescription').val(),
          number_bathrooms: $('#SubletNumberBathrooms').val(),
          bathroom_type_id: $('#SubletBathroomTypeId').val(),
          utility_type_id: $('#SubletUtilityTypeId').val(),
          utility_cost: $('#SubletUtilityCost').val(),
          parking: $('#SubletParking').val(),
          ac: $('#SubletAc').val(),
          furnished_type_id: $('#SubletFurnishedTypeId').val(),
          deposit_amount: $('#SubletDepositAmount').val(),
          additional_fees_description: $('#SubletAdditionalFeesDescription').val(),
          additional_fees_amount: $('#SubletAdditionalFeesAmount').val()
        },
        CurrentStep: 2
      };
      return $.post(url, request_data, function(response) {
        var data;
        console.log(response);
        data = JSON.parse(response);
        console.log(data);
        console.log("Done with step 2");
        return $('#server-notice').dialog2("options", {
          content: "Sublets/ajax_add3"
        });
      });
    };

    SubletAdd.subletAddStep3 = function() {
      var request_data, url,
        _this = this;
      url = "sublets/ajax_add_create";
      request_data = {
        Sublet: {
          description: $('#SubletDescription').val()
        },
        Housemate: {
          quantity: $('#HousemateQuantity').val(),
          enrolled: $('#HousemateEnrolled').val(),
          student_type_id: $('#HousemateStudentTypeId').val(),
          major: $('#HousemateMajor').val(),
          seeking: $('#HousemateSeeking').val(),
          gender_type_id: $('#HousemateGenderTypeId').val()
        },
        CurrentStep: 3,
        Finish: 1
      };
      return $.post(url, request_data, function(response) {
        var data;
        console.log(response);
        data = JSON.parse(response);
        console.log(data);
        console.log("Done");
        alert("You should be finished by now.");
        return $('#server-notice').dialog2("options", {
          content: "Sublets/ajax_add4"
        });
      });
    };

    return SubletAdd;

  })();

}).call(this);
