(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  A2Cribs.HoverData = (function(_super) {

    __extends(HoverData, _super);

    function HoverData(hoverData) {
      HoverData.__super__.constructor.call(this, "hoverData", hoverData);
    }

    /*
    	Overwrite Object.GetId
    	Want to return the marker_id to which this hoverData belongs
    */

    HoverData.prototype.GetId = function() {
      if ((this[0] != null) && (this[0].Listing != null) && (this[0].Listing.marker_id != null)) {
        return parseInt(this[0].Listing.marker_id);
      }
      return null;
    };

    return HoverData;

  })(A2Cribs.Object);

}).call(this);
