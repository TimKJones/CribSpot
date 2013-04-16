// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.PropertyManagement = (function() {

    function PropertyManagement() {}

    PropertyManagement.removeSublet = function(id) {
      var _this = this;
      return alertify.confirm("Are you sure you want to delete this property? This can't be undone.", function(e) {
        var url;
        if (e) {
          url = myBaseUrl + ("sublets/remove/" + id);
          return window.location.href = url;
        } else {

        }
      });
    };

    return PropertyManagement;

  })();

}).call(this);
