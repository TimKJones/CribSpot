// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.MiniMap = (function() {

    function MiniMap(div, latitude, longitude, marker_visible, enabled) {
      var MapOptions, mapDiv;
      if (latitude == null) {
        latitude = 39.8282;
      }
      if (longitude == null) {
        longitude = -98.5795;
      }
      if (marker_visible == null) {
        marker_visible = false;
      }
      if (enabled == null) {
        enabled = true;
      }
      mapDiv = div.find('#correctLocationMap')[0];
      this.center = new google.maps.LatLng(latitude, longitude);
      MapOptions = {
        zoom: 2,
        center: this.center,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        panControl: false,
        zoomControl: false,
        streetViewControl: false
      };
      this.Map = new google.maps.Map(mapDiv, MapOptions);
      this.Marker = new google.maps.Marker({
        draggable: enabled,
        position: this.center,
        map: this.Map,
        visible: marker_visible
      });
      if (!enabled) {
        this.Disable();
      }
      this.Resize();
    }

    MiniMap.prototype.CenterMap = function(latitude, longitude) {
      this.center = new google.maps.LatLng(latitude, longitude);
      return this.Resize();
    };

    MiniMap.prototype.Resize = function() {
      google.maps.event.trigger(this.Map, "resize");
      return this.Map.setCenter(this.center);
    };

    MiniMap.prototype.SetMarkerPosition = function(location) {
      this.Map.panTo(location);
      this.SetZoom(18);
      this.Marker.setPosition(location);
      return this.Marker.setVisible(true);
    };

    MiniMap.prototype.SetZoom = function(zoom) {
      return this.Map.setZoom(zoom);
    };

    MiniMap.prototype.GetMarkerPosition = function() {
      return {
        'latitude': this.Marker.position.lat(),
        'longitude': this.Marker.position.lng()
      };
    };

    return MiniMap;

  })();

}).call(this);
