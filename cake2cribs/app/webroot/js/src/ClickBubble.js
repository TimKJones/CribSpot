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

    ClickBubble.PADDING = 50;

    ClickBubble.IsOpen = false;

    /*
    	Private function that relocates the bubble near the marker
    */


    move_near_marker = function(listing_id) {
      var listing, marker, marker_pixel_position, position, postition;
      listing = A2Cribs.UserCache.Get("listing", listing_id);
      marker = A2Cribs.UserCache.Get("marker", listing.marker_id);
      position = null;
      if ((marker != null) && (marker.GMarker != null)) {
        position = marker.GMarker.getPosition();
      } else if (marker != null) {
        postition = new google.maps.LatLng(marker.latitude, marker.longitude);
      }
      if (position === null) {
        return;
      }
      marker_pixel_position = ClickBubble.ConvertLatLongToPixels(position);
      ClickBubble.div.css("left", marker_pixel_position.x + ClickBubble.OFFSET.LEFT);
      return ClickBubble.div.css("top", marker_pixel_position.y + ClickBubble.OFFSET.TOP);
    };

    ClickBubble.ConvertLatLongToPixels = function(latLng) {
      var nw, position, scale, worldCoordinate, worldCoordinateNW;
      scale = Math.pow(2, this.map.getZoom());
      nw = new google.maps.LatLng(this.map.getBounds().getNorthEast().lat(), this.map.getBounds().getSouthWest().lng());
      worldCoordinateNW = this.map.getProjection().fromLatLngToPoint(nw);
      worldCoordinate = this.map.getProjection().fromLatLngToPoint(latLng);
      position = {};
      position.x = Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale);
      position.y = Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale);
      return position;
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
    	Returns deferred object that gets resolved after clickbubble is loaded.
    	After it is loaded and visible, load its image.
    */


    ClickBubble.Open = function(listing_id) {
      var listing, openDeferred,
        _this = this;
      this.IsOpen = true;
      openDeferred = new $.Deferred;
      if (listing_id != null) {
        listing = A2Cribs.UserCache.Get(A2Cribs.Map.ACTIVE_LISTING_TYPE, listing_id);
        A2Cribs.MixPanel.Click(listing, "large popup");
        if (listing.rental_id != null) {
          this.SetContent(listing.GetObject());
          this.Show(listing_id);
          openDeferred.resolve(listing_id);
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
              _this.SetContent(listing.GetObject());
              _this.Show(listing_id);
              return openDeferred.resolve(listing_id);
            }
          });
        }
      }
      return openDeferred.promise();
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
      var key, marker, unit_style_description, value;
      this.Clear();
      for (key in listing_object) {
        value = listing_object[key];
        this.div.find("." + key).text(value);
      }
      this.div.find(".date_range").text(this.resolveDateRange(listing_object.start_date));
      marker = A2Cribs.UserCache.Get("marker", A2Cribs.UserCache.Get("listing", listing_object.listing_id).marker_id);
      this.div.find(".building_name").text(marker.GetName());
      this.div.find(".unit_type").text(marker.GetBuildingType());
      unit_style_description = '';
      if ((listing_object.unit_style_options != null) && (listing_object.unit_style_description != null)) {
        unit_style_description = listing_object.unit_style_options + '-' + listing_object.unit_style_description;
      } else if (listing_object.unit_style_options === 'Entire House') {
        unit_style_description = 'Entire House';
      }
      this.div.find('.unit_style_description').text(unit_style_description);
      this.div.find('unit_style_description').text;
      this.setBeds(listing_object.beds);
      this.linkWebsite(".website_link", listing_object.website, listing_object.listing_id);
      this.setAvailability("available", listing_object.available);
      this.setOwnerName("property_manager", listing_object.listing_id);
      this.setPrimaryImage("property_image", listing_object.listing_id);
      this.setFullPage("full_page_link", listing_object.listing_id);
      this.setFullPageContact("full_page_contact", listing_object.listing_id);
      this.div.find(".share_btn").unbind("click");
      this.div.find(".facebook_share").click(function() {
        return A2Cribs.ShareManager.ShareListingOnFacebook(listing_object.listing_id, marker.street_address, marker.city, marker.state, marker.zip, listing_object.description, listing_object.building_type_id);
      });
      this.div.find(".link_share").click(function() {
        return A2Cribs.ShareManager.CopyListingUrl(listing_object.listing_id, marker.street_address, marker.city, marker.state, marker.zip);
      });
      this.div.find(".twitter_share").click(function() {
        return A2Cribs.ShareManager.ShareListingOnTwitter(listing_object.listing_id, marker.street_address, marker.city, marker.state, marker.zip);
      });
      return A2Cribs.FavoritesManager.setFavoriteButton(this.div.find(".favorite_listing"), listing_object.listing_id, A2Cribs.FavoritesManager.FavoritesListingIds);
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
        return this.div.find("." + div_name).hide();
      } else if (availability) {
        this.div.find("." + div_name).show().text("Available");
        return this.div.find("." + div_name).removeClass("leased");
      } else {
        this.div.find("." + div_name).show().text("Leased");
        return this.div.find("." + div_name).addClass("leased");
      }
    };

    ClickBubble.linkWebsite = function(div_name, link, listing_id) {
      var _this = this;
      if (link != null) {
        if (link.indexOf("http") === -1) {
          link = "http://" + link;
        }
        return this.div.find(div_name).unbind("click").click(function() {
          A2Cribs.MixPanel.Click(A2Cribs.UserCache.Get("listing", listing_id), "go to realtor's website");
          return window.open(link, '_blank');
        });
      } else {
        return this.div.find(div_name).unbind("click").click(function() {
          return A2Cribs.UIManager.Error('This owner does not have a website for this listing');
        });
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
        if ((image_url != null) && (div_name != null)) {
          image_url = this._processImagePath(image_url);
          return $("." + div_name).css("background-image", "url(/" + image_url + ")");
        }
      } else if (div_name != null) {
        return $("." + div_name).css("background-image", "url(/img/tooltip/no_photo.jpg)");
      }
    };

    /*
    	Prepends 'med_' to the filename and returns result
    */


    ClickBubble._processImagePath = function(path) {
      var directory, filename;
      directory = path.substr(0, path.lastIndexOf('/'));
      filename = 'med_' + path.substr(path.lastIndexOf('/') + 1);
      return directory + '/' + filename;
    };

    ClickBubble.setFullPage = function(div_name, listing_id) {
      $("." + div_name).unbind("click");
      return $("." + div_name).click(function() {
        var link, win;
        A2Cribs.MixPanel.Click(A2Cribs.UserCache.Get("listing", listing_id), "full page");
        link = "/listings/view/" + listing_id;
        win = window.open(link, '_blank');
        return win.focus();
      });
    };

    ClickBubble.setFullPageContact = function(div_name, listing_id) {
      $("." + div_name).unbind("click");
      return $("." + div_name).click(function() {
        var link, win;
        A2Cribs.MixPanel.Click(A2Cribs.UserCache.Get("listing", listing_id), "full page contact user");
        link = "/messages/contact/" + listing_id;
        win = window.open(link, '_blank');
        return win.focus();
      });
    };

    ClickBubble.setBeds = function(bed_count) {
      if (!(bed_count != null) || parseInt(bed_count, 10) === NaN) {
        this.div.find(".beds").text("??");
        return this.div.find(".bed_desc").text("Beds");
      } else if (parseInt(bed_count, 10) === 0) {
        this.div.find(".beds").text("Studio");
        return this.div.find(".bed_desc").text("");
      } else if (parseInt(bed_count, 10) === 1) {
        this.div.find(".bed_desc").text(bed_count);
        return this.div.find(".bed_desc").text("Bed");
      } else {
        this.div.find(".bed_desc").text(bed_count);
        return this.div.find(".bed_desc").text("Beds");
      }
    };

    /*
    	takes as arguments the x and y position of the clicked marker
    	returns the x and y amounts to pan the map so that the click bubble fits on the screen
    */


    ClickBubble.GetAdjustedClickBubblePosition = function(marker_x, marker_y) {
      var BOTTOM, RIGHT, TOP, filter_offset, offset, x_max, y_high, y_low;
      y_high = marker_y + this.OFFSET['TOP'];
      y_low = marker_y + this.OFFSET['TOP'] + $(".click-bubble").height();
      x_max = marker_x + this.OFFSET['LEFT'] + $(".click-bubble").width();
      offset = {};
      offset.x = 0;
      offset.y = 0;
      RIGHT = $("#map_region").width();
      BOTTOM = $(window).height() - 5;
      filter_offset = $("#map_filter").offset();
      TOP = filter_offset.top;
      if (y_high < (TOP + this.PADDING)) {
        offset.y = y_high - (TOP + this.PADDING);
      }
      if (y_low > (BOTTOM - this.PADDING)) {
        offset.y = y_low - (BOTTOM - this.PADDING);
      }
      if (x_max > (RIGHT - this.PADDING)) {
        offset.x = x_max - (RIGHT - this.PADDING);
      }
      return offset;
    };

    return ClickBubble;

  }).call(this);

}).call(this);
