// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.MarkerModal = (function() {
    var _this = this;

    function MarkerModal() {}

    /*
    	Clear
    	Removes all the values in input fields and resets
    	to the first part of selecting a marker
    */


    MarkerModal.Clear = function() {
      this.modal.find("#marker_select_container").show();
      this.modal.find("input").val("");
      this.modal.find('select option:first-child').attr("selected", "selected");
      return this.MiniMap.SetMarkerVisible(false);
    };

    /*
    	Marker Validate
    	Iterates through the address fields and makes sure everything
    	is completed and checks to make sure the text fields are not
    	too long
    */


    MarkerModal.MarkerValidate = function() {
      var addressFields, addressOK, field, isValid, _i, _len;
      isValid = true;
      addressFields = ["street_address", "city", "state"];
      addressOK = true;
      for (_i = 0, _len = addressFields.length; _i < _len; _i++) {
        field = addressFields[_i];
        if (!(this.modal.find("#Marker_" + field).val() != null) || this.modal.find("#Marker_" + field).val().length === 0) {
          this.modal.find("#Marker_" + field).parent().addClass("error");
          addressOK = false;
        }
      }
      if (!addressOK) {
        A2Cribs.UIManager.Error("Fill in the full address please.");
        isValid = false;
      }
      if (this.modal.find('#Marker_building_type_id').val().length === 0) {
        A2Cribs.UIManager.Error("You need to select a building type.");
        this.modal.find('#Marker_building_type_id').parent().addClass("error");
        isValid = false;
      }
      if (this.modal.find('#Marker_alternate_name').val().length >= 249) {
        A2Cribs.UIManager.Error("Your building name is too long.");
        this.modal.find('#Marker_alternate_name').parent().addClass("error");
        isValid = false;
      }
      return isValid;
    };

    /*
    */


    MarkerModal.Save = function(trigger) {
      var latLng, marker_id, marker_object,
        _this = this;
      if (this.MarkerValidate()) {
        if (!this.modal.find('#Marker_latitude').val()) {
          A2Cribs.UIManager.Error("Please place your street address on the map using the Place On Map button.");
          return;
        }
        marker_id = this.modal.find("#Marker_marker_id").val();
        latLng = this.MiniMap.GetMarkerPosition();
        marker_object = {
          alternate_name: this.modal.find('#Marker_alternate_name').val(),
          building_type_id: this.modal.find('#Marker_building_type_id').val(),
          street_address: this.modal.find('#Marker_street_address').val(),
          city: this.modal.find('#Marker_city').val(),
          state: this.modal.find('#Marker_state').val(),
          zip: this.modal.find('#Marker_zip').val(),
          latitude: latLng['latitude'],
          longitude: latLng['longitude']
        };
        A2Cribs.MixPanel.PostListing("Marker Save", marker_object);
        if ((marker_id != null ? marker_id.length : void 0) !== 0) {
          marker_object.marker_id = marker_id;
        }
        return $.ajax({
          url: "/Markers/Save/",
          type: "POST",
          data: marker_object,
          success: function(response) {
            if (response.error) {
              return UIManager.Error(response.error);
            } else {
              _this.modal.modal("hide");
              marker_object.marker_id = response;
              A2Cribs.MixPanel.PostListing("Marker Save Complete", {
                "marker id": marker_object.marker_id
              });
              A2Cribs.UserCache.Set(new A2Cribs.Marker(marker_object));
              return trigger(marker_object.marker_id);
            }
          }
        });
      }
    };

    MarkerModal.SetupUI = function() {
      var _this = this;
      this.modal.on('shown', function() {
        return _this.MiniMap.Resize();
      });
      this.modal.find(".required").keydown(function() {
        return $(this).parent().removeClass("error");
      });
      this.modal.find("#place_map_button").click(function() {
        var marker_selected;
        marker_selected = _this.modal.find("#marker_select").val();
        A2Cribs.MixPanel.PostListing("Marker Selected", {
          "new marker": false,
          "marker_id": marker_selected
        });
        return _this.FindAddress(_this.modal);
      });
      this.modal.find("#marker_select").change(function() {
        var marker_selected;
        marker_selected = _this.modal.find("#marker_select").val();
        if (marker_selected === "0") {
          _this.modal.find("#continue-button").addClass("disabled");
        } else {
          _this.modal.find("#continue-button").removeClass("disabled");
        }
        if (marker_selected === "new_marker") {
          _this.modal.find('#marker_add').show();
          return _this.MiniMap.Resize();
        } else {
          return _this.modal.find('#marker_add').hide();
        }
      });
      this.modal.find("#continue-button").click(function() {
        var marker, marker_selected;
        marker_selected = _this.modal.find("#marker_select").val();
        if (marker_selected === "new_marker") {
          A2Cribs.MixPanel.PostListing("Marker Selected", {
            "new marker": true
          });
          return _this.Save();
        } else if (marker_selected !== "0") {
          marker = A2Cribs.UserCache.Get("marker", marker_selected);
          A2Cribs.MixPanel.PostListing("Marker Selected", {
            "new marker": false,
            "marker id": marker_selected,
            "marker name": marker != null ? marker.GetName() : void 0,
            "marker address": marker != null ? marker.street_address : void 0,
            "marker city": marker != null ? marker.city : void 0,
            "marker state": marker != null ? marker.state : void 0
          });
          _this.modal.modal("hide");
          return _this.TriggerMarkerAdded(marker_selected);
        }
      });
      return this.MiniMap = new A2Cribs.MiniMap(this.modal);
    };

    MarkerModal.Open = function(listing_type, marker_id) {
      if (marker_id == null) {
        marker_id = null;
      }
      if (listing_type != null) {
        this.ListingType = listing_type;
      }
      if (!(marker_id != null)) {
        this.NewMarker();
      }
      return this.modal.modal('show');
    };

    MarkerModal.NewMarker = function() {
      var marker, markers, name, option, _i, _len,
        _this = this;
      this.Clear();
      this.modal.find('#marker_add').hide();
      this.modal.find("#continue-button").addClass("disabled").text("Continue");
      this.modal.find(".title").text("Create a New " + (this.ListingType.charAt(0).toUpperCase() + this.ListingType.slice(1)));
      markers = A2Cribs.UserCache.Get("marker");
      this.modal.find("#marker_select").empty().append('<option value="0">--</option>\
			<option value="new_marker"><strong>New Location</strong></option>');
      this.modal.find("#continue-button").unbind('click').click(function() {
        var marker_selected;
        marker_selected = _this.modal.find("#marker_select").val();
        if (marker_selected === "new_marker") {
          return _this.Save(_this.TriggerMarkerAdded);
        } else if (marker_selected !== "0") {
          _this.modal.modal("hide");
          return _this.TriggerMarkerAdded(marker_selected);
        }
      });
      if (markers != null) {
        for (_i = 0, _len = markers.length; _i < _len; _i++) {
          marker = markers[_i];
          name = (marker.alternate_name != null) && marker.alternate_name.length ? marker.alternate_name : marker.street_address;
          option = $("<option />", {
            text: name,
            value: marker.marker_id
          });
          this.modal.find("#marker_select").append(option);
        }
      }
      return this.modal.find("#marker_select").val("0");
    };

    MarkerModal.LoadMarker = function(marker_id) {
      var key, latLng, marker, value,
        _this = this;
      this.Clear();
      this.modal.find('#marker_add').show();
      this.modal.find("#marker_select_container").hide();
      marker = A2Cribs.UserCache.Get("marker", marker_id);
      this.modal.find("#continue-button").removeClass("disabled");
      this.modal.find("#continue-button").text("Save");
      this.modal.find(".title").text("Edit Listing Address");
      this.modal.find("#marker_select").val("new_marker");
      for (key in marker) {
        value = marker[key];
        this.modal.find("#Marker_" + key).val(value);
      }
      this.modal.find("#continue-button").unbind('click');
      this.modal.find("#continue-button").click(function() {
        return _this.Save(_this.TriggerMarkerUpdated);
      });
      latLng = new google.maps.LatLng(this.modal.find("#Marker_latitude").val(), this.modal.find("#Marker_longitude").val());
      return this.MiniMap.SetMarkerPosition(latLng);
    };

    MarkerModal.TriggerMarkerAdded = function(marker_id) {
      return $("#" + MarkerModal.ListingType + "_list_content").trigger("marker_added", [marker_id]);
    };

    MarkerModal.TriggerMarkerUpdated = function(marker_id) {
      return $('body').trigger("" + MarkerModal.ListingType + "_marker_updated", [marker_id]);
    };

    MarkerModal.FindAddress = function(div) {
      var addressObj,
        _this = this;
      if (this.MarkerValidate()) {
        addressObj = {
          address: div.find("#Marker_street_address").val() + " " + div.find("#Marker_city").val() + ", " + div.find("#Marker_state").val()
        };
        return A2Cribs.Geocoder.geocode(addressObj, function(response, status) {
          var component, street_name, street_number, type, _i, _j, _len, _len1, _ref, _ref1;
          if (status === google.maps.GeocoderStatus.OK && response[0].address_components.length >= 2) {
            _ref = response[0].address_components;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              component = _ref[_i];
              _ref1 = component.types;
              for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
                type = _ref1[_j];
                switch (type) {
                  case "street_number":
                    street_number = component.short_name;
                    break;
                  case "route":
                    street_name = component.short_name;
                    break;
                  case "locality":
                    div.find('#Marker_city').val(component.short_name);
                    break;
                  case "administrative_area_level_1":
                    div.find('#Marker_state').val(component.short_name);
                    break;
                  case "postal_code":
                    div.find('#Marker_zip').val(component.short_name);
                }
              }
            }
            if (!(street_number != null)) {
              A2Cribs.UIManager.Alert("Entered street address is not valid.");
              $("#Marker_street_address").text("");
              return;
            }
            _this.MiniMap.SetMarkerPosition(response[0].geometry.location);
            div.find("#Marker_street_address").val(street_number + " " + street_name);
            div.find("#Marker_latitude").val(response[0].geometry.location.lat());
            return div.find("#Marker_longitude").val(response[0].geometry.location.lng());
          }
        });
      }
    };

    $('#marker-modal').ready(function() {
      MarkerModal.modal = $('#marker-modal');
      return MarkerModal.SetupUI();
    });

    return MarkerModal;

  }).call(this);

}).call(this);
