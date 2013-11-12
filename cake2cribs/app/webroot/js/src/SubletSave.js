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
      this.MiniMap = new A2Cribs.MiniMap(this.div.find(".mini_map"));
      $(".sublet-content").on("shown", function() {
        return _this.MiniMap.Resize();
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
      this.div.find('.date-field').datepicker();
      $(".create-listing").find("a").click(function(event) {
        var listing_type;
        listing_type = $(event.currentTarget).data("listing-type");
        if (listing_type === "sublet") return _this.Open();
      });
      $("#sublet_list_content").on("marker_updated", function(event, marker_id) {
        return _this.PopulateMarker(A2Cribs.UserCache.Get("marker", marker_id));
      });
      return this.div.find(".photo_adder").click(function() {
        var image_array, listing_id, _ref;
        listing_id = _this.div.find(".listing_id").val();
        if ((listing_id != null ? listing_id.length : void 0) !== 0) {
          image_array = (_ref = A2Cribs.UserCache.Get("image", listing_id)) != null ? _ref.GetObject() : void 0;
        } else {
          image_array = _this._temp_images;
        }
        return A2Cribs.PhotoManager.Open(image_array, _this.PhotoAddedCallback);
      });
    };

    /*
    	Photo Added
    	When photos have been added, decides whether to cache if sublet
    	has been saved and save in temp_images
    */

    SubletSave.PhotoAddedCallback = function(photos) {
      var image, listing_id, _i, _len;
      listing_id = SubletSave.div.find(".listing_id").val();
      if ((listing_id != null ? listing_id.length : void 0) !== 0) {
        for (_i = 0, _len = photos.length; _i < _len; _i++) {
          image = photos[_i];
          image.listing_id = listing_id;
        }
        A2Cribs.UserCache.Set(new A2Cribs.Image(photos));
        SubletSave.Save();
      }
      return SubletSave._temp_images = photos;
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
        if (isValid && $(value).find(".active").size() === 0) {
          isValid = false;
          return A2Cribs.UIManager.Error($(value).data("error-message"));
        }
      });
      this.div.find(".text-field").each(function(index, value) {
        if (isValid && $(value).val().length === 0) {
          isValid = false;
          return A2Cribs.UIManager.Error($(value).data("error-message"));
        }
      });
      return isValid;
    };

    /*
    	Reset
    	Erases all the fields and resets
    	the Sublet window and sublet object
    */

    SubletSave.Reset = function() {
      this.div.find(".btn-group").each(function(index, value) {
        return $(value).find(".active").removeClass("active");
      });
      this.div.find("input").val("");
      return this.div.find("textarea").val("");
    };

    /*
    	Open
    	Opens up an existing sublet from a marker_id if marker_id
    	is defined. Otherwise will start a new sublet
    */

    SubletSave.Open = function(marker_id) {
      var listings,
        _this = this;
      if (marker_id == null) marker_id = null;
      if (marker_id != null) {
        listings = A2Cribs.UserCache.GetAllAssociatedObjects("listing", "marker", marker_id);
        this.div.find(".listing_id").val(listings[0].listing_id);
        A2Cribs.UserCache.GetListing("sublet", listings[0].listing_id).done(function(sublet) {
          _this.PopulateMarker(A2Cribs.UserCache.Get("marker", marker_id));
          return _this.Populate(sublet);
        });
      } else {
        this.Reset();
        this.MiniMap.Reset();
        this.div.find(".more_info").slideUp();
        this.div.find(".marker_card").fadeOut('fast', function() {
          return _this.div.find(".marker_searchbox").fadeIn();
        });
      }
      return A2Cribs.Dashboard.Direct({
        "classname": "sublet",
        "data": {}
      });
    };

    /*
    	Populate Marker
    	Populates the fields based on the marker
    */

    SubletSave.PopulateMarker = function(marker) {
      var _this = this;
      $(".location_fields").each(function(index, value) {
        var input_val;
        input_val = marker[$(value).data("field-name")];
        if (typeof marker[$(value).data("field-name")] === "boolean") {
          input_val = +input_val;
        }
        return $(value).val(input_val);
      });
      this.div.find(".marker_id").val(marker.GetId());
      this.MiniMap.SetMarkerPosition(new google.maps.LatLng(marker.latitude, marker.longitude));
      this.div.find(".building_name").text(marker.GetName());
      this.div.find(".building_type").text(marker.GetBuildingType());
      this.div.find(".full_address").html("<i class='icon-map-marker'></i> " + marker.street_address + ", " + marker.city + ", " + marker.state);
      return this.div.find(".marker_searchbox").fadeOut('fast', function() {
        _this.div.find(".marker_card").fadeIn();
        return _this.div.find(".more_info").slideDown();
      });
    };

    /*
    	Populate
    	Populates the sublet fields in the dom
    */

    SubletSave.Populate = function(sublet_object) {
      var _this = this;
      this.Reset();
      return $(".sublet_fields").each(function(index, value) {
        var input_val;
        input_val = sublet_object[$(value).data("field-name")];
        if (typeof sublet_object[$(value).data("field-name")] === "boolean") {
          input_val = +input_val;
        }
        $(value).val(input_val);
        if ($(value).hasClass("btn-group")) {
          return $(value).find("button[value='" + input_val + "']").addClass("active");
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
      var sublet_object,
        _this = this;
      if (this.Validate()) {
        sublet_object = this.GetSubletObject();
        return $.ajax({
          url: myBaseUrl + "listings/Save/",
          type: "POST",
          data: sublet_object,
          success: function(response) {
            var _ref;
            response = JSON.parse(response);
            if (((_ref = response.error) != null ? _ref.message : void 0) != null) {
              return A2Cribs.UIManager.Error(response.error.message);
            } else {
              if (!(sublet_object.Listing.listing_id != null)) {
                $('#sublet_list_content').trigger("marker_added", [sublet_object.Listing.marker_id]);
              }
              A2Cribs.UserCache.CacheData(response.listing);
              return A2Cribs.UIManager.Success("Your listing has been saved!");
            }
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
      var listing_id, sublet_object,
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
      listing_id = this.div.find(".listing_id").val().length !== 0 ? this.div.find(".listing_id").val() : void 0;
      sublet_object.listing_id = listing_id;
      return {
        'Listing': {
          listing_type: 1,
          marker_id: this.div.find(".marker_id").val(),
          listing_id: listing_id
        },
        'Sublet': sublet_object,
        'Image': this._temp_images
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
        return _this.FindMarkerByAddress(street_address, city, state).done(function(marker) {
          return _this.PopulateMarker(marker);
        }).fail(function() {
          return A2Cribs.MarkerModal.OpenLocation('sublet', street_address, city, state);
        });
      }).fail(function() {
        return A2Cribs.MarkerModal.OpenLocation('sublet', location_object.street_address, location_object.city, location_object.state);
      });
    };

    SubletSave.FindMarkerByAddress = function(street_address, city, state) {
      var deferred,
        _this = this;
      deferred = new $.Deferred();
      $.ajax({
        url: myBaseUrl + "Markers/FindMarkerByAddress/" + street_address + "/" + city + "/" + state,
        type: "GET",
        success: function(response) {
          var marker;
          response = JSON.parse(response);
          if (response != null) {
            marker = new A2Cribs.Marker(response);
            A2Cribs.UserCache.Set(marker);
            return deferred.resolve(marker);
          } else {
            return deferred.reject();
          }
        }
      });
      return deferred.promise();
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
    	Get Formatted Date
    	Returns date in readable front-end syntax
    */

    SubletSave.GetFormattedDate = function(dateString) {
      var date_array;
      date_array = dateString.split(" ");
      date_array = date_array[0].split("-");
      return "" + date_array[1] + "/" + date_array[2] + "/" + date_array[0];
    };

    $("#sublet_window").ready(function() {
      SubletSave._temp_images = [];
      return SubletSave.SetupUI($("#sublet_window"));
    });

    return SubletSave;

  }).call(this);

}).call(this);
