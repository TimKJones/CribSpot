
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

    ClickBubble.IsOpen = false;

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
      ClickBubble.div.css("left", Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale) + ClickBubble.OFFSET.LEFT);
      return ClickBubble.div.css("top", Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale) + ClickBubble.OFFSET.TOP);
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
      this.IsOpen = true;
      if (listing_id != null) {
        listing = A2Cribs.UserCache.Get(A2Cribs.Map.ACTIVE_LISTING_TYPE, listing_id);
        A2Cribs.MixPanel.Click(listing, "large popup");
        if (listing.rental_id != null) {
          this.SetContent(listing.GetObject());
          return this.Show(listing_id);
        } else {
          return $.ajax({
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
              _this.SetContent(listing.GetObject());
              return _this.Show(listing_id);
            }
          });
        }
      }
    };

    ClickBubble.Show = function(listing_id) {
      this.IsOpen = true;
      move_near_marker(listing_id);
      return this.div.show('fade');
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
      this.IsOpen = false;
      return this.div.hide('fade');
    };

    ClickBubble.Clear = function() {
      return this.div.find(".clear_field").text("?").html("?").val("?");
    };

    /*
    	Sets the content of the tooltip
    */

    ClickBubble.SetContent = function(listing_object) {
      var key, marker, value;
      this.Clear();
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
      this.div.find(".share_btn").unbind("click");
      this.div.find(".facebook_share").click(function() {
        return A2Cribs.ShareManager.ShareListingOnFacebook(listing_object.listing_id, marker.street_address, marker.city, marker.state, marker.zip);
      });
      this.div.find(".link_share").click(function() {
        return A2Cribs.ShareManager.CopyListingUrl(listing_object.listing_id, marker.street_address, marker.city, marker.state, marker.zip);
      });
      this.div.find(".twitter_share").click(function() {
        return A2Cribs.ShareManager.ShareListingOnTwitter(listing_object.listing_id, marker.street_address, marker.city, marker.state, marker.zip);
      });
      return A2Cribs.FavoritesManager.setFavoriteButton("favorite_listing", listing_object.listing_id, A2Cribs.FavoritesManager.FavoritesListingIds);
    };

    ClickBubble.resolveDateRange = function(startDate) {
      var range, rmonth, startSplit;
      range = "Unknown Start Date";
      if (startDate != null) {
        rmonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        range = "";
        startSplit = startDate.split("-");
        range = "" + rmonth[+startSplit[1] - 1] + " " + (parseInt(startSplit[2], 10)) + ", " + startSplit[0];
      }
      return range;
    };

    ClickBubble.setAvailability = function(div_name, availability) {
      if (!(availability != null)) {
        return $("." + div_name).hide();
      } else if (availability) {
        $("." + div_name).show().text("Available");
        return $("." + div_name).removeClass("leased");
      } else {
        $("." + div_name).show().text("Leased");
        return $("." + div_name).addClass("leased");
      }
    };

    ClickBubble.linkWebsite = function(div_name, link) {
      if (link != null) {
        if (link.indexOf("http" === -1)) link = "http://" + link;
        this.div.find(div_name).attr("href", link);
        return this.div.find(div_name).attr("onclick", "");
      } else {
        return this.div.find(div_name).attr("onclick", "A2Cribs.UIManager.Error('This owner does not have a website for this listing')");
      }
    };

    ClickBubble.setOwnerName = function(div_name, listing_id) {
      var listing, user;
      listing = A2Cribs.UserCache.Get("listing", listing_id);
      user = A2Cribs.UserCache.Get("user", listing.user_id);
      if ((user != null ? user.company_name : void 0) != null) {
        $("." + div_name).show().text(user.company_name);
      } else if (((user != null ? user.first_name : void 0) != null) && user.last_name) {
        $("." + div_name).show().text("" + user.first_name + " " + user.last_name);
      } else {
        $("." + div_name).hide();
      }
      if (user != null ? user.verified : void 0) {
        return this.div.find(".verified").show();
      } else {
        return this.div.find(".verified").hide();
      }
    };

    ClickBubble.setPrimaryImage = function(div_name, listing_id) {
      var image_url;
      if (A2Cribs.UserCache.Get("image", listing_id) != null) {
        image_url = A2Cribs.UserCache.Get("image", listing_id).GetPrimary();
        if (image_url != null) {
          return $("." + div_name).css("background-image", "url(/" + image_url + ")");
        }
      } else {
        return $("." + div_name).css("background-image", "url(/img/tooltip/no_photo.jpg)");
      }
    };

    ClickBubble.setFullPage = function(div_name, listing_id) {
      $("." + div_name).unbind("click");
      return $("." + div_name).click(function() {
        var link, win;
        A2Cribs.MixPanel(A2Cribs.UserCache.Get("listing", listing_id), "full page");
        link = "/listings/view/" + listing_id;
        win = window.open(link, '_blank');
        return win.focus();
      });
    };

    ClickBubble.setFullPageContact = function(div_name, listing_id) {
      $("." + div_name).unbind("click");
      return $("." + div_name).click(function() {
        var link, win;
        A2Cribs.MixPanel(A2Cribs.UserCache.Get("listing", listing_id), "full page contact user");
        link = "/messages/contact/" + listing_id;
        win = window.open(link, '_blank');
        return win.focus();
      });
    };

    return ClickBubble;

  }).call(this);

}).call(this);
