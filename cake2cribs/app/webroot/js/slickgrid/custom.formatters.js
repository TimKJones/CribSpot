/***
 * Contains basic SlickGrid formatters.
 * 
 * NOTE:  These are merely examples.  You will most likely need to implement something more
 *        robust/extensible/localizable/etc. for your use!
 * 
 * @module Formatters
 * @namespace Slick
 */

(function ($) {
  // register namespace
  $.extend(true, window, {
    "A2Cribs": {
      "Formatters": {
        "Range": NumericRangeFormatter,
        "Money": MoneyFormatter,
        "Months" : MonthsFormatter,
        "Unit" : UnitFormatter
      }
    }
  });
  function NumericRangeFormatter(row, cell, value, columnDef, dataContext) {
    return dataContext.min_occupancy + " - " + dataContext.max_occupancy;
  }
  function MoneyFormatter (row, cell, value, columnDef, dataContext) {
    return "$" + value;
  }
  function MonthsFormatter (row, cell, value, columnDef, dataContext) {
    return value + " months";
  }
  function UnitFormatter (row, cell, value, columnDef, dataContext) {
    return dataContext.unit_style_options + " - " + dataContext.unit_style_description;
  }
})(jQuery);
