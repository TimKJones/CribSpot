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
        "Text" : TextFormatter,
        "Availability" : AvailabilityFormatter,
        "RequiredText" : RequiredTextFormatter,
        "RequiredMoney" : RequiredMoneyFormatter
      }
    }
  });
  function NumericRangeFormatter(row, cell, value, columnDef, dataContext) {
    var text, text_class;
    if (typeof(dataContext.min_occupancy) != "undefined" && dataContext.min_occupancy != null)
      text = dataContext.min_occupancy + " - " + dataContext.max_occupancy;
    else
      text = ""
    text_class = (text.length) ? "" : "required"
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value='" + text + "' class='" + text_class + "' type='text' required/>";
    return text;
  }
  function MoneyFormatter (row, cell, value, columnDef, dataContext) {
    value = (typeof(value) != "undefined") ? value : "";
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value=$" + value + " type='text' />";
    return value;
  }
  function RequiredMoneyFormatter (row, cell, value, columnDef, dataContext) {
    var text_class;
    value = (typeof(value) != "undefined") ? "$" + value : "";
    text_class = (value.length) ? "" : "required";
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value='" + value + "' type='text' class='" + text_class + "' />";
    return value;
  }
  function MonthsFormatter (row, cell, value, columnDef, dataContext) {
    value = (typeof(value) != "undefined") ? value + " months" : "";
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value='" + value + "' type='text' />";
    return value;
  }
  function UnitFormatter (row, cell, value, columnDef, dataContext) {
    var text, text_class;
    if (typeof(dataContext.unit_style_options) == "undefined" || dataContext.unit_style_options == null)
      text = ""
    else
      text = dataContext.unit_style_type + " - " + dataContext.unit_style_description
    text_class = (text.length) ? "" : "required"
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value='" + text + "' class='" + text_class +"' type='text' required>";
    return text;
  }
  function ButtonFormatter (row, cell, value, columnDef, dataContext) {
    var button = '<a class="btn btn-primary btn-mini" href="#picture-modal" ' + dataContext.listing_id + ' data-toggle="modal">Add/Edit Images</a>';
    return button;
  }
  function TextFormatter (row, cell, value, columnDef, dataContext) {
    value = (typeof(value) != "undefined") ? value : "";
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value='" + value + "' type='text' />";
    return value;
  }
  function AvailabilityFormatter (row, cell, value, columnDef, dataContext) {
    var text, text_class;
    if (typeof(value) == "undefined")
    {
      text = "";
      text_class = "";
    }
    else
    {
      text = (value) ? "Available" : "Leased"
      text_class = (value) ? "text-success" : "text-error"
    }
    text_class += (text.length) ? "" : " required"
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input class='" + text_class + "' value='" + text + "' type='text' >";
    return "<strong class='" + text_class + "'>" + text + "</strong>";
  }
  function RequiredTextFormatter (row, cell, value, columnDef, dataContext) {
    var text_class;
    value = (typeof(value) != "undefined") ? value : "";
    text_class = (value.toString().length) ? "" : "required";
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value='" + value + "' type='text' class='" + text_class + "' required>";
    return value;
  }
})(jQuery);
