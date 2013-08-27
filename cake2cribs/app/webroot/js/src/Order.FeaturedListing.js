(function() {

  A2Cribs.Order.FeaturedListing = (function() {

    function FeaturedListing(Widget, listing_id, address, UniData, initialState) {
      this.Widget = Widget;
      this.listing_id = listing_id;
      this.address = address;
      this.UniData = UniData;
      if (initialState == null) initialState = null;
      this.Weekdays = 0;
      this.Weekends = 0;
      this.Price = 0;
      this.WD_price = 0;
      this.WE_price = 0;
      this.MIN_DAY_OFFSET = 3;
      this.initMultiDatesPicker(initialState);
      this.initTemplates();
      this.PrevSelectedDate = null;
      this.RangeSelectEnabled = true;
      this.Widget.find('.address').html(this.address);
      this.setupHandlers();
      this.setupUniPriceTable(initialState);
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
        _this.UniData[index].enabled = $(event.currentTarget).prop('checked');
        return _this.refresh();
      });
    };

    FeaturedListing.prototype.setupUniPriceTable = function(intialState) {
      var rows, uniPrice, _i, _len, _ref, _ref2;
      rows = "";
      _ref = this.UniData;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        uniPrice = _ref[_i];
        if ((typeof initialState !== "undefined" && initialState !== null ? (_ref2 = initialState.universities) != null ? _ref2[uniPrice.university_id] : void 0 : void 0) != null) {
          uniPrice.enabled = initialState.universities[uniPrice.university_id];
        } else {
          uniPrice.enabled = true;
        }
        rows += this.UniPriceRow(uniPrice);
      }
      return this.Widget.find('.uniPriceTable>tbody').html(rows);
    };

    FeaturedListing.GenerateOrderItem = function(orderState, uni_data) {
      var dates;
      dates = _.without.apply(_, [orderState.selectedDates].concat(uni_data.unavailable_dates));
      return {
        listing_id: orderState.listing_id,
        university_id: uni_data.university_id,
        dates: dates
      };
    };

    FeaturedListing.prototype.getState = function() {
      var uni, unis, _i, _len, _ref;
      unis = {};
      _ref = this.UniData;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        uni = _ref[_i];
        unis[uni.university_id] = uni.enabled;
      }
      return {
        selectedDates: this.getDates('string'),
        universities: unis,
        listing_id: this.listing_id
      };
    };

    FeaturedListing.prototype.clear = function() {
      this.datepicker.multiDatesPicker('resetDates', 'picked');
      return this.refresh();
    };

    FeaturedListing.prototype.reset = function(refresh_after) {
      if (refresh_after == null) refresh_after = true;
      this.datepicker.multiDatesPicker('resetDates', 'picked');
      this.datepicker.multiDatesPicker('resetDates', 'disabled');
      this.Widget.off('click', '.rst input');
      this.Widget.off('click', '.rst .clear-selected-dates');
      return this.Widget.off('click', 'input.uni-toggle', refresh_after ? this.refresh() : void 0);
    };

    FeaturedListing.prototype.getDates = function(type) {
      if (type == null) type = 'object';
      return this.datepicker.multiDatesPicker('getDates', type);
    };

    FeaturedListing.prototype.updatePrice = function() {
      return this.Price = this.Weekdays * this.WD_price + this.Weekends * this.WE_price;
    };

    FeaturedListing.prototype.updateRates = function() {
      var uni, _i, _len, _ref;
      this.WE_price = 0;
      this.WD_price = 0;
      _ref = this.UniData;
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

    FeaturedListing.prototype.initMultiDatesPicker = function(initialState) {
      var pickeroptions, today,
        _this = this;
      today = new Date();
      pickeroptions = {
        dateFormat: "yy-mm-dd",
        minDate: new Date(today.setDate(today.getDate() + this.MIN_DAY_OFFSET)),
        onSelect: function(dateText, inst) {
          if (_this.RangeSelectEnabled) _this.rangeSelect(dateText);
          return _this.refresh();
        }
      };
      if (initialState != null) {
        pickeroptions.addDates = initialState.selectedDates;
      }
      this.datepicker = $(this.Widget).find('.mdp').first().multiDatesPicker(pickeroptions);
      return this.datepicker.click();
    };

    FeaturedListing.prototype.rangeSelect = function(dateText) {
      var date, i, selectedDate, _date, _ref, _ref2;
      if (this.PrevSelectedDate != null) {
        _date = new Date(dateText);
        selectedDate = new Date(_date.getUTCFullYear(), _date.getUTCMonth(), _date.getUTCDate());
        if (this.PrevSelectedDate > selectedDate) {
          _ref = [selectedDate, this.PrevSelectedDate], this.PrevSelectedDate = _ref[0], selectedDate = _ref[1];
        }
        this.SelectedDateRange = A2Cribs.UtilityFunctions.getDateRange(this.PrevSelectedDate, selectedDate);
        for (i = _ref2 = this.SelectedDateRange.length - 1; i >= 0; i += -1) {
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
      var dateConflictNoticeHTML, uniPriceRowHTML;
      uniPriceRowHTML = "<tr data-university_id='<%= university_id %>' >\n    <td><%=name%></td>\n    <td class = 'rates'>$<%=weekday_price.toFixed(2)%></td>\n    <td class = 'rates'>$<%=weekend_price.toFixed(2)%></td>\n    <td><input class = 'uni-toggle' type='checkbox' <% if(enabled){print('checked');} %> />\n</tr>";
      this.UniPriceRow = _.template(uniPriceRowHTML);
      dateConflictNoticeHTML = "<li><i class = 'icon-warning-sign'></i> Listing already featured at <%=name%> on <%\n    $.each(dates, function(index, date){\n        d = new Date(date)\n        if(index != dates.length-1)\n            print(d.getMonth()+1 + \"-\" + d.getDate() +\"-\"+ d.getFullYear() + \", \");\n        else\n            print(d.getMonth()+1 + \"-\" + d.getDate()+\"-\"+ d.getFullYear());\n    });\n    %></li>";
      return this.DateConflictNotice = _.template(dateConflictNoticeHTML);
    };

    FeaturedListing.prototype.checkForDateConflicts = function() {
      var conflictNotices, d, dates, day, priceDif, selected_dates, unavailDate, uni, _i, _j, _len, _len2, _ref, _ref2;
      selected_dates = this.getDates('string');
      conflictNotices = "";
      priceDif = 0;
      _ref = this.UniData;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        uni = _ref[_i];
        if (!uni.enabled) continue;
        dates = [];
        _ref2 = uni.unavailable_dates;
        for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
          unavailDate = _ref2[_j];
          if ($.inArray(unavailDate, selected_dates) !== -1) {
            dates.push(unavailDate);
            d = new Date(unavailDate);
            day = d.getDay();
            if (!(day != null)) continue;
            day = (day + 1) % 7;
            if (day === 0 || day === 6) {
              priceDif += uni.weekend_price;
            } else {
              priceDif += uni.weekday_price;
            }
          }
        }
        if (dates.length > 0) {
          conflictNotices += this.DateConflictNotice({
            name: uni.name,
            dates: dates
          });
        }
      }
      this.Widget.find('.DateConflicts').html(conflictNotices);
      return priceDif;
    };

    FeaturedListing.prototype.refresh = function() {
      var priceDiffDueToConflicts;
      this.updateDayCounts();
      this.updateRates();
      this.updatePrice();
      priceDiffDueToConflicts = this.checkForDateConflicts();
      this.Price -= priceDiffDueToConflicts;
      $(this.Widget).find('.price').html(" $" + (this.Price.toFixed(2)));
      $(this.Widget).find('.weekdays').html(this.Weekdays);
      $(this.Widget).find('.weekends').html(this.Weekends);
      return this.Widget.trigger('orderItemChanged', this);
    };

    return FeaturedListing;

  })();

}).call(this);
