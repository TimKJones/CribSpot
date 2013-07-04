(function() {

  A2Cribs.UtilityFunctions = (function() {

    function UtilityFunctions() {}

    /*
    	returns the left and top offsets of an element relative to the entire page
    */

    UtilityFunctions.getPosition = function(el) {
      var lx, ly, x;
      lx = 0;
      ly = 0;
      while (true) {
        if (!el) break;
        lx += el.offsetLeft;
        ly += el.offsetTop;
        el = el.offsetParent;
      }
      x = {
        x: lx,
        y: ly
      };
      return x;
    };

    /*
    	Returns a date (year, month, day) formatted for Mysql
    */

    UtilityFunctions.GetFormattedDate = function(date) {
      var day, month, year;
      year = date.getUTCFullYear();
      month = date.getMonth() + 1;
      day = date.getDate();
      return year + '-' + month + '-' + day;
    };

    return UtilityFunctions;

  })();

}).call(this);
