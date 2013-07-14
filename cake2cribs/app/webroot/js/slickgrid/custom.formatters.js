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
        "Unit" : UnitFormatter,
        "Button" : ButtonFormatter,
        "Text" : TextFormatter
      }
    }
  });
  function NumericRangeFormatter(row, cell, value, columnDef, dataContext) {
    return dataContext.min_occupancy + " - " + dataContext.max_occupancy;
  }
  function MoneyFormatter (row, cell, value, columnDef, dataContext) {
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value=$" + value + " type='text' style='width:" + columnDef.width + "px;' />";
    return "$" + value;
  }
  function MonthsFormatter (row, cell, value, columnDef, dataContext) {
    return value + " months";
  }
  function UnitFormatter (row, cell, value, columnDef, dataContext) {
    return dataContext.unit_style_options + " - " + dataContext.unit_style_description;
  }
  function ButtonFormatter (row, cell, value, columnDef, dataContext) {
    var button = '<a class="btn btn-primary btn-mini" href="#picture-modal" ' + dataContext.listing_id + ' data-toggle="modal">Add/Edit Images</a>';
    return button;
  }
  function TextFormatter (row, cell, value, columnDef, dataContext) {
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value=" + value + " type='text' style='width:" + columnDef.width + "px;' />";
    return value;
  }
})(jQuery);
