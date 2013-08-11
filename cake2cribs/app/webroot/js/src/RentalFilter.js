// Generated by CoffeeScript 1.4.0
(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  A2Cribs.RentalFilter = (function(_super) {
    var loadPreviewText;

    __extends(RentalFilter, _super);

    /*
    	Private method for loading the contents of the filter preview into the header filter
    */


    function RentalFilter() {
      return RentalFilter.__super__.constructor.apply(this, arguments);
    }

    loadPreviewText = function(div, text) {
      var title;
      title = $(div).closest(".filter_content").attr("data-link");
      return $(title).find(".filter_preview").html(text);
    };

    RentalFilter.CreateListeners = function() {
      /*
      		On Change listeners for applying changed fields
      */

      /*
      		Bed filter click event listener
      		Finds range of beds and applies the changes of bed amounts
      */

      var _this = this;
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
          return loadPreviewText(event.delegateTarget, "");
        } else if (selected_list.length === 1) {
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
      });
      this.div.find("#start_filter").find(".btn").click(function(event) {
        var button_group, selected_list;
        selected_list = [];
        $(event.delegateTarget).toggleClass("active");
        button_group = $(event.delegateTarget).parent();
        button_group.find(".btn.active").each(function() {
          return selected_list.push($(this).text());
        });
        if (selected_list.length === 0) {
          return loadPreviewText(event.delegateTarget, "");
        } else if (selected_list.length === 1) {
          return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + selected_list[0] + "</div><div class='filter_label'>&nbsp;start</div>");
        } else {
          return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + selected_list.length + "</div><div class='filter_label'>&nbsp;starts</div>");
        }
      });
      return this.div.find("input[type='checkbox']").change(function(event) {
        var group, selected_list;
        group = $(event.target).closest(".filter_content");
        selected_list = [];
        group.find("input[type='checkbox']").each(function() {
          if (this.checked) {
            return selected_list.push("0");
          }
        });
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
    };

    /*
    	Creates all listeners and jquery events for RentalFilter
    */


    RentalFilter.SetupUI = function() {
      var _this = this;
      this.div = $("#map_filter");
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
        $(event.delegateTarget).addClass("active");
        $(event.delegateTarget).find(".filter_preview").hide();
        $(event.delegateTarget).find(".filter_title").show();
        return _this.div.find("#filter_dropdown").slideUp("fast", function() {
          _this.div.find(".filter_content").hide();
          _this.div.find(content).show();
          return _this.div.find("#filter_dropdown").slideDown();
        });
      });
      return this.CreateListeners();
    };

    /*
    	Called immediately after user applies a filter.
    	Submits an ajax call with all current filter parameters
    */


    RentalFilter.ApplyFilter = function(event, ui) {
      var ajaxData;
      ajaxData = null;
      /*
      		ajaxData += "minBeds=" + $("#minBedsSelect").val()
      		ajaxData += "&maxBeds=" + $("#maxBedsSelect").val()
      		ajaxData += "&minBaths=" + $("#minBathsSelect").val()
      		ajaxData += "&maxBaths=" + $("#maxBathsSelect").val()
      		ajaxData += "&house=" + $("#houseCheck").is(':checked')
      		ajaxData += "&apt=" + $("#aptCheck").is(':checked')
      		ajaxData += "&duplex=" + $("#duplexCheck").is(':checked')
      		ajaxData += "&ac=" + $("#acCheck").is(':checked')
      		ajaxData += "&parking=" + $("#parkingCheck").is(':checked')
      */

      ajaxData += "&beds=" + this.GetBeds();
      ajaxData += "&rent=" + this.GetRent();
      ajaxData += "&parking=" + 1;
      ajaxData += "&dates=" + JSON.stringify(this.GetMonths());
      ajaxData += "&unit_types=" + JSON.stringify(this.GetUnitTypes());
      ajaxData += "&amenities=" + JSON.stringify(this.GetAmenities());
      return $.ajax({
        url: myBaseUrl + "Rentals/ApplyFilter",
        data: ajaxData,
        type: "GET",
        context: this,
        success: A2Cribs.FilterManager.UpdateMarkers
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

    RentalFilter.GetBeds = function() {
      var beds;
      beds = [3, 5, 6, 10];
      return JSON.stringify(beds);
    };

    RentalFilter.GetRent = function() {
      var rent;
      rent = {
        "min": 100,
        "max": 5000
      };
      return JSON.stringify(rent);
    };

    RentalFilter.GetMonths = function() {
      var dates;
      dates = {
        "months": {
          "1": 1,
          "2": 0,
          "3": 1,
          "4": 0,
          "5": 1,
          "6": 0,
          "7": 1,
          "8": 0,
          "9": 1,
          "10": 0,
          "11": 1,
          "12": 0
        },
        "curYear": [13, 14],
        "leaseLength": {
          'min': 2,
          'max': 4
        }
      };
      return dates;
    };

    RentalFilter.GetUnitTypes = function() {
      var unit_types;
      return unit_types = {
        "house": 0,
        "apartment": 1,
        "duplex": 1,
        "other": 0
      };
    };

    RentalFilter.GetAmenities = function() {
      var amenities;
      return amenities = {
        'elevator': 1
      };
    };

    RentalFilter.prototype.FilterRent = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterBeds = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterBaths = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterBuildingType = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterDates = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterUnitFeatures = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterParking = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterPets = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterAmenities = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterSquareFeet = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterYearBuilt = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterUtilities = function(listing) {
      return true;
    };

    return RentalFilter;

  })(A2Cribs.FilterManager);

}).call(this);
