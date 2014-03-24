(function() {

  A2Cribs.ShoppingCart = (function() {

    function ShoppingCart(Widget) {
      var ListItemHTML,
        _this = this;
      this.Widget = Widget;
      this.Widget.on('click', '.edit', function(event) {
        var index;
        index = $(event.currentTarget).attr('id');
        return _this.edit(index);
      }).on('click', '.remove', function(event) {
        var index;
        index = $(event.currentTarget).attr('id');
        return _this.remove(index);
      });
      this.Widget.find('.buy').click(function() {
        return A2Cribs.Order.BuyCart();
      });
      this.Widget.find('.hide-edit').click(function() {
        $('.fl-cart-item').removeClass('editing');
        return $('.edit-form').fadeOut();
      });
      this.Widget.find('.save').click(function() {
        return _this.save(_this.EditingIndex);
      });
      this.Editing = false;
      this.EditingIndex = -1;
      this.orderItem = null;
      ListItemHTML = "<tr class = 'fl-cart-item'>\n    <td><span  class = 'address'><%= address %></span></td>\n    <td><span class = 'price'?>$<%= price %></span></td>\n    <td class = 'actions'>\n        <a href = '#' class = 'edit' id = '<%= id %>'><i class = 'icon-edit'></i></a>   \n        <a href = '#' class = 'remove' id = '<%= id %>'><i class = 'icon-remove-circle'></i></a>\n    </td>\n</tr>";
      this.ListItemTemplate = _.template(ListItemHTML);
      this.refresh();
    }

    ShoppingCart.prototype.remove = function(index) {
      var data, url,
        _this = this;
      url = myBaseUrl + "shoppingCart/remove";
      data = {
        'index': index
      };
      return $.post(url, data, function(response) {
        var success;
        success = JSON.parse(response).success;
        if (success) {
          return _this.refresh();
        } else {
          return alertify.error("Removing item " + (index + 1) + " failed");
        }
      });
    };

    ShoppingCart.prototype.refresh = function() {
      var _this = this;
      return $.getJSON('/shoppingCart/get', function(orderItems) {
        var data, html, i, oi, _i, _len;
        $('.orderItems > tbody').html('');
        html = "";
        i = 0;
        for (_i = 0, _len = orderItems.length; _i < _len; _i++) {
          oi = orderItems[_i];
          data = {
            price: oi.price.toFixed(2),
            address: oi.item.address,
            id: i++
          };
          html += _this.ListItemTemplate(data);
        }
        $('.orderItems > tbody').html(html);
        $('table.orderItems').show();
        return _this.orderItems = orderItems;
      });
    };

    ShoppingCart.prototype.edit = function(index) {
      var fl, _ref;
      fl = this.orderItems[index];
      if ((_ref = this.orderItem) != null) _ref.clear();
      this.orderItem = new A2Cribs.Order.FeaturedListing($('.featured-listing-order-item').first(), fl.item.listing_id, fl.item.address, {
        selected_dates: fl.item.dates
      });
      $('.edit-form').fadeIn('fast');
      this.EditingIndex = index;
      return $(".fl-cart-item:eq(" + index + ")").addClass('editing').siblings().removeClass('editing');
    };

    ShoppingCart.prototype.save = function() {
      var data,
        _this = this;
      if (this.EditingIndex >= 0) {
        data = {
          orderItem: JSON.stringify(this.orderItem.getOrderItem()),
          index: this.EditingIndex
        };
        return $.post('/shoppingCart/edit', data, function(response) {
          data = JSON.parse(response);
          if (data.success) {
            alertify.success("Save Successful");
            _this.Widget.find('.hide-edit').click();
            return _this.refresh();
          } else {
            return alertify.error(data.message);
          }
        });
      }
    };

    return ShoppingCart;

  })();

}).call(this);
