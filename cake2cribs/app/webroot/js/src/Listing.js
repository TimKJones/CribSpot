// Generated by CoffeeScript 1.4.0
(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  A2Cribs.Listing = (function(_super) {

    __extends(Listing, _super);

    Listing.LISTING_TYPES = ['Rental', 'Sublet'];

    function Listing(listing) {
      Listing.__super__.constructor.call(this, "listing", listing);
    }

    /*
    	Checks/Sets if the listing is visible
    	on the map
    	Defaults to true
    */


    Listing.prototype.IsVisible = function(visible) {
      if (visible == null) {
        visible = null;
      }
      if (typeof visible === "boolean") {
        this.visible = visible;
      }
      if (this.visible === false) {
        return false;
      }
      return true;
    };

    /*
    	Checks/Sets if the listing is in the sidebar
    	This variable is set when the listing
    	is loaded in the sidebar
    */


    Listing.prototype.InSidebar = function(in_sidebar) {
      if (in_sidebar == null) {
        in_sidebar = null;
      }
      if (typeof in_sidebar === "boolean") {
        this.in_sidebar = in_sidebar;
      }
      if (this.in_sidebar === true) {
        return true;
      }
      return false;
    };

    /*
    	Check/Sets if the listing is featured
    */


    Listing.prototype.IsFeatured = function(is_featured) {
      if (is_featured == null) {
        is_featured = null;
      }
      if (typeof is_featured === "boolean") {
        this.is_featured = is_featured;
      }
      if (this.is_featured === true) {
        return true;
      }
      return false;
    };

    /*
    	Gets all objects connected to the listing
    */


    Listing.prototype.GetConnectedObject = function() {
      var a2object, a2objects, listing_string, obj, ret_object, _i, _len;
      listing_string = A2Cribs.Listing.LISTING_TYPES[parseInt(this.listing_type, 10)];
      a2objects = ['Listing', 'Image', listing_string];
      ret_object = {};
      for (_i = 0, _len = a2objects.length; _i < _len; _i++) {
        a2object = a2objects[_i];
        obj = A2Cribs.UserCache.Get(a2object.toLowerCase(), this.GetId());
        if (obj != null) {
          ret_object[a2object] = obj.GetObject();
        }
      }
      return ret_object;
    };

    return Listing;

  })(A2Cribs.Object);

}).call(this);
