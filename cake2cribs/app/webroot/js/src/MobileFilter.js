(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  A2Cribs.MobileFilter = (function(_super) {

    __extends(MobileFilter, _super);

    function MobileFilter() {
      MobileFilter.__super__.constructor.apply(this, arguments);
    }

    MobileFilter.FilterData = {};

    /*
    	Creates all listeners and jquery events for MobileFilter
    */

    MobileFilter.SetupUI = function() {
      var _this = this;
      this.div = $("#mobile_filter");
      this.div.find('select#listing_type').change(function(e) {
        var school_name;
        console.log($(e.target).val());
        school_name = _this.div.data('university-name');
        switch ($(e.target).val()) {
          case "Rentals":
            return window.location.href = "/rental/" + school_name;
          case "Sublets":
            return window.location.href = "/sublet/" + school_name;
        }
      });
      this.div.find('select#bedrooms').change(function(e) {
        var $opt, max, min, _i, _results;
        $opt = $(e.target).find('option:selected');
        min = parseInt($opt.data('min'), 10);
        max = parseInt($opt.data('max'), 10);
        return _this.ApplyFilter("Beds", (function() {
          _results = [];
          for (var _i = min; min <= max ? _i <= max : _i >= max; min <= max ? _i++ : _i--){ _results.push(_i); }
          return _results;
        }).apply(this));
      });
      return this.div.find('select#rent').change(function(e) {
        var $opt;
        $opt = $(e.target).find('option:selected');
        return _this.ApplyFilter("Rent", {
          min: parseInt($opt.data("min"), 10),
          max: parseInt($opt.data("max"), 10)
        });
      });
    };

    /*
    	Called immediately after user applies a filter.
    	Submits an ajax call with all current filter parameters
    */

    MobileFilter.ApplyFilter = function(field, value) {
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
    	Get Backend Date Format
    	Replaces '/' with '-' to make convertible to db format
    */

    MobileFilter.GetBackendDateFormat = function(dateString) {
      var beginDateFormatted, date, day, month, year;
      date = new Date(dateString);
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = year + "-" + month + "-" + day;
    };

    return MobileFilter;

  })(A2Cribs.FilterManager);

}).call(this);
