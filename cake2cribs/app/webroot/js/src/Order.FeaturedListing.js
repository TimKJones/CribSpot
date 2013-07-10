// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.Order.FeaturedListing = (function() {

    function FeaturedListing(Widget, listing_id, address, options) {
      this.Widget = Widget;
      this.listing_id = listing_id;
      this.address = address;
      if (options == null) {
        options = null;
      }
      this.Weekdays = 0;
      this.Weekends = 0;
      this.Price = 0;
      this.WD_price = 15;
      this.WE_price = 5;
      this.MIN_DAY_OFFSET = 3;
      this.initMultiDatesPicker(options);
      this.Widget.find('.address').html(this.address);
      this.refresh();
    }

    FeaturedListing.prototype.getPrice = function() {
      return this.Price;
    };

    FeaturedListing.prototype.getOrderItem = function() {
      return {
        type: 'FeaturedListing',
        price: this.getPrice(),
        item: {
          address: this.address,
          listing_id: this.listing_id,
          dates: this.getDates('string')
        }
      };
    };

    FeaturedListing.prototype.clear = function() {
      this.datepicker.multiDatesPicker('resetDates');
      return this.refresh();
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
      var disabled_dates, selected_dates, today,
        _this = this;
      if (options == null) {
        options = null;
      }
      today = new Date();
      selected_dates = null;
      disabled_dates = null;
      if ((options != null ? options.selected_dates : void 0) != null) {
        selected_dates = options.selected_dates;
      }
      if ((options != null ? options.disabled_dates : void 0) != null) {
        disabled_dates = options.disabled_dates;
      }
      selected_dates;

      this.datepicker = $(this.Widget).find('.mdp').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        addDates: selected_dates,
        addDisabledDates: disabled_dates,
        minDate: new Date(today.setDate(today.getDate() + this.MIN_DAY_OFFSET)),
        onSelect: function(dateText, inst) {
          return _this.refresh();
        }
      });
      return this.datepicker.click();
    };

    FeaturedListing.prototype.refresh = function() {
      this.updateDayCounts();
      this.updatePrice();
      $(this.Widget).find('.price').html(" $" + (this.Price.toFixed(2)));
      $(this.Widget).find('.weekdays').html(this.Weekdays);
      $(this.Widget).find('.weekends').html(this.Weekends);
      return this.Widget.trigger('orderItemChanged', this);
    };

    return FeaturedListing;

  })();

}).call(this);
