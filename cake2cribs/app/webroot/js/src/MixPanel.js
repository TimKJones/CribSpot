// Generated by CoffeeScript 1.4.0
(function() {
  var __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  A2Cribs.MixPanel = (function() {
    var array_max, array_min, month_max, month_min,
      _this = this;

    function MixPanel() {}

    array_min = function(arr) {
      if (arr != null) {
        return Math.min.apply(null, arr);
      }
    };

    array_max = function(arr) {
      if (arr != null) {
        return Math.min.apply(null, arr);
      }
    };

    month_min = function(arr) {};

    month_max = function(arr) {};

    /*
    	Takes a listing or a marker
    	Uses mixpanel to track the Listing Click event
    	Object can be listing or marker
    	display_type = small popup, large popup, full page
    */


    MixPanel.Click = function(object, display_type) {
      var available, bed, i, is_featured, listing, listings, marker, mixpanel_object, month, unit_type, _i, _j, _k, _l, _len, _len1, _len2, _len3, _ref, _ref1, _ref10, _ref11, _ref12, _ref13, _ref14, _ref15, _ref16, _ref17, _ref18, _ref19, _ref2, _ref20, _ref21, _ref22, _ref23, _ref24, _ref25, _ref26, _ref3, _ref4, _ref5, _ref6, _ref7, _ref8, _ref9;
      if (object === void 0 || object === null) {
        return;
      }
      is_featured = 0;
      available = null;
      if (object.class_name === "listing") {
        listing = object;
        is_featured = 1 * (_ref = parseInt(listing.listing_id), __indexOf.call(A2Cribs.FeaturedListings.FLListingIds, _ref) >= 0);
        available = listing.available;
        marker = A2Cribs.UserCache.Get("marker", listing.marker_id);
      } else if (object.class_name === "marker") {
        marker = object;
        listings = A2Cribs.UserCache.GetAllAssociatedObjects('listing', 'marker', marker.marker_id);
        for (_i = 0, _len = listings.length; _i < _len; _i++) {
          listing = listings[_i];
          if (_ref1 = parseInt(listing.listing_id), __indexOf.call(A2Cribs.FeaturedListings.FLListingIds, _ref1) >= 0) {
            is_featured = 1;
            break;
          }
        }
        available = marker.available;
      } else if (object.class_name === "rental") {
        listing = A2Cribs.UserCache.Get("listing", object.listing_id);
        is_featured = 1 * (_ref2 = parseInt(listing.listing_id), __indexOf.call(A2Cribs.FeaturedListings.FLListingIds, _ref2) >= 0);
      } else {
        return false;
      }
      mixpanel_object = {
        'listing type': marker != null ? marker.GetBuildingType() : void 0,
        'display type': display_type,
        'is featured': is_featured,
        'listing_id': listing != null ? listing.GetId() : void 0,
        'marker_id': marker != null ? marker.GetId() : void 0,
        'available': available,
        'university_id': (_ref3 = A2Cribs.Map) != null ? _ref3.CurentSchoolId : void 0,
        'filter minimum beds': array_min((_ref4 = A2Cribs.RentalFilter.FilterData) != null ? _ref4.Beds : void 0),
        'filter maximum beds': array_max((_ref5 = A2Cribs.RentalFilter.FilterData) != null ? _ref5.Beds : void 0),
        'filter minimum rent': (_ref6 = A2Cribs.RentalFilter.FilterData) != null ? (_ref7 = _ref6.Rent) != null ? _ref7.min : void 0 : void 0,
        'filter maximum rent': (_ref8 = A2Cribs.RentalFilter.FilterData) != null ? (_ref9 = _ref8.Rent) != null ? _ref9.max : void 0 : void 0,
        'filter start year': (_ref10 = A2Cribs.RentalFilter.FilterData) != null ? (_ref11 = _ref10.Dates) != null ? _ref11.year : void 0 : void 0,
        'filter minimum lease length': (_ref12 = A2Cribs.RentalFilter.FilterData) != null ? (_ref13 = _ref12.LeaseRange) != null ? _ref13.min : void 0 : void 0,
        'filter maximum lease length': (_ref14 = A2Cribs.RentalFilter.FilterData) != null ? (_ref15 = _ref14.LeaseRange) != null ? _ref15.max : void 0 : void 0,
        'filter building_type min': array_min,
        'filter building_type': array_min,
        'filter pets allowed': (_ref16 = A2Cribs.RentalFilter.FilterData) != null ? _ref16.PetsAllowed : void 0,
        'filter parking available': (_ref17 = A2Cribs.RentalFilter.FilterData) != null ? _ref17.ParkingAvailable : void 0,
        'filter air conditioning': (_ref18 = A2Cribs.RentalFilter.FilterData) != null ? _ref18.Air : void 0,
        'filter utilities included': (_ref19 = A2Cribs.RentalFilter.FilterData) != null ? _ref19.UtilitiesIncluded : void 0
      };
      if (((_ref20 = A2Cribs.RentalFilter.FilterData) != null ? _ref20.Beds : void 0) != null) {
        _ref21 = A2Cribs.RentalFilter.FilterData.Beds;
        for (i = _j = 0, _len1 = _ref21.length; _j < _len1; i = ++_j) {
          bed = _ref21[i];
          mixpanel_object["filter bed " + i] = bed;
        }
      }
      if (((_ref22 = A2Cribs.RentalFilter.FilterData) != null ? (_ref23 = _ref22.Dates) != null ? _ref23.months : void 0 : void 0) != null) {
        _ref24 = A2Cribs.RentalFilter.FilterData.Dates.months;
        for (i = _k = 0, _len2 = _ref24.length; _k < _len2; i = ++_k) {
          month = _ref24[i];
          mixpanel_object["filter month " + i] = month;
        }
      }
      if (((_ref25 = A2Cribs.RentalFilter.FilterData) != null ? _ref25.UnitTypes : void 0) != null) {
        _ref26 = A2Cribs.RentalFilter.FilterData.UnitTypes;
        for (i = _l = 0, _len3 = _ref26.length; _l < _len3; i = ++_l) {
          unit_type = _ref26[i];
          mixpanel_object["filter unit_type " + i] = unit_type;
        }
      }
      return mixpanel.track('Listing Click', mixpanel_object);
    };

    /*
    	Post listing is a wrapper that appends Post Listing to each mixpanel event
    	Actions such as Started, Marker Selected, Find Address on Map, Marker save complete,
    	Add Unit, Overview started, Features started, Description started, Images started, Saved
    */


    MixPanel.PostListing = function(action, data) {
      return mixpanel.track("Post Listing - " + action, data);
    };

    /*
    	For either sign up or login
    */


    MixPanel.AuthEvent = function(action, data) {
      return mixpanel.track(action, data);
    };

    /*
    	Just for basic events
    */


    MixPanel.Event = function(action, data) {
      return mixpanel.track(action, data);
    };

    $(document).ready(function() {
      return $(".mark_leased").click(function(event) {
        var listing_id;
        listing_id = $(event.currentTarget).data("listing-id");
        $(event.currentTarget).button("loading");
        return MixPanel.Event("marked leased", {
          "listing_id": listing_id
        });
      });
    });

    return MixPanel;

  }).call(this);

}).call(this);
