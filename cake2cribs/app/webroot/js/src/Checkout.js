// Generated by CoffeeScript 1.4.0
(function() {

  A2Cribs.Checkout = (function() {
    var FeaturedListingOrder;

    function Checkout(widget, rules) {
      var _this = this;
      this.widget = widget;
      this.rules = rules;
      this.FeaturedListings = [];
      $('.featured-listing-order-item').each(function(index, element) {
        return _this.FeaturedListings.push(new FeaturedListingOrder($(element), _this.rules.FeaturedListings, _this.orderChanged));
      });
      $(this.widget).on('priceChange', '.date-range', function() {
        return _this.priceChanged();
      });
      $(this.widget).on('removeRange', '.featured-listing-order-item', function(event, daterange) {
        return _this.priceChanged();
      });
      $(this.widget).find('.buy').click(function() {
        return _this.startWalletFlow();
      });
    }

    Checkout.prototype.priceChanged = function() {
      var details, listing, total, weekdays, weekends, _i, _len, _ref;
      total = 0;
      weekdays = 0;
      weekends = 0;
      _ref = this.FeaturedListings;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        listing = _ref[_i];
        details = listing.getOrderDetails();
        total += details.price;
        weekdays += details.weekdays;
        weekends += details.weekends;
      }
      $(this.widget).find('.total').html(" $" + (total.toFixed(2)));
      $(this.widget).find('.weekdays').html(weekdays);
      $(this.widget).find('.weekends').html(weekends);
      return $(this.widget).find('.total-tally').show();
    };

    Checkout.prototype.getOrderRequest = function() {
      var daterange, id, listing, request, _i, _len, _ref, _ref1;
      request = [];
      _ref = this.FeaturedListings;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        listing = _ref[_i];
        _ref1 = listing.Ranges;
        for (id in _ref1) {
          daterange = _ref1[id];
          if (daterange.days > 0) {
            request.push({
              listing_id: listing.listing_id,
              start: daterange.start.getTime(),
              end: daterange.end.getTime()
            });
          }
        }
      }
      console.log(request);
      return request;
    };

    Checkout.prototype.startWalletFlow = function() {
      var data, url,
        _this = this;
      data = {
        'type': 'featured-listing',
        'order': JSON.stringify(this.getOrderRequest())
      };
      url = '/order/getJwt';
      return $.post(url, data, function(response_raw) {
        var response;
        response = JSON.parse(response_raw);
        if (!response.success) {
          console.log(response.message);
        }
        return google.payments.inapp.buy({
          parameters: {},
          jwt: response.jwt,
          success: function() {
            return alert("success");
          },
          failture: function() {
            return alert("fail");
          }
        });
      });
    };

    FeaturedListingOrder = (function() {
      var DateRange;

      function FeaturedListingOrder(item, rules) {
        var _this = this;
        this.item = item;
        this.rules = rules;
        this.address = this.item.find('.address').text();
        this.listing_id = this.item.attr('id');
        this.nextRangeId = 0;
        this.Ranges = {};
        this.newRange();
        this.item.find('.add-dates').click(function() {
          return _this.newRange();
        });
        this.item.on('removeRange', '.date-range', function(event, daterange) {
          daterange.widget.remove();
          delete _this.Ranges[daterange.id];
          return _this.item.trigger('removeRange', _this);
        });
      }

      FeaturedListingOrder.prototype.getOrderDetails = function() {
        var details, id, price, range, weekdays, weekends, _ref;
        price = 0;
        weekdays = 0;
        weekends = 0;
        _ref = this.Ranges;
        for (id in _ref) {
          range = _ref[id];
          price += range.getPrice();
          weekdays += range.weekdays;
          weekends += range.weekends;
        }
        details = {
          price: price,
          weekdays: weekdays,
          weekends: weekends
        };
        return details;
      };

      FeaturedListingOrder.prototype.newRange = function() {
        var id, widget;
        widget = $("<div class = 'date-range row-fluid'>\n    <div class = 'span10'>\n        Start: <input type = 'text' class = 'date-input start'></input>\n        End: <input type = 'text' class = 'date-input end'></input> \n        <a href = '#' class ='remove-range'><i class = 'icon-trash icon-large'></i></a>\n    </div>\n    <div class = 'span2'>\n        <span class ='pull-right price'></>\n    </div>\n</div>").appendTo(this.item);
        id = this.nextRangeId++;
        return this.Ranges[id] = new DateRange(widget, id, this.rules);
      };

      DateRange = (function() {

        function DateRange(widget, id, rules) {
          var _this = this;
          this.widget = widget;
          this.id = id;
          this.rules = rules;
          this.days = 0;
          this.weekends = 0;
          this.weekdays = 0;
          this.initDatePickers();
          this.widget.find('.remove-range').click(function() {
            return _this.widget.trigger('removeRange', _this);
          });
        }

        DateRange.prototype.initDatePickers = function() {
          var end_picker, now, nowTemp, start_picker,
            _this = this;
          nowTemp = new Date();
          now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
          this.start = now;
          this.end = now;
          start_picker = this.widget.find('.start').first().datepicker({
            onRender: function(date) {
              if (date.valueOf() < now.valueOf()) {
                return 'disabled';
              } else {
                return '';
              }
            }
          }).on('changeDate', function(event) {
            var newDate;
            if (event.date.valueOf() > _this.end.valueOf()) {
              newDate = new Date(event.date);
              newDate.setDate(newDate.getDate() + 1);
              end_picker.setValue(newDate);
              _this.end = newDate;
            }
            _this.start = event.date;
            start_picker.hide();
            _this.orderChanged();
            return _this.widget.find('.end').focus();
          }).data('datepicker');
          return end_picker = this.widget.find('.end').first().datepicker({
            onRender: function(date) {
              if (date.valueOf() <= _this.start.valueOf()) {
                return 'disabled';
              } else {
                return '';
              }
            }
          }).on('changeDate', function(event) {
            _this.end = event.date;
            _this.orderChanged();
            return end_picker.hide();
          }).data('datepicker');
        };

        DateRange.prototype.orderChanged = function() {
          this.days = A2Cribs.UtilityFunctions.getDaysBetweenDates(this.start, this.end);
          this.weekdays = A2Cribs.UtilityFunctions.getWeekdaysBetweenDates(this.start, this.end);
          this.weekends = this.days - this.weekdays;
          this.setPrice(this.getPrice());
          return this.widget.trigger("priceChange");
        };

        DateRange.prototype.setPrice = function(price) {
          return this.widget.find('.price').html("$" + (price.toFixed(2)));
        };

        DateRange.prototype.getPrice = function() {
          if ((this.weekdays != null) && (this.weekends != null)) {
            return this.weekdays * this.rules.costs.weekday + this.weekends * this.rules.costs.weekend;
          } else {
            return 0;
          }
        };

        return DateRange;

      })();

      return FeaturedListingOrder;

    })();

    return Checkout;

  })();

}).call(this);
