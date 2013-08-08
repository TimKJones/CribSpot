(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  A2Cribs.RentalFilter = (function(_super) {

    __extends(RentalFilter, _super);

    function RentalFilter() {
      RentalFilter.__super__.constructor.apply(this, arguments);
    }

    /*
    	Called immediately after user applies a filter.
    	Submits an ajax call with all current filter parameters
    */

    RentalFilter.ApplyFilter = function(event, ui) {
      var ajaxData, currentYears;
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
      ajaxData = "min_beds=" + 0;
      ajaxData += "&max_beds=" + 5;
      ajaxData += "&min_baths=" + 0;
      ajaxData += "&max_baths=" + 3;
      ajaxData += "&min_rent=" + 0;
      ajaxData += "&max_rent=" + 3000;
      ajaxData += "&unit_type_house=" + 1;
      ajaxData += "&unit_type_apartment=" + 1;
      ajaxData += "&unit_type_duplex=" + 1;
      ajaxData += "&unit_type_condo=" + 1;
      ajaxData += "&unit_type_townhouse=" + 1;
      ajaxData += "&unit_type_coop=" + 1;
      ajaxData += "&unit_type_other=" + 1;
      ajaxData += "&parking=" + 1;
      ajaxData += "&month_1=" + 1;
      ajaxData += "&month_2=" + 0;
      ajaxData += "&month_3=" + 0;
      ajaxData += "&month_4=" + 1;
      ajaxData += "&month_5=" + 0;
      ajaxData += "&month_6=" + 1;
      ajaxData += "&month_7=" + 0;
      ajaxData += "&month_8=" + 0;
      ajaxData += "&month_9=" + 1;
      ajaxData += "&month_10=" + 0;
      ajaxData += "&month_11=" + 0;
      ajaxData += "&month_12=" + 0;
      ajaxData += "&amenity_air=" + 1;
      ajaxData += "&month_12=" + 0;
      currentYears = [13];
      ajaxData += "&month_curYear=" + JSON.stringify(currentYears);
      ajaxData += "&month_leaseLength=" + 7;
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
