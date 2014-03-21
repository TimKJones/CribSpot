(function() {

  A2Cribs.Rentpay = (function() {

    function Rentpay() {}

    Rentpay.init = function() {
      this.braintree = Braintree.create("MIIBCgKCAQEAoae5cN5m4spsJAXDUy7MxIH8hH3PcCO/M4PhXEZI51y5LAk6aT4zsNMzdA0G/+nJyhDnPitc3L3PCzNn+JJjeuKNwP5Il59JAmojqw5y6REzDIpFjCWHZId2qocQTbB56SGpfNd/OJIcBL+xv7ndJhM8uqX5byEpTuWXWOf+Sj83GszqfpQQtNDEWrW1a79ayl+Eg7PtGA/BHKEftlxtKJ1GVkOOdek8P4B2jHqnvfchMN2dMTetZiOWeIkcquCGn55k4cRgDj0i4v2CIQ7BFI+XTmqoaW6zcZHPkZKEWg0tWBhBXTB8JvttF9hZPqJkXR+eaHwJ2OCi6l44GTQHdwIDAQAB");
      return this.braintree.onSubmitEncryptForm('braintree-payment-form', this.EncryptFormCallback);
    };

    Rentpay.EncryptFormCallback = function(event) {
      var _this = this;
      $.post('/Rentpays/CreateTransaction', $("#braintree-payment-form").serialize(), function() {
        return console.log('posted');
      });
      $("#paymentSubmit").attr("disabled", "disabled");
      return false;
    };

    return Rentpay;

  })();

}).call(this);
