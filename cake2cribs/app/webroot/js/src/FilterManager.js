// Generated by CoffeeScript 1.4.0
(function() {
  var __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  A2Cribs.FilterManager = (function() {

    function FilterManager() {}

    FilterManager.MinRent = 0;

    FilterManager.MaxRent = 999999;

    FilterManager.MaxSliderRent = 4000;

    FilterManager.MinBeds = 0;

    FilterManager.MaxBeds = 999999;

    FilterManager.MaxSliderBeds = 10;

    FilterManager.Geocoder = null;

    FilterManager.UpdateMarkers = function(visibleMarkerIds) {
      var marker, markerid, _i, _len, _ref, _ref1;
      visibleMarkerIds = JSON.parse(visibleMarkerIds);
      _ref = A2Cribs.Map.IdToMarkerMap;
      for (markerid = _i = 0, _len = _ref.length; _i < _len; markerid = ++_i) {
        marker = _ref[markerid];
        if (_ref1 = markerid.toString(), __indexOf.call(visibleMarkerIds, _ref1) >= 0) {
          if (marker) {
            marker.GMarker.setVisible(true);
          }
        } else {
          if (marker) {
            marker.GMarker.setVisible(false);
          }
        }
      }
      return A2Cribs.Map.GMarkerClusterer.repaint();
    };

    /*
    	Called immediately after user applies a filter.
    */


    FilterManager.ApplyFilter = function(event, ui) {
      var apt, duplex, fall, house, other, spring;
      fall = $("#fallCheck").is(':checked');
      spring = $("#springCheck").is(':checked');
      other = $("#otherCheck").is(':checked');
      house = $("#houseCheck").is(':checked');
      apt = $("#aptCheck").is(':checked');
      duplex = $("#duplexCheck").is(':checked');
      if (event) {
        if (event.target.id === "rentSlider") {
          A2Cribs.FilterManager.MinRent = ui.values[0];
          A2Cribs.FilterManager.MaxRent = ui.values[1];
          if (A2Cribs.FilterManager.MaxRent === A2Cribs.FilterManager.MaxSliderRent) {
            A2Cribs.FilterManager.MaxRent = 999999;
          }
        } else {
          A2Cribs.FilterManager.MinBeds = ui.values[0];
          A2Cribs.FilterManager.MaxBeds = ui.values[1];
          if (A2Cribs.FilterManager.MaxBeds === A2Cribs.FilterManager.MaxSliderBeds) {
            A2Cribs.FilterManager.MaxBeds = 999999;
          }
        }
      }
      return $.ajax({
        url: myBaseUrl + "Listings/ApplyFilter",
        type: "GET",
        data: "fall=" + fall + "&spring=" + spring + "&other=" + other + "&house=" + house + "&apt=" + apt + "&duplex=" + duplex + "&minRent=" + A2Cribs.FilterManager.MinRent + "&maxRent=" + A2Cribs.FilterManager.MaxRent + "&minBeds=" + A2Cribs.FilterManager.MinBeds + "&maxBeds=" + A2Cribs.FilterManager.MaxBeds,
        context: this,
        success: A2Cribs.FilterManager.UpdateMarkers
      });
    };

    /*
    	Initialize the underlying google maps functionality of the address search bar
    */


    FilterManager.InitAddressSearch = function() {
      return A2Cribs.FilterManager.Geocoder = new google.maps.Geocoder();
    };

    FilterManager.AddressSearchCallback = function(response, status) {
      var formattedAddress;
      if (status === google.maps.GeocoderStatus.OK && response[0].types[0] !== "postal_code") {
        $("#addressSearchBar").effect("highlight", {
          color: "#5858FA"
        }, 2000);
        A2Cribs.Map.GMap.panTo(response[0].geometry.location);
        A2Cribs.Map.GMap.setZoom(18);
        if (response[0].address_components.length >= 2) {
          formattedAddress = response[0].address_components[0].short_name + " " + response[0].address_components[1].short_name;
          if (A2Cribs.Map.AddressToMarkerIdMap[formattedAddress]) {
            return alert(A2Cribs.Map.AddressToMarkerIdMap[formattedAddress]);
          }
        }
      } else {
        return $("#addressSearchBar").effect("highlight", {
          color: "#FF0000"
        }, 2000);
      }
    };

    FilterManager.SearchForAddress = function() {
      var address, request;
      address = $("#addressSearchBar").val();
      request = {
        location: A2Cribs.Map.GMap.getCenter(),
        radius: 8100,
        types: ['street_address', 'street_number', 'postal_code', 'postal_code_prefix', 'point_of_interest', 'neighborhood', 'intersection', 'transit_station'],
        keyword: address,
        name: address
      };
      return A2Cribs.FilterManager.Geocoder.geocode({
        'address': address + " Ann Arbor, MI 48104"
      }, A2Cribs.FilterManager.AddressSearchCallback);
    };

    return FilterManager;

  })();

}).call(this);
