(function() {

  A2Cribs.MixPanel = (function() {
    var array_max, array_min, month_max, month_min;

    function MixPanel() {}

    array_min = function(arr) {
      if (arr != null) return Math.min.apply(null, arr);
    };

    array_max = function(arr) {
      if (arr != null) return Math.min.apply(null, arr);
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
      var bed, i, listing, marker, mixpanel_object, month, unit_type, _len, _len2, _len3, _ref, _ref10, _ref11, _ref12, _ref13, _ref14, _ref15, _ref16, _ref17, _ref18, _ref19, _ref2, _ref20, _ref21, _ref22, _ref23, _ref24, _ref3, _ref4, _ref5, _ref6, _ref7, _ref8, _ref9;
      if (object === void 0 || object === null) return;
      if (object.class_name === "listing") {
        listing = object;
        marker = A2Cribs.UserCache.Get("marker", listing.marker_id);
      } else if (object.class_name === "marker") {
        marker = object;
      } else if (object.class_name === "rental") {
        listing = A2Cribs.UserCache.Get("listing", object.listing_id);
      } else {
        return false;
      }
      mixpanel_object = {
        'listing type': marker != null ? marker.GetBuildingType() : void 0,
        'display type': display_type,
        'is featured': false,
        'listing_id': listing != null ? listing.GetId() : void 0,
        'marker_id': marker != null ? marker.GetId() : void 0,
        'university_id': (_ref = A2Cribs.Map) != null ? _ref.CurentSchoolId : void 0,
        'filter minimum beds': array_min((_ref2 = A2Cribs.RentalFilter.FilterData) != null ? _ref2.Beds : void 0),
        'filter maximum beds': array_max((_ref3 = A2Cribs.RentalFilter.FilterData) != null ? _ref3.Beds : void 0),
        'filter minimum rent': (_ref4 = A2Cribs.RentalFilter.FilterData) != null ? (_ref5 = _ref4.Rent) != null ? _ref5.min : void 0 : void 0,
        'filter maximum rent': (_ref6 = A2Cribs.RentalFilter.FilterData) != null ? (_ref7 = _ref6.Rent) != null ? _ref7.max : void 0 : void 0,
        'filter start year': (_ref8 = A2Cribs.RentalFilter.FilterData) != null ? (_ref9 = _ref8.Dates) != null ? _ref9.year : void 0 : void 0,
        'filter minimum lease length': (_ref10 = A2Cribs.RentalFilter.FilterData) != null ? (_ref11 = _ref10.LeaseRange) != null ? _ref11.min : void 0 : void 0,
        'filter maximum lease length': (_ref12 = A2Cribs.RentalFilter.FilterData) != null ? (_ref13 = _ref12.LeaseRange) != null ? _ref13.max : void 0 : void 0,
        'filter building_type min': array_min,
        'filter building_type': array_min,
        'filter pets allowed': (_ref14 = A2Cribs.RentalFilter.FilterData) != null ? _ref14.PetsAllowed : void 0,
        'filter parking available': (_ref15 = A2Cribs.RentalFilter.FilterData) != null ? _ref15.ParkingAvailable : void 0,
        'filter air conditioning': (_ref16 = A2Cribs.RentalFilter.FilterData) != null ? _ref16.Air : void 0,
        'filter utilities included': (_ref17 = A2Cribs.RentalFilter.FilterData) != null ? _ref17.UtilitiesIncluded : void 0
      };
      if (((_ref18 = A2Cribs.RentalFilter.FilterData) != null ? _ref18.Beds : void 0) != null) {
        _ref19 = A2Cribs.RentalFilter.FilterData.Beds;
        for (i = 0, _len = _ref19.length; i < _len; i++) {
          bed = _ref19[i];
          mixpanel_object["filter bed " + i] = bed;
        }
      }
      if (((_ref20 = A2Cribs.RentalFilter.FilterData) != null ? (_ref21 = _ref20.Dates) != null ? _ref21.months : void 0 : void 0) != null) {
        _ref22 = A2Cribs.RentalFilter.FilterData.Dates.months;
        for (i = 0, _len2 = _ref22.length; i < _len2; i++) {
          month = _ref22[i];
          mixpanel_object["filter month " + i] = month;
        }
      }
      if (((_ref23 = A2Cribs.RentalFilter.FilterData) != null ? _ref23.UnitTypes : void 0) != null) {
        _ref24 = A2Cribs.RentalFilter.FilterData.UnitTypes;
        for (i = 0, _len3 = _ref24.length; i < _len3; i++) {
          unit_type = _ref24[i];
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
      return mixpanel.track(("Post Listing - " + action).data);
    };

    return MixPanel;

  })();

}).call(this);
