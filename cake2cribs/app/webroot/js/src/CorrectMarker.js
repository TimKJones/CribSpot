(function() {

  A2Cribs.CorrectMarker = (function() {

    function CorrectMarker() {}

    CorrectMarker.Map = null;

    CorrectMarker.Marker = null;

    CorrectMarker.Geocoder = null;

    CorrectMarker.Enabled = true;

    CorrectMarker.Init = function() {
      var div;
      this.LoadUniversities();
      div = $('#post-sublet-modal').find('#correctLocationMap')[0];
      this.CreateMap(div, 42.2808256, -83.7430378);
      return this.Geocoder = new google.maps.Geocoder();
    };

    CorrectMarker.CreateMap = function(div, latitude, longitude, marker_visible, enabled) {
      var MapOptions, center;
      if (marker_visible == null) marker_visible = false;
      if (enabled == null) enabled = true;
      center = new google.maps.LatLng(latitude, longitude);
      MapOptions = {
        zoom: 15,
        center: center,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        panControl: false,
        zoomControl: false,
        streetViewControl: false
      };
      this.Map = new google.maps.Map(div, MapOptions);
      this.Marker = new google.maps.Marker({
        draggable: enabled,
        position: center,
        map: A2Cribs.CorrectMarker.Map,
        visible: marker_visible
      });
      if (!enabled) this.Disable();
      return google.maps.event.trigger(this.Map, "resize");
    };

    CorrectMarker.Disable = function() {
      if ((this.Map != null)) {
        return this.Map.setOptions({
          draggable: false,
          zoomControl: false,
          scrollwheel: false,
          disableDoubleClickZoom: true
        });
      } else {
        return this.Enabled = false;
      }
    };

    CorrectMarker.Enable = function() {
      if ((this.Map != null)) {
        return this.Map.setOptions({
          draggable: true,
          zoomControl: true,
          scrollwheel: true,
          disableDoubleClickZoom: false
        });
      } else {
        return this.Enabled = true;
      }
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
          zip = "00000";
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

    CorrectMarker.CenterMap = function(lat, lng) {
      return this.Map.setCenter(new google.maps.LatLng(lat, lng));
    };

    CorrectMarker.SetMarkerAtPosition = function(latLng) {
      return A2Cribs.CorrectMarker.Marker.setPosition(latLng);
    };

    CorrectMarker.FindAddress = function() {
      var address, request, u;
      address = $("#formattedAddress").val();
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
      var index, selected, u;
      selected = $("#universityName").val();
      index = this.SchoolList.indexOf(selected);
      if (index >= 0) {
        A2Cribs.CorrectMarker.SelectedUniversity = this.universitiesMap[index].University;
        A2Cribs.Cache.SelectedUniversity = this.universitiesMap[index].University;
        u = A2Cribs.CorrectMarker.SelectedUniversity;
        A2Cribs.CorrectMarker.CenterMap(u.latitude, u.longitude);
        return $("#universityId").val(A2Cribs.CorrectMarker.SchoolIDList[index]);
      }
    };

    CorrectMarker.LoadUniversities = function() {
      return $.ajax({
        url: "/University/getAll",
        success: function(response) {
          var university, _i, _len, _ref;
          A2Cribs.CorrectMarker.universitiesMap = JSON.parse(response);
          A2Cribs.CorrectMarker.SchoolList = [];
          A2Cribs.CorrectMarker.SchoolIDList = [];
          _ref = A2Cribs.CorrectMarker.universitiesMap;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            university = _ref[_i];
            A2Cribs.CorrectMarker.SchoolList.push(university.University.name);
            A2Cribs.CorrectMarker.SchoolIDList.push(university.University.id);
          }
          return $("#universityName").typeahead({
            source: A2Cribs.CorrectMarker.SchoolList
          });
        }
      });
    };

    CorrectMarker.ClearMarker = function() {
      if (A2Cribs.CorrectMarker.Marker != null) {
        return A2Cribs.CorrectMarker.Marker.setVisible(false);
      }
    };

    return CorrectMarker;

  })();

}).call(this);
