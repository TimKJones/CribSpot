(function() {
  var Analytics, LargeBubble, QuickRental, SmallBubble, SubletSave, Tour,
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; },
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; },
    _this = this;

  if (window.A2Cribs == null) window.A2Cribs = {};

  A2Cribs.Object = (function() {

    function Object(class_name, a2_object) {
      var key, value;
      this.class_name = class_name != null ? class_name : "object";
      for (key in a2_object) {
        value = a2_object[key];
        if (value != null) this[key] = value;
      }
    }

    Object.prototype.Update = function(a2_object) {
      var key, value;
      for (key in a2_object) {
        value = a2_object[key];
        if (value != null) this[key] = value;
      }
      return this;
    };

    Object.prototype.GetId = function(id) {
      return parseInt(this["" + this.class_name + "_id"], 10);
    };

    Object.prototype.GetObject = function() {
      var key, return_object, value;
      return_object = {};
      for (key in this) {
        value = this[key];
        if (typeof value !== "function") {
          if (typeof value === "boolean") value = +value;
          return_object[key] = value;
        }
      }
      return return_object;
    };

    Object.prototype.IsComplete = function() {
      return true;
    };

    return Object;

  })();

  A2Cribs.User = (function(_super) {

    __extends(User, _super);

    function User(user) {
      User.__super__.constructor.call(this, "user", user);
    }

    User.prototype.GetId = function() {
      return this.id;
    };

    return User;

  })(A2Cribs.Object);

  /*
  Class Analytics
  Wrapper class to handle the interactions
  with google analytics event tracking
  
  **************************
  Cribspot Events:
  - TODO: ADD DASHBOARD EVENTS!!!!
  - Login
  	- Logged in
  	- Signed up
  	- Login required
  - Filter
  	- TODO: Changed
  - Listing
  	- Popup Opened
  	- Go to website
  	- View full page
  	- Sidebar Click
  - Full Page
  	- Schedule Tour Clicked
  	- Contact Owner Clicked
  - Message
  	- Sending Message
  	- Message Sent
  	- Message Failed
  - Marker
  	- Popup Opened
  	- Save
  	- Save Completed
  	- Marker Clicked
  - Share
  	- URL Copied 
  	- Listing on FB
  		- Completed Sublet
  	- Listing on FB Completed
  		- Completed Sublet
  	- Website on FB
  		- Header Button
  		- Wisconsin Sunglasses
  	- Website on FB Completed
  		- Header Button
  		- Wisconsin Sunglasses
  	- Listing on Twitter
  	- Invite Friends
  	- Invite Friends Completed
  - Advertising
  	- Featured PM
  - Photo Editor
  	- Load Images
  	- Save Images
  - Tour
  - Post Rental
  	- Open Marker
  	- Tab Change
  	- Save
  	- Add Unit
  - Post Sublet
  	- Create
  	- Save
  	- Save Completed
  **************************
  */

  Analytics = (function() {
    var push_event,
      _this = this;

    function Analytics() {}

    /*
    	Private Event Method
    	Wrapper for the _trackEvent for google analytics
    */

    push_event = function(category, action, label, value) {
      if (label == null) label = null;
      if (value == null) value = null;
      return _gaq.push(['_trackEvent', category, action, label, value]);
    };

    /*
    	Document Ready
    */

    $(document).ready(function() {
      return $(document).on("track_event", function(event, category, action, label, value) {
        if (label == null) label = null;
        if (value == null) value = null;
        return push_event(category, action, label, value);
      });
    });

    return Analytics;

  }).call(this);

  A2Cribs.Favorite = (function() {

    function Favorite(FavoriteId, ListingId, UserId) {
      this.FavoriteId = FavoriteId;
      this.ListingId = ListingId;
      this.UserId = UserId;
    }

    return Favorite;

  })();

  A2Cribs.Listing = (function(_super) {

    __extends(Listing, _super);

    Listing.LISTING_TYPES = ['Rental', 'Sublet'];

    function Listing(listing) {
      Listing.__super__.constructor.call(this, "listing", listing);
    }

    /*
    	Checks/Sets if the listing is visible
    	on the map
    	Defaults to true
    */

    Listing.prototype.IsVisible = function(visible) {
      if (visible == null) visible = null;
      if (typeof visible === "boolean") this.visible = visible;
      if (this.visible === false) return false;
      return true;
    };

    /*
    	Checks/Sets if the listing is in the sidebar
    	This variable is set when the listing
    	is loaded in the sidebar
    */

    Listing.prototype.InSidebar = function(in_sidebar) {
      if (in_sidebar == null) in_sidebar = null;
      if (typeof in_sidebar === "boolean") this.in_sidebar = in_sidebar;
      if (this.in_sidebar === true) return true;
      return false;
    };

    /*
    	Check/Sets if the listing is featured
    */

    Listing.prototype.IsFeatured = function(is_featured) {
      if (is_featured == null) is_featured = null;
      if (typeof is_featured === "boolean") this.is_featured = is_featured;
      if (this.is_featured === true) return true;
      return false;
    };

    /*
    	Returns the string of the listing type
    */

    Listing.prototype.GetListingType = function() {
      return A2Cribs.Listing.LISTING_TYPES[parseInt(this.listing_type, 10)];
    };

    /*
    	Gets all objects connected to the listing
    */

    Listing.prototype.GetConnectedObject = function() {
      var a2object, a2objects, listing_string, obj, ret_object, _i, _len;
      listing_string = A2Cribs.Listing.LISTING_TYPES[parseInt(this.listing_type, 10)];
      a2objects = ['Listing', 'Image', listing_string];
      ret_object = {};
      for (_i = 0, _len = a2objects.length; _i < _len; _i++) {
        a2object = a2objects[_i];
        obj = A2Cribs.UserCache.Get(a2object.toLowerCase(), this.GetId());
        if (obj != null) ret_object[a2object] = obj.GetObject();
      }
      return ret_object;
    };

    return Listing;

  })(A2Cribs.Object);

  A2Cribs.Rentpay = (function() {
    var _this = this;

    function Rentpay() {}

    Rentpay.init = function() {
      var _this = this;
      this.report_credit = true;
      this.is_venmo = "no";
      this.braintree = Braintree.create('MIIBCgKCAQEAvxM/Oy1nPH0H/N/kya9jT84pJ78pR5UglboAxJH3yktxWjNNFQ85uNsjd5fKd+XFgEGYyfEqwUuHej1MafyO0Wu2W4HJoau3OmYC3EQRMr5yZR9mR1/3pRmi4JBi/wd3NBXdUZ5ZIjSO2bkaYJSTVcguGKtocKVseBYlHsHtS6tseWOi813cqHz15877F/iOZzdcS84JVGwPdVIdzBqQCpAoiUnPjzNAGeFBk+Rm9y6CtzWGGn6Z7zOxzUf5VJPycoz94Yr7EDIjTy8vF42IHsyOhreWGX/+p9nQGxiey+sslhTlKue7jRkC8IHwAHaXLYH37lrRoriN6emASj7shQIDAQAB');
      this.braintree.onSubmitEncryptForm('braintree-payment-form', this.EncryptFormCallback);
      this.div.find(".back").click(function(event) {
        _this.div.find(".rentpay-step").hide();
        return _this.div.find("." + $(event.currentTarget).data('back')).show();
      });
      this.div.find(".next-step").click(function(event) {
        if (_this.validate_inputs(event.currentTarget)) {
          if ($(event.currentTarget).hasClass("finish-rentpay")) {
            $("#rentpay-signup").modal("hide");
            _this.div.find(".rentpay-step").hide();
            _this.div.find(".part-one").show();
            return;
          }
          _this.div.find(".rentpay-step").hide();
          return _this.div.find("." + $(event.currentTarget).data('next-step')).show();
        } else {
          return A2Cribs.UIManager.Error("Please complete all fields!");
        }
      });
      this.div.find(".pay-option").click(function(event) {
        if (!$(event.currentTarget).hasClass("inactive")) {
          _this.div.find(".pay-option").removeClass("active");
          $(event.currentTarget).addClass("active");
          if ($(event.currentTarget).hasClass("show-card")) {
            _this.div.find(".white-cover").hide();
            $("#braintree-payment-form").find("[name=venmo]").val("no");
            return _this.is_venmo = "no";
          } else {
            _this.div.find(".white-cover").show();
            $("#braintree-payment-form").find("[name=venmo]").val("yes");
            return _this.is_venmo = "yes";
          }
        }
      });
      this.div.find(".finish-rentpay").click(function() {
        _this.div.find(".form-field").each(function(index, value) {
          return $("#braintree-payment-form").find("[name=" + ($(value).data("field-name")) + "]").val($(value).val());
        });
        return $("#braintree-payment-form").submit();
      });
      this.div.find(".report-rent").click(function(event) {
        $(event.currentTarget).find("img").toggleClass("hide");
        return _this.report_credit = !_this.report_credit;
      });
      return this.div.find(".add_roommate").click(function() {
        var div;
        div = "<div class=\"housemate\">\n	<input class=\"gotham-bold input75 email\" type=\"text\" placeholder=\"Housemate's Email\">\n	<input class=\"gotham-bold input25 rent\" type=\"text\" placeholder=\"Rent\">\n</div>";
        return _this.div.find(".housemates").append(div);
      });
    };

    Rentpay.validate_inputs = function(target) {
      var retVal;
      retVal = true;
      if ($(target).closest(".rentpay-step").hasClass("part-three")) return true;
      if ($(target).closest(".rentpay-step").hasClass("part-two") && Rentpay.is_venmo === "yes") {
        return true;
      }
      if ($(target).closest(".rentpay-step").hasClass("part-two") && Rentpay.is_venmo === "no") {
        if ($("#card_number").val().length !== 16) {
          A2Cribs.UIManager.Error("Please type a valid card number");
          return false;
        }
      }
      $(target).closest(".rentpay-step").find("input").each(function(index, value) {
        if (!$(value).val().length) return retVal = false;
      });
      return retVal;
    };

    Rentpay.EncryptFormCallback = function(event) {
      var data, housemates;
      housemates = [];
      $(".housemate").each(function(index, value) {
        var email, rent;
        email = $(value).find(".email").val();
        rent = $(value).find(".rent").val();
        if ((rent != null ? rent.length : void 0) && (email != null ? email.length : void 0)) {
          return housemates.push({
            email: email,
            rent: rent
          });
        }
      });
      data = $("#braintree-payment-form").serializeObject();
      data.housemates = housemates;
      data.build_credit = this.report_credit;
      $.ajax({
        type: 'POST',
        url: '/Rentpays/CreateTransaction',
        data: data,
        success: function() {
          A2Cribs.UIManager.CloseLogs();
          return A2Cribs.UIManager.Success("Thanks for signing up! Your payment has been recorded!");
        },
        error: function() {
          A2Cribs.UIManager.CloseLogs();
          return A2Cribs.UIManager.Error("There has been an error setting up your account. Please chat us in the bottom-left corner for help!");
        }
      });
      return false;
    };

    $(document).ready(function() {
      var password_modal;
      if ((Rentpay.div = $("#rentpay-signup")).length) Rentpay.init();
      password_modal = $("#password-protect");
      if (password_modal.length) {
        password_modal.find(".password_protected").submit(function() {
          if (password_modal.find("input").val() === "GOBLUE") {
            password_modal.modal("hide");
          } else {
            A2Cribs.UIManager.CloseLogs();
            A2Cribs.UIManager.Error("Password was incorrect");
          }
          return false;
        });
        return password_modal.modal({
          backdrop: 'static',
          keyboard: false
        });
      }
    });

    return Rentpay;

  }).call(this);

  $.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
		$.each(a, function() {
			if (o[this.name] !== undefined) {
				if (!o[this.name].push) {
					o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value || '');
			} else {
				o[this.name] = this.value || '';
			}
		});
		return o;
};;

  A2Cribs.Marker = (function(_super) {
    var FilterVisibleListings, UpdateMarkerContent;

    __extends(Marker, _super);

    Marker.BuildingType = ['House', 'Apartment', 'Duplex', 'Condo', 'Townhouse', 'Co-Op', 'Dorm', 'Greek', 'Other'];

    Marker.TYPE = {
      UNKNOWN: 0,
      LEASED: 1,
      AVAILABLE: 2
    };

    function Marker(marker) {
      this.MarkerClicked = __bind(this.MarkerClicked, this);      Marker.__super__.constructor.call(this, "marker", marker);
    }

    Marker.prototype.GetName = function() {
      if ((this.alternate_name != null) && this.alternate_name.length) {
        return this.alternate_name;
      } else {
        return this.street_address;
      }
    };

    Marker.prototype.GetBuildingType = function() {
      if (isNaN(parseInt(this.building_type_id, 10))) return this.building_type_id;
      return A2Cribs.Marker.BuildingType[this.building_type_id];
    };

    Marker.prototype.GetType = function() {
      return this._type;
    };

    Marker.prototype.SetType = function(_type) {
      var marker_dot, _ref;
      this._type = _type;
      switch (this._type) {
        case A2Cribs.Marker.TYPE.UNKNOWN:
          marker_dot = "unknown";
          break;
        case A2Cribs.Marker.TYPE.LEASED:
          marker_dot = "leased";
          break;
        case A2Cribs.Marker.TYPE.AVAILABLE:
          marker_dot = "available";
      }
      return (_ref = this.GMarker) != null ? _ref.setIcon("/img/dots/dot_" + marker_dot + ".png") : void 0;
    };

    Marker.prototype.IsVisible = function(visible) {
      var _ref, _ref2;
      if (visible == null) visible = null;
      if (typeof visible === "boolean") {
        if ((_ref = this.GMarker) != null) _ref.setVisible(visible);
      }
      if (!(this.GMarker != null)) return false;
      return (_ref2 = this.GMarker) != null ? _ref2.getVisible() : void 0;
    };

    Marker.prototype.Init = function() {
      this.GMarker = new google.maps.Marker({
        position: new google.maps.LatLng(this.latitude, this.longitude),
        icon: "/img/dots/dot_leased.png",
        id: this.GetId()
      });
      return google.maps.event.addListener(this.GMarker, 'click', this.MarkerClicked);
    };

    Marker.prototype.GetObject = function() {
      var return_val;
      return_val = Marker.__super__.GetObject.call(this);
      return_val.GMarker = null;
      return return_val;
    };

    Marker.prototype.MarkerClicked = function(event) {
      $("#map_region").trigger("marker_clicked", [this]);
      return $(document).trigger("track_event", ["Marker", "Marker Clicked", "", this.GetId()]);
    };

    /*
    	Filters the listing_ids at the current marker according to the user's current filter settings.
    	Returns list of listing_ids that should be visible in marker tooltip.
    */

    FilterVisibleListings = function(subletIdList) {
      var ac, apt, bathType, bathroom, bathrooms_match, beds, end_date, female, grad, has_females, has_grads, has_males, has_students_only, has_undergrads, house, housemate, housemate_id, l, male, max_rent, min_rent, no_security_deposit, no_security_deposit_match, other, parking, start_date, students_only, subletId, sublet_end_date, sublet_start_date, undergrad, unitType, utilities, utilities_included_match, visibleListingIds, _i, _len;
      if (subletIdList === void 0) return null;
      house = $("#houseCheck").is(':checked');
      apt = $("#aptCheck").is(':checked');
      other = $("#otherCheck").is(':checked');
      male = $("#maleCheck").is(':checked');
      female = $("#femaleCheck").is(':checked');
      students_only = $("#studentsOnlyCheck").is(':checked');
      grad = $("#gradCheck").is(':checked');
      undergrad = $("#undergradCheck").is(':checked');
      ac = $("#acCheck").is(':checked');
      parking = $("#parkingCheck").is(':checked');
      utilities = $("#utilitiesCheck").is(':checked');
      no_security_deposit = $("#noSecurityDepositCheck").is(':checked');
      min_rent = A2Cribs.FilterManager.MinRent;
      max_rent = A2Cribs.FilterManager.MaxRent;
      beds = $("#bedsSelect").val();
      if (beds === "2+") beds = "2";
      beds = parseInt(beds);
      start_date = new Date(A2Cribs.FilterManager.DateBegin);
      end_date = new Date(A2Cribs.FilterManager.DateEnd);
      bathroom = $("#bathSelect").val();
      visibleListingIds = [];
      for (_i = 0, _len = subletIdList.length; _i < _len; _i++) {
        subletId = subletIdList[_i];
        l = A2Cribs.Cache.IdToSubletMap[subletId];
        unitType = l.BuildingType;
        bathType = l.BathroomType;
        sublet_start_date = new Date(l.StartDate);
        sublet_end_date = new Date(l.EndDate);
        housemate_id = A2Cribs.Cache.SubletIdToHousemateIdsMap[subletId];
        housemate = A2Cribs.Cache.IdToHousematesMap[housemate_id];
        has_males = true;
        has_females = true;
        has_grads = true;
        has_undergrads = true;
        has_students_only = false;
        if (housemate !== void 0 && housemate !== null) {
          has_males = housemate.Gender === "Male" || housemate.Gender === "Mix" || housemate.Gender === void 0 || housemate.Gender === null;
          has_females = housemate.Gender === "Female" || housemate.Gender === "Mix" || housemate.Gender === void 0 || housemate.Gender === null;
          has_grads = housemate.GradType === "Graduate" || housemate.GradType === "Mix" || housemate.GradType === void 0 || housemate.GradType === null;
          has_undergrads = housemate.GradType === "Undergraduate" || housemate.GradType === "Mix" || housemate.GradType === void 0 || housemate.GradType === null;
          has_students_only = housemate.Enrolled === true || housemate.Enrolled === void 0 || housemate.Enrolled === null;
        }
        bathrooms_match = (l.BathroomType === bathroom) || (bathroom !== "Private" && bathroom !== "Shared");
        utilities_included_match = !utilities || (utilities && l.UtilityCost === 0);
        no_security_deposit_match = !no_security_deposit || (no_security_deposit && l.DepositAmount === 0);
        if ((((unitType === 'House' || unitType === null) && house) || ((unitType === 'Apartment' || unitType === null) && apt) || ((unitType === 'Duplex' || unitType === null) && other) || (unitType !== 'House' && unitType !== 'Duplex' && unitType !== 'Apartment')) && (l.PricePerBedroom >= min_rent && l.PricePerBedroom <= max_rent) && (l.Bedrooms >= beds) && ((start_date >= sublet_start_date) || !A2Cribs.Marker.IsValidDate(start_date)) && ((sublet_end_date >= end_date) || !A2Cribs.Marker.IsValidDate(end_date)) && ((female && has_females) || (male && has_males)) && ((undergrad && has_undergrads) || (grad && has_grads)) && (!students_only || (students_only && has_students_only)) && bathrooms_match && utilities_included_match && no_security_deposit_match) {
          visibleListingIds.push(subletId);
        }
      }
      return visibleListingIds;
    };

    /*
    	Called after successful ajax call to retrieve all listing data for a specific marker_id.
    	Updates UI with retrieved data
    */

    UpdateMarkerContent = function(markerData) {
      var clickedMarker, listing, _i, _len;
      if (!this.Clicked) {
        for (_i = 0, _len = markerData.length; _i < _len; _i++) {
          listing = markerData[_i];
          A2Cribs.UserCache.Set(A2Cribs.Listing(JSON.parse(markerData)));
        }
        clickedMarker = A2Cribs.UserCache.Get("marker", this.MarkerId);
        clickedMarker.GMarker.setIcon("/img/dots/clicked_dot.png");
      }
      this.Clicked = true;
      return this.FilterVisibleListingsAndOpenPopup();
    };

    /*
    	Load all listing data for this marker
    	Called when a marker is clicked
    */

    Marker.prototype.LoadMarkerData = function() {
      this.CorrectTooltipLocation();
      if (this.Clicked) {
        return this.FilterVisibleListingsAndOpenPopup();
      } else {
        return $.ajax({
          url: myBaseUrl + "Listings/LoadMarkerData/" + this.MarkerId,
          type: "GET",
          context: this,
          success: UpdateMarkerContent
        });
      }
    };

    Marker.prototype.FilterVisibleListingsAndOpenPopup = function() {
      var visibleListingIds;
      visibleListingIds = FilterVisibleListings(A2Cribs.Cache.MarkerIdToSubletIdsMap[this.MarkerId]);
      return A2Cribs.Map.ClickBubble.Open(this, visibleListingIds);
    };

    Marker.GetMarkerPixelCoordinates = function(latlng) {
      var map, markerLocation, nw, scale, worldCoordinate, worldCoordinateNW;
      map = A2Cribs.Map.GMap;
      scale = Math.pow(2, map.getZoom());
      nw = new google.maps.LatLng(map.getBounds().getNorthEast().lat(), map.getBounds().getSouthWest().lng());
      worldCoordinateNW = map.getProjection().fromLatLngToPoint(nw);
      worldCoordinate = map.getProjection().fromLatLngToPoint(latlng);
      markerLocation = new google.maps.Point(Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale), Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale));
      return markerLocation;
    };

    /*
    	Correct the tooltip location to fit it on the screen.
    */

    Marker.prototype.CorrectTooltipLocation = function() {
      var leftBound, markerLocation, tooltipOffset;
      leftBound = A2Cribs.Map.Bounds.CONTROL_BOX_LEFT;
      markerLocation = A2Cribs.Marker.GetMarkerPixelCoordinates(this.GMarker.position);
      tooltipOffset = {
        x: 0,
        y: 0
      };
      if (markerLocation.x + A2Cribs.MarkerTooltip.Width - A2Cribs.MarkerTooltip.ArrowOffset + A2Cribs.MarkerTooltip.Padding > A2Cribs.Map.Bounds.RIGHT) {
        tooltipOffset.x = markerLocation.x + A2Cribs.MarkerTooltip.Width - A2Cribs.MarkerTooltip.ArrowOffset + A2Cribs.MarkerTooltip.Padding - A2Cribs.Map.Bounds.RIGHT;
      }
      if (markerLocation.x - A2Cribs.MarkerTooltip.ArrowOffset - A2Cribs.MarkerTooltip.Padding < leftBound) {
        tooltipOffset.x = markerLocation.x - A2Cribs.MarkerTooltip.ArrowOffset - A2Cribs.MarkerTooltip.Padding - leftBound;
      }
      if (markerLocation.y - A2Cribs.MarkerTooltip.Height - A2Cribs.MarkerTooltip.ArrowHeight < 0) {
        tooltipOffset.y = markerLocation.y - A2Cribs.MarkerTooltip.Height - A2Cribs.MarkerTooltip.ArrowHeight;
      }
      if (markerLocation.y > A2Cribs.Map.Bounds.BOTTOM - A2Cribs.MarkerTooltip.Padding) {
        tooltipOffset.y = markerLocation.y - A2Cribs.Map.Bounds.BOTTOM + A2Cribs.MarkerTooltip.Padding;
      }
      return A2Cribs.Map.GMap.panBy(tooltipOffset.x, tooltipOffset.y);
    };

    Marker.IsValidDate = function(date) {
      return date.toString() !== "Invalid Date";
    };

    return Marker;

  })(A2Cribs.Object);

  /*
  Static class handling all Favorites functionality.
  Call functions using FavoritesManager.FunctionName()
  */

  A2Cribs.FavoritesManager = (function() {
    var _this = this;

    function FavoritesManager() {}

    FavoritesManager.FavoritesListingIds = [];

    FavoritesManager.FavoritesVisible = false;

    $(document).ready(function() {
      return $("body").on('click', '.favorite_listing', function(event) {
        var listing_id, _ref;
        if (((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) === false) {
          $("#signup_modal").modal("show").find(".signup_message").text("Please signup to favorite this listing.");
          return false;
        }
        listing_id = $(event.currentTarget).data("listing-id");
        if (FavoritesManager.FavoritesListingIds.indexOf(parseInt(listing_id, 10)) === -1) {
          FavoritesManager.AddFavorite(listing_id, event.currentTarget);
        } else {
          FavoritesManager.DeleteFavorite(listing_id, event.currentTarget);
        }
        return $(event.currentTarget).toggleClass("active");
      });
    });

    /*
    	Add a favorite
    */

    FavoritesManager.AddFavorite = function(listing_id, button) {
      return $.ajax({
        url: myBaseUrl + "Favorites/AddFavorite/" + listing_id,
        type: "POST",
        context: this,
        success: function(response) {
          return A2Cribs.FavoritesManager.AddFavoriteCallback(response, listing_id, button);
        }
      });
    };

    FavoritesManager.AddFavoriteCallback = function(response, listing_id, button) {
      var fl_sidebar_item;
      response = JSON.parse(response);
      if (response.success === void 0) {
        if (response.error.message !== void 0) {
          return A2Cribs.UIManager.Error(response.error.message);
        } else {
          return A2Cribs.UIManager.Error("There was an error adding your favorite. Contact help@cribspot.com if the error persists.");
        }
      } else {
        this.FavoritesListingIds.push(parseInt(listing_id, 10));
        fl_sidebar_item = $("#fl-sb-item-" + listing_id);
        if (fl_sidebar_item.length === 1) {
          fl_sidebar_item.find(".favorite").attr('title', 'Delete from Favorites');
          fl_sidebar_item.find(".favorite").addClass('active');
        }
        if (button != null) {
          $(button).attr('title', 'Delete from Favorites');
          $(button).addClass('active');
        }
        return this._setFavoriteCount();
      }
    };

    /*
    	Delete a favorite
    */

    FavoritesManager.DeleteFavorite = function(listing_id, button) {
      return $.ajax({
        url: myBaseUrl + "Favorites/DeleteFavorite/" + listing_id,
        type: "POST",
        context: this,
        success: function(response) {
          return A2Cribs.FavoritesManager.DeleteFavoriteCallback(response, listing_id, button);
        }
      });
    };

    FavoritesManager.DeleteFavoriteCallback = function(response, listing_id, button) {
      var fl_sidebar_item, index;
      response = JSON.parse(response);
      if (response.error !== void 0) {
        return A2Cribs.UIManager.Error(response.error.message);
      } else {
        index = A2Cribs.FavoritesManager.FavoritesListingIds.indexOf(parseInt(listing_id, 10));
        if (index !== -1) {
          A2Cribs.FavoritesManager.FavoritesListingIds.splice(index, 1);
        }
        fl_sidebar_item = $("#fl-sb-item-" + listing_id);
        if (fl_sidebar_item.length === 1) {
          fl_sidebar_item.find(".favorite").attr('title', 'Add to Favorites');
          fl_sidebar_item.find(".favorite").removeClass('active');
        }
        if (button != null) {
          $(button).attr('title', 'Add to Favorites');
          $(button).removeClass('active');
          return this._setFavoriteCount();
        }
      }
    };

    /*
    	response contains a list of listing_ids that have been favorited by the logged-in user
    */

    FavoritesManager.InitializeFavorites = function(listing_ids) {
      var listing_id, _i, _len;
      for (_i = 0, _len = listing_ids.length; _i < _len; _i++) {
        listing_id = listing_ids[_i];
        A2Cribs.FavoritesManager.FavoritesListingIds.push(parseInt(listing_id, 10));
      }
      return this._setFavoriteCount();
    };

    /*
    	Called when user clicks the heart icon in the header.
    	Toggles visibility of markers where user has favorited a listing.
    */

    FavoritesManager.ToggleFavoritesVisibility = function() {
      if (A2Cribs.Map.ToggleListingVisibility(FavoritesManager.FavoritesListingIds, "favorites")) {
        A2Cribs.Map.IsCluster(true);
        return $(".favorite_button").removeClass("active");
      } else {
        A2Cribs.Map.IsCluster(false);
        return $(".favorite_button").addClass("active");
      }
    };

    FavoritesManager.FavoritesVisibilityIsOn = function() {
      return $("#FavoritesHeaderIcon").hasClass("pressed");
    };

    /*
    	Initialize a heart icon for adding favorites
    */

    FavoritesManager.setFavoriteButton = function(div, listing_id) {
      if (FavoritesManager.FavoritesListingIds.indexOf(parseInt(listing_id, 10)) === -1) {
        return div.removeClass("active");
      } else {
        return div.addClass("active");
      }
    };

    FavoritesManager._setFavoriteCount = function() {
      if (this.FavoritesListingIds.length === 0) {
        return $(".favorite_count").hide();
      } else {
        return $(".favorite_count").show().text(this.FavoritesListingIds.length);
      }
    };

    /*
    	Removes the recent favorite into the favorites tab
    */

    FavoritesManager._removeFromFavoriteDiv = function(listing_id) {
      $('#personalFavoritesList').find('#favoriteDiv' + listing_id).remove();
      if (this.FavoritesCache.size === 0) return $('#noFavorites').show();
    };

    FavoritesManager._insertFavoriteCache = function(listing_id) {
      this.FavoritesCache[listing_id] = true;
      ++this.FavoritesCache.size;
      return $('#numFavorites').html(this.FavoritesCache.size);
    };

    FavoritesManager._removeFavoriteCache = function(listing_id) {
      this.FavoritesCache[listing_id] = null;
      --this.FavoritesCache.size;
      return $('#numFavorites').html(this.FavoritesCache.size);
    };

    return FavoritesManager;

  }).call(this);

  /*
  Manager class for all social networking functionality
  */

  A2Cribs.FacebookManager = (function() {

    function FacebookManager() {}

    FacebookManager.FacebookLogin = function() {
      var url;
      url = 'https://www.facebook.com/dialog/oauth?';
      url += 'client_id=450938858319396';
      url += '&redirect_uri=https://www.cribspot.com/login';
      url += '&scope=email';
      $(document).trigger("track_event", ["Login", "Logged in"]);
      return window.location.href = url;
    };

    FacebookManager.FacebookJSLogin = function() {
      return FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
          if ((response != null) && (response.authResponse != null)) {
            return A2Cribs.FacebookManager.AttemptLogin(response.authResponse);
          } else {
            return A2Cribs.UIManager.Error("We're having trouble logging you in with facebook, but don't worry!					You can still create an account with our regular login.");
          }
        } else {
          return FB.login(function(response) {
            if ((response != null) && (response.authResponse != null)) {
              return A2Cribs.FacebookManager.AttemptLogin(response.authResponse);
            } else {
              return A2Cribs.UIManager.Error("We're having trouble logging you in with facebook, but don't worry!						You can still create an account with our regular login.");
            }
          }, {
            scope: 'email'
          });
        }
      });
    };

    /*
    	Send signed request to server to finish registration
    */

    FacebookManager.AttemptLogin = function(authResponse) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + 'Users/AttemptFacebookLogin',
        data: authResponse,
        type: 'POST',
        success: function(response) {
          return console.log(response);
        },
        error: function(response) {
          return console.log(response);
        }
      });
    };

    FacebookManager.Logout = function() {
      alert('logging out');
      return $.ajax({
        url: myBaseUrl + "Users/Logout",
        type: "GET"
      });
    };

    FacebookManager.Login = function() {
      return alert('logging in');
    };

    FacebookManager.JSLogin = function() {
      return FB.login(A2Cribs.FacebookManager.JSLoginCallback);
    };

    FacebookManager.JSLoginCallback = function(response) {
      if (response.authResponse) {
        FB.api('/me', A2Cribs.FacebookManager.APICallback);
        return $.ajax({
          url: myBaseUrl + "Verify/FacebookVerify",
          type: "POST"
        });
      } else {
        return alert('failed');
      }
    };

    FacebookManager.FindMutualFriends = function() {
      var query;
      query = 'SELECT uid, first_name, last_name, pic_small FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + $("#userid_input").val() + ')';
      return FB.api({
        method: 'fql.query',
        query: query
      }, A2Cribs.FacebookManager.FindMutualFriendsCallback);
    };

    FacebookManager.FindMutualFriendsCallback = function(response) {
      return $("#numMutualFriendsVal").html(response.length);
    };

    FacebookManager.APICallback = function(response) {
      console.log(response);
      $(".facebook.unverified").toggleClass("unverified verified");
      return $(".facebook.verified").html(response.name + " is now verified.");
    };

    FacebookManager.UpdateLinkedinLogin = function(response) {
      $(".linkedin.unverified").toggleClass("unverified verified");
      $(".linkedin.verified").html(response.values[0].firstName + " " + response.values[0].lastName + " is now verified.");
      return $.ajax({
        url: myBaseUrl + "Verify/LinkedinVerify",
        type: "POST"
      });
    };

    FacebookManager.SubmitEmail = function() {
      var domain, email, emailRegEx, lastPart;
      email = $("#emailInput").val();
      emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
      if (email.search(emailRegEx) === -1) {
        alert("Email address is invalid");
        return;
      }
      domain = email.substring(email.indexOf("@") + 1);
      lastPart = domain.substring(domain.indexOf(".") + 1);
      if (lastPart.toLowerCase() === "edu") {
        $("#emailEduVerified").toggleClass("unverified verified");
        return $("#emailEduVerified").html("Verified edu email (" + domain.substring(0, domain.length - 4).toLowerCase() + ")");
      }
    };

    return FacebookManager;

  })();

  A2Cribs.UtilityFunctions = (function() {

    function UtilityFunctions() {}

    /*
    	returns the left and top offsets of an element relative to the entire page
    */

    UtilityFunctions.getPosition = function(el) {
      var lx, ly, x;
      lx = 0;
      ly = 0;
      while (true) {
        if (!el) break;
        lx += el.offsetLeft;
        ly += el.offsetTop;
        el = el.offsetParent;
      }
      x = {
        x: lx,
        y: ly
      };
      return x;
    };

    /*
    	Returns a date (year, month, day) formatted for Mysql
    */

    UtilityFunctions.GetFormattedDate = function(date) {
      var day, month, year;
      year = date.getUTCFullYear();
      month = date.getMonth() + 1;
      day = date.getDate();
      return year + '-' + month + '-' + day;
    };

    UtilityFunctions.getDateRange = function(startDate, endDate) {
      Date.prototype.addDays = function(days) {
                var dat = new Date(this.valueOf())
                dat.setDate(dat.getDate() + days);
                return dat; 
            };
      var currentDate, dateArray;
      dateArray = new Array();
      currentDate = startDate;
      while (currentDate <= endDate) {
        dateArray.push(currentDate);
        currentDate = currentDate.addDays(1);
      }
      return dateArray;
    };

    return UtilityFunctions;

  })();

  A2Cribs.PhotoPicker = (function() {
    var Photo,
      _this = this;

    function PhotoPicker() {}

    PhotoPicker.MAX_CAPTION_LENGTH = 25;

    PhotoPicker.MAX_PHOTOS = 24;

    PhotoPicker.MAX_FILE_SIZE = 5000000;

    /*
    	Class Photo
    	Holds all the information for each photo
    	also has some methods to make
    */

    Photo = (function() {
      /*
      		Photo Constructor
      		Takes integer for the index in the photo array,
      		an object that is either a preview for the image
      		to be saved or is the complete photo. A deferred
      		object is included if the photo has yet to be
      		saved.
      */
      function Photo(index, object, deferred) {
        this.index = index;
        if (deferred == null) deferred = null;
        this.Resolve = __bind(this.Resolve, this);
        if (deferred != null) {
          this.SetPreview(object);
          deferred.done(this.Resolve);
        } else {
          ({
            image_id: this._image_id = object.image_id
          });
          this.SaveCaption(object.caption);
          this._is_primary = object.is_primary;
          this._path = object.image_path;
          this._listing_id = object.listing_id;
        }
      }

      /*
      		Set Preview
      		Sets the preview of the sent object
      */

      Photo.prototype.SetPreview = function(preview) {
        this.preview = preview;
      };

      /*
      		Resolve
      		When the photo has been saved to the backend,
      		the resolve callback is called saving the other
      		parts of the image object to this photo
      */

      Photo.prototype.Resolve = function(data) {
        console.log(data.result);
        this._image_id = data.result.image_id;
        this._path = data.result.image_path;
        return this._listing_id = data.result.listing_id;
      };

      /*
      		Edit
      		Triggers an edit event to make the image
      		appear in the edit window
      */

      Photo.prototype.Edit = function() {
        return $(".image-row").trigger("edit_image", [this.index]);
      };

      /*
      		Delete
      		Triggers a delete on the index
      */

      Photo.prototype.Delete = function() {
        this.DeleteDeferred = null;
        if (this._image_id != null) {
          this.DeleteDeferred = $.ajax({
            url: myBaseUrl + "images/delete/" + this._image_id,
            type: "GET"
          });
        }
        return $(".image-row").trigger("delete_image", [this.index]);
      };

      /*
      		Make Primary
      */

      Photo.prototype.MakePrimary = function() {
        this._is_primary = true;
        return this.div.find(".primary").addClass('cur-primary');
      };

      /*
      		Unset Primary
      */

      Photo.prototype.UnsetPrimary = function() {
        this._is_primary = false;
        return this.div.find(".primary").removeClass('cur-primary');
      };

      Photo.prototype.IsPrimary = function() {
        if (this._is_primary != null) {
          return this._is_primary;
        } else {
          return false;
        }
      };

      /*
      		Get Preview
      		Returns either a canvas or an image of the photo
      */

      Photo.prototype.GetPreview = function() {
        var context, newCanvas, path_splice;
        if (this.preview != null) {
          newCanvas = document.createElement('canvas');
          context = newCanvas.getContext('2d');
          newCanvas.width = this.preview.width;
          newCanvas.height = this.preview.height;
          context.drawImage(this.preview, 0, 0);
          return newCanvas;
        } else {
          path_splice = this._path.split("/");
          return $("<img src='/" + path_splice[0] + "/" + path_splice[1] + "/med_" + path_splice[2] + "'>");
        }
      };

      /*
      		Get Caption
      		Returns the caption of the photo
      */

      Photo.prototype.GetCaption = function() {
        if (this._caption != null) {
          return this._caption;
        } else {
          return "";
        }
      };

      /*
      		Save Caption
      		Sets the caption of the photo
      */

      Photo.prototype.SaveCaption = function(_caption) {
        this._caption = _caption;
      };

      /*
      		Get Object
      		Returns the necessary fields for A2Cribs.Image
      		conversion
      */

      Photo.prototype.GetObject = function() {
        return {
          image_id: this._image_id,
          caption: this.GetCaption(),
          is_primary: +this.IsPrimary(),
          image_path: this._path,
          listing_id: this._listing_id
        };
      };

      /*
      		Get Html
      		Returns the html for the image container for 
      		this photo
      */

      Photo.prototype.GetHtml = function() {
        var path_splice,
          _this = this;
        if (!(this.div != null)) {
          this.div = $("<div id=\"prev_" + this.index + "\" class=\"imageContainer span6\">\n	<div class=\"imageContent imageThumb\"></div>\n	<div class = 'image-actions-container'>\n		<i class=\"delete icon-trash\"></i>\n		<i class=\"edit icon-edit\"></i>\n		<i class=\"primary icon-asterisk\"></i>\n	</div>\n</div>");
          if (this.preview != null) {
            this.div.find(".imageContent").html(this.preview);
          } else {
            path_splice = this._path.split("/");
            this.div.find(".imageContent").html("<img src='/" + path_splice[0] + "/" + path_splice[1] + "/med_" + path_splice[2] + "'>");
          }
          this.div.find(".delete").click(function() {
            return _this.Delete();
          });
          this.div.find(".edit, .imageContent").click(function() {
            return _this.Edit();
          });
          this.div.find(".primary").click(function() {
            return $(".image-row").trigger("set_primary", [_this.index]);
          });
          this.div.find(".delete").tooltip({
            'selector': '',
            'placement': 'bottom',
            'title': 'Delete'
          });
          this.div.find(".edit").tooltip({
            'selector': '',
            'placement': 'bottom',
            'title': 'Edit'
          });
          this.div.find(".primary").tooltip({
            'selector': '',
            'placement': 'bottom',
            'title': 'Make Primary'
          });
        }
        return this.div;
      };

      return Photo;

    })();

    /*
    	SetupUI
    	Attaches listeners to all the UI elements
    	in the photo manager
    */

    PhotoPicker.SetupUI = function(div) {
      var _this = this;
      this.div = div;
      this.Reset();
      this.div.find(".image-row").on("delete_image", function(event, index) {
        return _this.Delete(index);
      });
      this.div.find(".image-row").on("edit_image", function(event, index) {
        return _this.Edit(index);
      });
      this.div.find(".image-row").on("set_primary", function(event, index) {
        return _this.MakePrimary(index);
      });
      this.div.find('#upload_image').click(function() {
        return _this.div.find('#real-file-input').click();
      });
      this.div.find("#captionInput").keyup(function() {
        var caption;
        caption = _this.div.find("#captionInput").val();
        if (caption.length >= _this.MAX_CAPTION_LENGTH) {
          _this.div.find("#charactersLeft").css("color", "red");
        } else {
          _this.div.find("#charactersLeft").css("color", "black");
        }
        _this.div.find("#charactersLeft").html(_this.MAX_CAPTION_LENGTH - caption.length);
        return _this._photos[_this.CurrentPreviewImage].SaveCaption(caption);
      });
      return this.div.find('#ImageAddForm').fileupload({
        url: myBaseUrl + 'images/AddImage',
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(jpeg|jpg|png)$/i,
        singleFileUploads: true,
        maxFileSize: this.MAX_FILE_SIZE,
        loadImageMaxFileSize: this.MAX_FILE_SIZE,
        disableImageResize: false,
        previewMaxWidth: 300,
        previewMaxHeight: 300,
        previewCrop: true
      }).on('fileuploadadd', function(e, data) {
        if (_this._photos.size === 24) {
          A2Cribs.UIManager.Error("Sorry but Cribspot only allows for 24 pictures!");
          return false;
        }
      }).on('fileuploadprocessalways', function(e, data) {
        var file, index;
        index = data.index;
        file = data.files[index];
        if (file.error != null) {
          A2Cribs.UIManager.Error("Sorry - " + file.error);
          return false;
        }
        if (file.preview != null) {
          _this.CurrentUploadDeferred = $.Deferred();
          _this.div.find("#upload_image").button('loading');
          _this.CurrentUploadDeferred.always(function() {
            return _this.div.find("#upload_image").button('reset');
          });
          return _this.AddPhoto(new Photo(_this._photos.next_id, file.preview, _this.CurrentUploadDeferred));
        }
      }).on('fileuploaddone', function(e, data) {
        var _ref;
        if (((_ref = data.result) != null ? _ref.error : void 0) != null) {
          A2Cribs.UIManager.Error(data.result.error);
          return _this.CurrentUploadDeferred.reject();
        } else {
          return _this.CurrentUploadDeferred.resolve(data);
        }
      }).on('fileuploadfail', function(e, data) {
        A2Cribs.UIManager.Error("Sorry something went wrong. Please retry photo upload.");
        return _this.CurrentUploadDeferred.reject();
      });
    };

    /*
    	Open
    */

    PhotoPicker.Open = function(image_array) {
      this.Load(image_array);
      this.div.modal('show');
      this.PhotoPickerDeferred = $.Deferred();
      return this.PhotoPickerDeferred.promise();
    };

    /*
    	Load
    	Loads up the images to the photo manager
    	given an image array
    */

    PhotoPicker.Load = function(image_array) {
      var image, key, photo, _i, _len, _ref,
        _this = this;
      this.Reset();
      if ((image_array != null ? image_array.length : void 0) != null) {
        for (_i = 0, _len = image_array.length; _i < _len; _i++) {
          image = image_array[_i];
          this.AddPhoto(new Photo(this._photos.next_id, image));
        }
        _ref = this._photos;
        for (key in _ref) {
          photo = _ref[key];
          if ((photo.IsPrimary != null) && photo.IsPrimary()) {
            $(".image-row").trigger("set_primary", [photo.index]);
          }
        }
      }
      return this.div.find("#finish_photo").unbind('click').click(function() {
        var _ref2;
        if (((_ref2 = _this.CurrentUploadDeferred) != null ? _ref2.state() : void 0) === 'pending') {
          A2Cribs.UIManager.Success("Cribspot is still uploading your images. We'll be done in just a moment.");
        }
        return $.when(_this.CurrentUploadDeferred).then(function(resolved) {
          _this.div.modal('hide');
          return _this.PhotoPickerDeferred.resolve(_this.GetPhotos());
        });
      });
    };

    /*
    	Edit
    	Takes an index into the A2Cribs.Image
    	photo image array and displays it in the
    	main photo section to be edited
    */

    PhotoPicker.Edit = function(index) {
      this.CurrentPreviewImage = index;
      this.div.find("#imageContent0").html(this._photos[index].GetPreview());
      this.div.find("#captionInput").val(this._photos[index].GetCaption());
      return this.div.find("#charactersLeft").html(this.MAX_CAPTION_LENGTH - this._photos[index].GetCaption().length);
    };

    /*
    	Reset
    	Resets the UI for a new image set or to
    	load an existing set of images
    */

    PhotoPicker.Reset = function() {
      this.ResetMainPhoto();
      this.div.find(".image-row").empty();
      this.CurrentPrimaryImage = null;
      return this._photos = {
        size: 0,
        next_id: 0
      };
    };

    /*
    	Make Primary
    	Unsets previous primary image and makes
    	index primary
    */

    PhotoPicker.MakePrimary = function(index) {
      if (this.CurrentPrimaryImage != null) {
        this._photos[this.CurrentPrimaryImage].UnsetPrimary();
      }
      return this._photos[this.CurrentPrimaryImage = index].MakePrimary();
    };

    /*
    	Delete
    	Deletes the photo from both the UI and
    	the backend if the photo has been saved
    */

    PhotoPicker.Delete = function(index) {
      var photo,
        _this = this;
      photo = this._photos[index];
      this.RemovePhoto(index);
      $.when(photo.DeleteDeferred).done(function(response) {
        if (!(response != null) || (response.error != null)) {
          A2Cribs.UIManager.Error("Photo could not be deleted!");
          return _this.AddPhoto(photo);
        } else {
          return A2Cribs.UIManager.Success("Photo was successfully deleted!");
        }
      }).fail(function(response) {
        A2Cribs.UIManager.Error("Photo could not be deleted!");
        return _this.AddPhoto(photo);
      });
      if (this.CurrentPrimaryImage === index) return this.DefaultPrimaryPhoto();
    };

    /*
    	Add Photo
    	Pushes photo onto the photo array and
    	renders the preview for the photo box
    */

    PhotoPicker.AddPhoto = function(photo) {
      this._photos[this._photos.next_id] = photo;
      this._photos.size += 1;
      this._photos.next_id += 1;
      this.div.find(".image-row").append(photo.GetHtml());
      if (!(this.CurrentPrimaryImage != null)) return this.DefaultPrimaryPhoto();
    };

    /*
    	Remove Photo
    	Removes photo from the photo array and
    	updates the UI to show there is no more
    	photo
    */

    PhotoPicker.RemovePhoto = function(index) {
      this._photos.size -= 1;
      if (this.CurrentPreviewImage === index) {
        this.div.find("#imageContent0").html("<div class = 'img-place-holder'></div>");
        this.div.find("#captionInput").val("");
        this.div.find("#charactersLeft").text(this.MAX_CAPTION_LENGTH);
      }
      delete this._photos[index];
      return this.div.find("#prev_" + index).fadeOut();
    };

    /*
    	Reset Main Photo
    	Clears the UI for the main photo
    */

    PhotoPicker.ResetMainPhoto = function() {
      this.div.find("#imageContent0").html("<div class = 'img-place-holder'></div>");
      this.div.find("#captionInput").val("");
      this.div.find("#charactersLeft").text(this.MAX_CAPTION_LENGTH);
      return this.CurrentPreviewImage = null;
    };

    /*
    	Default Primary Photo
    	Defaults the current primary image to the first
    	Photo in _photos
    */

    PhotoPicker.DefaultPrimaryPhoto = function() {
      var key, photo, _ref, _results;
      this.CurrentPrimaryImage = null;
      _ref = this._photos;
      _results = [];
      for (key in _ref) {
        photo = _ref[key];
        if (photo.MakePrimary != null) {
          this.CurrentPrimaryImage = parseInt(key, 10);
          photo.MakePrimary();
          break;
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    /*
    	Get Photos
    	Returns an array of all the images that are in the
    	photo picker
    */

    PhotoPicker.GetPhotos = function() {
      var key, photo, results, _ref;
      results = [];
      _ref = this._photos;
      for (key in _ref) {
        photo = _ref[key];
        if (photo.GetObject != null) results.push(photo.GetObject());
      }
      return results;
    };

    /*
    	Document ready
    	Waits for the document to be loaded.
    	When loaded creates all the listeners
    	needed to connect the UI
    */

    $(document).ready(function() {
      if ($("#picture-modal").length) {
        return PhotoPicker.SetupUI($("#picture-modal"));
      }
    });

    return PhotoPicker;

  }).call(this);

  A2Cribs.ShareManager = (function() {
    var _this = this;

    function ShareManager() {}

    /*
    	Creates a listing url from its individual components
    */

    ShareManager.GetShareUrl = function(listing_id, street_address, city, state, zip) {
      var url;
      if (listing_id === null || street_address === null || city === null || state === null || zip === null) {
        return null;
      }
      street_address = street_address.split(' ').join('-');
      city = city.split(' ').join('-');
      url = 'https://cribspot.com/listing/' + listing_id;
      $(document).trigger("track_event", ["Share", "URL Copied", "", listing_id]);
      return url;
    };

    /*
    	Brings up a dialog box for user to add a message and then post to their facebook timeline
    */

    ShareManager.ShareListingOnFacebook = function(listing_id, street_address, city, state, zip, description, building_name) {
      var caption, fbObj, url;
      if (description == null) description = null;
      if (building_name == null) building_name = null;
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      caption = 'Check out this listing on Cribspot!';
      if (building_name === null) {
        building_name = street_address;
      } else {
        caption = street_address;
      }
      $(document).trigger("track_event", ["Share", "Listing on FB", "", listing_id]);
      fbObj = {
        method: 'feed',
        link: url,
        picture: 'https://s3-us-west-2.amazonaws.com/cribspot-img/upright_logo.png',
        name: building_name,
        caption: caption
      };
      if (description !== null) fbObj['description'] = description;
      return FB.ui(fbObj);
    };

    /*
    	Shares the sublet on facebook
    */

    ShareManager.ShareSubletOnFB = function(marker, sublet, images) {
      var fbObj, primary_image, url;
      url = 'https://cribspot.com/listing/' + sublet.listing_id;
      $(document).trigger("track_event", ["Share", "Listing on FB", "Completed Sublet", sublet.listing_id]);
      primary_image = 'https://s3-us-west-2.amazonaws.com/cribspot-img/upright_logo.png';
      fbObj = {
        method: 'feed',
        link: url,
        picture: primary_image,
        name: "" + (marker.GetName()) + " - Check out my sublet on Cribspot!",
        caption: "I am subletting my place on Cribspot. Message me if you are interested.",
        description: sublet.description
      };
      return FB.ui(fbObj, function(response) {
        if (response != null ? response.post_id : void 0) {
          return $(document).trigger("track_event", ["Share", "Listing on FB Completed", "Completed Sublet", sublet.listing_id]);
        }
      });
    };

    /*
    	Shares the school page on facebook
    */

    ShareManager.ShareOnFacebook = function() {
      var fbObj;
      $(document).trigger("track_event", ["Share", "Website on FB", "Header Button"]);
      fbObj = {
        method: 'feed',
        link: "https://cribspot.com/",
        picture: 'https://s3-us-west-2.amazonaws.com/cribspot-img/upright_logo.png',
        name: "Join Cribspot",
        caption: "It's a party!",
        description: "Make your life easier...use Cribspot. Search off-campus houses and apartments quickly."
      };
      return FB.ui(fbObj, function(response) {
        if (response != null ? response.post_id : void 0) {
          return $(document).trigger("track_event", ["Share", "Website on FB Completed", "Header Button"]);
        }
      });
    };

    ShareManager.FBPromotion = function() {
      var fbObj;
      $(document).trigger("track_event", ["Share", "Website on FB", "Wisconsin Sunglasses"]);
      fbObj = {
        method: 'feed',
        link: "https://cribspot.com/",
        picture: 'https://lh4.googleusercontent.com/-JCwU1KBqw1I/UnAMzgSnPeI/AAAAAAAAAIA/ySQHQfwYGFA/w726-h545-no/sunglasses.jpg',
        name: "Free Shades for Wisconsin Students!",
        caption: "You're gonna need to protect your eyes - your off-campus housing search is now looking pretty bright.",
        description: "To celebrate our recent launch at University of Wisconsin-Madison, we're giving away 5 pairs of these awesome sunglasses! Offer only valid for Wisconsin students - just share this post to qualify! We'll notify the winners on Thursday, October 31st."
      };
      return FB.ui(fbObj, function(response) {
        if (response != null ? response.post_id : void 0) {
          return $(document).trigger("track_event", ["Share", "Website on FB Completed", "Wisconsin Sunglasses"]);
        }
      });
    };

    ShareManager.CopyListingUrl = function(listing_id, street_address, city, state, zip) {
      var url;
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      return window.prompt("Copy to clipboard: Ctrl+C, Enter", url);
    };

    ShareManager.ShareSubletOnTwitter = function(listing_id) {
      var url, x, y;
      url = this.GetTwitterShareUrl(listing_id);
      $(document).trigger("track_event", ["Share", "Listing on Twitter", "Completed Sublet", listing_id]);
      x = screen.width / 2 - 600 / 2;
      y = screen.height / 2 - 350 / 2;
      return window.open(url, 'winname', "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=350,top=" + y + ",left=" + x);
    };

    ShareManager.ShareListingOnTwitter = function(listing_id, street_address, city, state, zip) {
      var url, x, y;
      url = this.GetTwitterShareUrl(listing_id);
      $(document).trigger("track_event", ["Share", "Listing on Twitter", "", listing_id]);
      x = screen.width / 2 - 600 / 2;
      y = screen.height / 2 - 350 / 2;
      return window.open(url, 'winname', "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=350,top=" + y + ",left=" + x);
    };

    ShareManager.GetTwitterShareUrl = function(listing_id) {
      var url;
      url = 'https://cribspot.com/listing/' + listing_id;
      return 'https://twitter.com/share?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent('Check out this listing on Cribspot!') + '&via=TheCribspot';
    };

    ShareManager.InitTweetButton = function(listing_id, street_address, city, state, zip) {
      var tweetBtn, url;
      if (listing_id === null || street_address === null || city === null || state === null || zip === null) {
        return null;
      }
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      $('#twitterDiv iframe').remove();
      tweetBtn = $('<a></a>').addClass('twitter-share-button').attr('href', 'https://twitter.com/share').attr('data-url', url).attr('data-text', 'Check out this awesome property on Cribspot.com! ' + url).attr('data-via', 'TheCribspot');
      $('#twitterDiv').append(tweetBtn);
      return twttr.widgets.load();
    };

    ShareManager.EmailInvite = function(email_list) {
      return $.ajax({
        url: myBaseUrl + "Invitations/InviteFriends",
        type: 'POST',
        data: {
          emails: email_list
        }
      });
    };

    /*
    	Show Share Modal
    	Will show the email or fb modal dependent on whether
    */

    ShareManager.ShowShareModal = function(subject, message, type) {
      var _this = this;
      return typeof FB !== "undefined" && FB !== null ? FB.getLoginStatus(function(response) {
        if (response.status === 'unknown') {
          $(document).trigger("track_event", ["Share", "Invite Friends", "Email Invite"]);
          $("#email_invite").modal("show");
          $("#email_invite").find(".modal_subject").text(subject);
          $("#email_invite").find(".modal_message").text(message);
          return $("#send_email_invite").unbind("click").click(function(event) {
            var emails;
            $("#send_email_invite").button("loading");
            emails = [];
            $(".completed_roommate").find(".roommate_email").each(function(index, element) {
              return emails.push($(element).val());
            });
            _this.EmailInvite(emails).always(function() {
              $("#email_invite").modal("hide");
              return $("#send_email_invite").button("reset");
            });
            return $(document).trigger("track_event", ["Share", "Invite Friends Completed", "Email Invite", emails != null ? emails.length : void 0]);
          });
        } else {
          $(document).trigger("track_event", ["Share", "Invite Friends", "FB Invite"]);
          return FB.ui({
            method: 'apprequests',
            message: message
          }, function(response) {
            var _ref;
            return $(document).trigger("track_event", ["Share", "Invite Friends", "FB Invite", (_ref = response.to) != null ? _ref.length : void 0]);
          });
        }
      }) : void 0;
    };

    $("#header").ready(function() {
      $(".share_on_fb").click(function() {
        return ShareManager.ShareOnFacebook();
      });
      return $(".promotion_on_fb").click(function() {
        return ShareManager.FBPromotion();
      });
    });

    $("#email_invite").ready(function() {
      $("#email_invite").on("keyup", ".roommate_email", function(event) {
        var re;
        re = /\S+@\S+\.\S+/;
        if (re.test($(event.currentTarget.parentElement).find(".roommate_email").val())) {
          $(event.currentTarget.parentElement).addClass("completed_roommate");
          return;
        }
        return $(event.currentTarget.parentElement).removeClass("completed_roommate");
      });
      return $(".add_roommate").click(function() {
        var email_row, row_count;
        row_count = $("#email_invite").find(".roommate_email").last().data("roommate-count");
        email_row = $("<div class='roommate_row'><input data-roommate-count='" + (row_count + 1) + "' class='roommate_email' type='email' placeholder='E.g. myhousem@te.com'><i class='icon-ok-sign'></i></div>");
        return $("#email_invite").find(".email_invite_list").append(email_row);
      });
    });

    return ShareManager;

  }).call(this);

  /*
  SmallBubble class
  Wrapper for google infobubble
  */

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

  /*
  LargeBubble class
  */

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
      if (position === null) return;
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
          var _ref;
          if ((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) {
            $('ul.friends, #hotlist').addClass('dragging');
            return A2Cribs.HotlistObj.startedDragging();
          }
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
      $(document).trigger("track_event", ["Listing", "Popup Opened", "", listing_id]);
      openDeferred = new $.Deferred();
      if (listing_id != null) {
        $("#loader").show();
        A2Cribs.UserCache.GetListing(A2Cribs.Map.ACTIVE_LISTING_TYPE, listing_id).done(function(listing) {
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
          if (event.keyCode === 13) return $(".share-to-email-btn").click();
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

    LargeBubble.linkWebsite = function(div_name, link, listing_id) {
      var _this = this;
      if (link != null) {
        return this.div.find(div_name).unbind("click").click(function() {
          var _ref;
          if (((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) === true) {
            $(document).trigger("track_event", ["Listing", "Go to website", "", listing_id]);
            return window.open("/listings/website/" + listing_id, '_blank');
          } else {
            $("#signup_modal").modal("show").find(".signup_message").text("Please signup to view this website");
            return $(document).trigger("track_event", ["Login", "Login required", "Go to website", listing_id]);
          }
        });
      } else {
        return this.div.find(div_name).unbind("click").click(function() {
          return A2Cribs.UIManager.Error('This owner does not have a website for this listing');
        });
      }
    };

    LargeBubble.setRent = function(rent) {
      if (!(rent != null)) {
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
        $(document).trigger("track_event", ["Listing", "View full page", "", listing_id]);
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
      if (y_high < (TOP + this.PADDING)) offset.y = y_high - (TOP + this.PADDING);
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

  A2Cribs.Cache = (function() {

    function Cache() {}

    Cache.IdToSubletMap = [];

    Cache.IdToMarkerMap = [];

    Cache.IdToUniversityMap = [];

    Cache.IdToHousematesMap = [];

    Cache.SubletIdToHousemateIdsMap = [];

    Cache.SubletIdToOwnerMap = [];

    Cache.SubletIdToImagesMap = [];

    Cache.MarkerIdToHoverDataMap = [];

    Cache.MarkerIdToSubletIdsMap = [];

    Cache.IdToMarkerMap = [];

    Cache.AddressToMarkerIdMap = [];

    Cache.BuildingIdToNameMap = [];

    Cache.BathroomIdToNameMap = [];

    Cache.GenderIdToNameMap = [];

    Cache.StudentTypeIdToNameMap = [];

    Cache.FavoritesSubletIdsList = [];

    Cache.FavoritesMarkerIdsList = [];

    Cache.IdToRentalMap = [];

    Cache.IdToParkingMap = [];

    Cache.ListingIdToUserMap = [];

    Cache.SubletEditInProgress = null;

    /*
    	Add list of sublets to cache
    */

    Cache.CacheSublet = function(sublet) {
      var bathroom, building, l;
      l = sublet;
      l.id = parseInt(l.id);
      l.marker_id = parseInt(l.marker_id);
      this.MarkerIdToSubletIdsMap[parseInt(sublet.marker_id)].push(l.id);
      l.number_bedrooms = parseInt(l.number_bedrooms);
      l.price_per_bedroom = parseInt(l.price_per_bedroom);
      l.number_bedrooms = parseInt(l.number_bedrooms);
      l.utility_cost = parseInt(l.utility_cost);
      l.deposit_amount = parseInt(l.deposit_amount);
      l.additional_fees_amount = parseInt(l.additional_fees_amount);
      l.marker_id = parseInt(l.marker_id);
      l.furnished_type_id = parseInt(l.furnished_type_id);
      building = this.IdToMarkerMap[l.marker_id].UnitType;
      l.bathroom_type_id = parseInt(l.bathroom_type_id);
      bathroom = this.BathroomIdToNameMap[l.bathroom_type_id];
      l.university_id = parseInt(l.university_id);
      return this.IdToSubletMap[l.id] = new A2Cribs.Sublet(l.id, l.university_id, building, l.name, l.street_address, l.city, l.state, l.date_begin, l.date_end, l.number_bedrooms, l.price_per_bedroom, l.short_description, bathroom, l.utility_cost, l.deposit_amount, l.additional_fees_description, l.additional_fees_amount, l.marker_id, l.flexible_dates, l.furnished_type_id, l.created, l.ac, l.parking);
    };

    /*
    	Add a list of subletIds to the MarkerIdToSubletIdsMap
    */

    Cache.CacheMarkerIdToSubletsList = function(sublets) {
      var sublet, _i, _len, _results;
      A2Cribs.Map.MarkerIdToSubletIdsMap[parseInt(sublets[0].Sublet.marker_id)] = [];
      _results = [];
      for (_i = 0, _len = sublets.length; _i < _len; _i++) {
        sublet = sublets[_i];
        if (sublet === void 0) continue;
        _results.push(this.MarkerIdToSubletIdsMap[parseInt(sublet.Sublet.marker_id)].push(parseInt(sublet.Sublets.sublet_id)));
      }
      return _results;
    };

    Cache.CacheUniversity = function(university) {
      var id;
      if (university === null) return;
      id = parseInt(university.id);
      return this.IdToUniversityMap[id] = new A2Cribs.University(university.city, university.domain, university.name, university.state);
    };

    Cache.CacheHoverData = function(hoverDataList) {
      /*
      		TODO: find min and max dates
      */
      var beds, building_type_id, hd, hdList, markerIdToHd, marker_id, maxBeds, maxDate, maxRent, minBeds, minDate, minRent, numListings, price, sublet, unitType, _i, _j, _len, _len2;
      markerIdToHd = [];
      for (_i = 0, _len = hoverDataList.length; _i < _len; _i++) {
        hd = hoverDataList[_i];
        marker_id = null;
        if (hd !== null) {
          marker_id = parseInt(hd.Sublet.marker_id);
          if (this.IdToMarkerMap[marker_id] === void 0) {
            continue;
          } else {
            if (markerIdToHd[marker_id] === void 0) markerIdToHd[marker_id] = [];
            markerIdToHd[marker_id].push(hd);
          }
        } else {
          continue;
        }
      }
      for (marker_id in markerIdToHd) {
        hdList = markerIdToHd[marker_id];
        numListings = hdList.length;
        sublet = hdList[0].Sublet;
        if (sublet === void 0 || sublet === null) return;
        unitType = this.IdToMarkerMap[marker_id].UnitType;
        minBeds = parseInt(sublet.number_bedrooms);
        maxBeds = parseInt(sublet.number_bedrooms);
        minRent = parseInt(sublet.price_per_bedroom);
        maxRent = parseInt(sublet.price_per_bedroom);
        minDate = sublet.date_begin;
        maxDate = sublet.date_end;
        for (_j = 0, _len2 = hdList.length; _j < _len2; _j++) {
          hd = hdList[_j];
          sublet = hd.Sublet;
          building_type_id = parseInt(sublet.building_type_id);
          beds = parseInt(sublet.number_bedrooms);
          price = parseInt(sublet.price_per_bedroom);
          if (beds < minBeds) minBeds = beds;
          if (beds > maxBeds) maxBeds = beds;
          if (price < minRent) minRent = price;
          if (price > maxRent) maxRent = price;
        }
        hd = new A2Cribs.HoverData(numListings, unitType, minBeds, maxBeds, minRent, maxRent, minDate, maxDate);
        this.MarkerIdToHoverDataMap[marker_id] = hd;
      }
    };

    Cache.CacheHousemates = function(housemates) {
      var gender, grad_status, id, quantity, sublet_id;
      if (!(housemates != null)) return;
      sublet_id = null;
      if (housemates.sublet_id != null) {
        sublet_id = parseInt(housemates.sublet_id);
      } else {
        return;
      }
      this.SubletIdToHousemateIdsMap[sublet_id] = [];
      id = parseInt(housemates.id);
      grad_status = this.StudentTypeIdToNameMap[parseInt(housemates.student_type_id)];
      gender = this.GenderIdToNameMap[parseInt(housemates.gender_type_id)];
      sublet_id = parseInt(housemates.sublet_id);
      quantity = parseInt(housemates.quantity);
      this.IdToHousematesMap[id] = new A2Cribs.Housemate(sublet_id, housemates.enrolled, housemates.major, housemates.seeking, grad_status, gender, quantity);
      return this.SubletIdToHousemateIdsMap[sublet_id].push(id);
    };

    Cache.CacheImages = function(imageList) {
      var caption, first_image, image, is_primary, path, sublet_id, _i, _len, _results;
      if (imageList === void 0 || imageList === null || imageList[0] === void 0) {
        return;
      }
      first_image = imageList[0];
      if (first_image === void 0 || first_image.sublet_id === void 0) return;
      sublet_id = parseInt(first_image.sublet_id);
      A2Cribs.Cache.SubletIdToImagesMap[sublet_id] = [];
      _results = [];
      for (_i = 0, _len = imageList.length; _i < _len; _i++) {
        image = imageList[_i];
        sublet_id = parseInt(image.sublet_id);
        path = "/" + image.image_path;
        is_primary = image.is_primary;
        caption = image.caption;
        _results.push(A2Cribs.Cache.SubletIdToImagesMap[sublet_id].push(new A2Cribs.Image(sublet_id, path, is_primary, caption)));
      }
      return _results;
    };

    Cache.CacheMarker = function(id, marker) {
      var m, unitType;
      m = marker;
      unitType = this.BuildingIdToNameMap[parseInt(m.building_type_id)];
      return this.IdToMarkerMap[id] = new A2Cribs.Marker(parseInt(id), m.street_address, m.alternate_name, unitType, m.latitude, m.longitude, m.city, m.state);
    };

    Cache.CacheSubletOwner = function(sublet_id, user) {
      var owner;
      owner = new A2Cribs.SubletOwner(user);
      return this.SubletIdToOwnerMap[sublet_id] = owner;
    };

    /*
    	Add sublet data to cache
    */

    Cache.CacheMarkerData = function(markerDataList) {
      var markerData, marker_id, sublet, _i, _len, _results;
      if (markerDataList[0] !== void 0 && markerDataList[0].Sublet !== void 0) {
        marker_id = parseInt(markerDataList[0].Sublet.marker_id);
        this.MarkerIdToSubletIdsMap[marker_id] = [];
      }
      _results = [];
      for (_i = 0, _len = markerDataList.length; _i < _len; _i++) {
        markerData = markerDataList[_i];
        sublet = markerData.Sublet;
        A2Cribs.Cache.CacheSublet(sublet);
        A2Cribs.Cache.CacheHousemates(markerData.Housemate);
        A2Cribs.Cache.CacheSubletOwner(parseInt(sublet.id), markerData.User);
        _results.push(A2Cribs.Cache.CacheImages(markerData.Image));
      }
      return _results;
    };

    Cache.CacheSubletAddStep1 = function(data) {
      return A2Cribs.Cache.Step1Data = data;
    };

    Cache.CacheSubletAddStep2 = function(data) {
      return A2Cribs.Cache.Step2Data = data;
    };

    Cache.CacheSubletAddStep3 = function(data) {
      return A2Cribs.Cache.Step3Data = data;
    };

    /*
    	Adds new rental object to IdToRentalMap
    */

    Cache.AddRental = function(rental) {
      rental.air = parseInt(rental.air);
      rental.beds = parseInt(rental.beds);
      rental.baths = parseInt(rental.baths);
      rental.building_type = parseInt(rental.building_type);
      rental.cable = parseInt(rental.cable);
      rental.deposit = parseInt(rental.deposit);
      rental.electric = parseInt(rental.electric);
      rental.furnished_type = parseInt(rental.furnished_type);
      rental.gas = parseInt(rental.gas);
      rental.heat = parseInt(rental.heat);
      rental.internet = parseInt(rental.internet);
      rental.listing_id = parseInt(rental.listing_id);
      rental.min_occupancy = parseInt(rental.min_occupancy);
      rental.max_occupancy = parseInt(rental.max_occupancy);
      rental.parking_spots = parseInt(rental.parking_spots);
      rental.parking_type = parseInt(rental.parking_type);
      rental.pets_type = parseInt(rental.pets_type);
      rental.rent = parseInt(rental.rent);
      rental.rental_id = parseInt(rental.rental_id);
      rental.sewage = parseInt(rental.sewage);
      rental.square_feet = parseInt(rental.square_feet);
      rental.trash = parseInt(rental.trash);
      rental.unit_style_options = parseInt(rental.unit_style_options);
      rental.utility_estimate_summer = parseInt(rental.utility_estimate_summer);
      rental.utility_estimate_winter = parseInt(rental.utility_estimate_winter);
      rental.water = parseInt(rental.water);
      rental.year_built = parseInt(rental.year_built);
      return this.IdToRentalMap[rental.rental_id] = rental;
    };

    /*
    	Creates a new Rental object from rental
    	Adds new rental object to IdToRentalMap
    */

    /*
    	Adds new parking object to IdToParkingMap
    */

    Cache.AddParking = function(parking) {
      return this.IdToParkingMap[parseInt(parking.parking_id)] = parking;
    };

    /*
    	Adds new user object to RentalIdToUserMap
    	IMPORTANT: only contains public, non-sensitive user data
    */

    Cache.AddUser = function(listing_id, user) {
      return this.ListingIdToUserMap[listing_id] = user;
    };

    /*
    	Adds listing to the appropriate cache based on listing_type
    */

    Cache.AddListing = function(listing) {
      if (listing === void 0 || listing === null) return;
      if (listing.Rental !== void 0) {
        this.AddRental(listing.Rental);
      } else if (listing.Parking !== void 0) {
        this.AddParking(listing.Parking);
      }
      return this.AddUser(parseInt(listing.Listing.listing_id), listing.User);
    };

    /*
    	Returns listing object specified by listing_id
    */

    Cache.GetListing = function(listing_id) {
      var listing;
      if (__indexOf.call(this.IdToRentalMap, listing_id) >= 0) {
        return this.IdToRentalMap[listing_id];
      }
      listing = null;
      $.ajax({
        url: myBaseUrl + "Listings/GetListing/" + listing_id,
        type: "GET",
        context: this,
        async: false,
        success: function(response) {
          listing = JSON.parse(response);
          return this.AddListing(listing[0]);
        }
      });
      if (listing !== null) {
        return listing[0];
      } else {
        return null;
      }
    };

    /*
    	Loads all listings owned by logged-in user
    	Loads PUBLIC user data for user into cache
    	Returns array of listings
    */

    Cache.GetListingsByLoggedInUser = function() {
      var listings;
      listings = null;
      $.ajax({
        url: myBaseUrl + "Listings/GetListingsByLoggedInUser",
        type: "GET",
        context: this,
        async: false,
        success: function(response) {
          var listing, _i, _len, _results;
          listings = JSON.parse(response);
          _results = [];
          for (_i = 0, _len = listings.length; _i < _len; _i++) {
            listing = listings[_i];
            _results.push(this.AddListing(listing));
          }
          return _results;
        }
      });
      return listings;
    };

    return Cache;

  })();

  A2Cribs.Housemate = (function() {

    function Housemate(SubletId, Enrolled, Major, Seeking, GradType, Gender, Quantity) {
      this.SubletId = SubletId;
      this.Enrolled = Enrolled;
      this.Major = Major;
      this.Seeking = Seeking;
      this.GradType = GradType;
      this.Gender = Gender;
      this.Quantity = Quantity;
    }

    return Housemate;

  })();

  A2Cribs.UIManager = (function() {

    function UIManager() {}

    UIManager._num_loaders = 0;

    /*
    	Show Loader
    	Takes a div (otherwise null). Shows loader
    	in the middle of the div otherwise the 
    	middle of the screen. Keeps track of the 
    	amount of loaders being displayed
    	TODO: ADD DIV SUPPORT (JUST GLOBAL FOR NOW)
    */

    UIManager.ShowLoader = function(div) {
      this._num_loaders++;
      return $("#loader").show();
    };

    /*
    	Hide Loader
    	Hides the spinner based on the div. If no 
    	div given then main loader. Only removes
    	the loader if loader count is 0.
    	TODO: ADD DIV SUPPORT (JUST GLOBAL FOR NOW)
    */

    UIManager.HideLoader = function(div) {
      if (--this._num_loaders === 0) return $("#loader").hide();
    };

    UIManager.Alert = function(message) {
      return alertify.alert(message);
    };

    UIManager.Error = function(message) {
      return alertify.error(message, 7000);
    };

    UIManager.Success = function(message) {
      return alertify.success(message);
    };

    UIManager.CloseLogs = function() {
      return $('.alertify-log').remove();
    };

    UIManager.FlashMessage = function() {
      if (typeof flash_message !== "undefined" && flash_message !== null) {
        return this[flash_message.method](flash_message.message, flash_message.callback);
      }
    };

    UIManager.Confirm = function(message, callback) {
      alertify.set({
        buttonFocus: "cancel"
      });
      return alertify.confirm(message, callback);
    };

    UIManager.ConfirmBox = function(message, labels, callback) {
      alertify.set({
        labels: labels,
        buttonFocus: "cancel"
      });
      return alertify.confirm(message, callback);
    };

    return UIManager;

  })();

  $(document).ready(function() {
    return setTimeout((function() {
      return A2Cribs.UIManager.FlashMessage();
    }), 2000);
  });

  A2Cribs.Image = (function(_super) {

    __extends(Image, _super);

    /*
    	Image is an array of all the images associated with a listing
    */

    function Image(image, listing_id) {
      var i, image_object, _len, _ref;
      if (listing_id == null) listing_id = null;
      if (listing_id != null) {
        this.listing_id = listing_id;
        this.class_name = "image";
        this.image_array = image;
      }
      if (image.length !== 0) {
        this.class_name = "image";
        this.image_array = image;
        _ref = this.image_array;
        for (i = 0, _len = _ref.length; i < _len; i++) {
          image_object = _ref[i];
          if (image_object.is_primary) this.primary = i;
        }
        this.listing_id = this.image_array[0].listing_id;
      }
    }

    Image.prototype.GetId = function() {
      return this.listing_id;
    };

    Image.prototype.GetPrimary = function(field) {
      if (field == null) field = 'image_path';
      if (this.primary != null) {
        return this.image_array[this.primary][field];
      } else if (this.image_array.length !== 0) {
        return this.image_array[0][field];
      }
    };

    Image.prototype.GetImages = function() {
      return this.image_array;
    };

    Image.prototype.GetObject = function() {
      var image, img_copy, key, return_array, value, _i, _len, _ref;
      return_array = [];
      _ref = this.image_array;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        image = _ref[_i];
        img_copy = {};
        for (key in image) {
          value = image[key];
          if (typeof value !== "function") {
            if (typeof value === "boolean") value = +value;
            img_copy[key] = value;
          }
        }
        return_array.push(img_copy);
      }
      return return_array;
    };

    return Image;

  })(A2Cribs.Object);

  A2Cribs.Rental = (function(_super) {

    __extends(Rental, _super);

    Rental.UNIT_STYLE = ["Unit", "Layout", "Entire House"];

    function Rental(rental) {
      var date, dates, index, _i, _len;
      Rental.__super__.constructor.call(this, "rental", rental);
      dates = ["start_date", "end_date", "alternate_start_date"];
      for (_i = 0, _len = dates.length; _i < _len; _i++) {
        date = dates[_i];
        if (this[date]) {
          if ((index = this[date].indexOf(" ")) !== -1) {
            this[date] = this[date].substring(0, index);
          }
        }
      }
    }

    Rental.prototype.GetUnitStyle = function() {
      return A2Cribs.Rental.UNIT_STYLE[this.unit_style_options];
    };

    Rental.prototype.GetId = function() {
      return parseInt(this["listing_id"], 10);
    };

    Rental.prototype.IsComplete = function() {
      if (this.rental_id != null) {
        return true;
      } else {
        return false;
      }
    };

    Rental.Required_Fields = {};

    return Rental;

  })(A2Cribs.Object);

  A2Cribs.FilterManager = (function() {

    function FilterManager() {}

    FilterManager.UpdateListings = function(visibleListingIds) {
      var all_listings, all_markers, listing, listing_id, marker, sidebar_visible_listings, visible_listings, visible_markers, _i, _j, _k, _len, _len2, _len3;
      visible_listings = JSON.parse(visibleListingIds);
      sidebar_visible_listings = [];
      $("#map_region").trigger('close_bubbles');
      all_listings = A2Cribs.UserCache.Get("listing");
      for (_i = 0, _len = all_listings.length; _i < _len; _i++) {
        listing = all_listings[_i];
        listing.visible = false;
      }
      visible_markers = {};
      for (_j = 0, _len2 = visible_listings.length; _j < _len2; _j++) {
        listing_id = visible_listings[_j];
        listing = A2Cribs.UserCache.Get("listing", listing_id);
        if (listing != null) {
          listing.visible = true;
          sidebar_visible_listings.push(listing.listing_id);
          visible_markers[+listing.marker_id] = true;
        }
      }
      all_markers = A2Cribs.UserCache.Get("marker");
      for (_k = 0, _len3 = all_markers.length; _k < _len3; _k++) {
        marker = all_markers[_k];
        if (visible_markers[+marker.marker_id]) {
          marker.GMarker.setVisible(true);
        } else {
          marker.GMarker.setVisible(false);
        }
      }
      A2Cribs.FeaturedListings.UpdateSidebar(sidebar_visible_listings);
      return A2Cribs.Map.Repaint();
    };

    /*
    	Initialize the underlying google maps functionality of the address search bar
    */

    FilterManager.InitAddressSearch = function() {
      return A2Cribs.FilterManager.Geocoder = new google.maps.Geocoder();
    };

    FilterManager.SearchForAddress = function(div) {
      var address, request,
        _this = this;
      if (!(A2Cribs.FilterManager.Geocoder != null)) {
        A2Cribs.FilterManager.Geocoder = new google.maps.Geocoder();
      }
      address = $(div).val();
      request = {
        location: A2Cribs.Map.GMap.getCenter(),
        radius: 8100,
        types: ['street_address', 'street_number', 'postal_code', 'postal_code_prefix', 'point_of_interest', 'neighborhood', 'intersection', 'transit_station'],
        keyword: address,
        name: address
      };
      return A2Cribs.FilterManager.Geocoder.geocode({
        'address': address + " " + A2Cribs.FilterManager.CurrentCity + ", " + A2Cribs.FilterManager.CurrentState
      }, function(response, status) {
        if (status === google.maps.GeocoderStatus.OK && response[0].types[0] !== "postal_code") {
          $(div).effect("highlight", {
            color: "#5858FA"
          }, 2000);
          A2Cribs.Map.GMap.panTo(response[0].geometry.location);
          return A2Cribs.Map.GMap.setZoom(18);
        } else {
          return $(div).effect("highlight", {
            color: "#FF0000"
          }, 2000);
        }
      });
    };

    return FilterManager;

  })();

  A2Cribs.RentalFilter = (function(_super) {
    var loadPreviewText;

    __extends(RentalFilter, _super);

    function RentalFilter() {
      RentalFilter.__super__.constructor.apply(this, arguments);
    }

    RentalFilter.FilterData = {};

    /*
    	Private method for loading the contents of the filter preview into the header filter
    */

    loadPreviewText = function(div, text) {
      var title;
      title = $(div).closest(".filter_content").attr("data-link");
      return $(title).find(".filter_preview").html(text);
    };

    RentalFilter.CreateListeners = function() {
      var _this = this;
      $("#filter_search_content").keyup(function(event) {
        if (event.keyCode === 13) {
          A2Cribs.FilterManager.SearchForAddress(event.delegateTarget);
          return $(event.delegateTarget).select();
        }
      });
      /*
      		On Change listeners for applying changed fields
      */
      this.div.find(".lease_slider").on("slideStop", function(event) {
        return _this.ApplyFilter("LeaseRange", {
          min: parseInt(event.value[0], 10),
          max: parseInt(event.value[1], 10)
        });
      });
      this.div.find(".rent_slider").on("slideStop", function(event) {
        return _this.ApplyFilter("Rent", {
          min: parseInt(event.value[0], 10),
          max: parseInt(event.value[1], 10)
        });
      });
      /*
      		Bed filter click event listener
      		Finds range of beds and applies the changes of bed amounts
      */
      this.div.find("#bed_filter").find(".btn").click(function(event) {
        var button_group, max, min, selected_list, text;
        selected_list = [];
        min = 1000;
        max = -1;
        $(event.delegateTarget).toggleClass("active");
        button_group = $(event.delegateTarget).parent();
        button_group.find(".btn.active").each(function() {
          var val;
          val = parseInt($(this).val(), 10);
          selected_list.push(val);
          min = Math.min(min, val);
          return max = Math.max(max, val);
        });
        if (selected_list.length === 0) {
          loadPreviewText(event.delegateTarget, "");
          return _this.ApplyFilter("Beds", null);
        } else {
          _this.ApplyFilter("Beds", selected_list);
          if (selected_list.length === 1) {
            if (min === 0) {
              text = "<div class='filter_data'>Studio</div>";
            } else if (min === 1) {
              text = "<div class='filter_data'>" + min + "</div><div class='filter_label'>&nbsp;bed</div>";
            } else {
              text = "<div class='filter_data'>" + min + "</div><div class='filter_label'>&nbsp;beds</div>";
            }
            return loadPreviewText(event.delegateTarget, text);
          } else {
            return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + min + "-" + max + "</div><div class='filter_label'>&nbsp;beds</div>");
          }
        }
      });
      this.div.find("#year_filter").change(function(event) {
        var dates, year;
        dates = _this.FilterData.Dates;
        year = $(event.delegateTarget).val();
        if (dates != null) {
          dates.year = year;
          return _this.ApplyFilter("Dates", dates);
        } else {
          return _this.ApplyFilter("Dates", {
            months: [],
            year: year
          });
        }
      });
      this.div.find("#start_filter").find(".btn").click(function(event) {
        var button_group, monthText, selected_list;
        selected_list = [];
        $(event.delegateTarget).toggleClass("active");
        button_group = $(event.delegateTarget).parent();
        monthText = "";
        button_group.find(".btn.active").each(function() {
          selected_list.push($(this).attr("data-month"));
          return monthText = $(this).text();
        });
        if (selected_list.length === 0) {
          loadPreviewText(event.delegateTarget, "");
          return _this.ApplyFilter('Dates', null);
        } else {
          _this.ApplyFilter('Dates', {
            months: selected_list,
            year: _this.div.find("#year_filter").val()
          });
          if (selected_list.length === 1) {
            return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + monthText + "</div><div class='filter_label'>&nbsp;start</div>");
          } else {
            return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + selected_list.length + "</div><div class='filter_label'>&nbsp;starts</div>");
          }
        }
      });
      this.div.find("input[type='checkbox']").change(function(event) {
        var filterType, group, selected_list;
        group = $(event.target).closest(".filter_content");
        filterType = $(event.delegateTarget).attr("data-filter");
        selected_list = [];
        group.find("input[type='checkbox']").each(function() {
          if (this.checked) return selected_list.push($(this).attr("data-value"));
        });
        if (filterType === "UnitTypes") {
          _this.ApplyFilter(filterType, selected_list);
        } else {
          _this.ApplyFilter(filterType, +event.delegateTarget.checked);
        }
        if (selected_list.length === 0) {
          return loadPreviewText(event.delegateTarget, "");
        } else {
          if (group.attr("id").indexOf("more") === -1) {
            if (selected_list.length === 1) {
              return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + selected_list.length + "</div><div class='filter_label'>&nbsp;type</div>");
            } else {
              return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + selected_list.length + "</div><div class='filter_label'>&nbsp;types</div>");
            }
          } else {
            return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + selected_list.length + "</div><div class='filter_label'>&nbsp;more</div>");
          }
        }
      });
      return this.div.find(".hidden_input").change(function(event) {
        var date, date_split, filter;
        console.log(event.currentTarget.value);
        date = _this.GetBackendDateFormat(event.currentTarget.value);
        filter = $(event.currentTarget).data("filter");
        _this.ApplyFilter(filter, date);
        date_split = event.currentTarget.value.split("/");
        if (filter.indexOf("Start") !== -1) {
          return $(event.currentTarget).parent().find(".filter_title").text("Starts: " + date_split[0] + "/" + date_split[1]);
        } else if (filter.indexOf("End") !== -1) {
          return $(event.currentTarget).parent().find(".filter_title").text("Ends: " + date_split[0] + "/" + date_split[1]);
        }
      });
    };

    /*
    	Creates all listeners and jquery events for RentalFilter
    */

    RentalFilter.SetupUI = function() {
      var _this = this;
      this.div = $("#map_filter");
      $(".hidden_input").datepicker({
        onClose: function(date) {
          return $(".filter_link").removeClass("active");
        }
      });
      $("#start_date_filter_link, #end_date_filter_link").click(function(event) {
        return $(event.currentTarget).find(".hidden_input").datepicker('show');
      });
      $("#filter_search_btn").click(function() {
        if ($("#filter_search_content").is(":visible")) {
          return $("#filter_search_content").hide('slide', {
            direction: 'left'
          }, 300);
        } else {
          $("#filter_search_content").show('slide', {
            direction: 'left'
          }, 300);
          return $("#filter_search_content").focus();
        }
      });
      this.div.find(".lease_slider").slider({
        min: 0,
        max: 12,
        step: 1,
        value: [0, 12],
        tooltip: 'hide'
      }).on("slide", function(event) {
        var max_desc;
        max_desc = event.value[1] > 1 ? "&nbsp;months" : "&nbsp;month";
        _this.div.find("#lease_min").text(event.value[0]);
        _this.div.find("#lease_min_desc").html(event.value[0] > 1 ? "&nbsp;months" : "&nbsp;month");
        _this.div.find("#lease_max").text(event.value[1]);
        _this.div.find("#lease_max_desc").html(max_desc);
        if (event.value[0] === event.value[1]) {
          return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + event.value[0] + "</div><div class='filter_label'>" + max_desc + "</div>");
        } else {
          return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + event.value[0] + "-" + event.value[1] + "</div><div class='filter_label'>" + max_desc + "</div>");
        }
      });
      this.div.find(".rent_slider").slider({
        min: 0,
        max: 5000,
        step: 100,
        value: [0, 5000],
        tooltip: 'hide'
      }).on("slide", function(event) {
        var max_amount, min_amount;
        min_amount = "$" + event.value[0];
        max_amount = event.value[1] === 5000 ? "$" + event.value[1] + "+" : "$" + event.value[1];
        _this.div.find("#rent_min").text(min_amount);
        _this.div.find("#rent_max").text(max_amount);
        return loadPreviewText(event.delegateTarget, "<div class='filter_data'>" + min_amount + "-" + max_amount + "</div>");
      });
      this.div.find(".filter_link").click(function(event) {
        var content, lastTab;
        content = $(event.delegateTarget).attr("data-filter");
        lastTab = _this.div.find(".filter_link.active");
        _this.div.find(".filter_link").removeClass("active");
        if (lastTab.length && lastTab.find(".filter_preview").html().length) {
          lastTab.find(".filter_title").hide();
          lastTab.find(".filter_preview").show();
        }
        if ($(lastTab).attr('id') !== $(event.delegateTarget).attr('id')) {
          $(event.delegateTarget).addClass("active");
          $(event.delegateTarget).find(".filter_preview").hide();
          $(event.delegateTarget).find(".filter_title").show();
        }
        return _this.div.find("#filter_dropdown").slideUp("fast", function() {
          _this.div.find(".filter_content").hide();
          if ($(lastTab).attr('id') !== $(event.delegateTarget).attr('id')) {
            _this.div.find(content).show();
            return _this.div.find("#filter_dropdown").slideDown();
          }
        });
      });
      this.div.find('#rentals-filter-label').click(function(event) {
        var lastTab;
        lastTab = _this.div.find(".filter_link.active");
        _this.div.find("#filter_dropdown").slideUp("fast");
        if (lastTab.length && lastTab.find(".filter_preview").html().length) {
          lastTab.find(".filter_title").hide();
          lastTab.find(".filter_preview").show();
        }
        return _this.div.find(".filter_link").removeClass("active");
      });
      return this.CreateListeners();
    };

    /*
    	Called immediately after user applies a filter.
    	Submits an ajax call with all current filter parameters
    */

    RentalFilter.ApplyFilter = function(field, value) {
      var ajaxData, first, key, _ref;
      if (value != null) {
        this.FilterData[field] = value;
      } else {
        delete this.FilterData[field];
      }
      ajaxData = '';
      first = true;
      _ref = this.FilterData;
      for (key in _ref) {
        value = _ref[key];
        if (!first) ajaxData += "&";
        first = false;
        ajaxData += key + "=" + JSON.stringify(value);
      }
      $("#loader").show();
      return $.ajax({
        url: myBaseUrl + ("Listings/ApplyFilter/" + A2Cribs.FilterManager.ActiveListingType),
        data: ajaxData,
        type: "GET",
        context: this,
        success: A2Cribs.FilterManager.UpdateListings,
        complete: function() {
          return $("#loader").hide();
        }
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

    /*
    	Get Backend Date Format
    	Replaces '/' with '-' to make convertible to db format
    */

    RentalFilter.GetBackendDateFormat = function(dateString) {
      var beginDateFormatted, date, day, month, year;
      date = new Date(dateString);
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = year + "-" + month + "-" + day;
    };

    return RentalFilter;

  })(A2Cribs.FilterManager);

  A2Cribs.FLDash = (function() {

    function FLDash(uiWidget) {
      var _this = this;
      this.uiWidget = uiWidget;
      this.OrderStates = {};
      this.ListingUniPricing = {};
      this.FL_Order = null;
      this.uiFL_Form = $('.featured-listing-order-item').first();
      this.uiListingsList = this.uiWidget.find('#listings_list');
      this.uiOrderItemsList = this.uiWidget.find('#orderItems_list');
      this.uiErrorsList = this.uiWidget.find("#validation-error-list");
      this.initTemplates();
      this.setupEventHandlers();
      $.when(A2Cribs.Dashboard.GetListings().then(function() {
        return _this.loadListings();
      }));
    }

    FLDash.prototype.setupEventHandlers = function() {
      var _this = this;
      this.uiListingsList.on('mouseenter', '.listing-item', function(event) {
        $(event.currentTarget).find('.feature-star').removeClass('icon-star-empty');
        return $(event.currentTarget).find('.feature-star').addClass('icon-star');
      }).on('mouseleave', '.listing-item', function(event) {
        $(event.currentTarget).find('.feature-star').removeClass('icon-star');
        return $(event.currentTarget).find('.feature-star').addClass('icon-star-empty');
      }).on('click', '.listing-item', function(event) {
        var listing_id;
        listing_id = $(event.currentTarget).data('id');
        if (!(_this.OrderStates[listing_id] != null)) {
          _this.addOrderItem(listing_id);
        }
        return _this.editOrderItem(listing_id);
      }).on('click', '.marker-info', function(event) {
        var marker_info;
        marker_info = $(event.currentTarget);
        marker_info.siblings('ul').slideToggle('fast');
        return marker_info.find('i').toggleClass("icon-plus").toggleClass('icon-minus');
      });
      this.uiOrderItemsList.on('click', 'a', function(event) {
        var id, target;
        target = $(event.currentTarget);
        id = target.data('id');
        if (target.hasClass('edit')) {
          return _this.editOrderItem(id);
        } else if (target.hasClass('remove')) {
          return _this.removeOrderItem(id);
        }
      });
      this.uiErrorsList.on('click', '.icon-remove', function(event) {
        var listing_id;
        listing_id = $(event.currentTarget).parent().data('id');
        return _this.removeErrors(listing_id);
      });
      this.uiWidget.find("#buyNow").click(function() {
        return _this.buy();
      });
      this.uiWidget.find(".feature-listing").click(function() {
        return _this.featureListing();
      });
      this.uiFL_Form.on('orderItemChanged', function(event, FL) {
        var listing_id, total;
        listing_id = FL.listing_id;
        _this.uiOrderItemsList.find(".orderItem[data-id=" + listing_id + "] .price").html("" + (FL.getPrice().toFixed(2)));
        total = 0;
        _this.uiOrderItemsList.find(".price").each(function(index, element) {
          return total += Number($(element).html());
        });
        return _this.uiOrderItemsList.siblings('tfoot').find('.total').html("" + (total.toFixed(2)));
      });
      return $('#fl-list-input').keyup(function(event) {
        return $("#listings_list .marker-item").show().filter(function() {
          if ($(this).find(".marker-info").text().toLowerCase().indexOf($("#fl-list-input").val().toLowerCase()) === -1) {
            return true;
          }
          return false;
        }).hide();
      });
    };

    FLDash.prototype.loadListings = function() {
      var address, alt_name, data, description, formattedRental, icon, list, list_item, listing, listing_id, listing_ids, listing_list, marker, marker_data, marker_id, marker_item, rental, unit_style_description, unit_style_options, _i, _j, _len, _len2, _ref;
      list = "";
      marker_data = {};
      _ref = A2Cribs.UserCache.Get('listing');
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        listing = _ref[_i];
        if (!(marker_data[listing.marker_id] != null)) {
          marker_data[listing.marker_id] = [];
        }
        marker_data[listing.marker_id].push(listing.listing_id);
      }
      for (marker_id in marker_data) {
        if (!__hasProp.call(marker_data, marker_id)) continue;
        listing_ids = marker_data[marker_id];
        marker = A2Cribs.UserCache.Get('marker', marker_id);
        listing_list = "";
        address = marker.street_address;
        alt_name = marker_data.alt_name;
        for (_j = 0, _len2 = listing_ids.length; _j < _len2; _j++) {
          listing_id = listing_ids[_j];
          listing = A2Cribs.UserCache.Get('listing', listing_id);
          icon = '';
          switch (parseInt(listing.listing_type)) {
            case 0:
              icon = 'icon-home';
              break;
            case 1:
              icon = 'icon-lemon';
              break;
            case 2:
              icon = 'icon-truck';
          }
          rental = A2Cribs.UserCache.GetAllAssociatedObjects('rental', 'listing', listing.listing_id);
          unit_style_options = "";
          unit_style_description = "";
          if ((rental != null) && rental[0] !== void 0) {
            formattedRental = rental[0];
          }
          description = 'Listing ' + listing_id;
          if ((formattedRental != null) && formattedRental.unit_style_options !== void 0 && formattedRental.unit_style_description !== void 0) {
            if (parseInt(formattedRental.unit_style_options) === 0) {
              unit_style_options = "Unit";
            }
            if (parseInt(formattedRental.unit_style_options) === 1) {
              unit_style_options = "Layout";
            }
            if (parseInt(formattedRental.unit_style_options) === 2) {
              unit_style_options = "Entire House";
            }
            description += unit_style_options;
            if (unit_style_options !== "Entire House") {
              description += " - " + formattedRental.unit_style_description;
            }
          }
          data = {
            icon: icon,
            address: address,
            description: description,
            listing_id: listing_id
          };
          list_item = this.ListingTemplate(data);
          listing_list += list_item;
        }
        data = {
          marker: marker,
          num_listings: listing_ids.length,
          listing_list: listing_list
        };
        marker_item = this.MarkerTemplate(data);
        list += marker_item;
        $("#listings_list_content").append(marker_item);
      }
      return this.uiListingsList.html(list);
    };

    FLDash.prototype.getUniData = function(listing_id) {
      var d, url,
        _this = this;
      if (listing_id == null) listing_id = null;
      if (!(this.ListingUniPricing[listing_id] != null)) {
        d = new $.Deferred();
        url = "/featuredListings/getUniDataForListing/" + listing_id;
        $.ajax({
          url: url,
          type: 'GET',
          success: function(data) {
            return d.resolve(JSON.parse(data));
          }
        });
        this.ListingUniPricing[listing_id] = d.promise();
      }
      return this.ListingUniPricing[listing_id];
    };

    FLDash.prototype.addOrderItem = function(listing_id) {
      var data, listing, marker;
      listing = A2Cribs.UserCache.Get('listing', listing_id);
      marker = A2Cribs.UserCache.Get('marker', listing.marker_id);
      data = {
        address: marker.street_address,
        price: 0.00,
        id: listing.listing_id
      };
      this.OrderStates[listing_id] = {};
      return this.uiOrderItemsList.append(this.OrderItemTemplate(data));
    };

    FLDash.prototype.editOrderItem = function(listing_id) {
      var address, id, initialState, listing, old_id,
        _this = this;
      listing = A2Cribs.UserCache.Get('listing', listing_id);
      if (this.FL_Order != null) {
        old_id = this.FL_Order.listing_id;
        this.uiOrderItemsList.find(".orderItem[data-id=" + old_id + "]").removeClass('editing');
        this.OrderStates[old_id] = this.FL_Order.getState();
        this.FL_Order.reset(false);
      }
      initialState = this.OrderStates[listing_id] != null ? this.OrderStates[listing_id] : null;
      address = A2Cribs.UserCache.Get('marker', listing.marker_id).street_address;
      id = listing_id;
      $.when(this.getUniData(listing_id)).then(function(uniData) {
        return _this.FL_Order = new A2Cribs.Order.FeaturedListing(_this.uiFL_Form, listing.listing_id, address, uniData, initialState);
      });
      this.uiOrderItemsList.find(".orderItem[data-id=" + listing_id + "]").addClass('editing');
      return this.toggleOrderDetailsUI(true);
    };

    FLDash.prototype.removeOrderItem = function(listing_id) {
      var different_id, _ref;
      if (listing_id == null) listing_id = null;
      if (listing_id === null) {
        this.uiOrderItemsList.find(".orderItem").remove();
        this.OrderStates = {};
        this.FL_Order.reset();
        this.FL_Order = null;
      } else {
        this.uiOrderItemsList.find(".orderItem[data-id=" + listing_id + "]").remove();
        this.removeErrors(listing_id);
        delete this.OrderStates[listing_id];
        if (parseInt((_ref = this.FL_Order) != null ? _ref.listing_id : void 0, 10) === listing_id) {
          this.FL_Order.reset();
          this.FL_Order = null;
        }
      }
      if (this.uiOrderItemsList.find(".orderItem").length === 0) {
        return this.toggleOrderDetailsUI(false);
      } else {
        different_id = this.uiOrderItemsList.find(".orderItem").first().data('id');
        return this.editOrderItem(different_id);
      }
    };

    FLDash.prototype.initTemplates = function() {
      var ListingHTML, MarkerHTML, OrderItemHTML;
      ListingHTML = "<li class = 'listing-item' data-id='<%= listing_id %>'>\n    <i class = 'icon-large <%= icon %> listing-icon'></i><strong><%= description %></strong>\n    <i class = 'pull-right feature-star icon-star-empty'></i>\n</li>";
      this.ListingTemplate = _.template(ListingHTML);
      MarkerHTML = "<div class = 'marker-item' data-id='<%= marker.marker_id %>'>\n    <div class = 'marker-info'><i class = 'icon-plus'></i><strong><%= marker.street_address %></strong>  <%= marker.alternate_name %> (<%=num_listings%>)</div>\n    <ul><%= listing_list %></ul>\n</div>";
      this.MarkerTemplate = _.template(MarkerHTML);
      OrderItemHTML = "<tr class = 'orderItem' data-id = '<%= id %>'>\n    <td><span  class = 'address'><%= address %></span></td>\n    <td>$<span class = 'price'?><%= price %></span></td>\n    <td class = 'actions'>\n        <a href = '#' class = 'edit' data-id = '<%= id %>'><i class = 'icon-edit'></i></a>   \n        <a href = '#' class = 'remove' data-id = '<%= id %>'><i class = 'icon-remove-circle'></i></a>\n    </td>\n</tr>\n";
      return this.OrderItemTemplate = _.template(OrderItemHTML);
    };

    FLDash.prototype.showErrors = function(errors) {
      var addr, error_msgs, html, index, listing_id, msg, oi, _len;
      html = "";
      for (listing_id in errors) {
        if (!__hasProp.call(errors, listing_id)) continue;
        error_msgs = errors[listing_id];
        oi = this.uiOrderItemsList.find(".orderItem[data-id=" + listing_id + "]");
        oi.addClass('error');
        addr = oi.find('.address').html();
        html += "<dt data-id='" + listing_id + "'>Validation Errors for " + addr + "<i class = 'icon-remove'></i></dt>";
        for (index = 0, _len = error_msgs.length; index < _len; index++) {
          msg = error_msgs[index];
          html += "<dd data-id='" + listing_id + "' class = 'validation-error'>" + (index + 1) + ". " + msg + "</dd>";
        }
      }
      return this.uiErrorsList.html(html);
    };

    FLDash.prototype.removeErrors = function(listing_id) {
      if (listing_id == null) listing_id = null;
      if (listing_id != null) {
        this.uiOrderItemsList.find(".orderItem[data-id=" + listing_id + "]").removeClass("error");
        return this.uiErrorsList.children("[data-id=" + listing_id + "]").remove();
      } else {
        this.uiOrderItemsList.find(".orderItem").removeClass("error");
        return this.uiErrorsList.html("");
      }
    };

    FLDash.prototype.buy = function() {
      var listing_id, uniDataDefereds, _ref,
        _this = this;
      this.removeErrors();
      if (this.FL_Order) {
        this.OrderStates[this.FL_Order.listing_id] = this.FL_Order.getState();
      }
      uniDataDefereds = [];
      _ref = this.OrderStates;
      for (listing_id in _ref) {
        if (!__hasProp.call(_ref, listing_id)) continue;
        uniDataDefereds.push(this.getUniData(listing_id));
      }
      return $.when.apply($, uniDataDefereds).then(function() {
        var od, oi, order, orderData, orderState, uni, uniData, _i, _j, _len, _len2;
        order = [];
        orderData = _.zip(arguments, _.values(_this.OrderStates));
        for (_i = 0, _len = orderData.length; _i < _len; _i++) {
          od = orderData[_i];
          uniData = od[0];
          orderState = od[1];
          if (orderState.selectedDates.length < 1) continue;
          for (_j = 0, _len2 = uniData.length; _j < _len2; _j++) {
            uni = uniData[_j];
            if (!uni.enabled) continue;
            oi = A2Cribs.Order.FeaturedListing.GenerateOrderItem(orderState, uni);
            order.push(oi);
          }
        }
        if (order.length === 0) {
          A2Cribs.UIManager.Alert("You haven't select any dates to feature listings");
          return;
        }
        return A2Cribs.Order.BuyItems(order, 0, function(errors) {
          if ((errors.error_type != null) && errors.error_type === 'NO_LISTINGS_SELECTED') {
            A2Cribs.UIManager.Alert("You haven't selected any dates to feature your listings.");
          } else {
            return _this.showErrors(errors);
          }
        }, function() {
          return _this.removeOrderItem();
        });
      });
    };

    FLDash.prototype.toggleOrderDetailsUI = function(show) {
      if (show) {
        $("#noListingSelected").fadeOut('fast');
        return this.uiWidget.find(".orderingInfo").slideDown();
      } else {
        this.uiWidget.find(".orderingInfo").slideUp();
        return $("#noListingSelected").fadeIn('fast');
      }
    };

    return FLDash;

  })();

  A2Cribs.Register = (function() {

    function Register() {}

    Register.RedirectUrl = null;

    Register.setupUI = function() {
      var _this = this;
      return $('#registerForm').submit(function(e) {
        e.preventDefault();
        return _this.cribspotRegister();
      });
    };

    /*
    	Open register modal and feed a specific url to redirect to after register is successful
    */

    Register.InitRegister = function(url) {
      if (url == null) url = null;
      $("#signupModal").modal("show");
      return A2Cribs.Register.RedirectUrl = '/dashboard?post_redirect=true';
    };

    Register.cribspotRegister = function() {
      var request_data, request_form, url,
        _this = this;
      url = "/users/AjaxRegister";
      request_form = $('#registerForm').serializeArray();
      request_data = {
        User: {
          email: $.trim(request_form[0]['value']),
          password: $.trim(request_form[1]['value']),
          first_name: $.trim(request_form[3]['value']),
          last_name: $.trim(request_form[4]['value'])
        }
      };
      return $.post(url, request_data, function(response) {
        var data;
        data = JSON.parse(response);
        console.log(data);
        if (data.success !== void 0 && data.success !== null) {
          return window.location.href = '/users/login?register_success=true';
        } else if (data.error_type === 'EMAIL_EXISTS') {
          A2Cribs.UIManager.Alert(data.error);
          return $('#inputEmail').val("");
        } else {
          if (typeof data.validation.email !== 'undefined') {
            $('#inputEmail').effect("highlight", {
              color: "#FF0000"
            }, 3000);
            $('#registerStatus').append('<p>' + data['email'][0] + '<p>');
          }
          if (typeof data.validation.first_name !== 'undefined') {
            $('#inputFirstName').effect("highlight", {
              color: "#FF0000"
            }, 3000);
            $('#registerStatus').append('<p>' + data['first_name'][0] + '<p>');
          }
          if (typeof data.validation.last_name !== 'undefined') {
            $('#inputLastName').effect("highlight", {
              color: "#FF0000"
            }, 3000);
            $('#registerStatus').append('<p>' + data['last_name'][0] + '<p>');
          }
          if (typeof data.validation.password !== 'undefined') {
            $('#registerStatus').append('<p>' + data['password'][0] + '<p>');
            $('#inputPassword').effect("highlight", {
              color: "#FF0000"
            }, 3000);
            $('#confirmPassword').effect("highlight", {
              color: "#FF0000"
            }, 3000);
          }
          return $('#loginStatus').effect("highlight", {
            color: "#FF0000"
          }, 3000);
        }
      });
    };

    return Register;

  })();

  A2Cribs.Map = (function() {
    var _this = this;

    function Map() {}

    Map.LISTING_TYPES = ['rental', 'sublet', 'parking'];

    Map.CLUSTER_SIZE = 2;

    $(document).ready(function() {
      if ($("#map_region").length) {
        return Map.Init($("#map_region").data("university-id"), $("#map_region").data("latitude"), $("#map_region").data("longitude"), $("#map_region").data("city"), $("#map_region").data("state"), $("#map_region").data("university-name"), $("#map_region").data("listing-type"));
      }
    });

    /*
    	Add all markers in markerList to map
    */

    Map.InitializeMarkers = function(markerList) {
      var marker, marker_object, _i, _len, _results;
      if (markerList != null) {
        markerList = JSON.parse(markerList);
        _results = [];
        for (_i = 0, _len = markerList.length; _i < _len; _i++) {
          marker_object = markerList[_i];
          marker = new A2Cribs.Marker(marker_object.Marker);
          marker.Init();
          A2Cribs.UserCache.Set(marker);
          _results.push(Map.GMarkerClusterer.addMarker(marker.GMarker));
        }
        return _results;
      }
    };

    /*
    	Used to only show markers that are within a certain bounds based on the user's current viewport.
    	https://developers.google.com/maps/articles/toomanymarkers#viewportmarkermanagement
    */

    Map.ShowMarkers = function() {
      var bounds;
      return bounds = A2Cribs.Map.GMap.getBounds();
    };

    Map.InitBoundaries = function() {
      return this.Bounds = {
        LEFT: 0,
        RIGHT: window.innerWidth,
        BOTTOM: window.innerHeight,
        TOP: 0,
        CONTROL_BOX_LEFT: 95
      };
    };

    Map.Init = function(school_id, latitude, longitude, city, state, school_name, active_listing_type_id) {
      var imageStyles, mcOptions, zoom;
      this.CurentSchoolId = school_id;
      A2Cribs.FilterManager.CurrentCity = city;
      A2Cribs.FilterManager.CurrentState = state;
      A2Cribs.FilterManager.CurrentSchool = school_name;
      A2Cribs.FilterManager.ActiveListingType = active_listing_type_id;
      this.ACTIVE_LISTING_TYPE_ID = active_listing_type_id;
      this.ACTIVE_LISTING_TYPE = this.LISTING_TYPES[active_listing_type_id];
      zoom = 14;
      this.MapCenter = new google.maps.LatLng(latitude, longitude);
      this.MapOptions = {
        zoom: zoom,
        center: A2Cribs.Map.MapCenter,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: this.style,
        panControl: false,
        streetViewControl: false,
        mapTypeControl: false,
        zoomControlOptions: {
          style: google.maps.ZoomControlStyle.SMALL,
          position: google.maps.ControlPosition.LEFT_CENTER
        }
      };
      A2Cribs.Map.GMap = new google.maps.Map(document.getElementById('map_canvas'), A2Cribs.Map.MapOptions);
      google.maps.event.addListener(A2Cribs.Map.GMap, 'idle', A2Cribs.Map.ShowMarkers);
      /*imageStyles = [
      			{
      				"url": "/img/dots/group_dot.png",
      			}
      		]
      */
      imageStyles = [
        {
          height: 39,
          url: '/img/dots/dot_group.png',
          width: 39,
          textColor: '#ffffff',
          textSize: 13
        }
      ];
      mcOptions = {
        gridSize: 60,
        maxZoom: 15,
        styles: imageStyles
      };
      this.GMarkerClusterer = new MarkerClusterer(A2Cribs.Map.GMap, [], mcOptions);
      this.GMarkerClusterer.setIgnoreHidden(true);
      $("#map_region").trigger("map_initialized", [this.GMap]);
      A2Cribs.Map.InitBoundaries();
      this.LoadAllMapData();
      return A2Cribs.FilterManager.InitAddressSearch();
    };

    Map.LoadBasicData = function() {
      var _this = this;
      if (!(this.BasicDataDeferred != null)) {
        this.BasicDataDeferred = new $.Deferred();
      }
      $.ajax({
        url: myBaseUrl + ("Map/GetBasicData/" + this.ACTIVE_LISTING_TYPE_ID + "/" + this.CurentSchoolId),
        type: "POST",
        success: function(responses) {
          return _this.BasicDataDeferred.resolve(responses);
        },
        error: function() {
          _this.BasicDataDeferred.resolve(null);
          return _this.BasicDataCached.resolve();
        }
      });
      return this.BasicDataDeferred.promise();
    };

    Map.LoadBasicDataCallback = function(response) {
      var all_listings, all_markers, key, listing, listing_id, listings, marker, value, _i, _j, _len, _len2;
      if (response === null || response === void 0) return;
      listings = JSON.parse(response);
      for (listing_id in listings) {
        listing = listings[listing_id];
        for (key in listing) {
          value = listing[key];
          A2Cribs.UserCache.Set(new A2Cribs[key](value));
        }
      }
      Map.BasicDataCached.resolve();
      all_markers = A2Cribs.UserCache.Get("marker");
      for (_i = 0, _len = all_markers.length; _i < _len; _i++) {
        marker = all_markers[_i];
        marker.Init();
        Map.GMarkerClusterer.addMarker(marker.GMarker);
      }
      all_listings = A2Cribs.UserCache.Get("listing");
      for (_j = 0, _len2 = all_listings.length; _j < _len2; _j++) {
        listing = all_listings[_j];
        listing.visible = true;
      }
      if (Map.ACTIVE_LISTING_TYPE === 'sublet') Map.IsCluster(false);
      return Map.Repaint();
    };

    /*
    	Set Marker Types
    	Loops through all the listings and if changes the marker
    	type (icon) based on the availability of the marker
    */

    Map.SetMarkerTypes = function() {
      var all_listings, all_markers, listing, marker, _i, _j, _len, _len2, _results;
      all_markers = A2Cribs.UserCache.Get("marker");
      for (_i = 0, _len = all_markers.length; _i < _len; _i++) {
        marker = all_markers[_i];
        marker.SetType(A2Cribs.Marker.TYPE.LEASED);
      }
      all_listings = A2Cribs.UserCache.Get("listing");
      _results = [];
      for (_j = 0, _len2 = all_listings.length; _j < _len2; _j++) {
        listing = all_listings[_j];
        if (listing.InSidebar() || listing.IsVisible()) {
          marker = A2Cribs.UserCache.Get("marker", listing.marker_id);
          if (!(listing.available != null) && marker.GetType() === A2Cribs.Marker.TYPE.LEASED) {
            _results.push(marker.SetType(A2Cribs.Marker.TYPE.UNKNOWN));
          } else if ((listing.available != null) && listing.available === true) {
            _results.push(marker.SetType(A2Cribs.Marker.TYPE.AVAILABLE));
          } else {
            _results.push(void 0);
          }
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    /*
    	EVAN:
    		marker_id is the id of the marker to open
    		sublet_data is an object containing all the data needed to populate a tooltip
    */

    Map.OpenMarker = function(marker_id, sublet_data) {
      if (marker_id === -1) {
        alert("This listing either has been removed or is invalid.");
        return;
      }
      if (marker_id === -2) return;
      return alert(marker_id);
    };

    /*
    	Load markers and hover data.
    	Use JQuery Deferred object to load all data asynchronously
    */

    Map.LoadAllMapData = function() {
      var basicData;
      $("#loader").show();
      basicData = this.LoadBasicData();
      this.BasicDataCached = new $.Deferred();
      A2Cribs.FeaturedListings.LoadFeaturedPMListings();
      basicData.done(this.LoadBasicDataCallback).always(function() {
        return $("#loader").hide();
      });
      A2Cribs.FeaturedListings.InitializeSidebar(this.CurentSchoolId, this.ACTIVE_LISTING_TYPE, basicData, this.BasicDataCached);
      return A2Cribs.Hotlist.Initialize();
    };

    Map.CenterMap = function(latitude, longitude) {
      if (!(this.GMap != null)) return;
      return this.GMap.setCenter(new google.maps.LatLng(latitude, longitude));
    };

    /*
    	Toggles visibility for the given listing_ids
    	When toggled on, only these listing_ids are visible.
    	When toggled off, all listings are visible
    */

    Map.ToggleListingVisibility = function(listing_ids, toggle_type) {
      var all_listings, all_markers, is_current_toggle, listing, listing_id, marker, _i, _j, _k, _l, _len, _len2, _len3, _len4, _len5, _m;
      $(".favorite_button").removeClass("active");
      $(".featured_pm").removeClass("active");
      $(document).trigger("close_bubbles");
      all_markers = A2Cribs.UserCache.Get('marker');
      all_listings = A2Cribs.UserCache.Get('listing');
      is_current_toggle = this.CurrentToggle === toggle_type;
      if (!is_current_toggle) {
        for (_i = 0, _len = all_markers.length; _i < _len; _i++) {
          marker = all_markers[_i];
          marker.IsVisible(false);
        }
        for (_j = 0, _len2 = all_listings.length; _j < _len2; _j++) {
          listing = all_listings[_j];
          listing.IsVisible(false);
        }
        for (_k = 0, _len3 = listing_ids.length; _k < _len3; _k++) {
          listing_id = listing_ids[_k];
          listing = A2Cribs.UserCache.Get('listing', listing_id);
          if (listing != null) {
            marker = A2Cribs.UserCache.Get('marker', listing.marker_id);
            marker.IsVisible(true);
            listing.IsVisible(true);
          }
        }
        this.CurrentToggle = toggle_type;
      } else {
        for (_l = 0, _len4 = all_markers.length; _l < _len4; _l++) {
          marker = all_markers[_l];
          if (marker != null) marker.IsVisible(true);
        }
        for (_m = 0, _len5 = all_listings.length; _m < _len5; _m++) {
          listing = all_listings[_m];
          listing.IsVisible(true);
        }
        this.CurrentToggle = null;
      }
      this.Repaint();
      return is_current_toggle;
    };

    /*
    	Checks/Sets if the map is in clusters
    	Never cluster if it is sublets!
    */

    Map.IsCluster = function(is_clustered) {
      if (is_clustered == null) is_clustered = null;
      if (typeof is_clustered === "boolean") {
        if (is_clustered === true && this.ACTIVE_LISTING_TYPE !== 'sublet') {
          this.GMarkerClusterer.setMinimumClusterSize(this.CLUSTER_SIZE);
        } else {
          this.GMarkerClusterer.setMinimumClusterSize(Number.MAX_VALUE);
        }
        this.Repaint();
      }
      return this.GMarkerClusterer.getMinimumClusterSize() === this.CLUSTER_SIZE;
    };

    /*
    	Repaints the map
    */

    Map.Repaint = function() {
      this.SetMarkerTypes();
      return this.GMarkerClusterer.repaint();
    };

    Map.style = [
      {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
          {
            "visibility": "on"
          }, {
            "color": "#ffffff"
          }
        ]
      }, {
        "featureType": "road.arterial",
        "elementType": "geometry.fill",
        "stylers": [
          {
            "color": "#ffffff"
          }
        ]
      }, {
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "color": "#3b393a"
          }
        ]
      }, {
        "featureType": "poi.sports_complex",
        "elementType": "geometry",
        "stylers": [
          {
            "color": "#e9ddbc"
          }
        ]
      }, {
        "featureType": "road",
        "elementType": "labels.text.stroke",
        "stylers": [
          {
            "color": "#ffffff"
          }
        ]
      }, {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
          {
            "color": "#868080"
          }, {
            "lightness": 55
          }
        ]
      }, {
        "featureType": "road.local",
        "elementType": "geometry.stroke",
        "stylers": [
          {
            "color": "#808080"
          }, {
            "lightness": 53
          }
        ]
      }, {
        "featureType": "poi.place_of_worship",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.attraction",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "road.highway",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "road"
      }, {
        "featureType": "transit.station.airport",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.government",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.business",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.government",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.medical",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.park",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.park",
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "lightness": 23
          }, {
            "color": "#83b243"
          }, {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.park",
        "elementType": "labels.text.stroke",
        "stylers": [
          {
            "color": "#f4f6f1"
          }, {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.school",
        "elementType": "labels.text",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "water",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "road.highway",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "poi.medical",
        "stylers": [
          {
            "color": "#ce979e"
          }, {
            "lightness": 26
          }
        ]
      }, {
        "featureType": "road.arterial",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "transit.station.rail",
        "elementType": "labels.icon",
        "stylers": [
          {
            "lightness": 39
          }
        ]
      }, {
        "featureType": "poi.park",
        "elementType": "geometry.fill",
        "stylers": [
          {
            "color": "#d6e0c6"
          }
        ]
      }, {
        "featureType": "water",
        "stylers": [
          {
            "color": "#c2d6ec"
          }
        ]
      }, {
        "featureType": "landscape.man_made",
        "stylers": [
          {
            "color": "#efece2"
          }
        ]
      }, {
        "featureType": "poi.medical",
        "stylers": [
          {
            "color": "#edcece"
          }
        ]
      }, {
        "featureType": "road.arterial",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }, {
        "featureType": "road.local",
        "elementType": "labels.text.fill",
        "stylers": [
          {
            "lightness": 16
          }
        ]
      }, {
        "featureType": "road.arterial",
        "stylers": [
          {
            "lightness": 15
          }
        ]
      }, {
        "featureType": "landscape.man_made",
        "elementType": "geometry.stroke",
        "stylers": [
          {
            "visibility": "on"
          }, {
            "lightness": 78
          }, {
            "color": "#b8b7b8"
          }
        ]
      }, {
        "featureType": "poi.business",
        "elementType": "geometry.fill",
        "stylers": [
          {
            "visibility": "on"
          }, {
            "lightness": 25
          }, {
            "saturation": -17
          }
        ]
      }
    ];

    return Map;

  }).call(this);

  A2Cribs.MarkerModal = (function() {
    var _this = this;

    function MarkerModal() {}

    /*
    	Clear
    	Removes all the values in input fields and resets
    	to the first part of selecting a marker
    */

    MarkerModal.Clear = function() {
      this.modal.find("#marker_select_container").show();
      this.modal.find("input").val("");
      this.modal.find('select option:first-child').attr("selected", "selected");
      return this.MiniMap.SetMarkerVisible(false);
    };

    /*
    	Marker Validate
    	Iterates through the address fields and makes sure everything
    	is completed and checks to make sure the text fields are not
    	too long
    */

    MarkerModal.MarkerValidate = function() {
      var addressFields, addressOK, field, isValid, _i, _len;
      isValid = true;
      addressFields = ["street_address", "city", "state"];
      addressOK = true;
      for (_i = 0, _len = addressFields.length; _i < _len; _i++) {
        field = addressFields[_i];
        if (!(this.modal.find("#Marker_" + field).val() != null) || this.modal.find("#Marker_" + field).val().length === 0) {
          this.modal.find("#Marker_" + field).parent().addClass("error");
          addressOK = false;
        }
      }
      if (!addressOK) {
        A2Cribs.UIManager.Error("Fill in the full address please.");
        isValid = false;
      }
      if (this.modal.find('#Marker_building_type_id').val().length === 0) {
        A2Cribs.UIManager.Error("You need to select a building type.");
        this.modal.find('#Marker_building_type_id').parent().addClass("error");
        isValid = false;
      }
      if (this.modal.find('#Marker_alternate_name').val().length >= 249) {
        A2Cribs.UIManager.Error("Your building name is too long.");
        this.modal.find('#Marker_alternate_name').parent().addClass("error");
        isValid = false;
      }
      return isValid;
    };

    /*
    */

    MarkerModal.Save = function(trigger) {
      var latLng, marker_id, marker_object,
        _this = this;
      if (this.MarkerValidate()) {
        if (!this.modal.find('#Marker_latitude').val()) {
          A2Cribs.UIManager.Error("Please place your street address on the map using the Place On Map button.");
          return;
        }
        marker_id = this.modal.find("#Marker_marker_id").val();
        latLng = this.MiniMap.GetMarkerPosition();
        marker_object = {
          alternate_name: this.modal.find('#Marker_alternate_name').val(),
          building_type_id: this.modal.find('#Marker_building_type_id').val(),
          street_address: this.modal.find('#Marker_street_address').val(),
          city: this.modal.find('#Marker_city').val(),
          state: this.modal.find('#Marker_state').val(),
          zip: this.modal.find('#Marker_zip').val(),
          latitude: latLng['latitude'],
          longitude: latLng['longitude']
        };
        $(document).trigger("track_event", ["Marker", "Save", "", marker_id]);
        if ((marker_id != null ? marker_id.length : void 0) !== 0) {
          marker_object.marker_id = marker_id;
        }
        return $.ajax({
          url: "/Markers/Save/",
          type: "POST",
          data: marker_object,
          success: function(response) {
            if (response.error) {
              return UIManager.Error(response.error);
            } else {
              _this.modal.modal("hide");
              marker_object.marker_id = response;
              $(document).trigger("track_event", ["Marker", "Save Completed", "", marker_object.marker_id]);
              A2Cribs.UserCache.Set(new A2Cribs.Marker(marker_object));
              return trigger(marker_object.marker_id);
            }
          }
        });
      }
    };

    MarkerModal.SetupUI = function() {
      var _this = this;
      this.modal.on('shown', function() {
        return _this.MiniMap.Resize();
      });
      this.modal.find(".required").keydown(function() {
        return $(this).parent().removeClass("error");
      });
      this.modal.find("#place_map_button").click(function() {
        var marker_selected;
        marker_selected = _this.modal.find("#marker_select").val();
        return _this.FindAddress(_this.modal);
      });
      this.modal.find("#marker_select").change(function() {
        var marker_selected;
        marker_selected = _this.modal.find("#marker_select").val();
        if (marker_selected === "0") {
          _this.modal.find("#continue-button").addClass("disabled");
        } else {
          _this.modal.find("#continue-button").removeClass("disabled");
        }
        if (marker_selected === "new_marker") {
          _this.modal.find('#marker_add').show();
          return _this.MiniMap.Resize();
        } else {
          return _this.modal.find('#marker_add').hide();
        }
      });
      this.modal.find("#continue-button").click(function() {
        var marker, marker_selected;
        marker_selected = _this.modal.find("#marker_select").val();
        if (marker_selected === "new_marker") {
          return _this.Save();
        } else if (marker_selected !== "0") {
          marker = A2Cribs.UserCache.Get("marker", marker_selected);
          _this.modal.modal("hide");
          return _this.TriggerMarkerAdded(marker_selected);
        }
      });
      return this.MiniMap = new A2Cribs.MiniMap(this.modal);
    };

    MarkerModal.Open = function(listing_type, marker_id) {
      if (marker_id == null) marker_id = null;
      if (listing_type != null) this.ListingType = listing_type;
      if (!(marker_id != null)) this.NewMarker();
      return this.modal.modal('show');
    };

    MarkerModal.OpenLocation = function(listing_type, street_address, city, state) {
      var _this = this;
      if (listing_type != null) this.ListingType = listing_type;
      this.Clear();
      this.modal.find(".title").text("Create a New " + (this.ListingType.charAt(0).toUpperCase() + this.ListingType.slice(1)));
      this.modal.find('#marker_add').show();
      this.modal.find("#marker_select_container").hide();
      this.modal.find("#Marker_street_address").val(street_address);
      this.modal.find('#Marker_city').val(city);
      this.modal.find('#Marker_state').val(state);
      this.modal.find("#continue-button").unbind('click').click(function() {
        return _this.Save(_this.TriggerMarkerUpdated);
      });
      return this.modal.modal('show');
    };

    MarkerModal.NewMarker = function() {
      var marker, markers, name, option, _i, _len,
        _this = this;
      this.Clear();
      this.modal.find('#marker_add').hide();
      this.modal.find("#continue-button").addClass("disabled").text("Continue");
      this.modal.find(".title").text("Create a New " + (this.ListingType.charAt(0).toUpperCase() + this.ListingType.slice(1)));
      markers = A2Cribs.UserCache.Get("marker");
      this.modal.find("#marker_select").empty().append('<option value="0">--</option>\
			<option value="new_marker"><strong>New Location</strong></option>');
      this.modal.find("#continue-button").unbind('click').click(function() {
        var marker_selected;
        marker_selected = _this.modal.find("#marker_select").val();
        if (marker_selected === "new_marker") {
          return _this.Save(_this.TriggerMarkerAdded);
        } else if (marker_selected !== "0") {
          _this.modal.modal("hide");
          return _this.TriggerMarkerAdded(marker_selected);
        }
      });
      if (markers != null) {
        for (_i = 0, _len = markers.length; _i < _len; _i++) {
          marker = markers[_i];
          name = (marker.alternate_name != null) && marker.alternate_name.length ? marker.alternate_name : marker.street_address;
          option = $("<option />", {
            text: name,
            value: marker.marker_id
          });
          this.modal.find("#marker_select").append(option);
        }
      }
      return this.modal.find("#marker_select").val("0");
    };

    MarkerModal.LoadMarker = function(marker_id) {
      var key, latLng, marker, value,
        _this = this;
      this.Clear();
      this.modal.find('#marker_add').show();
      this.modal.find("#marker_select_container").hide();
      marker = A2Cribs.UserCache.Get("marker", marker_id);
      this.modal.find("#continue-button").removeClass("disabled");
      this.modal.find("#continue-button").text("Save");
      this.modal.find(".title").text("Edit Listing Address");
      this.modal.find("#marker_select").val("new_marker");
      for (key in marker) {
        value = marker[key];
        this.modal.find("#Marker_" + key).val(value);
      }
      this.modal.find("#continue-button").unbind('click');
      this.modal.find("#continue-button").click(function() {
        return _this.Save(_this.TriggerMarkerUpdated);
      });
      latLng = new google.maps.LatLng(this.modal.find("#Marker_latitude").val(), this.modal.find("#Marker_longitude").val());
      return this.MiniMap.SetMarkerPosition(latLng);
    };

    MarkerModal.TriggerMarkerAdded = function(marker_id) {
      return $("#" + MarkerModal.ListingType + "_list_content").trigger("marker_added", [marker_id]);
    };

    MarkerModal.TriggerMarkerUpdated = function(marker_id) {
      return $("#" + MarkerModal.ListingType + "_list_content").trigger("marker_updated", [marker_id]);
    };

    MarkerModal.FindAddress = function(div) {
      var addressObj,
        _this = this;
      if (this.MarkerValidate()) {
        addressObj = {
          address: div.find("#Marker_street_address").val() + " " + div.find("#Marker_city").val() + ", " + div.find("#Marker_state").val()
        };
        return A2Cribs.Geocoder.FindAddress(div.find("#Marker_street_address").val(), div.find("#Marker_city").val(), div.find("#Marker_state").val()).done(function(response) {
          var city, location, state, street_address, zip;
          street_address = response[0], city = response[1], state = response[2], zip = response[3], location = response[4];
          _this.MiniMap.SetMarkerPosition(location);
          div.find("#Marker_street_address").val(street_address);
          div.find("#Marker_latitude").val(location.lat());
          div.find("#Marker_longitude").val(location.lng());
          div.find('#Marker_city').val(city);
          div.find('#Marker_state').val(state);
          return div.find('#Marker_zip').val(zip);
        }).fail(function() {
          A2Cribs.UIManager.Alert("Entered street address is not valid.");
          return $("#Marker_street_address").text("");
        });
      }
    };

    $(document).ready(function() {
      if ($("#marker-modal").length) {
        MarkerModal.modal = $('#marker-modal');
        return MarkerModal.SetupUI();
      }
    });

    return MarkerModal;

  }).call(this);

  window.A2Cribs.UILayer = {};

  A2Cribs.UILayer.Rentals = (function() {

    function Rentals() {}

    Rentals.rental_id = function() {
      return "";
    };

    Rentals.listing_id = function() {
      return 2;
    };

    Rentals.street_address = function() {
      return "521 Linden St";
    };

    Rentals.city = function() {
      return "Ann Arbor";
    };

    Rentals.state = function() {
      return "MI";
    };

    Rentals.zipcode = function() {
      return "48104";
    };

    Rentals.unit_style_options = function() {
      return 2;
    };

    Rentals.unit_style_type = function() {
      return "NA";
    };

    Rentals.unit_style_description = function() {
      return "NA";
    };

    Rentals.building_name = function() {
      return "";
    };

    Rentals.beds = function() {
      return 6;
    };

    Rentals.min_occupancy = function() {
      return 1;
    };

    Rentals.max_occupancy = function() {
      return 6;
    };

    Rentals.building_type = function() {
      return 2;
    };

    Rentals.rent = function() {
      return 3600;
    };

    Rentals.rent_negotiable = function() {
      return 0;
    };

    Rentals.unit_count = function() {
      return 1;
    };

    Rentals.start_date = function() {
      return A2Cribs.UtilityFunctions.GetFormattedDate(new Date("09-02-2013"));
    };

    Rentals.alternate_start_date = function() {
      return "";
    };

    Rentals.end_date = function() {
      return A2Cribs.UtilityFunctions.GetFormattedDate(new Date("08-17-2014"));
    };

    Rentals.available = function() {
      return 1;
    };

    Rentals.baths = function() {
      return 2;
    };

    Rentals.air = function() {
      return 1;
    };

    Rentals.parking_type = function() {
      return 1;
    };

    Rentals.parking_spots = function() {
      return 6;
    };

    Rentals.street_parking = function() {
      return 0;
    };

    Rentals.furnished_type = function() {
      return 0;
    };

    Rentals.pets_type = function() {
      return 1;
    };

    Rentals.smoking = function() {
      return 1;
    };

    Rentals.square_feet = function() {
      return 2000;
    };

    Rentals.year_built = function() {
      return 1944;
    };

    Rentals.electric = function() {
      return 1;
    };

    Rentals.water = function() {
      return 1;
    };

    Rentals.gas = function() {
      return 1;
    };

    Rentals.heat = function() {
      return 1;
    };

    Rentals.sewage = function() {
      return 1;
    };

    Rentals.trash = function() {
      return 1;
    };

    Rentals.cable = function() {
      return 1;
    };

    Rentals.internet = function() {
      return 1;
    };

    Rentals.utility_total_flat_rate = function() {
      return 0;
    };

    Rentals.utility_estimate_winter = function() {
      return 250;
    };

    Rentals.utility_estimate_summer = function() {
      return 200;
    };

    Rentals.deposit = function() {
      return 900;
    };

    Rentals.highlights = function() {
      return "Its a really fun place";
    };

    Rentals.description = function() {
      return "This is a longer description about the place";
    };

    Rentals.waitlist = function() {
      return 1;
    };

    Rentals.waitlist_open_date = function() {
      return "";
    };

    Rentals.lease_office_address = function() {
      return "Jonah Copi's place";
    };

    Rentals.contact_email = function() {
      return "email@address.com";
    };

    Rentals.contact_phone = function() {
      return "5555555555";
    };

    Rentals.website = function() {
      return "www.cribspot.com";
    };

    return Rentals;

  })();

  A2Cribs.UILayer.Fees = (function() {

    function Fees() {}

    /*
    	Return an array of Fee objects
    */

    Fees.GetFees = function() {
      var fees;
      fees = [];
      fees.push({
        fee_id: 160,
        description: "Admin",
        amount: 69
      });
      fees.push({
        fee_id: 161,
        description: "Parking",
        amount: 25
      });
      fees.push({
        fee_id: 162,
        description: "Furniture",
        amount: 45
      });
      fees.push({
        fee_id: 163,
        description: "Pets",
        amount: 50
      });
      fees.push({
        fee_id: 164,
        description: "Upper Floor",
        amount: 66
      });
      fees.push({
        fee_id: 165,
        description: "Cleaning",
        amount: 50
      });
      return fees;
    };

    return Fees;

  })();

  /*
  Quick Rental
  
  Class for quick change of rentals.
  Makes it easy to toggle availablity, pick start dates,
  set rent price
  */

  QuickRental = (function() {
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
          if (!$(value).is(":visible")) $(value).fadeIn();
          return;
        }
        if ($(value).find(".street_address").text().toLowerCase().indexOf($(event.currentTarget).val().toLowerCase()) !== -1) {
          if (!$(value).is(":visible")) $(value).fadeIn();
          return;
        }
        $(value).fadeOut();
      });
    };

    /*
    	Sort Availability
    	Sorts the listings by availability
    */

    QuickRental.SortAvailability = function(show_available) {
      return this.div.find(".rental_preview").each(function(index, value) {
        if (!(show_available != null)) {
          return $(value).fadeIn();
        } else if ($(value).find(".available_listing_count").hasClass("leased")) {
          if (show_available) {
            return $(value).fadeOut();
          } else {
            return $(value).fadeIn();
          }
        } else {
          if (show_available) {
            return $(value).fadeIn();
          } else {
            return $(value).fadeOut();
          }
        }
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
      if (isNaN(rent_amount)) rent_amount = 0;
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
      if (date_split.length !== 3) return false;
      for (_i = 0, _len = date_split.length; _i < _len; _i++) {
        date_val = date_split[_i];
        if (isNaN(date_val)) return false;
      }
      if (date_split[0] < 1 || date_split[0] > 12) return false;
      if (date_split[1] < 1 || date_split[1] > 31) return false;
      if (date_split[2] < 2000) return false;
      if (date_split[0].length === 1) date_split[0] = "0" + date_split[0];
      if (date_split[1].length === 1) date_split[1] = "0" + date_split[1];
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
        if (listing.available) available_count++;
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
        $(document).trigger("track_event", ["Post " + (a2_object.GetListingType()), "Save", "Quick Dashboard", listing_id]);
        a2_object[input.data("field")] = input.data("value");
        $(event.currentTarget).find(".save-note").hide();
        $(event.currentTarget).find(".not-saved").show();
        return _this.Save(listing_id).always(function() {
          $(event.currentTarget).find(".save-note").hide();
          return $(event.currentTarget).find(".saved").show();
        });
      });
      this.div.on('keyup', '.search_rentals', this.Filter);
      this.div.on('click', '.label_explained', function(event) {
        event.preventDefault();
        event.stopPropagation();
        return false;
      });
      this.div.on('click', '.open_photos', function(event) {
        var image_array, listing_id, _ref;
        listing_id = $(event.currentTarget).parent().data("listing-id");
        image_array = (_ref = A2Cribs.UserCache.Get("image", listing_id)) != null ? _ref.GetObject() : void 0;
        return A2Cribs.PhotoPicker.Open(image_array).done(function(photos) {
          var image, _i, _len;
          for (_i = 0, _len = photos.length; _i < _len; _i++) {
            image = photos[_i];
            image.listing_id = listing_id;
          }
          A2Cribs.UserCache.Set(new A2Cribs.Image(photos, listing_id));
          $(event.currentTarget).parent().find(".save-note").hide();
          $(event.currentTarget).parent().find(".not-saved").show();
          return _this.Save(listing_id).done(function() {
            $(event.currentTarget).parent().find(".save-note").hide();
            return $(event.currentTarget).parent().find(".saved").show();
          });
        });
      });
      this.div.find(".toggle_all_listings").click(this.ToggleCollapse);
      return this.div.find("#sort_availablity").change(function(event) {
        var value;
        value = parseInt($(event.currentTarget).val(), 10);
        if (isNaN(value)) value = null;
        return _this.SortAvailability(value);
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
      A2Cribs.UIManager.ShowLoader();
      return $.when(QuickRental.BackgroundLoadRentals()).done(function() {
        if (QuickRental.div.find(".unit_list:visible").length === QuickRental.div.find(".rental_preview").length) {
          QuickRental.div.find(".unit_list").slideUp();
          QuickRental.div.find(".toggle_text").hide();
          QuickRental.div.find(".show_listings").show();
          return QuickRental.div.find(".toggle_all_listings").text("Open all listings");
        } else {
          QuickRental.div.find(".unit_list").slideDown();
          QuickRental.div.find(".toggle_text").hide();
          QuickRental.div.find(".hide_listings").show();
          return QuickRental.div.find(".toggle_all_listings").text("Collapse all listings");
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
        QuickRental.div.find(".toggle_all_listings").text("Open all listings");
        return;
      }
      A2Cribs.UIManager.ShowLoader();
      deferred = $.Deferred();
      deferred.done(function(element) {
        A2Cribs.UIManager.HideLoader();
        element.find(".unit_list").slideDown();
        $(event.currentTarget).parent().find(".toggle_text").hide();
        $(event.currentTarget).parent().find(".hide_listings").show();
        element.find(".rental_expand_toggle").one('click', QuickRental.ToggleShowListings);
        if (QuickRental.div.find(".unit_list:visible").length === QuickRental.div.find(".rental_preview").length) {
          return QuickRental.div.find(".toggle_all_listings").text("Collapse all listings");
        }
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
      if (this.LoadRentalsDeferred != null) return this.LoadRentalsDeferred;
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
      marker_row = "<div class='rental_preview' data-marker-id='" + (marker.GetId()) + "' data-visible-state=\"hidden\">\n	<div class='rental_title rental_expand_toggle'>\n		<span>\n			<span class='building_name'>" + (marker.GetName()) + "</span>\n		</span>\n		<span class='separator'>|</span>\n		<span class='street_address'>" + marker.street_address + "</span>\n		<span class='separator'>|</span>\n		<span class='building_type'>" + (marker.GetBuildingType()) + "</span>\n		<span class='pull-right available_listing_count'></span>\n	</div>\n	<div class='unit_list hide'>\n		<div class='fields_label'>\n			<div class='pull-left text-center listing_label'>Listing</div>\n			<div class='pull-left text-center available_label'>Availablity</div>\n			<div class='pull-left text-center rent_label'>Rent</div>\n			<div class='pull-left text-center start_date_label'>Start Date</div>\n			<a href=\"#\" class='pull-right label_explained' data-toggle='popover' data-content=\"We have simplified things a bit. If you would like to update a field that is not listed below, please click on the rentals or sublet tab on the left.\">Where's the rest? <i class='icon-info-sign'></i></a>\n		</div>\n	</div>\n	<div class='rental_expand_toggle rental_expand_toggle_div'>\n		<div class='show_listings toggle_text'>\n			<span><i class='icon-chevron-sign-down'></i> Click to view</span>\n			<span class='unit_count'>" + listings.length + "</span>\n			<span> Listings</span>\n		</div>\n		<div class='hide_listings hide toggle_text'>\n			<span><i class='icon-chevron-sign-up'></i> Hide these Listings</span>\n		</div>\n	</div>\n</div>";
      marker_row_div = $(marker_row);
      marker_row_div.find(".rental_expand_toggle").one('click', this.ToggleShowListings);
      marker_row_div.find(".label_explained").popover();
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
      var date_split, date_string, div, listing_row, listing_type, rental, unit_description, _ref;
      listing_type = listing.GetListingType();
      rental = A2Cribs.UserCache.Get(listing_type.toLowerCase(), listing.GetId());
      if (rental != null) {
        date_split = (_ref = rental.start_date) != null ? _ref.split("-") : void 0;
        date_string = (date_split != null ? date_split.length : void 0) === 3 ? "" + date_split[1] + "-" + date_split[2] + "-" + date_split[0] : "";
        if (rental.GetUnitStyle != null) {
          unit_description = "" + (rental.GetUnitStyle()) + " " + rental.unit_style_description + " - " + rental.beds + "Br";
        } else {
          unit_description = "" + rental.beds + "Br - " + rental.baths + "Bath";
        }
        listing_row = "<div class=\"rental_edit\" data-listing-id=\"" + (listing.GetId()) + "\">\n	<a href=\"/listing/" + (listing.GetId()) + "\" target=\"_blank\" class=\"unit_description pull-left\">" + unit_description + "</a>\n	<div class=\"btn-group pull-left\" data-toggle=\"buttons-radio\" data-object=\"listing\" data-field=\"available\" data-value=\"" + (listing.available ? "1" : "0") + "\">\n		<button type=\"button\" class=\"btn btn-available " + (listing.available ? "active" : "") + "\" data-value=\"1\">Available</button>\n		<button type=\"button\" class=\"btn btn-leased " + (!listing.available ? "active" : "") + "\" data-value=\"0\">Leased</button>\n	</div>\n	<input type=\"text\" class=\"rent pull-left\" placeholder=\"Rent\" data-object=\"" + rental.class_name + "\" data-field=\"rent\" data-value=\"" + rental.rent + "\" value=\"" + rental.rent + "\">\n	<input type=\"text\" class=\"start_date pull-left\" maxlength=\"10\" value=\"" + date_string + "\" data-object=\"" + rental.class_name + "\" data-field=\"start_date\" data-value=\"" + rental.start_date + "\" placeholder=\"MM-DD-YYYY\">\n	<button type=\"button pull-left\" class=\"open_photos btn btn-primary\">Add Photos</button>\n	<span class=\"not-saved save-note hide\"><i class='icon-spinner icon-spin'></i> Saving...</span>\n	<span class=\"saved save-note hide\"><i class='icon-ok-sign'></i> Saved</span>\n</div>";
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

  A2Cribs.RentalSave = (function() {

    function RentalSave(dropdown_content, user_email, user_phone) {
      this.user_email = user_email;
      this.user_phone = user_phone;
      this.SaveImages = __bind(this.SaveImages, this);
      this.div = $('.rental-content');
      this.EditableRows = [];
      this.Editable = false;
      this.VisibleGrid = 'overview_grid';
      this.SetupUI(dropdown_content);
      this.NextListing;
    }

    RentalSave.prototype.SetupUI = function(dropdown_content) {
      var _this = this;
      $('#middle_content').height();
      this.div.find("grid-pane").height;
      $(".create-listing").find("a").click(function(event) {
        var listing_type;
        listing_type = $(event.currentTarget).data("listing-type");
        if (listing_type === "rental") {
          return A2Cribs.MarkerModal.Open(listing_type);
        }
      });
      this.CreateCallbacks();
      return this.CreateGrids(dropdown_content);
    };

    RentalSave.prototype.CreateCallbacks = function() {
      var _this = this;
      $('#rental_list_content').on("marker_added", function(event, marker_id) {
        A2Cribs.Dashboard.Direct({
          classname: 'rental',
          data: true
        });
        return _this.Open(marker_id).done(function() {
          return _this.AddNewUnit();
        });
      });
      $('body').on('click', '.show_photo_picker', function(event) {
        return _this.LoadImages($(event.currentTarget).data("row"));
      });
      $('body').on("Rental_marker_updated", function(event, marker_id) {
        var list_item, name;
        if ($("#rental_list_content").find("#" + marker_id).length === 1) {
          list_item = $("#rental_list_content").find("#" + marker_id);
          name = A2Cribs.UserCache.Get("marker", marker_id).GetName();
          list_item.text(name);
          return _this.CreateListingPreview(marker_id);
        }
      });
      $("body").on('click', '.rental_list_item', function(event) {
        if (_this.Editable) {
          return A2Cribs.UIManager.ConfirmBox("By selecting a new address, all unsaved changes will be lost.", {
            "ok": "Abort Changes",
            "cancel": "Return to Editor"
          }, function(success) {
            if (success) {
              _this.CancelEditing();
              return _this.Open(event.target.id);
            }
          });
        } else {
          return _this.Open(event.target.id);
        }
      });
      this.div.find(".edit_marker").click(function() {
        $(document).trigger("track_event", ["Post Rental", "Open Marker", "", _this.CurrentMarker]);
        A2Cribs.MarkerModal.Open("rental");
        return A2Cribs.MarkerModal.LoadMarker(_this.CurrentMarker);
      });
      $("#rentals_edit").click(function(event) {
        var selected;
        selected = _this.GridMap[_this.VisibleGrid].getSelectedRows();
        if (_this.Editable) {
          _this.FinishEditing();
        } else {
          if ((selected != null ? selected.length : void 0) === 0) {
            A2Cribs.UIManager.CloseLogs();
            A2Cribs.UIManager.Error("Please select the row you wish to edit!");
            return;
          }
          _this.Edit(selected);
        }
        return _this.GridMap[_this.VisibleGrid].setSelectedRows(selected);
      });
      $("#rentals_delete").click(function() {
        var active_row, index, listings, row, selected, _i, _len;
        selected = _this.GridMap[_this.VisibleGrid].getSelectedRows();
        if (selected.length) {
          if (_this.GridMap[_this.VisibleGrid].getEditorLock().isActive()) {
            active_row = _this.GridMap[_this.VisibleGrid].getActiveCell().row;
          }
          if (selected.indexOf(active_row) !== -1) {
            return _this.GridMap[_this.VisibleGrid].getEditorLock().cancelCurrentEdit();
          }
          listings = [];
          for (_i = 0, _len = selected.length; _i < _len; _i++) {
            row = selected[_i];
            if (_this.GridMap[_this.VisibleGrid].getDataItem(row).listing_id != null) {
              listings.push(_this.GridMap[_this.VisibleGrid].getDataItem(row).listing_id);
            }
            if ((index = _this.EditableRows.indexOf(row)) !== -1) {
              _this.EditableRows.splice(index, 1);
            }
          }
          _this.Delete(selected, listings);
          if (_this.EditableRows.length === 0) return _this.FinishEditing();
        }
      });
      $(".rentals_tab").click(function(event) {
        var row, selected, _i, _len, _ref;
        if (_this.CommitSlickgridChanges()) {
          selected = _this.GridMap[_this.VisibleGrid].getSelectedRows();
          _this.VisibleGrid = $(event.target).attr("data-target").substring(1);
          $(document).trigger("track_event", ["Post Rental", "Tab Change", "" + _this.VisibleGrid, _this.CurrentMarker]);
          _this.GridMap[_this.VisibleGrid].setSelectedRows(selected);
          _ref = _this.EditableRows;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            row = _ref[_i];
            _this.Validate(row);
          }
          $(event.target).removeClass("highlight-tab");
          return $(event.delegateTarget).tab('show');
        }
      });
      return $(".rental-content").on("shown", function(event) {
        var grid, height, width, _ref, _results;
        width = $("#" + _this.VisibleGrid).width();
        height = $('#add_new_unit').position().top - $("#" + _this.VisibleGrid).position().top;
        if ((_ref = _this.Map) != null) _ref.Resize();
        _results = [];
        for (grid in _this.GridMap) {
          $("#" + grid).css("width", "" + width + "px");
          $("#" + grid).css("height", "" + height + "px");
          _results.push(_this.GridMap[grid].init());
        }
        return _results;
      });
    };

    RentalSave.prototype.CommitSlickgridChanges = function() {
      var _ref;
      return (_ref = this.GridMap[this.VisibleGrid].getEditorLock()) != null ? _ref.commitCurrentEdit() : void 0;
    };

    RentalSave.prototype.Edit = function(rows) {
      var data, row, _i, _len;
      this.EditableRows = rows;
      $("#rentals_edit").text("Finish Editing");
      for (_i = 0, _len = rows.length; _i < _len; _i++) {
        row = rows[_i];
        data = this.GridMap[this.VisibleGrid].getDataItem(row);
        if (data != null) data.editable = true;
      }
      return this.Editable = true;
    };

    RentalSave.prototype.CancelEditing = function() {
      var data, row, _i, _len, _ref, _ref2;
      if ((_ref = this.GridMap[this.VisibleGrid].getEditorLock()) != null) {
        _ref.cancelCurrentEdit();
      }
      _ref2 = this.EditableRows;
      for (_i = 0, _len = _ref2.length; _i < _len; _i++) {
        row = _ref2[_i];
        data = this.GridMap[this.VisibleGrid].getDataItem(row);
        data.editable = false;
        if (!((data != null ? data.listing_id : void 0) != null)) {
          this.GridMap[this.VisibleGrid].getData().splice(row, 1);
        }
      }
      this.GridMap[this.VisibleGrid].updateRowCount();
      this.GridMap[this.VisibleGrid].render();
      this.GridMap[this.VisibleGrid].getSelectionModel().setSelectedRanges([]);
      this.EditableRows = [];
      this.Editable = false;
      $("#rentals_edit").text("Edit");
      return $(".rentals_tab").removeClass("highlight-tab");
    };

    RentalSave.prototype.FinishEditing = function() {
      var data, isValid, row, _i, _j, _len, _len2, _ref, _ref2;
      if (this.CommitSlickgridChanges()) {
        isValid = true;
        _ref = this.EditableRows;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          row = _ref[_i];
          isValid = isValid && this.Validate(row);
        }
        if (isValid) {
          $("#rentals_edit").text("Edit");
          $(".rentals_tab").removeClass("highlight-tab");
          _ref2 = this.EditableRows;
          for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
            row = _ref2[_j];
            data = this.GridMap[this.VisibleGrid].getDataItem(row);
            data.editable = false;
          }
          this.GridMap[this.VisibleGrid].setSelectedRows(this.EditableRows);
          this.EditableRows = [];
          return this.Editable = false;
        } else {
          A2Cribs.UIManager.CloseLogs();
          return A2Cribs.UIManager.Error("Please complete all required fields to finish editing!");
        }
      }
    };

    RentalSave.prototype.Open = function(marker_id) {
      var deferred,
        _this = this;
      $("#loader").show();
      deferred = new $.Deferred();
      $.ajax({
        url: myBaseUrl + "listings/GetOwnedListingsByMarkerId/" + marker_id,
        type: "GET",
        success: function(response) {
          var i, item, key, value, _i, _j, _len, _len2;
          response = JSON.parse(response);
          for (_i = 0, _len = response.length; _i < _len; _i++) {
            item = response[_i];
            for (key in item) {
              value = item[key];
              if (A2Cribs[key] != null) {
                A2Cribs.UserCache.Set(new A2Cribs[key](value));
              } else if ((A2Cribs[key] != null) && (value.length != null)) {
                for (_j = 0, _len2 = value.length; _j < _len2; _j++) {
                  i = value[_j];
                  A2Cribs.UserCache.Set(new A2Cribs[key](i));
                }
              }
            }
          }
          _this.ClearGrids();
          _this.CurrentMarker = marker_id;
          _this.CreateListingPreview(marker_id);
          A2Cribs.Dashboard.ShowContent($(".rental-content"), true);
          _this.PopulateGrid(marker_id);
          deferred.resolve();
          return $("#loader").hide();
        }
      });
      return deferred.promise();
    };

    RentalSave.prototype.CreateListingPreview = function(marker_id) {
      var marker_object, name;
      marker_object = A2Cribs.UserCache.Get("marker", marker_id);
      name = marker_object.GetName();
      $("#rentals_address").html("<strong>" + name + "</strong><br>");
      if (!(this.Map != null)) {
        this.Map = new A2Cribs.MiniMap($("#rentals_preview"));
      }
      if ((marker_object.latitude != null) && (marker_object.longitude != null)) {
        return this.Map.SetMarkerPosition(new google.maps.LatLng(marker_object.latitude, marker_object.longitude));
      }
    };

    RentalSave.prototype.Validate = function(row) {
      var data, highlighted_tabs, isValid, key, required, tab, value;
      required = A2Cribs.Rental.Required_Fields;
      data = this.GridMap[this.VisibleGrid].getDataItem(row);
      highlighted_tabs = {};
      isValid = true;
      for (key in required) {
        tab = required[key];
        if (!(data[key] != null)) {
          isValid = false;
          highlighted_tabs[tab] = true;
        }
      }
      $(".rentals_tab").removeClass("highlight-tab");
      for (tab in highlighted_tabs) {
        value = highlighted_tabs[tab];
        $("a[data-target='#" + tab + "']").addClass("highlight-tab");
      }
      $("a[data-target='#" + this.VisibleGrid + "']").removeClass("highlight-tab");
      return isValid;
    };

    RentalSave.prototype.GetObjectByRow = function(row) {
      var data, image_object, rental_object, _ref, _ref2;
      data = this.GridMap[this.VisibleGrid].getDataItem(row);
      if (data.listing_id != null) {
        image_object = (_ref = A2Cribs.UserCache.Get("image", data.listing_id)) != null ? _ref.GetObject() : void 0;
      }
      if (!(image_object != null)) image_object = [];
      rental_object = {
        Rental: data,
        Listing: data.listing_id != null ? A2Cribs.UserCache.Get("listing", data.listing_id).GetObject() : void 0,
        Image: image_object
      };
      if (!(rental_object.Listing != null)) {
        rental_object.Listing = {
          listing_type: 0,
          marker_id: this.CurrentMarker
        };
      }
      rental_object.Listing.available = data.available;
      if (((_ref2 = rental_object.Image) != null ? _ref2.length : void 0) === 0 && (data.Image != null)) {
        rental_object.Image = data.Image;
      }
      return rental_object;
    };

    RentalSave.prototype.Save = function(row) {
      var rental_object, save_type,
        _this = this;
      if (this.Validate(row)) {
        rental_object = this.GetObjectByRow(row);
        save_type = rental_object.listing_id != null ? "Edit" : "Save";
        $(document).trigger("track_event", ["Post Rental", "Save", save_type, rental_object.listing_id]);
        $("#loader").show();
        return $.ajax({
          url: myBaseUrl + "listings/Save/",
          type: "POST",
          data: rental_object,
          success: function(response) {
            var image, key, value, _i, _len, _ref;
            response = JSON.parse(response);
            if (response.listing_id != null) {
              $(document).trigger("track_event", ["Post Rental", "Save Completed", "", rental_object.listing_id]);
              A2Cribs.UIManager.Success("Save successful!");
              rental_object.Listing.listing_id = response.listing_id;
              rental_object.Rental.listing_id = response.listing_id;
              _ref = rental_object.Image;
              for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                image = _ref[_i];
                image.listing_id = response.listing_id;
              }
              for (key in rental_object) {
                value = rental_object[key];
                if (A2Cribs[key] != null) {
                  A2Cribs.UserCache.Set(new A2Cribs[key](value));
                }
              }
              console.log(response);
            } else {
              A2Cribs.UIManager.Error(response.error.message);
              console.log(response);
            }
            return $("#loader").hide();
          }
        });
      }
    };

    /*
    	Test function for Listings/GetListing.
    	Retrieves the listing specified by listing_id.
    	If listing_id is null, retrieves all listings owned by the logged-in user.
    */

    RentalSave.prototype.GetListing = function(listing_id) {
      var url,
        _this = this;
      if (listing_id == null) listing_id = null;
      url = myBaseUrl + 'listings/GetListing/';
      if (listing_id !== null) url = url + listing_id;
      return $.ajax({
        url: url,
        type: "POST",
        success: function(response) {
          return console.log(JSON.parse(response));
        }
      });
    };

    RentalSave.prototype.Copy = function(rental_ids) {
      /*
      		********************* TODO (Not first priority) *
      */
    };

    RentalSave.prototype.Delete = function(rows, listing_ids) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + "listings/Delete/" + JSON.stringify(listing_ids),
        type: "POST",
        success: function(response) {
          var data, listing_id, rental, rentals, row, _i, _j, _k, _len, _len2, _len3;
          response = JSON.parse(response);
          if (response.success !== null && response.success !== void 0) {
            A2Cribs.UIManager.Success("Listings deleted!");
            data = _this.GridMap[_this.VisibleGrid].getData();
            for (_i = 0, _len = listing_ids.length; _i < _len; _i++) {
              listing_id = listing_ids[_i];
              rentals = A2Cribs.UserCache.GetAllAssociatedObjects("rental", "listing", listing_id);
              for (_j = 0, _len2 = rentals.length; _j < _len2; _j++) {
                rental = rentals[_j];
                A2Cribs.UserCache.Remove(rental.class_name, rental.GetId());
              }
              A2Cribs.UserCache.Remove("listing", listing_id);
            }
            for (_k = 0, _len3 = rows.length; _k < _len3; _k++) {
              row = rows[_k];
              data.splice(row, 1);
            }
            _this.GridMap[_this.VisibleGrid].updateRowCount();
            return _this.GridMap[_this.VisibleGrid].render();
          } else {
            A2Cribs.UIManager.Error("Delete unsuccessful");
            return console.log(response);
          }
        }
      });
    };

    RentalSave.prototype.Create = function(marker_id) {
      /*
      		********************* TODO **********************
      */
      var data, grid, key, _ref;
      this.CurrentMarker = marker_id;
      A2Cribs.Dashboard.ShowContent($(".rentals-content"), true);
      _ref = this.GridMap;
      for (key in _ref) {
        grid = _ref[key];
        grid.init();
      }
      return data = this.GridMap["overview_grid"].getData();
    };

    /*
    	Grabs all the images based on a row and loads them into A2Cribs.PhotoManager
    */

    RentalSave.prototype.LoadImages = function(row) {
      var data, image_array, _ref,
        _this = this;
      data = this.GridMap[this.VisibleGrid].getDataItem(row);
      if (data.listing_id != null) {
        image_array = (_ref = A2Cribs.UserCache.Get("image", data.listing_id)) != null ? _ref.GetImages() : void 0;
      } else {
        image_array = data.Image;
      }
      $(document).trigger("track_event", ["Photo Editor", "Load Images", "", data.listing_id]);
      return A2Cribs.PhotoPicker.Open(image_array).done(function(photos) {
        return _this.SaveImages(photos, row);
      });
    };

    /*
    	Saves the images in either the cache or temp object in slickgrid
    */

    RentalSave.prototype.SaveImages = function(images, row) {
      var data, image, _i, _len;
      data = this.GridMap[this.VisibleGrid].getDataItem(row);
      $(document).trigger("track_event", ["Photo Editor", "Save Images", "", data.listing_id]);
      if (data.listing_id != null) {
        for (_i = 0, _len = images.length; _i < _len; _i++) {
          image = images[_i];
          image.listing_id = data.listing_id;
        }
        A2Cribs.UserCache.Set(new A2Cribs.Image(images, data.listing_id));
      } else {
        data.Image = images;
      }
      return this.Save(row);
    };

    /*
    	Called when user adds a new row for the existing marker
    	Adds a new row to the grid, with a new row_id.
    	Sets the row_id hidden field.
    */

    RentalSave.prototype.AddNewUnit = function() {
      var container, data, grid, row_number, _ref, _results;
      $(document).trigger("track_event", ["Post Rental", "Add Unit", "", this.CurrentMarker]);
      data = this.GridMap[this.VisibleGrid].getData();
      row_number = data.length;
      this.EditableRows.push(row_number);
      data.push({
        editable: true,
        contact_email: this.user_email,
        contact_phone: this.user_phone,
        unit_style_description: row_number + 1
      });
      this.GridMap[this.VisibleGrid].setSelectedRows(this.EditableRows);
      $("#rentals_edit").text("Finish Editing");
      this.Editable = true;
      this.Validate(row_number);
      _ref = this.GridMap;
      _results = [];
      for (container in _ref) {
        grid = _ref[container];
        grid.updateRowCount();
        _results.push(grid.render());
      }
      return _results;
    };

    RentalSave.prototype.PopulateGrid = function(marker_id) {
      var data, grid, key, listing, rental, rentals, _i, _len, _ref, _results;
      this.GridMap[this.VisibleGrid].getSelectionModel().setSelectedRanges([]);
      rentals = A2Cribs.UserCache.Get("rental");
      data = [];
      if (rentals.length) {
        for (_i = 0, _len = rentals.length; _i < _len; _i++) {
          rental = rentals[_i];
          listing = A2Cribs.UserCache.Get("listing", rental.listing_id);
          if (listing.marker_id === this.CurrentMarker) {
            data.push(rental.GetObject());
          }
        }
      }
      _ref = this.GridMap;
      _results = [];
      for (key in _ref) {
        grid = _ref[key];
        grid.setData(data);
        grid.updateRowCount();
        _results.push(grid.render());
      }
      return _results;
    };

    RentalSave.prototype.ClearGrids = function() {
      var container, data, grid, _ref, _results;
      _ref = this.GridMap;
      _results = [];
      for (container in _ref) {
        grid = _ref[container];
        data = [];
        grid.setData(data);
        _results.push(grid.render());
      }
      return _results;
    };

    RentalSave.prototype.CreateGrids = function(dropdown_content) {
      var checkboxSelector, columnpicker, columns, container, containers, data, options, _i, _len, _results,
        _this = this;
      containers = ["overview_grid", "features_grid", "amenities_grid", "utilities_grid", "buildingamenities_grid", "fees_grid", "description_grid", "picture_grid", "contact_grid"];
      this.GridMap = {};
      options = {
        editable: true,
        enableCellNavigation: true,
        asyncEditorLoading: false,
        enableAddRow: false,
        autoEdit: true,
        forceFitColumns: true,
        explicitInitialization: true,
        rowHeight: 35
      };
      data = [];
      _results = [];
      for (_i = 0, _len = containers.length; _i < _len; _i++) {
        container = containers[_i];
        columns = this.GetColumns(container, dropdown_content);
        checkboxSelector = new Slick.CheckboxSelectColumn({
          cssClass: "grid_checkbox"
        });
        columns[0] = checkboxSelector.getColumnDefinition();
        this.GridMap[container] = new Slick.Grid("#" + container, data, columns, options);
        this.GridMap[container].setSelectionModel(new Slick.RowSelectionModel({
          selectActiveRow: false
        }));
        this.GridMap[container].registerPlugin(checkboxSelector);
        columnpicker = new Slick.Controls.ColumnPicker(columns, this.GridMap[container], options);
        this.GridMap[container].onBeforeEditCell.subscribe(function(e, args) {
          if (_this.EditableRows.indexOf(args.row) !== -1) return true;
          return false;
        });
        this.GridMap[container].onCellChange.subscribe(function(e, args) {
          return _this.Save(args.row);
        });
        _results.push(this.GridMap[container].onValidationError.subscribe(function(e, args) {
          A2Cribs.UIManager.CloseLogs();
          return A2Cribs.UIManager.Error(args.validationResults.msg);
        }));
      }
      return _results;
    };

    RentalSave.prototype.GetColumns = function(container, dropdown_content) {
      var AmenitiesColumns, BuildingAmenitiesColumns, ContactColumns, DescriptionColumns, FeaturesColumns, FeesColumns, OverviewColumns, PictureColumns, UtilitiesColumns;
      OverviewColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185,
            headerCssClass: "slickgrid_header"
          }, {
            id: "beds",
            name: "Beds",
            field: "beds",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.RequiredText
          }, {
            id: "occupancy",
            name: "Occupancy",
            field: "occupancy",
            formatter: A2Cribs.Formatters.Range,
            editor: A2Cribs.Editors.Range
          }, {
            id: "rent",
            name: "Total Rent",
            field: "rent",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.RequiredMoney
          }, {
            id: "rent_negotiable",
            cssClass: "grid_checkbox",
            name: "(Neg.)",
            field: "rent_negotiable",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "start_date",
            name: "Start Date",
            field: "start_date",
            editor: Slick.Editors.Date,
            formatter: A2Cribs.Formatters.Date(true)
          }, {
            id: "alternate_start_date",
            name: "Alt. Start Date",
            field: "alternate_start_date",
            editor: Slick.Editors.Date,
            formatter: A2Cribs.Formatters.Date()
          }, {
            id: "lease_length",
            name: "Lease Length",
            field: "lease_length",
            editor: A2Cribs.Editors.Dropdown([null, "1 month", "2 months", "3 months", "4 months", "5 months", "6 months", "7 months", "8 months", "9 months", "10 months", "11 months", "12 months"]),
            formatter: A2Cribs.Formatters.Dropdown(["", "1 month", "2 months", "3 months", "4 months", "5 months", "6 months", "7 months", "8 months", "9 months", "10 months", "11 months", "12 months"], true)
          }, {
            id: "available",
            name: "Availability",
            field: "available",
            editor: A2Cribs.Editors.Dropdown(["Leased", "Available"]),
            formatter: A2Cribs.Formatters.Dropdown(["Leased", "Available"], true)
          }, {
            id: "unit_count",
            name: "Unit Count",
            field: "unit_count",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Text
          }
        ];
      };
      FeaturesColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "baths",
            name: "Baths",
            field: "baths",
            editor: A2Cribs.Editors.Float,
            formatter: A2Cribs.Formatters.RequiredText
          }, {
            id: "parking_type",
            name: "Parking",
            field: "parking_type",
            editor: A2Cribs.Editors.Dropdown(dropdown_content["parking"]),
            formatter: A2Cribs.Formatters.Dropdown(dropdown_content["parking"])
          }, {
            id: "parking_spots",
            name: "Spots",
            field: "parking_spots",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Text
          }, {
            id: "street_parking",
            cssClass: "grid_checkbox",
            name: "Street Parking",
            field: "street_parking",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "furnished_type",
            name: "Furnished",
            field: "furnished_type",
            editor: A2Cribs.Editors.Dropdown(dropdown_content["furnished"]),
            formatter: A2Cribs.Formatters.Dropdown(dropdown_content["furnished"])
          }, {
            id: "pets_type",
            name: "Pets",
            field: "pets_type",
            editor: A2Cribs.Editors.Dropdown(dropdown_content["pets"]),
            formatter: A2Cribs.Formatters.Dropdown(dropdown_content["pets"])
          }, {
            id: "smoking",
            name: "Smoking",
            field: "smoking",
            editor: A2Cribs.Editors.Dropdown(["Prohibited", "Allowed"]),
            formatter: A2Cribs.Formatters.Dropdown(["Prohibited", "Allowed"])
          }, {
            id: "square_feet",
            name: "SQ Feet",
            field: "square_feet",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Text
          }, {
            id: "year_built",
            name: "Year Built",
            field: "year_built",
            editor: A2Cribs.Editors.Year,
            formatter: A2Cribs.Formatters.Text
          }
        ];
      };
      AmenitiesColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "air",
            cssClass: "grid_checkbox",
            name: "A/C",
            field: "air",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "washer_dryer",
            name: "Washer/Dryer",
            field: "washer_dryer",
            editor: A2Cribs.Editors.Dropdown(dropdown_content["washer_dryer"]),
            formatter: A2Cribs.Formatters.Dropdown(dropdown_content["washer_dryer"])
          }, {
            id: "fridge",
            cssClass: "grid_checkbox",
            name: "Fridge",
            field: "fridge",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "balcony",
            cssClass: "grid_checkbox",
            name: "Balcony",
            field: "balcony",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "tv",
            cssClass: "grid_checkbox",
            name: "TV",
            field: "tv",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "storage",
            cssClass: "grid_checkbox",
            name: "Storage",
            field: "storage",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "security_system",
            cssClass: "grid_checkbox",
            name: "Security System",
            field: "security_system",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }
        ];
      };
      BuildingAmenitiesColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "pool",
            cssClass: "grid_checkbox",
            name: "Pool",
            field: "pool",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "hot_tub",
            cssClass: "grid_checkbox",
            name: "Hot Tubs",
            field: "hot_tub",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "fitness_center",
            cssClass: "grid_checkbox",
            name: "Fitness Center",
            field: "fitness_center",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "game_room",
            cssClass: "grid_checkbox",
            name: "Game Room",
            field: "game_room",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "front_desk",
            cssClass: "grid_checkbox",
            name: "Front Desk",
            field: "front_desk",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "tanning_beds",
            cssClass: "grid_checkbox",
            name: "Tanning Beds",
            field: "tanning_beds",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "study_lounge",
            cssClass: "grid_checkbox",
            name: "Study Lounge",
            field: "study_lounge",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "patio_deck",
            cssClass: "grid_checkbox",
            name: "Deck/Patio",
            field: "patio_deck",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "yard_space",
            cssClass: "grid_checkbox",
            name: "Yard Space",
            field: "yard_space",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }, {
            id: "elevator",
            cssClass: "grid_checkbox",
            name: "Elevator",
            field: "elevator",
            editor: Slick.Editors.Checkbox,
            formatter: A2Cribs.Formatters.Check
          }
        ];
      };
      UtilitiesColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "electric",
            name: "Electricity",
            field: "electric",
            editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"]),
            formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
          }, {
            id: "water",
            name: "Water",
            field: "water",
            editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"]),
            formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
          }, {
            id: "gas",
            name: "Gas",
            field: "gas",
            editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"]),
            formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
          }, {
            id: "heat",
            name: "Heat",
            field: "heat",
            editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"]),
            formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
          }, {
            id: "trash",
            name: "Trash",
            field: "trash",
            editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"]),
            formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
          }, {
            id: "cable",
            name: "Cable",
            field: "cable",
            editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"]),
            formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
          }, {
            id: "internet",
            name: "Internet",
            field: "internet",
            editor: A2Cribs.Editors.Dropdown(["Not Included", "Included"]),
            formatter: A2Cribs.Formatters.Dropdown(["Not Included", "Included"])
          }, {
            id: "utility_total_flat_rate",
            name: "Total Flat Rate",
            field: "utility_total_flat_rate",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }
        ];
      };
      FeesColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "deposit_amount",
            name: "Deposit",
            field: "deposit_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "admin_amount",
            name: "Admin",
            field: "admin_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "parking_amount",
            name: "Parking",
            field: "parking_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "furniture_amount",
            name: "Furniture",
            field: "furniture_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "pets_amount",
            name: "Pets",
            field: "pets_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "amenity_amount",
            name: "Amenity",
            field: "amenity_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "upper_floor_amount",
            name: "Upper Floor",
            field: "upper_floor_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }, {
            id: "extra_occupant_amount",
            name: "Cost for Extra Occupant",
            field: "extra_occupant_amount",
            editor: Slick.Editors.Integer,
            formatter: A2Cribs.Formatters.Money
          }
        ];
      };
      DescriptionColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "highlights",
            name: "Highlights",
            field: "highlights",
            editor: A2Cribs.Editors.LongText(160, "Highlights"),
            formatter: A2Cribs.Formatters.Text
          }, {
            id: "description",
            name: "Description",
            field: "description",
            editor: A2Cribs.Editors.LongText(1000, "Description"),
            formatter: A2Cribs.Formatters.Text
          }
        ];
      };
      PictureColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "pictures",
            name: "Pictures",
            formatter: A2Cribs.Formatters.Button
          }
        ];
      };
      ContactColumns = function() {
        var columns;
        return columns = [
          {}, {
            id: "title",
            name: "Unit/Style - Name",
            field: "title",
            editor: A2Cribs.Editors.Unit,
            formatter: A2Cribs.Formatters.Unit,
            minWidth: 185
          }, {
            id: "waitlist",
            name: "Waitlist",
            field: "waitlist",
            editor: Slick.Editors.YesNoSelect,
            formatter: Slick.Formatters.YesNo
          }, {
            id: "waitlist_open_date",
            name: "Waitlist Open Date",
            field: "waitlist_open_date",
            editor: Slick.Editors.Date,
            formatter: A2Cribs.Formatters.Date()
          }, {
            id: "lease_office_address",
            name: "Leasing Office Address",
            field: "lease_office_address",
            editor: Slick.Editors.Text,
            formatter: A2Cribs.Formatters.Text
          }, {
            id: "contact_email",
            name: "Contact Email",
            field: "contact_email",
            editor: A2Cribs.Editors.Email,
            formatter: A2Cribs.Formatters.RequiredText
          }, {
            id: "contact_phone",
            name: "Contact Phone",
            field: "contact_phone",
            editor: A2Cribs.Editors.Phone,
            formatter: A2Cribs.Formatters.RequiredText
          }, {
            id: "website",
            name: "Website",
            field: "website",
            editor: Slick.Editors.Text,
            formatter: A2Cribs.Formatters.Text
          }
        ];
      };
      switch (container) {
        case "overview_grid":
          return OverviewColumns();
        case "features_grid":
          return FeaturesColumns();
        case "amenities_grid":
          return AmenitiesColumns();
        case "utilities_grid":
          return UtilitiesColumns();
        case "fees_grid":
          return FeesColumns();
        case "description_grid":
          return DescriptionColumns();
        case "picture_grid":
          return PictureColumns();
        case "contact_grid":
          return ContactColumns();
        case "buildingamenities_grid":
          return BuildingAmenitiesColumns();
      }
    };

    return RentalSave;

  })();

  A2Cribs.MiniMap = (function() {

    function MiniMap(div, latitude, longitude, marker_visible, enabled) {
      var MapOptions, mapDiv;
      if (latitude == null) latitude = 39.8282;
      if (longitude == null) longitude = -98.5795;
      if (marker_visible == null) marker_visible = false;
      if (enabled == null) enabled = true;
      mapDiv = div.find('#correctLocationMap')[0];
      this.center = new google.maps.LatLng(latitude, longitude);
      MapOptions = {
        zoom: 2,
        center: this.center,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        panControl: false,
        zoomControl: false,
        streetViewControl: false,
        draggable: enabled
      };
      this.Map = new google.maps.Map(mapDiv, MapOptions);
      this.Marker = new google.maps.Marker({
        draggable: enabled,
        position: this.center,
        map: this.Map,
        visible: marker_visible
      });
      this.Resize();
    }

    MiniMap.prototype.Reset = function() {
      this.SetZoom(2);
      this.CenterMap(39.8282, -98.5795);
      return this.SetMarkerVisible(false);
    };

    MiniMap.prototype.CenterMap = function(latitude, longitude) {
      this.center = new google.maps.LatLng(latitude, longitude);
      return this.Resize();
    };

    MiniMap.prototype.Resize = function() {
      google.maps.event.trigger(this.Map, "resize");
      return this.Map.setCenter(this.center);
    };

    MiniMap.prototype.SetMarkerVisible = function(value) {
      if (value == null) value = true;
      if (this.Marker != null) return this.Marker.setVisible(false);
    };

    MiniMap.prototype.SetMarkerPosition = function(location) {
      this.center = location;
      this.Map.panTo(location);
      this.SetZoom(18);
      this.Marker.setPosition(location);
      return this.Marker.setVisible(true);
    };

    MiniMap.prototype.SetZoom = function(zoom) {
      return this.Map.setZoom(zoom);
    };

    MiniMap.prototype.GetMarkerPosition = function() {
      return {
        'latitude': this.Marker.position.lat(),
        'longitude': this.Marker.position.lng()
      };
    };

    MiniMap.prototype.SetEnabled = function(value) {
      if (value == null) value = true;
      if (this.Map != null) {
        this.Map.setOptions({
          draggable: value,
          zoomControl: value,
          scrollwheel: value,
          disableDoubleClickZoom: value
        });
      }
      if (this.Marker != null) {
        this.Marker.setOptions({
          draggable: value
        });
      }
      return this.Enabled = value;
    };

    return MiniMap;

  })();

  A2Cribs.PageHeader = (function() {

    function PageHeader() {}

    PageHeader.renderUnreadConversationsCount = function() {
      var url,
        _this = this;
      url = myBaseUrl + "messages/getUnreadCount";
      return $.get(url, function(data) {
        var count, notification, response_data;
        try {
          response_data = JSON.parse(data);
        } catch (error) {
          return;
        }
        count = response_data.unread_conversations;
        notification = $('.message_count');
        if (count === 0) {
          return notification.hide();
        } else {
          notification.html(response_data.unread_conversations);
          return notification.show();
        }
      });
    };

    return PageHeader;

  })();

  A2Cribs.ShoppingCart = (function() {

    function ShoppingCart(Widget) {
      var ListItemHTML,
        _this = this;
      this.Widget = Widget;
      this.Widget.on('click', '.edit', function(event) {
        var index;
        index = $(event.currentTarget).attr('id');
        return _this.edit(index);
      }).on('click', '.remove', function(event) {
        var index;
        index = $(event.currentTarget).attr('id');
        return _this.remove(index);
      });
      this.Widget.find('.buy').click(function() {
        return A2Cribs.Order.BuyCart();
      });
      this.Widget.find('.hide-edit').click(function() {
        $('.fl-cart-item').removeClass('editing');
        return $('.edit-form').fadeOut();
      });
      this.Widget.find('.save').click(function() {
        return _this.save(_this.EditingIndex);
      });
      this.Editing = false;
      this.EditingIndex = -1;
      this.orderItem = null;
      ListItemHTML = "<tr class = 'fl-cart-item'>\n    <td><span  class = 'address'><%= address %></span></td>\n    <td><span class = 'price'?>$<%= price %></span></td>\n    <td class = 'actions'>\n        <a href = '#' class = 'edit' id = '<%= id %>'><i class = 'icon-edit'></i></a>   \n        <a href = '#' class = 'remove' id = '<%= id %>'><i class = 'icon-remove-circle'></i></a>\n    </td>\n</tr>";
      this.ListItemTemplate = _.template(ListItemHTML);
      this.refresh();
    }

    ShoppingCart.prototype.remove = function(index) {
      var data, url,
        _this = this;
      url = myBaseUrl + "shoppingCart/remove";
      data = {
        'index': index
      };
      return $.post(url, data, function(response) {
        var success;
        success = JSON.parse(response).success;
        if (success) {
          return _this.refresh();
        } else {
          return alertify.error("Removing item " + (index + 1) + " failed");
        }
      });
    };

    ShoppingCart.prototype.refresh = function() {
      var _this = this;
      return $.getJSON('/shoppingCart/get', function(orderItems) {
        var data, html, i, oi, _i, _len;
        $('.orderItems > tbody').html('');
        html = "";
        i = 0;
        for (_i = 0, _len = orderItems.length; _i < _len; _i++) {
          oi = orderItems[_i];
          data = {
            price: oi.price.toFixed(2),
            address: oi.item.address,
            id: i++
          };
          html += _this.ListItemTemplate(data);
        }
        $('.orderItems > tbody').html(html);
        $('table.orderItems').show();
        return _this.orderItems = orderItems;
      });
    };

    ShoppingCart.prototype.edit = function(index) {
      var fl, _ref;
      fl = this.orderItems[index];
      if ((_ref = this.orderItem) != null) _ref.clear();
      this.orderItem = new A2Cribs.Order.FeaturedListing($('.featured-listing-order-item').first(), fl.item.listing_id, fl.item.address, {
        selected_dates: fl.item.dates
      });
      $('.edit-form').fadeIn('fast');
      this.EditingIndex = index;
      return $(".fl-cart-item:eq(" + index + ")").addClass('editing').siblings().removeClass('editing');
    };

    ShoppingCart.prototype.save = function() {
      var data,
        _this = this;
      if (this.EditingIndex >= 0) {
        data = {
          orderItem: JSON.stringify(this.orderItem.getOrderItem()),
          index: this.EditingIndex
        };
        return $.post('/shoppingCart/edit', data, function(response) {
          data = JSON.parse(response);
          if (data.success) {
            alertify.success("Save Successful");
            _this.Widget.find('.hide-edit').click();
            return _this.refresh();
          } else {
            return alertify.error(data.message);
          }
        });
      }
    };

    return ShoppingCart;

  })();

  A2Cribs.Landing = (function() {
    var set_school, swap_backgrounds;

    function Landing() {}

    swap_backgrounds = function(university_id) {
      var new_background, old_background;
      old_background = $(".current_background");
      if (old_background.attr("data-university") !== university_id) {
        new_background = $("img[data-university='" + university_id + "'].school_background");
        new_background.css("opacity", "0.0").zIndex(-1).addClass("current_background");
        old_background.zIndex(-2).removeClass("current_background");
        return new_background.animate({
          "opacity": 1.0
        }, 1200, function() {
          return old_background.zIndex(-3);
        });
      }
    };

    set_school = function(university) {
      var key, url_name, val, _ref, _ref2, _ref3;
      _ref = university['University'];
      for (key in _ref) {
        val = _ref[key];
        $("#school_page").find("." + key).hide().html(val).fadeIn();
      }
      swap_backgrounds(university['University']['id']);
      url_name = university['University']['name'].split(" ").join("_");
      $("#map_link").attr("href", "/rental/" + url_name);
      $(".background_source").attr("href", university['University']['background_source']);
      if ((_ref2 = university['University']['logo_path']) != null ? _ref2.length : void 0) {
        $(".school_logo").show().css("background-image", "url(" + university['University']['logo_path'] + ")");
      } else {
        $(".school_logo").hide();
      }
      if ((_ref3 = university.University.founder_image) != null ? _ref3.length : void 0) {
        $(".founder_photo").attr("src", university.University.founder_image);
        $(".founder_title").text("" + university['University']['name'] + " Founder");
        return $("#founder_box").fadeIn();
      } else {
        return $("#founder_box").hide();
      }
    };

    Landing.Init = function(locations) {
      var random_school, _ref,
        _this = this;
      this.locations = locations;
      $(window).scroll(function() {
        var scrolled;
        scrolled = $(window).scrollTop();
        return $('.current_background').css('top', (0 - (scrolled * .25)) + 'px');
      });
      $("#friends_invite").click(function() {
        return A2Cribs.ShareManager.ShowShareModal("", "College housing sucks! Tell your friends how easy finding the perfect house can be. Share Cribspot!", "landing page share");
      });
      if (((_ref = this.locations) != null ? _ref.length : void 0) != null) {
        random_school = Math.floor(Math.random() * this.locations.length);
        set_school(this.locations[random_school]);
      }
      $(".mobile_selector").change(function(event) {
        var university, university_id, url_name, _i, _len, _ref2, _results;
        university_id = $(event.currentTarget).val();
        _ref2 = _this.locations;
        _results = [];
        for (_i = 0, _len = _ref2.length; _i < _len; _i++) {
          university = _ref2[_i];
          if (university['University']['id'] === university_id) {
            url_name = university['University']['name'].split(" ").join("_");
            _results.push(window.location.href = "/rental/" + url_name);
          } else {
            _results.push(void 0);
          }
        }
        return _results;
      });
      return $("#school_selector").change(function(event) {
        var temp_school, university, university_id, _i, _len, _ref2;
        university_id = $(event.currentTarget).val();
        _ref2 = _this.locations;
        for (_i = 0, _len = _ref2.length; _i < _len; _i++) {
          university = _ref2[_i];
          if (university['University']['id'] === university_id) {
            temp_school = university;
            _this.Current_University = university;
            break;
          }
        }
        if (temp_school != null) set_school(_this.Current_University);
        return $('html, body').animate({
          scrollTop: $("#school_page").offset().top
        }, 1200);
      });
    };

    return Landing;

  })();

  A2Cribs.Login = (function() {
    var createUser, setup_facebook_signup_modal, validate,
      _this = this;

    function Login() {}

    Login.LANDING_URL = "cribspot.com";

    Login.HTTP_PREFIX = "https://";

    $(document).ready(function() {
      $.when(window.fbInit).then(function() {
        return Login.CheckLoggedIn();
      });
      if ($("#signup_modal").length) Login.SignupModalSetupUI();
      if ($("#login_modal").length) Login.LoginModalSetupUI();
      if ($("#login_signup").length) Login.LoginPageSetupUI();
      if ($("#user_welcome_page").length) {
        return $(document).on("logged_in", function() {
          return location.reload();
        });
      }
    });

    /*
    	Private function setup facebook signup modal
    	Given a user, hides the facebook signup button
    	and populates that area with the users profile
    	picture and populates the input fields with
    	first name and last name
    */

    setup_facebook_signup_modal = function(user) {
      $(".fb-name").text(user.first_name);
      $(".fb-image").attr("src", user.img_url);
      $("#signup_modal").find(".login-separator").fadeOut();
      $("#signup_modal").find(".fb-login").fadeOut('slow', function() {
        return $(".fb-signup-welcome").fadeIn();
      });
      $("#student_first_name").val(user.first_name);
      $("#student_last_name").val(user.last_name);
      return $("#student_email").focus();
    };

    /*
    	Check Logged In
    	Trys to fetch the user information from the backend
    	If logged in populates the header
    	Called when the document is ready
    */

    Login.CheckLoggedIn = function() {
      var deferred,
        _this = this;
      deferred = new $.Deferred();
      $.ajax({
        url: myBaseUrl + 'Users/IsLoggedIn',
        success: function(response) {
          var _ref;
          response = JSON.parse(response);
          if (response.error != null) return deferred.reject();
          if (response.success === "LOGGED_IN") {
            _this.logged_in = true;
            _this.PopulateHeader(response.data);
            _this.PopulateFavorites((_ref = response.data) != null ? _ref.favorites : void 0);
            $(document).trigger("logged_in", [response.data]);
          } else if (response.success === "NOT_LOGGED_IN") {
            _this.logged_in = false;
            _this.ResetHeader();
          }
          $(document).trigger("checked_logged_in", [_this.logged_in]);
          return deferred.resolve(response);
        },
        error: function(response) {
          console.log(response);
          return deferred.reject();
        }
      });
      return deferred.promise();
    };

    /*
    	Signup Modal SetupUI
    	Adds click listeners to the show signup buttons, fb signup
    	and submit for the new user form
    */

    Login.SignupModalSetupUI = function() {
      var _this = this;
      $(".show_signup_modal").click(function() {
        $("#login_modal").modal("hide");
        return $("#signup_modal").modal("show").find(".signup_message").text("Sign up for Cribspot.");
      });
      $("#signup_modal").find("form").submit(function(event) {
        $("#signup_modal").find(".signup-button").button('loading');
        _this.CreateStudent(event.delegateTarget).always(function() {
          return $("#signup_modal").find(".signup-button").button('reset');
        });
        return false;
      });
      return $("#signup_modal").find(".fb-login").click(function() {
        $(".fb-login").button('loading');
        return _this.FacebookJSLogin().done(function(response) {
          var _ref;
          if (response.success === "NOT_LOGGED_IN") {
            return setup_facebook_signup_modal(response.data);
          } else if (response.success === "LOGGED_IN") {
            $(".modal").modal('hide');
            _this.logged_in = true;
            $(document).trigger("track_event", ["Login", "Logged in", "facebook", data.success]);
            _this.PopulateHeader(response.data);
            _this.PopulateFavorites((_ref = response.data) != null ? _ref.favorites : void 0);
            return $(document).trigger("logged_in", [response.data]);
          }
        }).always(function() {
          return $(".fb-login").button('reset');
        });
      });
    };

    /*
    	Login Modal SetupUI
    	Adds Listeners to open login modal, submit login,
    	and fb login
    */

    Login.LoginModalSetupUI = function() {
      var _this = this;
      $(".show_login_modal").click(function() {
        $("#signup_modal").modal("hide");
        return $("#login_modal").modal("show");
      });
      $("#login_modal").find("form").submit(function(event) {
        $("#login_modal").find(".signup-button").button('loading');
        _this.cribspotLogin(event.delegateTarget).always(function() {
          return $("#login_modal").find(".signup-button").button('reset');
        });
        return false;
      });
      return $("#login_modal").find(".fb-login").click(function() {
        $(".fb-login").button('loading');
        return _this.FacebookJSLogin().done(function(response) {
          var _ref;
          if (response.success === "NOT_LOGGED_IN") {
            $("#login_modal").modal('hide');
            $("#signup_modal").modal('show');
            return setup_facebook_signup_modal(response.data);
          } else if (response.success === "LOGGED_IN") {
            $(".modal").modal('hide');
            _this.logged_in = true;
            _this.PopulateHeader(response.data);
            _this.PopulateFavorites((_ref = response.data) != null ? _ref.favorites : void 0);
            return $(document).trigger("logged_in", [response.data]);
          }
        }).always(function() {
          return $(".fb-login").button('reset');
        });
      });
    };

    /*
    	Login Page SetupUI
    	Sets up listeners for full page login
    */

    Login.LoginPageSetupUI = function() {
      var _this = this;
      this.div = $("#login_signup");
      this.div.find(".show_signup").click(function() {
        _this.div.find(".login_row").hide('fade');
        return _this.div.find(".signup_row").show('fade');
      });
      this.div.find(".show_login").click(function() {
        _this.div.find(".signup_row").hide('fade');
        return _this.div.find(".login_row").show('fade');
      });
      this.div.find(".show_pm").click(function() {
        _this.div.find(".student_icon").removeClass("active");
        _this.div.find(".pm_icon").addClass("active");
        _this.div.find(".fb_box").hide();
        _this.div.find(".student_signup").hide();
        return _this.div.find(".pm_signup").show();
      });
      this.div.find(".show_student").click(function() {
        _this.div.find(".pm_icon").removeClass("active");
        _this.div.find(".student_icon").addClass("active");
        _this.div.find(".pm_signup").hide();
        _this.div.find(".fb_box").show();
        return _this.div.find(".student_signup").show();
      });
      this.div.find(".fb_login_btn").click(function() {
        $(".fb-login").button('loading');
        return _this.FacebookJSLogin().done(function(response) {
          if (response.success === "NOT_LOGGED_IN") {
            _this.div.find("#student_first_name").val(response.data.first_name);
            _this.div.find("#student_last_name").val(response.data.last_name);
            _this.div.find(".fb-image").attr("src", response.data.img_url);
            $(".fb-name").text(response.data.first_name);
            _this.div.find(".show_signup").first().click();
            _this.div.find(".show_student").first().click();
            _this.div.find(".email_login_message").fadeOut('slow', function() {
              return _this.div.find(".fb-signup-welcome").fadeIn();
            });
            return _this.div.find("#student_email").focus();
          } else if (response.success === "LOGGED_IN") {
            _this.logged_in = true;
            $(document).trigger("logged_in", [response.data]);
            return location.reload();
          }
        }).always(function() {
          return $(".fb-login").button('reset');
        });
      });
      this.div.find("#login_content").submit(function(event) {
        _this.cribspotLogin(event.delegateTarget).done(function() {
          return location.reload();
        });
        return false;
      });
      this.div.find("#student_signup").submit(function() {
        _this.CreateStudent().done(function() {
          return location.reload();
        });
        return false;
      });
      return this.div.find("#pm_signup").submit(function() {
        _this.CreatePropertyManager().done(function() {
          return location.reload();
        });
        return false;
      });
    };

    /*
    	Reset header
    	Shows Login and signup buttons and removes
    	the user information from the header
    */

    Login.ResetHeader = function() {
      $(".personal_menu").hide();
      $(".personal_buttons").hide();
      $(".signup_btn").show();
      return $(".nav-text").show();
    };

    /*
    	Populate the header
    	Fill in dropdowns and show picture of the user
    */

    Login.PopulateHeader = function(user) {
      var example;
      example = user;
      $(".signup_btn").hide();
      $(".nav-text").hide();
      $(".personal_buttons").show();
      A2Cribs.FavoritesManager.InitializeFavorites(user.favorites);
      $(".personal_menu").find(".user_name").text(user.name);
      $(".personal_menu").find("img").attr("src", user.img_url);
      if (user.num_messages !== 0) {
        $(".personal_buttons").find(".message_count").show().text(user.num_messages);
      }
      $(".personal_menu_" + user.user_type).show();
      return $(".user_email").text(user.email);
    };

    /*
    	Populate favorites
    	Highlight or un-highlight favorites
    */

    Login.PopulateFavorites = function(favorites) {
      var listing_id, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = favorites.length; _i < _len; _i++) {
        listing_id = favorites[_i];
        _results.push($(".favorite_listing*[data-listing-id='" + listing_id + "']").addClass("active"));
      }
      return _results;
    };

    /*
    	Facebook JS Login
    */

    Login.FacebookJSLogin = function() {
      var _this = this;
      this.fb_login_deferred = new $.Deferred();
      FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
          if ((response != null) && (response.authResponse != null)) {
            return _this.AttemptFacebookLogin(response.authResponse);
          } else {
            A2Cribs.UIManager.Error("We're having trouble logging you in with facebook, but don't worry!					You can still create an account with our regular login.");
            return _this.fb_login_deferred.reject();
          }
        } else {
          return FB.login(function(response) {
            if ((response != null) && (response.authResponse != null)) {
              return _this.AttemptFacebookLogin(response.authResponse);
            } else {
              A2Cribs.UIManager.Error("We're having trouble logging you in with facebook, but don't worry!						You can still create an account with our regular login.");
              return _this.fb_login_deferred.reject();
            }
          }, {
            scope: 'email'
          });
        }
      });
      return this.fb_login_deferred.promise();
    };

    /*
    	Send signed request to server to finish registration
    */

    Login.AttemptFacebookLogin = function(authResponse) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + 'Users/AttemptFacebookLogin',
        data: authResponse,
        type: 'POST',
        success: function(response) {
          response = JSON.parse(response);
          if (response.error != null) return _this.fb_login_deferred.reject();
          return _this.fb_login_deferred.resolve(response);
        },
        error: function(response) {
          console.log(response);
          return _this.fb_login_deferred.reject();
        }
      });
    };

    Login.cribspotLogin = function(div) {
      var request_data, url,
        _this = this;
      this._login_deferred = new $.Deferred();
      url = myBaseUrl + "users/AjaxLogin";
      request_data = {
        User: {
          email: $(div).find('#inputEmail').val(),
          password: $(div).find('#inputPassword').val()
        }
      };
      if ((request_data.User.email != null) && (request_data.User.password != null)) {
        $.post(url, request_data, function(response) {
          var data, _ref;
          data = JSON.parse(response);
          console.log(data);
          if (data.error != null) {
            if (data.error_type === "EMAIL_UNVERIFIED") {
              A2Cribs.UIManager.Confirm("Your email address has not yet been confirmed. 							Please click the link provided in your confirmation email. 							Do you want us to resend you the email?", function(resend) {
                if (resend) return _this.ResendConfirmationEmail();
              });
            } else {
              A2Cribs.UIManager.CloseLogs();
              A2Cribs.UIManager.Error(data.error);
            }
            return _this._login_deferred.reject();
          } else {
            $(document).trigger("track_event", ["Login", "Logged in", "email", data.success]);
            $(".modal").modal('hide');
            _this.PopulateHeader(data.data);
            _this.PopulateFavorites((_ref = data.data) != null ? _ref.favorites : void 0);
            _this.logged_in = true;
            $(document).trigger("logged_in", [data.data]);
            return _this._login_deferred.resolve();
          }
        });
      }
      return this._login_deferred.promise();
    };

    Login.ResendConfirmationEmail = function(canceled) {
      if (canceled == null) canceled = false;
      if (canceled) return;
      return $.ajax({
        url: myBaseUrl + "users/ResendConfirmationEmail",
        type: "POST",
        success: function(response) {
          response = JSON.parse(response);
          if (response.error != null) {
            return A2Cribs.UIManager.Alert(response.error.message);
          } else {
            return A2Cribs.UIManager.Success("Email has been sent! Click the link to verify.");
          }
        }
      });
    };

    validate = function(user_type, required_fields, div) {
      var field, isValid, phone_number, type_prefix, _i, _len;
      type_prefix = user_type === 0 ? "student_" : "pm_";
      A2Cribs.UIManager.CloseLogs();
      isValid = true;
      for (_i = 0, _len = required_fields.length; _i < _len; _i++) {
        field = required_fields[_i];
        if (div.find("#" + type_prefix + field).val().length === 0) {
          isValid = false;
        }
      }
      if (!isValid) A2Cribs.UIManager.Error("Please fill in all of the fields!");
      if (user_type === 1) {
        phone_number = div.find("#" + type_prefix + "phone").val().split("-").join("");
        if (phone_number.length !== 10 || isNaN(phone_number)) {
          isValid = false;
          A2Cribs.UIManager.Error("Please enter a valid phone number");
        }
      }
      if (div.find("#" + type_prefix + "password").val().length < 6) {
        isValid = false;
        A2Cribs.UIManager.Error("Please enter a password of 6 or more characters");
      }
      return isValid;
    };

    createUser = function(user_type, required_fields, fields, div) {
      var field, request_data, type_prefix, _i, _len;
      Login._create_user_deferred = new $.Deferred();
      type_prefix = user_type === 0 ? "student_" : "pm_";
      if (validate(user_type, required_fields, div)) {
        if (div.find("#" + type_prefix + "confirm_password").val() != null) {
          if (div.find("#" + type_prefix + "password").val() !== div.find("#" + type_prefix + "confirm_password").val()) {
            A2Cribs.UIManager.Error("Make sure passwords match!");
            return;
          }
        }
        request_data = {
          User: {
            user_type: user_type
          }
        };
        for (_i = 0, _len = fields.length; _i < _len; _i++) {
          field = fields[_i];
          if (div.find("#" + type_prefix + field).val().length !== 0) {
            request_data.User[field] = div.find("#" + type_prefix + field).val();
          }
        }
        $.post("/users/AjaxRegister", request_data, function(response) {
          var data, email, _ref;
          data = JSON.parse(response);
          if (data.error != null) {
            A2Cribs.UIManager.CloseLogs();
            A2Cribs.UIManager.Error(data.error);
            return Login._create_user_deferred.reject();
          } else {
            email = null;
            if (user_type === 0) {
              email = $("#student_email").val();
            } else {
              email = $("#pm_email").val();
            }
            $(document).trigger("track_event", ["Login", "Logged in", "email", response.success]);
            Login.PopulateHeader(data.data);
            Login.PopulateFavorites((_ref = data.data) != null ? _ref.favorites : void 0);
            Login.logged_in = true;
            $(document).trigger("logged_in", [data.data]);
            $(".modal").modal('hide');
            return Login._create_user_deferred.resolve();
          }
        });
        return Login._create_user_deferred.promise();
      }
      return Login._create_user_deferred.reject();
    };

    Login.CreateStudent = function(div) {
      var fields, required_fields;
      div = !(div != null) ? this.div : $(div);
      required_fields = ["email", "password", "first_name", "last_name"];
      fields = required_fields.slice(0);
      return createUser(0, required_fields, fields, div);
    };

    Login.CreatePropertyManager = function() {
      var fields, required_fields;
      required_fields = ["email", "password", "company_name", "street_address", "phone", "city", "state"];
      fields = required_fields.slice(0);
      fields.push("website");
      return createUser(1, required_fields, fields, this.div);
    };

    return Login;

  }).call(this);

  A2Cribs.FeaturedListings = (function() {
    var Sidebar;

    function FeaturedListings() {}

    FeaturedListings.FeaturedPMIdToListingIdsMap = [];

    FeaturedListings.FeaturedPMListingsVisible = false;

    FeaturedListings.resizeHandler = function() {
      var h, w;
      h = $(window).height() - $('#listings-list').offset().top - $('.legal-bar').height();
      $('#listings-list').height(h);
      w = $(window).width();
      if (w < 900) {
        return $('.fl-sb-item').draggable('disable');
      } else {
        return $('.fl-sb-item').draggable('enable');
      }
    };

    FeaturedListings.SetupResizing = function() {
      this.resizeHandler();
      return $(window).on('resize', this.resizeHandler);
    };

    FeaturedListings.GetFlIds = function(university_id) {
      var deferred,
        _this = this;
      deferred = new $.Deferred();
      $.get("/featuredListings/cycleIds/" + university_id + "/" + this.FL_LIMIT, function(response) {
        var listing_ids;
        listing_ids = JSON.parse(response);
        if (listing_ids != null) {
          return deferred.resolve(listing_ids);
        } else {
          return deferred.resolve(null);
        }
      });
      return deferred.promise();
    };

    FeaturedListings.FL_LIMIT = 5;

    FeaturedListings.GetListingDeferred = function(id, type) {
      var deferred, listing_id, listing_type,
        _this = this;
      deferred = new $.Deferred();
      listing_id = id;
      listing_type = type;
      $.ajax({
        url: myBaseUrl + "Listings/GetListing/" + listing_id,
        type: "GET",
        success: function(data) {
          var item, key, listing, response_data, value, _i, _len;
          response_data = JSON.parse(data);
          for (_i = 0, _len = response_data.length; _i < _len; _i++) {
            item = response_data[_i];
            for (key in item) {
              value = item[key];
              if (A2Cribs[key] != null) {
                A2Cribs.UserCache.Set(new A2Cribs[key](value));
              }
            }
          }
          listing = A2Cribs.UserCache.Get(listing_type, listing_id);
          return deferred.resolve(item);
        },
        error: function() {
          return deferred.resolve(null);
        }
      });
      return deferred.promise();
    };

    FeaturedListings.FetchListingsByIds = function(listing_ids, active_listing_type) {
      var deferred, id, listingDefereds, _i, _len,
        _this = this;
      deferred = new $.Deferred();
      if (!listing_ids || listing_ids.length < 1) {
        deferred.resolve(null);
        return deferred;
      }
      listingDefereds = [];
      for (_i = 0, _len = listing_ids.length; _i < _len; _i++) {
        id = listing_ids[_i];
        listingDefereds.push(A2Cribs.FeaturedListings.GetListingDeferred(id, active_listing_type));
      }
      $.when.apply($, listingDefereds).then(function() {
        return deferred.resolve(arguments);
      });
      return deferred.promise();
    };

    FeaturedListings.GetRandomListingsFromMap = function(num, all_listing_ids) {
      var shuf, sliced;
      shuf = _.shuffle(all_listing_ids);
      sliced = shuf.slice(0, num);
      return sliced;
    };

    FeaturedListings.SetupListingItemEvents = function() {
      var $el;
      $el = $('.fl-sb-item');
      $el.unbind();
      return $el.draggable({
        revert: true,
        opacity: 0.7,
        cursorAt: {
          top: -12,
          right: -20
        },
        helper: function(event) {
          var name;
          name = $(this).find('.name').html() || "this listing";
          return $("<div class='listing-drag-helper'>Share " + name + "</div>");
        },
        start: function(event) {
          var _ref;
          if ((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) {
            $('ul.friends, #hotlist').addClass('dragging');
            return A2Cribs.HotlistObj.startedDragging();
          }
        },
        stop: function(event) {
          $('ul.friends, #hotlist').removeClass('dragging');
          return A2Cribs.HotlistObj.stoppedDragging();
        },
        appendTo: 'body'
      });
    };

    FeaturedListings.GetListingObjects = function(listing_ids) {
      var id, listing, listingObject, listing_object, listings, marker, _i, _len;
      listings = [];
      for (_i = 0, _len = listing_ids.length; _i < _len; _i++) {
        id = listing_ids[_i];
        listingObject = {};
        listing = A2Cribs.UserCache.Get('listing', id);
        marker = listing_object = null;
        if (listing != null) {
          listing.InSidebar(true);
          marker = A2Cribs.UserCache.Get('marker', listing.marker_id);
          listing_object = A2Cribs.UserCache.Get(A2Cribs.Map.ACTIVE_LISTING_TYPE, id);
          if (listing_object[0] != null) listing_object = listing_object[0];
        }
        if ((listing != null) && (marker != null) && (listing_object != null)) {
          listingObject.Listing = listing;
          listingObject.Marker = marker;
          listingObject.ListingObject = listing_object;
          listings.push(listingObject);
        }
      }
      return listings;
    };

    FeaturedListings.BuildListingIds = function(flIds) {
      var NUM_RANDOM_LISTINGS, all_listing_ids, id, listing, listings, randomIds, sidebar_listing_ids, _i, _j, _k, _len, _len2, _len3;
      NUM_RANDOM_LISTINGS = 2500;
      listings = A2Cribs.UserCache.Get('listing');
      all_listing_ids = [];
      for (_i = 0, _len = listings.length; _i < _len; _i++) {
        listing = listings[_i];
        if ((listing != null) && listing.listing_id) {
          all_listing_ids.push(parseInt(listing.listing_id));
        }
      }
      randomIds = null;
      if (all_listing_ids.length > 0) {
        randomIds = this.GetRandomListingsFromMap(NUM_RANDOM_LISTINGS, all_listing_ids);
      }
      if (!(flIds != null) && !(randomIds != null)) return null;
      sidebar_listing_ids = [];
      for (_j = 0, _len2 = flIds.length; _j < _len2; _j++) {
        id = flIds[_j];
        id = parseInt(id);
        this.FLListingIds.push(id);
        sidebar_listing_ids.push(id);
      }
      if (randomIds != null) {
        for (_k = 0, _len3 = randomIds.length; _k < _len3; _k++) {
          id = randomIds[_k];
          sidebar_listing_ids.push(id);
        }
      }
      return sidebar_listing_ids;
    };

    FeaturedListings.SetupScrollEvents = function() {
      $(window).scroll(function() {
        if (!$('.fl-sb-item').hasClass('no-listings')) {
          if ($(this).scrollTop() + $(this).innerHeight() >= $('.featured-listings-wrapper').height()) {
            return A2Cribs.FeaturedListings.LoadMoreListings();
          }
        }
      });
      return $('#listings-list').scroll(function() {
        if (!$('.fl-sb-item').hasClass('no-listings')) {
          if ($(this).scrollTop() + $(this).innerHeight() >= $('#ran-listings').height()) {
            return A2Cribs.FeaturedListings.LoadMoreListings();
          }
        }
      });
    };

    FeaturedListings.LoadMoreListings = function() {
      var _this = this;
      this.GetSidebarImagePathsDeferred = new $.Deferred();
      $('#loading-spinner').show();
      if ((this.current_index != null) && (this.listing_ids != null)) {
        this.listingObjects = this.GetListingObjects(this.listing_ids.slice(this.current_index, (this.current_index + 24) + 1 || 9e9));
        this.GetSidebarImagePaths(this.listing_ids.slice(this.current_index, (this.current_index + 24) + 1 || 9e9));
        this.sidebar.addListings(this.listingObjects, 'ran');
        this.current_index += 25;
      } else {
        console.log('warning: no listing ids or current index found.');
      }
      this.SetupListingItemEvents();
      return $.when(this.GetSidebarImagePathsDeferred).then(function(images) {
        var image, _i, _len;
        images = JSON.parse(images);
        for (_i = 0, _len = images.length; _i < _len; _i++) {
          image = images[_i];
          if ((image != null) && (image.Image != null)) {
            $("#fl-sb-item-" + image.Image.listing_id + " .img-wrapper").css('background-image', "url(/" + image.Image.image_path + ")");
          }
        }
        return $('#loading-spinner').hide();
      });
    };

    FeaturedListings.UpdateSidebar = function(listing_ids) {
      var _this = this;
      this.GetSidebarImagePathsDeferred = new $.Deferred();
      this.listing_ids = listing_ids;
      this.current_index = 0;
      if (this.listing_ids != null) {
        this.listingObjects = this.GetListingObjects(this.listing_ids.slice(0, 25));
        this.sidebar.addListings(this.listingObjects, 'ran', true);
        this.GetSidebarImagePaths(this.listing_ids.slice(0, 25));
        this.SetupListingItemEvents();
      }
      return $.when(this.GetSidebarImagePathsDeferred).then(function(images) {
        var image, _i, _len;
        images = JSON.parse(images);
        if (images != null) {
          for (_i = 0, _len = images.length; _i < _len; _i++) {
            image = images[_i];
            if ((image != null) && (image.Image != null)) {
              $("#fl-sb-item-" + image.Image.listing_id + " .img-wrapper").css('background-image', "url(/" + image.Image.image_path + ")");
            }
          }
        }
        return $('#loading-spinner').hide();
      });
    };

    FeaturedListings.InitializeSidebar = function(university_id, active_listing_type, basicDataDeferred, basicDataCachedDeferred) {
      var alt, getFlIdsDeferred,
        _this = this;
      alt = active_listing_type;
      if (!(this.SidebarListingCache != null)) this.SidebarListingCache = {};
      if (!(this.FLListingIds != null)) this.FLListingIds = [];
      this.sidebar = new Sidebar($('#fl-side-bar'));
      this.current_index = 0;
      getFlIdsDeferred = this.GetFlIds(university_id);
      this.GetSidebarImagePathsDeferred = new $.Deferred();
      this.SetupResizing();
      this.SetupScrollEvents();
      $.when(getFlIdsDeferred, basicDataCachedDeferred).then(function(flIds) {
        var sidebar_listing_ids;
        sidebar_listing_ids = _this.BuildListingIds(flIds);
        if (sidebar_listing_ids != null) {
          _this.sidebar.addListings(_this.GetListingObjects(sidebar_listing_ids.slice(0, 25)), 'ran', true);
          _this.listing_ids = sidebar_listing_ids;
          _this.GetSidebarImagePaths(sidebar_listing_ids.slice(0, 25));
          _this.SetupListingItemEvents();
          $('#listings-list').on('click', '.fl-sb-item', function(event) {
            var $map, listing, listing_id, marker, markerPosition, marker_id;
            $map = $('#map_region');
            if ($map.is(':visible')) {
              marker_id = parseInt($(this).attr('marker_id'));
              listing_id = parseInt($(this).attr('listing_id'));
              marker = A2Cribs.UserCache.Get('marker', marker_id);
              listing = A2Cribs.UserCache.Get('listing', listing_id);
              markerPosition = marker.GMarker.getPosition();
              A2Cribs.Map.GMap.setZoom(16);
              A2Cribs.Map.CenterMap(markerPosition.lat(), markerPosition.lng());
              return $map.trigger("marker_clicked", [marker]);
            } else {
              if ($(this).hasClass('expanded')) {
                return $(this).removeClass('expanded');
              } else {
                $(this).addClass('expanded');
                return setTimeout(function() {
                  return $('.fl-sb-item.expanded').not(event.currentTarget).removeClass('expanded');
                }, 200);
              }
            }
          });
        } else {
          _this.listing_ids = [];
        }
        return $(".fl-sb-item").click(function(event) {
          var featured_text, listing, listing_id, marker, markerPosition, marker_id;
          marker_id = parseInt($(event.currentTarget).attr('marker_id'));
          listing_id = parseInt($(event.currentTarget).attr('listing_id'));
          marker = A2Cribs.UserCache.Get('marker', marker_id);
          listing = A2Cribs.UserCache.Get('listing', listing_id);
          A2Cribs.Map.GMap.setZoom(16);
          $("#map_region").trigger("marker_clicked", [marker]);
          featured_text = listing.IsFeatured() ? "Featured Listing" : "Normal Listing";
          $(document).trigger("track_event", ["Listing", "Sidebar Click", featured_text, listing_id]);
          markerPosition = marker.GMarker.getPosition();
          return A2Cribs.Map.CenterMap(markerPosition.lat(), markerPosition.lng());
        }).draggable({
          revert: true,
          opacity: 0.7,
          cursorAt: {
            top: -12,
            right: -20
          },
          helper: function(event) {
            var name;
            name = $(this).find('.name').html() || "this listing";
            return $("<div class='listing-drag-helper'>Share " + name + "</div>");
          },
          start: function(event) {
            var _ref;
            if ((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) {
              $('ul.friends, #hotlist').addClass('dragging');
              return A2Cribs.HotlistObj.startedDragging();
            }
          },
          stop: function(event) {
            $('ul.friends, #hotlist').removeClass('dragging');
            return A2Cribs.HotlistObj.stoppedDragging();
          },
          appendTo: 'body'
        });
      });
      $.when(this.GetSidebarImagePathsDeferred).then(function(images) {
        var image, _i, _len, _results;
        images = JSON.parse(images);
        if (images != null) {
          _results = [];
          for (_i = 0, _len = images.length; _i < _len; _i++) {
            image = images[_i];
            if ((image != null) && (image.Image != null)) {
              _results.push($("#fl-sb-item-" + image.Image.listing_id + " .img-wrapper").css('background-image', "url(/" + image.Image.image_path + ")"));
            } else {
              _results.push(void 0);
            }
          }
          return _results;
        }
      });
      return $('#loading-spinner').hide();
    };

    FeaturedListings.GetSidebarImagePaths = function(listing_ids) {
      return $.ajax({
        url: myBaseUrl + "Images/GetPrimaryImages/" + JSON.stringify(listing_ids),
        type: "GET",
        success: function(data) {
          return FeaturedListings.GetSidebarImagePathsDeferred.resolve(data);
        },
        error: function() {
          return FeaturedListings.GetSidebarImagePathsDeferred.resolve(null);
        }
      });
    };

    FeaturedListings.LoadFeaturedPMListings = function() {
      return $.ajax({
        url: myBaseUrl + "Listings/GetFeaturedPMListings/" + A2Cribs.Map.CurentSchoolId,
        type: "GET",
        success: function(data) {
          FeaturedListings.FeaturedPMIdToListingIdsMap = JSON.parse(data);
          return $(".featured_pm").click(function(event) {
            var listing_ids, user_id;
            user_id = $(event.delegateTarget).data("user-id");
            if (FeaturedListings.FeaturedPMIdToListingIdsMap[user_id] != null) {
              listing_ids = FeaturedListings.FeaturedPMIdToListingIdsMap[user_id];
              if (A2Cribs.Map.ToggleListingVisibility(listing_ids, "PM_" + user_id)) {
                return A2Cribs.Map.IsCluster(true);
              } else {
                A2Cribs.Map.IsCluster(false);
                $(event.delegateTarget).addClass("active");
                return $(document).trigger("track_event", ["Advertising", "Featured PM", "", user_id]);
              }
            }
          });
        },
        error: function() {
          return FeaturedListings.FeaturedPMIdToListingIdsMap = [];
        }
      });
    };

    Sidebar = (function() {

      function Sidebar(SidebarUI) {
        this.SidebarUI = SidebarUI;
        this.ListItemTemplate = _.template(A2Cribs.FeaturedListings.ListItemHTML);
        this.EmptyListingsTemplate = _.template(A2Cribs.FeaturedListings.EmptyListingsHTML);
      }

      Sidebar.prototype.addListings = function(listings, list, clear) {
        var list_html;
        if (clear == null) clear = false;
        if (listings === null || listings.length === 0) {
          list_html = $(this.EmptyListingsTemplate({
            clear: clear
          }));
        } else {
          list_html = this.getListHtml(listings);
        }
        if (clear) {
          return this.SidebarUI.find("#" + list + "-listings").html(list_html);
        } else {
          return this.SidebarUI.find("#" + list + "-listings").append(list_html);
        }
      };

      Sidebar.prototype.getDateString = function(date) {
        var month, year;
        if (!(this.MonthArray != null)) {
          this.MonthArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        }
        month = this.MonthArray[date.getMonth()];
        year = date.getFullYear();
        return "" + month + " " + year;
      };

      Sidebar.prototype.getListHtml = function(listings) {
        var beds, data, end_date, image, lease_length, list, listing, listing_item, name, primary_image_path, rent, start_date, _i, _j, _len, _len2, _ref, _results;
        list = $("<div />");
        _results = [];
        for (_i = 0, _len = listings.length; _i < _len; _i++) {
          listing = listings[_i];
          rent = name = beds = lease_length = start_date = null;
          if (listing.ListingObject.rent != null) {
            rent = parseFloat(listing.ListingObject.rent).toFixed(0);
          } else {
            rent = ' --';
          }
          if (listing.Marker.alternate_name != null) {
            name = listing.Marker.alternate_name;
          } else {
            name = listing.Marker.street_address;
          }
          if (listing.ListingObject.lease_length != null) {
            lease_length = listing.ListingObject.lease_length;
          } else {
            lease_length = '-- ';
          }
          if (listing.ListingObject.beds > 1) {
            beds = "" + listing.ListingObject.beds + " beds";
          } else if (listing.ListingObject.beds != null) {
            beds = "" + listing.ListingObject.beds + " bed";
          } else {
            beds = "?? beds";
          }
          if (listing.ListingObject.start_date != null) {
            start_date = listing.ListingObject.start_date.toString().replace(' ', 'T');
            start_date = this.getDateString(new Date(start_date));
          } else {
            start_date = 'Start Date --';
          }
          if (listing.ListingObject.end_date != null) {
            end_date = listing.ListingObject.end_date.toString().replace(' ', 'T');
            end_date = this.getDateString(new Date(end_date));
          }
          primary_image_path = '/img/sidebar/no_photo_small.jpg';
          if (listing.Image != null) {
            _ref = listing.Image;
            for (_j = 0, _len2 = _ref.length; _j < _len2; _j++) {
              image = _ref[_j];
              if (image.is_primary) primary_image_path = '/' + image.image_path;
            }
          }
          data = {
            rent: rent,
            beds: beds,
            building_type: listing.Marker.building_type_id,
            start_date: start_date,
            end_date: end_date,
            lease_length: lease_length,
            name: name,
            img: primary_image_path,
            listing_id: listing.Listing.listing_id,
            marker_id: listing.Marker.marker_id,
            available: (function() {
              if (typeof listing.Listing.available === 'undefined') {
                return 'unknown';
              }
              if (listing.Listing.available) {
                return 'available';
              } else {
                return 'unavailable';
              }
            })()
          };
          listing_item = $(this.ListItemTemplate(data));
          A2Cribs.FavoritesManager.setFavoriteButton(listing_item.find(".favorite"), listing.Listing.listing_id, A2Cribs.FavoritesManager.FavoritesListingIds);
          listing_item.find(".hotlist_share a").popover({
            content: function() {
              return A2Cribs.HotlistObj.getHotlistForPopup($(this).data('listing'));
            },
            html: true,
            trigger: 'manual',
            placement: 'left',
            container: 'body',
            title: 'Share this listing'
          }).click(function(e) {
            var _this = this;
            e.preventDefault();
            $(this).popover('show');
            return $('.popover a').on('click', function() {
              $('.popover').popover('hide').hide();
              return $('.popover').off('click');
            });
          });
          listing_item.find("#share-to-email").keyup(function(event) {
            if (event.keyCode === 13) return $(".share-to-email-btn").click();
          });
          _results.push(list.append(listing_item));
        }
        return _results;
      };

      return Sidebar;

    })();

    FeaturedListings.EmptyListingsHTML = "<div class='fl-sb-item no-listings'>\n	<% if (clear) { %>\n		<span class='no-listings-text'>No listings found for these filter settings. Try adjusting the filter for more results.</span>\n	<% } else { %>\n		<span class='no-listings-text'>No more listings found for these filter settings.</span>\n	<% } %>\n</div>";

    FeaturedListings.ListItemHTML = "<div id = 'fl-sb-item-<%= listing_id %>' class = 'fl-sb-item' listing_id=<%= listing_id %> marker_id=<%= marker_id %>>\n	<div class='listing-content'>\n		<div class = 'img-wrapper' style='background-image:url(\"<%=img%>\")'> </div>\n		<div class = 'info-wrapper'>\n			<div class = 'info-row'>\n				<span class = 'rent price-text'><%= \"$\" + rent %></span>\n				<span class = 'divider'>|</span>\n				<span class = 'beds'><%= beds %> </span>\n				<span class = 'favorite pull-right'><i class = 'icon-heart fav-icon share_btn favorite_listing' id='<%= listing_id %>' data-listing-id='<%= listing_id %>'></i></span>	\n				<span class = 'hotlist_share pull-right'><a href='#' data-listing=\"<%=listing_id%>\"><i class='fav-icon icon-user'></i></a></span>\n				<span class = 'hotlist-share-grab grab pull-right'><i class='icon-reorder'></i><i class='icon-reorder'></i><i class=\"icon-reorder\"></i></span>\n			</div>\n			<div class = 'info-row'>\n				<span class = 'building-type'><%= building_type %></span>\n				<span class = 'divider'>|</span>\n				<% if (typeof(end_date) != \"undefined\") { %>\n				<span class = 'lease-start'><%= start_date %></span> - <span class = 'lease_length'><%= end_date %></span>\n				<% } else { %>\n				<span class = 'lease-start'><%= start_date %></span> | <span class = 'lease_length'><%= lease_length %> mo.</span>\n				<% } %>\n			</div>\n			<div class = 'info-row'>\n				<i class = 'icon-map-marker <%= available %>'></i><span class = 'name'><%=name%></span>\n			</div>\n		</div>   \n	</div>\n	<div class='listing-actions'>\n		<ul class='action-row'>\n			<li class='hotlist-share'><a href='#'><i class=\"action-icon icon-heart favorite_listing\" data-listing-id='<%=listing_id%>'></i></a></li>\n			<li class='hotlist-share'><a href='/listing/<%=listing_id%>'><i class=\"action-icon icon-share\"></i></a></li>\n		</ul>\n	</div>\n</div>";

    return FeaturedListings;

  }).call(this);

  A2Cribs.Order = (function() {

    function Order() {}

    Order.BuyItems = function(orderItems, order_type, errorHandler, successHandler, failHandler) {
      var data, url,
        _this = this;
      if (successHandler == null) successHandler = null;
      if (failHandler == null) failHandler = null;
      data = {
        'orderItems': JSON.stringify(orderItems),
        'order_type': order_type
      };
      url = "" + myBaseUrl + "order/buy";
      return $.post(url, data, function(response_raw) {
        var response;
        response = JSON.parse(response_raw);
        if (!response.success) {
          errorHandler(response.errors);
          return;
        }
        if (response.jwt != null) {
          return google.payments.inapp.buy({
            parameters: {},
            jwt: response.jwt,
            success: function() {
              return alert("success");
            },
            failture: function() {
              return alert("fail");
            }
          });
        } else {
          A2Cribs.UIManager.Alert(response.msg);
          return successHandler();
        }
      });
    };

    Order.BuyCart = function(successHandler, failHandler) {
      var url,
        _this = this;
      if (successHandler == null) successHandler = null;
      if (failHandler == null) failHandler = null;
      url = "" + myBaseUrl + "order/buyCart";
      return $.post(url, function(response_raw) {
        var response;
        response = JSON.parse(response_raw);
        if (!response.success) console.log(response.message);
        return google.payments.inapp.buy({
          parameters: {},
          jwt: response.jwt,
          success: function() {
            return alert("success");
          },
          failture: function() {
            return alert("fail");
          }
        });
      });
    };

    Order.AddToCart = function(orderItems) {
      var data, url,
        _this = this;
      data = {
        'orderItems': JSON.stringify(orderItems)
      };
      url = myBaseUrl + "shoppingCart/add";
      return $.post(url, data, function(response_raw) {
        var response;
        response = JSON.parse(response_raw);
        if (response.success) {
          return alertify.success('Added to cart', 1500);
        } else {
          return alertify.error("Adding to cart failed", 1500);
        }
      });
    };

    return Order;

  })();

  A2Cribs.FullListing = (function() {

    function FullListing() {}

    FullListing.SetupUI = function(listing_id) {
      var _this = this;
      this.listing_id = listing_id;
      this.div = $(".full_page");
      this.div.find(".show_scheduling").click(function(event) {
        var _ref;
        if (((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) === true) {
          if (!($(event.currentTarget).attr("href") != null)) {
            $("#scheduling_tour_tab").click();
          } else {
            $(event.currentTarget).tab('show');
            $(document).trigger("track_event", ["Full Page", "Schedule Tour Clicked", "", listing_id]);
          }
        } else {
          $("#signup_modal").modal("show").find(".signup_message").text("Please sign in to schedule a tour.");
          $(document).trigger("track_event", ["Login", "Login required", "Schedule Tour", listing_id]);
        }
        return event.preventDefault();
      });
      this.div.find(".image_preview").click(function(event) {
        var image;
        image = $(event.delegateTarget).css("background-image");
        _this.div.find(".image_preview.active").removeClass("active");
        $(event.delegateTarget).addClass("active");
        return _this.div.find("#main_photo").css("background-image", image);
      });
      this.div.find(".page_right").click(function(event) {
        var next_photo;
        if (_this.div.find(".image_preview.active").next().length) {
          next_photo = _this.div.find(".image_preview.active").next();
          _this.div.find(".image_preview.active").removeClass("active");
          next_photo.addClass("active");
          return _this.div.find("#main_photo").css("background-image", next_photo.css("background-image"));
        }
      });
      this.div.find(".page_left").click(function(event) {
        var next_photo;
        if (_this.div.find(".image_preview.active").prev().length) {
          next_photo = _this.div.find(".image_preview.active").prev();
          _this.div.find(".image_preview.active").removeClass("active");
          next_photo.addClass("active");
          return _this.div.find("#main_photo").css("background-image", next_photo.css("background-image"));
        }
      });
      this.div.find("#contact_owner").click(function() {
        var _ref;
        if (((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) === true) {
          $(document).trigger("track_event", ["Full Page", "Contact Owner Clicked", "", listing_id]);
          _this.div.find("#contact_owner").hide();
          return _this.div.find("#contact_message").slideDown();
        } else {
          $("#signup_modal").modal("show").find(".signup_message").text("Please sign in to contact the owner.");
          return $(document).trigger("track_event", ["Login", "Login required", "Contact Owner", listing_id]);
        }
      });
      this.div.find("#message_cancel").click(function() {
        return _this.div.find("#contact_message").slideUp('fast', function() {
          return _this.div.find("#contact_owner").show();
        });
      });
      return this.div.find("#message_send").click(function() {
        $("#message_send").button("loading");
        $(document).trigger("track_event", ["Message", "Sending Message", "", listing_id]);
        $("#loader").show();
        return $.ajax({
          url: myBaseUrl + "Messages/messageSublet",
          type: "POST",
          data: {
            listing_id: _this.listing_id,
            message_body: $("#message_area").val()
          },
          success: function(response) {
            var data;
            data = JSON.parse(response);
            if (data.success) {
              $("#message_area").val("");
              A2Cribs.UIManager.Success("Message Sent!");
              $(document).trigger("track_event", ["Message", "Message Sent", "", listing_id]);
            } else {
              if (data.message != null) {
                A2Cribs.UIManager.Error(data.message);
              } else {
                A2Cribs.UIManager.Error("Message Failed! Please Try Again.");
              }
              $(document).trigger("track_event", ["Message", "Message Failed", "", listing_id]);
            }
            return $("#message_send").button("reset");
          },
          complete: function() {
            return $("#loader").hide();
          }
        });
      });
    };

    FullListing.Directive = function(directive) {
      if (directive.contact_owner != null) {
        return this.div.find("#contact_owner").click();
      } else if (directive.schedule != null) {
        return this.div.find("#scheduling_tour_tab").click();
      }
    };

    return FullListing;

  })();

  A2Cribs.Dashboard = (function() {

    function Dashboard() {}

    Dashboard.SetupUI = function() {
      var list_content_height,
        _this = this;
      $(window).resize(function() {
        return _this.SizeContent();
      });
      this.SizeContent();
      $('.content-header').each(function(index, element) {
        var class_name, content, content_header;
        content_header = $(element);
        class_name = content_header.attr('classname');
        content = $('.' + class_name + '-content');
        $(element).click(function(event) {
          var _ref;
          if ((_ref = A2Cribs.RentalSave) != null ? _ref.Editable : void 0) {
            return A2Cribs.UIManager.ConfirmBox("By leaving this page, all unsaved changes will be lost.", {
              "ok": "Abort Changes & Continue",
              "cancel": "Return to Editor"
            }, function(success) {
              if (success) {
                A2Cribs.RentalSave.CancelEditing();
                return _this.ContentHeaderClick(event);
              }
            });
          } else {
            return _this.ContentHeaderClick(event);
          }
        });
        return typeof content_header.next === "function" ? content_header.next('.drop-down').find('.drop-down-list').click(function() {
          return _this.ShowContent(content);
        }) : void 0;
      });
      $("#feature-btn").click(function(event) {
        return _this.Direct({
          'classname': 'featured-listing'
        });
      });
      $("body").on('click', '.messages_list_item', function(event) {
        return _this.ShowContent($('.messages-content'));
      });
      list_content_height = $("#navigation-bar").parent().height() - $("#navigation-bar").height() - 68;
      $(".list_content").css("height", list_content_height + "px");
      /*
      		Search listener
      */
      $('.dropdown-search').keyup(function(event) {
        var list;
        list = $(event.delegateTarget).attr("data-filter-list");
        return $("" + list + " li").show().filter(function() {
          if ($(this).text().toLowerCase().indexOf($(event.delegateTarget).val().toLowerCase()) === -1) {
            return true;
          }
          return false;
        }).hide();
      });
      this.AttachListeners();
      return this.GetUserMarkerData();
    };

    /*
    	Attach Listeners
    	Attaches events listeners to objects
    */

    Dashboard.AttachListeners = function() {
      var _this = this;
      return $(".list_content").on("marker_added", function(event, marker_id) {
        var count, list_item, listing_type, name;
        listing_type = $(event.currentTarget).data("listing-type");
        if ($(event.currentTarget).find("#" + marker_id).length === 0) {
          name = A2Cribs.UserCache.Get("marker", marker_id).GetName();
          list_item = $("<li />", {
            text: name,
            "class": "" + listing_type + "_list_item",
            id: marker_id
          });
          count = $("#" + listing_type + "_count").text();
          $("#" + listing_type + "_count").text(parseInt(count, 10) + 1);
          $(event.currentTarget).append(list_item);
          return $(event.currentTarget).slideDown();
        }
      });
    };

    /*
    */

    Dashboard.ContentHeaderClick = function(event) {
      var class_name, content, content_header;
      content_header = $(event.delegateTarget);
      class_name = content_header.attr('classname');
      content = $('.' + class_name + '-content');
      $('.content-header.active').removeClass("active");
      $(event.delegateTarget).addClass("active");
      if (content_header.hasClass("list-dropdown-header")) {
        if (!$("#" + class_name + "_list").is(":visible")) {
          if ($(".list-dropdown.active").size() !== 0) {
            return $(".list-dropdown.active").removeClass("active").slideUp('fast', function() {
              return $("#" + class_name + "_list").addClass("active").slideDown();
            });
          } else {
            return $("#" + class_name + "_list").addClass("active").slideDown();
          }
        }
      } else {
        $(".list-dropdown").slideUp();
        return this.ShowContent(content, true);
      }
    };

    /*
    	Retrieves all basic marker_data for the logged in user and updates nav bar in dashboard
    */

    Dashboard.GetUserMarkerData = function() {
      var url,
        _this = this;
      if (this.MarkerDataDeferred != null) return this.MarkerDataDeferred;
      url = myBaseUrl + "listings/GetMarkerDataByLoggedInUser";
      $.ajax({
        url: url,
        type: "GET",
        success: function(data) {
          _this.GetUserMarkerDataCallback(data);
          return _this.MarkerDataDeferred.resolve(data);
        }
      });
      this.MarkerDataDeferred = new $.Deferred();
      return this.MarkerDataDeferred.promise();
    };

    Dashboard.GetUserMarkerDataCallback = function(data) {
      var i, list_item, listing, listing_type, listing_types, listings, listings_count, marker, _i, _len, _len2, _results;
      listings_count = [0, 0, 0];
      listing_types = ["rental", "sublet", "parking"];
      A2Cribs.UserCache.CacheData(JSON.parse(data));
      listings = A2Cribs.UserCache.Get("listing");
      for (_i = 0, _len = listings.length; _i < _len; _i++) {
        listing = listings[_i];
        marker = A2Cribs.UserCache.Get("marker", listing.marker_id);
        if ($("#" + listing_types[listing.listing_type] + "_list_content").find("#" + (marker.GetId())).length === 0) {
          list_item = $("<li />", {
            text: marker.GetName(),
            "class": "" + listing_types[listing.listing_type] + "_list_item",
            id: marker.GetId()
          });
        }
        $("#" + listing_types[listing.listing_type] + "_list_content").append(list_item);
        listings_count[listing.listing_type] += 1;
      }
      _results = [];
      for (i = 0, _len2 = listing_types.length; i < _len2; i++) {
        listing_type = listing_types[i];
        _results.push($("#" + listing_type + "_count").text(listings_count[i]));
      }
      return _results;
    };

    /*
    	Retrieves all listings for logged-in user and adds them to the cache.
    
    	Returns a promise that will return the cache when complete.
    	This can be used by other module who want to know when the dashboard
    	has the listinngs loaded.
    */

    Dashboard.GetListings = function() {
      var url;
      if (!(this.DeferedListings != null)) {
        this.DeferedListings = new $.Deferred();
      } else {
        return this.DeferedListings.promise();
      }
      url = myBaseUrl + "listings/GetListing";
      $.ajax({
        url: url,
        type: "GET",
        success: this.GetListingsCallback
      });
      return this.DeferedListings.promise();
    };

    Dashboard.GetListingsCallback = function(data) {
      var item, key, list_item, listing, listing_type, listing_types, listings, listings_count, marker, marker_id, marker_id_array, marker_set, name, response_data, type, value, _i, _j, _len, _len2, _results;
      response_data = JSON.parse(data);
      for (_i = 0, _len = response_data.length; _i < _len; _i++) {
        item = response_data[_i];
        for (key in item) {
          value = item[key];
          if (A2Cribs[key] != null) A2Cribs.UserCache.Set(new A2Cribs[key](value));
        }
      }
      listings = A2Cribs.UserCache.Get("listing");
      marker_set = {};
      for (_j = 0, _len2 = listings.length; _j < _len2; _j++) {
        listing = listings[_j];
        if (!(marker_set[listing.listing_type] != null)) {
          marker_set[listing.listing_type] = {};
        }
        marker_set[listing.listing_type][listing.marker_id] = true;
      }
      Dashboard.DeferedListings.resolve();
      listings_count = [0, 0, 0];
      listing_types = ["rentals", "sublet", "parking"];
      _results = [];
      for (listing_type in marker_set) {
        marker_id_array = marker_set[listing_type];
        _results.push((function() {
          var _results2;
          _results2 = [];
          for (marker_id in marker_id_array) {
            marker = A2Cribs.UserCache.Get("marker", marker_id);
            name = marker.GetName();
            type = listing_types[parseInt(listing_type, 10)];
            listings_count[parseInt(listing_type, 10)]++;
            list_item = $("<li />", {
              text: name,
              "class": "" + type + "_list_item",
              id: marker.marker_id
            });
            _results2.push($("#" + type + "_list_content").append(list_item));
          }
          return _results2;
        })());
      }
      return _results;
    };

    Dashboard.SizeContent = function() {};

    Dashboard.SlideDropDown = function(content_header, show_content) {
      var dropdown, toggle_icon;
      dropdown = content_header.next('.drop-down');
      if (dropdown.length === 0) return;
      toggle_icon = content_header.children('i')[0];
      $(toggle_icon).toggleClass('icon-caret-right', !show_content).toggleClass('icon-caret-down', show_content);
      $(content_header).toggleClass('shadowed', show_content).toggleClass('expanded', show_content).toggleClass('minimized', !show_content);
      if (show_content) {
        return dropdown.slideDown('fast');
      } else {
        return dropdown.slideUp('fast');
      }
    };

    Dashboard.ShowContent = function(content) {
      content.siblings().addClass('hidden').hide();
      content.removeClass('hidden').hide().fadeIn();
      return content.trigger('shown');
    };

    Dashboard.HideContent = function(classname) {
      return $("." + classname + "-content").addClass('hidden');
    };

    Dashboard.Direct = function(directive) {
      var content_header;
      content_header = $("#" + directive.classname + "-content-header");
      content_header.trigger('click');
      if (directive.data != null) {
        return this.ShowContent($("." + directive.classname + "-content"));
      }
    };

    return Dashboard;

  }).call(this);

  $("body").ready(function() {
    var _ref;
    if ($("#main_content").length) {
      A2Cribs.VerifyManager.init(JSON.parse($("#user_info_json").val()));
      A2Cribs.Dashboard.SetupUI();
      A2Cribs.Account.setupUI();
      if (((_ref = window.directive) != null ? _ref.classname : void 0) != null) {
        A2Cribs.Dashboard.Direct(window.directive);
        A2Cribs.Messages.Direct(window.directive);
        A2Cribs.Account.Direct(window.directive);
      }
      A2Cribs.Messages.init(JSON.parse($("#user_info_json").val()));
      A2Cribs.Messages.setupUI();
      if (document.URL.indexOf("university_verified") !== -1) {
        return A2Cribs.UIManager.Alert("You have successfully been verified with a university!");
      }
    }
  });

  A2Cribs.Account = (function() {

    function Account() {}

    Account.setupUI = function() {
      var my_verification_info, veripanel, _ref,
        _this = this;
      my_verification_info = (_ref = A2Cribs.VerifyManager) != null ? _ref.getMyVerification() : void 0;
      veripanel = $('#my-verification-panel');
      if (my_verification_info != null) {
        if (my_verification_info.verified_email) {
          veripanel.find('#veri-email i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign');
        }
        if (my_verification_info.verified_edu) {
          veripanel.find('#veri-edu i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign');
        }
        if (my_verification_info.verified_fb) {
          veripanel.find('#veri-fb  i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign');
        } else {
          $('#veri-fb').append("<a href = '#'>Verify?</a>").click(this.FacebookConnect);
        }
      }
      $('.veridd').each(function(index, element) {
        return $(element).tooltip({
          'title': 'Verify?',
          'trigger': 'hover'
        });
      });
      $('#changePasswordButton').click(function(event) {
        $(event.delegateTarget).button('loading');
        return _this.ChangePassword($('#changePasswordButton'), $('#new_password').val(), $('#confirm_password').val(), $("#u_id").val(), $("#reset_token").val()).always(function() {
          return $(event.delegateTarget).button('reset');
        });
      });
      $('#VerifyUniversityButton').click(function(event) {
        return _this.VerifyUniversity();
      });
      $('#changePhoneBtn').click(function(event) {
        $(event.delegateTarget).button('loading');
        return _this.SavePhone().always(function() {
          return $(event.delegateTarget).button('reset');
        });
      });
      $('#changeAddressBtn').click(function(event) {
        $(event.delegateTarget).button('loading');
        return _this.SaveAddress().always(function() {
          return $(event.delegateTarget).button('reset');
        });
      });
      $('#changeCompanyNameBtn').click(function(event) {
        $(event.delegateTarget).button('loading');
        return _this.SaveCompanyName().always(function() {
          return $(event.delegateTarget).button('reset');
        });
      });
      $('#changeFirstLastNameButton').click(function(event) {
        $(event.delegateTarget).button('loading');
        return _this.SaveFirstLastName().always(function() {
          return $(event.delegateTarget).button('reset');
        });
      });
      return $('#changeEmailButton').click(function(event) {
        $(event.delegateTarget).button('loading');
        return _this.SaveEmail().always(function() {
          return $(event.delegateTarget).button('reset');
        });
      });
    };

    Account.SaveFirstLastName = function() {
      var pair;
      pair = {
        'first_name': $("#first_name_input").val(),
        'last_name': $("#last_name_input").val()
      };
      return this.SaveAccount(pair, $("#changeFirstLastNameButton"));
    };

    Account.SaveEmail = function() {
      var pair;
      pair = {
        'email': $("#new_email").val()
      };
      return this.SaveAccount(pair, $("#changeEmailButton"));
    };

    Account.SaveCompanyName = function() {
      var pair;
      pair = {
        'company_name': $("#company_name_input").val()
      };
      return this.SaveAccount(pair, $("#changeCompanyNameButton"));
    };

    Account.SavePhone = function() {
      var pair, phone;
      phone = $("#phone_input").val();
      if (this.ValidatePhone(phone)) {
        pair = {
          'phone': phone
        };
        return this.SaveAccount(pair, $("#changePhoneBtn"));
      } else {
        A2Cribs.UIManager.Error("Invalid phone number");
        return (new $.Deferred()).reject();
      }
    };

    Account.ValidatePhone = function(phone) {
      phone = phone.replace(/[^0-9]/g, '');
      return phone.length === 10;
    };

    Account.SaveAddress = function() {
      var city, pair, street_address;
      street_address = $("#street_address_input").val();
      city = $("#city_address_input").val();
      pair = {
        'street_address': street_address,
        'city': city
      };
      return this.SaveAccount(pair, $("#changeAddressBtn"));
    };

    Account.Direct = function(directive) {};

    Account.VerifyUniversity = function() {
      var data, university_email;
      $('#VerifyUniversityButton').attr('disabled', 'disabled');
      university_email = $('#university_email').val();
      data = {
        'university_email': university_email
      };
      if (university_email.search('.edu') !== -1) {
        return $.post(myBaseUrl + 'users/verifyUniversity', data, function(response) {
          var json_response;
          console.log(data);
          json_response = JSON.parse(response);
          if (json_response.success === 1) {
            A2Cribs.UIManager.Error('Please check your email for a verification link.');
          } else {
            A2Cribs.UIManager.Error('Verification not successful: ' + json_response.message);
          }
          return $('#VerifyUniversityButton').removeAttr('disabled');
        });
      } else {
        return A2Cribs.UIManager.Error('Please enter a university email.');
      }
    };

    Account.ChangePassword = function(change_password_button, new_password, confirm_password, id, reset_token, redirect) {
      var data,
        _this = this;
      if (id == null) id = null;
      if (reset_token == null) reset_token = null;
      if (redirect == null) redirect = null;
      this._change_password_deferred = new $.Deferred();
      data = {
        'new_password': new_password,
        'confirm_password': confirm_password
      };
      if (id !== null && reset_token !== null) {
        data['id'] = id;
        data['reset_token'] = reset_token;
      }
      if (new_password.length < 5) {
        A2Cribs.UIManager.Alert("Password must be at least 6 characters long.");
        return this._change_password_deferred.reject();
      }
      if (new_password !== confirm_password) {
        A2Cribs.UIManager.Alert("Passwords do not match.");
        return this._change_password_deferred.reject();
      }
      $.ajax({
        url: myBaseUrl + 'users/AjaxChangePassword',
        data: data,
        type: "POST",
        success: function(response) {
          response = JSON.parse(response);
          if (response.error != null) {
            A2Cribs.UIManager.Alert(response.error);
            return _this._change_password_deferred.reject();
          } else {
            if (id === null && reset_token === null) {
              alertify.success('Password Changed', 3000);
              if (redirect !== null) window.location.href = redirect;
            } else {
              window.location.href = '/dashboard';
            }
            return _this._change_password_deferred.resolve();
          }
        },
        error: function() {
          return _this._change_password_deferred.reject();
        }
      });
      return this._change_password_deferred.promise();
    };

    Account.SaveAccount = function(keyValuePairs, button) {
      if (keyValuePairs == null) keyValuePairs = null;
      if (button == null) button = null;
      return $.post(myBaseUrl + 'users/AjaxEditUser', keyValuePairs, function(response) {
        var json_response;
        json_response = JSON.parse(response);
        if (json_response.error === void 0) {
          alertify.success('Account Saved', 3000);
        } else {
          A2Cribs.UIManager.Error('Account Failed to Save: ' + json_response.error);
        }
        if (button != null) return button.removeAttr('disabled');
      });
    };

    Account.FacebookConnect = function() {
      return FB.login(function(response) {
        $.ajax({
          url: myBaseUrl + "account/verifyFacebook",
          data: {
            'signed_request': response.authResponse.signedRequest
          },
          type: "POST"
        });
        return document.location.href = '/account';
      });
    };

    /*
    	Submits email address for which to reset password.
    */

    Account.SubmitResetPassword = function(email) {
      var data,
        _this = this;
      data = 'email=' + $("#UserEmail").val();
      return $.post('/users/AjaxResetPassword', data, function(response) {
        data = JSON.parse(response);
        if (data.success != null) {
          A2Cribs.UIManager.Alert("Email sent to reset password!");
          return false;
        } else {
          A2Cribs.UIManager.Error(data.error);
          return false;
        }
      });
    };

    return Account;

  })();

  A2Cribs.Messages = (function() {
    var create_message_div;

    function Messages() {}

    Messages.setupUI = function() {
      var _this = this;
      $('#send_reply').click(function() {
        return _this.sendReply();
      });
      $('#view_unread_cb').change(function() {
        return _this.toggleUnreadConversations();
      });
      $('#refresh_content').click(function() {
        return _this.refresh();
      });
      $('#current_conversation').scroll(function(event) {
        return _this.MessageScrollingHandler(event);
      });
      $('#meaning').click(function() {
        return $('#hidden-meaning').fadeToggle();
      });
      $('#delete_conversation').click(function() {
        return _this.DeleteConversation();
      });
      return this.refresh();
    };

    Messages.ScrollMessagesTo = function(mli) {
      var cc, dist;
      cc = $('#current_conversation');
      dist = (cc.offset().top + cc.innerHeight()) - (mli.offset().top + mli.innerHeight() + 10);
      return cc.scrollTop(cc.scrollTop() - dist);
    };

    Messages.MessageScrollingHandler = function(event) {
      if ($("#current_conversation").scrollTop() > 20 || this.NumMessagePages === 0) {
        return;
      }
      return this.loadMessages(this.NumMessagePages + 1);
    };

    Messages.refresh = function() {
      this.refreshUnreadCount();
      this.refreshConversations();
      if (this.CurrentConversation !== -1) {
        this.refreshParticipantInfo();
        return this.refreshMessages();
      }
    };

    Messages.refreshUnreadCount = function() {
      var url,
        _this = this;
      url = myBaseUrl + "messages/getUnreadCount";
      return $.get(url, function(data) {
        var response_data;
        response_data = JSON.parse(data);
        return $('#message_count').html(response_data.unread_messages);
      });
    };

    Messages.refreshConversations = function() {
      var url,
        _this = this;
      url = myBaseUrl + "messages/getConversations";
      return $.get(url, function(data) {
        var conversations, convo, item_html, list_item, participant_name, _i, _len, _ref, _results;
        $("#messages_list_content").empty();
        conversations = JSON.parse(data);
        _results = [];
        for (_i = 0, _len = conversations.length; _i < _len; _i++) {
          convo = conversations[_i];
          participant_name = ((_ref = convo.Participant.first_name) != null ? _ref.length : void 0) ? convo.Participant.first_name : convo.Participant.company_name;
          item_html = "<div class=\"message_title\">" + convo.Conversation.title + "</div>\n<div class=\"message_desc\">\n	" + (convo.Last_Message.user_id === convo.Participant.id ? participant_name : "Me") + ": " + convo.Last_Message.body + "\n</div>";
          list_item = $("<li />", {
            html: item_html,
            "class": "messages_list_item",
            id: convo.Conversation.conversation_id,
            "data-participant": convo.Participant.id,
            "data-listing": convo.Conversation.listing_id,
            "data-title": convo.Conversation.title
          });
          if (parseInt(convo.Conversation.unread_message_count, 10) > 0) {
            list_item.addClass("unread");
          }
          $("#messages_list_content").append(list_item);
          _results.push(_this.attachConversationListItemHandler(list_item));
        }
        return _results;
      });
    };

    Messages.toggleUnreadConversations = function() {
      this.ViewOnlyUnread = $('#view_unread_cb').is(':checked');
      return this.refreshConversations();
    };

    Messages.refreshParticipantInfo = function() {
      var conversation_id, participantid, url;
      participantid = Messages.CurrentParticipantID;
      conversation_id = Messages.CurrentConversation;
      if (Messages.ParticipantInfoCache[participantid] != null) {
        Messages.setParticipantInfoUI(Messages.ParticipantInfoCache[participantid]);
        return;
      }
      url = url = myBaseUrl + "messages/getParticipantInfo/" + conversation_id + "/";
      return $.ajax({
        url: url,
        type: "GET",
        success: function(data) {
          var user_data;
          user_data = JSON.parse(data);
          Messages.ParticipantInfoCache[user_data['id']] = user_data;
          return Messages.setParticipantInfoUI(Messages.ParticipantInfoCache[participantid]);
        }
      });
    };

    Messages.setParticipantInfoUI = function(participant) {
      var img_url, nameString;
      nameString = "";
      if (participant.first_name != null) {
        nameString += participant.first_name;
        if (participant.last_name != null) {
          nameString += " " + participant.last_name;
        }
      }
      $(".from_participant").html(nameString);
      img_url = "img/head_large.jpg";
      if (participant.profile_img != null) {
        img_url = "/" + participant.profile_img;
      } else if (participant.facebook_id != null) {
        img_url = "https://graph.facebook.com/" + participant.facebook_id + "/picture?width=480";
      }
      return $('#p_pic').attr('src', img_url);
    };

    Messages.loadConversation = function(event) {
      var listing, title, total_count, unread_count;
      $('.messages_list_item').removeClass('selected');
      $(event.currentTarget).addClass('selected').removeClass('unread');
      unread_count = parseInt($(event.currentTarget).find(".notification_count").text(), 10);
      if (unread_count > 0) {
        total_count = parseInt($("#message_count").text(), 10);
        $(event.currentTarget).find(".notification_count").text("0");
        $("#message_count").text(total_count - unread_count);
      }
      this.CurrentConversation = parseInt($(event.delegateTarget).attr('id'));
      this.CurrentParticipantID = $(event.delegateTarget).attr('data-participant');
      $('#message_reply').show();
      $('#participant_info_short').show();
      title = $(event.delegateTarget).attr("data-title");
      listing = $(event.delegateTarget).attr("data-listing");
      $('#listing_title').text(title).attr('href', "/listings/view/" + listing);
      this.refreshParticipantInfo();
      this.refreshUnreadCount();
      return this.refreshMessages(event);
    };

    Messages.loadMessages = function(page, align_bottom, event) {
      var url, _ref,
        _this = this;
      if (align_bottom == null) align_bottom = false;
      if (event == null) event = null;
      if (((_ref = this.DeferredLoadMessages) != null ? _ref.state() : void 0) === "pending") {
        return;
      }
      this.DeferredLoadMessages = new $.Deferred();
      url = myBaseUrl + "messages/getMessages/" + this.CurrentConversation + "/" + page + "/";
      $.get(url, function(data, textStatus) {
        var diff, initial_height, message, message_batch, message_list, messages, _i, _len;
        messages = JSON.parse(data);
        if (messages.error !== void 0) {
          _this.DeferredLoadMessages.resolve();
          return;
        }
        message_list = $('#message_list');
        initial_height = message_list.innerHeight();
        message_batch = "";
        for (_i = 0, _len = messages.length; _i < _len; _i++) {
          message = messages[_i];
          message_batch += create_message_div(message);
        }
        $(message_batch).hide().prependTo('#message_list').fadeIn();
        $('.mli').each(function(index, element) {
          var new_height;
          new_height = $(this).find('.message_buble').height();
          return $(this).css('height', new_height + 'px');
        });
        if (align_bottom) {
          _this.ScrollMessagesTo($("#mli_0"));
        } else {
          diff = message_list.innerHeight() - initial_height;
          $('#current_conversation').scrollTop($('#current_conversation').scrollTop() + diff);
        }
        $('#current_conversation').trigger('scroll');
        if (event != null) {
          _this.attachConversationListItemHandler(event.delegateTarget);
        }
        _this.NumMessagePages = page;
        return _this.DeferredLoadMessages.resolve();
      }).fail(function() {
        return _this.NumMessagePages = 0;
      });
      return this.DeferredLoadMessages.promise();
    };

    Messages.attachConversationListItemHandler = function(container) {
      var _this = this;
      return $(container).one('click', function(event) {
        return _this.loadConversation(event);
      });
    };

    Messages.refreshMessages = function(event) {
      var message_list;
      this.NumMessagePages = 1;
      message_list = $('#message_list');
      message_list.empty();
      $("#loader").show();
      return this.loadMessages(this.NumMessagePages, true, event).always(function() {
        return $("#loader").hide();
      });
    };

    Messages.sendReply = function(event) {
      var message_data, message_text, url,
        _this = this;
      message_text = $('#message_text textarea').val();
      if (message_text.length === 0) {
        A2Cribs.UIManager.Error("Message can not be empty");
        return false;
      }
      $('#send_reply').button('loading');
      message_text = $('#message_text textarea').val();
      message_data = {
        'message_text': message_text,
        'conversation_id': this.CurrentConversation
      };
      url = myBaseUrl + "messages/newMessage/";
      $.post(url, message_data, function(data) {
        var response;
        _this.refreshMessages();
        _this.refreshConversations();
        $('#message_text textarea').val('');
        response = JSON.parse(data);
        if ((data != null ? data.success : void 0) === false) {
          return A2Cribs.UIManager.Error("Something went wrong while sending a reply, please refresh the page and try again");
        }
      }).always(function() {
        return $('#send_reply').button('reset');
      });
      return false;
    };

    Messages.DeleteConversation = function() {
      var request_data, url,
        _this = this;
      url = myBaseUrl + "messages/deleteConversation/";
      request_data = {
        'conv_id': this.CurrentConversation
      };
      return $.post(url, request_data, function(response) {
        var data;
        try {
          data = JSON.parse(response);
        } catch (e) {
          A2Cribs.UIManager.Error('Failed to delete the conversation');
          return;
        }
        if (data.success === 1) {
          alertify.success('Conversation deleted', 3000);
          _this.CurrentConversation = -1;
          _this.CurrentParticipantID = -1;
          A2Cribs.Dashboard.HideContent('messages');
          return _this.refresh();
        } else {
          return A2Cribs.UIManager.Error('Failed to delete the conversation');
        }
      });
    };

    create_message_div = function(message) {
      return "<div class = 'mli mli-" + message.side + "-side row-fluid' id = 'mli_" + message.count + "' meta = '" + message.id + "'>			<div class = 'span12'>				<div class = 'participant_message_pic'>						<img src = '" + message.pic + "'></img>				</div>				<img src = '/img/messages/arrow-" + message.side + ".png' class = 'arrow-" + message.side + "'></img>				<div class = 'message_bubble'>					<div>						<span class = 'bubble-top-row'>							<strong>" + message.name + ":</strong>							<span class = 'time-ago'>" + message.time_ago + "</span>						</span>						<p class = 'message_body'>" + message.body + "</p>					</div>				</div>			</div>		</div>";
    };

    Messages.Direct = function(directive) {
      var conv_id, participant_id;
      if ((directive.data != null) && directive.classname === "messages") {
        conv_id = parseInt(directive.data.conversation_id);
        this.CurrentConversation = conv_id;
        participant_id = parseInt(directive.data.participant_id);
        this.CurrentParticipantID = participant_id;
        return $('#listing_title').text(directive.data.title);
      }
    };

    Messages.init = function(user) {
      this.me = user;
      this.ViewOnlyUnread = false;
      if (!(this.CurrentConversation != null)) this.CurrentConversation = -1;
      this.DropDownVisible = false;
      this.NumMessagePages = -1;
      if (!(this.CurrentParticipantID != null)) this.CurrentParticipantID = -1;
      this.ParticipantInfoCache = {};
      return this.LoadingMessages = false;
    };

    return Messages;

  }).call(this);

  A2Cribs.PropertyManagement = (function() {

    function PropertyManagement() {}

    PropertyManagement.removeSublet = function(id) {
      var _this = this;
      return alertify.confirm("Are you sure you want to delete this property? This can't be undone.", function(e) {
        var url;
        if (e) {
          url = myBaseUrl + ("sublets/remove/" + id);
          return window.location.href = url;
        } else {

        }
      });
    };

    return PropertyManagement;

  })();

  A2Cribs.MobileFilter = (function(_super) {

    __extends(MobileFilter, _super);

    function MobileFilter() {
      MobileFilter.__super__.constructor.apply(this, arguments);
    }

    MobileFilter.FilterData = {};

    /*
    	Creates all listeners and jquery events for MobileFilter
    */

    MobileFilter.SetupUI = function() {
      var _this = this;
      this.div = $("#mobile_filter");
      this.div.find('select#listing_type').change(function(e) {
        var school_name;
        console.log($(e.target).val());
        school_name = _this.div.data('university-name');
        switch ($(e.target).val()) {
          case "Rentals":
            return window.location.href = "/rental/" + school_name;
          case "Sublets":
            return window.location.href = "/sublet/" + school_name;
        }
      });
      this.div.find('select#bedrooms').change(function(e) {
        var $opt, max, min, _i, _results;
        $opt = $(e.target).find('option:selected');
        min = parseInt($opt.data('min'), 10);
        max = parseInt($opt.data('max'), 10);
        return _this.ApplyFilter("Beds", (function() {
          _results = [];
          for (var _i = min; min <= max ? _i <= max : _i >= max; min <= max ? _i++ : _i--){ _results.push(_i); }
          return _results;
        }).apply(this));
      });
      return this.div.find('select#rent').change(function(e) {
        var $opt;
        $opt = $(e.target).find('option:selected');
        return _this.ApplyFilter("Rent", {
          min: parseInt($opt.data("min"), 10),
          max: parseInt($opt.data("max"), 10)
        });
      });
    };

    /*
    	Called immediately after user applies a filter.
    	Submits an ajax call with all current filter parameters
    */

    MobileFilter.ApplyFilter = function(field, value) {
      var ajaxData, first, key, _ref;
      if (value != null) {
        this.FilterData[field] = value;
      } else {
        delete this.FilterData[field];
      }
      ajaxData = '';
      first = true;
      _ref = this.FilterData;
      for (key in _ref) {
        value = _ref[key];
        if (!first) ajaxData += "&";
        first = false;
        ajaxData += key + "=" + JSON.stringify(value);
      }
      $("#loader").show();
      return $.ajax({
        url: myBaseUrl + ("Listings/ApplyFilter/" + A2Cribs.FilterManager.ActiveListingType),
        data: ajaxData,
        type: "GET",
        context: this,
        success: A2Cribs.FilterManager.UpdateListings,
        complete: function() {
          return $("#loader").hide();
        }
      });
    };

    /*
    	Get Backend Date Format
    	Replaces '/' with '-' to make convertible to db format
    */

    MobileFilter.GetBackendDateFormat = function(dateString) {
      var beginDateFormatted, date, day, month, year;
      date = new Date(dateString);
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = year + "-" + month + "-" + day;
    };

    return MobileFilter;

  })(A2Cribs.FilterManager);

  /*
  Manager class for all verify functionality
  */

  A2Cribs.VerifyManager = (function() {

    function VerifyManager() {}

    VerifyManager.init = function(user) {
      if (user == null) user = null;
      this.me = user;
      return this.VerificationData = {};
    };

    /*    
    	Returns a JQuery defered object. Example way to call the function is
    
    	@getVerificationFor(user).then (verification_info)->
    	  # Do what you want with the data
    
    	the verification info object has the following key value pairs
    	{
    		'user_id': int
    		'fb_id': int or null
    		'tw_id': int or null
    		'verified_email': bool,
    		'verificed_edu': bool,
    		'verified_fb': bool,
    		'verified_tw': bool,
    		'mutual_friends': int or null, #depends if the user is verified on fb and if you are verified on fb
    		'total_friends': int or null, #depends on if the user is verified on fb
    		'total_followers' int or null, #depends on if the user is verified ob tw
    	}
    
    	You do not need to worry about caching the data as this function already provides this functionality
    
    	Jquery deferred      http://api.jquery.com/category/deferred-object/
    */

    VerifyManager.getVerificationFor = function(user_) {
      var defered, user;
      if (!(this.VerificationData[user_.id] != null)) {
        defered = new $.Deferred();
        user = user_;
        this.VerificationData[user.id] = defered;
        $.when(this.getTotalFriends(user), this.getMutalFriends(user), this.getTwitterFollowers(user)).done(function(tot_friends, mut_friends, followers_count) {
          var verification_info;
          verification_info = {
            'user_id': user.id,
            'fb_id': user.facebook_id,
            'verified_email': user.verified === true,
            'verified_edu': user.university_verified === true,
            'tw_id': user.twitter_userid,
            'verified_fb': tot_friends,
            'mut_friends': mut_friends,
            'tot_friends': tot_friends,
            'verified_tw': followers_count != null,
            'tot_followers': followers_count
          };
          return defered.resolve(verification_info);
        });
      }
      return this.VerificationData[user_.id];
    };

    VerifyManager.getMutalFriends = function(user) {
      var defered, query, _ref;
      defered = new $.Deferred();
      if ((((_ref = this.me) != null ? _ref.facebook_id : void 0) != null) && (user.facebook_id != null)) {
        query = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + this.me.facebook_id + ') AND uid IN (SELECT uid2 FROM friend WHERE uid1 = ' + user.facebook_id + ')';
        FB.api({
          method: 'fql.query',
          query: query
        }, function(mut_friends_res) {
          if (mut_friends_res.error_code != null) {
            console.log("Error during verification fb error: " + mut_friends_res.error_code + ".");
            defered.resolve(null);
          }
          return defered.resolve(mut_friends_res.length);
        });
        return defered.promise();
      } else {
        return defered.resolve(null);
      }
    };

    VerifyManager.getTotalFriends = function(user) {
      var defered, query;
      defered = new $.Deferred();
      if (user.facebook_id != null) {
        query = 'SELECT friend_count FROM user WHERE uid = ' + user.facebook_id;
        FB.api({
          method: 'fql.query',
          query: query
        }, function(tot_friends_res) {
          if (tot_friends_res.error_code != null) {
            console.log("Error during verification fb error: " + tot_friends_res.error_code + ".");
            defered.resolve(null);
          }
          return defered.resolve(parseInt(tot_friends_res[0].friend_count));
        });
        return defered.promise();
      } else {
        return defered.resolve(null);
      }
    };

    VerifyManager.getTwitterFollowers = function(user) {
      var defered,
        _this = this;
      defered = new $.Deferred();
      if (user.twitter_userid != null) {
        $.ajax({
          url: myBaseUrl + "Users/GetTwitterFollowers/" + user.id,
          type: "GET",
          success: function(response) {
            var data;
            data = JSON.parse(response);
            return defered.resolve(data.followers_count);
          }
        });
        return defered.promise();
      } else {
        return defered.resolve(null);
      }
    };

    VerifyManager.getMyVerification = function() {
      var my_verif_info;
      if (!(this.me != null)) return null;
      my_verif_info = {
        'user_id': parseInt(this.me.id),
        'fb_id': parseInt(this.me.facebook_id),
        'tw_id': this.me.twitter_userid,
        'verified_email': this.me.verified === true,
        'verified_edu': this.me.university_verified === true,
        'verified_fb': this.me.facebook_id != null,
        'verified_tw': this.me.twitter_userid != null
      };
      return my_verif_info;
    };

    return VerifyManager;

  })();

  A2Cribs.UserCache = (function() {
    var _cache_object, _get,
      _this = this;

    function UserCache() {}

    UserCache.Cache = {};

    _get = function(object_type, id, callback) {
      var url,
        _this = this;
      if (object_type === "listing" || object_type === "rental") {
        url = myBaseUrl + "Listings/GetListing/" + id;
      }
      return $.ajax({
        url: url,
        type: "GET",
        success: function(data) {
          return callback != null ? callback.success(JSON.parse(data)) : void 0;
        },
        error: function() {
          return callback != null ? callback.error() : void 0;
        }
      });
    };

    _cache_object = function(object) {
      var a2_object, key, old_object, value, _results;
      _results = [];
      for (key in object) {
        value = object[key];
        if (A2Cribs[key] != null) {
          a2_object = new A2Cribs[key](value);
          old_object = UserCache.Get(key.toLowerCase(), a2_object.GetId());
          if ((old_object != null) && !(old_object.length != null)) {
            a2_object = old_object.Update(value);
          }
          _results.push(UserCache.Set(a2_object));
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    /*
    	Cache Data
    	Caches an array of Listing objects
    */

    UserCache.CacheData = function(object) {
      var item, _i, _len, _results;
      if (object instanceof Array) {
        _results = [];
        for (_i = 0, _len = object.length; _i < _len; _i++) {
          item = object[_i];
          _results.push(_cache_object(item));
        }
        return _results;
      } else {
        return _cache_object(object);
      }
    };

    /*
    	Get Listing
    	Retrieves a listing_type (rental, sublet, parking) by a listing id
    	Uses deferred object to fetch from async
    */

    UserCache.GetListing = function(listing_type, listing_id) {
      var deferred, listing,
        _this = this;
      deferred = new $.Deferred();
      if (!(listing_type != null) || !(listing_id != null)) {
        return deferred.reject();
      }
      listing = A2Cribs.UserCache.Get(listing_type, listing_id);
      if (listing != null ? listing.IsComplete() : void 0) {
        return deferred.resolve(listing);
      }
      $.ajax({
        url: myBaseUrl + "Listings/GetListing/" + listing_id,
        type: "GET",
        success: function(data) {
          var response_data;
          response_data = JSON.parse(data);
          _this.CacheData(response_data);
          listing = _this.Get(listing_type, listing_id);
          return deferred.resolve(listing);
        },
        error: function() {
          return deferred.reject();
        }
      });
      return deferred.promise();
    };

    UserCache.GetDiferred = function(object_type, id) {
      var deferred, item,
        _this = this;
      deferred = new $.Deferred();
      item = this.Get(object_type, id);
      if (!(item != null) || !item.IsComplete()) {
        _get(object_type, id, {
          success: function(data) {
            var key, listing_object, value, _i, _len;
            for (_i = 0, _len = data.length; _i < _len; _i++) {
              listing_object = data[_i];
              for (key in listing_object) {
                value = listing_object[key];
                if (A2Cribs[key] != null) _this.Set(new A2Cribs[key](value));
              }
            }
            return item = _this.Get(object_type, id);
          },
          error: function() {
            return deferred.resolve(null);
          }
        });
        return deferred.promise();
      } else {
        return deferred.resolve(item);
      }
    };

    UserCache.Set = function(object) {
      var class_name;
      class_name = object.class_name;
      if (!(this.Cache[object.class_name] != null)) {
        this.Cache[object.class_name] = {};
      }
      return this.Cache[object.class_name][object.GetId()] = object;
    };

    UserCache.Get = function(object_type, id) {
      var item, list;
      if (this.Cache[object_type] != null) {
        if (id != null) {
          return this.Cache[object_type][id];
        } else {
          list = [];
          for (item in this.Cache[object_type]) {
            list.push(this.Cache[object_type][item]);
          }
          return list;
        }
      }
      if (id != null) {
        return null;
      } else {
        return [];
      }
    };

    UserCache.Remove = function(object_type, id) {
      if ((this.Cache[object_type] != null) && (id != null)) {
        return delete this.Cache[object_type][id];
      }
    };

    /*
    	Think of it as Get all {return_type} with a sorted_type_id that equals
    	sorted_id
    	Get all images with a listing_id of 3 would be
    	GetAllAssociatedObjects("image", "listing", listing_id)
    */

    UserCache.GetAllAssociatedObjects = function(return_type, sorted_type, sorted_id) {
      var item, list, return_id, return_list;
      if ((return_type != null) && (sorted_type != null) && (sorted_id != null)) {
        list = {};
        return_list = [];
        sorted_id = parseInt(sorted_id, 10);
        for (item in this.Cache[return_type]) {
          if (this.Cache[return_type][item]["" + sorted_type + "_id"] != null) {
            return_id = parseInt(this.Cache[return_type][item]["" + sorted_type + "_id"], 10);
            if (return_id === sorted_id) {
              list[this.Cache[return_type][item].GetId()] = true;
            }
          }
        }
        for (item in list) {
          return_list.push(this.Get(return_type, item));
        }
        return return_list;
      }
    };

    return UserCache;

  }).call(this);

  /*
  Class is for scheduling and picking a time to tour
  */

  Tour = (function() {
    var date, months, timeslots, weekdays,
      _this = this;

    function Tour() {}

    Tour.DATE_RANGE_SIZE = 3;

    Tour.current_offset = 1;

    /*
    	Object map that contains the selected timeslots
    	Hashed by string of the date_offset and timeslot
    */

    Tour.selected_timeslots = {};

    weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    timeslots = [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];

    /*
    	Private Helper function
    	Returns an object with strings for day and month
    	Today offset is zero
    */

    date = function(day_offset) {
      var offset_date, today;
      if (day_offset == null) day_offset = 0;
      today = new Date();
      offset_date = new Date(today.getTime() + day_offset * 24 * 60 * 60 * 1000);
      return {
        date: offset_date.getDate(),
        day: weekdays[offset_date.getDay()],
        month: months[offset_date.getMonth()],
        year: offset_date.getYear()
      };
    };

    /*
    	Listens for when the element is ready
    	Will not fire if schedule_tour div is
    	not contained on the page
    */

    $(document).ready(function() {
      if ($("#schedule_tour").length) {
        Tour.SetupCalendarUI();
        Tour.SetDates(Tour.current_offset);
        Tour.SetupInfoUI();
        return $(document).on("logged_in", function(event, user) {
          if ((user != null ? user.phone : void 0) != null) {
            $("#phone_verified").val(user.phone);
            $("#verify_phone_number").val(user.phone);
            return $("#verify_phone_btn").addClass("verified").text("Verified").prop("disabled", true);
          }
        });
      }
    });

    /*
    	SetupCalendarUI
    	Adds listeners for click objects in the
    	schedule tour element
    */

    Tour.SetupCalendarUI = function() {
      var _this = this;
      $(".time_slot").mouseenter(function(event) {
        var filler;
        filler = $(event.delegateTarget).find(".time_slot_filler");
        if ($(event.delegateTarget).hasClass("selected")) {
          filler.find("i").removeClass("icon-ok-sign").addClass("icon-remove-sign");
        }
        return filler.show();
      }).mouseleave(function(event) {
        var filler;
        filler = $(event.delegateTarget).find(".time_slot_filler");
        if ($(event.delegateTarget).hasClass("selected")) {
          return filler.show().find("i").removeClass("icon-remove-sign").addClass("icon-ok-sign");
        } else {
          return filler.hide();
        }
      }).click(function(event) {
        var filler, offset_date;
        filler = $(event.delegateTarget).find(".time_slot_filler");
        if ($(event.delegateTarget).hasClass("selected")) {
          filler.hide().find("i").removeClass("icon-remove-sign").addClass("icon-plus-sign");
          $(event.delegateTarget).removeClass("selected");
          offset_date = parseInt($(event.delegateTarget).attr("data-dateoffset"), 10);
          return _this.DeleteTimeSlot(_this.current_offset + offset_date, $(event.delegateTarget).attr("data-timeslot"));
        } else {
          filler.show().find("i").removeClass("icon-plus-sign").addClass("icon-ok-sign");
          $(event.delegateTarget).addClass("selected");
          offset_date = parseInt($(event.delegateTarget).attr("data-dateoffset"), 10);
          return _this.AddTimeSlot(_this.current_offset + offset_date, $(event.delegateTarget).attr("data-timeslot"));
        }
      });
      $("#next_date").click(function() {
        _this.current_offset += _this.DATE_RANGE_SIZE;
        _this.SetDates(_this.current_offset);
        if (_this.current_offset > _this.DATE_RANGE_SIZE) {
          return $("#prev_date").removeClass("disabled");
        }
      });
      $("#prev_date").click(function() {
        if (_this.current_offset - _this.DATE_RANGE_SIZE > 0) {
          _this.current_offset -= _this.DATE_RANGE_SIZE;
          _this.SetDates(_this.current_offset);
          if (_this.current_offset < _this.DATE_RANGE_SIZE) {
            return $("#prev_date").addClass("disabled");
          }
        }
      });
      return $("#request_times_btn").click(function() {
        if (_this.TimeSlotCount() >= 3) {
          $("#calendar_picker").hide();
          return $("#schedule_info").show();
        } else {
          A2Cribs.UIManager.CloseLogs();
          return A2Cribs.UIManager.Error("Please select at least three time slots that work for you!");
        }
      });
    };

    /*
    	Setup My Info UI
    */

    Tour.SetupInfoUI = function() {
      var _this = this;
      $("body").on("keyup", ".roommate_input", function(event) {
        var re, _ref;
        if (((_ref = $(event.currentTarget.parentElement).find(".roommate_name").val()) != null ? _ref.length : void 0) !== 0) {
          re = /\S+@\S+\.\S+/;
          if (re.test($(event.currentTarget.parentElement).find(".roommate_email").val())) {
            $(event.currentTarget.parentElement).addClass("completed_roommate");
            return;
          }
        }
        return $(event.currentTarget.parentElement).removeClass("completed_roommate");
      });
      $("#back_to_timeslots").click(function() {
        $("#schedule_info").hide();
        return $("#calendar_picker").show();
      });
      $("#add_roommate_email").click(function() {
        var email_row, row_count;
        row_count = $(".email_row").last().data("email-row");
        email_row = $("<div data-email-row='" + (row_count + 1) + "' class='row-fluid email_row'>				<input class='roommate_input roommate_name' type='text' placeholder='Name'>				<input class='roommate_input roommate_email' type='email' placeholder='Email'>				<span class='complete_email'><i class='icon-ok-sign icon-large'></i></span>			</div>");
        return $("#email_invite_list").append(email_row);
      });
      $("#verify_phone_number").keyup(function() {
        var phone;
        phone = $("#verify_phone_number").val();
        if ((phone != null ? phone.length : void 0) > 0 && phone === $("#phone_verified").val()) {
          return $("#verify_phone_btn").addClass("verified").text("Verified").prop("disabled", true);
        } else {
          return $("#verify_phone_btn").removeClass("verified").text("Click to Verify").prop("disabled", false);
        }
      });
      $("#verify_phone_btn").click(function() {
        var phone;
        phone = $("#verify_phone_number").val();
        if ((phone != null ? phone.length : void 0) !== 10 || isNaN(phone)) {
          A2Cribs.UIManager.CloseLogs();
          A2Cribs.UIManager.Error("Please enter a valid phone number");
          return;
        }
        $.ajax({
          url: myBaseUrl + "Users/SendPhoneVerificationCode",
          type: 'POST',
          data: {
            phone: phone
          }
        });
        return $("#verify_phone").modal('show');
      });
      $("#confirm_validation_code").click(function() {
        var code;
        code = $("#verification_code").val();
        if ((code != null ? code.length : void 0) !== 5) {
          A2Cribs.UIManager.CloseLogs();
          A2Cribs.UIManager.Error("Invalid Code!");
          return false;
        }
        return $.ajax({
          url: myBaseUrl + "Users/ConfirmPhoneVerificationCode",
          type: 'POST',
          data: {
            code: code
          },
          success: function(response) {
            response = JSON.parse(response);
            if (response.error != null) {
              return A2Cribs.UIManager.Error(response.error);
            } else {
              A2Cribs.UIManager.Success("Phone Number Verified!");
              $("#verify_phone").modal('hide');
              $("#phone_verified").val($("#verify_phone_number").val());
              return $("#verify_phone_btn").addClass("verified").text("Verified").prop("disabled", true);
            }
          },
          error: function() {
            A2Cribs.UIManager.CloseLogs();
            return A2Cribs.UIManager.Error("Invalid Code!");
          }
        });
      });
      return $("#complete_tour_request").click(function() {
        var phone;
        phone = $("#verify_phone_number").val();
        if ((phone != null ? phone.length : void 0) !== 10 || isNaN(phone)) {
          A2Cribs.UIManager.CloseLogs();
          A2Cribs.UIManager.Error("Please enter a valid phone number");
          return;
        }
        if ((phone != null ? phone.length : void 0) === 0 || phone !== $("#phone_verified").val()) {
          A2Cribs.UIManager.CloseLogs();
          A2Cribs.UIManager.Error("Please click the verify button to send verification text");
          return;
        }
        $("#complete_tour_request").button('loading');
        return _this.RequestTourTimes($("#listing-data").data("listing-id"), $("#tour_notes").val(), _this.GetHousematesList()).done(function(response) {
          if (response.error != null) {
            A2Cribs.UIManager.Error(response.error);
            return;
          }
          $(".schedule_page").hide();
          $("#schedule_completed").show();
          return A2Cribs.UIManager.Success("Your tour times have been received");
        }).fail(function() {
          A2Cribs.UIManager.CloseLogs();
          return A2Cribs.UIManager.Error("Failed to request tour times. Sorry. Please contact help@cribspot.com if this continues to be an issue");
        }).always(function() {
          return $("#complete_tour_request").button('reset');
        });
      });
    };

    /*
    	Get Housemates List
    	Fetches all the validated housemates email and names
    */

    Tour.GetHousematesList = function() {
      var housemates_list;
      housemates_list = [];
      $(".completed_roommate").each(function(index, element) {
        return housemates_list.push({
          name: $(element).find(".roommate_name").val(),
          email: $(element).find(".roommate_email").val()
        });
      });
      return housemates_list;
    };

    /*
    	Add Time Slot
    	Adds timeslot to selected timeslot map
    */

    Tour.AddTimeSlot = function(offset_date, time_slot) {
      var hash, today;
      hash = "" + offset_date + "-" + time_slot;
      today = new Date();
      return this.selected_timeslots[hash] = {
        date: new Date(today.getFullYear(), today.getMonth(), today.getDate() + offset_date, time_slot)
      };
    };

    /*
    	Delete Time Slot
    	Removes the timeslot from the selected timeslot
    	map
    */

    Tour.DeleteTimeSlot = function(offset_date, time_slot) {
      var hash;
      hash = "" + offset_date + "-" + time_slot;
      if (this.selected_timeslots[hash] != null) {
        return delete this.selected_timeslots[hash];
      }
    };

    /*
    	Time Slot Count
    	Returns the number of timeslots currently
    	selected
    */

    Tour.TimeSlotCount = function() {
      var count, key;
      count = 0;
      for (key in this.selected_timeslots) {
        count++;
      }
      return count;
    };

    /*
    	Request Tour Times
    	Sends a list of the date objects to
    	Tours/RequestTourTimes
    */

    Tour.RequestTourTimes = function(listing_id, note, housemates) {
      var key, time, times, _ref;
      if (note == null) note = "";
      times = [];
      _ref = this.selected_timeslots;
      for (key in _ref) {
        time = _ref[key];
        if (time.date != null) {
          times.push({
            date: time.date.toJSON()
          });
        }
      }
      return $.ajax({
        url: myBaseUrl + 'Tours/RequestTourTimes',
        type: 'POST',
        data: {
          times: times,
          listing_id: listing_id,
          notes: note,
          housemates: housemates
        }
      });
    };

    /*
    	Set Calendar
    	Takes an offset_date and fills in calendar UI
    	with saved timeslots in selected timeslots
    */

    Tour.SetCalendar = function(offset_date) {
      var i, timeslot, _i, _len, _results;
      this.ClearCalendar();
      _results = [];
      for (_i = 0, _len = timeslots.length; _i < _len; _i++) {
        timeslot = timeslots[_i];
        _results.push((function() {
          var _ref, _results2;
          _results2 = [];
          for (i = offset_date, _ref = offset_date + this.DATE_RANGE_SIZE - 1; offset_date <= _ref ? i <= _ref : i >= _ref; offset_date <= _ref ? i++ : i--) {
            if (this.selected_timeslots["" + i + "-" + timeslot] != null) {
              console.log("Timeslot : " + timeslot + " " + i);
              _results2.push($("#ts_" + (i - offset_date) + timeslot).addClass("selected").find(".time_slot_filler").show().find("i").removeClass("icon-plus-sign").addClass("icon-ok-sign"));
            } else {
              _results2.push(void 0);
            }
          }
          return _results2;
        }).call(this));
      }
      return _results;
    };

    /*
    	Clear Calendar
    	Clears all selected div but does not
    	remove the timeslots from the selected timeslots
    	map (purely UI)
    */

    Tour.ClearCalendar = function() {
      $(".time_slot").removeClass("selected");
      return $(".time_slot_filler").hide().find("i").removeClass("icon-remove-sign").removeClass("icon-ok-sign").addClass("icon-plus-sign");
    };

    /*
    	Set Dates
    	Load the set of three days
    */

    Tour.SetDates = function(offset_date) {
      var calendar_table_dates_html, date_range_array, date_range_string, i, _ref;
      if (offset_date == null) offset_date = 0;
      date_range_array = [];
      calendar_table_dates_html = "<td></td>";
      for (i = 0, _ref = this.DATE_RANGE_SIZE - 1; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i--) {
        date_range_array.push(date(i + offset_date));
        calendar_table_dates_html += "<th>" + date_range_array[i].day + ", " + date_range_array[i].month + " " + date_range_array[i].date + "</th>";
      }
      $("#calendar_table_dates").empty().html(calendar_table_dates_html);
      date_range_string = "" + (date_range_array[0].month.substring(0, 3)) + " " + date_range_array[0].date + "-";
      date_range_string += "" + (date_range_array[2].month.substring(0, 3)) + " " + date_range_array[2].date;
      $(".date_range").html(date_range_string);
      return this.SetCalendar(offset_date);
    };

    return Tour;

  }).call(this);

  A2Cribs.Order.FeaturedListing = (function() {

    function FeaturedListing(Widget, listing_id, address, UniData, initialState) {
      this.Widget = Widget;
      this.listing_id = listing_id;
      this.address = address;
      this.UniData = UniData;
      if (initialState == null) initialState = null;
      this.Weekdays = 0;
      this.Weekends = 0;
      this.Price = 0;
      this.WD_price = 0;
      this.WE_price = 0;
      this.MIN_DAY_OFFSET = 3;
      this.initMultiDatesPicker(initialState);
      this.initTemplates();
      this.PrevSelectedDate = null;
      this.RangeSelectEnabled = true;
      this.Widget.find('.address').html(this.address);
      this.setupHandlers();
      this.setupUniPriceTable(initialState);
      this.refresh();
    }

    FeaturedListing.prototype.getPrice = function() {
      return this.Price;
    };

    FeaturedListing.prototype.setupHandlers = function() {
      var _this = this;
      this.Widget.on('click', '.rst input', function(event) {
        _this.RangeSelectEnabled = !_this.RangeSelectEnabled;
        return _this.PrevSelectedDate = null;
      }).on('click', '.rst .clear-selected-dates', function(event) {
        return _this.clear();
      });
      return this.Widget.on('click', 'input.uni-toggle', function(event) {
        var index;
        index = $(event.currentTarget).parents().eq(1).index();
        _this.UniData[index].enabled = $(event.currentTarget).prop('checked');
        return _this.refresh();
      });
    };

    FeaturedListing.prototype.setupUniPriceTable = function(intialState) {
      var rows, uniPrice, _i, _len, _ref, _ref2;
      rows = "";
      _ref = this.UniData;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        uniPrice = _ref[_i];
        if ((typeof initialState !== "undefined" && initialState !== null ? (_ref2 = initialState.universities) != null ? _ref2[uniPrice.university_id] : void 0 : void 0) != null) {
          uniPrice.enabled = initialState.universities[uniPrice.university_id];
        } else {
          uniPrice.enabled = true;
        }
        rows += this.UniPriceRow(uniPrice);
      }
      return this.Widget.find('.uniPriceTable>tbody').html(rows);
    };

    FeaturedListing.GenerateOrderItem = function(orderState, uni_data) {
      var dates;
      dates = _.without.apply(_, [orderState.selectedDates].concat(uni_data.unavailable_dates));
      return {
        listing_id: orderState.listing_id,
        university_id: uni_data.university_id,
        dates: dates
      };
    };

    FeaturedListing.prototype.getState = function() {
      var uni, unis, _i, _len, _ref;
      unis = {};
      _ref = this.UniData;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        uni = _ref[_i];
        unis[uni.university_id] = uni.enabled;
      }
      return {
        selectedDates: this.getDates('string'),
        universities: unis,
        listing_id: this.listing_id
      };
    };

    FeaturedListing.prototype.clear = function() {
      this.datepicker.multiDatesPicker('resetDates', 'picked');
      return this.refresh();
    };

    FeaturedListing.prototype.reset = function(refresh_after) {
      if (refresh_after == null) refresh_after = true;
      this.datepicker.multiDatesPicker('resetDates', 'picked');
      this.datepicker.multiDatesPicker('resetDates', 'disabled');
      this.Widget.off('click', '.rst input');
      this.Widget.off('click', '.rst .clear-selected-dates');
      return this.Widget.off('click', 'input.uni-toggle', refresh_after ? this.refresh() : void 0);
    };

    FeaturedListing.prototype.getDates = function(type) {
      if (type == null) type = 'object';
      return this.datepicker.multiDatesPicker('getDates', type);
    };

    FeaturedListing.prototype.updatePrice = function() {
      return this.Price = this.Weekdays * this.WD_price + this.Weekends * this.WE_price;
    };

    FeaturedListing.prototype.updateRates = function() {
      var uni, _i, _len, _ref;
      this.WE_price = 0;
      this.WD_price = 0;
      _ref = this.UniData;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        uni = _ref[_i];
        if (uni.enabled) {
          this.WD_price += uni.weekday_price;
          this.WE_price += uni.weekend_price;
        }
      }
      this.Widget.find('#wd_rate').html(this.WD_price.toFixed(2));
      return this.Widget.find('#we_rate').html(this.WE_price.toFixed(2));
    };

    FeaturedListing.prototype.updateDayCounts = function() {
      var d, day, _i, _len, _ref;
      this.Weekends = 0;
      this.Weekdays = 0;
      _ref = this.getDates();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        d = _ref[_i];
        day = d.getDay();
        if (day === 0 || day === 6) {
          this.Weekends++;
        } else {
          this.Weekdays++;
        }
      }
      return [this.Weekdays, this.Weekends];
    };

    FeaturedListing.prototype.initMultiDatesPicker = function(initialState) {
      var pickeroptions, today,
        _this = this;
      today = new Date();
      pickeroptions = {
        dateFormat: "yy-mm-dd",
        minDate: new Date(today.setDate(today.getDate() + this.MIN_DAY_OFFSET)),
        onSelect: function(dateText, inst) {
          if (_this.RangeSelectEnabled) _this.rangeSelect(dateText);
          return _this.refresh();
        }
      };
      if (initialState != null) {
        pickeroptions.addDates = initialState.selectedDates;
      }
      this.datepicker = $(this.Widget).find('.mdp').first().multiDatesPicker(pickeroptions);
      return this.datepicker.click();
    };

    FeaturedListing.prototype.rangeSelect = function(dateText) {
      var date, i, selectedDate, _date, _ref, _ref2;
      if (this.PrevSelectedDate != null) {
        _date = new Date(dateText);
        selectedDate = new Date(_date.getUTCFullYear(), _date.getUTCMonth(), _date.getUTCDate());
        if (this.PrevSelectedDate > selectedDate) {
          _ref = [selectedDate, this.PrevSelectedDate], this.PrevSelectedDate = _ref[0], selectedDate = _ref[1];
        }
        this.SelectedDateRange = A2Cribs.UtilityFunctions.getDateRange(this.PrevSelectedDate, selectedDate);
        for (i = _ref2 = this.SelectedDateRange.length - 1; i >= 0; i += -1) {
          date = this.SelectedDateRange[i];
          if (this.datepicker.multiDatesPicker('gotDate', date, 'disabled') !== false) {
            this.SelectedDateRange.splice(i, 1);
          }
        }
        this.PrevSelectedDate = null;
        return this.datepicker.multiDatesPicker('addDates', this.SelectedDateRange);
      } else {
        if (this.SelectedDateRange != null) {
          this.datepicker.multiDatesPicker('removeDates', this.SelectedDateRange);
        }
        this.SelectedDateRange = null;
        _date = new Date(dateText);
        this.PrevSelectedDate = new Date(_date.getUTCFullYear(), _date.getUTCMonth(), _date.getUTCDate());
        return this.datepicker.multiDatesPicker('addDates', [this.PrevSelectedDate]);
      }
    };

    FeaturedListing.prototype.initTemplates = function() {
      var dateConflictNoticeHTML, uniPriceRowHTML;
      uniPriceRowHTML = "<tr data-university_id='<%= university_id %>' >\n    <td><%=name%></td>\n    <td class = 'rates'>$<%=weekday_price.toFixed(2)%></td>\n    <td class = 'rates'>$<%=weekend_price.toFixed(2)%></td>\n    <td><input class = 'uni-toggle' type='checkbox' <% if(enabled){print('checked');} %> />\n</tr>";
      this.UniPriceRow = _.template(uniPriceRowHTML);
      dateConflictNoticeHTML = "<li><i class = 'icon-warning-sign'></i> Listing already featured at <%=name%> on <%\n    $.each(dates, function(index, date){\n        d = new Date(date)\n        if(index != dates.length-1)\n            print(d.getMonth()+1 + \"-\" + d.getDate() +\"-\"+ d.getFullYear() + \", \");\n        else\n            print(d.getMonth()+1 + \"-\" + d.getDate()+\"-\"+ d.getFullYear());\n    });\n    %></li>";
      return this.DateConflictNotice = _.template(dateConflictNoticeHTML);
    };

    FeaturedListing.prototype.checkForDateConflicts = function() {
      var conflictNotices, d, dates, day, priceDif, selected_dates, unavailDate, uni, _i, _j, _len, _len2, _ref, _ref2;
      selected_dates = this.getDates('string');
      conflictNotices = "";
      priceDif = 0;
      _ref = this.UniData;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        uni = _ref[_i];
        if (!uni.enabled) continue;
        dates = [];
        _ref2 = uni.unavailable_dates;
        for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
          unavailDate = _ref2[_j];
          if ($.inArray(unavailDate, selected_dates) !== -1) {
            dates.push(unavailDate);
            d = new Date(unavailDate);
            day = d.getDay();
            if (!(day != null)) continue;
            day = (day + 1) % 7;
            if (day === 0 || day === 6) {
              priceDif += uni.weekend_price;
            } else {
              priceDif += uni.weekday_price;
            }
          }
        }
        if (dates.length > 0) {
          conflictNotices += this.DateConflictNotice({
            name: uni.name,
            dates: dates
          });
        }
      }
      this.Widget.find('.DateConflicts').html(conflictNotices);
      return priceDif;
    };

    FeaturedListing.prototype.refresh = function() {
      var priceDiffDueToConflicts;
      this.updateDayCounts();
      this.updateRates();
      this.updatePrice();
      priceDiffDueToConflicts = this.checkForDateConflicts();
      this.Price -= priceDiffDueToConflicts;
      $(this.Widget).find('.price').html(" $" + (this.Price.toFixed(2)));
      $(this.Widget).find('.weekdays').html(this.Weekdays);
      $(this.Widget).find('.weekends').html(this.Weekends);
      return this.Widget.trigger('orderItemChanged', this);
    };

    return FeaturedListing;

  })();

  A2Cribs.Sublet = (function(_super) {

    __extends(Sublet, _super);

    function Sublet(rental) {
      var date, dates, index, _i, _len;
      Sublet.__super__.constructor.call(this, "sublet", rental);
      dates = ["start_date", "end_date"];
      for (_i = 0, _len = dates.length; _i < _len; _i++) {
        date = dates[_i];
        if (this[date]) {
          if ((index = this[date].indexOf(" ")) !== -1) {
            this[date] = this[date].substring(0, index);
          }
        }
      }
    }

    Sublet.prototype.GetId = function(id) {
      return parseInt(this["listing_id"], 10);
    };

    Sublet.prototype.IsComplete = function() {
      if (this.sublet_id != null) {
        return true;
      } else {
        return false;
      }
    };

    return Sublet;

  })(A2Cribs.Object);

  SubletSave = (function() {
    var _this = this;

    function SubletSave() {}

    /*
    	Setup UI
    	Creates the listeners and all the UI for the
    	Sublet window
    */

    SubletSave.SetupUI = function(div) {
      var _this = this;
      this.div = div;
      this.MiniMap = new A2Cribs.MiniMap(this.div.find(".mini_map"));
      $(".sublet-content").on("shown", function() {
        return _this.MiniMap.Resize();
      });
      $('#sublet_list_content').on('click', '.sublet_list_item', function(event) {
        return _this.Open(event.currentTarget.id);
      });
      this.div.find("#sublet_save_button").click(function() {
        _this.div.find("#sublet_save_button").button('loading');
        return _this.Save().always(function() {
          return _this.div.find("#sublet_save_button").button('reset');
        });
      });
      this.div.find(".btn-group.sublet_fields .btn").click(function(event) {
        return $(event.currentTarget).parent().val($(event.currentTarget).val());
      });
      this.div.find("#find_address").click(function() {
        return _this.FindAddress();
      });
      this.div.find('.date-field').datepicker();
      $(".create-listing").find("a").click(function(event) {
        var listing_type;
        listing_type = $(event.currentTarget).data("listing-type");
        if (listing_type === "sublet") {
          $(document).trigger("track_event", ["Post Sublet", "Create"]);
          return _this.Open();
        }
      });
      $("#sublet_list_content").on("marker_updated", function(event, marker_id) {
        return _this.PopulateMarker(A2Cribs.UserCache.Get("marker", marker_id));
      });
      this.SetupShareButtons();
      this.div.find(".photo_adder").click(function() {
        var image_array, listing_id, _ref;
        listing_id = _this.div.find(".listing_id").val();
        if ((listing_id != null ? listing_id.length : void 0) !== 0) {
          image_array = (_ref = A2Cribs.UserCache.Get("image", listing_id)) != null ? _ref.GetObject() : void 0;
        } else {
          image_array = _this._temp_images;
        }
        return A2Cribs.PhotoPicker.Open(image_array).done(_this.PhotoAddedCallback);
      });
      return this.div.find(".rent").keyup(function(event) {
        return $(event.currentTarget).parent().removeClass("error");
      });
    };

    /*
    	Setup Share Buttons
    	Links share manager functions to the share buttons
    	when a sublet is posted
    */

    SubletSave.SetupShareButtons = function() {
      var listing_id,
        _this = this;
      listing_id = this.div.find(".listing_id").val();
      this.div.find('.fb_sublet_share').click(function() {
        var images, marker, sublet;
        sublet = A2Cribs.UserCache.Get("sublet", listing_id);
        images = A2Cribs.UserCache.Get("image", listing_id);
        marker = A2Cribs.UserCache.Get("marker", _this.div.find(".marker_id").val());
        return A2Cribs.ShareManager.ShareSubletOnFB(marker, sublet, images);
      });
      this.div.find('.google_sublet_share').click(function() {
        var images, marker, sublet;
        sublet = A2Cribs.UserCache.Get("sublet", listing_id);
        images = A2Cribs.UserCache.Get("image", listing_id);
        marker = A2Cribs.UserCache.Get("marker", _this.div.find(".marker_id").val());
        return A2Cribs.ShareManager.ShareSubletOnFB(marker, sublet, images);
      });
      this.div.find('.twitter_sublet_share').click(function() {
        return A2Cribs.ShareManager.ShareSubletOnTwitter(listing_id);
      });
      return this.div.find('.sublet_link').attr("href", "/listing/" + listing_id);
    };

    /*
    	Photo Added
    	When photos have been added, decides whether to cache if sublet
    	has been saved and save in temp_images
    */

    SubletSave.PhotoAddedCallback = function(photos) {
      var image, listing_id, _i, _len;
      listing_id = SubletSave.div.find(".listing_id").val();
      if ((listing_id != null ? listing_id.length : void 0) !== 0) {
        for (_i = 0, _len = photos.length; _i < _len; _i++) {
          image = photos[_i];
          image.listing_id = listing_id;
        }
        A2Cribs.UserCache.Set(new A2Cribs.Image(photos, listing_id));
        SubletSave._temp_images = photos;
        return SubletSave.Save();
      } else {
        return SubletSave._temp_images = photos;
      }
    };

    /*
    	Validate
    	Called before advancing steps
    	Returns true if validations pass; false otherwise
    */

    SubletSave.Validate = function() {
      var isValid, rent;
      isValid = true;
      rent = this.div.find(".rent").val();
      if (rent != null ? rent.length : void 0) {
        rent = rent.replace(/[$,]/g, "");
        if (isNaN(rent)) {
          isValid = false;
          A2Cribs.UIManager.Error("Please only provide numbers for your rent.");
          this.div.find(".rent").focus().parent().addClass("error");
        } else {
          this.div.find(".rent").val(rent);
        }
      }
      this.div.find(".btn-group").each(function(index, value) {
        if (isValid && $(value).find(".active").size() === 0) {
          isValid = false;
          return A2Cribs.UIManager.Error($(value).data("error-message"));
        }
      });
      this.div.find(".text-field").each(function(index, value) {
        if (isValid && $(value).val().length === 0) {
          isValid = false;
          return A2Cribs.UIManager.Error($(value).data("error-message"));
        }
      });
      return isValid;
    };

    /*
    	Reset
    	Erases all the fields and resets
    	the Sublet window and sublet object
    */

    SubletSave.Reset = function() {
      this.div.find(".btn-group").each(function(index, value) {
        return $(value).find(".active").removeClass("active");
      });
      this.div.find("input").val("");
      return this.div.find("textarea").val("");
    };

    /*
    	Open
    	Opens up an existing sublet from a marker_id if marker_id
    	is defined. Otherwise will start a new sublet
    */

    SubletSave.Open = function(marker_id) {
      var listings,
        _this = this;
      if (marker_id == null) marker_id = null;
      this.div.find(".done_section").fadeOut('fast', function() {
        return _this.div.find(".sublet_section").fadeIn();
      });
      if (marker_id != null) {
        listings = A2Cribs.UserCache.GetAllAssociatedObjects("listing", "marker", marker_id);
        A2Cribs.UserCache.GetListing("sublet", listings[0].listing_id).done(function(sublet) {
          _this.Reset();
          _this.div.find(".listing_id").val(listings[0].listing_id);
          _this.PopulateMarker(A2Cribs.UserCache.Get("marker", marker_id));
          return _this.Populate(sublet);
        });
      } else {
        this.Reset();
        this.MiniMap.Reset();
        this.div.find(".more_info").slideUp();
        this.div.find(".marker_card").fadeOut('fast', function() {
          return _this.div.find(".marker_searchbox").fadeIn();
        });
      }
      return A2Cribs.Dashboard.Direct({
        "classname": "sublet",
        "data": {}
      });
    };

    /*
    	Populate Marker
    	Populates the fields based on the marker
    */

    SubletSave.PopulateMarker = function(marker) {
      var _this = this;
      $(".location_fields").each(function(index, value) {
        var input_val;
        input_val = marker[$(value).data("field-name")];
        if (typeof marker[$(value).data("field-name")] === "boolean") {
          input_val = +input_val;
        }
        return $(value).val(input_val);
      });
      this.div.find(".marker_id").val(marker.GetId());
      this.MiniMap.SetMarkerPosition(new google.maps.LatLng(marker.latitude, marker.longitude));
      this.div.find(".building_name").text(marker.GetName());
      this.div.find(".building_type").text(marker.GetBuildingType());
      this.div.find(".full_address").html("<i class='icon-map-marker'></i> " + marker.street_address + ", " + marker.city + ", " + marker.state);
      return this.div.find(".marker_searchbox").fadeOut('fast', function() {
        _this.div.find(".marker_card").fadeIn();
        return _this.div.find(".more_info").slideDown();
      });
    };

    /*
    	Populate
    	Populates the sublet fields in the dom
    */

    SubletSave.Populate = function(sublet_object) {
      var _this = this;
      return $(".sublet_fields").each(function(index, value) {
        var input_val;
        input_val = sublet_object[$(value).data("field-name")];
        if (typeof sublet_object[$(value).data("field-name")] === "boolean") {
          input_val = +input_val;
        }
        $(value).val(input_val);
        if ($(value).hasClass("btn-group")) {
          return $(value).find("button[value='" + input_val + "']").addClass("active");
        } else if ($(value).hasClass("date-field")) {
          return $(value).val(_this.GetFormattedDate(sublet_object[$(value).data("field-name")]));
        }
      });
    };

    /*
    	Save
    	Submits sublet to backend to save
    	Assumes all front-end validations have been passed.
    */

    SubletSave.Save = function() {
      var sublet_object,
        _this = this;
      if (this.Validate()) {
        sublet_object = this.GetSubletObject();
        $(document).trigger("track_event", ["Post Sublet", "Save"]);
        return $.ajax({
          url: myBaseUrl + "listings/Save/",
          type: "POST",
          data: sublet_object,
          success: function(response) {
            var _ref;
            response = JSON.parse(response);
            if (((_ref = response.error) != null ? _ref.message : void 0) != null) {
              return A2Cribs.UIManager.Error(response.error.message);
            } else {
              if (!(sublet_object.Listing.listing_id != null)) {
                $('#sublet_list_content').trigger("marker_added", [sublet_object.Listing.marker_id]);
              }
              _this.div.find(".sublet_section").fadeOut('slow', function() {
                return _this.div.find(".done_section").fadeIn();
              });
              $(document).trigger("track_event", ["Post Sublet", "Save Completed", "", response.listing.Listing.listing_id]);
              A2Cribs.UserCache.CacheData(response.listing);
              _this.div.find(".listing_id").val(response.listing.Listing.listing_id);
              _this.SetupShareButtons();
              return A2Cribs.UIManager.Success("Your listing has been saved!");
            }
          }
        });
      } else {
        return new $.Deferred().reject();
      }
    };

    /*
    	GetSubletObject
    	Returns an object containing all sublet data from all 4 steps.
    */

    SubletSave.GetSubletObject = function() {
      var listing_id, sublet_object,
        _this = this;
      sublet_object = {};
      this.div.find(".sublet_fields").each(function(index, value) {
        var field_value;
        field_value = $(value).val();
        if ($(value).hasClass("date-field")) {
          field_value = _this.GetBackendDateFormat(field_value);
        }
        return sublet_object[$(value).data("field-name")] = field_value;
      });
      listing_id = this.div.find(".listing_id").val().length !== 0 ? this.div.find(".listing_id").val() : void 0;
      sublet_object.listing_id = listing_id;
      return {
        'Listing': {
          listing_type: 1,
          marker_id: this.div.find(".marker_id").val(),
          listing_id: listing_id
        },
        'Sublet': sublet_object,
        'Image': this._temp_images
      };
    };

    /*
    	Find Address
    	Finds the geocode address and searches the backend
    	for the correct address
    */

    SubletSave.FindAddress = function() {
      var isValid, location_object,
        _this = this;
      location_object = {};
      isValid = true;
      $(".location_fields").each(function(index, value) {
        if ($(value).val().length === 0) isValid = false;
        return location_object[$(value).data("field-name")] = $(value).val();
      });
      if (!isValid) {
        A2Cribs.UIManager.Error("Please complete all fields to find address");
        return;
      }
      return A2Cribs.Geocoder.FindAddress(location_object.street_address, location_object.city, location_object.state).done(function(response) {
        var city, location, state, street_address, zip;
        street_address = response[0], city = response[1], state = response[2], zip = response[3], location = response[4];
        return _this.FindMarkerByAddress(street_address, city, state).done(function(marker) {
          return _this.PopulateMarker(marker);
        }).fail(function() {
          return A2Cribs.MarkerModal.OpenLocation('sublet', street_address, city, state);
        });
      }).fail(function() {
        return A2Cribs.MarkerModal.OpenLocation('sublet', location_object.street_address, location_object.city, location_object.state);
      });
    };

    SubletSave.FindMarkerByAddress = function(street_address, city, state) {
      var deferred,
        _this = this;
      deferred = new $.Deferred();
      $.ajax({
        url: myBaseUrl + "Markers/FindMarkerByAddress/" + street_address + "/" + city + "/" + state,
        type: "GET",
        success: function(response) {
          var marker;
          response = JSON.parse(response);
          if (response != null) {
            marker = new A2Cribs.Marker(response);
            A2Cribs.UserCache.Set(marker);
            return deferred.resolve(marker);
          } else {
            return deferred.reject();
          }
        }
      });
      return deferred.promise();
    };

    /*
    	Get Backend Date Format
    	Replaces '/' with '-' to make convertible to db format
    */

    SubletSave.GetBackendDateFormat = function(dateString) {
      var beginDateFormatted, date, day, month, year;
      date = new Date(dateString);
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = year + "-" + month + "-" + day;
    };

    /*
    	Get Formatted Date
    	Returns date in readable front-end syntax
    */

    SubletSave.GetFormattedDate = function(dateString) {
      var date_array;
      date_array = dateString.split(" ");
      date_array = date_array[0].split("-");
      return "" + date_array[1] + "/" + date_array[2] + "/" + date_array[0];
    };

    $(document).ready(function() {
      if ($("#sublet_window").length) {
        SubletSave._temp_images = [];
        return SubletSave.SetupUI($("#sublet_window"));
      }
    });

    return SubletSave;

  }).call(this);

  A2Cribs.Geocoder = (function() {

    function Geocoder() {}

    Geocoder.FindAddress = function(street_address, city, state) {
      var deferred,
        _this = this;
      deferred = new $.Deferred();
      if (!(this._geocoder != null)) this._geocoder = new google.maps.Geocoder();
      this._geocoder.geocode({
        address: "" + street_address + " " + city + ", " + state
      }, function(response, status) {
        var component, location, street_name, street_number, type, zip, _i, _j, _len, _len2, _ref, _ref2;
        if (status === google.maps.GeocoderStatus.OK && response[0].address_components.length >= 2) {
          _ref = response[0].address_components;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            component = _ref[_i];
            _ref2 = component.types;
            for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
              type = _ref2[_j];
              switch (type) {
                case "street_number":
                  street_number = component.short_name;
                  break;
                case "route":
                  street_name = component.short_name;
                  break;
                case "locality":
                  city = component.short_name;
                  break;
                case "administrative_area_level_1":
                  state = component.short_name;
                  break;
                case "postal_code":
                  zip = component.short_name;
              }
            }
          }
          location = response[0].geometry.location;
          if (!(street_number != null)) return deferred.reject();
          return deferred.resolve(["" + street_number + " " + street_name, city, state, zip, location]);
        } else {
          return deferred.reject();
        }
      });
      return deferred.promise();
    };

    return Geocoder;

  })();

  (function($) {
    $.fn.removeStyle = function(style) {
      var search;
      search = new RegExp(style + '[^;]+;?', 'g');
      return this.each(function() {
        return $(this).attr('style', function(i, style) {
          if (style) return style.replace(search, '');
        });
      });
    };
    return $.fn.animateHighlight = function(highlightColor, duration) {
      var animateMs, highlightBg, originalBg, originalColor;
      highlightBg = highlightColor || "#FFFFFF";
      animateMs = duration || 500;
      originalBg = this.css("backgroundColor");
      originalColor = this.css('color');
      return this.stop().css("background-color", highlightBg).css('color', highlightBg).animate({
        backgroundColor: originalBg,
        color: originalColor
      }, animateMs);
    };
  })(jQuery);

  A2Cribs.Hotlist = (function() {

    Hotlist.Initialize = function() {
      var el;
      el = $('#hotlist');
      A2Cribs.HotlistObj = new A2Cribs.Hotlist(el);
      return A2Cribs.HotlistObj.setup();
    };

    Hotlist.prototype.call = function(action, method, data) {
      var deferred, url,
        _this = this;
      deferred = new $.Deferred();
      url = myBaseUrl + action;
      $.ajax({
        url: url,
        data: data,
        type: method,
        success: function(response) {
          try {
            return deferred.resolve(JSON.parse(response));
          } catch (error) {
            return deferred.reject(response);
          }
        },
        error: function(response) {
          return deferred.reject(response);
        }
      });
      return deferred.promise();
    };

    function Hotlist(DOMRoot) {
      this.DOMRoot = DOMRoot;
      this.topSection = _.template(A2Cribs.Hotlist.topSectionTemplate);
      this.friendsList = _.template(A2Cribs.Hotlist.friendsListTemplate);
      this.notLoggedIn = _.template(A2Cribs.Hotlist.notLoggedInTemplate);
      this.friendsListPopup = _.template(A2Cribs.Hotlist.friendsListPopupTemplate);
      this.expandButton = _.template(A2Cribs.Hotlist.expandButtonTemplate);
      this.sources = [
        {
          name: 'accounts',
          remote: {
            url: myBaseUrl + 'users/getbyname?name=%QUERY',
            filter: function(response) {
              return response.map(function(item) {
                return {
                  value: item.User.email,
                  name: "" + item.User.first_name + " " + item.User.last_name
                };
              });
            }
          }
        }
      ];
      this.setEditing(false);
      this.isExpanded = false;
    }

    Hotlist.prototype.handleFBLoad = function() {
      var _this = this;
      this.sources.push({
        name: 'facebook-friends',
        prefetch: {
          url: "https://graph.facebook.com/me/friends?access_token=" + (FB.getAccessToken()) + "&fields=id,name,picture,first_name,last_name",
          ttl: 0,
          filter: function(response) {
            return response.data.map(function(item) {
              return {
                value: item.name,
                tokens: item.name.split(' '),
                facebook_id: item.id,
                picture: item.picture.data.url,
                first_name: item.first_name,
                last_name: item.last_name
              };
            });
          }
        }
      });
      return this.DOMRoot.find('#add-field').typeahead(this.sources).on('typeahead:selected', function(e, d, ds) {
        return _this.setAddIdField(e, d, ds);
      }).on('typeahead:autocompleted', function(e, d, ds) {
        return _this.setAddIdField(e, d, ds);
      }).on('typeahead:hinted', function(e, d, ds) {
        return _this.setAddIdField(e, d, ds);
      }).bind('change cut paste keyup', function() {
        if ($(this).val() === '') return $(this).removeData('friend');
      });
    };

    Hotlist.prototype.setAddIdField = function(event, datum, dataset) {
      var name, val;
      name = datum.value.replace(/^\s+|\s+$/g, "").toLowerCase();
      val = $('#add-field').val().replace(/^\s+|\s+$/g, "").toLowerCase();
      if (name === val) {
        return this.DOMRoot.find('#add-field').data('friend', datum);
      } else {
        return this.DOMRoot.find('#add-field').removeData('friend');
      }
    };

    Hotlist.prototype.setup = function() {
      var _this = this;
      return $(document).on("checked_logged_in logged_in", function(event) {
        var logged_in, _ref;
        logged_in = (_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0;
        _this.renderTopSection(logged_in);
        _this.show();
        _this.renderBottomSection();
        _this.currentHotlist = _this.get();
        return _this.setHeight(true);
      });
    };

    Hotlist.prototype.setupDroppables = function() {
      this.DOMRoot.find('li.friend').droppable({
        accept: '.fl-sb-item, .large-bubble',
        hoverClass: 'drop-hover',
        tolerance: 'pointer',
        drop: function(event, ui) {
          var listing_id;
          listing_id = ui.draggable.attr('listing_id') || ui.draggable.data('listing_id');
          if ($(this).data('facebook_id')) {
            A2Cribs.HotlistObj.shareToFB(listing_id, $(this).data('facebook_id'));
          } else {
            A2Cribs.HotlistObj.shareToEmail(listing_id, $(this).data('email'));
          }
          $(this).find('.friend-abbr').animateHighlight();
          return ui.helper.hide();
        }
      });
      return this.DOMRoot.find('ul.friends.no-friends').droppable({
        accept: '.fl-sb-item, .large-bubble',
        hoverClass: 'drop-hover',
        tolerance: 'pointer',
        drop: function(event, ui) {
          var listing_id;
          listing_id = ui.draggable.attr('listing_id') || ui.draggable.data('listing_id');
          ui.helper.hide();
          return FB.ui({
            method: 'send',
            link: "http://www.cribspot.com/listing/" + listing_id,
            name: "Share this listing"
          }, function(response) {});
        }
      });
    };

    Hotlist.prototype.destroyDroppables = function() {
      this.DOMRoot.find('li.friend').droppable("destroy");
      return this.DOMRoot.find('ul.friends.no-friends').droppable("destroy");
    };

    Hotlist.prototype.renderTopSection = function(logged_in) {
      var _this = this;
      this.DOMRoot.find('#top-section').html(this.topSection({
        loggedIn: logged_in
      }));
      this.DOMRoot.find('#title').show();
      this.DOMRoot.find('#add-field').hide();
      this.DOMRoot.find('#btn-add').hide();
      $.when(window.fbInit).then(function() {
        return FB.getLoginStatus(function(response) {
          if (response.status === 'connected') return _this.handleFBLoad();
        });
      });
      this.DOMRoot.find('.twitter-typeahead').hide();
      this.DOMRoot.find('#link-info').popover({
        title: 'What is this?',
        content: "You can share listings with your friends!<br/>Either click the <i class='icon-user'></i> icon on a listing or drag the listing to one of your friends on the hotlist.",
        html: true,
        placement: 'bottom'
      });
      return $("#add-field").keyup(function(event) {
        if (event.keyCode === 13) return $("#btn-add").click();
      });
    };

    Hotlist.prototype.renderFriendsList = function(data) {
      if (A2Cribs.Login.logged_in) {
        this.DOMRoot.find('#friends').html(this.friendsList(data));
        this.DOMRoot.find('#add-field').val("");
        this.DOMRoot.find('.tt-hint').val("");
        this.DOMRoot.find('.btn-hotlist-remove').hide();
        this.DOMRoot.find('.friend-name').hide();
        $(document).on('mousedown mouseup', '.grab, .grabbing', function(event) {
          return $(this).toggleClass('grab').toggleClass('grabbing');
        });
        this.setupDroppables();
        this.DOMRoot.find('li.friend').tooltip({
          animated: 'fade',
          container: 'body'
        });
      } else {
        this.DOMRoot.find("#friends").html(this.notLoggedIn());
      }
      return this.setHeight(true);
    };

    Hotlist.prototype.startedDragging = function() {
      var _ref;
      if ((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) {
        this.retract();
        return this.expand();
      }
    };

    Hotlist.prototype.stoppedDragging = function() {
      var _ref;
      if ((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0) {
        return this.retract();
      }
    };

    Hotlist.prototype.shareToAll = function(event, ui) {
      var fb_ids, listing_id;
      listing_id = ui.draggable.attr('listing_id') || ui.draggable.data('listing_id');
      return fb_ids = $('ul.friends li').map(function(i) {
        var a;
        a = $(this).data('facebook_id');
        return a;
      });
    };

    Hotlist.prototype.renderBottomSection = function() {
      return this.DOMRoot.find('#bottom-section').html(this.expandButton());
    };

    Hotlist.prototype.getHotlistForPopup = function(listing_id) {
      return this.friendsListPopup({
        friends: this.currentHotlist,
        listing_id: listing_id
      });
    };

    Hotlist.prototype.get = function() {
      var _this = this;
      if (A2Cribs.Login.logged_in) {
        return $.when(this.call('friends/hotlist', 'GET', null)).then(function(data) {
          return _this.currentHotlist = data;
        }).fail(function(data) {});
      }
    };

    Hotlist.prototype.show = function() {
      var _this = this;
      if (A2Cribs.Login.logged_in) {
        return $.when(this.call('friends/hotlist', 'GET', null)).then(function(data) {
          return _this.renderFriendsList({
            friends: data
          });
        }).fail(function(data) {});
      } else {
        return this.renderFriendsList(null);
      }
    };

    Hotlist.prototype.add = function(friend) {
      var postdata, route, _ref,
        _this = this;
      if (A2Cribs.Login.logged_in) {
        if (((_ref = $('#add-field').data('friend')) != null ? _ref.facebook_id : void 0) != null) {
          route = 'invitations/invitefbfriend';
          postdata = {
            friend: $('#add-field').data('friend')
          };
          this.showFBAddMessageModal($('#add-field').data('friend').facebook_id);
        } else {
          route = 'invitations/invitefriends';
          postdata = {
            emails: [$('#add-field').val()]
          };
        }
        return $.when(this.call(route, 'POST', postdata).then(function(data) {
          return _this.call('friends/hotlist', 'GET', null);
        })).then(function(data) {
          _this.currentHotlist = data;
          _this.renderFriendsList({
            friends: data
          });
          return _this.expandForEdit();
        }).fail(function(data) {});
      }
    };

    Hotlist.prototype.showFBAddMessageModal = function(friend) {
      return FB.ui({
        method: 'send',
        link: 'http://www.cribspot.com',
        to: friend
      });
    };

    Hotlist.prototype.remove = function(friend) {
      var _this = this;
      if (A2Cribs.Login.logged_in) {
        return $.when(this.call('friends/hotlist/remove', 'POST', {
          friend: friend
        }).then(function(data) {
          _this.renderFriendsList({
            friends: data
          });
          _this.expandForEdit();
          return _this.currentHotlist = data;
        })).fail(function(data) {});
      }
    };

    Hotlist.prototype.share = function(listing, friend) {
      var _this = this;
      if (A2Cribs.Login.logged_in) {
        return $.when(this.call('friends/share', 'POST', {
          friend: friend,
          listing: listing
        }).then(function(data) {
          if (data.success === true) {
            return A2Cribs.UIManager.Success("Successfully Shared Listing");
          } else {
            return A2Cribs.UIManager.Error("There was a problem sharing the listing.");
          }
        })).fail(function(data) {
          return A2Cribs.UIManager.Error("There was a problem sharing the listing.");
        });
      }
    };

    Hotlist.prototype.shareToEmail = function(listing, friend) {
      var _this = this;
      return $.when(this.call('invitations/inviteFriends', 'POST', {
        emails: [friend],
        listing: listing
      }).then(function(data) {
        if (data.success === true) {
          return A2Cribs.UIManager.Success("Successfully Shared Listing");
        } else {
          return A2Cribs.UIManager.Error("There was a problem sharing the listing.");
        }
      })).fail(function(data) {
        return A2Cribs.UIManager.Error("There was a problem sharing the listing.");
      }).always(function(data, status, jqXHR) {
        return $('#share-to-email').val("");
      });
    };

    Hotlist.prototype.shareToFB = function(listing, facebook_id) {
      return FB.ui({
        method: 'send',
        link: "http://www.cribspot.com/listing/" + listing,
        to: facebook_id,
        name: "Share this listing"
      });
    };

    Hotlist.prototype.retract = function() {
      var hides, shows;
      shows = ['.friend-abbr', '#title'];
      hides = ['.btn-hotlist-remove', '.friend-name', '#add-field', '.twitter-typeahead', '.tt-hint', '#btn-add'];
      this.DOMRoot.removeClass('expanded').removeClass('detailed');
      this.DOMRoot.find('#expand-button i').removeClass('icon-caret-up').addClass('icon-caret-down');
      this.DOMRoot.find(shows.join(',')).show();
      this.DOMRoot.find(hides.join(',')).hide();
      this.DOMRoot.find('#btn-edit').removeClass('editing').html('<i class="icon-edit"></i>');
      this.DOMRoot.find('ul.friends').removeStyle('height');
      this.setEditing(false);
      this.isExpanded = false;
      this.setHeight(true);
      this.setupDroppables();
      return this.DOMRoot.find('li.friend').tooltip({
        animated: 'fade',
        container: 'body'
      });
    };

    Hotlist.prototype.expand = function() {
      this.DOMRoot.addClass('expanded');
      this.DOMRoot.find('#expand-button i').removeClass('icon-caret-down').addClass('icon-caret-up');
      this.isExpanded = true;
      return this.setHeight();
    };

    Hotlist.prototype.expandForEdit = function() {
      var hides, shows;
      this.DOMRoot.addClass('expanded');
      this.DOMRoot.find('#expand-button i').removeClass('icon-caret-down').addClass('icon-caret-up');
      this.isExpanded = true;
      this.DOMRoot.addClass('detailed');
      shows = ['.btn-hotlist-remove', '.twitter-typeahead', '.tt-hint', '.friend-name', '#add-field', '#btn-add'];
      hides = ['.friend-abbr', '#title'];
      this.DOMRoot.find(shows.join(',')).show();
      this.DOMRoot.find(hides.join(',')).hide();
      this.DOMRoot.find('#btn-edit').addClass('editing').html('Done');
      this.DOMRoot.find('li.friend').tooltip("destroy");
      this.destroyDroppables();
      return this.setHeight(false, true);
    };

    Hotlist.prototype.showOrHideExpandArrow = function() {
      var el, hotlistOnOneLine, _ref;
      el = this.DOMRoot.find('#bottom-section a');
      if (this.DOMRoot.find('ul.friends li').length) {
        hotlistOnOneLine = this.DOMRoot.find('ul.friends li:first').offset().top === this.DOMRoot.find('ul.friends li:last').offset().top;
      } else {
        hotlistOnOneLine = true;
      }
      if (!((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0)) {
        el.hide();
        return;
      }
      if (this.isExpanded || !hotlistOnOneLine) {
        return el.show();
      } else {
        return el.hide();
      }
    };

    Hotlist.prototype.setHeight = function(retract, max) {
      var a, height, _ref,
        _this = this;
      if (retract == null) retract = false;
      if (max == null) max = false;
      this.showOrHideExpandArrow();
      if (retract) {
        a = this.DOMRoot.find('ul.friends li:first-child');
      } else {
        a = this.DOMRoot.find('ul.friends li:last-child');
      }
      if (a.length) {
        height = a.offset().top + a.height() - $('ul.friends').offset().top;
      } else {
        height = 0;
      }
      if (height <= 10) height = 70;
      if ($('#bottom-section a').is(":visible")) {
        height = height + $('#bottom-section a').height() + 20;
      }
      if (!((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0)) {
        height = height + 25;
      }
      if (height < 300 || !max) {
        this.DOMRoot.find('ul.friends').height(height);
      } else {
        this.DOMRoot.find('ul.friends').height(300);
      }
      return this.DOMRoot.find('ul.friends').on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function() {
        return A2Cribs.FeaturedListings.resizeHandler();
      });
    };

    Hotlist.prototype.toggleEdit = function() {
      if (this.isEditing()) {
        this.setEditing(false);
        return this.retract();
      } else {
        this.setEditing(true);
        return this.expandForEdit();
      }
    };

    Hotlist.prototype.toggleExpand = function() {
      if ($('#hotlist').hasClass('expanded')) {
        return this.retract();
      } else {
        return this.expand(false);
      }
    };

    Hotlist.prototype.isEditing = function() {
      return this.DOMRoot.hasClass('editing');
    };

    Hotlist.prototype.setEditing = function(state) {
      if (state) {
        return this.DOMRoot.addClass('editing');
      } else {
        return this.DOMRoot.removeClass('editing');
      }
    };

    Hotlist.friendsListPopupTemplate = "<div id='shareto'>\n  <input type='email' id='share-to-email' placeholder='to email'></input>\n  <a class='share-to-email-btn' href='#' onClick='A2Cribs.HotlistObj.shareToEmail(<%=listing_id%>, $(\"#share-to-email\").val());'>\n    <i class=\"icon-share\"></i>\n  </a>\n</div>\n<ul class=\"friends-popup\">\n  <% _.each(friends, function(elem, idx, list) { %>\n    <li>\n      <% name = elem.first_name ? elem.first_name + ' ' + elem.last_name : elem.email %>\n      <% if(elem.facebook_id) { %>\n        <a href='#' onclick='A2Cribs.HotlistObj.shareToFB(<%=listing_id%>, <%=elem.facebook_id%>)'><%=name%></a>\n      <% } else { %>\n        <a href='#' onclick='A2Cribs.HotlistObj.share(<%=listing_id%>, <%=elem.id%>)'><%=name%></a>\n      <% } %>\n    </li>\n  <% }) %>\n</ul>";

    Hotlist.topSectionTemplate = "<div id='share-all'>\n  <span class='title'>Share with your Friends <a title='What is this?' href='#' id='link-info' class='icon icon-info-sign'></a></span>\n  <span class='share-text'>Share to All</span>\n</div>\n<input class='typeahead' type='text' autocomplete='off' id='add-field'></input>\n<div id='buttons' class='pull-right <%=loggedIn ? \"\" : \"hide\"%>'>\n  <a href='#' data-toggle='popover' id='btn-add' class='btn-hotlist btn-hotlist-add' onClick=\"A2Cribs.HotlistObj.add($('#add-field').val())\">+</a>\n  <a href='#' id='btn-edit' class='btn-hotlist btn-hotlist-edit' onClick='A2Cribs.HotlistObj.toggleEdit()'><i class='icon-edit'></i></a>\n</div>\n<div style='clear: both;'></div>";

    Hotlist.friendsListTemplate = "<ul class='friends <%=friends.length ? \"has-friends\" : \"no-friends\"%>'>\n  <% if(friends.length) { %>\n  <% _.each(friends, function(elem, idx, list) { %>\n    <% \n      var tooltitle = elem.email \n      if (elem.first_name) {\n        tooltitle = elem.first_name + ' ' + elem.last_name\n      }\n    %> \n    <li class='friend' data-id='<%=elem.id%>' data-toggle='tooltip' title='<%=tooltitle%>'' data-facebook_id='<%=elem.facebook_id || null%>' data-email='<%=elem.email%>'>\n      <% if (elem.facebook_id){ %>\n        <img class='friend-abbr hotlist-profile-img' src='https://graph.facebook.com/<%=elem.facebook_id%>/picture?width=80&height=80'></img>\n      <% } else if (elem.profile_img) { %>\n        <img class='friend-abbr otlist-profile-img' src='<%=elem.profile_img%>'></img>\n      <% } else if (typeof elem.first_name !== 'undefined' && elem.first_name !== null) { %>\n        <span class='friend-abbr'>\n          <%=elem.first_name[0].toUpperCase()%><%=elem.last_name[0].toUpperCase()%> \n        </span>\n      <% } else { %>\n        <span class='friend-abbr'>\n          <%=elem.email[0]%>@<%=elem.email.split('@')[1][0]%>\n        </span>\n      <% } %>\n      <span class='friend-name'>\n        <% if (typeof elem.first_name !== 'undefined' && elem.first_name !== null) { %>\n          <%=elem.first_name%> <%=elem.last_name%> \n        <% } else { %>\n          <%=elem.email%>\n        <% } %>\n      </span>\n      <a class='btn-hotlist-remove btn-hotlist pull-right' href='#' onClick='A2Cribs.HotlistObj.remove(<%=elem.id%>)'><i class='icon icon-remove-circle'></i></a>\n    </li>\n  <% }); %>\n  <% } else { %>\n    <li class='add-friends-notice'>No friends added yet.</li>\n    <li class='no-friends-notice'>Add friends by clicking here <i class='icon-reply icon-rotate'></i></li>\n    <li class='share-to-fb-notice'><i class='icon-facebook-sign'></i> Drag to Share</li>\n  <% } %>\n</ul>";

    Hotlist.notLoggedInTemplate = "<ul class='friends no-friends not-logged-in'>\n  <li class='not-logged-in-notice'>Log In to share</li>\n</ul>";

    Hotlist.expandButtonTemplate = "<a href='#' onclick='A2Cribs.HotlistObj.toggleExpand()' id='expand-button'><i class='icon icon-caret-down'></i></a>";

    return Hotlist;

  })();

}).call(this);
