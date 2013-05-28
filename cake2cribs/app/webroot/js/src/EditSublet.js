// Generated by CoffeeScript 1.4.0
(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  A2Cribs.EditSublet = (function(_super) {

    __extends(EditSublet, _super);

    function EditSublet() {
      this.div = $('#edit_sublet_window');
      this.setupUI();
    }

    EditSublet.prototype.setupUI = function() {
      var _this = this;
      return this.div.find(".step-button").click(function(event) {
        _this.div.find(".step-button").removeClass("active");
        $(event.currentTarget).closest(".step-button").addClass("active");
        return _this.GotoStep($(event.currentTarget).closest(".step-button").attr("step"));
      });
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
          if (subletData.redirect != null) {
            window.location = subletData.redirect;
          }
          _this.PopulateInputFields(subletData);
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

    EditSublet.prototype.GetSubletObject = function() {
      return EditSublet.__super__.GetSubletObject.call(this, this.div);
    };

    EditSublet.prototype.Close = function() {
      this.Reset();
      return this.div.parent().hide();
    };

    EditSublet.prototype.Open = function() {
      return this.div.parent().show();
    };

    EditSublet.prototype.PopulateInputFields = function(subletData) {
      var input, k, p, q, v, _results;
      _results = [];
      for (k in subletData) {
        v = subletData[k];
        _results.push((function() {
          var _results1;
          _results1 = [];
          for (p in v) {
            q = v[p];
            console.log(k + "_" + p);
            input = this.div.find("#" + k + "_" + p);
            if (input != null) {
              if ("checkbox" === input.attr("type")) {
                _results1.push(input.prop("checked", q));
              } else {
                _results1.push(input.val(q));
              }
            } else {
              _results1.push(void 0);
            }
          }
          return _results1;
        }).call(this));
      }
      return _results;
    };

    EditSublet.prototype.GotoStep = function(step) {
      return this.div.find('.step').eq(step).show().siblings().hide();
    };

    return EditSublet;

  })(A2Cribs.SubletSave);

}).call(this);
