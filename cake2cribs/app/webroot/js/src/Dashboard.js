(function() {

  A2Cribs.Dashboard = (function() {

    function Dashboard() {}

    Dashboard.SetupUI = function() {
      var _this = this;
      $(window).resize(function() {
        return _this.SizeContent();
      });
      this.SizeContent();
      $('.content-header').each(function(index, element) {
        var class_name, content, content_header;
        content_header = $(element);
        class_name = content_header.attr('classname');
        content = $('.' + class_name + '-content');
        $(element).click(function(event) {
          $(".list-dropdown").slideUp();
          if (content_header.hasClass("list-dropdown-header")) {
            return $("#" + class_name + "_list").slideDown();
          } else {
            return _this.ShowContent(content, true);
          }
        });
        return typeof content_header.next === "function" ? content_header.next('.drop-down').find('.drop-down-list').click(function() {
          return _this.ShowContent(content);
        }) : void 0;
      });
      $("#create-listing").find("a").click(function(event) {
        A2Cribs.MarkerModal.NewMarker();
        return A2Cribs.MarkerModal.Open();
      });
      return this.GetListings();
    };

    Dashboard.GetListings = function() {
      var url,
        _this = this;
      url = myBaseUrl + "listings/GetListing";
      return $.get(url, function(data) {
        var i, item, key, list_item, listing, listing_type, listings, marker, marker_id, marker_id_array, marker_set, name, response_data, type, value, _i, _j, _k, _len, _len2, _len3, _results;
        response_data = JSON.parse(data);
        for (_i = 0, _len = response_data.length; _i < _len; _i++) {
          item = response_data[_i];
          for (key in item) {
            value = item[key];
            if ((A2Cribs[key] != null) && !(value.length != null)) {
              A2Cribs.UserCache.Set(new A2Cribs[key](value));
            } else if ((A2Cribs[key] != null) && (value.length != null)) {
              for (_j = 0, _len2 = value.length; _j < _len2; _j++) {
                i = value[_j];
                A2Cribs.UserCache.Set(new A2Cribs[key](i));
              }
            }
          }
        }
        listings = A2Cribs.UserCache.Get("listing");
        marker_set = {};
        for (_k = 0, _len3 = listings.length; _k < _len3; _k++) {
          listing = listings[_k];
          if (!(marker_set[listing.listing_type] != null)) {
            marker_set[listing.listing_type] = {};
          }
          marker_set[listing.listing_type][listing.marker_id] = true;
        }
        _results = [];
        for (listing_type in marker_set) {
          marker_id_array = marker_set[listing_type];
          _results.push((function() {
            var _results2;
            _results2 = [];
            for (marker_id in marker_id_array) {
              marker = A2Cribs.UserCache.Get("marker", marker_id);
              name = (marker.alternate_name != null) && marker.alternate_name.length ? marker.alternate_name : marker.street_address;
              type = null;
              if (parseInt(listing_type, 10) === 0) type = "rentals";
              if (parseInt(listing_type, 10) === 1) type = "sublet";
              if (parseInt(listing_type, 10) === 2) type = "parking";
              list_item = $("<li />", {
                text: name,
                "class": "" + type + "_list_item",
                id: marker.marker_id
              });
              _results2.push($("#" + type + "_list").append(list_item));
            }
            return _results2;
          })());
        }
        return _results;
      });
    };

    Dashboard.SizeContent = function() {};

    Dashboard.SlideDropDown = function(content_header, show_content) {
      var dropdown, toggle_icon;
      dropdown = content_header.next('.drop-down');
      if (dropdown.length === 0) return;
      toggle_icon = content_header.children('i')[0];
      $(toggle_icon).toggleClass('icon-caret-right', !show_content).toggleClass('icon-caret-down', show_content);
      $(content_header).toggleClass('shadowed', show_content).toggleClass('expanded', show_content).toggleClass('minimized', !show_content);
      if (show_content) {
        return dropdown.slideDown('fast');
      } else {
        return dropdown.slideUp('fast');
      }
    };

    Dashboard.ShowContent = function(content) {
      content.siblings().addClass('hidden');
      content.removeClass('hidden');
      return content.trigger('shown');
    };

    Dashboard.HideContent = function(classname) {
      return $("." + classname + "-content").addClass('hidden');
    };

    Dashboard.Direct = function(directive) {
      var content_header;
      content_header = $('#' + directive.classname + "-content-header");
      content_header.trigger('click');
      if (directive.data != null) {
        return this.ShowContent($('.' + directive.classname + "-content"));
      }
    };

    return Dashboard;

  })();

}).call(this);
