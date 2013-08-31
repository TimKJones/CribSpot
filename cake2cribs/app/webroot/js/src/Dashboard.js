(function() {
  var __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  A2Cribs.Dashboard = (function() {

    function Dashboard() {}

    Dashboard.SetupUI = function() {
      var list_content_height,
        _this = this;
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
      $("#feature-btn").click(function(event) {
        return _this.Direct({
          'classname': 'featured-listing'
        });
      });
      $("#create-listing").find("a").click(function(event) {
        A2Cribs.MarkerModal.NewMarker();
        return A2Cribs.MarkerModal.Open();
      });
      $("body").on('click', '.messages_list_item', function(event) {
        return _this.ShowContent($('.messages-content'));
      });
      list_content_height = $("#navigation-bar").parent().height() - $("#navigation-bar").height() - 68;
      $(".list_content").css("height", list_content_height + "px");
      /*
      		Search listener
      */
      $('.dropdown-search').keyup(function(event) {
        var list;
        list = $(event.delegateTarget).attr("data-filter-list");
        return $("" + list + " li").show().filter(function() {
          if ($(this).text().toLowerCase().indexOf($(event.delegateTarget).val().toLowerCase()) === -1) {
            return true;
          }
          return false;
        }).hide();
      });
      return this.GetUserMarkerData();
    };

    /*
    	Retrieves all basic marker_data for the logged in user and updates nav bar in dashboard
    */

    Dashboard.GetUserMarkerData = function() {
      var url,
        _this = this;
      url = myBaseUrl + "listings/GetMarkerDataByLoggedInUser";
      return $.get(url, function(data) {
        var list_item, listing_types, listings_count, marker, marker_ids_processed, markers, name, _i, _len, _ref, _results;
        markers = JSON.parse(data);
        /*
        			for item in response_data
        				for key, value of item
        					if A2Cribs[key]?
        						A2Cribs.UserCache.Set new A2Cribs[key] value
        					else if A2Cribs[key]? and value.length? # Is an array
        						for i in value
        							A2Cribs.UserCache.Set new A2Cribs[key] i
        */
        listings_count = [0, 0, 0];
        listing_types = ["rentals", "sublet", "parking"];
        $("#rentals_count").text(markers.length);
        marker_ids_processed = [];
        _results = [];
        for (_i = 0, _len = markers.length; _i < _len; _i++) {
          marker = markers[_i];
          if (marker.Marker != null) {
            marker = marker.Marker;
          } else {
            continue;
          }
          if ((marker.marker_id != null) && (_ref = marker.marker_id, __indexOf.call(marker_ids_processed, _ref) >= 0)) {
            continue;
          }
          name = marker.alternate_name;
          if (!marker.alternate_name || !marker.alternate_name.length) {
            name = marker.street_address;
          }
          list_item = $("<li />", {
            text: name,
            "class": "rentals_list_item",
            id: marker.marker_id
          });
          $("#rentals_list_content").append(list_item);
          _results.push(marker_ids_processed.push(marker.marker_id));
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
