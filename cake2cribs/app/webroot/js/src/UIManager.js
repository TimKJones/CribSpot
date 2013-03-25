(function() {

  A2Cribs.UIManager = (function() {

    function UIManager() {}

    UIManager.Alert = function(message) {
      return alertify.alert(message);
    };

    return UIManager;

  })();

}).call(this);
