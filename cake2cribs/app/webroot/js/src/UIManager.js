(function() {

  A2Cribs.UIManager = (function() {

    function UIManager() {}

    UIManager.Alert = function(message) {
      return Alertify.dialog.alert(message);
    };

    return UIManager;

  })();

}).call(this);
