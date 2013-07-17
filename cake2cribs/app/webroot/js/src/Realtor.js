(function() {

  A2Cribs.Realtor = (function() {

    function Realtor(RealtorId, Company, email) {
      this.RealtorId = RealtorId;
      this.Company = Company;
      this.email = email;
      if (this.Company === null) this.LoadRealtor(this.RealtorId);
    }

    Realtor.prototype.LoadRealtor = function() {};

    return Realtor;

  })();

}).call(this);
