// Generated by CoffeeScript 1.4.0
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
          $('.content-header.active').removeClass("active");
          $(event.delegateTarget).addClass("active");
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
      $("body").on('click', '.messages_list_item', function(event) {
        return _this.ShowContent($('.messages-content'));
      });
      return this.GetListings();
    };

    /*
    	Retrieves all listings for logged-in user and adds them to the cache.
    */


    Dashboard.GetListings = function() {
      var url,
        _this = this;
      url = myBaseUrl + "listings/GetListing";
      return $.get(url, function(data) {
        var i, item, key, list_item, listing, listing_type, listing_types, listings, listings_count, marker, marker_id, marker_id_array, marker_set, name, response_data, type, value, _i, _j, _k, _len, _len1, _len2, _results;
        response_data = JSON.parse(data);
        for (_i = 0, _len = response_data.length; _i < _len; _i++) {
          item = response_data[_i];
          for (key in item) {
            value = item[key];
            if (A2Cribs[key] != null) {
              A2Cribs.UserCache.Set(new A2Cribs[key](value));
            }
          }
        }
        listings = A2Cribs.UserCache.Get("listing");
        marker_set = {};
        for (_j = 0, _len1 = listings.length; _j < _len1; _j++) {
          listing = listings[_j];
          if (!(marker_set[listing.listing_type] != null)) {
            marker_set[listing.listing_type] = {};
          }
          marker_set[listing.listing_type][listing.marker_id] = true;
        }
        listings_count = [0, 0, 0];
        listing_types = ["rentals", "sublet", "parking"];
        for (listing_type in marker_set) {
          marker_id_array = marker_set[listing_type];
          for (marker_id in marker_id_array) {
            marker = A2Cribs.UserCache.Get("marker", marker_id);
            name = marker.GetName();
            type = listing_types[parseInt(listing_type, 10)];
            listings_count[parseInt(listing_type, 10)]++;
            list_item = $("<li />", {
              text: name,
              "class": "" + type + "_list_item",
              id: marker.marker_id
            });
            $("#" + type + "_list").append(list_item);
          }
        }
        _results = [];
        for (i = _k = 0, _len2 = listing_types.length; _k < _len2; i = ++_k) {
          type = listing_types[i];
          _results.push($("#" + type + "_count").text(listings_count[i]));
        }
        return _results;
      });
    };

    Dashboard.SizeContent = function() {};

    Dashboard.SlideDropDown = function(content_header, show_content) {
      var dropdown, toggle_icon;
      dropdown = content_header.next('.drop-down');
      if (dropdown.length === 0) {
        return;
      }
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
