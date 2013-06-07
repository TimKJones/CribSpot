// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.UIManager = (function() {

    function UIManager() {}

    UIManager.Alert = function(message) {
      return alertify.alert(message);
    };

    UIManager.Error = function(message) {
      return alertify.error(message);
    };

    UIManager.Success = function(message) {
      return alertify.success(message);
    };

    UIManager.CloseLogs = function() {
      return $('.alertify-log').remove();
    };

    return UIManager;

  })();

}).call(this);
