(function() {
  var _this = this;

  A2Cribs.UIManager = (function() {

    function UIManager() {}

    UIManager._num_loaders = 0;

    /*
    	Show Loader
    	Takes a div (otherwise null). Shows loader
    	in the middle of the div otherwise the 
    	middle of the screen. Keeps track of the 
    	amount of loaders being displayed
    	TODO: ADD DIV SUPPORT (JUST GLOBAL FOR NOW)
    */

    UIManager.ShowLoader = function(div) {
      this._num_loaders++;
      return $("#loader").show();
    };

    /*
    	Hide Loader
    	Hides the spinner based on the div. If no 
    	div given then main loader. Only removes
    	the loader if loader count is 0.
    	TODO: ADD DIV SUPPORT (JUST GLOBAL FOR NOW)
    */

    UIManager.HideLoader = function(div) {
      if (--this._num_loaders === 0) return $("#loader").hide();
    };

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
        return this[flash_message.method](flash_message.message, flash_message.callback);
      }
    };

    UIManager.Confirm = function(message, callback) {
      alertify.set({
        buttonFocus: "cancel"
      });
      return alertify.confirm(message, callback);
    };

    UIManager.ConfirmBox = function(message, labels, callback) {
      alertify.set({
        labels: labels,
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
