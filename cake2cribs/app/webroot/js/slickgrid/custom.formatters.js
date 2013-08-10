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
        "RequiredText" : RequiredTextFormatter,
        "RequiredMoney" : RequiredMoneyFormatter,
        "Check" : CheckmarkFormatter,
        "Dropdown" : makeDropdown
      }
    }
  });

  function makeDropdown(selectable_options, isRequired)
  {
    if (isRequired === null)
      isRequired = false;

    return function (row, cell, value, columnDef, dataContext)
    {
      var text, text_class;
      if (typeof(value) == "undefined")
        text = "";
      else
      {
        value = parseInt(value, 10);
        text = selectable_options[value];
      }
      text_class = ""
      if (isRequired && text.length === 0)
        text_class = "required";
      if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
        return "<input value='" + text + "' type='text' class='" + text_class + "' >";
      return "<strong>" + text + "</strong>";
    }
  }

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
      text = "";
    else
      if (+dataContext.unit_style_options === 0)
        text = "Layout" + " - " + dataContext.unit_style_description;
      else if (+dataContext.unit_style_options === 1)
        text = "Unit" + " - " + dataContext.unit_style_description;
      else if (+dataContext.unit_style_options === 2)
        text = "Entire House";
    text_class = (text.length) ? "" : "required"
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value='" + text + "' class='" + text_class +"' type='text' required>";
    return text;
  }
  function ButtonFormatter (row, cell, value, columnDef, dataContext) {
    var button = '<a class="btn btn-primary btn-mini" href="#picture-modal" onclick="A2Cribs.RentalSave.LoadImages(' + row + ')" data-toggle="modal">Add/Edit Images</a>';
    return button;
  }
  function TextFormatter (row, cell, value, columnDef, dataContext) {
    value = (typeof(value) != "undefined") ? value : "";
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value='" + value + "' type='text' />";
    return value;
  }
  function RequiredTextFormatter (row, cell, value, columnDef, dataContext) {
    var text_class;
    value = (typeof(value) != "undefined") ? value : "";
    text_class = (value.toString().length) ? "" : "required";
    if (typeof(dataContext.editable) != "undefined" && dataContext.editable)
      return "<input value='" + value + "' type='text' class='" + text_class + "' required>";
    return value;
  }
  function CheckmarkFormatter(row, cell, value, columnDef, dataContext) {
    return value ? '<img src="/img/dashboard/yes.png" alt="Yes">' : '<img src="/img/dashboard/no.png" alt="No">' ;
  }
})(jQuery);
