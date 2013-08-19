// Generated by CoffeeScript 1.4.0
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
      return x({
        this.MonthArray: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
      });
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

    UtilityFunctions.getDateRange = function(startDate, endDate) {
      Date.prototype.addDays = function(days) {
                var dat = new Date(this.valueOf())
                dat.setDate(dat.getDate() + days);
                return dat; 
            };

      var currentDate, dateArray;
      dateArray = new Array();
      currentDate = startDate;
      while (currentDate <= endDate) {
        dateArray.push(currentDate);
        currentDate = currentDate.addDays(1);
      }
      return dateArray;
    };

    return UtilityFunctions;

  })();

}).call(this);
