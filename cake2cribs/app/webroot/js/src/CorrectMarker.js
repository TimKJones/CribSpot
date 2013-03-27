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
      var city, first_comma, formatted_address, postal, remaining, second_comma, state, street, street_number;
      console.log(response);
      if (status === google.maps.GeocoderStatus.OK) {
        if (response[0].address_components.length >= 2) {
          formatted_address = response[0].formatted_address;
          first_comma = formatted_address.indexOf(',');
          street = formatted_address.substring(0, first_comma);
          street_number = street.substring(0, street.indexOf(' '));
          if (isNaN(parseInt(street_number))) {
            A2Cribs.UIManager.Alert("Entered street address is not valid.");
            $("#formattedAddress").text("");
            return;
          } else {
            A2Cribs.CorrectMarker.Map.panTo(response[0].geometry.location);
            A2Cribs.CorrectMarker.Map.setZoom(18);
          }
          second_comma = formatted_address.indexOf(',', first_comma + 1);
          city = formatted_address.substring(first_comma + 2, second_comma);
          remaining = formatted_address.substring(second_comma + 2);
          state = remaining.substring(0, remaining.indexOf(" "));
          remaining = remaining.substring(remaining.indexOf(" ") + 1);
          postal = remaining.substring(0, remaining.indexOf(","));
          $("#formattedAddress").html(street);
          $("#city").html(city);
          $("#state").html(state);
          $("#postal").html(postal);
          A2Cribs.CorrectMarker.Marker.setPosition(response[0].geometry.location);
          A2Cribs.CorrectMarker.Marker.setVisible(true);
          google.maps.event.addListener(A2Cribs.CorrectMarker.Marker, 'dragend', A2Cribs.CorrectMarker.UpdateLatLong);
          $("#updatedLat").html(response[0].geometry.location.lat());
          return $("#updatedLong").html(response[0].geometry.location.lng());
        }
      }
    };

    CorrectMarker.CenterMap = function(lat, long) {
      return this.Map.setCenter(new google.maps.LatLng(lat, long));
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
        return A2Cribs.CorrectMarker.Geocoder.geocode({
          'address': address
        }, A2Cribs.CorrectMarker.AddressSearchCallback);
      }
    };

    return CorrectMarker;

  })();

}).call(this);
