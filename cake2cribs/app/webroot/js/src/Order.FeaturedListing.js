// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.Order.FeaturedListing = (function() {

    function FeaturedListing(Widget, listing_id, address, UniPricing, options) {
      this.Widget = Widget;
      this.listing_id = listing_id;
      this.address = address;
      this.UniPricing = UniPricing;
      if (options == null) {
        options = null;
      }
      this.Weekdays = 0;
      this.Weekends = 0;
      this.Price = 0;
      this.WD_price = 0;
      this.WE_price = 0;
      this.MIN_DAY_OFFSET = 3;
      this.initMultiDatesPicker(options);
      this.initTemplates();
      this.PrevSelectedDate = null;
      this.RangeSelectEnabled = true;
      this.Widget.find('.address').html(this.address);
      this.setupHandlers();
      this.setupUniPriceTable(options);
      this.refresh();
    }

    FeaturedListing.prototype.getPrice = function() {
      return this.Price;
    };

    FeaturedListing.prototype.setupHandlers = function() {
      var _this = this;
      this.Widget.on('click', '.rst input', function(event) {
        _this.RangeSelectEnabled = !_this.RangeSelectEnabled;
        return _this.PrevSelectedDate = null;
      }).on('click', '.rst .clear-selected-dates', function(event) {
        return _this.clear();
      });
      return this.Widget.on('click', 'input.uni-toggle', function(event) {
        var index;
        index = $(event.currentTarget).parents().eq(1).index();
        _this.UniPricing[index].enabled = $(event.currentTarget).prop('checked');
        return _this.refresh();
      });
    };

    FeaturedListing.prototype.setupUniPriceTable = function(options) {
      var rows, uniPrice, _i, _len, _ref, _ref1;
      rows = "";
      _ref = this.UniPricing;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        uniPrice = _ref[_i];
        if (((_ref1 = options.universities) != null ? _ref1[uniPrice.university_id] : void 0) != null) {
          uniPrice.enabled = options.universities[uniPrice.university_id];
        } else {
          uniPrice.enabled = true;
        }
        rows += this.UniPriceRow(uniPrice);
      }
      return this.Widget.find('.uniPriceTable>tbody').html(rows);
    };

    FeaturedListing.prototype.getOrderItem = function() {
      var orderItem, uni, unis, _i, _len, _ref;
      unis = {};
      _ref = this.UniPricing;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        uni = _ref[_i];
        unis[uni.university_id] = uni.enabled;
      }
      return orderItem = {
        type: 'FeaturedListing',
        price: this.getPrice(),
        item: {
          address: this.address,
          listing_id: this.listing_id,
          dates: this.getDates('string'),
          universities: unis
        }
      };
    };

    FeaturedListing.prototype.clear = function() {
      this.datepicker.multiDatesPicker('resetDates', 'picked');
      return this.refresh();
    };

    FeaturedListing.prototype.reset = function(refresh_after) {
      if (refresh_after == null) {
        refresh_after = true;
      }
      this.datepicker.multiDatesPicker('resetDates', 'picked');
      this.datepicker.multiDatesPicker('resetDates', 'disabled');
      this.Widget.off('click', '.rst input');
      this.Widget.off('click', '.rst .clear-selected-dates');
      if (refresh_after) {
        return this.refresh();
      }
    };

    FeaturedListing.prototype.getDates = function(type) {
      if (type == null) {
        type = 'object';
      }
      return this.datepicker.multiDatesPicker('getDates', type);
    };

    FeaturedListing.prototype.updatePrice = function() {
      return this.Price = this.Weekdays * this.WD_price + this.Weekends * this.WE_price;
    };

    FeaturedListing.prototype.updateRates = function() {
      var uni, _i, _len, _ref;
      this.WE_price = 0;
      this.WD_price = 0;
      _ref = this.UniPricing;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        uni = _ref[_i];
        if (uni.enabled) {
          this.WD_price += uni.weekday_price;
          this.WE_price += uni.weekend_price;
        }
      }
      this.Widget.find('#wd_rate').html(this.WD_price.toFixed(2));
      return this.Widget.find('#we_rate').html(this.WE_price.toFixed(2));
    };

    FeaturedListing.prototype.updateDayCounts = function() {
      var d, day, _i, _len, _ref;
      this.Weekends = 0;
      this.Weekdays = 0;
      _ref = this.getDates();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        d = _ref[_i];
        day = d.getDay();
        if (day === 0 || day === 6) {
          this.Weekends++;
        } else {
          this.Weekdays++;
        }
      }
      return [this.Weekdays, this.Weekends];
    };

    FeaturedListing.prototype.initMultiDatesPicker = function(options) {
      var pickeroptions, today,
        _this = this;
      if (options == null) {
        options = null;
      }
      today = new Date();
      pickeroptions = {
        dateFormat: "yy-mm-dd",
        minDate: new Date(today.setDate(today.getDate() + this.MIN_DAY_OFFSET)),
        onSelect: function(dateText, inst) {
          if (_this.RangeSelectEnabled) {
            _this.rangeSelect(dateText);
          }
          return _this.refresh();
        }
      };
      if ((options != null ? options.selected_dates : void 0) != null) {
        pickeroptions.addDates = options.selected_dates;
      }
      if ((options != null ? options.disabled_dates : void 0) != null) {
        pickeroptions.addDisabledDates = options.disabled_dates;
      }
      this.datepicker = $(this.Widget).find('.mdp').first().multiDatesPicker(pickeroptions);
      return this.datepicker.click();
    };

    FeaturedListing.prototype.rangeSelect = function(dateText) {
      var date, i, selectedDate, _date, _i, _ref, _ref1;
      if (this.PrevSelectedDate != null) {
        _date = new Date(dateText);
        selectedDate = new Date(_date.getUTCFullYear(), _date.getUTCMonth(), _date.getUTCDate());
        if (this.PrevSelectedDate > selectedDate) {
          _ref = [selectedDate, this.PrevSelectedDate], this.PrevSelectedDate = _ref[0], selectedDate = _ref[1];
        }
        this.SelectedDateRange = A2Cribs.UtilityFunctions.getDateRange(this.PrevSelectedDate, selectedDate);
        for (i = _i = _ref1 = this.SelectedDateRange.length - 1; _i >= 0; i = _i += -1) {
          date = this.SelectedDateRange[i];
          if (this.datepicker.multiDatesPicker('gotDate', date, 'disabled') !== false) {
            this.SelectedDateRange.splice(i, 1);
          }
        }
        this.PrevSelectedDate = null;
        return this.datepicker.multiDatesPicker('addDates', this.SelectedDateRange);
      } else {
        if (this.SelectedDateRange != null) {
          this.datepicker.multiDatesPicker('removeDates', this.SelectedDateRange);
        }
        this.SelectedDateRange = null;
        _date = new Date(dateText);
        this.PrevSelectedDate = new Date(_date.getUTCFullYear(), _date.getUTCMonth(), _date.getUTCDate());
        return this.datepicker.multiDatesPicker('addDates', [this.PrevSelectedDate]);
      }
    };

    FeaturedListing.prototype.initTemplates = function() {
      var uniPriceRowHTML;
      uniPriceRowHTML = "<tr data-university_id='<%= university_id %>' >\n    <td><%=name%></td>\n    <td class = 'rates'>$<%=weekday_price.toFixed(2)%></td>\n    <td class = 'rates'>$<%=weekend_price.toFixed(2)%></td>\n    <td><input class = 'uni-toggle' type='checkbox' <% if(enabled){print('checked');} %> />\n</tr>";
      return this.UniPriceRow = _.template(uniPriceRowHTML);
    };

    FeaturedListing.prototype.refresh = function() {
      this.updateDayCounts();
      this.updateRates();
      this.updatePrice();
      $(this.Widget).find('.price').html(" $" + (this.Price.toFixed(2)));
      $(this.Widget).find('.weekdays').html(this.Weekdays);
      $(this.Widget).find('.weekends').html(this.Weekends);
      return this.Widget.trigger('orderItemChanged', this);
    };

    return FeaturedListing;

  })();

}).call(this);
