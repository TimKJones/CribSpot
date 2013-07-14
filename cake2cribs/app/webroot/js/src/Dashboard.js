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
      return this.GetListings();
    };

    Dashboard.GetListings = function() {
      var url,
        _this = this;
      url = myBaseUrl + "listings/GetListing";
      return $.get(url, function(data) {
        var list_item, listing_markers, marker, marker_set, name, response_data, type, _results;
        response_data = JSON.parse(data);
        A2Cribs.UserCache.CacheListings(response_data);
        listing_markers = A2Cribs.UserCache.GetListingMarkers();
        _results = [];
        for (type in listing_markers) {
          marker_set = listing_markers[type];
          _results.push((function() {
            var _i, _len, _results1;
            _results1 = [];
            for (_i = 0, _len = marker_set.length; _i < _len; _i++) {
              marker = marker_set[_i];
              name = (marker.alternate_name != null) && marker.alternate_name.length ? marker.alternate_name : marker.street_address;
              list_item = $("<li />", {
                text: name,
                "class": "" + type + "_list_item",
                id: marker.marker_id
              });
              _results1.push($("#" + type + "_list").append(list_item));
            }
            return _results1;
          })());
        }
        return _results;
      });
    };

    Dashboard.SizeContent = function() {
      var main_content, middle_content;
      main_content = $('#main_content');
      middle_content = $('#middle_content');
      return main_content.css('height', Math.max(window.innerHeight - main_content.offset().top, 750) + 'px');
    };

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
      return content.removeClass('hidden');
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
