
/*
SmallBubble class
Wrapper for google infobubble
*/

(function() {
  var SmallBubble;

  SmallBubble = (function() {
    var _this = this;

    function SmallBubble() {}

    /*
    	When the map is initialized, call init for the map
    */

    $(document).ready(function() {
      return $("#map_region").on("map_initialized", function(event, map) {
        return SmallBubble.Init(map);
      });
    });

    /*
    	Constructor
    	-creates infobubble object
    */

    SmallBubble.Init = function(map) {
      var obj,
        _this = this;
      this.template = $(".small-bubble:first").parent();
      obj = {
        map: map,
        arrowStyle: 0,
        arrowPosition: 20,
        shadowStyle: 0,
        borderRadius: 5,
        arrowSize: 17,
        borderWidth: 0,
        disableAutoPan: true,
        padding: 0,
        disableAnimation: true
      };
      this.InfoBubble = new InfoBubble(obj);
      this.InfoBubble.hideCloseButton();
      this.InfoBubble.setBackgroundClassName("map_bubble");
      $("#map_region").on("marker_clicked", function(event, marker) {
        return _this.Open(marker);
      });
      $("#map_region").on('close_bubbles', function() {
        return _this.Close();
      });
      return this.template.find(".close_button").attr("onclick", "$('#map_region').trigger('close_bubbles');");
    };

    /*
    	Opens the tooltip given a marker, with popping animation
    */

    SmallBubble.Open = function(marker) {
      $("#map_region").trigger('close_bubbles');
      if (marker != null) {
        marker.IsVisible(true);
        this.SetContent(marker);
        return this.InfoBubble.open(A2Cribs.Map.GMap, marker.GMarker);
      }
    };

    /*
    	Refreshes the tooltip with the new content, no animation
    */

    SmallBubble.Refresh = function() {
      return this.InfoBubble.open();
    };

    /*
    	Closes the tooltip, no animation
    */

    SmallBubble.Close = function() {
      return this.InfoBubble.close();
    };

    /*
    	Sets the content of the tooltip
    */

    SmallBubble.SetContent = function(marker) {
      var available_dot, bed_count, bed_desc, codes, k, listing, listing_info, listings, sortedCodes, sortedListings, unit_template, _i, _len;
      listings = A2Cribs.UserCache.GetAllAssociatedObjects("listing", "marker", marker.GetId());
      this.template.find(".building_type").text(marker.GetBuildingType());
      this.template.find(".unit_div").empty();
      sortedListings = listings.sort(function(a, b) {
        var listing_a, listing_b;
        listing_a = A2Cribs.UserCache.Get(A2Cribs.Map.ACTIVE_LISTING_TYPE, a.GetId());
        listing_b = A2Cribs.UserCache.Get(A2Cribs.Map.ACTIVE_LISTING_TYPE, b.GetId());
        if (!(listing_a.rent != null) && !(listing_b.rent != null)) {
          return 0;
        } else if ((listing_a.rent != null) && !(listing_b.rent != null)) {
          return 1;
        } else if (!(listing_a.rent != null) && (listing_b.rent != null)) {
          return -1;
        }
        return parseInt(listing_a.rent, 10) - parseInt(listing_b.rent, 10);
      });
      for (_i = 0, _len = sortedListings.length; _i < _len; _i++) {
        listing = sortedListings[_i];
        if (listing.InSidebar() || listing.IsVisible()) {
          listing_info = A2Cribs.UserCache.Get(A2Cribs.Map.ACTIVE_LISTING_TYPE, listing.GetId());
          codes = (function() {
            var _results;
            _results = [];
            for (k in listings) {
              _results.push(k);
            }
            return _results;
          })();
          sortedCodes = codes.sort(function(a, b) {
            return listings[b] - listings[a];
          });
          bed_count = listing_info.beds;
          bed_desc = "Beds";
          if (!(listing_info["beds"] != null)) {
            bed_count = "?";
          } else if (parseInt(listing_info["beds"], 10) === 0) {
            bed_count = "Studio";
            bed_desc = "";
          } else if (parseInt(listing_info["beds"], 10) === 1) {
            bed_desc = "Bed";
          }
          available_dot = "unknown";
          if ((listing.available != null) && listing.available === true) {
            available_dot = "available";
          } else if ((listing.available != null) && listing.available !== true) {
            available_dot = "leased";
          }
          unit_template = $("<div />", {
            "class": "unit"
          });
          unit_template.attr("onclick", "$('#map_region').trigger('listing_click', [" + (listing.GetId()) + "])");
          $("<div />", {
            "class": "dot " + available_dot
          }).appendTo(unit_template);
          $("<div />", {
            "class": "beds",
            text: bed_count
          }).appendTo(unit_template);
          $("<div />", {
            "class": "bed_desc",
            text: bed_desc
          }).appendTo(unit_template);
          $("<div />", {
            "class": "rent",
            text: (listing_info["rent"] != null) && parseInt(listing_info["rent"], 10) !== 0 ? "$" + listing_info["rent"] : "Contact"
          }).appendTo(unit_template);
          this.template.find(".unit_div").append(unit_template);
        }
      }
      return this.InfoBubble.setContent(this.template.html());
    };

    SmallBubble.resolveDate = function(minDate, maxDate) {
      var maxSplit, minSplit;
      minSplit = minDate.split("-");
      maxSplit = maxDate.split("-");
      return +minSplit[1] + "/" + +minSplit[2] + "-" + +maxSplit[1] + "/" + +maxSplit[2];
    };

    return SmallBubble;

  }).call(this);

}).call(this);
