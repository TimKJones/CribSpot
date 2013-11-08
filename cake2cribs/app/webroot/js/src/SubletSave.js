(function() {
  var SubletSave;

  SubletSave = (function() {
    var _this = this;

    function SubletSave() {}

    /*
    	Setup UI
    	Creates the listeners and all the UI for the
    	Sublet window
    */

    SubletSave.SetupUI = function(div) {
      var _this = this;
      this.div = div;
      $('#sublet_list_content').on("marker_added", function(event, marker_id) {
        return _this.Open(marker_id);
      });
      $('#sublet_list_content').on('click', '.sublet_list_item', function(event) {
        return _this.Open(event.currentTarget.id);
      });
      this.div.find("#sublet_save_button").click(function() {
        return _this.Save();
      });
      this.div.find(".btn-group.sublet_fields .btn").click(function(event) {
        return $(event.currentTarget).parent().val($(event.currentTarget).val());
      });
      this.div.find("#find_address").click(function() {
        return _this.FindAddress();
      });
      return this.div.find('.date-field').datepicker();
    };

    /*
    	Validate
    	Called before advancing steps
    	Returns true if validations pass; false otherwise
    */

    SubletSave.Validate = function() {
      var isValid;
      isValid = true;
      this.div.find(".btn-group").each(function(index, value) {
        if ($(value).find(".active").size() === 0) return isValid = false;
      });
      this.div.find(".date-field").each(function(index, value) {
        if ($(value).val().length === 0) return isValid = false;
      });
      return isValid;
    };

    /*
    	Reset
    	Erases all the fields and resets
    	the Sublet window and sublet object
    */

    SubletSave.Reset = function() {
      return this.div.find(".btn-group").each(function(index, value) {
        return $(value).find(".active").removeClass("active");
      });
    };

    /*
    	Open
    	Opens up an existing sublet from a marker_id
    */

    SubletSave.Open = function(marker_id) {
      var sublets;
      sublets = A2Cribs.UserCache.GetAllAssociatedObjects("sublet", "marker", marker_id);
      if (sublets.length !== 0) {
        this.Populate(sublets[0]);
      } else {
        this.Reset();
      }
      return this.div.show();
    };

    /*
    */

    SubletSave.Populate = function(sublet_object) {
      var _this = this;
      return $(".sublet_fields").each(function(index, value) {
        var lol;
        $(value).val(sublet_object[$(value).data("field-name")]);
        if ($(value).hasClass("btn-group")) {
          return lol = "lol";
        } else if ($(value).hasClass("date-field")) {
          return $(value).val(_this.GetFormattedDate(sublet_object[$(value).data("field-name")]));
        }
      });
    };

    /*
    	Save
    	Submits sublet to backend to save
    	Assumes all front-end validations have been passed.
    */

    SubletSave.Save = function() {
      var _this = this;
      if (this.Validate()) {
        return $.ajax({
          url: myBaseUrl + "listings/Save/",
          type: "POST",
          data: this.GetSubletObject(),
          success: function(response) {
            return console.log(response);
          }
        });
      } else {
        return new $.Deferred().reject();
      }
    };

    /*
    	GetSubletObject
    	Returns an object containing all sublet data from all 4 steps.
    */

    SubletSave.GetSubletObject = function() {
      var sublet_object,
        _this = this;
      sublet_object = {};
      this.div.find(".sublet_fields").each(function(index, value) {
        var field_value;
        field_value = $(value).val();
        if ($(value).hasClass("date-field")) {
          field_value = _this.GetBackendDateFormat(field_value);
        }
        return sublet_object[$(value).data("field-name")] = field_value;
      });
      return {
        'Listing': {
          listing_type: 1,
          marker_id: this.div.find(".marker_id").val()
        },
        'Sublet': sublet_object,
        'Image': []
      };
    };

    /*
    	Find Address
    	Finds the geocode address and searches the backend
    	for the correct address
    */

    SubletSave.FindAddress = function() {
      var isValid, location_object,
        _this = this;
      location_object = {};
      isValid = true;
      $(".location_fields").each(function(index, value) {
        if ($(value).val().length === 0) isValid = false;
        return location_object[$(value).data("field-name")] = $(value).val();
      });
      if (!isValid) {
        A2Cribs.UIManager.Error("Please complete all fields to find address");
        return;
      }
      return A2Cribs.Geocoder.FindAddress(location_object.street_address, location_object.city, location_object.state).done(function(response) {
        var city, location, state, street_address, zip;
        street_address = response[0], city = response[1], state = response[2], zip = response[3], location = response[4];
        return _this.div.find(".marker_id").val("1");
      });
    };

    SubletSave.FindMarkerTest = function() {
      var city, state, street_address,
        _this = this;
      street_address = '114 N Division St';
      city = 'Ann Arbor';
      state = 'MI';
      return $.ajax({
        url: myBaseUrl + "Markers/FindMarkerByAddress/" + street_address + "/" + city + "/" + state,
        type: "GET",
        success: function(response) {
          return console.log(response);
        }
      });
    };

    /*
    	Get Backend Date Format
    	Replaces '/' with '-' to make convertible to db format
    */

    SubletSave.GetBackendDateFormat = function(dateString) {
      var beginDateFormatted, date, day, month, year;
      date = new Date(dateString);
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = year + "-" + month + "-" + day;
    };

    /*
    	Get Today's Date
    	Returns todays date in readable front-end syntax
    */

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

    /*
    	Get Formatted Date
    	Returns date in readable front-end syntax
    */

    SubletSave.GetFormattedDate = function(date) {
      var beginDateFormatted, day, month, year;
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = month + "/" + day + "/" + year;
    };

    $("#sublet_window").ready(function() {
      return SubletSave.SetupUI($("#sublet_window"));
    });

    return SubletSave;

  }).call(this);

}).call(this);
