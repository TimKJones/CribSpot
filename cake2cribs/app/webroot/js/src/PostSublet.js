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
      return PostSublet.__super__.setupUI.call(this, this.div);
    };

    PostSublet.prototype.Reset = function() {
      this.ProgressBar.reset();
      this.div.find('.step').eq(0).show();
      this.div.find('.step').eq(0).siblings().hide();
      return PostSublet.__super__.Reset.call(this, this.div);
    };

    PostSublet.prototype.Save = function() {
      if (this.Validate()) {
        return PostSublet.__super__.Save.call(this, this.GetSubletObject());
      }
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

    return PostSublet;

  })(A2Cribs.SubletSave);

}).call(this);
