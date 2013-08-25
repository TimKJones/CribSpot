(function() {
  var _this = this;

  A2Cribs.UIManager = (function() {

    function UIManager() {}

    UIManager.Alert = function(message) {
      return alertify.alert(message);
    };

    UIManager.Error = function(message) {
      return alertify.error(message, 7000);
    };

    UIManager.Success = function(message) {
      return alertify.success(message);
    };

    UIManager.CloseLogs = function() {
      return $('.alertify-log').remove();
    };

    UIManager.FlashMessage = function() {
      if (typeof flash_message !== "undefined" && flash_message !== null) {
        return this[flash_message.method](flash_message.message);
      }
    };

    UIManager.Confirm = function(message, callback) {
      alertify.set({
        buttonFocus: "cancel"
      });
      return alertify.confirm(message, callback);
    };

    return UIManager;

  })();

  $(document).ready(function() {
    return setTimeout((function() {
      return A2Cribs.UIManager.FlashMessage();
    }), 2000);
  });

}).call(this);
