(function() {

  A2Cribs.Rental_Marker = (function() {
    var UpdateMarkerContent;

    function Rental_Marker(MarkerId, Address, Title, UnitType, Latitude, Longitude, City, State) {
      this.MarkerId = MarkerId;
      this.Address = Address;
      this.Title = Title;
      this.UnitType = UnitType;
      this.Latitude = Latitude;
      this.Longitude = Longitude;
      this.City = City;
      this.State = State;
      this.ListingIds = null;
      this.MarkerId = parseInt(this.MarkerId);
      this.GMarker = new google.maps.Marker({
        position: new google.maps.LatLng(this.Latitude, this.Longitude),
        icon: "/img/dots/available_dot.png",
        id: this.MarkerId
      });
      this.Clicked = false;
    }

    /*
    	Called after successful ajax call to retrieve all listing data for a specific marker_id.
    	Updates UI with retrieved data
    */

    UpdateMarkerContent = function(markerData) {
      console.log(JSON.parse(markerData));
      if (!this.Clicked) {
        A2Cribs.Cache.CacheMarkerData(JSON.parse(markerData));
        A2Cribs.Cache.IdToMarkerMap[this.MarkerId].GMarker.setIcon("/img/dots/clicked_dot.png");
        return this.Clicked = true;
      }
    };

    /*
    	Load all listing data for this marker
    	Called when a marker is clicked
    */

    Rental_Marker.prototype.LoadMarkerData = function() {
      if (this.Clicked) {
        return UpdateMarkerContent(null);
      } else {
        return $.ajax({
          url: myBaseUrl + "Listings/LoadMarkerData/" + A2Cribs.Types.LISTING_TYPE_RENTAL + "/" + this.MarkerId,
          type: "GET",
          context: this,
          success: UpdateMarkerContent
        });
      }
    };

    return Rental_Marker;

  })();

}).call(this);
