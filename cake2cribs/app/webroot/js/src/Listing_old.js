(function() {

  A2Cribs.Listing = (function() {

    function Listing(ListingId, MarkerId, Available, LeaseRange, UnitType, UnitDescription, Beds, Baths, Rent, Electric, Water, Heat, Air, Parking, Furnished, Url, RealtorId) {
      this.ListingId = ListingId;
      this.MarkerId = MarkerId;
      this.Available = Available;
      this.LeaseRange = LeaseRange;
      this.UnitType = UnitType;
      this.UnitDescription = UnitDescription;
      this.Beds = Beds;
      this.Baths = Baths;
      this.Rent = Rent;
      this.Electric = Electric;
      this.Water = Water;
      this.Heat = Heat;
      this.Air = Air;
      this.Parking = Parking;
      this.Furnished = Furnished;
      this.Url = Url;
      this.RealtorId = RealtorId;
    }

    return Listing;

  })();

}).call(this);