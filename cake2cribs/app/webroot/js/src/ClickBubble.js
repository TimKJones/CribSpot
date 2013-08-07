// Generated by CoffeeScript 1.4.0

/*
ClickBubble class
*/


(function() {

  A2Cribs.ClickBubble = (function() {
    var move_near_marker,
      _this = this;

    function ClickBubble() {}

    ClickBubble.OFFSET = {
      TOP: -190,
      LEFT: 140
    };

    /*
    	Private function that relocates the bubble near the marker
    */


    move_near_marker = function(listing_id) {
      var marker, nw, scale, worldCoordinate, worldCoordinateNW;
      marker = A2Cribs.UserCache.Get("marker", 1).GMarker;
      scale = Math.pow(2, ClickBubble.map.getZoom());
      nw = new google.maps.LatLng(ClickBubble.map.getBounds().getNorthEast().lat(), ClickBubble.map.getBounds().getSouthWest().lng());
      worldCoordinateNW = ClickBubble.map.getProjection().fromLatLngToPoint(nw);
      worldCoordinate = ClickBubble.map.getProjection().fromLatLngToPoint(marker.getPosition());
      return ClickBubble.div.offset({
        left: Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale) + ClickBubble.OFFSET.LEFT,
        top: Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale) + ClickBubble.OFFSET.TOP
      });
    };

    /*
    	Constructor
    */


    ClickBubble.Init = function(map) {
      this.map = map;
      return this.div = $(".click-bubble:first");
    };

    /*
    	Opens the tooltip given a marker, with popping animation
    */


    ClickBubble.Open = function(listing_id) {
      var listing,
        _this = this;
      if (listing_id != null) {
        listing = A2Cribs.UserCache.Get(A2Cribs.Map.ACTIVE_LISTING_TYPE, listing_id);
        if (listing.rental_id != null) {
          this.SetContent(listing.GetObject());
        } else {
          $.ajax({
            url: myBaseUrl + "Listings/GetListing/" + listing_id,
            type: "GET",
            success: function(data) {
              var i, item, key, response_data, value, _i, _j, _len, _len1;
              response_data = JSON.parse(data);
              for (_i = 0, _len = response_data.length; _i < _len; _i++) {
                item = response_data[_i];
                for (key in item) {
                  value = item[key];
                  if ((A2Cribs[key] != null) && !(value.length != null)) {
                    A2Cribs.UserCache.Set(new A2Cribs[key](value));
                  } else if ((A2Cribs[key] != null) && (value.length != null)) {
                    for (_j = 0, _len1 = value.length; _j < _len1; _j++) {
                      i = value[_j];
                      A2Cribs.UserCache.Set(new A2Cribs[key](i));
                    }
                  }
                }
              }
              listing = A2Cribs.UserCache.Get(A2Cribs.Map.ACTIVE_LISTING_TYPE, listing_id);
              return _this.SetContent(listing.GetObject());
            }
          });
        }
        move_near_marker(listing_id);
        return this.div.show('fade');
      }
    };

    /*
    	Refreshes the tooltip with the new content, no animation
    */


    ClickBubble.Refresh = function() {
      return this.div.show('fade');
    };

    /*
    	Closes the tooltip, no animation
    */


    ClickBubble.Close = function() {
      return this.div.hide('fade');
    };

    /*
    	Sets the content of the tooltip
    */


    ClickBubble.SetContent = function(listing) {
      var key, marker, value;
      for (key in listing) {
        value = listing[key];
        this.div.find("." + key).text(value);
      }
      this.div.find(".date_range").text(this.resolveDateRange(listing.start_date, listing.end_date));
      marker = A2Cribs.UserCache.Get("marker", A2Cribs.UserCache.Get("listing", listing.listing_id).marker_id);
      this.div.find(".building_name").text(marker.GetName());
      this.div.find(".unit_type").text(marker.GetBuildingType());
      this.div.find(".website_link").attr("href", listing.website);
      return this.setAvailability("available", listing.available);
    };

    ClickBubble.resolveDateRange = function(startDate, endDate) {
      var endSplit, range, rmonth, startSplit;
      rmonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      range = "";
      startSplit = startDate.split("-");
      endSplit = endDate.split("-");
      range = "" + rmonth[+startSplit[1] - 1] + " " + (parseInt(startSplit[2], 10)) + ", " + startSplit[0] + " - ";
      return range += "" + rmonth[+endSplit[1] - 1] + " " + (parseInt(endSplit[2], 10)) + ", " + endSplit[0];
    };

    ClickBubble.setAvailability = function(div_name, availability) {
      if (availability) {
        return $("." + div_name).text("Available");
      } else {
        return $("." + div_name).text("Leased");
      }
    };

    return ClickBubble;

  }).call(this);

}).call(this);
