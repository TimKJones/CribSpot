(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  A2Cribs.RentalFilter = (function(_super) {
    var loadPreviewText;

    __extends(RentalFilter, _super);

    function RentalFilter() {
      RentalFilter.__super__.constructor.apply(this, arguments);
    }

    RentalFilter.FilterData = {};

    /*
    	Private method for loading the contents of the filter preview into the header filter
    */

    loadPreviewText = function(div, text) {
      var title;
      title = $(div).closest(".filter_content").attr("data-link");
      return $(title).find(".filter_preview").html(text);
    };

    RentalFilter.CreateListeners = function() {
      var _this = this;
      $("#filter_search_content").keyup(function(event) {
        if (event.keyCode === 13) {
          A2Cribs.FilterManager.SearchForAddress(event.delegateTarget);
          return $(event.delegateTarget).select();
        }
      });
      /*
      		On Change listeners for applying changed fields
      */
      this.div.find(".lease_slider").on("slideStop", function(event) {
        return _this.ApplyFilter("LeaseRange", {
          min: parseInt(event.value[0], 10),
          max: parseInt(event.value[1], 10)
        });
      });
      this.div.find(".rent_slider").on("slideStop", function(event) {
        return _this.ApplyFilter("Rent", {
          min: parseInt(event.value[0], 10),
          max: parseInt(event.value[1], 10)
        });
      });
      /*
      		Bed filter click event listener
      		Finds range of beds and applies the changes of bed amounts
      */
      this.div.find("#bed_filter").find(".btn").click(function(event) {
        var button_group, max, min, selected_list, text;
        selected_list = [];
        min = 1000;
        max = -1;
        $(event.delegateTarget).toggleClass("active");
        button_group = $(event.delegateTarget).parent();
        button_group.find(".btn.active").each(function() {
          var val;
          val = parseInt($(this).val(), 10);
          selected_list.push(val);
          min = Math.min(min, val);
          return max = Math.max(max, val);
        });
        if (selected_list.length === 0) {
          loadPreviewText(event.delegateTarget, "");
          return _this.ApplyFilter("Beds", null);
        } else {
          _this.ApplyFilter("Beds", selected_list);
          if (selected_list.length === 1) {
            if (min === 0) {
              text = "<div class='filter_data'>Studio</div>";
            } else if (min === 1) {
              text = "<div class='filter_data'>" + min + "</div><div class='filter_label'>&nbsp;bed</div>";
            } else {
              text = "<div class='filter_data'>" + min + "</div><div class='filter_label'>&nbsp;beds</div>";
            }
            return loadPreviewText(event.delegateTarget, text);
          } else {
            return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + min + "-" + max + "</div><div class='filter_label'>&nbsp;beds</div>");
          }
        }
      });
      this.div.find("#year_filter").change(function(event) {
        var dates, year;
        dates = _this.FilterData.Dates;
        year = $(event.delegateTarget).val();
        if (dates != null) {
          dates.year = year;
          return _this.ApplyFilter("Dates", dates);
        } else {
          return _this.ApplyFilter("Dates", {
            months: [],
            year: year
          });
        }
      });
      this.div.find("#start_filter").find(".btn").click(function(event) {
        var button_group, monthText, selected_list;
        selected_list = [];
        $(event.delegateTarget).toggleClass("active");
        button_group = $(event.delegateTarget).parent();
        monthText = "";
        button_group.find(".btn.active").each(function() {
          selected_list.push($(this).attr("data-month"));
          return monthText = $(this).text();
        });
        if (selected_list.length === 0) {
          loadPreviewText(event.delegateTarget, "");
          return _this.ApplyFilter('Dates', null);
        } else {
          _this.ApplyFilter('Dates', {
            months: selected_list,
            year: _this.div.find("#year_filter").val()
          });
          if (selected_list.length === 1) {
            return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + monthText + "</div><div class='filter_label'>&nbsp;start</div>");
          } else {
            return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + selected_list.length + "</div><div class='filter_label'>&nbsp;starts</div>");
          }
        }
      });
      this.div.find("input[type='checkbox']").change(function(event) {
        var filterType, group, selected_list;
        group = $(event.target).closest(".filter_content");
        filterType = $(event.delegateTarget).attr("data-filter");
        selected_list = [];
        group.find("input[type='checkbox']").each(function() {
          if (this.checked) return selected_list.push($(this).attr("data-value"));
        });
        if (filterType === "UnitTypes") {
          _this.ApplyFilter(filterType, selected_list);
        } else {
          _this.ApplyFilter(filterType, +event.delegateTarget.checked);
        }
        if (selected_list.length === 0) {
          return loadPreviewText(event.delegateTarget, "");
        } else {
          if (group.attr("id").indexOf("more") === -1) {
            if (selected_list.length === 1) {
              return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + selected_list.length + "</div><div class='filter_label'>&nbsp;type</div>");
            } else {
              return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + selected_list.length + "</div><div class='filter_label'>&nbsp;types</div>");
            }
          } else {
            return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + selected_list.length + "</div><div class='filter_label'>&nbsp;more</div>");
          }
        }
      });
      return this.div.find(".hidden_input").change(function(event) {
        var date, date_split, filter;
        console.log(event.currentTarget.value);
        date = _this.GetBackendDateFormat(event.currentTarget.value);
        filter = $(event.currentTarget).data("filter");
        _this.ApplyFilter(filter, date);
        date_split = event.currentTarget.value.split("/");
        if (filter.indexOf("Start") !== -1) {
          return $(event.currentTarget).parent().find(".filter_title").text("Starts: " + date_split[0] + "/" + date_split[1]);
        } else if (filter.indexOf("End") !== -1) {
          return $(event.currentTarget).parent().find(".filter_title").text("Ends: " + date_split[0] + "/" + date_split[1]);
        }
      });
    };

    /*
    	Creates all listeners and jquery events for RentalFilter
    */

    RentalFilter.SetupUI = function() {
      var _this = this;
      this.div = $("#map_filter");
      $(".hidden_input").datepicker({
        onClose: function(date) {
          return $(".filter_link").removeClass("active");
        }
      });
      $("#start_date_filter_link, #end_date_filter_link").click(function(event) {
        return $(event.currentTarget).find(".hidden_input").datepicker('show');
      });
      $("#filter_search_btn").click(function() {
        if ($("#filter_search_content").is(":visible")) {
          return $("#filter_search_content").hide('slide', {
            direction: 'left'
          }, 300);
        } else {
          $("#filter_search_content").show('slide', {
            direction: 'left'
          }, 300);
          return $("#filter_search_content").focus();
        }
      });
      this.div.find(".lease_slider").slider({
        min: 0,
        max: 12,
        step: 1,
        value: [0, 12],
        tooltip: 'hide'
      }).on("slide", function(event) {
        var max_desc;
        max_desc = event.value[1] > 1 ? "&nbsp;months" : "&nbsp;month";
        _this.div.find("#lease_min").text(event.value[0]);
        _this.div.find("#lease_min_desc").html(event.value[0] > 1 ? "&nbsp;months" : "&nbsp;month");
        _this.div.find("#lease_max").text(event.value[1]);
        _this.div.find("#lease_max_desc").html(max_desc);
        if (event.value[0] === event.value[1]) {
          return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + event.value[0] + "</div><div class='filter_label'>" + max_desc + "</div>");
        } else {
          return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + event.value[0] + "-" + event.value[1] + "</div><div class='filter_label'>" + max_desc + "</div>");
        }
      });
      this.div.find(".rent_slider").slider({
        min: 0,
        max: 5000,
        step: 100,
        value: [0, 5000],
        tooltip: 'hide'
      }).on("slide", function(event) {
        var max_amount, min_amount;
        min_amount = "$" + event.value[0];
        max_amount = event.value[1] === 5000 ? "$" + event.value[1] + "+" : "$" + event.value[1];
        _this.div.find("#rent_min").text(min_amount);
        _this.div.find("#rent_max").text(max_amount);
        return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + min_amount + "-" + max_amount + "</div>");
      });
      this.div.find(".filter_link").click(function(event) {
        var content, lastTab;
        content = $(event.delegateTarget).attr("data-filter");
        lastTab = _this.div.find(".filter_link.active");
        _this.div.find(".filter_link").removeClass("active");
        if (lastTab.length && lastTab.find(".filter_preview").html().length) {
          lastTab.find(".filter_title").hide();
          lastTab.find(".filter_preview").show();
        }
        if ($(lastTab).attr('id') !== $(event.delegateTarget).attr('id')) {
          $(event.delegateTarget).addClass("active");
          $(event.delegateTarget).find(".filter_preview").hide();
          $(event.delegateTarget).find(".filter_title").show();
        }
        return _this.div.find("#filter_dropdown").slideUp("fast", function() {
          _this.div.find(".filter_content").hide();
          if ($(lastTab).attr('id') !== $(event.delegateTarget).attr('id')) {
            _this.div.find(content).show();
            return _this.div.find("#filter_dropdown").slideDown();
          }
        });
      });
      this.div.find('#rentals-filter-label').click(function(event) {
        var lastTab;
        lastTab = _this.div.find(".filter_link.active");
        _this.div.find("#filter_dropdown").slideUp("fast");
        if (lastTab.length && lastTab.find(".filter_preview").html().length) {
          lastTab.find(".filter_title").hide();
          lastTab.find(".filter_preview").show();
        }
        return _this.div.find(".filter_link").removeClass("active");
      });
      return this.CreateListeners();
    };

    /*
    	Called immediately after user applies a filter.
    	Submits an ajax call with all current filter parameters
    */

    RentalFilter.ApplyFilter = function(field, value) {
      var ajaxData, first, key, _ref;
      if (value != null) {
        this.FilterData[field] = value;
      } else {
        delete this.FilterData[field];
      }
      ajaxData = '';
      first = true;
      _ref = this.FilterData;
      for (key in _ref) {
        value = _ref[key];
        if (!first) ajaxData += "&";
        first = false;
        ajaxData += key + "=" + JSON.stringify(value);
      }
      $("#loader").show();
      return $.ajax({
        url: myBaseUrl + ("Listings/ApplyFilter/" + A2Cribs.FilterManager.ActiveListingType),
        data: ajaxData,
        type: "GET",
        context: this,
        success: A2Cribs.FilterManager.UpdateListings,
        complete: function() {
          return $("#loader").hide();
        }
      });
    };

    /*
    	Retrieves all listing_ids for a given marker_id that fit the current filter criteria
    */

    RentalFilter.FilterVisibleListings = function(marker_id) {
      var amenities, baths, beds, building_type, dates, listing, listings, parking, pets, rent, square_feet, unit_features, utilities, visibile_listings, year_built, _i, _len;
      listings = A2Cribs.UserCache.GetAllAssociatedObjects("listing", "marker", marker_id);
      visibile_listings = [];
      for (_i = 0, _len = listings.length; _i < _len; _i++) {
        listing = listings[_i];
        rent = FilterRent(listing);
        beds = FilterBeds(listing);
        baths = FilterBaths(listing);
        building_type = FilterBuildingType(listing);
        dates = FilterDates(listing);
        unit_features = FilterUnitFeatures(listing);
        parking = FilterParking(listing);
        pets = FilterPets(listing);
        amenities = FilterAmenities(listing);
        square_feet = FilterSquareFeet(listing);
        year_built = FilterYearBuilt(listing);
        utilities = FilterUtilities(listing);
        if (rent && beds && baths && building_type && dates && unit_features && parking && pets && amenities && square_feet && year_built && utilities) {
          visibile_listings.push(listing);
        }
      }
      return visibile_listings;
    };

    /*
    	Get Backend Date Format
    	Replaces '/' with '-' to make convertible to db format
    */

    RentalFilter.GetBackendDateFormat = function(dateString) {
      var beginDateFormatted, date, day, month, year;
      date = new Date(dateString);
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = year + "-" + month + "-" + day;
    };

    return RentalFilter;

  })(A2Cribs.FilterManager);

}).call(this);
