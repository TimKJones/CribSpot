(function() {

  A2Cribs.SubletSave = (function() {

    function SubletSave() {}

    SubletSave.SetupUI = function(initialStep) {
      var _this = this;
      this.CurrentStep = initialStep;
      $('.step').eq(this.CurrentStep).siblings().hide();
      this.ProgressBar = new A2Cribs.PostSubletProgress($('.post-sublet-progress'), initialStep);
      $("#address-step").siblings().hide();
      $(".next-btn").click(function(event) {
        if (_this.Validate(_this.CurrentStep + 1)) {
          $(event.currentTarget).closest(".step").hide().next(".step").show();
          _this.CurrentStep++;
          return _this.ProgressBar.next();
        }
      });
      $(".back-btn").click(function(event) {
        $(event.currentTarget).closest(".step").hide().prev(".step").show();
        _this.CurrentStep--;
        return _this.ProgressBar.prev();
      });
      A2Cribs.CorrectMarker.Init();
      $("#SubletShortDescription").keyup(function() {
        if ($(this).val().length >= 160) $(this).val($(this).val().substr(0, 160));
        return $("#desc-char-left").text(160 - $(this).val().length);
      });
      $("#SubletDateBegin").datepicker();
      $("#SubletDateEnd").datepicker();
      $("#universityName").focusout(function() {
        return A2Cribs.CorrectMarker.FindSelectedUniversity();
      });
      A2Cribs.Map.LoadTypeTables();
      return A2Cribs.SubletSave.PopulateInputFields();
    };

    /*
    	Called before advancing steps
    	Returns true if validations pass; false otherwise
    */

    SubletSave.Validate = function(step_) {
      if (step_ >= 1) if (!this.ValidateStep1()) return false;
      if (step_ >= 2) if (!this.ValidateStep2()) return false;
      if (step_ >= 3) {
        if (!this.ValidateStep3()) return false;
        if (!this.SaveSublet()) return false;
      }
      return true;
    };

    SubletSave.ValidateStep1 = function() {
      if (!$('#formattedAddress').val()) {
        A2Cribs.UIManager.Alert("Please place your street address on the map using the Place On Map button.");
        return false;
      }
      if (!$('#universityName').val()) {
        A2Cribs.UIManager.Alert("You need to select a university.");
        return false;
      }
      if ($('#SubletUnitNumber').val().length >= 249) {
        A2Cribs.UIManager.Alert("Your unit number is too long.");
        return false;
      }
      if ($('#SubletName').val().length >= 249) {
        A2Cribs.UIManager.Alert("Your alternate name is too long.");
        return false;
      }
      return true;
    };

    SubletSave.ValidateStep2 = function() {
      var parsedBeginDate, parsedEndDate, todayDate;
      parsedBeginDate = new Date(Date.parse($('#SubletDateBegin').val()));
      parsedEndDate = new Date(Date.parse($('#SubletDateEnd').val()));
      todayDate = new Date();
      if (parsedBeginDate.toString() === "Invalid Date" || parsedEndDate.toString() === "Invalid Date") {
        A2Cribs.UIManager.Alert("Please enter a valid date.");
        return false;
      }
      if (parsedEndDate.valueOf() <= parsedBeginDate.valueOf() && parsedBeginDate.valueOf() <= todayDate.valueOf()) {
        A2Cribs.UIManager.Alert("Please enter a valid date.");
        return false;
      }
      if (!$('#SubletNumberBedrooms').val() || isNaN($("#SubletNumberBedrooms").val()) || $('#SubletNumberBedrooms').val() <= 0 || $('#SubletNumberBedrooms').val() >= 30) {
        A2Cribs.UIManager.Alert("Please enter a valid number of bedrooms.");
        return false;
      }
      if (!$('#SubletPricePerBedroom').val() || isNaN($("#SubletPricePerBedroom").val()) || $('#SubletPricePerBedroom').val() < 1 || $('#SubletPricePerBedroom').val() >= 20000) {
        A2Cribs.UIManager.Alert("Please enter a valid price per bedroom.");
        return false;
      }
      if ($('#SubletShortDescription').val().length === 0) {
        A2Cribs.UIManager.Alert("Please enter a description.");
        return false;
      }
      if ($('#SubletShortDescription').val().length >= 161) {
        A2Cribs.UIManager.Alert("Please keep the description under 160 characters.");
        return false;
      }
      if (!$('#SubletUtilityCost').val() || isNaN($("#SubletUtilityCost").val()) || $('#SubletUtilityCost').val() < 0 || $('#SubletUtilityCost').val() >= 50000) {
        A2Cribs.UIManager.Alert("Please enter a valid utility cost.");
        return false;
      }
      if (!$('#SubletDepositAmount').val() || isNaN($("#SubletDepositAmount").val()) || $('#SubletDepositAmount').val() < 0 || $('#SubletDepositAmount').val() >= 50000) {
        A2Cribs.UIManager.Alert("Please enter a valid deposit amount.");
        return false;
      }
      if ($('#SubletAdditionalFeesDescription').val().length >= 161) {
        A2Cribs.UIManager.Alert("Please keep the additional fees description under 160 characters.");
        return false;
      }
      if (!$('#SubletAdditionalFeesAmount').val() || isNaN($("#SubletAdditionalFeesAmount").val()) || $('#SubletAdditionalFeesAmount').val() < 0 || $('#SubletAdditionalFeesAmount').val() >= 50000) {
        A2Cribs.UIManager.Alert("Please enter a valid 'Other fees' amount.");
        return false;
      }
      return true;
    };

    SubletSave.ValidateStep3 = function() {
      if ($('#HousemateMajor').val().length >= 254) {
        A2Cribs.UIManager.Alert("Please keep the majors description under 255 characters.");
        return false;
      } else if ($("#HousemateStudentType").val() !== "Graduate") {
        if ($("#HousemateYear").val() === "") {
          A2Cribs.UIManager.Alert("Please select a year for your housemates.");
          return false;
        }
      }
      return true;
    };

    /*
    	Retrieves all necessary sublet data and then pulls up the edit sublet interface
    */

    SubletSave.EditSublet = function(sublet_id) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + "Sublets/getSubletDataById/" + sublet_id,
        type: "GET",
        success: function(subletData) {
          subletData = JSON.parse(subletData);
          A2Cribs.SubletSave.PopulateInputFields(subletData);
          /*
          				TODO: Open Modal Here
          */
          A2Cribs.SubletAdd.resizeModal(modal_body);
          return $(window).resize(function() {
            return A2Cribs.SubletAdd.resizeModal(modal_body);
          });
        },
        error: function() {
          return alertify.error("An error occured while loading your sublet data, please try again.", 2000);
        }
      });
    };

    /*
    	Populates all fields in all steps with sublet data loaded for a sublet edit.
    */

    SubletSave.PopulateInputFields = function(subletData) {
      if (subletData == null) subletData = null;
      if (subletData === null) {
        this.InitUniversityAutocomplete();
        this.ResetAllInputFields();
        return;
      }
      this.InitEditStep1(subletData);
      this.InitEditStep2(subletData);
      this.InitEditStep3(subletData);
      return this.InitEditStep4(subletData);
    };

    /*
    	Initializes map and university input autocomplete
    	If subletData is not null, then we populate all inputs in step 1 with loaded sublet data
    */

    SubletSave.InitEditStep1 = function(subletData) {
      if (subletData == null) subletData = null;
      if (subletData === null) return;
      this.InitUniversityAutocomplete();
      if (subletData.University !== null && subletData.University !== void 0) {
        $('#universityName').val(subletData.University.name);
        A2Cribs.CorrectMarker.FindSelectedUniversity();
      }
      if (subletData.Sublet !== null && subletData.Sublet !== void 0) {
        $('#SubletUnitNumber').val(subletData.Sublet.unit_number);
      }
      if (subletData.Marker !== null && subletData.Marker !== void 0) {
        $('#SubletBuildingTypeId').val(subletData.Marker.building_type_id);
        $('#SubletName').val(subletData.Marker.alternate_name);
        $("#formattedAddress").val(subletData.Marker.street_address);
        $('#updatedLat').val(subletData.Marker.latitude);
        $('#updatedLong').val(subletData.Marker.longitude);
        $("#city").val(subletData.Marker.city);
        $("#state").val(subletData.Marker.state);
        $("#postal").val(subletData.Marker.zip);
        $("#addressToMark").val(subletData.Marker.street_address);
        if (subletData.Marker.street_address !== null && subletData.Marker.street_address !== void 0) {
          A2Cribs.CorrectMarker.FindAddress();
        }
      }
      return A2Cribs.CorrectMarker.Disable();
    };

    SubletSave.InitEditStep2 = function(subletData) {
      var beginDate, endDate, formattedBeginDate, formattedEndDate;
      if (subletData === null) return;
      $('#SubletDateBegin').val("");
      $('#SubletDateEnd').val("");
      $('#SubletFlexibleDates').prop("checked", true);
      $('#SubletParking').prop("checked", false);
      $('#SubletAc').prop("checked", false);
      if (subletData.Sublet === null || subletData.Sublet === void 0) return;
      if (subletData.Sublet.date_begin !== null) {
        beginDate = new Date(subletData.Sublet.date_begin);
        formattedBeginDate = A2Cribs.SubletAdd.GetFormattedDate(beginDate);
      }
      if (A2Cribs.Cache.SubletEditInProgress.Sublet.date_end !== null) {
        endDate = new Date(subletData.Sublet.date_end);
        formattedEndDate = A2Cribs.SubletAdd.GetFormattedDate(endDate);
      }
      $('#SubletDateBegin').val(formattedBeginDate);
      $('#SubletDateEnd').val(formattedEndDate);
      if (subletData.Sublet.flexible_dates !== null) {
        $('#SubletFlexibleDates').prop('checked', subletData.Sublet.flexible_dates);
      }
      $('#SubletNumberBedrooms').val(subletData.Sublet.number_bedrooms);
      $('#SubletPricePerBedroom').val(subletData.Sublet.price_per_bedroom);
      $('#SubletShortDescription').val(subletData.Sublet.short_description);
      $('#SubletBathroomType').val(subletData.Sublet.bathroom_type_id);
      $('#SubletUtilityTypeId').val(subletData.Sublet.utility_type_id);
      $('#SubletUtilityCost').val(subletData.Sublet.utility_type_id);
      $('#SubletParking').prop("checked", subletData.Sublet.parking);
      $('#SubletAc').prop("checked", subletData.Sublet.ac);
      $('#SubletFurnishedType').val(subletData.Sublet.furnished_type_id);
      $('#SubletDepositAmount').val(subletData.Sublet.deposit_amount);
      $('#SubletAdditionalFeesDescription').val(subletData.Sublet.additional_fees_description);
      return $('#SubletAdditionalFeesAmount').val(subletData.Sublet.additional_fees_amount);
    };

    /*
    	Initialize step 3 - Housemate data
    */

    SubletSave.InitEditStep3 = function(subletData) {
      if (subletData === null) return;
      $("#HousemateEnrolled").prop("checked", false);
      if (subletData.Housemate === null || subletData.Housemate === void 0) return;
      $("#HousemateQuantity").val(subletData.Housemate.quantity);
      $("#HousemateEnrolled").prop("checked", subletData.Housemate.enrolled);
      $("#HousemateStudentType").val(subletData.Housemate.student_type_id);
      $("#HousemateMajor").val(subletData.Housemate.major);
      $("#HousemateGenderType").val(subletData.Housemate.gender_type_id);
      return $("#HousemateYear").val(subletData.Housemate.year);
    };

    /*
    	Initialize step 4 - Photos
    */

    SubletSave.InitEditStep4 = function() {
      return A2Cribs.PhotoManager.LoadImages();
    };

    /*
    	Reset all input fields for a new sublet posting process
    */

    SubletSave.ResetAllInputFields = function() {};

    SubletSave.InitUniversityAutocomplete = function() {
      return $.ajax({
        url: myBaseUrl + "universities/loadAll",
        success: function(response) {
          var university, _i, _len, _ref;
          A2Cribs.CorrectMarker.universitiesMap = JSON.parse(response);
          A2Cribs.CorrectMarker.SchoolList = [];
          _ref = A2Cribs.CorrectMarker.universitiesMap;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            university = _ref[_i];
            A2Cribs.CorrectMarker.SchoolList.push(university.University.name);
          }
          return $("#universityName").typeahead({
            source: A2Cribs.CorrectMarker.SchoolList
          });
        }
      });
    };

    SubletSave.UtilityChanged = function() {
      if ($("#SubletUtilityType").val() === "Included") {
        return $("#SubletUtilityCost").val("0");
      }
    };

    SubletSave.StudentTypeChanged = function() {
      if ($("#HousemateStudentType").val() === "Graduate") {
        return $("#HousemateYear").val(0);
      }
    };

    /*
    	Submits sublet to backend to save
    	Assumes all front-end validations have been passed.
    */

    SubletSave.SaveSublet = function() {
      var url,
        _this = this;
      url = "/sublets/ajax_submit_sublet";
      return $.post(url, A2Cribs.SubletSave.GetSubletObject(), function(response) {
        var data;
        data = JSON.parse(response);
        console.log(data.status);
        if (data.status) {
          A2Cribs.UIManager.Alert(data.status);
          A2Cribs.ShareManager.SavedListing = data.newid;
          return true;
        } else {
          A2Cribs.UIManager.Alert(data.error);
          return false;
        }
      });
    };

    /*
    	Called when user finishes the final step of sublet add/edit.
    	Closes sublet modal and redirects user to map with sublet popup open.
    */

    SubletSave.FinishSubletSave = function() {
      /*
      		TODO: Close Modal
      */      if (!isNaN(A2Cribs.ShareManager.SavedListing)) {
        return window.location.href = "/sublet/" + A2Cribs.ShareManager.SavedListing;
      }
    };

    /*
    	Returns an object containing all sublet data from all 4 steps.
    */

    SubletSave.GetSubletObject = function() {
      var subletObject;
      return subletObject = {
        Sublet: {
          id: $("#subletId").val(),
          university_id: $("#universityId").val(),
          university_name: $("#universityName").val(),
          building_type_id: $('#buildingType').val(),
          date_begin: this.GetMysqlDateFormat($('#SubletDateBegin').val()),
          date_end: this.GetMysqlDateFormat($('#SubletDateEnd').val()),
          number_bedrooms: $('#SubletNumberBedrooms').val(),
          price_per_bedroom: $('#SubletPricePerBedroom').val(),
          payment_type_id: 1,
          short_description: $('#SubletShortDescription').val(),
          description: $('#SubletLongDescription').val(),
          bathroom_type_id: $('#SubletBathroomType').val(),
          utility_type_id: $('#SubletUtilityType').val(),
          utility_cost: $('#SubletUtilityCost').val(),
          deposit_amount: $('#SubletDepositAmount').val(),
          additional_fees_description: $('#SubletAdditionalFeesDescription').val(),
          additional_fees_amount: $('#SubletDepositAmount').val(),
          unit_number: $('#SubletUnitNumber').val(),
          flexible_dates: $('#SubletFlexibleDates').is(':checked'),
          furnished_type_id: $('#SubletFurnishedType').val(),
          ac: $('#ac').val() === "Yes",
          parking: $('#parking').val() === "Yes"
        },
        Marker: {
          alternate_name: $('#SubletName').val(),
          street_address: $("#formattedAddress").val(),
          building_type_id: $('#buildingType').val(),
          city: $('#city').val(),
          state: $('#state').val(),
          zip: $('#postal').val(),
          latitude: $('#updatedLat').val(),
          longitude: $('#updatedLong').val()
        },
        Housemate: {
          quantity: $("#HousemateQuantity").val(),
          enrolled: $("#HousemateEnrolled").is(':checked'),
          student_type_id: $("#HousemateStudentType").val(),
          major: $("#HousemateMajor").val(),
          gender_type_id: $("#HousemateGenderType").val(),
          year: $("#HousemateYear").val()
        }
      };
    };

    /*
    	Replaces '/' with '-' to make convertible to mysql datetime format
    */

    SubletSave.GetMysqlDateFormat = function(dateString) {
      var beginDateFormatted, date, day, month, year;
      date = new Date(dateString);
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = year + "-" + month + "-" + day;
    };

    SubletSave.GetTodaysDate = function() {
      var dd, mm, today, yyyy;
      today = new Date();
      dd = today.getDate();
      mm = today.getMonth() + 1;
      yyyy = today.getFullYear();
      if (dd < 10) dd = '0' + dd;
      if (mm < 10) mm = '0' + mm;
      today = mm + '/' + dd + '/' + yyyy;
      return today;
    };

    return SubletSave;

  })();

}).call(this);
