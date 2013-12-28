// Generated by CoffeeScript 1.6.3
(function() {
  A2Cribs.FilterManager = (function() {
    function FilterManager() {}

    FilterManager.UpdateListings = function(visibleListingIds) {
      var all_listings, all_markers, listing, listing_id, marker, sidebar_visible_listings, visible_listings, visible_markers, _i, _j, _k, _len, _len1, _len2;
      visible_listings = JSON.parse(visibleListingIds);
      sidebar_visible_listings = [];
      $("#map_region").trigger('close_bubbles');
      all_listings = A2Cribs.UserCache.Get("listing");
      for (_i = 0, _len = all_listings.length; _i < _len; _i++) {
        listing = all_listings[_i];
        listing.visible = false;
      }
      visible_markers = {};
      for (_j = 0, _len1 = visible_listings.length; _j < _len1; _j++) {
        listing_id = visible_listings[_j];
        listing = A2Cribs.UserCache.Get("listing", listing_id);
        if (listing != null) {
          listing.visible = true;
          sidebar_visible_listings.push(listing.listing_id);
          visible_markers[+listing.marker_id] = true;
        }
      }
      all_markers = A2Cribs.UserCache.Get("marker");
      for (_k = 0, _len2 = all_markers.length; _k < _len2; _k++) {
        marker = all_markers[_k];
        if (visible_markers[+marker.marker_id]) {
          marker.GMarker.setVisible(true);
        } else {
          marker.GMarker.setVisible(false);
        }
      }
      A2Cribs.FeaturedListings.UpdateSidebar(sidebar_visible_listings);
      return A2Cribs.Map.Repaint();
    };

    /*
    	Initialize the underlying google maps functionality of the address search bar
    */


    FilterManager.InitAddressSearch = function() {
      return A2Cribs.FilterManager.Geocoder = new google.maps.Geocoder();
    };

    FilterManager.SearchForAddress = function(div) {
      var address, request,
        _this = this;
      if (A2Cribs.FilterManager.Geocoder == null) {
        A2Cribs.FilterManager.Geocoder = new google.maps.Geocoder();
      }
      address = $(div).val();
      request = {
        location: A2Cribs.Map.GMap.getCenter(),
        radius: 8100,
        types: ['street_address', 'street_number', 'postal_code', 'postal_code_prefix', 'point_of_interest', 'neighborhood', 'intersection', 'transit_station'],
        keyword: address,
        name: address
      };
      return A2Cribs.FilterManager.Geocoder.geocode({
        'address': address + " " + A2Cribs.FilterManager.CurrentCity + ", " + A2Cribs.FilterManager.CurrentState
      }, function(response, status) {
        if (status === google.maps.GeocoderStatus.OK && response[0].types[0] !== "postal_code") {
          $(div).effect("highlight", {
            color: "#5858FA"
          }, 2000);
          A2Cribs.Map.GMap.panTo(response[0].geometry.location);
          return A2Cribs.Map.GMap.setZoom(18);
        } else {
          return $(div).effect("highlight", {
            color: "#FF0000"
          }, 2000);
        }
      });
    };

    return FilterManager;

  })();

}).call(this);
