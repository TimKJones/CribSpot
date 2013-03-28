(function() {

  A2Cribs.SubletAdd = (function() {

    function SubletAdd() {}

    SubletAdd.setupUI = function() {
      var oldBeginDate, oldEndDate,
        _this = this;
      $('#goToStep2').click(function(e) {
        if (!$('#formattedAddress').text()) {
          return A2Cribs.UIManager.Alert("Please place your street address on the map using the Place On Map button.");
        } else if (!$('#universitiesInput').val()) {
          return A2Cribs.UIManager.Alert("You need to select a university.");
        } else if ($('#SubletUnitNumber').val().length >= 249) {
          return A2Cribs.UIManager.Alert("Your unit number is too long.");
        } else if ($('#SubletName').val().length >= 249) {
          return A2Cribs.UIManager.Alert("Your alternate name is too long.");
        } else {
          e.preventDefault();
          return _this.subletAddStep1();
        }
      });
      $('#goToStep1').click(function(e) {
        return _this.backToStep1();
      });
      $("#goToStep3").click(function(e) {
        var parsedBeginDate, parsedEndDate, todayDate;
        parsedBeginDate = new Date(Date.parse($('#SubletDateBegin').val()));
        parsedEndDate = new Date(Date.parse($('#SubletDateEnd').val()));
        todayDate = new Date();
        if (parsedBeginDate.toString() === "Invalid Date" || parsedEndDate.toString() === "Invalid Date") {
          A2Cribs.UIManager.Alert("Please enter a valid date.");
        }
        if (parsedEndDate.valueOf() <= parsedBeginDate.valueOf() && parsedBeginDate.valueOf() <= todayDate.valueOf()) {
          return A2Cribs.UIManager.Alert("Please enter a valid date.");
        } else if (!$('#SubletNumberBedrooms').val() || $('#SubletNumberBedrooms').val() <= 0 || $('#SubletNumberBedrooms').val() >= 30) {
          return A2Cribs.UIManager.Alert("Please enter a valid number of bedrooms.");
        } else if (!$('#SubletPricePerBedroom').val() || $('#SubletPricePerBedroom').val() < 0 || $('#SubletPricePerBedroom').val() >= 20000) {
          return A2Cribs.UIManager.Alert("Please enter a valid price per bedroom.");
        } else if ($('#SubletShortDescription').val().length >= 161) {
          return A2Cribs.UIManager.Alert("Please keep the short description under 160 characters.");
        } else if (!$('#SubletNumberBathrooms').val() || $('#SubletNumberBathrooms').val() < 0 || $('#SubletNumberBathrooms').val() >= 30) {
          return A2Cribs.UIManager.Alert("Please enter a valid number of bathrooms.");
        } else if (!$('#SubletUtilityCost').val() || $('#SubletUtilityCost').val() < 0 || $('#SubletUtilityCost').val() >= 50000) {
          return A2Cribs.UIManager.Alert("Please enter a valid utility cost.");
        } else if (!$('#SubletDepositAmount').val() || $('#SubletDepositAmount').val() < 0 || $('#SubletDepositAmount').val() >= 50000) {
          return A2Cribs.UIManager.Alert("Please enter a valid deposit amount.");
        } else if ($('#SubletAdditionalFeesDescription').val().length >= 161) {
          return A2Cribs.UIManager.Alert("Please keep the additional fees description under 160 characters.");
        } else if ($('#SubletAdditionalFeesAmount').val() < 0 || $('#SubletAdditionalFeesAmount').val() >= 50000) {
          return A2Cribs.UIManager.Alert("Please enter a valid additional fees amount.");
        } else {
          return A2Cribs.SubletAdd.subletAddStep2();
        }
      });
      $('#finishSubletAdd').click(function(e) {
        if ($('#SubletDescription').val().length >= 254) {
          A2Cribs.UIManager.Alert("Please keep the sublet description under 255 characters.");
        }
        if (!$('#HousemateQuantity').val() || $('#HousemateQuantity').val() >= 50 || $('#HousemateQuantity').val() < 0) {
          A2Cribs.UIManager.Alert("Please enter a valid housemate quantity.");
        }
        if ($('#HousemateMajor').val().length >= 254) {
          A2Cribs.UIManager.Alert("Please keep the majors description under 255 characters.");
        }
        if ($('#HousemateSeeking').val().lenght >= 254) {
          A2Cribs.UIManager.Alert("Please keep the description of who you're seeking under 255 characters.");
        }
        e.preventDefault();
        return _this.subletAddStep3();
      });
      oldBeginDate = new Date($('#SubletDateBegin').val());
      $('#SubletDateBegin').val(oldBeginDate.toDateString());
      oldEndDate = new Date($('#SubletDateEnd').val());
      return $('#SubletDateEnd').val(oldEndDate.toDateString());
    };

    SubletAdd.backToStep1 = function() {
      return $('#server-notice').dialog2("options", {
        content: "Sublets/ajax_add"
      });
      /*$("#universitiesInput").val(A2Cribs.Cache.Step1Data.Sublet.university)
      		$("#SubletBuildingTypeId").val(A2Cribs.Cache.Step1Data.Sublet.building_type_id)
      		$("#SubletName").val(A2Cribs.Cache.Step1Data.Sublet.name)
      		$("#addressToMark").val(A2Cribs.Cache.Step1Data.Sublet.address)
      		A2Cribs.CorrectMarker.FindAddress()
      		$("#SubletUnitNumber").val(A2Cribs.Cache.Step1Data.Sublet.unit_number)
      */
    };

    SubletAdd.subletAddStep1 = function() {
      var request_data, url,
        _this = this;
      url = "sublets/ajax_add_create";
      request_data = {
        Sublet: {
          university_id: parseInt(A2Cribs.CorrectMarker.SelectedUniversity),
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
      A2Cribs.Cache.CacheSubletAddStep1(request_data);
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
      var parsedBeginDate, parsedEndDate, request_data, url,
        _this = this;
      url = "sublets/ajax_add_create";
      parsedBeginDate = new Date(Date.parse($('#SubletDateBegin').val()));
      parsedEndDate = new Date(Date.parse($('#SubletDateEnd').val()));
      request_data = {
        Sublet: {
          date_begin: parsedBeginDate.toISOString(),
          date_end: parsedEndDate.toISOString(),
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
        console.log(data.status);
        if (data.status) {
          A2Cribs.UIManager.Alert(data.status);
        } else {
          A2Cribs.UIManager.Alert(data.error);
        }
        return $('#server-notice').dialog2("close");
      });
    };

    return SubletAdd;

  })();

}).call(this);
