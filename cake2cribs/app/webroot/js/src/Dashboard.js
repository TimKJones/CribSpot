// Generated by CoffeeScript 1.6.3
(function() {
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
          var _ref;
          if ((_ref = A2Cribs.RentalSave) != null ? _ref.Editable : void 0) {
            return A2Cribs.UIManager.ConfirmBox("By leaving this page, all unsaved changes will be lost.", {
              "ok": "Abort Changes & Continue",
              "cancel": "Return to Editor"
            }, function(success) {
              if (success) {
                A2Cribs.RentalSave.CancelEditing();
                return _this.ContentHeaderClick(event);
              }
            });
          } else {
            return _this.ContentHeaderClick(event);
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
      this.AttachListeners();
      return this.GetUserMarkerData();
    };

    /*
    	Attach Listeners
    	Attaches events listeners to objects
    */


    Dashboard.AttachListeners = function() {
      var _this = this;
      return $(".list_content").on("marker_added", function(event, marker_id) {
        var count, list_item, listing_type, name;
        listing_type = $(event.currentTarget).data("listing-type");
        if ($(event.currentTarget).find("#" + marker_id).length === 0) {
          name = A2Cribs.UserCache.Get("marker", marker_id).GetName();
          list_item = $("<li />", {
            text: name,
            "class": "" + listing_type + "_list_item",
            id: marker_id
          });
          count = $("#" + listing_type + "_count").text();
          $("#" + listing_type + "_count").text(parseInt(count, 10) + 1);
          $(event.currentTarget).append(list_item);
          return $(event.currentTarget).slideDown();
        }
      });
    };

    /*
    */


    Dashboard.ContentHeaderClick = function(event) {
      var class_name, content, content_header;
      content_header = $(event.delegateTarget);
      class_name = content_header.attr('classname');
      content = $('.' + class_name + '-content');
      $('.content-header.active').removeClass("active");
      $(event.delegateTarget).addClass("active");
      if (content_header.hasClass("list-dropdown-header")) {
        if (!$("#" + class_name + "_list").is(":visible")) {
          if ($(".list-dropdown.active").size() !== 0) {
            return $(".list-dropdown.active").removeClass("active").slideUp('fast', function() {
              return $("#" + class_name + "_list").addClass("active").slideDown();
            });
          } else {
            return $("#" + class_name + "_list").addClass("active").slideDown();
          }
        }
      } else {
        $(".list-dropdown").slideUp();
        return this.ShowContent(content, true);
      }
    };

    /*
    	Retrieves all basic marker_data for the logged in user and updates nav bar in dashboard
    */


    Dashboard.GetUserMarkerData = function() {
      var url;
      url = myBaseUrl + "listings/GetMarkerDataByLoggedInUser";
      return $.ajax({
        url: url,
        type: "GET",
        success: this.GetUserMarkerDataCallback
      });
    };

    Dashboard.GetUserMarkerDataCallback = function(data) {
      var i, list_item, listing, listing_type, listing_types, listings, listings_count, marker, _i, _j, _len, _len1, _results;
      listings_count = [0, 0, 0];
      listing_types = ["rental", "sublet", "parking"];
      A2Cribs.UserCache.CacheData(JSON.parse(data));
      listings = A2Cribs.UserCache.Get("listing");
      for (_i = 0, _len = listings.length; _i < _len; _i++) {
        listing = listings[_i];
        marker = A2Cribs.UserCache.Get("marker", listing.marker_id);
        if ($("#" + listing_types[listing.listing_type] + "_list_content").find("#" + (marker.GetId())).length === 0) {
          list_item = $("<li />", {
            text: marker.GetName(),
            "class": "" + listing_types[listing.listing_type] + "_list_item",
            id: marker.GetId()
          });
        }
        $("#" + listing_types[listing.listing_type] + "_list_content").append(list_item);
        listings_count[listing.listing_type] += 1;
      }
      _results = [];
      for (i = _j = 0, _len1 = listing_types.length; _j < _len1; i = ++_j) {
        listing_type = listing_types[i];
        _results.push($("#" + listing_type + "_count").text(listings_count[i]));
      }
      return _results;
    };

    /*
    	Retrieves all listings for logged-in user and adds them to the cache.
    
    	Returns a promise that will return the cache when complete.
    	This can be used by other module who want to know when the dashboard
    	has the listinngs loaded.
    */


    Dashboard.GetListings = function() {
      var url;
      if (this.DeferedListings == null) {
        this.DeferedListings = new $.Deferred();
      } else {
        return this.DeferedListings.promise();
      }
      url = myBaseUrl + "listings/GetListing";
      $.ajax({
        url: url,
        type: "GET",
        success: this.GetListingsCallback
      });
      return this.DeferedListings.promise();
    };

    Dashboard.GetListingsCallback = function(data) {
      var item, key, list_item, listing, listing_type, listing_types, listings, listings_count, marker, marker_id, marker_id_array, marker_set, name, response_data, type, value, _i, _j, _len, _len1, _results;
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
        if (marker_set[listing.listing_type] == null) {
          marker_set[listing.listing_type] = {};
        }
        marker_set[listing.listing_type][listing.marker_id] = true;
      }
      Dashboard.DeferedListings.resolve();
      listings_count = [0, 0, 0];
      listing_types = ["rentals", "sublet", "parking"];
      _results = [];
      for (listing_type in marker_set) {
        marker_id_array = marker_set[listing_type];
        _results.push((function() {
          var _results1;
          _results1 = [];
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
            _results1.push($("#" + type + "_list_content").append(list_item));
          }
          return _results1;
        })());
      }
      return _results;
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
      content.siblings().addClass('hidden').hide();
      content.removeClass('hidden').hide().fadeIn();
      return content.trigger('shown');
    };

    Dashboard.HideContent = function(classname) {
      return $("." + classname + "-content").addClass('hidden');
    };

    Dashboard.Direct = function(directive) {
      var content_header;
      content_header = $("#" + directive.classname + "-content-header");
      content_header.trigger('click');
      if (directive.data != null) {
        return this.ShowContent($("." + directive.classname + "-content"));
      }
    };

    return Dashboard;

  }).call(this);

}).call(this);
