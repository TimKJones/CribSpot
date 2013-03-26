(function() {
  var __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  A2Cribs.FilterManager = (function() {

    function FilterManager() {}

    FilterManager.MinRent = 0;

    FilterManager.MaxRent = 999999;

    FilterManager.MaxSliderRent = 2000;

    FilterManager.MinBeds = 0;

    FilterManager.MaxBeds = 999999;

    FilterManager.MaxSliderBeds = 10;

    FilterManager.DateBegin = 'NOT_SET';

    FilterManager.DateEnd = 'NOT_SET';

    FilterManager.Geocoder = null;

    FilterManager.UpdateMarkers = function(visibleMarkerIds) {
      var marker, markerid, _len, _ref, _ref2;
      visibleMarkerIds = JSON.parse(visibleMarkerIds);
      _ref = A2Cribs.Cache.IdToMarkerMap;
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
      var ac, ajaxData, apt, bathroom_type, beds, eventDate, female, grad, house, male, no_security_deposit, other, parking, students_only, undergrad, utilities;
      ajaxData = null;
      house = $("#houseCheck").is(':checked');
      ajaxData = "house=" + house;
      apt = $("#aptCheck").is(':checked');
      ajaxData += "&apt=" + apt;
      other = $("#otherCheck").is(':checked');
      ajaxData += "&unit_type_other=" + other;
      male = $("#maleCheck").is(':checked');
      ajaxData += "&male=" + male;
      female = $("#femaleCheck").is(':checked');
      ajaxData += "&female=" + female;
      students_only = $("#studentsOnlyCheck").is(':checked');
      ajaxData += "&students_only=" + students_only;
      grad = $("#gradCheck").is(':checked');
      ajaxData += "&grad=" + grad;
      undergrad = $("#undergradCheck").is(':checked');
      ajaxData += "&undergrad=" + undergrad;
      ac = $("#acCheck").is(':checked');
      ajaxData += "&ac=" + ac;
      parking = $("#parkingCheck").is(':checked');
      ajaxData += "&parking=" + parking;
      utilities = $("#utilitiesCheck").is(':checked');
      ajaxData += "&utilities_included=" + utilities;
      no_security_deposit = $("#noSecurityDepositCheck").is(':checked');
      ajaxData += "&no_security_deposit=" + no_security_deposit;
      beds = $("#bedsSelect").val();
      if (beds === "2+") beds = "2";
      ajaxData += "&beds=" + beds;
      bathroom_type = $("#bathSelect").val();
      ajaxData += "&bathroom_type=" + bathroom_type;
      if (event.target !== void 0 && event.target.id === "slider") {
        A2Cribs.FilterManager.MinRent = event.value[0];
        A2Cribs.FilterManager.MaxRent = event.value[1];
        if (A2Cribs.FilterManager.MaxRent === A2Cribs.FilterManager.MaxSliderRent) {
          A2Cribs.FilterManager.MaxRent = 999999;
        }
      }
      if (event.target !== void 0 && event.target.id === "startDate") {
        eventDate = event.valueOf().date;
        if (A2Cribs.FilterManager.DateEnd !== "NOT_SET" && A2Cribs.FilterManager.DateEnd !== "Whenever" && eventDate > new Date(A2Cribs.FilterManager.DateEnd)) {
          A2Cribs.UIManager.Alert("Start Date cannot occur after End Date.");
          A2Cribs.FilterManager.DateBegin = new Date(A2Cribs.FilterManager.DateEnd);
          A2Cribs.FilterManager.StartDateObject.setValue(A2Cribs.FilterManager.DateBegin);
          return;
        }
        A2Cribs.FilterManager.DateBegin = A2Cribs.FilterManager.GetFormattedDate(eventDate);
      }
      if (event.target !== void 0 && event.target.id === "endDate") {
        eventDate = event.valueOf().date;
        if (A2Cribs.FilterManager.DateBegin !== "NOT_SET" && A2Cribs.FilterManager.DateBegin !== "Whenever" && eventDate < new Date(A2Cribs.FilterManager.DateBegin)) {
          A2Cribs.UIManager.Alert("End Date cannot occur before Start Date.");
          A2Cribs.FilterManager.DateEnd = new Date(A2Cribs.FilterManager.DateBegin);
          A2Cribs.FilterManager.EndDateObject.setValue(A2Cribs.FilterManager.DateEnd);
          return;
        }
        A2Cribs.FilterManager.DateEnd = A2Cribs.FilterManager.GetFormattedDate(event.valueOf().date);
      }
      ajaxData += "&min_rent=" + A2Cribs.FilterManager.MinRent;
      ajaxData += "&max_rent=" + A2Cribs.FilterManager.MaxRent;
      ajaxData += "&start_date=" + A2Cribs.FilterManager.DateBegin;
      ajaxData += "&end_date=" + A2Cribs.FilterManager.DateEnd;
      return $.ajax({
        url: myBaseUrl + "Sublets/ApplyFilter",
        type: "GET",
        data: ajaxData,
        context: this,
        success: A2Cribs.FilterManager.UpdateMarkers
      });
    };

    FilterManager.GetFormattedDate = function(date) {
      var day, month, year;
      year = date.getUTCFullYear();
      month = date.getMonth() + 1;
      day = date.getDate();
      return year + '-' + month + '-' + day;
    };

    FilterManager.GetTodaysDate = function() {
      var dd, mm, today, yyyy;
      today = new Date();
      dd = today.getDate();
      mm = today.getMonth() + 1;
      yyyy = today.getUTCFullYear();
      if (dd < 10) dd = '0' + dd;
      if (mm < 10) mm = '0' + mm;
      today = mm + '-' + dd + '-' + yyyy;
      return new Date(today);
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
      address = $("#AddressSearchText").val();
      request = {
        location: A2Cribs.Map.GMap.getCenter(),
        radius: 8100,
        types: ['street_address', 'street_number', 'postal_code', 'postal_code_prefix', 'point_of_interest', 'neighborhood', 'intersection', 'transit_station'],
        keyword: address,
        name: address
      };
      return A2Cribs.FilterManager.Geocoder.geocode({
        'address': address + " " + A2Cribs.FilterManager.CurrentCity + ", " + A2Cribs.FilterManager.CurrentState
      }, A2Cribs.FilterManager.AddressSearchCallback);
    };

    return FilterManager;

  })();

}).call(this);
