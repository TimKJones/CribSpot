(function() {

  A2Cribs.UILayer.Fees = (function() {

    function Fees() {}

    /*
    	Return an array of Fee objects
    */

    Fees.GetFees = function() {
      var fees;
      fees = [];
      fees.push({
        fee_id: null,
        description: "Admin",
        amount: null
      });
      fees.push({
        fee_id: null,
        description: "Parking",
        amount: 25
      });
      fees.push({
        fee_id: null,
        description: "Furniture",
        amount: null
      });
      fees.push({
        fee_id: null,
        description: "Pets",
        amount: 50
      });
      fees.push({
        fee_id: null,
        description: "Upper Floor",
        amount: null
      });
      fees.push({
        fee_id: null,
        description: "Cleaning",
        amount: 50
      });
      return fees;
    };

    return Fees;

  })();

}).call(this);
