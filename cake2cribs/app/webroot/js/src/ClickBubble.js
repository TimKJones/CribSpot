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
      var listing, marker, nw, scale, worldCoordinate, worldCoordinateNW;
      listing = A2Cribs.UserCache.Get("listing", listing_id);
      marker = A2Cribs.UserCache.Get("marker", listing.marker_id).GMarker;
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
      var _this = this;
      this.map = map;
      this.div = $(".click-bubble:first");
      return this.div.find(".close_button").click(function() {
        return _this.Close();
      });
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
              var item, key, response_data, value, _i, _len;
              response_data = JSON.parse(data);
              for (_i = 0, _len = response_data.length; _i < _len; _i++) {
                item = response_data[_i];
                for (key in item) {
                  value = item[key];
                  if (key !== "Marker" && (A2Cribs[key] != null)) {
                    A2Cribs.UserCache.Set(new A2Cribs[key](value));
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


    ClickBubble.SetContent = function(listing_object) {
      var key, marker, value;
      for (key in listing_object) {
        value = listing_object[key];
        this.div.find("." + key).text(value);
      }
      this.div.find(".date_range").text(this.resolveDateRange(listing_object.start_date));
      marker = A2Cribs.UserCache.Get("marker", A2Cribs.UserCache.Get("listing", listing_object.listing_id).marker_id);
      this.div.find(".building_name").text(marker.GetName());
      this.div.find(".unit_type").text(marker.GetBuildingType());
      this.linkWebsite(".website_link", listing_object.website);
      this.setAvailability("available", listing_object.available);
      this.setOwnerName("property_manager", listing_object.listing_id);
      this.setPrimaryImage("property_image", listing_object.listing_id);
      this.setFullPage("full_page_link", listing_object.listing_id);
      this.setFullPageContact("full_page_contact", listing_object.listing_id);
      this.div.find(".facebook_share").click(function() {
        return A2Cribs.ShareManager.ShareListingOnFacebook(listing_object.listing_id, marker.street_address, marker.city, marker.state, marker.zip);
      });
      this.div.find(".link_share").click(function() {
        return A2Cribs.ShareManager.CopyListingUrl(listing_object.listing_id, marker.street_address, marker.city, marker.state, marker.zip);
      });
      this.div.find(".twitter_share").click(function() {
        return A2Cribs.ShareManager.ShareListingOnTwitter(listing_object.listing_id, marker.street_address, marker.city, marker.state, marker.zip);
      });
      return this.setFavoriteButton("favorite_listing", listing_object.listing_id, A2Cribs.FavoritesManager.FavoritesListingIds);
    };

    ClickBubble.resolveDateRange = function(startDate) {
      var range, rmonth, startSplit;
      rmonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      range = "";
      startSplit = startDate.split("-");
      return range = "" + rmonth[+startSplit[1] - 1] + " " + (parseInt(startSplit[2], 10)) + ", " + startSplit[0];
    };

    ClickBubble.setAvailability = function(div_name, availability) {
      if (availability) {
        $("." + div_name).text("Available");
        return $("." + div_name).removeClass("leased");
      } else {
        $("." + div_name).text("Leased");
        return $("." + div_name).addClass("leased");
      }
    };

    ClickBubble.linkWebsite = function(div_name, link) {
      if (link.indexOf("http" === -1)) {
        link = "http://" + link;
      }
      return this.div.find(div_name).attr("href", link);
    };

    ClickBubble.setOwnerName = function(div_name, listing_id) {
      var listing, user;
      listing = A2Cribs.UserCache.Get("listing", listing_id);
      user = A2Cribs.UserCache.Get("user", listing.user_id);
      if ((user != null ? user.company_name : void 0) != null) {
        $("." + div_name).text(user.company_name);
      } else if (((user != null ? user.first_name : void 0) != null) && user.last_name) {
        $("." + div_name).text("" + user.first_name + " " + user.last_name);
      }
      if (user != null ? user.verified : void 0) {
        return this.div.find(".verified").show();
      } else {
        return this.div.find(".verified").hide();
      }
    };

    ClickBubble.setPrimaryImage = function(div_name, listing_id) {
      var image_url;
      image_url = A2Cribs.UserCache.Get("image", listing_id).GetPrimary();
      if (image_url != null) {
        return $("." + div_name).css("background-image", "url(/" + image_url + ")");
      }
    };

    ClickBubble.setFullPage = function(div_name, listing_id) {
      var link;
      link = "/listings/view/" + listing_id;
      return $("." + div_name).attr("href", link);
    };

    ClickBubble.setFullPageContact = function(div_name, listing_id) {
      var link;
      link = "/messages/contact/" + listing_id;
      return $("." + div_name).attr("href", link);
    };

    ClickBubble.setFavoriteButton = function(div_name, listing_id, favorites_list) {
      if (favorites_list.indexOf(parseInt(listing_id, 10)) === -1) {
        $("." + div_name).attr("onclick", "A2Cribs.FavoritesManager.AddFavorite(" + listing_id + ", this)");
        return $("." + div_name).removeClass("active");
      } else {
        $("." + div_name).attr("onclick", "A2Cribs.FavoritesManager.DeleteFavorite(" + listing_id + ", this)");
        return $("." + div_name).addClass("active");
      }
    };

    return ClickBubble;

  }).call(this);

}).call(this);
