(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  A2Cribs.EditSublet = (function(_super) {

    __extends(EditSublet, _super);

    function EditSublet() {
      this.div = $('#edit_sublet_window');
      this.setupUI();
    }

    EditSublet.prototype.setupUI = function() {
      var _this = this;
      this.div.find(".step-button").click(function(event) {
        _this.div.find(".step-button").removeClass("active");
        $(event.currentTarget).closest(".step-button").addClass("active");
        return _this.GotoStep($(event.currentTarget).closest(".step-button").attr("step"));
      });
      return EditSublet.__super__.setupUI.call(this, this.div);
    };

    EditSublet.prototype.Reset = function() {
      this.div.find('.step').eq(0).show().siblings().hide();
      this.div.find(".step-button").removeClass("active");
      this.div.find('.step-button').eq(0).addClass("active");
      return EditSublet.__super__.Reset.call(this, this.div);
    };

    EditSublet.prototype.Edit = function(sublet_id) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + "Sublets/getSubletDataById/" + sublet_id,
        type: "GET",
        success: function(subletData) {
          _this.Close();
          subletData = JSON.parse(subletData);
          if (subletData.redirect != null) window.location = subletData.redirect;
          _this.MiniMap.SetMarkerPosition(new google.maps.LatLng(subletData.Marker.latitude, subletData.Marker.longitude));
          _this.PopulateInputFields(subletData);
          _this.PhotoManager.LoadImages(subletData.Image);
          _this.DisableInputFields();
          return _this.Open();
        },
        error: function() {
          return A2Cribs.UIManager.Alert("An error occured while loading your sublet data, please try again.");
        }
      });
    };

    EditSublet.prototype.Save = function() {
      if (this.Validate()) {
        return EditSublet.__super__.Save.call(this, this.GetSubletObject());
      }
    };

    EditSublet.prototype.Delete = function(sublet_id) {
      return alertify.confirm("Are you sure you want to delete this property? This can't be undone.", function(e) {
        var url;
        if (e) {
          url = myBaseUrl + ("sublets/remove/" + sublet_id);
          return window.location.href = url;
        }
      });
    };

    EditSublet.prototype.GetSubletObject = function() {
      return EditSublet.__super__.GetSubletObject.call(this, this.div);
    };

    EditSublet.prototype.Close = function() {
      this.Reset();
      return this.div.parent().hide();
    };

    EditSublet.prototype.Open = function() {
      var _this = this;
      return this.div.parent().show('slow', function() {
        return _this.MiniMap.Resize();
      });
    };

    EditSublet.prototype.PopulateInputFields = function(subletData) {
      var input, k, p, q, v, _results;
      _results = [];
      for (k in subletData) {
        v = subletData[k];
        _results.push((function() {
          var _results2;
          _results2 = [];
          for (p in v) {
            q = v[p];
            console.log(k + "_" + p);
            input = this.div.find("#" + k + "_" + p);
            if (input != null) {
              if ("checkbox" === input.attr("type")) {
                input.prop("checked", q);
              } else if (input.hasClass("date_field")) {
                input.val(this.GetFormattedDate(new Date(q)));
              } else if (typeof q === 'boolean') {
                input.val(+q);
              } else {
                input.val(q);
              }
              if (k === "Marker") {
                _results2.push(input.prop('disabled', true));
              } else {
                _results2.push(void 0);
              }
            } else {
              _results2.push(void 0);
            }
          }
          return _results2;
        }).call(this));
      }
      return _results;
    };

    EditSublet.prototype.DisableInputFields = function() {
      this.MiniMap.SetEnabled(false);
      this.div.find('#place_map_button').prop('disabled', true);
      return this.div.find('#University_name').prop('disabled', true);
    };

    EditSublet.prototype.GotoStep = function(step) {
      if (this.Validate()) {
        return this.div.find('.step').eq(step).show().siblings().hide();
      }
    };

    EditSublet.prototype.Validate = function() {
      return EditSublet.__super__.Validate.call(this, 3);
    };

    return EditSublet;

  })(A2Cribs.SubletSave);

}).call(this);
