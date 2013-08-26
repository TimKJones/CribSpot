
/*
HoverBubble class
Wrapper for google infobubble
*/

(function() {

  A2Cribs.HoverBubble = (function() {

    function HoverBubble() {}

    /*
    	Constructor
    	-creates infobubble object
    */

    HoverBubble.Init = function(map) {
      var obj;
      this.template = $(".hover-bubble:first").parent();
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
      return this.template.find(".close_button").attr("onclick", "A2Cribs.HoverBubble.Close();");
    };

    /*
    	Opens the tooltip given a marker, with popping animation
    */

    HoverBubble.Open = function(marker) {
      var _ref;
      A2Cribs.MixPanel.Click(marker, 'small popup');
      this.Close();
      if ((_ref = A2Cribs.ClickBubble) != null) _ref.Close();
      if (marker) {
        this.SetContent(marker);
        return this.InfoBubble.open(A2Cribs.Map.GMap, marker.GMarker);
      }
    };

    /*
    	Refreshes the tooltip with the new content, no animation
    */

    HoverBubble.Refresh = function() {
      return this.InfoBubble.open();
    };

    /*
    	Closes the tooltip, no animation
    */

    HoverBubble.Close = function() {
      return this.InfoBubble.close();
    };

    /*
    	Sets the content of the tooltip
    */

    HoverBubble.SetContent = function(marker) {
      var codes, k, listing, listing_info, listings, sortedCodes, sortedListings, unit_template, _i, _len;
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
        if (!(listing.visible != null) || listing.visible) {
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
          if (!(listing_info["beds"] != null)) {
            listing_info["beds"] = "??";
            listing_info["bed_desc"] = "Beds";
          } else if (parseInt(listing_info["beds"], 10) === 0) {
            listing_info["beds"] = "Studio";
            listing_info["bed_desc"] = "";
          } else if (parseInt(listing_info["beds"], 10) === 1) {
            listing_info["bed_desc"] = "Bed";
          } else {
            listing_info["bed_desc"] = "Beds";
          }
          unit_template = $("<div />", {
            "class": "unit"
          });
          unit_template.attr("onclick", "A2Cribs.ClickBubble.Open(" + (listing.GetId()) + ")");
          $("<div />", {
            "class": "beds",
            text: listing_info["beds"]
          }).appendTo(unit_template);
          $("<div />", {
            "class": "bed_desc",
            text: listing_info["bed_desc"]
          }).appendTo(unit_template);
          $("<div />", {
            "class": "rent",
            text: listing_info["rent"] != null ? "$" + listing_info["rent"] : "??"
          }).appendTo(unit_template);
          this.template.find(".unit_div").append(unit_template);
        }
      }
      return this.InfoBubble.setContent(this.template.html());
    };

    HoverBubble.resolveDate = function(minDate, maxDate) {
      var maxSplit, minSplit;
      minSplit = minDate.split("-");
      maxSplit = maxDate.split("-");
      return +minSplit[1] + "/" + +minSplit[2] + "-" + +maxSplit[1] + "/" + +maxSplit[2];
    };

    return HoverBubble;

  })();

}).call(this);
