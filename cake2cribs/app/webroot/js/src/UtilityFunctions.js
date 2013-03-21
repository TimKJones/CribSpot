// Generated by CoffeeScript 1.6.1
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
        if (!el) {
          break;
        }
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

    return UtilityFunctions;

  })();

}).call(this);
