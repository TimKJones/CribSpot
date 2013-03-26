(function() {

  A2Cribs.Sublet = (function() {

    function Sublet(SubletId, UniversityId, BuildingType, Name, StreetAddress, City, State, StartDate, EndDate, Bedrooms, PricePerBedroom, Description, NumberBathrooms, BathroomType, UtilityCost, DepositAmount, AdditionalFeesDescription, AdditionalFeesAmount, MarkerId, FlexibleDates, Furnished, DateAdded, Air, Parking) {
      this.SubletId = SubletId;
      this.UniversityId = UniversityId;
      this.BuildingType = BuildingType;
      this.Name = Name;
      this.StreetAddress = StreetAddress;
      this.City = City;
      this.State = State;
      this.StartDate = StartDate;
      this.EndDate = EndDate;
      this.Bedrooms = Bedrooms;
      this.PricePerBedroom = PricePerBedroom;
      this.Description = Description;
      this.NumberBathrooms = NumberBathrooms;
      this.BathroomType = BathroomType;
      this.UtilityCost = UtilityCost;
      this.DepositAmount = DepositAmount;
      this.AdditionalFeesDescription = AdditionalFeesDescription;
      this.AdditionalFeesAmount = AdditionalFeesAmount;
      this.MarkerId = MarkerId;
      this.FlexibleDates = FlexibleDates;
      this.Furnished = Furnished;
      this.DateAdded = DateAdded;
      this.Air = Air;
      this.Parking = Parking;
    }

    return Sublet;

  })();

}).call(this);
