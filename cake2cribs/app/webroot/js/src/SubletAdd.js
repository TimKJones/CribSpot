(function() {

  A2Cribs.SubletAdd = (function() {

    function SubletAdd() {}

    SubletAdd.setupUI = function() {
      var oldBeginDate, oldEndDate,
        _this = this;
      $('#goToStep2').click(function(e) {
        if (!$('#formattedAddress').val()) {
          return A2Cribs.UIManager.Alert("Please place your street address on the map using the Place On Map button.");
        } else if (!$('#universityName').val()) {
          return A2Cribs.UIManager.Alert("You need to select a university.");
        } else if ($('#SubletUnitNumber').val().length >= 249) {
          return A2Cribs.UIManager.Alert("Your unit number is too long.");
        } else if ($('#SubletName').val().length >= 249) {
          return A2Cribs.UIManager.Alert("Your alternate name is too long.");
        } else {
          return _this.subletAddStep1();
        }
      });
      $("#backToStep2").click(function(e) {
        return _this.backToStep2();
      });
      $("#backToStep3").click(function(e) {
        return _this.backToStep3();
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
          return A2Cribs.UIManager.Alert("Please enter a valid date.");
        } else if (parsedEndDate.valueOf() <= parsedBeginDate.valueOf() && parsedBeginDate.valueOf() <= todayDate.valueOf()) {
          return A2Cribs.UIManager.Alert("Please enter a valid date.");
        } else if (!$('#SubletNumberBedrooms').val() || $('#SubletNumberBedrooms').val() <= 0 || $('#SubletNumberBedrooms').val() >= 30) {
          return A2Cribs.UIManager.Alert("Please enter a valid number of bedrooms.");
        } else if (!$('#SubletPricePerBedroom').val() || $('#SubletPricePerBedroom').val() < 0 || $('#SubletPricePerBedroom').val() >= 20000) {
          return A2Cribs.UIManager.Alert("Please enter a valid price per bedroom.");
        } else if ($('#SubletDescription').val().length >= 161) {
          return A2Cribs.UIManager.Alert("Please keep the short description under 160 characters.");
        } else if (!$('#SubletUtilityCost').val() || $('#SubletUtilityCost').val() < 0 || $('#SubletUtilityCost').val() >= 50000) {
          return A2Cribs.UIManager.Alert("Please enter a valid utility cost.");
        } else if (!$('#SubletDepositAmount').val() || $('#SubletDepositAmount').val() < 0 || $('#SubletDepositAmount').val() >= 50000) {
          return A2Cribs.UIManager.Alert("Please enter a valid deposit amount.");
        } else if ($('#SubletAdditionalFeesDescription').val().length >= 161) {
          return A2Cribs.UIManager.Alert("Please keep the additional fees description under 160 characters.");
        } else if (!$('#SubletAdditionalFeesAmount').val() || $('#SubletAdditionalFeesAmount').val() < 0 || $('#SubletAdditionalFeesAmount').val() >= 50000) {
          return A2Cribs.UIManager.Alert("Please enter a valid additional fees amount.");
        } else {
          return A2Cribs.SubletAdd.subletAddStep2();
        }
      });
      $('#goToStep4').click(function(e) {
        if (!$('#HousemateQuantity').val() || $('#HousemateQuantity').val() >= 50 || $('#HousemateQuantity').val() < 0) {
          return A2Cribs.UIManager.Alert("Please enter a valid housemate quantity.");
        } else if ($('#HousemateMajor').val().length >= 254) {
          return A2Cribs.UIManager.Alert("Please keep the majors description under 255 characters.");
        } else {
          A2Cribs.SubletEdit.CacheStep3Data();
          e.preventDefault();
          return _this.subletAddStep3();
        }
      });
      $('#goToStep5').click(function(e) {
        return _this.subletAddStep4();
      });
      $("#finishShare").click(function(e) {
        return $('#server-notice').dialog2("close");
      });
      oldBeginDate = new Date($('#SubletDateBegin').val());
      $('#SubletDateBegin').val(oldBeginDate.toDateString());
      oldEndDate = new Date($('#SubletDateEnd').val());
      return $('#SubletDateEnd').val(oldEndDate.toDateString());
    };

    SubletAdd.InitPostingProcess = function(e) {
      if (e == null) e = null;
      A2Cribs.Cache.SubletEditInProgress = new A2Cribs.SubletInProgress();
      this.OpenStep1();
      if (e !== null) return e.preventDefault();
    };

    SubletAdd.backToStep1 = function() {
      return this.OpenStep1();
    };

    SubletAdd.backToStep2 = function() {
      return this.OpenStep2();
    };

    SubletAdd.backToStep3 = function() {
      return this.OpenStep3();
    };

    SubletAdd.subletAddStep1 = function() {
      this.OpenStep2();
      A2Cribs.SubletEdit.CacheStep1Data();
      return this.OpenStep2();
    };

    SubletAdd.subletAddStep2 = function() {
      this.OpenStep3();
      A2Cribs.SubletEdit.CacheStep2Data();
      return this.OpenStep3();
    };

    SubletAdd.subletAddStep3 = function() {
      var url,
        _this = this;
      url = "/sublets/ajax_submit_sublet";
      return $.post(url, A2Cribs.Cache.SubletEditInProgress, function(response) {
        var data;
        data = JSON.parse(response);
        console.log(data.status);
        if (data.status) {
          A2Cribs.ShareManager.SavedListing = data.newid;
          _this.OpenStep4();
          return A2Cribs.PhotoManager.LoadImages(A2Cribs.ShareManager.SavedListing);
        } else {
          A2Cribs.UIManager.Alert(data.error);
          return $('#server-notice').dialog2("close");
        }
      });
    };

    SubletAdd.subletAddStep4 = function() {
      return this.OpenStep5();
    };

    SubletAdd.DisableMarkerFields = function() {
      $("#addressToMark").attr('disabled', 'disabled');
      return A2Cribs.CorrectMarker.Disable();
    };

    SubletAdd.GetFormattedDate = function(date) {
      var beginDateFormatted, day, month, year;
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = month + "/" + day + "/" + year;
    };

    /*
    	For all open() functions, re-use existing modal if it has been created. Otherwise, create and save it.
    */

    SubletAdd.OpenStep1 = function() {
      if (A2Cribs.Cache.Step1Modal !== void 0 && A2Cribs.Cache.Step1Modal !== null) {
        A2Cribs.Cache.Step1Modal.dialog2('open');
        return alert("already exists");
      } else {
        return A2Cribs.Cache.Step1Modal = $("<div/>").dialog2({
          title: "Post a sublet",
          content: "/Sublets/ajax_add",
          id: "server-notice"
        });
      }
    };

    SubletAdd.OpenStep2 = function() {
      if (A2Cribs.Cache.Step2Modal !== void 0 && A2Cribs.Cache.Step2Modal !== null) {
        return A2Cribs.Cache.Step2Modal.dialog2('open');
      } else {
        return A2Cribs.Cache.Step2Modal = $('#server-notice').dialog2("options", {
          content: "/Sublets/ajax_add2",
          removeOnClose: false
        });
      }
    };

    SubletAdd.OpenStep3 = function() {
      if (A2Cribs.Cache.Step3Modal !== void 0 && A2Cribs.Cache.Step3Modal !== null) {
        return A2Cribs.Cache.Step3Modal.dialog2('open');
      } else {
        return A2Cribs.SubletAdd.Step3Modal = $('#server-notice').dialog2("options", {
          content: "/Sublets/ajax_add3",
          removeOnClose: false
        });
      }
    };

    SubletAdd.OpenStep4 = function() {
      if (A2Cribs.Cache.Step4Modal !== void 0 && A2Cribs.Cache.Step4Modal !== null) {
        return A2Cribs.Cache.Step4Modal.dialog2('open');
      } else {
        return A2Cribs.SubletAdd.Step4Modal = $('#server-notice').dialog2("options", {
          content: "/Sublets/ajax_add4",
          removeOnClose: false
        });
      }
    };

    SubletAdd.OpenStep5 = function() {
      if (A2Cribs.Cache.Step5Modal !== void 0 && A2Cribs.Cache.Step5Modal !== null) {
        return A2Cribs.Cache.Step5Modal.dialog2('open');
      } else {
        return A2Cribs.SubletAdd.Step5Modal = $('#server-notice').dialog2("options", {
          content: "/Sublets/ajax_add5",
          removeOnClose: false
        });
      }
    };

    SubletAdd.ClosePreviousModal = function() {};

    return SubletAdd;

  })();

}).call(this);
