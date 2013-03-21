(function() {

  A2Cribs.CorrectMarker = (function() {

    function CorrectMarker() {}

    CorrectMarker.Map = null;

    CorrectMarker.Marker = null;

    CorrectMarker.Geocoder = null;

    CorrectMarker.Init = function() {
      var AnnArborCenter, MapOptions;
      AnnArborCenter = new google.maps.LatLng(42.2808256, -83.7430378);
      MapOptions = {
        zoom: 15,
        center: AnnArborCenter,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      this.Map = new google.maps.Map(document.getElementById('correctLocationMap'), MapOptions);
      this.Marker = new google.maps.Marker({
        draggable: true,
        position: A2Cribs.Map.AnnArborCenter,
        map: A2Cribs.CorrectMarker.Map,
        visible: false
      });
      this.Geocoder = new google.maps.Geocoder();
      return A2Cribs.CorrectMarker.Map.setCenter();
    };

    CorrectMarker.UpdateLatLong = function(e) {
      $("#updatedLat").html(e.latLng.lat());
      return $("#updatedLong").html(e.latLng.lng());
    };

    CorrectMarker.AddressSearchCallback = function(response, status) {
      var formattedAddress;
      console.log(response);
      if (status === google.maps.GeocoderStatus.OK) {
        A2Cribs.CorrectMarker.Map.panTo(response[0].geometry.location);
        A2Cribs.CorrectMarker.Map.setZoom(18);
        if (response[0].address_components.length >= 2) {
          formattedAddress = response[0].address_components[0].short_name + " " + response[0].address_components[1].short_name;
          $("#formattedAddress").html(formattedAddress);
          A2Cribs.CorrectMarker.Marker.setPosition(response[0].geometry.location);
          A2Cribs.CorrectMarker.Marker.setVisible(true);
          google.maps.event.addListener(A2Cribs.CorrectMarker.Marker, 'dragend', A2Cribs.CorrectMarker.UpdateLatLong);
          $("#updatedLat").html(response[0].geometry.location.lat());
          return $("#updatedLong").html(response[0].geometry.location.lng());
        }
      }
    };

    CorrectMarker.FindAddress = function() {
      var address, request;
      address = $("#addressToMark").val();
      request = {
        location: A2Cribs.CorrectMarker.Map.getCenter(),
        radius: 8100,
        types: ['street_address'],
        keyword: address,
        name: address
      };
      return A2Cribs.CorrectMarker.Geocoder.geocode({
        'address': address + " Ann Arbor, MI 48104"
      }, A2Cribs.CorrectMarker.AddressSearchCallback);
    };

    return CorrectMarker;

  })();

}).call(this);
