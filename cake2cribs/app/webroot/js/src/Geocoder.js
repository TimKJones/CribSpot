(function() {

  A2Cribs.Geocoder = (function() {

    function Geocoder() {}

    Geocoder.FindAddress = function(street_address, city, state) {
      var deferred,
        _this = this;
      deferred = new $.Deferred();
      if (!(this._geocoder != null)) this._geocoder = new google.maps.Geocoder();
      this._geocoder.geocode({
        address: "" + street_address + " " + city + ", " + state
      }, function(response, status) {
        var component, location, street_name, street_number, type, zip, _i, _j, _len, _len2, _ref, _ref2;
        if (status === google.maps.GeocoderStatus.OK && response[0].address_components.length >= 2) {
          _ref = response[0].address_components;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            component = _ref[_i];
            _ref2 = component.types;
            for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
              type = _ref2[_j];
              switch (type) {
                case "street_number":
                  street_number = component.short_name;
                  break;
                case "route":
                  street_name = component.short_name;
                  break;
                case "locality":
                  city = component.short_name;
                  break;
                case "administrative_area_level_1":
                  state = component.short_name;
                  break;
                case "postal_code":
                  zip = component.short_name;
              }
            }
          }
          location = response[0].geometry.location;
          if (!(street_number != null)) return deferred.reject();
          return deferred.resolve(["" + street_number + " " + street_name, city, state, zip, location]);
        } else {
          return deferred.reject();
        }
      });
      return deferred.promise();
    };

    return Geocoder;

  })();

}).call(this);
