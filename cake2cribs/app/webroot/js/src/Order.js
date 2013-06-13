// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.Order = (function() {

    function Order() {}

    Order.Buy = function(orderItems, successHandler, failHandler) {
      var data, url,
        _this = this;
      if (successHandler == null) {
        successHandler = null;
      }
      if (failHandler == null) {
        failHandler = null;
      }
      data = {
        'orderItems': JSON.stringify(orderItems)
      };
      url = "${myBaseUrl}/order/getJwt";
      return $.post(url, data, function(response_raw) {
        var response;
        response = JSON.parse(response_raw);
        if (!response.success) {
          console.log(response.message);
        }
        return google.payments.inapp.buy({
          parameters: {},
          jwt: response.jwt,
          success: function() {
            return alert("success");
          },
          failture: function() {
            return alert("fail");
          }
        });
      });
    };

    Order.AddToCart = function(orderItems) {
      var data, url,
        _this = this;
      data = {
        'orderItems': JSON.stringify(orderItems)
      };
      url = "${myBaseUrl}/shoppingCart/add";
      return $.post(url, data, function(response_raw) {
        var response;
        response = JSON.parse(response_raw);
        alertify.success('Bug report sent. Thank You!', 1500);
        if (response.success) {
          return alertify.success('Added to cart', 1500);
        } else {
          return alertify.error("Adding to cart failed", 1500);
        }
      });
    };

    return Order;

  })();

}).call(this);
