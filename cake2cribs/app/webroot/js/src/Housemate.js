(function() {

  A2Cribs.Housemate = (function() {

    function Housemate(SubletId, Enrolled, Major, Seeking, GradType, Gender, Quantity) {
      this.SubletId = SubletId;
      this.Enrolled = Enrolled;
      this.Major = Major;
      this.Seeking = Seeking;
      this.GradType = GradType;
      this.Gender = Gender;
      this.Quantity = Quantity;
    }

    return Housemate;

  })();

}).call(this);
