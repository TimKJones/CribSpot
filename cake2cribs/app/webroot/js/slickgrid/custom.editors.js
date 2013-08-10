/***
 * Contains basic SlickGrid editors.
 * @module Editors
 * @namespace Slick
 */

(function ($) {
  // register namespace
  $.extend(true, window, {
    "A2Cribs": {
      "Editors": {
        "Range": NumericRangeEditor,
        "Unit" : UnitEditor,
        "LeaseLength": makeDropdown(["0 months", "1 month", "2 months", "3 months", "4 months", "5 months", "6 months", "7 months", "8 months", "9 months", "10 months", "11 months", "12 months"]),
        "Dropdown" : makeDropdown
      }
    }
  });
  function NumericRangeEditor(args) {
    var $min_occupancy, $max_occupancy;
    var scope = this;
    var right_count = 0;

    this.init = function () {
      $min_occupancy = $("<INPUT type=text style='width:25px' placeholder='min' />")
          .appendTo(args.container)
          .bind("keydown", scope.handleKeyDown);

      $(args.container).append("&nbsp; to &nbsp;");

      $max_occupancy = $("<INPUT type=text style='width:25px' placeholder='max' />")
          .appendTo(args.container)
          .bind("keydown", scope.handleKeyDown);

      scope.focus();
    };

    this.handleKeyDown = function (e) {
      if (e.keyCode == $.ui.keyCode.LEFT)
        right_count--;

      if (e.keyCode == $.ui.keyCode.RIGHT || e.keyCode == $.ui.keyCode.TAB)
        right_count++;

      if (right_count >= 0 && right_count < 2)
        e.stopImmediatePropagation();
    };

    this.destroy = function () {
      $(args.container).empty();
    };

    this.focus = function () {
      $min_occupancy.focus();
    };

    this.serializeValue = function () {
      return {min_occupancy: parseInt($min_occupancy.val(), 10), max_occupancy: parseInt($max_occupancy.val(), 10)};
    };

    this.applyValue = function (item, state) {
      item.min_occupancy = state.min_occupancy;
      item.max_occupancy = state.max_occupancy;
    };

    this.loadValue = function (item) {
      $min_occupancy.val(item.min_occupancy);
      $max_occupancy.val(item.max_occupancy);
    };

    this.isValueChanged = function () {
      return args.item.min_occupancy != parseInt($min_occupancy.val(), 10) || args.item.max_occupancy != parseInt($min_occupancy.val(), 10);
    };

    this.validate = function () {
      if (isNaN(parseInt($min_occupancy.val(), 10)) || isNaN(parseInt($max_occupancy.val(), 10))) {
        return {valid: false, msg: "Please type in valid numbers."};
      }

      if (parseInt($min_occupancy.val(), 10) > parseInt($max_occupancy.val(), 10)) {
        return {valid: false, msg: "'min_occupancy' cannot be greater than 'to'"};
      }

      return {valid: true, msg: null};
    };

    this.init();
  }
  function UnitEditor(args) {
    var $unit_style_options, $unit_style_description;
    var scope = this;
    var right_count = 0;

    this.init = function () {
      $unit_style_options = $("<select style='width:70px;'/>");
      $("<option />", {value: 1, text: "Unit"}).appendTo($unit_style_options);
      $("<option />", {value: 0, text: "Layout"}).appendTo($unit_style_options);
      $("<option />", {value: 2, text: "Entire House"}).appendTo($unit_style_options);
      $unit_style_options.bind("keydown", scope.handleKeyDown);

      $unit_style_options.appendTo(args.container);
      $(args.container).append("&nbsp;");

      $unit_style_description = $("<INPUT type=text style='width:50px' placeholder='Style F' />")
          .appendTo(args.container)
          .bind("keydown", scope.handleKeyDown);

      $unit_style_options.change(function() {
        var value = $unit_style_options.val() ;
        if (value === "0")
          $unit_style_description.show().attr("placeholder", "Style F")
        else if (value === "1")
          $unit_style_description.show().attr("placeholder", "A7")
        else if (value === "2")
          $unit_style_description.hide()
      });

      scope.focus();
    };

    this.handleKeyDown = function (e) {
      if (e.keyCode == $.ui.keyCode.LEFT)
        right_count--;

      if (e.keyCode == $.ui.keyCode.RIGHT || e.keyCode == $.ui.keyCode.TAB)
        right_count++;

      if (right_count >= 0 && right_count < 3)
        e.stopImmediatePropagation();
    };

    this.destroy = function () {
      $(args.container).empty();
    };

    this.focus = function () {
      $unit_style_options.focus();
    };

    this.serializeValue = function () {
      return {
        unit_style_options: +$unit_style_options.val(),
        unit_style_description: $unit_style_description.val()
      };
    };

    this.applyValue = function (item, state) {
      item.unit_style_options = state.unit_style_options;
      item.unit_style_description = state.unit_style_description;
      item.unit_style_options_text = state.unit_style_options_text
    };

    this.loadValue = function (item) {
      $unit_style_options.val(item.unit_style_options);
      if (+item.unit_style_options === 2)
        $unit_style_description.hide()
      else
        $unit_style_description.show().val(item.unit_style_description);
    };

    this.isValueChanged = function () {
      return args.item.unit_style_options != $unit_style_options.val() || args.item.unit_style_description != $unit_style_description.val();
    };

    this.validate = function () {
      return {valid: true, msg: null};
    };

    this.init();
  }

function makeDropdown(selectable_options)
{
  return function (args) {
      var $select;
      var defaultValue;
      var scope = this;

      this.init = function () {
        $select = $("<select id='selectId' name='selectName' style='width:95px;' />");
        for (var i = 0; i < selectable_options.length; i++) {
          selectable_options[i]
          $("<option />", {value: i, text: selectable_options[i]}).appendTo($select);
        };

        $select.appendTo(args.container);
        $select.focus();
      };

      this.destroy = function () {
        $select.remove();
      };

      this.focus = function () {
        $select.focus();
      };

      this.loadValue = function (item) {
        defaultValue = item[args.column.field];
        $select.val(+defaultValue);
      };

      this.serializeValue = function () {
        return +$select.val();
      };

      this.applyValue = function (item, state) {
        item[args.column.field] = state;
      };

      this.isValueChanged = function () {
        return (this.serializeValue() !== defaultValue);
      };

      this.validate = function () {
        return {
          valid: true,
          msg: null
        };
      };

      this.init();
    }
  }
})(jQuery);
