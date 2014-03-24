(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  A2Cribs.Sublet = (function(_super) {

    __extends(Sublet, _super);

    function Sublet(rental) {
      var date, dates, index, _i, _len;
      Sublet.__super__.constructor.call(this, "sublet", rental);
      dates = ["start_date", "end_date"];
      for (_i = 0, _len = dates.length; _i < _len; _i++) {
        date = dates[_i];
        if (this[date]) {
          if ((index = this[date].indexOf(" ")) !== -1) {
            this[date] = this[date].substring(0, index);
          }
        }
      }
    }

    Sublet.prototype.GetId = function(id) {
      return parseInt(this["listing_id"], 10);
    };

    Sublet.prototype.IsComplete = function() {
      if (this.sublet_id != null) {
        return true;
      } else {
        return false;
      }
    };

    return Sublet;

  })(A2Cribs.Object);

}).call(this);
