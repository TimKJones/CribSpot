(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  A2Cribs.Listing = (function(_super) {

    __extends(Listing, _super);

    function Listing(listing) {
      Listing.__super__.constructor.call(this, "listing", listing);
    }

    return Listing;

  })(A2Cribs.Object);

}).call(this);
