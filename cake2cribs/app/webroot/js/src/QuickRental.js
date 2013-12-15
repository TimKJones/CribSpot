// Generated by CoffeeScript 1.4.0

/*
Quick Rental

Class for quick change of rentals.
Makes it easy to toggle availablity, pick start dates,
set rent price
*/


(function() {

  A2Cribs.QuickRental = (function() {
    var format_rent, validate_date,
      _this = this;

    function QuickRental() {}

    /*
    	Filter
    	Filters out the quick rentals based
    	on the search bar
    */


    QuickRental.Filter = function(event) {
      return QuickRental.div.find(".rental_preview").each(function(index, value) {
        if ($(value).find(".building_name").text().toLowerCase().indexOf($(event.currentTarget).val().toLowerCase()) !== -1) {
          if (!$(value).is(":visible")) {
            $(value).fadeIn();
          }
          return;
        }
        if ($(value).find(".street_address").text().toLowerCase().indexOf($(event.currentTarget).val().toLowerCase()) !== -1) {
          if (!$(value).is(":visible")) {
            $(value).fadeIn();
          }
          return;
        }
        $(value).fadeOut();
      });
    };

    /*
    	Format Rent
    	Private method to update the rent value and
    	format the rent correctly and cleanly
    */


    format_rent = function(rent_div) {
      var j, rent_amount, rent_string, _ref;
      rent_amount = parseInt((_ref = rent_div.val()) != null ? _ref.replace(/\D/g, '') : void 0, 10);
      if (isNaN(rent_amount)) {
        rent_amount = 0;
      }
      rent_amount = rent_amount.toString();
      rent_div.data("value", rent_amount);
      j = (j = rent_amount.length) > 3 ? j % 3 : 0;
      rent_string = "$" + (j ? rent_amount.substr(0, j) + "," : "");
      rent_string += rent_amount.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + ",");
      return rent_div.val(rent_amount !== "0" && rent_amount.length !== 0 ? rent_string : "");
    };

    /*
    	Validate Date
    	Private method to update the date value and
    	validate
    */


    validate_date = function(date_div) {
      var date, date_split, date_val, _i, _len;
      date_div.addClass("error");
      date = date_div.val();
      date_split = date.split("-");
      if (date_split.length !== 3) {
        return false;
      }
      for (_i = 0, _len = date_split.length; _i < _len; _i++) {
        date_val = date_split[_i];
        if (isNaN(date_val)) {
          return false;
        }
      }
      if (date_split[0] < 1 || date_split[0] > 12) {
        return false;
      }
      if (date_split[1] < 1 || date_split[1] > 31) {
        return false;
      }
      if (date_split[2] < 2013) {
        return false;
      }
      if (date_split[0].length === 1) {
        date_split[0] = "0" + date_split[0];
      }
      if (date_split[1].length === 1) {
        date_split[1] = "0" + date_split[1];
      }
      date_div.data("value", "" + date_split[2] + "-" + date_split[0] + "-" + date_split[1]);
      date_div.removeClass("error");
      return true;
    };

    /*
    	Check Marker Availabilty
    	Takes a rental_preview div and finds the availablity
    	of each listing attached to the marker and updates
    	the UI to show the count
    */


    QuickRental.CheckMarkerAvailabilty = function(rental_preview) {
      var available_count, listing, listings, _i, _len;
      listings = A2Cribs.UserCache.GetAllAssociatedObjects("listing", "marker", rental_preview.data("marker-id"));
      available_count = 0;
      for (_i = 0, _len = listings.length; _i < _len; _i++) {
        listing = listings[_i];
        if (listing.available) {
          available_count++;
        }
      }
      if (available_count === 0) {
        return rental_preview.find(".available_listing_count").text("Leased").addClass("leased");
      } else {
        return rental_preview.find(".available_listing_count").text("" + available_count + " of " + listings.length + " Available").removeClass("leased");
      }
    };

    /*
    	Create Listeners
    	Creates and fires save events for that rental/
    	listing
    */


    QuickRental.CreateListeners = function() {
      var _this = this;
      this.rent_timeouts = {};
      this.div.on('click', ".btn-group .btn", function(event) {
        if ($(event.currentTarget).parent().data('value') !== $(event.currentTarget).data('value')) {
          $(event.currentTarget).parent().data('value', $(event.currentTarget).data('value'));
          $(event.currentTarget).closest(".rental_edit").trigger("save_rental", [$(event.currentTarget).parent()]);
          return _this.CheckMarkerAvailabilty($(event.currentTarget).closest(".rental_preview"));
        }
      });
      this.div.on('keyup', ".rent", function(event) {
        var listing_id;
        format_rent($(event.currentTarget));
        listing_id = $(event.currentTarget).parent().data("listing-id");
        clearTimeout(_this.rent_timeouts[listing_id]);
        $(event.currentTarget).parent().find(".save-note").hide();
        $(event.currentTarget).parent().find(".not-saved").show();
        return _this.rent_timeouts[listing_id] = setTimeout(function() {
          return $(event.currentTarget).closest(".rental_edit").trigger("save_rental", [$(event.currentTarget)]);
        }, 1000);
      });
      this.div.on('keyup', ".start_date", function(event) {
        var date;
        date = $(event.currentTarget).data("value");
        if (validate_date($(event.currentTarget))) {
          if (date !== $(event.currentTarget).data("value")) {
            return $(event.currentTarget).closest(".rental_edit").trigger("save_rental", [$(event.currentTarget)]);
          }
        }
      });
      this.div.on('save_rental', '.rental_edit', function(event, input) {
        var a2_object, listing_id;
        listing_id = $(event.currentTarget).data("listing-id");
        a2_object = A2Cribs.UserCache.Get(input.data("object"), listing_id);
        a2_object[input.data("field")] = input.data("value");
        $(event.currentTarget).find(".save-note").hide();
        $(event.currentTarget).find(".not-saved").show();
        return _this.Save(listing_id).always(function() {
          $(event.currentTarget).find(".save-note").hide();
          return $(event.currentTarget).find(".saved").show();
        });
      });
      return this.div.on('keyup', '.search_rentals', this.Filter);
    };

    /*
    	Save
    	Sends a listing to the backend to be saved
    	Depends on many different deferreds. Will
    	reject a deferred if it is trying to be resaved
    	before the save is completed
    */


    QuickRental.Save = function(listing_id) {
      var listing, listing_object,
        _this = this;
      listing = A2Cribs.UserCache.Get("listing", listing_id);
      listing_object = listing.GetConnectedObject();
      return $.ajax({
        url: myBaseUrl + "listings/Save/",
        type: "POST",
        data: listing_object,
        success: function(response) {
          return console.log(response);
        }
      });
    };

    /*
    	Toggle Collapse
    	Collapses all or expands all rental divs
    */


    QuickRental.ToggleCollapse = function() {
      var _this = this;
      A2Cribs.UIManager.ShowLoader();
      return $.when(this.BackgroundLoadRentals()).done(function() {
        if (_this.div.find(".unit_list:visible").length === _this.div.find(".rental_preview").length) {
          _this.div.find(".unit_list").slideUp();
          _this.div.find(".toggle_text").hide();
          return _this.div.find(".show_listings").show();
        } else {
          _this.div.find(".unit_list").slideDown();
          _this.div.find(".toggle_text").hide();
          return _this.div.find(".hide_listings").show();
        }
      }).always(function() {
        return A2Cribs.UIManager.HideLoader();
      });
    };

    /*
    	Toggle Show Listings
    	Collapses all for that individual listing
    */


    QuickRental.ToggleShowListings = function(event) {
      var deferred, marker_id, url;
      if ($(event.currentTarget).parent().find(".unit_list").is(":visible")) {
        $(event.currentTarget).parent().find(".unit_list").slideUp();
        $(event.currentTarget).parent().find(".toggle_text").hide();
        $(event.currentTarget).parent().find(".show_listings").show();
        $(event.currentTarget).one('click', QuickRental.ToggleShowListings);
        return;
      }
      A2Cribs.UIManager.ShowLoader();
      deferred = $.Deferred();
      deferred.done(function(element) {
        A2Cribs.UIManager.HideLoader();
        element.find(".unit_list").slideDown();
        $(event.currentTarget).parent().find(".toggle_text").hide();
        $(event.currentTarget).parent().find(".hide_listings").show();
        return element.find(".rental_expand_toggle").one('click', QuickRental.ToggleShowListings);
      });
      if (QuickRental.BackgroundLoadRentals().state() === "resolved") {
        return deferred.resolve($(event.currentTarget).parent());
      } else {
        marker_id = $(event.currentTarget).parent().data("marker-id");
        url = "" + myBaseUrl + "Listings/GetOwnedListingsByMarkerId/" + marker_id;
        return $.ajax({
          url: url,
          type: "GET",
          success: function(data) {
            var _ref;
            A2Cribs.UserCache.CacheData(JSON.parse(data));
            if ((_ref = A2Cribs.UserCache.Get("marker", marker_id)) != null) {
              _ref.listings_loaded.resolve(marker_id, $(event.currentTarget).parent());
            }
            return deferred.resolve($(event.currentTarget).parent());
          }
        });
      }
    };

    /*
    	Load All Markers
    	Loads up all the marker owned by the property
    	manager into the quick rental view
    */


    QuickRental.LoadAllMarkers = function() {
      var marker, markers, _i, _len, _results;
      markers = A2Cribs.UserCache.Get("marker");
      _results = [];
      for (_i = 0, _len = markers.length; _i < _len; _i++) {
        marker = markers[_i];
        _results.push(this.AddMarker(marker));
      }
      return _results;
    };

    /*
    	Load All Rentals
    	Creates the UI for all the rentals in the
    	quick rental view by looping through all
    	the marker objects in the quick rental
    	view
    */


    QuickRental.LoadAllRentals = function() {
      var _this = this;
      return this.div.find(".rental_preview").each(function(index, value) {
        var marker_id, _ref;
        marker_id = $(value).data("marker-id");
        return (_ref = A2Cribs.UserCache.Get("marker", marker_id)) != null ? _ref.listings_loaded.resolve(marker_id, value) : void 0;
      });
    };

    /*
    	Background Load Rentals
    	Loads all the rentals in the background to appear
    	to property manager that the data is ready to 
    	use
    */


    QuickRental.BackgroundLoadRentals = function() {
      var url,
        _this = this;
      if (this.LoadRentalsDeferred != null) {
        return this.LoadRentalsDeferred;
      }
      this.LoadRentalsDeferred = $.Deferred();
      url = myBaseUrl + "Listings/GetListing";
      $.ajax({
        url: url,
        type: "GET",
        success: function(data) {
          A2Cribs.UserCache.CacheData(JSON.parse(data));
          return _this.LoadRentalsDeferred.resolve();
        },
        error: function() {
          return _this.LoadRentalsDeferred.reject();
        }
      });
      return this.LoadRentalsDeferred.promise();
    };

    /*
    	Add Marker
    	Adds marker to the quick rentals div
    */


    QuickRental.AddMarker = function(marker) {
      var listings, marker_row, marker_row_div,
        _this = this;
      listings = A2Cribs.UserCache.GetAllAssociatedObjects("listing", "marker", marker.GetId());
      marker_row = "<div class='rental_preview' data-marker-id='" + (marker.GetId()) + "' data-visible-state=\"hidden\">\n	<div class='rental_title'>\n		<span>\n			<span class='building_name'>" + (marker.GetName()) + "</span>\n		</span>\n		<span class='separator'>|</span>\n		<span class='street_address'>" + marker.street_address + "</span>\n		<span class='separator'>|</span>\n		<span class='building_type'>" + (marker.GetBuildingType()) + "</span>\n		<span class='pull-right available_listing_count'></span>\n	</div>\n	<div class='unit_list hide'>\n		<div class='fields_label'>\n			<div class='pull-left text-center listing_label'>Listing</div>\n			<div class='pull-left text-center available_label'>Availablity</div>\n			<div class='pull-left text-center rent_label'>Rent</div>\n			<div class='pull-left text-center start_date_label'>Start Date</div>\n			<div class='pull-right label_explained'>Where's the rest? <i class='icon-info-sign'></i></div>\n		</div>\n	</div>\n	<div class='rental_expand_toggle'>\n		<div class='show_listings toggle_text'>\n			<span><i class='icon-chevron-sign-down'></i> Click to view</span>\n			<span class='unit_count'>" + listings.length + "</span>\n			<span> Listings</span>\n		</div>\n		<div class='hide_listings hide toggle_text'>\n			<span><i class='icon-chevron-sign-up'></i> Hide these Listings</span>\n		</div>\n	</div>\n</div>";
      marker_row_div = $(marker_row);
      marker_row_div.find(".rental_expand_toggle").one('click', this.ToggleShowListings);
      this.div.find("#rental_preview_list").append(marker_row_div);
      marker.listings_loaded = $.Deferred();
      marker.listings_loaded.promise();
      return marker.listings_loaded.done(function(marker_id, value) {
        var listing, _i, _len;
        listings = A2Cribs.UserCache.GetAllAssociatedObjects("listing", "marker", marker_id);
        for (_i = 0, _len = listings.length; _i < _len; _i++) {
          listing = listings[_i];
          _this.AddRental(listing, $(value));
        }
        return _this.CheckMarkerAvailabilty(marker_row_div);
      });
    };

    /*
    	Add Rental
    	Adds rental to the rental preview div
    */


    QuickRental.AddRental = function(listing, container) {
      var date_split, date_string, div, listing_row, rental, _ref;
      rental = A2Cribs.UserCache.Get("rental", listing.GetId());
      if (rental != null) {
        date_split = (_ref = rental.start_date) != null ? _ref.split("-") : void 0;
        date_string = (date_split != null ? date_split.length : void 0) === 3 ? "" + date_split[1] + "-" + date_split[2] + "-" + date_split[0] : "";
        listing_row = "<div class=\"rental_edit\" data-listing-id=\"" + (listing.GetId()) + "\">\n	<span class=\"unit_description pull-left\">" + (rental.GetUnitStyle()) + " " + rental.unit_style_description + " - " + rental.beds + "Br</span>\n	<div class=\"btn-group pull-left\" data-toggle=\"buttons-radio\" data-object=\"listing\" data-field=\"available\" data-value=\"" + (listing.available ? "1" : "0") + "\">\n		<button type=\"button\" class=\"btn btn-available " + (listing.available ? "active" : "") + "\" data-value=\"1\">Available</button>\n		<button type=\"button\" class=\"btn btn-leased " + (!listing.available ? "active" : "") + "\" data-value=\"0\">Leased</button>\n	</div>\n	<input type=\"text\" class=\"rent\" placeholder=\"Rent\" data-object=\"rental\" data-field=\"rent\" data-value=\"" + rental.rent + "\" value=\"" + rental.rent + "\">\n	<input type=\"text\" class=\"start_date\" maxlength=\"10\" value=\"" + date_string + "\" data-object=\"rental\" data-field=\"start_date\" data-value=\"" + rental.start_date + "\" placeholder=\"MM-DD-YYYY\">\n	<span class=\"not-saved save-note hide\"><i class='icon-spinner icon-spin'></i> Saving...</span>\n	<span class=\"saved save-note hide\"><i class='icon-ok-sign'></i> Saved</span>\n</div>";
        div = $(listing_row);
        format_rent(div.find(".rent"));
        return container.find(".unit_list").append(div);
      }
    };

    /*
    	On Ready
    */


    $(document).ready(function() {
      if ($("#rental_quickedit").length) {
        QuickRental.div = $("#rental_quickedit");
        QuickRental._markers_loaded = $.Deferred();
        QuickRental._markers_loaded.promise();
        QuickRental.BackgroundLoadRentals();
        A2Cribs.Dashboard.GetUserMarkerData().done(function() {
          QuickRental.LoadAllMarkers();
          return QuickRental._markers_loaded.resolve();
        });
        $.when(QuickRental._markers_loaded, QuickRental.BackgroundLoadRentals()).done(function() {
          return QuickRental.LoadAllRentals();
        });
        return QuickRental.CreateListeners();
      }
    });

    return QuickRental;

  }).call(this);

}).call(this);
