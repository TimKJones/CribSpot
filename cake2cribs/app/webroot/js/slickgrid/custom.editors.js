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
        "Dropdown" : makeDropdown,
        "Year": YearEditor,
        "Email": EmailEditor,
        "Phone": PhoneEditor
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
      right_count = 0;
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
      var min = $min_occupancy.val(), max = $max_occupancy.val()
      // Copy fields if only one filled in
      if (min.length === 0 && max.length > 0)
        $min_occupancy.val(max);
      else if (min.length > 0 && max.length === 0)
        $max_occupancy.val(min);

      if (isNaN(parseInt($min_occupancy.val(), 10)) || isNaN(parseInt($max_occupancy.val(), 10))) {
        return {valid: false, msg: "Please type in valid numbers."};
      }

      if (parseInt($min_occupancy.val(), 10) > parseInt($max_occupancy.val(), 10)) {
        return {valid: false, msg: "Invalid Range. Min cannot be larger than max!"};
      }

      if (parseInt($min_occupancy.val(), 10) < 0 || parseInt($max_occupancy.val(), 10) < 0) {
        return {valid: false, msg: "Occupancy cannot be less than 0!"};
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
      $unit_style_options = $("<select style='width:122px;'/>");
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
          $unit_style_description.show().attr("placeholder", "Style F").val("")
        else if (value === "1")
          $unit_style_description.show().attr("placeholder", "A7").val("")
        else if (value === "2")
          $unit_style_description.hide().val("NA")
      });

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
        $select = $("<select id='selectId' name='selectName' style='width:100%;' />");
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

  function YearEditor(args) {
    var $input;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $input = $("<INPUT type=text class='editor-text' />");

      $input.bind("keydown.nav", function (e) {
        if (e.keyCode === $.ui.keyCode.LEFT || e.keyCode === $.ui.keyCode.RIGHT) {
          e.stopImmediatePropagation();
        }
      });

      $input.appendTo(args.container);
      $input.focus().select();
    };

    this.destroy = function () {
      $input.remove();
    };

    this.focus = function () {
      $input.focus();
    };

    this.loadValue = function (item) {
      defaultValue = item[args.column.field];
      $input.val(defaultValue);
      $input[0].defaultValue = defaultValue;
      $input.select();
    };

    this.serializeValue = function () {
      return parseInt($input.val(), 10) || 0;
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
    };

    this.validate = function () {
      if (isNaN($input.val())) {
        return {
          valid: false,
          msg: "Please enter a valid year"
        };
      }
      if ($input.val() > 2030) {
        return {
          valid: false,
          msg: "Futurisitc buildings don't exist!"
        };
      }
      if ($input.val() < 1000) {
        return {
          valid: false,
          msg: "That's a really old building"
        };
      }

      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }

  function EmailEditor(args) {
    var $input;
    var defaultValue;
    var scope = this;

    this.init = function () {
      $input = $("<INPUT type=text class='editor-text' />");

      $input.bind("keydown.nav", function (e) {
        if (e.keyCode === $.ui.keyCode.LEFT || e.keyCode === $.ui.keyCode.RIGHT) {
          e.stopImmediatePropagation();
        }
      });

      $input.appendTo(args.container);
      $input.focus().select();
    };

    this.destroy = function () {
      $input.remove();
    };

    this.focus = function () {
      $input.focus();
    };

    this.loadValue = function (item) {
      defaultValue = item[args.column.field];
      $input.val(defaultValue);
      $input[0].defaultValue = defaultValue;
      $input.select();
    };

    this.serializeValue = function () {
      return $input.val();
    };

    this.applyValue = function (item, state) {
      item[args.column.field] = state;
    };

    this.isValueChanged = function () {
      return (!($input.val() == "" && defaultValue == null)) && ($input.val() != defaultValue);
    };

    this.validate = function () {
      var at_index = $input.val().indexOf("@");
      var dot_index = $input.val().lastIndexOf(".");

      if (at_index < 1 || dot_index < at_index + 2 || dot_index + 2 >= $input.val().length)
        return {
          valid: false,
          msg: "Please input a valid email address"
        }

      return {
        valid: true,
        msg: null
      };
    };

    this.init();
  }
  function PhoneEditor(args) {
    var $areacode, $first_numbers, $last_numbers;
    var scope = this;
    var right_count = 0;

    this.init = function () {
      $(args.container).append("(");
      $areacode = $("<INPUT type='text' style='width:25px' maxlength='3' />")
          .appendTo(args.container)
          .bind("keydown", scope.handleKeyDown)
          .bind("change", function () {
            if ($areacode.val().length > 3)
              $areacode.val($areacode.val().substr(0, 3));
          });

      $(args.container).append(")&nbsp;-&nbsp;");

      $first_numbers = $("<INPUT type='text' style='width:25px' maxlength='3' />")
          .appendTo(args.container)
          .bind("keydown", scope.handleKeyDown)
          .bind("change", function () {
            if ($first_numbers.val().length > 3)
              $first_numbers.val($first_numbers.val().substr(0, 3));
          });
      
      $(args.container).append("&nbsp;-&nbsp;");

      $last_numbers = $("<INPUT type='text' style='width:35px' maxlength='4' />")
          .appendTo(args.container)
          .bind("keydown", scope.handleKeyDown)
          .bind("change", function () {
            if ($last_numbers.val().length > 4)
              $last_numbers.val($last_numbers.val().substr(0, 4));
          });

      scope.focus();
    };

    this.handleKeyDown = function (e) {
      if ($last_numbers.is(":focus") && (e.keyCode == $.ui.keyCode.RIGHT || e.keyCode == $.ui.keyCode.TAB))
        return;

      if (e.keyCode == $.ui.keyCode.LEFT || e.keyCode == $.ui.keyCode.RIGHT || e.keyCode == $.ui.keyCode.TAB) {
        e.stopImmediatePropagation();
      }
    };

    this.destroy = function () {
      $(args.container).empty();
    };

    this.focus = function () {
      $areacode.focus();
      right_count = 0;
    };

    this.serializeValue = function () {
      return {
        contact_phone: $areacode.val() + $first_numbers.val() + $last_numbers.val()
      };
    };

    this.applyValue = function (item, state) {
      item.contact_phone = state.contact_phone;
    };

    this.loadValue = function (item) {
      if (item.contact_phone != null && item.contact_phone.length > 0)
      {
        $areacode.val(item.contact_phone.substr(0, 3));
        $first_numbers.val(item.contact_phone.substr(3, 3));
        $last_numbers.val(item.contact_phone.substr(6, 4));        
      }
    };

    this.isValueChanged = function () {
      var phone = $areacode.val() + $first_numbers.val() + $last_numbers.val();
      if (args.item.contact_phone != phone)
        return true;
    };

    this.validate = function () {
      if ($areacode.val().length !== 3 || $first_numbers.val().length !== 3 || $last_numbers.val().length !== 4)
        return {
          valid: false,
          msg: "Please input a valid number"
        };
      if (isNaN($areacode.val()) || isNaN($first_numbers.val()) || isNaN($last_numbers.val()))
        return {
          valid: false,
          msg: "Please input a valid number"
        }
      return {valid: true, msg: null};
    };

    this.init();
  }
})(jQuery);
