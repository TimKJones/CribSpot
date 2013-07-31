(function() {

  A2Cribs.UserCache = (function() {
    var add_marker, count, delete_listing, get, get_marker_from_id, get_markers, load_markers,
      _this = this;

    function UserCache() {}

    UserCache.Markers = {
      Rental: [],
      Parking: [],
      Sublet: []
    };

    UserCache.Listings = [];

    get = function(key) {
      var items, listing, _i, _len, _ref;
      items = [];
      _ref = UserCache.Listings;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        listing = _ref[_i];
        if (listing[key] != null) items.push(listing);
      }
      return items;
    };

    count = function(key) {
      var items, listing, _i, _len, _ref;
      items = 0;
      _ref = UserCache.Listings;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        listing = _ref[_i];
        if (listing[key] != null) items++;
      }
      return items;
    };

    get_markers = function(key) {
      if ((UserCache.Markers != null) && UserCache.Markers[key]) {
        return UserCache.Markers[key];
      }
    };

    get_marker_from_id = function(id) {
      var field, key, marker, _i, _len, _ref;
      _ref = UserCache.Markers;
      for (key in _ref) {
        field = _ref[key];
        for (_i = 0, _len = field.length; _i < _len; _i++) {
          marker = field[_i];
          if (marker.marker_id === id) return marker;
        }
      }
      return null;
    };

    load_markers = function(key) {
      var items, listing, marker, _i, _j, _len, _len2, _ref, _results;
      items = [];
      _ref = UserCache.Listings;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        listing = _ref[_i];
        if (listing[key] != null) items[listing.Marker.marker_id] = listing.Marker;
      }
      _results = [];
      for (_j = 0, _len2 = items.length; _j < _len2; _j++) {
        marker = items[_j];
        if (marker != null) {
          _results.push(UserCache.Markers[key].push(marker));
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    add_marker = function(key, marker) {
      var alreadyAdded, m, _i, _len, _ref;
      if ((UserCache.Markers != null) && UserCache.Markers[key]) {
        alreadyAdded = false;
        _ref = UserCache.Markers[key];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          m = _ref[_i];
          if (marker.marker_id === m.marker_id) alreadyAdded = true;
        }
        if (!alreadyAdded) return UserCache.Markers[key].push(marker);
      }
    };

    delete_listing = function(listing_id) {
      var i, _ref, _results;
      _results = [];
      for (i = 0, _ref = UserCache.Listings.length - 1; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
        if (UserCache.Listings[i].Listing.listing_id === listing_id) {
          _results.push(UserCache.Listings.splice(i, 1));
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    UserCache.CacheListings = function(listing_list) {
      var field, i, index, key, listing, term, value, _ref, _results;
      this.Listings = listing_list;
      for (i = 0, _ref = this.Listings.length - 1; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
        listing = this.Listings[i];
        for (key in listing) {
          field = listing[key];
          for (term in field) {
            value = field[term];
            if (!(value != null)) {
              delete this.Listings[i][key][term];
            } else if (typeof value === "string" && (index = value.indexOf("00:00")) !== -1) {
              this.Listings[i][key][term] = value.substring(0, index - 1);
            } else if (typeof value === "boolean") {
              this.Listings[i][key][term] = +value;
            }
          }
        }
      }
      _results = [];
      for (key in this.Markers) {
        _results.push(load_markers(key));
      }
      return _results;
    };

    UserCache.GetListings = function() {
      return this.Listings;
    };

    UserCache.GetRentals = function() {
      return get("Rental");
    };

    UserCache.GetParking = function() {
      return get("Parking");
    };

    UserCache.GetSublets = function() {
      return get("Sublet");
    };

    UserCache.GetListingCount = function() {
      if (this.Listings) {
        return this.Listings.length();
      } else {
        return 0;
      }
    };

    UserCache.GetSubletCount = function() {
      return count("Sublet");
    };

    UserCache.GetParkingCount = function() {
      return count("Parking");
    };

    UserCache.GetRentalCount = function() {
      return count("Rental");
    };

    UserCache.GetRentalMarkers = function() {
      return get_markers("Rental");
    };

    UserCache.GetSubletMarkers = function() {
      return get_markers("Sublet");
    };

    UserCache.GetParkingMarkers = function() {
      return get_markers("Parking");
    };

    UserCache.GetListingMarkers = function() {
      return {
        sublet: this.GetSubletMarkers(),
        parking: this.GetParkingMarkers(),
        rentals: this.GetRentalMarkers()
      };
    };

    UserCache.AddSubletMarker = function(marker) {
      return add_marker("Sublet", marker);
    };

    UserCache.AddParkingMarker = function(marker) {
      return add_marker("Parking", marker);
    };

    UserCache.AddRentalMarker = function(marker) {
      return add_marker("Rental", marker);
    };

    UserCache.DeleteListing = function(listing_id) {
      return delete_listing(listing_id);
    };

    UserCache.GetMarkerById = function(id) {
      return get_marker_from_id(id);
    };

    return UserCache;

  }).call(this);

}).call(this);
