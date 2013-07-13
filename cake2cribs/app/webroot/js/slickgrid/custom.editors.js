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
        "Availability" : AvailabilityEditor,
        "Utilities" : UtilitiesEditor,
        "Pets" : PetsEditor,
        "Furnished" : FurnishedEditor,
        "Parking" : ParkingEditor,
        "AC" : ACEditor,
        "Smoking" : SmokingEditor
      }
    }
  });
  function NumericRangeEditor(args) {
    var $min_occupancy, $max_occupancy;
    var scope = this;

    this.init = function () {
      $min_occupancy = $("<INPUT type=text style='width:40px' placeholder='min' />")
          .appendTo(args.container)
          .bind("keydown", scope.handleKeyDown);

      $(args.container).append("&nbsp; to &nbsp;");

      $max_occupancy = $("<INPUT type=text style='width:40px' placeholder='max' />")
          .appendTo(args.container)
          .bind("keydown", scope.handleKeyDown);

      scope.focus();
    };

    this.handleKeyDown = function (e) {
      if (e.keyCode == $.ui.keyCode.LEFT || e.keyCode == $.ui.keyCode.RIGHT || e.keyCode == $.ui.keyCode.TAB) {
        e.stopImmediatePropagation();
      }
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
    var $unit_style_type, $unit_style_options, $unit_style_description;
    var scope = this;

    this.init = function () {
      $unit_style_type = $("<select />");
      $("<option />", {value: 1, text: "Unit"}).appendTo($unit_style_type);
      $("<option />", {value: 0, text: "Style"}).appendTo($unit_style_type);

      $unit_style_type.appendTo(args.container);
      $unit_style_options = $("<INPUT type=text style='width:40px' placeholder='Unit/Style' />")
          .appendTo(args.container)
          .bind("keydown", scope.handleKeyDown);

      $(args.container).append("&nbsp; - &nbsp;");

      $unit_style_description = $("<INPUT type=text style='width:40px' placeholder='Name' />")
          .appendTo(args.container)
          .bind("keydown", scope.handleKeyDown);

      scope.focus();
    };

    this.handleKeyDown = function (e) {
      if (e.keyCode == $.ui.keyCode.LEFT || e.keyCode == $.ui.keyCode.RIGHT || e.keyCode == $.ui.keyCode.TAB) {
        e.stopImmediatePropagation();
      }
    };

    this.destroy = function () {
      $(args.container).empty();
    };

    this.focus = function () {
      $unit_style_type.focus();
    };

    this.serializeValue = function () {
      return {
        unit_style_type: $unit_style_type.val(),
        unit_style_options: $unit_style_options.val(),
        unit_style_description: $unit_style_description.val()
      };
    };

    this.applyValue = function (item, state) {
      item.unit_style_type = state.unit_style_type;
      item.unit_style_options = state.unit_style_options;
      item.unit_style_description = state.unit_style_description;
    };

    this.loadValue = function (item) {
      $unit_style_type.val(item.unit_style_type);
      $unit_style_options.val(item.unit_style_options);
      $unit_style_description.val(item.unit_style_description);
    };

    this.isValueChanged = function () {
      return args.item.unit_style_type != $unit_style_type.val() || args.item.unit_style_options != $unit_style_options.val() || args.item.unit_style_description != $unit_style_description.val();
    };

    this.validate = function () {
      return {valid: true, msg: null};
    };

    this.init();
  }
  function AvailabilityEditor(args) {
    var $select;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $select = $("<select id='selectId' name='selectName' />");
      $("<option />", {value: 1, text: "Available"}).appendTo($select);
      $("<option />", {value: 0, text: "Leased"}).appendTo($select);

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
      defaultValue = !!item[args.column.field];
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
  function UtilitiesEditor(args) {
    var $select;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $select = $("<select id='selectId' name='selectName' />");
      $("<option />", {value: 1, text: "Yes"}).appendTo($select);
      $("<option />", {value: 0, text: "No"}).appendTo($select);
      $("<option />", {value: 2, text: "Flat Rate"}).appendTo($select);

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
      defaultValue = !!item[args.column.field];
      $select.val(+defaultValue);
    };

    this.serializeValue = function () {
      return !!+$select.val();
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
  function PetsEditor(args) {
    var $select;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $select = $("<select id='selectId' name='selectName' />");
      $("<option />", {value: 1, text: "Cats Only"}).appendTo($select);
      $("<option />", {value: 2, text: "Dogs Only"}).appendTo($select);
      $("<option />", {value: 3, text: "Cats & Dogs"}).appendTo($select);
      $("<option />", {value: 4, text: "All Animals"}).appendTo($select);
      $("<option />", {value: 0, text: "Prohibited"}).appendTo($select);

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
      defaultValue = !!item[args.column.field];
      $select.val(+defaultValue);
    };

    this.serializeValue = function () {
      return !!+$select.val();
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
  function FurnishedEditor(args) {
    var $select;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $select = $("<select id='selectId' name='selectName' />");
      $("<option />", {value: 1, text: "Fully"}).appendTo($select);
      $("<option />", {value: 0, text: "Unfurnished"}).appendTo($select);
      $("<option />", {value: 2, text: "Partially"}).appendTo($select);

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
      defaultValue = !!item[args.column.field];
      $select.val(+defaultValue);
    };

    this.serializeValue = function () {
      return !!+$select.val();
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
  function ParkingEditor(args) {
    var $select;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $select = $("<select id='selectId' name='selectName' />");
      $("<option />", {value: 1, text: "Lot"}).appendTo($select);
      $("<option />", {value: 2, text: "Driveway"}).appendTo($select);
      $("<option />", {value: 3, text: "Garage"}).appendTo($select);
      $("<option />", {value: 4, text: "Off-Site"}).appendTo($select);
      $("<option />", {value: 0, text: "None"}).appendTo($select);

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
      defaultValue = !!item[args.column.field];
      $select.val(+defaultValue);
    };

    this.serializeValue = function () {
      return !!+$select.val();
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
  function ACEditor(args) {
    var $select;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $select = $("<select id='selectId' name='selectName' />");
      $("<option />", {value: 1, text: "Central"}).appendTo($select);
      $("<option />", {value: 0, text: "None"}).appendTo($select);
      $("<option />", {value: 2, text: "Wall Unit"}).appendTo($select);

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
      defaultValue = !!item[args.column.field];
      $select.val(+defaultValue);
    };

    this.serializeValue = function () {
      return !!+$select.val();
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
  function SmokingEditor(args) {
    var $select;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $select = $("<select id='selectId' name='selectName' />");
      $("<option />", {value: 1, text: "Allowed"}).appendTo($select);
      $("<option />", {value: 0, text: "Prohibited"}).appendTo($select);

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
      defaultValue = !!item[args.column.field];
      $select.val(+defaultValue);
    };

    this.serializeValue = function () {
      return !!+$select.val();
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
})(jQuery);
