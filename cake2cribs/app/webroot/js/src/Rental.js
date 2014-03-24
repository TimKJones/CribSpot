(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  A2Cribs.Rental = (function(_super) {

    __extends(Rental, _super);

    Rental.UNIT_STYLE = ["Unit", "Layout", "Entire House"];

    function Rental(rental) {
      var date, dates, index, _i, _len;
      Rental.__super__.constructor.call(this, "rental", rental);
      dates = ["start_date", "end_date", "alternate_start_date"];
      for (_i = 0, _len = dates.length; _i < _len; _i++) {
        date = dates[_i];
        if (this[date]) {
          if ((index = this[date].indexOf(" ")) !== -1) {
            this[date] = this[date].substring(0, index);
          }
        }
      }
    }

    Rental.prototype.GetUnitStyle = function() {
      return A2Cribs.Rental.UNIT_STYLE[this.unit_style_options];
    };

    Rental.prototype.GetId = function() {
      return parseInt(this["listing_id"], 10);
    };

    Rental.prototype.IsComplete = function() {
      if (this.rental_id != null) {
        return true;
      } else {
        return false;
      }
    };

    Rental.Required_Fields = {};

    return Rental;

  })(A2Cribs.Object);

}).call(this);
