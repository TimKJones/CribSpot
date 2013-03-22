(function() {

  A2Cribs.HoverData = (function() {

    function HoverData(NumListings, UnitType, MinBeds, MaxBeds, MinRent, MaxRent, MinDate, MaxDate) {
      this.NumListings = NumListings;
      this.UnitType = UnitType;
      this.MinBeds = MinBeds;
      this.MaxBeds = MaxBeds;
      this.MinRent = MinRent;
      this.MaxRent = MaxRent;
      this.MinDate = MinDate;
      this.MaxDate = MaxDate;
    }

    return HoverData;

  })();

}).call(this);
