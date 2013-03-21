(function() {
  var __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  A2Cribs.Landing = (function() {

    function Landing() {}

    Landing.Init = function(locations) {
      var location, that, _i, _len;
      this.schoolList = Array();
      for (_i = 0, _len = locations.length; _i < _len; _i++) {
        location = locations[_i];
        this.schoolList.push(location.University.name);
      }
      that = this;
      return $(function() {
        return $(".typeahead").typeahead({
          source: that.schoolList
        });
      });
    };

    Landing.Submit = function() {
      var location;
      location = $("#search-text").val();
      if (__indexOf.call(this.schoolList, location) < 0) {
        alert(location + " is not a valid location.");
        return false;
      }
      return window.location = "/map/sublet/" + location.split(' ').join('_');
    };

    return Landing;

  })();

}).call(this);
