(function() {
  var __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

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
      var marker, markerid, _len, _ref, _ref2;
      visibleMarkerIds = JSON.parse(visibleMarkerIds);
      _ref = A2Cribs.Map.IdToMarkerMap;
      for (markerid = 0, _len = _ref.length; markerid < _len; markerid++) {
        marker = _ref[markerid];
        if (_ref2 = markerid.toString(), __indexOf.call(visibleMarkerIds, _ref2) >= 0) {
          if (marker) marker.GMarker.setVisible(true);
        } else {
          if (marker) marker.GMarker.setVisible(false);
        }
      }
      return A2Cribs.Map.GMarkerClusterer.repaint();
    };

    /*
    	Called immediately after user applies a filter.
    	start_date, end_date, minRent, maxRent, beds, house, apt, unit_type_other, male, female, students_only, grad, undergrad,
    	bathroom_type, ac, parking, utilities_included, no_security_deposit
    */

    FilterManager.ApplyFilter = function(event, ui) {
      var ac, ajaxData, apt, female, grad, house, male, no_security_deposit, other, parking, students_only, undergrad, utilities;
      ajaxData = null;
      if (event.id === "houseCheck") {
        house = $("#houseCheck").is(':checked');
        ajaxData = "house=" + house;
      } else if (event.id === "aptCheck") {
        apt = $("#aptCheck").is(':checked');
        ajaxData = "apt=" + apt;
      } else if (event.id === "otherCheck") {
        other = $("#otherCheck").is(':checked');
        ajaxData = "unit_type_other=" + other;
      } else if (event.id === "maleCheck") {
        male = $("#maleCheck").is(':checked');
        ajaxData = "male=" + male;
      } else if (event.id === "femaleCheck") {
        female = $("#femaleCheck").is(':checked');
        ajaxData = "female=" + female;
      } else if (event.id === "studentsOnlyCheck") {
        students_only = $("#studentsOnlyCheck").is(':checked');
        ajaxData = "students_only=" + students_only;
      } else if (event.id === "gradCheck") {
        grad = $("#gradCheck").is(':checked');
        ajaxData = "grad=" + grad;
      } else if (event.id === "undergradCheck") {
        undergrad = $("#undergradCheck").is(':checked');
        ajaxData = "undergrad=" + undergrad;
      } else if (event.id === "acCheck") {
        ac = $("#acCheck").is(':checked');
        ajaxData = "ac=" + ac;
      } else if (event.id === "parkingCheck") {
        parking = $("#parkingCheck").is(':checked');
        ajaxData = "parking=" + parking;
      } else if (event.id === "utilitiesCheck") {
        utilities = $("#utilitiesCheck").is(':checked');
        ajaxData = "utilities_included=" + utilities;
      } else if (event.id === "noSecurityDepositCheck") {
        no_security_deposit = $("#noSecurityDepositCheck").is(':checked');
        ajaxData = "no_security_deposit=" + no_security_deposit;
      }
      if (event.target) {
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
        url: myBaseUrl + "Sublets/ApplyFilter",
        type: "GET",
        data: ajaxData,
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
