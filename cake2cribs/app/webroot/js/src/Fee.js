(function() {

  A2Cribs.Fee = (function() {

    function Fee(fee_id, description, amount) {
      this.fee_id = fee_id;
      this.description = description;
      this.amount = amount;
    }

    return Fee;

  })();

}).call(this);
