(function() {

  A2Cribs.CorrectMarker = (function() {

    function CorrectMarker() {}

    CorrectMarker.Map = null;

    CorrectMarker.Marker = null;

    CorrectMarker.Geocoder = null;

    CorrectMarker.Init = function() {
      var MapOptions;
      this.AnnArborCenter = new google.maps.LatLng(42.2808256, -83.7430378);
      MapOptions = {
        zoom: 15,
        center: this.AnnArborCenter,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      this.Map = new google.maps.Map(document.getElementById('correctLocationMap'), MapOptions);
      google.maps.event.trigger(this.Map, "resize");
      this.Marker = new google.maps.Marker({
        draggable: true,
        position: this.AnnArborCenter,
        map: A2Cribs.CorrectMarker.Map,
        visible: false
      });
      return this.Geocoder = new google.maps.Geocoder();
    };

    CorrectMarker.UpdateLatLong = function(e) {
      $("#updatedLat").html(e.latLng.lat());
      return $("#updatedLong").html(e.latLng.lng());
    };

    CorrectMarker.AddressSearchCallback = function(response, status) {
      var city, component, state, street_address, street_name, street_number, type, zip, _i, _j, _len, _len2, _ref, _ref2;
      console.log(response);
      if (status === google.maps.GeocoderStatus.OK) {
        if (response[0].address_components.length >= 2) {
          street_number = null;
          street_name = null;
          city = null;
          state = null;
          zip = null;
          _ref = response[0].address_components;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            component = _ref[_i];
            _ref2 = component.types;
            for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
              type = _ref2[_j];
              if (type === "street_number") {
                street_number = component.short_name;
              } else if (type === "route") {
                street_name = component.short_name;
              } else if (type === "locality") {
                city = component.short_name;
              } else if (type === "administrative_area_level_1") {
                state = component.short_name;
              } else if (type === "postal_code") {
                zip = component.short_name;
              }
            }
          }
          if (street_number === null) {
            A2Cribs.UIManager.Alert("Entered street address is not valid.");
            $("#formattedAddress").text("");
            return;
          } else {
            A2Cribs.CorrectMarker.Map.panTo(response[0].geometry.location);
            A2Cribs.CorrectMarker.Map.setZoom(18);
          }
          street_address = street_number + " " + street_name;
          /*formatted_address = response[0].formatted_address
          				first_comma = formatted_address.indexOf(',')
          				street = formatted_address.substring(0, first_comma)
          				street_number = street.substring(0, street.indexOf(' '))
          				if isNaN(parseInt(street_number))
          					A2Cribs.UIManager.Alert "Entered street address is not valid."
          					$("#formattedAddress").text("")
          					return
          				else
          					A2Cribs.CorrectMarker.Map.panTo response[0].geometry.location
          					A2Cribs.CorrectMarker.Map.setZoom(18)
          				second_comma = formatted_address.indexOf(',', first_comma + 1)
          				city = formatted_address.substring(first_comma + 2, second_comma)
          				remaining = formatted_address.substring(second_comma + 2)
          				state = remaining.substring(0, remaining.indexOf(" "))
          				remaining = remaining.substring(remaining.indexOf(" ") + 1)
          				postal = remaining.substring(0,remaining.indexOf(","))
          */
          $("#formattedAddress").val(street_address);
          $("#city").val(city);
          $("#state").val(state);
          $("#postal").val(zip);
          A2Cribs.CorrectMarker.Marker.setPosition(response[0].geometry.location);
          A2Cribs.CorrectMarker.Marker.setVisible(true);
          google.maps.event.addListener(A2Cribs.CorrectMarker.Marker, 'dragend', A2Cribs.CorrectMarker.UpdateLatLong);
          $("#updatedLat").val(response[0].geometry.location.lat());
          return $("#updatedLong").val(response[0].geometry.location.lng());
        }
      }
    };

    CorrectMarker.CenterMap = function(lat, long) {
      return this.Map.setCenter(new google.maps.LatLng(lat, long));
    };

    CorrectMarker.SetMarkerAtPosition = function(latLng) {
      return A2Cribs.CorrectMarker.Marker.setPosition(latLng);
    };

    CorrectMarker.FindAddress = function() {
      var address, request, u;
      address = $("#addressToMark").val();
      request = {
        location: A2Cribs.CorrectMarker.Map.getCenter(),
        radius: 8100,
        types: ['street_address'],
        keyword: address,
        name: address
      };
      if (A2Cribs.CorrectMarker.SelectedUniversity !== void 0) {
        u = A2Cribs.CorrectMarker.SelectedUniversity;
        return A2Cribs.CorrectMarker.Geocoder.geocode({
          'address': address + " " + u.city + ", " + u.state
        }, A2Cribs.CorrectMarker.AddressSearchCallback);
      } else {
        return A2Cribs.UIManager.Alert("Please select a university.");
      }
    };

    CorrectMarker.FindSelectedUniversity = function() {
      var selected, u, university, _i, _len, _ref;
      selected = $("#universityName").val();
      _ref = A2Cribs.CorrectMarker.universitiesMap;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        university = _ref[_i];
        if (university.University.name === selected) {
          A2Cribs.CorrectMarker.SelectedUniversity = university.University;
          A2Cribs.Cache.SelectedUniversity = university.University;
        }
      }
      if (A2Cribs.CorrectMarker.SelectedUniversity !== void 0) {
        u = A2Cribs.CorrectMarker.SelectedUniversity;
        return A2Cribs.CorrectMarker.CenterMap(u.latitude, u.longitude);
      }
    };

    return CorrectMarker;

  })();

}).call(this);
