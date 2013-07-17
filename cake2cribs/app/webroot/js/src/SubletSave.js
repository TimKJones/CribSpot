(function() {

  A2Cribs.SubletSave = (function() {

    function SubletSave() {}

    SubletSave.prototype.setupUI = function(div) {
      var _this = this;
      if (!(A2Cribs.Geocoder != null)) {
        A2Cribs.Geocoder = new google.maps.Geocoder();
      }
      this.div = div;
      div.find("#Sublet_short_description").keyup(function() {
        if ($(this).val().length >= 160) $(this).val($(this).val().substr(0, 160));
        return div.find("#desc-char-left").text(160 - $(this).val().length);
      });
      div.find("#Sublet_utility_type_id").change(function() {
        if (+div.find("#Sublet_utility_type_id").val() === 1) {
          return div.find("#Sublet_utility_cost").val("0");
        }
      });
      div.find("#Housemate_student_type_id").change(function() {
        if (+div.find("#Housemate_student_type_id").val() === 1) {
          return _this.div.find("#Housemate_year").val(0);
        }
      });
      div.find(".required").keydown(function() {
        return $(this).parent().removeClass("error");
      });
      div.find(".date_field").datepicker();
      this.MiniMap = new A2Cribs.MiniMap(div);
      this.PhotoManager = new A2Cribs.PhotoManager(div);
      return A2Cribs.Map.LoadTypeTables();
    };

    /*
    	Called before advancing steps
    	Returns true if validations pass; false otherwise
    */

    SubletSave.prototype.Validate = function(step_) {
      if (step_ >= 1) if (!this.ValidateStep1()) return false;
      if (step_ >= 2) if (!this.ValidateStep2()) return false;
      if (step_ >= 3) if (!this.ValidateStep3()) return false;
      return true;
    };

    SubletSave.prototype.ValidateStep1 = function() {
      var isValid;
      isValid = true;
      A2Cribs.UIManager.CloseLogs();
      if (!this.div.find('#Marker_street_address').val()) {
        A2Cribs.UIManager.Error("Please place your street address on the map using the Place On Map button.");
        this.div.find('#Marker_street_address').parent().addClass("error");
        isValid = false;
      }
      if (!this.div.find('#University_name').val()) {
        A2Cribs.UIManager.Error("You need to select a university.");
        this.div.find('#University_name').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find('#Marker_building_type_id').val().length === 0) {
        A2Cribs.UIManager.Error("You need to select a building type.");
        this.div.find('#Marker_building_type_id').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find('#Sublet_unit_number').val().length >= 249) {
        A2Cribs.UIManager.Error("Your unit number is too long.");
        this.div.find('#Sublet_unit_number').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find('#Marker_alternate_name').val().length >= 249) {
        A2Cribs.UIManager.Error("Your alternate name is too long.");
        this.div.find('#Marker_alternate_name').parent().addClass("error");
        isValid = false;
      }
      return isValid;
    };

    SubletSave.prototype.ValidateStep2 = function() {
      var descLength, isValid, parsedBeginDate, parsedEndDate, todayDate;
      isValid = true;
      A2Cribs.UIManager.CloseLogs();
      parsedBeginDate = new Date(Date.parse(this.div.find('#Sublet_date_begin').val()));
      parsedEndDate = new Date(Date.parse(this.div.find('#Sublet_date_end').val()));
      todayDate = new Date();
      if (parsedBeginDate.toString() === "Invalid Date" || parsedEndDate.toString() === "Invalid Date") {
        A2Cribs.UIManager.Error("Please enter a valid date.");
        this.div.find('#Sublet_date_begin').parent().addClass("error");
        this.div.find('#Sublet_date_end').parent().addClass("error");
        isValid = false;
      } else if (parsedEndDate.valueOf() <= parsedBeginDate.valueOf()) {
        A2Cribs.UIManager.Error("Please enter a valid date.");
        this.div.find('#Sublet_date_begin').parent().addClass("error");
        this.div.find('#Sublet_date_end').parent().addClass("error");
        isValid = false;
      }
      if (!this.div.find('#Sublet_number_bedrooms').val() || isNaN(parseInt(this.div.find("#Sublet_number_bedrooms").val())) || this.div.find('#Sublet_number_bedrooms').val() <= 0 || this.div.find('#Sublet_number_bedrooms').val() >= 30) {
        A2Cribs.UIManager.Error("Please enter a valid number of bedrooms.");
        this.div.find('#Sublet_number_bedrooms').parent().addClass("error");
        isValid = false;
      }
      if (!this.div.find('#Sublet_price_per_bedroom').val() || isNaN(parseInt(this.div.find("#Sublet_price_per_bedroom").val())) || this.div.find('#Sublet_price_per_bedroom').val() < 1 || this.div.find('#Sublet_price_per_bedroom').val() >= 20000) {
        A2Cribs.UIManager.Error("Please enter a valid price per bedroom.");
        this.div.find('#Sublet_price_per_bedroom').parent().parent().addClass("error");
        isValid = false;
      }
      if (this.div.find('#Sublet_short_description').val().length === 0) {
        A2Cribs.UIManager.Error("Please enter a description.");
        this.div.find('#Sublet_short_description').parent().addClass("error");
        isValid = false;
      }
      if (!this.div.find('#Sublet_utility_cost').val() || isNaN(parseInt(this.div.find("#Sublet_utility_cost").val())) || this.div.find('#Sublet_utility_cost').val() < 0 || this.div.find('#Sublet_utility_cost').val() >= 50000) {
        A2Cribs.UIManager.Error("Please enter a valid utility cost.");
        this.div.find('#Sublet_utility_cost').parent().addClass("error");
        isValid = false;
      }
      if (!this.div.find('#Sublet_deposit_amount').val() || isNaN(parseInt(this.div.find("#Sublet_deposit_amount").val())) || this.div.find('#Sublet_deposit_amount').val() < 0 || this.div.find('#Sublet_deposit_amount').val() >= 50000) {
        A2Cribs.UIManager.Error("Please enter a valid deposit amount.");
        this.div.find('#Sublet_deposit_amount').parent().parent().addClass("error");
        isValid = false;
      }
      descLength = this.div.find('#Sublet_additional_fees_description').val().length;
      if (descLength >= 161) {
        A2Cribs.UIManager.Error("Please keep the additional fees description under 160 characters.");
        this.div.find('#Sublet_additional_fees_description').parent().addClass("error");
        isValid = false;
      }
      if (descLength > 0) {
        if (!this.div.find('#Sublet_additional_fees_amount').val() || isNaN(parseInt(this.div.find("#Sublet_additional_fees_amount").val())) || this.div.find('#Sublet_additional_fees_amount').val() < 0 || this.div.find('#Sublet_additional_fees_amount').val() >= 50000) {
          A2Cribs.UIManager.Error("Please enter a valid additional fees amount.");
          this.div.find('#Sublet_additional_fees_amount').parent().addClass("error");
          isValid = false;
        }
      }
      if (this.div.find("#Sublet_furnished_type_id").val().length === 0) {
        A2Cribs.UIManager.Error("Please describe the situation with the furniture.");
        this.div.find('#Sublet_furnished_type_id').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find("#Sublet_utility_type_id").val().length === 0) {
        A2Cribs.UIManager.Error("Please describe the situation with the utilities.");
        this.div.find('#Sublet_utility_type_id').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find("#Sublet_parking").val().length === 0) {
        A2Cribs.UIManager.Error("Please describe the situation with parking.");
        this.div.find('#Sublet_parking').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find("#Sublet_ac").val().length === 0) {
        A2Cribs.UIManager.Error("Please describe the situation with parking.");
        this.div.find('#Sublet_ac').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find("#Sublet_bathroom_type_id").val().length === 0) {
        A2Cribs.UIManager.Error("Please describe the situation with your bathroom.");
        this.div.find('#Sublet_bathroom_type_id').parent().addClass("error");
        isValid = false;
      }
      return isValid;
    };

    SubletSave.prototype.ValidateStep3 = function() {
      var isValid;
      isValid = true;
      if (this.div.find('#Housemate_quantity').val().length === 0) {
        isValid = false;
      } else {
        if (+this.div.find('#Housemate_quantity').val() !== 0) {
          if (this.div.find('#Housemate_enrolled option:selected').text().length === 0) {
            isValid = false;
          } else if (+this.div.find('#Housemate_enrolled').val() === 1) {
            if (+this.div.find('#Housemate_student_type_id').val() === 0) {
              isValid = false;
            } else if (+this.div.find('#Housemate_student_type_id').val() !== 1) {
              if (+this.div.find('#Housemate_year').val() === 0) isValid = false;
            }
            if (+this.div.find('#Housemate_gender_type_id').val() === 0) {
              isValid = false;
            }
            if (this.div.find('#Housemate_major').val().length >= 255) {
              isValid = false;
            }
          }
        }
      }
      return isValid;
    };

    SubletSave.prototype.Reset = function() {
      this.ResetAllInputFields();
      return this.PhotoManager.Reset();
    };

    /*
    	Reset all input fields for a new sublet posting process
    */

    SubletSave.prototype.ResetAllInputFields = function() {
      this.div.find('input:text').val('');
      this.div.find('input:hidden').val('');
      this.div.find('select option:first-child').attr("selected", "selected");
      return this.div.find("#Sublet_payment_type_id").val("1");
    };

    /*
    	Submits sublet to backend to save
    	Assumes all front-end validations have been passed.
    */

    SubletSave.prototype.Save = function(subletObject, success) {
      var url,
        _this = this;
      if (success == null) success = null;
      url = "/sublets/ajax_submit_sublet";
      return $.post(url, subletObject, function(response) {
        var data;
        data = JSON.parse(response);
        console.log(data.status);
        if (data.redirect != null) window.location = data.redirect;
        if (data.status != null) {
          A2Cribs.UIManager.Success(data.status);
          A2Cribs.ShareManager.SavedListing = data.newid;
          if (success != null) return success(data.newid);
        } else {
          return A2Cribs.UIManager.Alert(data.error);
        }
      });
    };

    /*
    	Returns an object containing all sublet data from all 4 steps.
    */

    SubletSave.prototype.GetSubletObject = function() {
      var input, k, p, q, v, _ref;
      _ref = A2Cribs.SubletObject;
      for (k in _ref) {
        v = _ref[k];
        for (p in v) {
          q = v[p];
          console.log(k + "_" + p);
          A2Cribs.SubletObject[k][p] = 0;
          input = this.div.find("#" + k + "_" + p);
          if (input != null) {
            if ("checkbox" === input.attr("type")) {
              A2Cribs.SubletObject[k][p] = input.prop("checked");
            } else if (input.hasClass("date_field")) {
              A2Cribs.SubletObject[k][p] = this.GetMysqlDateFormat(input.val());
            } else {
              A2Cribs.SubletObject[k][p] = input.val();
            }
          }
        }
      }
      A2Cribs.SubletObject.Image = this.PhotoManager.GetPhotos();
      return A2Cribs.SubletObject;
    };

    /*
    	Replaces '/' with '-' to make convertible to mysql datetime format
    */

    SubletSave.prototype.GetMysqlDateFormat = function(dateString) {
      var beginDateFormatted, date, day, month, year;
      date = new Date(dateString);
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = year + "-" + month + "-" + day;
    };

    SubletSave.prototype.GetTodaysDate = function() {
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

    SubletSave.prototype.GetFormattedDate = function(date) {
      var beginDateFormatted, day, month, year;
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = month + "/" + day + "/" + year;
    };

    return SubletSave;

  })();

}).call(this);
