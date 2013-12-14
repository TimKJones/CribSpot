// Generated by CoffeeScript 1.6.3
/*
LargeBubble class
*/


(function() {
  var LargeBubble;

  LargeBubble = (function() {
    var move_near_marker,
      _this = this;

    function LargeBubble() {}

    LargeBubble.OFFSET = {
      TOP: -190,
      LEFT: 140
    };

    LargeBubble.PADDING = 50;

    LargeBubble.IsOpen = false;

    /*
    	When the map is initialized, call init for the map
    */


    $(document).ready(function() {
      return $("#map_region").on("map_initialized", function(event, map) {
        return LargeBubble.Init(map);
      });
    });

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
      marker_pixel_position = LargeBubble.ConvertLatLongToPixels(position);
      LargeBubble.div.css("left", marker_pixel_position.x + LargeBubble.OFFSET.LEFT);
      return LargeBubble.div.css("top", marker_pixel_position.y + LargeBubble.OFFSET.TOP);
    };

    LargeBubble.ConvertLatLongToPixels = function(latLng) {
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


    LargeBubble.Init = function(map) {
      var _this = this;
      this.map = map;
      this.div = $(".large-bubble:first");
      google.maps.event.addListener(this.map, 'center_changed', function() {
        return _this.Close();
      });
      this.div.find(".close_button").click(function() {
        return _this.Close();
      });
      $("#map_region").on('close_bubbles', function() {
        return _this.Close();
      });
      $("#map_region").on("marker_clicked", function(event, marker) {
        var marker_pixel_position, pixels_to_pan;
        marker_pixel_position = _this.ConvertLatLongToPixels(marker.GMarker.getPosition());
        pixels_to_pan = _this.GetAdjustedLargeBubblePosition(marker_pixel_position.x, marker_pixel_position.y);
        return _this.map.panBy(pixels_to_pan.x, pixels_to_pan.y);
      });
      $('#map_region').on('listing_click', function(event, listing_id) {
        return _this.Open(listing_id);
      });
      return this.div.draggable({
        revert: true,
        opacity: 0.7,
        cursorAt: {
          top: -12,
          right: -20
        },
        helper: function(event) {
          var name;
          name = $(this).find('.building_name').html() || "this listing";
          return $("<div class='listing-drag-helper'>Share " + name + "</div>");
        },
        start: function(event) {
          console.log('start');
          $('ul.friends, #hotlist').addClass('dragging');
          return A2Cribs.HotlistObj.startedDragging();
        },
        stop: function(event) {
          $('ul.friends, #hotlist').removeClass('dragging');
          return A2Cribs.HotlistObj.stoppedDragging();
        },
        appendTo: 'body'
      });
    };

    /*
    	Opens the tooltip given a marker, with popping animation
    	Returns deferred object that gets resolved after LargeBubble is loaded.
    	After it is loaded and visible, load its image.
    */


    LargeBubble.Open = function(listing_id) {
      var openDeferred,
        _this = this;
      this.IsOpen = true;
      $("#map_canvas").trigger("click_bubble_open", [listing_id]);
      openDeferred = new $.Deferred();
      if (listing_id != null) {
        $("#loader").show();
        A2Cribs.UserCache.GetListing(A2Cribs.Map.ACTIVE_LISTING_TYPE, listing_id).done(function(listing) {
          A2Cribs.MixPanel.Click(listing, "large popup");
          _this.SetContent(listing.GetObject());
          _this.Show(listing_id);
          return openDeferred.resolve(listing_id);
        }).fail(function() {
          return A2Cribs.UIManager.Error("Sorry - We could not find this listing!");
        }).always(function() {
          return $("#loader").hide();
        });
      }
      return openDeferred.promise();
    };

    LargeBubble.Show = function(listing_id) {
      this.IsOpen = true;
      move_near_marker(listing_id);
      return this.div.show('fade');
    };

    /*
    	Refreshes the tooltip with the new content, no animation
    */


    LargeBubble.Refresh = function() {
      return this.div.show('fade');
    };

    /*
    	Closes the tooltip, no animation
    */


    LargeBubble.Close = function() {
      this.IsOpen = false;
      return this.div.hide('fade');
    };

    LargeBubble.Clear = function() {
      return this.div.find(".clear_field").text("?").html("?").val("?");
    };

    /*
    	Sets the content of the tooltip
    */


    LargeBubble.SetContent = function(listing_object) {
      var key, marker, unit_style_description, value;
      this.Clear();
      this.div.data('listing_id', listing_object.listing_id);
      for (key in listing_object) {
        value = listing_object[key];
        this.div.find("." + key).text(value);
      }
      this.div.find(".start_date").text(this.resolveDateRange(listing_object.start_date));
      if (listing_object.end_date != null) {
        this.div.find(".lease_length").text(this.resolveDateRange(listing_object.end_date));
        this.div.find(".lease_box").hide();
        this.div.find(".end_date_box").show();
      } else {
        this.div.find(".end_date_box").hide();
        this.div.find(".lease_box").show();
      }
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
      this.setBeds(listing_object.beds);
      this.linkWebsite(".website_link", listing_object.website, listing_object.listing_id);
      this.setRent(listing_object.rent);
      this.setAvailability("available", listing_object.available);
      this.setOwnerName("property_manager", listing_object.listing_id);
      this.setPrimaryImage("property_image", listing_object.listing_id);
      this.setFullPage("full_page_link", listing_object.listing_id);
      this.setFullPageContact("full_page_contact", listing_object.listing_id);
      this.setFullPageSchedule("schedule_tour", listing_object.listing_id);
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
      this.div.find(".hotlist_share").popover({
        content: function() {
          return A2Cribs.HotlistObj.getHotlistForPopup(listing_object.listing_id);
        },
        html: true,
        trigger: 'manual',
        container: 'body',
        title: 'Share this listing'
      }).click(function(e) {
        var _this = this;
        e.preventDefault();
        $(this).popover('show');
        return $('.popover a').on('click', function() {
          $('.popover').popover('hide').hide();
          return $('.popover').off('click');
        }).find("#share-to-email").keyup(function(event) {
          if (event.keyCode === 13) {
            return $(".share-to-email-btn").click();
          }
        });
      });
      this.div.find(".favorite_listing").data("listing-id", listing_object.listing_id);
      return A2Cribs.FavoritesManager.setFavoriteButton(this.div.find(".favorite_listing"), listing_object.listing_id);
    };

    LargeBubble.resolveDateRange = function(startDate) {
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

    LargeBubble.setAvailability = function(div_name, availability) {
      if (availability == null) {
        return this.div.find("." + div_name).hide();
      } else if (availability) {
        this.div.find("." + div_name).show().text("Available");
        return this.div.find("." + div_name).removeClass("leased");
      } else {
        this.div.find("." + div_name).show().text("Leased");
        return this.div.find("." + div_name).addClass("leased");
      }
    };

    LargeBubble.linkWebsite = function(div_name, link, listing_id) {
      var mix_object, _ref,
        _this = this;
      mix_object = A2Cribs.UserCache.Get("listing", listing_id);
      if (mix_object == null) {
        mix_object = {};
      }
      mix_object["logged_in"] = (_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0;
      if (link != null) {
        return this.div.find(div_name).unbind("click").click(function() {
          var _ref1;
          if (((_ref1 = A2Cribs.Login) != null ? _ref1.logged_in : void 0) === true) {
            A2Cribs.MixPanel.Click(mix_object, "go to realtor's website");
            return window.open("/listings/website/" + listing_id, '_blank');
          } else {
            $("#signup_modal").modal("show").find(".signup_message").text("Please signup to view this website");
            return A2Cribs.MixPanel.Event("login required", {
              "listing_id": listing_id,
              action: "go to realtor's website"
            });
          }
        });
      } else {
        return this.div.find(div_name).unbind("click").click(function() {
          return A2Cribs.UIManager.Error('This owner does not have a website for this listing');
        });
      }
    };

    LargeBubble.setRent = function(rent) {
      if (rent == null) {
        this.div.find(".rent").text("Ask for Rent");
        this.div.find(".per_month").text("");
        return this.div.find(".price_label").text("");
      } else if (parseInt(rent, 10) !== 0) {
        this.div.find(".rent").text(rent);
        this.div.find(".per_month").text("/m");
        return this.div.find(".price_label").text("$");
      } else {
        this.div.find(".rent").text("Call for Rent");
        this.div.find(".per_month").text("");
        return this.div.find(".price_label").text("");
      }
    };

    LargeBubble.setOwnerName = function(div_name, listing_id) {
      var listing, user;
      listing = A2Cribs.UserCache.Get("listing", listing_id);
      user = A2Cribs.UserCache.Get("user", listing.user_id);
      if ((user != null ? user.company_name : void 0) != null) {
        $("." + div_name).show().text(user.company_name);
      } else if (((user != null ? user.first_name : void 0) != null) && user.last_name) {
        $("." + div_name).show().text("" + user.first_name);
      } else {
        $("." + div_name).hide();
      }
      if (user != null ? user.verified : void 0) {
        return this.div.find(".verified").show();
      } else {
        return this.div.find(".verified").hide();
      }
    };

    LargeBubble.setPrimaryImage = function(div_name, listing_id) {
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


    LargeBubble._processImagePath = function(path) {
      var directory, filename;
      directory = path.substr(0, path.lastIndexOf('/'));
      filename = 'med_' + path.substr(path.lastIndexOf('/') + 1);
      return directory + '/' + filename;
    };

    LargeBubble.setFullPage = function(div_name, listing_id) {
      $("." + div_name).unbind("click");
      return $("." + div_name).click(function() {
        var link, win;
        A2Cribs.MixPanel.Click(A2Cribs.UserCache.Get("listing", listing_id), "full page");
        link = "/listings/view/" + listing_id;
        win = window.open(link, '_blank');
        return win.focus();
      });
    };

    LargeBubble.setFullPageContact = function(div_name, listing_id) {
      $("." + div_name).unbind("click");
      return $("." + div_name).click(function() {
        var link, win;
        link = "/messages/contact/" + listing_id;
        win = window.open(link, '_blank');
        return win.focus();
      });
    };

    LargeBubble.setFullPageSchedule = function(div_name, listing_id) {
      var listing;
      listing = A2Cribs.UserCache.Get("listing", listing_id);
      if ((listing != null ? listing.available : void 0) === true) {
        $("." + div_name).show();
      } else {
        $("." + div_name).hide();
      }
      $("." + div_name).unbind("click");
      return $("." + div_name).click(function() {
        var link, win;
        link = "/tours/schedule/" + listing_id;
        win = window.open(link, '_blank');
        return win.focus();
      });
    };

    LargeBubble.setBeds = function(bed_count) {
      if ((bed_count == null) || parseInt(bed_count, 10) === NaN) {
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


    LargeBubble.GetAdjustedLargeBubblePosition = function(marker_x, marker_y) {
      var BOTTOM, RIGHT, TOP, filter_offset, offset, x_max, y_high, y_low;
      y_high = marker_y + this.OFFSET['TOP'];
      y_low = marker_y + this.OFFSET['TOP'] + $(".large-bubble").height();
      x_max = marker_x + this.OFFSET['LEFT'] + $(".large-bubble").width();
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

    return LargeBubble;

  }).call(this);

}).call(this);
