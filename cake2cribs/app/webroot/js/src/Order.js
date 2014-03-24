(function() {

  A2Cribs.Order = (function() {

    function Order() {}

    Order.BuyItems = function(orderItems, order_type, errorHandler, successHandler, failHandler) {
      var data, url,
        _this = this;
      if (successHandler == null) successHandler = null;
      if (failHandler == null) failHandler = null;
      data = {
        'orderItems': JSON.stringify(orderItems),
        'order_type': order_type
      };
      url = "" + myBaseUrl + "order/buy";
      return $.post(url, data, function(response_raw) {
        var response;
        response = JSON.parse(response_raw);
        if (!response.success) {
          errorHandler(response.errors);
          return;
        }
        if (response.jwt != null) {
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
        } else {
          A2Cribs.UIManager.Alert(response.msg);
          return successHandler();
        }
      });
    };

    Order.BuyCart = function(successHandler, failHandler) {
      var url,
        _this = this;
      if (successHandler == null) successHandler = null;
      if (failHandler == null) failHandler = null;
      url = "" + myBaseUrl + "order/buyCart";
      return $.post(url, function(response_raw) {
        var response;
        response = JSON.parse(response_raw);
        if (!response.success) console.log(response.message);
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
      url = myBaseUrl + "shoppingCart/add";
      return $.post(url, data, function(response_raw) {
        var response;
        response = JSON.parse(response_raw);
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
