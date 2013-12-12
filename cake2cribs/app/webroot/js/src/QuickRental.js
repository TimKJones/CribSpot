// Generated by CoffeeScript 1.4.0

/*
Quick Rental

Class for quick change of rentals.
Makes it easy to toggle availablity, pick start dates,
set rent price
*/


(function() {

  A2Cribs.QuickRental = (function() {
    var _this = this;

    function QuickRental() {}

    /*
    	Filter
    	Filters out the quick rentals based
    	on the search bar
    */


    QuickRental.Filter = function() {};

    /*
    	Create Listeners
    	Creates and fires save events for that rental/
    	listing
    */


    QuickRental.CreateListeners = function() {
      var _this = this;
      this.div.on('click', ".btn-group .btn", function(event) {
        if ($(event.currentTarget).parent().data('value') !== $(event.currentTarget).data('value')) {
          $(event.currentTarget).parent().data('value', $(event.currentTarget).data('value'));
          return $(event.currentTarget).closest(".rental_edit").trigger("save_rental", [$(event.currentTarget).parent()]);
        }
      });
      return this.div.on('save_rental', '.rental_edit', function(event, input) {
        var a2_object, listing_id;
        listing_id = $(event.currentTarget).data("listing-id");
        a2_object = A2Cribs.UserCache.Get(input.data("object"), listing_id);
        a2_object[input.data("field")] = input.data("value");
        return _this.Save(listing_id);
      });
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
      return this.BackgroundLoadRentals().done(function() {
        if (false) {
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
      var deferred;
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
        /* GETTING CLOSER 
        			marker_id = $(event.currentTarget).parent().data("marker-id")
        			url = "#{myBaseUrl}Listings/GetOwnedListingsByMarkerId/#{marker_id}" 
        			$.ajax 
        				url: url
        				type:"GET"
        				success: (data) =>
        					# Load all of the data into the user cache
        					A2Cribs.UserCache.CacheData JSON.parse data
        					listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker", marker_id
        					for listing in listings
        						@AddRental listing, $(event.currentTarget).parent()
        					deferred.resolve $(event.currentTarget).parent()
        				error: =>
        					deferred.reject()
        */

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
        var listing, listings, marker_id, _i, _len, _results;
        marker_id = $(value).data("marker-id");
        listings = A2Cribs.UserCache.GetAllAssociatedObjects("listing", "marker", marker_id);
        _results = [];
        for (_i = 0, _len = listings.length; _i < _len; _i++) {
          listing = listings[_i];
          _results.push(_this.AddRental(listing, $(value)));
        }
        return _results;
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
      this.LoadRentalsDeferred = new $.Deferred();
      return this.LoadRentalsDeferred.promise();
    };

    /*
    	Add Marker
    	Adds marker to the quick rentals div
    */


    QuickRental.AddMarker = function(marker) {
      var listings, marker_row, marker_row_div;
      listings = A2Cribs.UserCache.GetAllAssociatedObjects("listing", "marker", marker.GetId());
      marker_row = "<div class='rental_preview' data-marker-id='" + (marker.GetId()) + "' data-visible-state=\"hidden\">\n	<div class='rental_title'>\n		<span>\n			<div class='marker_box pull-left'><i class='icon-map-marker'></i></div>&nbsp;\n			<span class='building_name'>" + (marker.GetName()) + "</span>\n		</span>\n		<span class='separator'>|</span>\n		<span class='street_address'>" + marker.street_address + "</span>\n		<span class='separator'>|</span>\n		<span class='building_type'>" + (marker.GetBuildingType()) + "</span>\n	</div>\n	<div class='unit_list hide'>\n	</div>\n	<div class='rental_expand_toggle'>\n		<div class='show_listings toggle_text'>\n			<span><i class='icon-chevron-sign-down'></i> Click to view</span>\n			<span class='unit_count'>" + listings.length + "</span>\n			<span> Listings</span>\n		</div>\n		<div class='hide_listings hide toggle_text'>\n			<span><i class='icon-chevron-sign-up'></i> Hide these Listings</span>\n		</div>\n	</div>\n</div>";
      marker_row_div = $(marker_row);
      marker_row_div.find(".rental_expand_toggle").one('click', this.ToggleShowListings);
      return this.div.find("#rental_preview_list").append(marker_row_div);
    };

    /*
    	Add Rental
    	Adds rental to the rental preview div
    */


    QuickRental.AddRental = function(listing, container) {
      var listing_row, rental;
      rental = A2Cribs.UserCache.Get("rental", listing.GetId());
      listing_row = "<div class=\"rental_edit\" data-listing-id=\"" + (listing.GetId()) + "\">\n	<span class=\"unit_description pull-left\">" + (rental.GetUnitStyle()) + " " + rental.unit_style_description + " - " + rental.beds + "Br</span>\n	<div class=\"btn-group pull-left\" data-toggle=\"buttons-radio\" data-object=\"listing\" data-field=\"available\" data-value=\"" + (listing.available ? "1" : "0") + "\">\n		<button type=\"button\" class=\"btn btn-available " + (listing.available ? "active" : "") + "\" data-value=\"1\">Available</button>\n		<button type=\"button\" class=\"btn btn-leased " + (!listing.available ? "active" : "") + "\" data-value=\"0\">Leased</button>\n	</div>\n	<input type=\"text\" class=\"rent\" placeholder=\"Rent\" value=\"" + rental.rent + "\" data-object=\"rental\">\n	<input type=\"text\" class=\"start_date\" placeholder=\"Lease Start Date\" value=\"" + rental.start_date + "\" data-object=\"rental\">\n	<span class=\"not-saved save-note hide\"><i class='icon-spinner icon-spin'></i> Saving...</span>\n	<span class=\"saved save-note hide\"><i class='icon-ok-sign'></i> Saved</span>\n	<button class=\"edit_rental pull-right btn btn-primary\">Edit</button>\n</div>";
      return container.find(".unit_list").append($(listing_row));
    };

    /*
    	On Ready
    */


    $(document).ready(function() {
      if ($("#rental_quickedit").length) {
        QuickRental.div = $("#rental_quickedit");
        QuickRental._markers_loaded = new $.Deferred();
        QuickRental._markers_loaded.promise();
        QuickRental.BackgroundLoadRentals();
        A2Cribs.Dashboard.GetUserMarkerData().done(function() {
          QuickRental.LoadAllMarkers();
          return QuickRental._markers_loaded.resolve();
        });
        return $.when(QuickRental._markers_loaded, QuickRental.BackgroundLoadRentals()).done(function() {
          return QuickRental.LoadAllRentals();
        });
      }
    });

    return QuickRental;

  }).call(this);

}).call(this);
