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
        fee_id: 160,
        description: "Admin",
        amount: 69
      });
      fees.push({
        fee_id: 161,
        description: "Parking",
        amount: 25
      });
      fees.push({
        fee_id: 162,
        description: "Furniture",
        amount: 34
      });
      fees.push({
        fee_id: 163,
        description: "Pets",
        amount: 50
      });
      fees.push({
        fee_id: 164,
        description: "Upper Floor",
        amount: 22
      });
      fees.push({
        fee_id: 165,
        description: "Cleaning",
        amount: 50
      });
      return fees;
    };

    return Fees;

  })();

}).call(this);
