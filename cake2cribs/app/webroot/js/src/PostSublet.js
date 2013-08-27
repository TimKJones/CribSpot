// Generated by CoffeeScript 1.4.0
(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  A2Cribs.PostSublet = (function(_super) {

    __extends(PostSublet, _super);

    function PostSublet() {
      this.div = $('#post-sublet-modal');
      this.currentStep = 0;
      /* INIT STEPS
      */

      this.setupUI();
    }

    PostSublet.prototype.setupUI = function() {
      var _this = this;
      this.ProgressBar = new A2Cribs.PostSubletProgress($('.post-sublet-progress'), this.currentStep);
      this.div.find("#address-step").siblings().hide();
      this.div.find(".next-btn").click(function(event) {
        if (_this.Validate(_this.currentStep + 1)) {
          $(event.currentTarget).closest(".step").hide().next(".step").show();
          _this.currentStep++;
          return _this.ProgressBar.next();
        }
      });
      this.div.find(".back-btn").click(function(event) {
        $(event.currentTarget).closest(".step").hide().prev(".step").show();
        _this.currentStep--;
        return _this.ProgressBar.prev();
      });
      this.div.on("shown", function() {
        return _this.MiniMap.Resize();
      });
      this.div.find("#University_name").focusout(function() {
        _this.FindSelectedUniversity(_this.div);
        if (_this.SelectedUniversity != null) {
          return _this.MiniMap.CenterMap(_this.SelectedUniversity.latitude, _this.SelectedUniversity.longitude);
        }
      });
      this.div.find(".post-btn").click(function() {
        return _this.Save();
      });
      this.InitUniversityAutocomplete();
      return PostSublet.__super__.setupUI.call(this, this.div);
    };

    PostSublet.prototype.Reset = function() {
      this.ProgressBar.reset();
      this.div.find('.step').eq(0).show();
      this.div.find('.step').eq(0).siblings().hide();
      this.currentStep = 0;
      return PostSublet.__super__.Reset.call(this, this.div);
    };

    PostSublet.prototype.Save = function() {
      if (this.Validate()) {
        return PostSublet.__super__.Save.call(this, this.GetSubletObject(), this.SaveRedirect);
      }
    };

    PostSublet.prototype.SaveRedirect = function(new_id) {
      return window.location.replace("/sublet/" + new_id);
    };

    PostSublet.prototype.Validate = function(step_) {
      if (step_ == null) {
        step_ = 3;
      }
      return PostSublet.__super__.Validate.call(this, step_, this.div);
    };

    PostSublet.prototype.GetSubletObject = function() {
      return PostSublet.__super__.GetSubletObject.call(this, this.div);
    };

    PostSublet.prototype.InitUniversityAutocomplete = function() {
      var _this = this;
      if (A2Cribs.Cache.SchoolList != null) {
        this.div.find("#University_name").typeahead({
          source: A2Cribs.Cache.SchoolList
        });
        return;
      }
      return $.ajax({
        url: "/University/getAll",
        success: function(response) {
          var university, _i, _len, _ref;
          A2Cribs.Cache.universitiesMap = JSON.parse(response);
          A2Cribs.Cache.SchoolList = [];
          A2Cribs.Cache.SchoolIDList = [];
          _ref = A2Cribs.Cache.universitiesMap;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            university = _ref[_i];
            A2Cribs.Cache.SchoolList.push(university.University.name);
            A2Cribs.Cache.SchoolIDList.push(university.University.id);
          }
          return _this.div.find("#University_name").typeahead({
            source: A2Cribs.Cache.SchoolList
          });
        }
      });
    };

    PostSublet.prototype.FindAddress = function() {
      var address, addressObj,
        _this = this;
      if (this.SelectedUniversity != null) {
        address = this.div.find("#Marker_street_address").val();
        addressObj = {
          'address': address + " " + this.SelectedUniversity.city + ", " + this.SelectedUniversity.state
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
                    _this.div.find('#Marker_city').val(component.short_name);
                    break;
                  case "administrative_area_level_1":
                    _this.div.find('#Marker_state').val(component.short_name);
                    break;
                  case "postal_code":
                    _this.div.find('#Marker_zip').val(component.short_name);
                }
              }
            }
            if (!(street_number != null)) {
              A2Cribs.UIManager.Alert("Entered street address is not valid.");
              $("#Marker_street_address").text("");
              return;
            }
            _this.MiniMap.SetMarkerPosition(response[0].geometry.location);
            _this.div.find("#Marker_street_address").val(street_number + " " + street_name);
            _this.div.find("#Marker_latitude").val(response[0].geometry.location.lat());
            return _this.div.find("#Marker_longitude").val(response[0].geometry.location.lng());
          }
        });
      }
    };

    PostSublet.prototype.FindSelectedUniversity = function() {
      var index, selected;
      selected = this.div.find("#University_name").val();
      index = A2Cribs.Cache.SchoolList.indexOf(selected);
      if (index >= 0) {
        this.SelectedUniversity = A2Cribs.Cache.universitiesMap[index].University;
        return this.div.find("#Sublet_university_id").val(A2Cribs.Cache.SchoolIDList[index]);
      } else {
        return this.SelectedUniversity = null;
      }
    };

    return PostSublet;

  })(A2Cribs.SubletSave);

}).call(this);
