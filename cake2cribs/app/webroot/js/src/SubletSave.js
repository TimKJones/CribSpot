(function() {

  A2Cribs.SubletSave = (function() {

    function SubletSave() {}

    SubletSave.SetupUI = function() {};

    /*
    	Called before advancing steps
    	Returns true if validations pass; false otherwise
    */

    SubletSave.Validate = function(step_) {
      if (step_ >= 1) if (!this.ValidateStep1()) return false;
      if (step_ >= 2) if (!this.ValidateStep2()) return false;
      if (step_ >= 3) if (!this.ValidateStep3()) return false;
      if (step_ >= 4) if (!this.ValidateStep4()) return false;
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
      if (!$('#SubletNumberBedrooms').val() || $('#SubletNumberBedrooms').val() <= 0 || $('#SubletNumberBedrooms').val() >= 30) {
        A2Cribs.UIManager.Alert("Please enter a valid number of bedrooms.");
        return false;
      }
      if (!$('#SubletPricePerBedroom').val() || $('#SubletPricePerBedroom').val() < 1 || $('#SubletPricePerBedroom').val() >= 20000) {
        A2Cribs.UIManager.Alert("Please enter a valid price per bedroom.");
        return false;
      }
      if ($('#SubletDescription').val().length >= 161) {
        A2Cribs.UIManager.Alert("Please keep the short description under 160 characters.");
        return false;
      }
      if (!$('#SubletUtilityCost').val() || $('#SubletUtilityCost').val() < 0 || $('#SubletUtilityCost').val() >= 50000) {
        A2Cribs.UIManager.Alert("Please enter a valid utility cost.");
        return false;
      }
      if (!$('#SubletDepositAmount').val() || $('#SubletDepositAmount').val() < 0 || $('#SubletDepositAmount').val() >= 50000) {
        A2Cribs.UIManager.Alert("Please enter a valid deposit amount.");
        return false;
      }
      if ($('#SubletAdditionalFeesDescription').val().length >= 161) {
        A2Cribs.UIManager.Alert("Please keep the additional fees description under 160 characters.");
        return false;
      }
      if (!$('#SubletAdditionalFeesAmount').val() || $('#SubletAdditionalFeesAmount').val() < 0 || $('#SubletAdditionalFeesAmount').val() >= 50000) {
        A2Cribs.UIManager.Alert("Please enter a valid additional fees amount.");
        return false;
      }
      return true;
    };

    SubletSave.ValidateStep3 = function() {
      if (!$('#HousemateQuantity').val() || $('#HousemateQuantity').val() >= 50 || $('#HousemateQuantity').val() < 0) {
        A2Cribs.UIManager.Alert("Please enter a valid housemate quantity.");
        return false;
      } else if ($('#HousemateMajor').val().length >= 254) {
        A2Cribs.UIManager.Alert("Please keep the majors description under 255 characters.");
        return false;
      }
      return true;
    };

    SubletSave.ValidateStep4 = function() {};

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
          A2Cribs.Cache.SubletData = subletData;
          A2Cribs.SubletEdit.Init();
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

    return SubletSave;

  })();

}).call(this);
