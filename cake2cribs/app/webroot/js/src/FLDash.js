// Generated by CoffeeScript 1.6.3
(function() {
  var __hasProp = {}.hasOwnProperty;

  A2Cribs.FLDash = (function() {
    function FLDash(uiWidget) {
      var _this = this;
      this.uiWidget = uiWidget;
      this.OrderStates = {};
      this.ListingUniPricing = {};
      this.FL_Order = null;
      this.uiFL_Form = $('.featured-listing-order-item').first();
      this.uiListingsList = this.uiWidget.find('#listings_list');
      this.uiOrderItemsList = this.uiWidget.find('#orderItems_list');
      this.uiErrorsList = this.uiWidget.find("#validation-error-list");
      this.initTemplates();
      this.setupEventHandlers();
      $.when(A2Cribs.Dashboard.GetListings().then(function() {
        return _this.loadListings();
      }));
    }

    FLDash.prototype.setupEventHandlers = function() {
      var _this = this;
      this.uiListingsList.on('mouseenter', '.listing-item', function(event) {
        $(event.currentTarget).find('.feature-star').removeClass('icon-star-empty');
        return $(event.currentTarget).find('.feature-star').addClass('icon-star');
      }).on('mouseleave', '.listing-item', function(event) {
        $(event.currentTarget).find('.feature-star').removeClass('icon-star');
        return $(event.currentTarget).find('.feature-star').addClass('icon-star-empty');
      }).on('click', '.listing-item', function(event) {
        var listing_id;
        listing_id = $(event.currentTarget).data('id');
        if (_this.OrderStates[listing_id] == null) {
          _this.addOrderItem(listing_id);
        }
        return _this.editOrderItem(listing_id);
      }).on('click', '.marker-info', function(event) {
        var marker_info;
        marker_info = $(event.currentTarget);
        marker_info.siblings('ul').slideToggle('fast');
        return marker_info.find('i').toggleClass("icon-plus").toggleClass('icon-minus');
      });
      this.uiOrderItemsList.on('click', 'a', function(event) {
        var id, target;
        target = $(event.currentTarget);
        id = target.data('id');
        if (target.hasClass('edit')) {
          return _this.editOrderItem(id);
        } else if (target.hasClass('remove')) {
          return _this.removeOrderItem(id);
        }
      });
      this.uiErrorsList.on('click', '.icon-remove', function(event) {
        var listing_id;
        listing_id = $(event.currentTarget).parent().data('id');
        return _this.removeErrors(listing_id);
      });
      this.uiWidget.find("#buyNow").click(function() {
        return _this.buy();
      });
      this.uiWidget.find(".feature-listing").click(function() {
        return _this.featureListing();
      });
      this.uiFL_Form.on('orderItemChanged', function(event, FL) {
        var listing_id, total;
        listing_id = FL.listing_id;
        _this.uiOrderItemsList.find(".orderItem[data-id=" + listing_id + "] .price").html("" + (FL.getPrice().toFixed(2)));
        total = 0;
        _this.uiOrderItemsList.find(".price").each(function(index, element) {
          return total += Number($(element).html());
        });
        return _this.uiOrderItemsList.siblings('tfoot').find('.total').html("" + (total.toFixed(2)));
      });
      return $('#fl-list-input').keyup(function(event) {
        return $("#listings_list .marker-item").show().filter(function() {
          if ($(this).find(".marker-info").text().toLowerCase().indexOf($("#fl-list-input").val().toLowerCase()) === -1) {
            return true;
          }
          return false;
        }).hide();
      });
    };

    FLDash.prototype.loadListings = function() {
      var address, alt_name, data, description, formattedRental, icon, list, list_item, listing, listing_id, listing_ids, listing_list, marker, marker_data, marker_id, marker_item, rental, unit_style_description, unit_style_options, _i, _j, _len, _len1, _ref;
      list = "";
      marker_data = {};
      _ref = A2Cribs.UserCache.Get('listing');
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        listing = _ref[_i];
        if (marker_data[listing.marker_id] == null) {
          marker_data[listing.marker_id] = [];
        }
        marker_data[listing.marker_id].push(listing.listing_id);
      }
      for (marker_id in marker_data) {
        if (!__hasProp.call(marker_data, marker_id)) continue;
        listing_ids = marker_data[marker_id];
        marker = A2Cribs.UserCache.Get('marker', marker_id);
        listing_list = "";
        address = marker.street_address;
        alt_name = marker_data.alt_name;
        for (_j = 0, _len1 = listing_ids.length; _j < _len1; _j++) {
          listing_id = listing_ids[_j];
          listing = A2Cribs.UserCache.Get('listing', listing_id);
          icon = '';
          switch (parseInt(listing.listing_type)) {
            case 0:
              icon = 'icon-home';
              break;
            case 1:
              icon = 'icon-lemon';
              break;
            case 2:
              icon = 'icon-truck';
          }
          rental = A2Cribs.UserCache.GetAllAssociatedObjects('rental', 'listing', listing.listing_id);
          unit_style_options = "";
          unit_style_description = "";
          if ((rental != null) && rental[0] !== void 0) {
            formattedRental = rental[0];
          }
          description = 'Listing ' + listing_id;
          if ((formattedRental != null) && formattedRental.unit_style_options !== void 0 && formattedRental.unit_style_description !== void 0) {
            if (parseInt(formattedRental.unit_style_options) === 0) {
              unit_style_options = "Unit";
            }
            if (parseInt(formattedRental.unit_style_options) === 1) {
              unit_style_options = "Layout";
            }
            if (parseInt(formattedRental.unit_style_options) === 2) {
              unit_style_options = "Entire House";
            }
            description += unit_style_options;
            if (unit_style_options !== "Entire House") {
              description += " - " + formattedRental.unit_style_description;
            }
          }
          data = {
            icon: icon,
            address: address,
            description: description,
            listing_id: listing_id
          };
          list_item = this.ListingTemplate(data);
          listing_list += list_item;
        }
        data = {
          marker: marker,
          num_listings: listing_ids.length,
          listing_list: listing_list
        };
        marker_item = this.MarkerTemplate(data);
        list += marker_item;
        $("#listings_list_content").append(marker_item);
      }
      return this.uiListingsList.html(list);
    };

    FLDash.prototype.getUniData = function(listing_id) {
      var d, url,
        _this = this;
      if (listing_id == null) {
        listing_id = null;
      }
      if (this.ListingUniPricing[listing_id] == null) {
        d = new $.Deferred();
        url = "/featuredListings/getUniDataForListing/" + listing_id;
        $.ajax({
          url: url,
          type: 'GET',
          success: function(data) {
            return d.resolve(JSON.parse(data));
          }
        });
        this.ListingUniPricing[listing_id] = d.promise();
      }
      return this.ListingUniPricing[listing_id];
    };

    FLDash.prototype.addOrderItem = function(listing_id) {
      var data, listing, marker;
      listing = A2Cribs.UserCache.Get('listing', listing_id);
      marker = A2Cribs.UserCache.Get('marker', listing.marker_id);
      data = {
        address: marker.street_address,
        price: 0.00,
        id: listing.listing_id
      };
      this.OrderStates[listing_id] = {};
      return this.uiOrderItemsList.append(this.OrderItemTemplate(data));
    };

    FLDash.prototype.editOrderItem = function(listing_id) {
      var address, id, initialState, listing, old_id,
        _this = this;
      listing = A2Cribs.UserCache.Get('listing', listing_id);
      if (this.FL_Order != null) {
        old_id = this.FL_Order.listing_id;
        this.uiOrderItemsList.find(".orderItem[data-id=" + old_id + "]").removeClass('editing');
        this.OrderStates[old_id] = this.FL_Order.getState();
        this.FL_Order.reset(false);
      }
      initialState = this.OrderStates[listing_id] != null ? this.OrderStates[listing_id] : null;
      address = A2Cribs.UserCache.Get('marker', listing.marker_id).street_address;
      id = listing_id;
      $.when(this.getUniData(listing_id)).then(function(uniData) {
        return _this.FL_Order = new A2Cribs.Order.FeaturedListing(_this.uiFL_Form, listing.listing_id, address, uniData, initialState);
      });
      this.uiOrderItemsList.find(".orderItem[data-id=" + listing_id + "]").addClass('editing');
      return this.toggleOrderDetailsUI(true);
    };

    FLDash.prototype.removeOrderItem = function(listing_id) {
      var different_id, _ref;
      if (listing_id == null) {
        listing_id = null;
      }
      if (listing_id === null) {
        this.uiOrderItemsList.find(".orderItem").remove();
        this.OrderStates = {};
        this.FL_Order.reset();
        this.FL_Order = null;
      } else {
        this.uiOrderItemsList.find(".orderItem[data-id=" + listing_id + "]").remove();
        this.removeErrors(listing_id);
        delete this.OrderStates[listing_id];
        if (parseInt((_ref = this.FL_Order) != null ? _ref.listing_id : void 0, 10) === listing_id) {
          this.FL_Order.reset();
          this.FL_Order = null;
        }
      }
      if (this.uiOrderItemsList.find(".orderItem").length === 0) {
        return this.toggleOrderDetailsUI(false);
      } else {
        different_id = this.uiOrderItemsList.find(".orderItem").first().data('id');
        return this.editOrderItem(different_id);
      }
    };

    FLDash.prototype.initTemplates = function() {
      var ListingHTML, MarkerHTML, OrderItemHTML;
      ListingHTML = "<li class = 'listing-item' data-id='<%= listing_id %>'>\n    <i class = 'icon-large <%= icon %> listing-icon'></i><strong><%= description %></strong>\n    <i class = 'pull-right feature-star icon-star-empty'></i>\n</li>";
      this.ListingTemplate = _.template(ListingHTML);
      MarkerHTML = "<div class = 'marker-item' data-id='<%= marker.marker_id %>'>\n    <div class = 'marker-info'><i class = 'icon-plus'></i><strong><%= marker.street_address %></strong>  <%= marker.alternate_name %> (<%=num_listings%>)</div>\n    <ul><%= listing_list %></ul>\n</div>";
      this.MarkerTemplate = _.template(MarkerHTML);
      OrderItemHTML = "<tr class = 'orderItem' data-id = '<%= id %>'>\n    <td><span  class = 'address'><%= address %></span></td>\n    <td>$<span class = 'price'?><%= price %></span></td>\n    <td class = 'actions'>\n        <a href = '#' class = 'edit' data-id = '<%= id %>'><i class = 'icon-edit'></i></a>   \n        <a href = '#' class = 'remove' data-id = '<%= id %>'><i class = 'icon-remove-circle'></i></a>\n    </td>\n</tr>\n";
      return this.OrderItemTemplate = _.template(OrderItemHTML);
    };

    FLDash.prototype.showErrors = function(errors) {
      var addr, error_msgs, html, index, listing_id, msg, oi, _i, _len;
      html = "";
      for (listing_id in errors) {
        if (!__hasProp.call(errors, listing_id)) continue;
        error_msgs = errors[listing_id];
        oi = this.uiOrderItemsList.find(".orderItem[data-id=" + listing_id + "]");
        oi.addClass('error');
        addr = oi.find('.address').html();
        html += "<dt data-id='" + listing_id + "'>Validation Errors for " + addr + "<i class = 'icon-remove'></i></dt>";
        for (index = _i = 0, _len = error_msgs.length; _i < _len; index = ++_i) {
          msg = error_msgs[index];
          html += "<dd data-id='" + listing_id + "' class = 'validation-error'>" + (index + 1) + ". " + msg + "</dd>";
        }
      }
      return this.uiErrorsList.html(html);
    };

    FLDash.prototype.removeErrors = function(listing_id) {
      if (listing_id == null) {
        listing_id = null;
      }
      if (listing_id != null) {
        this.uiOrderItemsList.find(".orderItem[data-id=" + listing_id + "]").removeClass("error");
        return this.uiErrorsList.children("[data-id=" + listing_id + "]").remove();
      } else {
        this.uiOrderItemsList.find(".orderItem").removeClass("error");
        return this.uiErrorsList.html("");
      }
    };

    FLDash.prototype.buy = function() {
      var listing_id, uniDataDefereds, _ref,
        _this = this;
      this.removeErrors();
      if (this.FL_Order) {
        this.OrderStates[this.FL_Order.listing_id] = this.FL_Order.getState();
      }
      uniDataDefereds = [];
      _ref = this.OrderStates;
      for (listing_id in _ref) {
        if (!__hasProp.call(_ref, listing_id)) continue;
        uniDataDefereds.push(this.getUniData(listing_id));
      }
      return $.when.apply($, uniDataDefereds).then(function() {
        var od, oi, order, orderData, orderState, uni, uniData, _i, _j, _len, _len1;
        order = [];
        orderData = _.zip(arguments, _.values(_this.OrderStates));
        for (_i = 0, _len = orderData.length; _i < _len; _i++) {
          od = orderData[_i];
          uniData = od[0];
          orderState = od[1];
          if (orderState.selectedDates.length < 1) {
            continue;
          }
          for (_j = 0, _len1 = uniData.length; _j < _len1; _j++) {
            uni = uniData[_j];
            if (!uni.enabled) {
              continue;
            }
            oi = A2Cribs.Order.FeaturedListing.GenerateOrderItem(orderState, uni);
            order.push(oi);
          }
        }
        if (order.length === 0) {
          A2Cribs.UIManager.Alert("You haven't select any dates to feature listings");
          return;
        }
        return A2Cribs.Order.BuyItems(order, 0, function(errors) {
          if ((errors.error_type != null) && errors.error_type === 'NO_LISTINGS_SELECTED') {
            A2Cribs.UIManager.Alert("You haven't selected any dates to feature your listings.");
          } else {
            return _this.showErrors(errors);
          }
        }, function() {
          return _this.removeOrderItem();
        });
      });
    };

    FLDash.prototype.toggleOrderDetailsUI = function(show) {
      if (show) {
        $("#noListingSelected").fadeOut('fast');
        return this.uiWidget.find(".orderingInfo").slideDown();
      } else {
        this.uiWidget.find(".orderingInfo").slideUp();
        return $("#noListingSelected").fadeIn('fast');
      }
    };

    return FLDash;

  })();

}).call(this);
