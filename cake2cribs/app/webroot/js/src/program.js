(function() {
  var __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; },
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; },
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    _this = this;

  window.A2Cribs = {};

  A2Cribs.Object = (function() {

    function Object(class_name, a2_object) {
      var key, value;
      this.class_name = class_name != null ? class_name : "object";
      for (key in a2_object) {
        value = a2_object[key];
        if (value != null) this[key] = value;
      }
    }

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

  A2Cribs.MixPanel = (function() {
    var array_max, array_min, month_max, month_min;

    function MixPanel() {}

    array_min = function(arr) {
      if (arr != null) return Math.min.apply(null, arr);
    };

    array_max = function(arr) {
      if (arr != null) return Math.min.apply(null, arr);
    };

    month_min = function(arr) {};

    month_max = function(arr) {};

    /*
    	Takes a listing or a marker
    	Uses mixpanel to track the Listing Click event
    	Object can be listing or marker
    	display_type = small popup, large popup, full page
    */

    MixPanel.Click = function(object, display_type) {
      var bed, i, is_featured, listing, listings, marker, mixpanel_object, month, unit_type, _i, _len, _len2, _len3, _len4, _ref, _ref10, _ref11, _ref12, _ref13, _ref14, _ref15, _ref16, _ref17, _ref18, _ref19, _ref2, _ref20, _ref21, _ref22, _ref23, _ref24, _ref25, _ref26, _ref27, _ref3, _ref4, _ref5, _ref6, _ref7, _ref8, _ref9;
      if (object === void 0 || object === null) return;
      is_featured = 0;
      if (object.class_name === "listing") {
        listing = object;
        is_featured = 1 * (_ref = parseInt(listing.listing_id), __indexOf.call(A2Cribs.FeaturedListings.FLListingIds, _ref) >= 0);
        marker = A2Cribs.UserCache.Get("marker", listing.marker_id);
      } else if (object.class_name === "marker") {
        marker = object;
        listings = A2Cribs.UserCache.GetAllAssociatedObjects('listing', 'marker', marker.marker_id);
        for (_i = 0, _len = listings.length; _i < _len; _i++) {
          listing = listings[_i];
          if (_ref2 = parseInt(listing.listing_id), __indexOf.call(A2Cribs.FeaturedListings.FLListingIds, _ref2) >= 0) {
            is_featured = 1;
            break;
          }
        }
      } else if (object.class_name === "rental") {
        listing = A2Cribs.UserCache.Get("listing", object.listing_id);
        is_featured = 1 * (_ref3 = parseInt(listing.listing_id), __indexOf.call(A2Cribs.FeaturedListings.FLListingIds, _ref3) >= 0);
      } else {
        return false;
      }
      mixpanel_object = {
        'listing type': marker != null ? marker.GetBuildingType() : void 0,
        'display type': display_type,
        'is featured': is_featured,
        'listing_id': listing != null ? listing.GetId() : void 0,
        'marker_id': marker != null ? marker.GetId() : void 0,
        'university_id': (_ref4 = A2Cribs.Map) != null ? _ref4.CurentSchoolId : void 0,
        'filter minimum beds': array_min((_ref5 = A2Cribs.RentalFilter.FilterData) != null ? _ref5.Beds : void 0),
        'filter maximum beds': array_max((_ref6 = A2Cribs.RentalFilter.FilterData) != null ? _ref6.Beds : void 0),
        'filter minimum rent': (_ref7 = A2Cribs.RentalFilter.FilterData) != null ? (_ref8 = _ref7.Rent) != null ? _ref8.min : void 0 : void 0,
        'filter maximum rent': (_ref9 = A2Cribs.RentalFilter.FilterData) != null ? (_ref10 = _ref9.Rent) != null ? _ref10.max : void 0 : void 0,
        'filter start year': (_ref11 = A2Cribs.RentalFilter.FilterData) != null ? (_ref12 = _ref11.Dates) != null ? _ref12.year : void 0 : void 0,
        'filter minimum lease length': (_ref13 = A2Cribs.RentalFilter.FilterData) != null ? (_ref14 = _ref13.LeaseRange) != null ? _ref14.min : void 0 : void 0,
        'filter maximum lease length': (_ref15 = A2Cribs.RentalFilter.FilterData) != null ? (_ref16 = _ref15.LeaseRange) != null ? _ref16.max : void 0 : void 0,
        'filter building_type min': array_min,
        'filter building_type': array_min,
        'filter pets allowed': (_ref17 = A2Cribs.RentalFilter.FilterData) != null ? _ref17.PetsAllowed : void 0,
        'filter parking available': (_ref18 = A2Cribs.RentalFilter.FilterData) != null ? _ref18.ParkingAvailable : void 0,
        'filter air conditioning': (_ref19 = A2Cribs.RentalFilter.FilterData) != null ? _ref19.Air : void 0,
        'filter utilities included': (_ref20 = A2Cribs.RentalFilter.FilterData) != null ? _ref20.UtilitiesIncluded : void 0
      };
      if (((_ref21 = A2Cribs.RentalFilter.FilterData) != null ? _ref21.Beds : void 0) != null) {
        _ref22 = A2Cribs.RentalFilter.FilterData.Beds;
        for (i = 0, _len2 = _ref22.length; i < _len2; i++) {
          bed = _ref22[i];
          mixpanel_object["filter bed " + i] = bed;
        }
      }
      if (((_ref23 = A2Cribs.RentalFilter.FilterData) != null ? (_ref24 = _ref23.Dates) != null ? _ref24.months : void 0 : void 0) != null) {
        _ref25 = A2Cribs.RentalFilter.FilterData.Dates.months;
        for (i = 0, _len3 = _ref25.length; i < _len3; i++) {
          month = _ref25[i];
          mixpanel_object["filter month " + i] = month;
        }
      }
      if (((_ref26 = A2Cribs.RentalFilter.FilterData) != null ? _ref26.UnitTypes : void 0) != null) {
        _ref27 = A2Cribs.RentalFilter.FilterData.UnitTypes;
        for (i = 0, _len4 = _ref27.length; i < _len4; i++) {
          unit_type = _ref27[i];
          mixpanel_object["filter unit_type " + i] = unit_type;
        }
      }
      return mixpanel.track('Listing Click', mixpanel_object);
    };

    /*
    	Post listing is a wrapper that appends Post Listing to each mixpanel event
    	Actions such as Started, Marker Selected, Find Address on Map, Marker save complete,
    	Add Unit, Overview started, Features started, Description started, Images started, Saved
    */

    MixPanel.PostListing = function(action, data) {
      return mixpanel.track(("Post Listing - " + action).data);
    };

    /*
    	For either sign up or login
    */

    MixPanel.AuthEvent = function(action, data) {
      return mixpanel.track(action, data);
    };

    return MixPanel;

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
  MarkerTooltip class
  Wrapper for google infobubble
  */

  A2Cribs.MarkerTooltip = (function() {
    /*
    	Constructor
    	-creates infobubble object
    */
    function MarkerTooltip(map) {
      var obj;
      obj = {
        map: map,
        arrowStyle: 0,
        arrowPosition: 20,
        backgroundColor: '#333333',
        shadowStyle: 1,
        borderRadius: 5,
        arrowSize: 17,
        borderWidth: 0,
        disableAutoPan: true,
        padding: 7
      };
      this.InfoBubble = new InfoBubble(obj);
      this.InfoBubble.setBackgroundClassName("markerTooltip");
      this.previousContent = '';
    }

    /*
    	Opens the tooltip given a marker, with popping animation
    */

    MarkerTooltip.prototype.Open = function(marker) {
      if (marker) {
        return this.InfoBubble.open(A2Cribs.Map.GMap, marker);
      } else {
        return this.InfoBubble.open();
      }
    };

    /*
    	Refreshes the tooltip with the new content, no animation
    */

    MarkerTooltip.prototype.Refresh = function() {
      return this.InfoBubble.open();
    };

    /*
    f	Closes the tooltip, no animation
    */

    MarkerTooltip.prototype.Close = function() {
      return this.InfoBubble.close();
    };

    /*
    	Sets the content of the tooltip
    */

    MarkerTooltip.prototype.SetContent = function(content) {
      return this.InfoBubble.setContent(content);
    };

    /*
    	Sets the content and opens tooltip over marker
    */

    MarkerTooltip.prototype.Display = function(visibleIds, marker) {
      if (visibleIds.length) {
        this.CreateContent(visibleIds);
        return this.Open(marker);
      }
    };

    /*
    	Creates the content based on how many listings on the marker
    */

    MarkerTooltip.prototype.CreateContent = function(visibleIds, fromMultipleListings) {
      if (fromMultipleListings == null) fromMultipleListings = false;
      if (visibleIds.length < 2) {
        return this.createSingleContent_(visibleIds, fromMultipleListings);
      } else {
        return this.CreateMultipleContent(visibleIds);
      }
    };

    /*
    	Creates single listing content based on the unit type
    */

    MarkerTooltip.prototype.createSingleContent_ = function(visibleId, fromMultipleListings) {
      if (A2Cribs.Map.IdToSubletMap[visibleId[0]].UnitType === "Greek") {
        return this.createGreekContent_(visibleId);
      } else {
        return this.createGeneralContent_(visibleId, fromMultipleListings);
      }
    };

    /*
    	Creates single listing content for Greek Housing
    */

    MarkerTooltip.prototype.createGreekContent_ = function(visibleId) {
      this.SetHeight(80);
      return this.SetWidth(210);
    };

    /*
    	Creates single listing content for General Housing
    */

    MarkerTooltip.prototype.createGeneralContent_ = function(visibleId, fromMultipleListings) {
      var content, title, tooltipDiv, utilities, visibleListing, visibleMarker;
      this.SetHeight(198);
      this.SetWidth(250);
      this.previousContent = fromMultipleListings ? this.InfoBubble.getContent() : null;
      visibleListing = A2Cribs.Map.IdToSubletMap[visibleId];
      visibleMarker = A2Cribs.Map.IdToMarkerMap[visibleListing.MarkerId];
      title = visibleMarker.Title ? visibleMarker.Title : visibleMarker.Address;
      tooltipDiv = $('#generalTooltip');
      tooltipDiv.find('#tooltipAddress').html(title);
      tooltipDiv.find('a').attr({
        href: visibleListing.Url
      });
      if (fromMultipleListings) {
        tooltipDiv.find('.backToMultipleListings').show();
      } else {
        tooltipDiv.find('.backToMultipleListings').hide();
      }
      if (A2Cribs.FavoritesManager.FavoritesCache[visibleId] != null) {
        tooltipDiv.find('#addFavoriteImg').addClass("starFavorite");
        tooltipDiv.find('#addFavoriteImg').removeClass("starNotFavorite");
        tooltipDiv.find('#addFavoriteImg').attr({
          title: "Delete from Favorites"
        });
        tooltipDiv.find('#addFavoriteImg').attr({
          onclick: "A2Cribs.FavoritesManager.DeleteFavorite(" + visibleId + ")"
        });
      } else {
        tooltipDiv.find('#addFavoriteImg').addClass("starNotFavorite");
        tooltipDiv.find('#addFavoriteImg').removeClass("starFavorite");
        tooltipDiv.find('#addFavoriteImg').attr({
          title: "Add to Favorites"
        });
        tooltipDiv.find('#addFavoriteImg').attr({
          onclick: "A2Cribs.FavoritesManager.AddFavorite(" + visibleId + ")"
        });
      }
      tooltipDiv.find('#tooltipPrice').html(visibleListing.Rent ? '$' + visibleListing.Rent : "Not Available");
      tooltipDiv.find('#tooltipBeds').html(visibleListing.Beds + (visibleListing.Beds > 1 ? " Beds" : " Bed"));
      tooltipDiv.find('#tooltipBaths').html(visibleListing.Baths + (visibleListing.Baths > 1 ? " Baths" : " Bath"));
      tooltipDiv.find('#tooltipLeaseRange').html(visibleListing.LeaseRange);
      tooltipDiv.find('#tooltipType').html(visibleListing.UnitType);
      tooltipDiv.find('#tooltipFurnished').html(visibleListing.Furnished === "Y" ? "Yes" : "No");
      tooltipDiv.find('#tooltipParking').html(visibleListing.Parking === "Y" ? "Yes" : "No");
      tooltipDiv.find('#tooltipAir').html(visibleListing.Air === "Y" ? "Yes" : "No");
      tooltipDiv.find('#tooltipCompany').html(A2Cribs.Map.IdToRealtorMap[visibleListing.RealtorId].Company);
      utilities = '';
      if (visibleListing.Water === "Y") utilities += "Water, ";
      if (visibleListing.Heat === "Y") utilities += "Heat, ";
      if (visibleListing.Electric === "Y") utilities += "Electric, ";
      tooltipDiv.find('#tooltipUtilities').html(utilities.length ? utilities.substring(0, utilities.length - 2) : "Not Included");
      content = $('#generalTooltip').html();
      this.SetContent(content);
      if (fromMultipleListings) return this.Refresh();
    };

    /*
    	Creates bubbles for multiple listings on a single marker
    */

    MarkerTooltip.prototype.CreateMultipleContent = function(visibleIds) {
      var content, id, rootMarker, title, tooltipDiv, _fn, _i, _len;
      this.SetHeight(198);
      this.SetWidth(250);
      if (!(visibleIds != null)) {
        this.SetContent(this.previousContent);
        return this.Refresh();
      }
      rootMarker = A2Cribs.Map.IdToMarkerMap[A2Cribs.Map.IdToSubletMap[visibleIds[0]].MarkerId];
      title = rootMarker.Title ? rootMarker.Title : rootMarker.Address;
      tooltipDiv = $('#multiTooltip');
      tooltipDiv.find('#tooltipAddress').html(title);
      tooltipDiv.find('#multiBubbleContainer').empty();
      _fn = function(id) {
        var currentListing, unitSummary;
        currentListing = A2Cribs.Map.IdToSubletMap[id];
        unitSummary = currentListing.Beds > 1 ? currentListing.Beds + " Beds, " : currentListing.Beds + " Bed, ";
        unitSummary += currentListing.Baths > 1 ? currentListing.Baths + " Baths, " : currentListing.Baths + " Baths, ";
        unitSummary += currentListing.LeaseRange;
        return $('<div/>', {
          id: id,
          "class": 'multiBubble',
          html: '<b>' + currentListing.UnitDescription + '</b><br>' + unitSummary,
          onclick: 'A2Cribs.Map.MarkerTooltip.CreateContent([' + id + '], true)'
        }).appendTo(tooltipDiv.find('#multiBubbleContainer'));
      };
      for (_i = 0, _len = visibleIds.length; _i < _len; _i++) {
        id = visibleIds[_i];
        _fn(id);
      }
      content = $('#multiTooltip').html();
      return this.SetContent(content);
    };

    /*
    */

    MarkerTooltip.prototype.SetWidth = function(width) {
      this.InfoBubble.setMaxWidth(width);
      return this.InfoBubble.setMinWidth(width);
    };

    MarkerTooltip.prototype.SetHeight = function(height) {
      this.InfoBubble.setMaxHeight(height);
      return this.InfoBubble.setMinHeight(height);
    };

    MarkerTooltip.Init = function() {
      this.Height = 230;
      this.Width = 309;
      this.ArrowOffset = 25;
      this.ArrowHeight = 15;
      return this.Padding = 20;
    };

    return MarkerTooltip;

  })();

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

    function Listing(listing) {
      Listing.__super__.constructor.call(this, "listing", listing);
    }

    return Listing;

  })(A2Cribs.Object);

  A2Cribs.Realtor = (function() {

    function Realtor(RealtorId, Company, email) {
      this.RealtorId = RealtorId;
      this.Company = Company;
      this.email = email;
      if (this.Company === null) this.LoadRealtor(this.RealtorId);
    }

    Realtor.prototype.LoadRealtor = function() {};

    return Realtor;

  })();

  A2Cribs.Marker = (function(_super) {
    var FilterVisibleListings, UpdateMarkerContent;

    __extends(Marker, _super);

    Marker.BuildingType = ["House", "Apartment", "Duplex"];

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
      return this.building_type_id;
    };

    Marker.prototype.Init = function() {
      this.GMarker = new google.maps.Marker({
        position: new google.maps.LatLng(this.latitude, this.longitude),
        icon: "/img/dots/available_dot.png",
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
      A2Cribs.MixPanel.Click(this, 'small popup');
      return A2Cribs.HoverBubble.Open(this);
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

    function FavoritesManager() {}

    FavoritesManager.FavoritesListingIds = [];

    FavoritesManager.FavoritesVisible = false;

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
      response = JSON.parse(response);
      if (response.success === void 0) {
        if (response.error.message !== void 0) {
          return A2Cribs.UIManager.Alert(response.error.message);
        } else {
          return A2Cribs.UIManager.Alert("There was an error adding your favorite. Contact help@cribspot.com if the error persists.");
        }
      } else {
        this.FavoritesListingIds.push(listing_id);
        if (button != null) {
          $(button).attr('onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + listing_id + ', this);');
          $(button).attr('title', 'Delete from Favorites');
          $(button).addClass('active');
          return this._setFavoriteCount();
        }
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
      var index;
      response = JSON.parse(response);
      if (response.error !== void 0) {
        return A2Cribs.UIManager.Alert(response.error.message);
      } else {
        index = A2Cribs.FavoritesManager.FavoritesListingIds.indexOf(listing_id);
        if (index !== -1) {
          A2Cribs.FavoritesManager.FavoritesListingIds.splice(index);
        }
        if (button != null) {
          $(button).attr('onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + listing_id + ', this);');
          $(button).attr('title', 'Add to Favorites');
          $(button).removeClass('active');
          return this._setFavoriteCount();
        }
      }
    };

    /*
    	response contains a list of listing_ids that have been favorited by the logged-in user
    */

    FavoritesManager.InitializeFavorites = function(response) {
      var listing_id, listing_ids, _i, _len;
      if (response === null || response === void 0) return;
      listing_ids = JSON.parse(response);
      for (_i = 0, _len = listing_ids.length; _i < _len; _i++) {
        listing_id = listing_ids[_i];
        A2Cribs.FavoritesManager.FavoritesListingIds.push(parseInt(listing_id));
      }
      return this._setFavoriteCount();
    };

    /*
    	Loads all favorites for current user.
    */

    FavoritesManager.LoadFavorites = function() {
      return $.ajax({
        url: myBaseUrl + "Favorites/LoadFavorites",
        type: "GET",
        context: this,
        success: A2Cribs.FavoritesManager.InitializeFavorites
      });
    };

    /*
    	Called when user clicks the heart icon in the header.
    	Toggles visibility of markers where user has favorited a listing.
    */

    FavoritesManager.ToggleFavoritesVisibility = function(button) {
      var all_listings, all_markers, listing, listing_id, marker, _i, _j, _k, _l, _len, _len2, _len3, _len4, _len5, _m, _ref, _ref2, _ref3, _ref4, _ref5, _ref6;
      $(button).toggleClass('active');
      if ((_ref = A2Cribs.HoverBubble) != null) _ref.Close();
      if ((_ref2 = A2Cribs.ClickBubble) != null) _ref2.Close();
      all_markers = A2Cribs.UserCache.Get('marker');
      all_listings = A2Cribs.UserCache.Get('listing');
      if (!A2Cribs.FavoritesManager.FavoritesVisible) {
        $("#FavoritesHeaderIcon").addClass("pressed");
        for (_i = 0, _len = all_markers.length; _i < _len; _i++) {
          marker = all_markers[_i];
          if ((_ref3 = marker.GMarker) != null) _ref3.setVisible(false);
        }
        for (_j = 0, _len2 = all_listings.length; _j < _len2; _j++) {
          listing = all_listings[_j];
          listing.visible = false;
        }
        _ref4 = A2Cribs.FavoritesManager.FavoritesListingIds;
        for (_k = 0, _len3 = _ref4.length; _k < _len3; _k++) {
          listing_id = _ref4[_k];
          listing = A2Cribs.UserCache.Get('listing', listing_id);
          marker = A2Cribs.UserCache.Get('marker', listing.marker_id);
          if ((_ref5 = marker.GMarker) != null) _ref5.setVisible(true);
          listing.visible = true;
        }
      } else {
        for (_l = 0, _len4 = all_markers.length; _l < _len4; _l++) {
          marker = all_markers[_l];
          if (marker != null) {
            if ((_ref6 = marker.GMarker) != null) _ref6.setVisible(true);
          }
        }
        for (_m = 0, _len5 = all_listings.length; _m < _len5; _m++) {
          listing = all_listings[_m];
          listing.visible = true;
        }
        $("#FavoritesHeaderIcon").removeClass("pressed");
      }
      A2Cribs.Map.GMarkerClusterer.repaint();
      return A2Cribs.FavoritesManager.FavoritesVisible = !A2Cribs.FavoritesManager.FavoritesVisible;
    };

    FavoritesManager.FavoritesVisibilityIsOn = function() {
      return $("#FavoritesHeaderIcon").hasClass("pressed");
    };

    /*
    	Initialize a heart icon for adding favorites
    */

    FavoritesManager.setFavoriteButton = function(div_name, listing_id, favorites_list) {
      var div_string;
      div_string = "." + div_name;
      if (listing_id === null) {
        listing_id = parseInt(div_name);
        div_string = "#" + ("" + div_name + ".favorite_listing");
      }
      if (favorites_list.indexOf(parseInt(listing_id, 10)) === -1) {
        $(div_string).attr("onclick", "A2Cribs.FavoritesManager.AddFavorite(" + listing_id + ", this)");
        return $(div_string).removeClass("active");
      } else {
        $(div_string).attr("onclick", "A2Cribs.FavoritesManager.DeleteFavorite(" + listing_id + ", this)");
        return $(div_string).addClass("active");
      }
    };

    /*
    	Inserts the recent favorite into the favorites tab
    */

    FavoritesManager._insertIntoFavoriteDiv = function(listing_id) {
      var content, marker, sublet, template, title;
      if (this.FavoritesCache.size === 1) $('#noFavorites').hide();
      sublet = A2Cribs.Map.IdToSubletMap[listing_id];
      marker = A2Cribs.Map.IdToMarkerMap[sublet.MarkerId];
      title = marker.Title ? marker.Title : marker.Address;
      template = $('#favoriteTemplate');
      template.find('.favoriteDiv').attr({
        id: "favoriteDiv" + listing_id
      });
      template.find('.favoritesAddress').html(title);
      template.find('.removeButton').attr({
        onclick: "A2Cribs.FavoritesManager.DeleteFavorite(" + listing_id + ")"
      });
      template.find('a').attr({
        href: listing.Url
      });
      template.find('#price').html(listing.Rent ? '$' + listing.Rent : "???");
      template.find('#beds').html(listing.Beds + (listing.Beds > 1 ? " Beds" : " Bed"));
      template.find('#baths').html(listing.Baths + (listing.Baths > 1 ? " Baths" : " Bath"));
      template.find('#payMonth').html(listing.LeaseRange);
      template.find('#aptType').html(listing.UnitType);
      template.find('#electric').find('div').addClass(listing.Electric ? "electric_selected" : "electric_unselected");
      template.find('#heat').find('div').addClass(listing.Heat ? "heat_selected" : "heat_unselected");
      template.find('#water').find('div').addClass(listing.Water ? "water_selected" : "water_unselected");
      template.find('#furnished').find('div').addClass(listing.Furnished ? "furnished_selected" : "furnished_unselected");
      template.find('#parking').find('div').addClass(listing.Parking ? "parking_selected" : "parking_unselected");
      template.find('#ac').find('div').addClass(listing.Air ? "ac_selected" : "ac_unselected");
      content = $('#favoriteTemplate').html();
      return $('#personalFavoritesList').append(content);
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

  })();

  /*
  Manager class for all social networking functionality
  */

  A2Cribs.FacebookManager = (function() {

    function FacebookManager() {}

    FacebookManager.FacebookLogin = function() {
      var url;
      url = 'https://www.facebook.com/dialog/oauth?';
      url += 'client_id=488039367944782';
      url += '&redirect_uri=http://' + window.location.hostname + '/login';
      url += '&scope=email';
      A2Cribs.MixPanel.AuthEvent('login', {
        'source': 'facebook'
      });
      return window.location.href = url;
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

  A2Cribs.CorrectMarker = (function() {

    function CorrectMarker() {}

    CorrectMarker.Map = null;

    CorrectMarker.Marker = null;

    CorrectMarker.Geocoder = null;

    CorrectMarker.Enabled = true;

    CorrectMarker.Init = function() {
      var div;
      this.LoadUniversities();
      div = $('#post-sublet-modal').find('#correctLocationMap')[0];
      this.CreateMap(div, 42.2808256, -83.7430378);
      return this.Geocoder = new google.maps.Geocoder();
    };

    CorrectMarker.CreateMap = function(div, latitude, longitude, marker_visible, enabled) {
      var MapOptions, center;
      if (marker_visible == null) marker_visible = false;
      if (enabled == null) enabled = true;
      center = new google.maps.LatLng(latitude, longitude);
      MapOptions = {
        zoom: 15,
        center: center,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        panControl: false,
        zoomControl: false,
        streetViewControl: false
      };
      this.Map = new google.maps.Map(div, MapOptions);
      this.Marker = new google.maps.Marker({
        draggable: enabled,
        position: center,
        map: A2Cribs.CorrectMarker.Map,
        visible: marker_visible
      });
      if (!enabled) this.Disable();
      return google.maps.event.trigger(this.Map, "resize");
    };

    CorrectMarker.Disable = function() {
      if ((this.Map != null)) {
        return this.Map.setOptions({
          draggable: false,
          zoomControl: false,
          scrollwheel: false,
          disableDoubleClickZoom: true
        });
      } else {
        return this.Enabled = false;
      }
    };

    CorrectMarker.Enable = function() {
      if ((this.Map != null)) {
        return this.Map.setOptions({
          draggable: true,
          zoomControl: true,
          scrollwheel: true,
          disableDoubleClickZoom: false
        });
      } else {
        return this.Enabled = true;
      }
    };

    CorrectMarker.UpdateLatLong = function(e) {
      $("#updatedLat").html(e.latLng.lat());
      return $("#updatedLong").html(e.latLng.lng());
    };

    CorrectMarker.AddressSearchCallback = function(response, status) {
      var city, component, state, street_address, street_name, street_number, type, zip, _i, _j, _len, _len2, _ref, _ref2;
      console.log(response);
      if (status === google.maps.GeocoderStatus.OK) {
        if (response[0].address_components.length >= 2) {
          street_number = null;
          street_name = null;
          city = null;
          state = null;
          zip = "00000";
          _ref = response[0].address_components;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            component = _ref[_i];
            _ref2 = component.types;
            for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
              type = _ref2[_j];
              if (type === "street_number") {
                street_number = component.short_name;
              } else if (type === "route") {
                street_name = component.short_name;
              } else if (type === "locality") {
                city = component.short_name;
              } else if (type === "administrative_area_level_1") {
                state = component.short_name;
              } else if (type === "postal_code") {
                zip = component.short_name;
              }
            }
          }
          if (street_number === null) {
            A2Cribs.UIManager.Alert("Entered street address is not valid.");
            $("#formattedAddress").text("");
            return;
          } else {
            A2Cribs.CorrectMarker.Map.panTo(response[0].geometry.location);
            A2Cribs.CorrectMarker.Map.setZoom(18);
          }
          street_address = street_number + " " + street_name;
          $("#formattedAddress").val(street_address);
          $("#city").val(city);
          $("#state").val(state);
          $("#postal").val(zip);
          A2Cribs.CorrectMarker.Marker.setPosition(response[0].geometry.location);
          A2Cribs.CorrectMarker.Marker.setVisible(true);
          google.maps.event.addListener(A2Cribs.CorrectMarker.Marker, 'dragend', A2Cribs.CorrectMarker.UpdateLatLong);
          $("#updatedLat").val(response[0].geometry.location.lat());
          return $("#updatedLong").val(response[0].geometry.location.lng());
        }
      }
    };

    CorrectMarker.CenterMap = function(lat, lng) {
      return this.Map.setCenter(new google.maps.LatLng(lat, lng));
    };

    CorrectMarker.SetMarkerAtPosition = function(latLng) {
      return A2Cribs.CorrectMarker.Marker.setPosition(latLng);
    };

    CorrectMarker.FindAddress = function() {
      var address, request, u;
      address = $("#formattedAddress").val();
      request = {
        location: A2Cribs.CorrectMarker.Map.getCenter(),
        radius: 8100,
        types: ['street_address'],
        keyword: address,
        name: address
      };
      if (A2Cribs.CorrectMarker.SelectedUniversity !== void 0) {
        u = A2Cribs.CorrectMarker.SelectedUniversity;
        return A2Cribs.CorrectMarker.Geocoder.geocode({
          'address': address + " " + u.city + ", " + u.state
        }, A2Cribs.CorrectMarker.AddressSearchCallback);
      } else {
        return A2Cribs.UIManager.Alert("Please select a university.");
      }
    };

    CorrectMarker.FindSelectedUniversity = function() {
      var index, selected, u;
      selected = $("#universityName").val();
      index = this.SchoolList.indexOf(selected);
      if (index >= 0) {
        A2Cribs.CorrectMarker.SelectedUniversity = this.universitiesMap[index].University;
        A2Cribs.Cache.SelectedUniversity = this.universitiesMap[index].University;
        u = A2Cribs.CorrectMarker.SelectedUniversity;
        A2Cribs.CorrectMarker.CenterMap(u.latitude, u.longitude);
        return $("#universityId").val(A2Cribs.CorrectMarker.SchoolIDList[index]);
      }
    };

    CorrectMarker.LoadUniversities = function() {
      return $.ajax({
        url: "/University/getAll",
        success: function(response) {
          var university, _i, _len, _ref;
          A2Cribs.CorrectMarker.universitiesMap = JSON.parse(response);
          A2Cribs.CorrectMarker.SchoolList = [];
          A2Cribs.CorrectMarker.SchoolIDList = [];
          _ref = A2Cribs.CorrectMarker.universitiesMap;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            university = _ref[_i];
            A2Cribs.CorrectMarker.SchoolList.push(university.University.name);
            A2Cribs.CorrectMarker.SchoolIDList.push(university.University.id);
          }
          return $("#universityName").typeahead({
            source: A2Cribs.CorrectMarker.SchoolList
          });
        }
      });
    };

    CorrectMarker.ClearMarker = function() {
      if (A2Cribs.CorrectMarker.Marker != null) {
        return A2Cribs.CorrectMarker.Marker.setVisible(false);
      }
    };

    return CorrectMarker;

  })();

  A2Cribs.PhotoManager = (function() {
    var Photo;

    Photo = (function() {

      function Photo(_div) {
        this._div = _div;
        this._imageId = -1;
        this._isEmpty = true;
        this._isPrimary = false;
        this._caption = "";
        this._path = "";
        this._preview = null;
      }

      Photo.prototype.LoadPhoto = function(_imageId, _path, _caption, isPrimary) {
        this._imageId = _imageId;
        this._path = _path;
        this._caption = _caption;
        this._isEmpty = false;
        this._preview = "<img src='" + this._path + "'></img>";
        this._div.find(".imageContent").html(this._preview);
        return this.SetPrimary(isPrimary);
      };

      Photo.prototype.CreatePreview = function(_file) {
        var reader,
          _this = this;
        this._file = _file;
        if (!Photo.IsAcceptableFileType(this._file.name)) return;
        this._isEmpty = false;
        reader = new FileReader;
        reader.onloadend = function(img) {
          if (typeof img === "object") img = img.target.result;
          _this._preview = "<img src='" + img + "'></img>";
          return _this._div.find(".imageContent").html(_this._preview);
        };
        return reader.readAsDataURL(this._file);
      };

      Photo.prototype.GetPreview = function() {
        return this._preview;
      };

      Photo.prototype.SaveCaption = function(caption) {
        return this._caption = caption;
      };

      Photo.prototype.GetCaption = function() {
        return this._caption;
      };

      Photo.prototype.GetImageId = function() {
        return this._imageId;
      };

      Photo.prototype.IsPrimary = function() {
        return this._isPrimary;
      };

      Photo.prototype.SetPrimary = function(value) {
        this._isPrimary = value;
        if (value) {
          return this._div.find(".primary").addClass('cur-primary');
        } else {
          return this._div.find(".primary").removeClass('cur-primary');
        }
      };

      Photo.prototype.SetId = function(id) {
        return this._imageId = id;
      };

      Photo.prototype.SetPath = function(path) {
        return this._path = path;
      };

      Photo.prototype.SetListingId = function(listing_id) {
        return this._listing_id = listing_id;
      };

      Photo.prototype.IsEmpty = function() {
        return this._isEmpty;
      };

      Photo.prototype.Reset = function() {
        this._isEmpty = true;
        this._div.find(".imageContent").html('<div class="img-place-holder"></div>');
        this._div.find(".image-actions-container").hide();
        this._isPrimary = false;
        this._caption = "";
        this._path = "";
        return this._preview = null;
      };

      Photo.prototype.GetObject = function() {
        return {
          image_id: this._imageId,
          caption: this._caption,
          is_primary: +this._isPrimary,
          image_path: this._path,
          listing_id: this._listing_id
        };
      };

      Photo.IsAcceptableFileType = function(fileName) {
        var fileType, indexOfDot;
        indexOfDot = fileName.indexOf(".", fileName.length - 4);
        if (indexOfDot === -1) return false;
        fileType = fileName.substring(indexOfDot + 1).toLowerCase();
        if (fileType === "jpg" || fileType === "jpeg" || fileType === "png") {
          return true;
        }
        A2Cribs.UIManager.Alert("Not a valid file type. Valid file types include 'jpg', jpeg', or 'png'.");
        return false;
      };

      return Photo;

    })();

    PhotoManager.NUM_PREVIEWS = 6;

    function PhotoManager(div) {
      var _this = this;
      this.div = div;
      this.SetupUI();
      this.CurrentPrimaryImage = 0;
      this.CurrentPreviewImage = null;
      this.CurrentImageLoading = null;
      this.Photos = [];
      this.div.find(".imageContainer").each(function(index, div) {
        return _this.Photos.push(new Photo($(div)));
      });
      this.MAX_CAPTION_LENGTH = 25;
      this.BACKSPACE = 8;
    }

    PhotoManager.prototype.SetupUI = function() {
      var that,
        _this = this;
      that = this;
      this.div.find('.imageContainer').hover(function(event) {
        if ($(event.currentTarget).find('img').length === 1) {
          if (event.type === 'mouseenter') {
            return $(event.currentTarget).find('.image-actions-container').show();
          } else {
            return $(event.currentTarget).find('.image-actions-container').hide();
          }
        }
      });
      this.div.find('#upload_image').click(function() {
        return _this.div.find('#real-file-input').click();
      });
      this.div.find(".imageContent").click(function(event) {
        var index;
        index = +event.currentTarget.id.match(/\d+/g)[0];
        return _this.EditImage(index - 1);
      });
      this.div.find(".edit").click(function(event) {
        var index;
        index = +event.currentTarget.id.match(/\d+/g)[0];
        return _this.EditImage(index - 1);
      });
      this.div.find(".delete").click(function(event) {
        var index;
        index = +event.currentTarget.id.match(/\d+/g)[0];
        return _this.DeleteImage(index - 1);
      });
      this.div.find(".primary").click(function(event) {
        var index;
        index = +event.currentTarget.id.match(/\d+/g)[0];
        return _this.MakePrimary(index - 1);
      });
      this.div.find("#captionInput").keyup(function() {
        var curString;
        curString = _this.div.find("#captionInput").val();
        if (curString.length === _this.MAX_CAPTION_LENGTH) {
          _this.div.find("#charactersLeft").css("color", "red");
        } else {
          _this.div.find("#charactersLeft").css("color", "black");
        }
        _this.div.find("#charactersLeft").html(_this.MAX_CAPTION_LENGTH - curString.length);
        if (_this.CurrentPreviewImage != null) {
          return _this.Photos[_this.CurrentPreviewImage].SaveCaption(_this.div.find("#captionInput").val());
        }
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
      return this.div.find('#ImageAddForm').fileupload({
        url: myBaseUrl + 'images/AddImage',
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(jpeg|jpg|png)$/i,
        singleFileUploads: true,
        maxFileSize: 5000000,
        loadImageMaxFileSize: 15000000,
        disableImageResize: false,
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
      }).on('fileuploadadd', function(e, data) {
        _this.div.find("#upload_image").button('loading');
        if ((_this.CurrentImageLoading = _this.NextAvailablePhoto()) >= 0 && (data.files != null) && (data.files[0] != null)) {
          return _this.Photos[_this.CurrentImageLoading].CreatePreview(data.files[0], _this.div.find("#imageContent" + (_this.CurrentImageLoading + 1)));
        }
      }).on('fileuploaddone', function(e, data) {
        _this.div.find("#upload_image").button('reset');
        if ((data.result.errors != null) && data.result.errors.length) {
          A2Cribs.UIManager.Error("Failed to upload image!");
          return _this.Photos[_this.CurrentImageLoading].Reset();
        } else {
          _this.Photos[_this.CurrentImageLoading].SetId(data.result.image_id);
          return _this.Photos[_this.CurrentImageLoading].SetPath(data.result.image_path);
        }
      }).on('fileuploadfail', function(e, data) {
        A2Cribs.UIManager.Error("Failed to upload image!");
        _this.div.find("#upload_image").button('reset');
        return _this.Photos[_this.CurrentImageLoading].Reset();
      });
    };

    PhotoManager.prototype.LoadImages = function(image_object, row, imageCallback) {
      var image, _i, _len, _ref,
        _this = this;
      this.Reset();
      if ((image_object != null ? image_object.image_array : void 0) != null) {
        _ref = image_object.image_array;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          image = _ref[_i];
          this.Photos[this.NextAvailablePhoto()].LoadPhoto(image.image_id, image.image_path, image.caption, image.is_primary);
        }
      }
      this.div.find("#finish_photo").unbind('click');
      return this.div.find("#finish_photo").click(function() {
        imageCallback(row, _this.GetPhotos());
        return _this.div.modal('hide');
      });
    };

    PhotoManager.prototype.NextAvailablePhoto = function() {
      var i, photo, _len, _ref;
      _ref = this.Photos;
      for (i = 0, _len = _ref.length; i < _len; i++) {
        photo = _ref[i];
        if (photo.IsEmpty()) return i;
      }
      return -1;
    };

    PhotoManager.prototype.DeleteImage = function(index) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + "images/delete/" + this.Photos[index].GetImageId(),
        type: "GET",
        success: function() {
          var i, photo, _len, _ref;
          _this.Photos[index].Reset();
          if (index === _this.CurrentPrimaryImage) {
            _ref = _this.Photos;
            for (i = 0, _len = _ref.length; i < _len; i++) {
              photo = _ref[i];
              if (!photo.IsEmpty()) _this.MakePrimary(i);
            }
          }
          if (index === _this.CurrentPreviewImage) {
            return _this.div.find("#imageContent0").html('<div class="img-place-holder"></div>');
          }
        }
      });
    };

    PhotoManager.prototype.EditImage = function(index) {
      if (!this.Photos[index].IsEmpty()) {
        this.CurrentPreviewImage = index;
        this.div.find("#imageContent0").html(this.Photos[index].GetPreview());
        this.div.find("#captionInput").val(this.Photos[index].GetCaption());
        return this.div.find("#charactersLeft").html(this.MAX_CAPTION_LENGTH - this.Photos[index].GetCaption().length);
      }
    };

    PhotoManager.prototype.MakePrimary = function(index) {
      this.Photos[this.CurrentPrimaryImage].SetPrimary(false);
      return this.Photos[this.CurrentPrimaryImage = index].SetPrimary(true);
    };

    PhotoManager.prototype.Reset = function() {
      var photo, _i, _len, _ref, _results;
      this.div.find("#imageContent0").html('<div class="img-place-holder"></div>');
      this.div.find("#captionInput").val("");
      this.div.find("#charactersLeft").html(this.MAX_CAPTION_LENGTH);
      _ref = this.Photos;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        photo = _ref[_i];
        _results.push(photo.Reset());
      }
      return _results;
    };

    PhotoManager.prototype.GetPhotos = function() {
      var photo, results, _i, _len, _ref;
      results = [];
      _ref = this.Photos;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        photo = _ref[_i];
        if (!photo.IsEmpty()) results.push(photo.GetObject());
      }
      if (results.length === 0) {
        return null;
      } else {
        return results;
      }
    };

    /*
    	Send photo and photo's row_id to server
    	The form of this function is mostly for testing the backend handling of row_id.
    */

    PhotoManager.SubmitPhoto = function(row_id, photo) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + "images/AddImage/" + row_id + "/" + photo,
        type: "POST",
        success: function(response) {
          response = JSON.parse(response);
          return console.log(response);
        }
      });
    };

    return PhotoManager;

  }).call(this);

  A2Cribs.ShareManager = (function() {

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
      url = 'http://cribspot.com/listing/' + listing_id;
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
      fbObj = {
        method: 'feed',
        link: url,
        picture: 'http://www.cribspot.com/img/upright_logo.png',
        name: building_name,
        caption: caption
      };
      if (description !== null) fbObj['description'] = description;
      return FB.ui(fbObj);
    };

    ShareManager.CopyListingUrl = function(listing_id, street_address, city, state, zip) {
      var url;
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      return window.prompt("Copy to clipboard: Ctrl+C, Enter", url);
    };

    ShareManager.ShareListingOnTwitter = function(listing_id, street_address, city, state, zip) {
      var url, x, y;
      url = this.GetTwitterShareUrl(listing_id, street_address, city, state, zip);
      x = screen.width / 2 - 600 / 2;
      y = screen.height / 2 - 350 / 2;
      return window.open(url, 'winname', "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=350,top=" + y + ",left=" + x);
    };

    ShareManager.GetTwitterShareUrl = function(listing_id, street_address, city, state, zip) {
      var url;
      if (listing_id === null || street_address === null || city === null || state === null || zip === null) {
        return null;
      }
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      return 'https://twitter.com/share?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent('Check out this listing on Cribspot!') + '&via=TheCribspot';
    };

    ShareManager.InitTweetButton = function(listing_id, street_address, city, state, zip) {
      var tweetBtn, url;
      if (listing_id === null || street_address === null || city === null || state === null || zip === null) {
        return null;
      }
      url = this.GetShareUrl(listing_id, street_address, city, state, zip);
      $('#twitterDiv iframe').remove();
      tweetBtn = $('<a></a>').addClass('twitter-share-button').attr('href', 'http://twitter.com/share').attr('data-url', url).attr('data-text', 'Check out this awesome property on Cribspot.com! ' + url).attr('data-via', 'TheCribspot');
      $('#twitterDiv').append(tweetBtn);
      return twttr.widgets.load();
    };

    return ShareManager;

  })();

  /*
  HoverBubble class
  Wrapper for google infobubble
  */

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
      var marker_pixel_position, pixels_to_pan, _ref;
      this.Close();
      if ((_ref = A2Cribs.ClickBubble) != null) _ref.Close();
      if (marker) {
        this.SetContent(marker);
        marker_pixel_position = A2Cribs.ClickBubble.ConvertLatLongToPixels(marker.GMarker.getPosition());
        pixels_to_pan = A2Cribs.ClickBubble.GetAdjustedClickBubblePosition(marker_pixel_position.x, marker_pixel_position.y);
        A2Cribs.Map.GMap.panBy(pixels_to_pan.x, pixels_to_pan.y);
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

  /*
  ClickBubble class
  */

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
      if (position === null) return;
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
      }
      this.div.find('.unit_style_description').text(unit_style_description);
      this.div.find('unit_style_description').text;
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
        if (link.indexOf("http") === -1) link = "http://" + link;
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
        if ((image_url != null) && (div_name != null)) {
          return $("." + div_name).css("background-image", "url(/" + image_url + ")");
        }
      } else if (div_name != null) {
        return $("." + div_name).css("background-image", "url(/img/tooltip/no_photo.jpg)");
      }
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
      if (y_high < (TOP + this.PADDING)) offset.y = y_high - (TOP + this.PADDING);
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

  A2Cribs.Sublet = (function() {

    function Sublet(SubletId, UniversityId, BuildingType, Name, StreetAddress, City, State, StartDate, EndDate, Bedrooms, PricePerBedroom, Description, BathroomType, UtilityCost, DepositAmount, AdditionalFeesDescription, AdditionalFeesAmount, MarkerId, FlexibleDates, Furnished, DateAdded, Air, Parking) {
      this.SubletId = SubletId;
      this.UniversityId = UniversityId;
      this.BuildingType = BuildingType;
      this.Name = Name;
      this.StreetAddress = StreetAddress;
      this.City = City;
      this.State = State;
      this.StartDate = StartDate;
      this.EndDate = EndDate;
      this.Bedrooms = Bedrooms;
      this.PricePerBedroom = PricePerBedroom;
      this.Description = Description;
      this.BathroomType = BathroomType;
      this.UtilityCost = UtilityCost;
      this.DepositAmount = DepositAmount;
      this.AdditionalFeesDescription = AdditionalFeesDescription;
      this.AdditionalFeesAmount = AdditionalFeesAmount;
      this.MarkerId = MarkerId;
      this.FlexibleDates = FlexibleDates;
      this.Furnished = Furnished;
      this.DateAdded = DateAdded;
      this.Air = Air;
      this.Parking = Parking;
    }

    return Sublet;

  })();

  A2Cribs.SubletObject = {
    Sublet: {
      id: 0,
      university_id: 0,
      university_name: 0,
      date_begin: 0,
      date_end: 0,
      number_bedrooms: 0,
      price_per_bedroom: 0,
      payment_type_id: 0,
      short_description: 0,
      description: 0,
      bathroom_type_id: 0,
      utility_type_id: 0,
      utility_cost: 0,
      deposit_amount: 0,
      additional_fees_description: 0,
      additional_fees_amount: 0,
      unit_number: 0,
      flexible_dates: 0,
      furnished_type_id: 0,
      ac: 0,
      parking: 0
    },
    Marker: {
      marker_id: 0,
      alternate_name: 0,
      street_address: 0,
      building_type_id: 0,
      city: 0,
      state: 0,
      zip: 0,
      latitude: 0,
      longitude: 0
    },
    Housemate: {
      id: 0,
      quantity: 0,
      enrolled: 0,
      student_type_id: 0,
      major: 0,
      gender_type_id: 0,
      year: 0
    }
  };

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

  A2Cribs.HoverData = (function(_super) {

    __extends(HoverData, _super);

    function HoverData(hoverData) {
      HoverData.__super__.constructor.call(this, "hoverData", hoverData);
    }

    /*
    	Overwrite Object.GetId
    	Want to return the marker_id to which this hoverData belongs
    */

    HoverData.prototype.GetId = function() {
      if ((this[0] != null) && (this[0].Listing != null) && (this[0].Listing.marker_id != null)) {
        return parseInt(this[0].Listing.marker_id);
      }
      return null;
    };

    return HoverData;

  })(A2Cribs.Object);

  A2Cribs.SubletOwner = (function() {

    function SubletOwner(user) {
      this.FirstName = user.first_name;
      this.facebook_userid = user.facebook_userid;
      this.VerifiedUniversity = user.verified_university;
      this.university_verified = user.university_verified;
      this.twitter_userid = user.twitter_userid;
      this.verified = user.verified;
      this.id = user.id;
    }

    return SubletOwner;

  })();

  /*
  ListingPopup class
  */

  A2Cribs.ListingPopup = (function() {
    /*
    	Constructor
    	-creates infobubble object
    */
    function ListingPopup() {
      this.modal = $('.listing-popup').modal({
        show: false
      });
    }

    /*
    	Opens the tooltip given a marker, with popping animation
    */

    ListingPopup.prototype.Open = function(subletId) {
      if (subletId != null) {
        A2Cribs.Map.ClickBubble.Close();
        this.SetContent(subletId);
        $("#overview-btn").click();
        return this.modal.modal('show');
      }
    };

    ListingPopup.prototype.Message = function(subletId) {
      if (subletId != null) {
        this.SetContent(subletId);
        $("#contact-btn").click();
        $("#message-button").click();
        $("#message-area").focus();
        return this.modal.modal('show');
      }
    };

    /*
    	Closes the tooltip, no animation
    */

    ListingPopup.prototype.Close = function() {
      return this.modal.modal('hide');
    };

    /*
    	Sets the content of the tooltip
    */

    ListingPopup.prototype.SetContent = function(subletId) {
      var content, housemates, image, is_favorite, marker, school, short_address, sublet, template, _i, _len, _ref;
      template = $(".listing-popup:first").wrap('<p/>').parent();
      content = template.children().first();
      sublet = A2Cribs.Cache.IdToSubletMap[subletId];
      marker = A2Cribs.Cache.IdToMarkerMap[sublet.MarkerId];
      housemates = A2Cribs.Cache.IdToHousematesMap[A2Cribs.Cache.SubletIdToHousemateIdsMap[subletId]];
      school = A2Cribs.FilterManager.CurrentSchool.split(" ").join("_");
      short_address = marker.Address.split(" ").join("_");
      content.find('.photos').empty();
      if ((A2Cribs.Cache.SubletIdToImagesMap[subletId] != null) && A2Cribs.Cache.SubletIdToImagesMap[subletId].length) {
        _ref = A2Cribs.Cache.SubletIdToImagesMap[subletId];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          image = _ref[_i];
          content.find('.photos').append;
          $('<a href="#" class="preview-thumbnail">').appendTo(content.find('.photos')).css({
            'background-image': image.Path
          });
          if (image.IsPrimary) {
            content.find('#main-photo').css({
              'background-image': 'url(' + image.Path + ')'
            });
          }
        }
      } else {
        content.find('#main-photo').css({
          'background-image': 'url(/img/tooltip/default_house_large.jpg)'
        });
      }
      content.find('.facebook-share').attr('onclick', 'A2Cribs.ShareManager.ShareListingOnFacebook("' + school + '","' + short_address + '", ' + subletId + ')');
      content.find('.twitter-share').attr('href', A2Cribs.ShareManager.GetTwitterShareUrl(school, short_address, subletId));
      content.find('#sublet-id').text(subletId);
      content.find('.sublet-name').text(sublet.Title ? sublet.Title : marker.Address);
      content.find('.bed-price').text(sublet.PricePerBedroom);
      content.find('.full-date').text(this.resolveDateRange(sublet.StartDate, sublet.EndDate));
      content.find('.building-type').text(sublet.BuildingType);
      content.find('.school-name').text(A2Cribs.Cache.SubletIdToOwnerMap[subletId].VerifiedUniversity);
      content.find('.full-address').text(marker.Address + ", " + marker.City + ", " + marker.State);
      content.find('.bath-type').text(sublet.BathroomType);
      content.find('.parking-avail').text(sublet.Parking ? "Yes" : "No");
      content.find('.ac-avail').text(sublet.Air ? "Yes" : "No");
      content.find('.furnish-avail').text(sublet.Furnished === 3 ? "No" : "Yes");
      content.find('.first-name').text(A2Cribs.Cache.SubletIdToOwnerMap[subletId].FirstName);
      content.find('.short-description').find('p').text(sublet.Description);
      subletId = sublet.SubletId;
      is_favorite = __indexOf.call(A2Cribs.Cache.FavoritesSubletIdsList, subletId) >= 0;
      if (is_favorite) {
        content.find('#favorite-btn').attr('title', 'Delete from Favorites');
        content.find('#favorite-btn').attr('onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ', this)');
        $('#favorite-btn').addClass("active");
      } else {
        content.find('#favorite-btn').attr('title', 'Add to Favorites');
        content.find('#favorite-btn').attr('onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ', this)');
        $('#favorite-btn').removeClass("active");
      }
      if (housemates !== void 0 && housemates !== null) {
        content.find('.housemate-count').text(housemates.Quantity);
        if (housemates.Quantity === 0) {
          content.find('.housemate-enrolled').text("--");
          content.find('.housemate-type').text("--");
          content.find('.housemate-major').text("--");
          content.find('.housemate-gender').text("--");
          content.find('.housemate-year').text("--");
        } else {
          content.find('.housemate-enrolled').text(housemates.Enrolled ? "Yes" : "No");
          if (!housemates.Enrolled) {
            content.find('.housemate-type').text("--");
            content.find('.housemate-major').text("--");
            content.find('.housemate-gender').text("--");
            content.find('.housemate-year').text("--");
          } else {
            content.find('.housemate-type').text(housemates.GradType);
            content.find('.housemate-major').text(housemates.Major);
            content.find('.housemate-gender').text(housemates.Gender);
            content.find('.housemate-year').text(housemates.Year);
          }
        }
      }
      content.find('.utilities-cost').text(sublet.UtilityCost === 0 ? "Included" : "$" + sublet.UtilityCost);
      content.find('.deposit-cost').text(sublet.DepositAmount === 0 ? "None" : "$" + sublet.DepositAmount);
      content.find('.additional-fee').text(sublet.AdditionalFeesAmount === 0 ? "None" : "$" + sublet.AdditionalFeesAmount);
      this.loadVerificationInfo(subletId, content);
      return $(".listing-popup:first").unwrap();
    };

    ListingPopup.prototype.resolveDateRange = function(startDate, endDate) {
      var endSplit, range, rmonth, startSplit;
      rmonth = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
      range = "";
      startSplit = startDate.split("-");
      endSplit = endDate.split("-");
      range += rmonth[startSplit[1] - 1];
      range += " " + parseInt(startSplit[2]) + ", " + startSplit[0] + " to ";
      return range + rmonth[endSplit[1] - 1] + " " + parseInt(endSplit[2]) + ", " + endSplit[0];
    };

    ListingPopup.prototype.loadVerificationInfo = function(sublet_id, content) {
      var user;
      if (!A2Cribs.FBInitialized && A2Cribs.marker_id_to_open > 0) {
        A2Cribs.FBInitialized = true;
        return;
      }
      user = A2Cribs.Cache.SubletIdToOwnerMap[sublet_id];
      return A2Cribs.VerifyManager.getVerificationFor(user).then(function(verification_info) {
        var pic_url;
        if (parseInt(verification_info.mut_friends) === 0 || verification_info.mut_friends === void 0 || verification_info.mut_friends === null) {
          $(".facebookFriendLabel").html("Total Friends:");
          if (verification_info.tot_friends !== null && !isNaN(verification_info.tot_friends)) {
            $(".numFacebookFriends").html(verification_info.tot_friends);
          } else {
            $(".numFacebookFriends").html("--");
          }
        } else {
          $(".facebookFriendLabel").html("Mutual Friends:");
          $(".numFacebookFriends").html(verification_info.mut_friends);
        }
        if (verification_info.tot_followers !== null && !isNaN(verification_info.tot_followers)) {
          $(".numTwitterFollowers").html(verification_info.tot_followers);
        } else {
          $(".numTwitterFollowers").html("--");
        }
        if (verification_info.verified_edu) {
          $("#universityVerified").removeClass("unverified");
          $("#universityVerified").addClass("verified");
        } else {
          $("#universityVerified").removeClass("verified");
          $("#universityVerified").addClass("unverified");
        }
        if (verification_info.verified_email || verification_info.verified_edu) {
          $("#emailVerified").removeClass("unverified");
          $("#emailVerified").addClass("verified");
        } else {
          $("#emailVerified").removeClass("verified");
          $("#emailVerified").addClass("unverified");
        }
        if (verification_info.verified_fb) {
          $("#fbVerified").removeClass("unverified");
          $("#fbVerified").addClass("verified");
        } else {
          $("#fbVerified").removeClass("verified");
          $("#fbVerified").addClass("unverified");
        }
        if (verification_info.verified_tw) {
          $("#twitterVerified").removeClass("unverified");
          $("#twitterVerified").addClass("verified");
        } else {
          $("#twitterVerified").removeClass("verified");
          $("#twitterVerified").addClass("unverified");
        }
        if (verification_info.verified_fb) {
          pic_url = "https://graph.facebook.com/" + verification_info.fb_id + "/picture?width=480";
          return $(".user_contact_pic").attr("src", pic_url);
        } else {
          return $(".user_contact_pic").attr('src', "/img/head_large.jpg");
        }
      });
    };

    return ListingPopup;

  })();

  A2Cribs.UIManager = (function() {

    function UIManager() {}

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
        return this[flash_message.method](flash_message.message);
      }
    };

    UIManager.Confirm = function(message, callback) {
      alertify.set({
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

    function Image(image) {
      var i, image_object, _len, _ref;
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
      if (this.primary != null) return this.image_array[this.primary][field];
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

  A2Cribs.SubletAdd = (function() {

    function SubletAdd() {}

    SubletAdd.setupUI = function() {
      var oldBeginDate, oldEndDate,
        _this = this;
      $('#goToStep2').click(function(e) {
        if (!$('#formattedAddress').val()) {
          return A2Cribs.UIManager.Alert("Please place your street address on the map using the Place On Map button.");
        } else if (!$('#universityName').val()) {
          return A2Cribs.UIManager.Alert("You need to select a university.");
        } else if ($('#SubletUnitNumber').val().length >= 249) {
          return A2Cribs.UIManager.Alert("Your unit number is too long.");
        } else if ($('#SubletName').val().length >= 249) {
          return A2Cribs.UIManager.Alert("Your alternate name is too long.");
        } else {
          return _this.subletAddStep1();
        }
      });
      $("#backToStep2").click(function(e) {
        return _this.backToStep2();
      });
      $('#goToStep1').click(function(e) {
        return _this.backToStep1();
      });
      $("#goToStep3").click(function(e) {
        var parsedBeginDate, parsedEndDate, todayDate;
        parsedBeginDate = new Date(Date.parse($('#SubletDateBegin').val()));
        parsedEndDate = new Date(Date.parse($('#SubletDateEnd').val()));
        todayDate = new Date();
        if (parsedBeginDate.toString() === "Invalid Date" || parsedEndDate.toString() === "Invalid Date") {
          return A2Cribs.UIManager.Alert("Please enter a valid date.");
        } else if (parsedEndDate <= parsedBeginDate || parsedBeginDate.valueOf() <= todayDate.valueOf()) {
          return A2Cribs.UIManager.Alert("Please enter a valid date.");
        } else if (!$('#SubletNumberBedrooms').val() || $('#SubletNumberBedrooms').val() <= 0 || $('#SubletNumberBedrooms').val() >= 30) {
          return A2Cribs.UIManager.Alert("Please enter a valid number of bedrooms.");
        } else if (!$('#SubletPricePerBedroom').val() || $('#SubletPricePerBedroom').val() < 1 || $('#SubletPricePerBedroom').val() >= 20000) {
          return A2Cribs.UIManager.Alert("Please enter a valid price per bedroom.");
        } else if ($('#SubletDescription').val().length >= 161) {
          return A2Cribs.UIManager.Alert("Please keep the short description under 160 characters.");
        } else if (!$('#SubletUtilityCost').val() || $('#SubletUtilityCost').val() < 0 || $('#SubletUtilityCost').val() >= 50000) {
          return A2Cribs.UIManager.Alert("Please enter a valid utility cost.");
        } else if (!$('#SubletDepositAmount').val() || $('#SubletDepositAmount').val() < 0 || $('#SubletDepositAmount').val() >= 50000) {
          return A2Cribs.UIManager.Alert("Please enter a valid deposit amount.");
        } else if ($('#SubletAdditionalFeesDescription').val().length >= 161) {
          return A2Cribs.UIManager.Alert("Please keep the additional fees description under 160 characters.");
        } else if (!$('#SubletAdditionalFeesAmount').val() || $('#SubletAdditionalFeesAmount').val() < 0 || $('#SubletAdditionalFeesAmount').val() >= 50000) {
          return A2Cribs.UIManager.Alert("Please enter a valid additional fees amount.");
        } else {
          return A2Cribs.SubletAdd.subletAddStep2();
        }
      });
      $('#finishSubletAdd').click(function(e) {
        if (!$('#HousemateQuantity').val() || $('#HousemateQuantity').val() >= 50 || $('#HousemateQuantity').val() < 0) {
          return A2Cribs.UIManager.Alert("Please enter a valid housemate quantity.");
        } else if ($('#HousemateMajor').val().length >= 254) {
          return A2Cribs.UIManager.Alert("Please keep the majors description under 255 characters.");
        } else {
          A2Cribs.SubletEdit.CacheStep3Data();
          e.preventDefault();
          return _this.subletAddStep3();
        }
      });
      $("#finishShare").click(function(e) {
        $('#server-notice').dialog2("close");
        if (!isNaN(A2Cribs.ShareManager.SavedListing)) {
          return window.location.href = "/sublets/show/" + A2Cribs.ShareManager.SavedListing;
        }
      });
      oldBeginDate = new Date($('#SubletDateBegin').val());
      $('#SubletDateBegin').val(oldBeginDate.toDateString());
      oldEndDate = new Date($('#SubletDateEnd').val());
      return $('#SubletDateEnd').val(oldEndDate.toDateString());
    };

    SubletAdd.InitPostingProcess = function(e) {
      var subletmodal,
        _this = this;
      if (e == null) e = null;
      A2Cribs.Cache.SubletEditInProgress = new A2Cribs.SubletInProgress();
      subletmodal = $("<div/>").dialog2({
        title: "Post a sublet",
        content: "/Sublets/ajax_add",
        id: "server-notice",
        closeOnOverlayClick: false,
        closeOnEscape: false,
        removeOnClose: true
      });
      this.resizeModal(subletmodal);
      $(window).resize(function() {
        return _this.resizeModal(subletmodal);
      });
      if (e !== null) return e.preventDefault();
    };

    SubletAdd.resizeModal = function(modal_body) {
      var header_footer_size, margin, new_body_height, parent_modal, target_modal_size;
      parent_modal = modal_body.parent('.modal');
      margin = 20;
      target_modal_size = window.innerHeight - (2 * margin);
      header_footer_size = parent_modal.height() - modal_body.height();
      new_body_height = target_modal_size - header_footer_size;
      return modal_body.css('height', new_body_height + 'px');
    };

    SubletAdd.backToStep1 = function() {
      return $('#server-notice').dialog2("options", {
        content: "/Sublets/ajax_add"
      });
    };

    SubletAdd.backToStep2 = function() {
      return $('#server-notice').dialog2("options", {
        content: "/Sublets/ajax_add2"
      });
    };

    SubletAdd.backToStep3 = function() {
      return $('#server-notice').dialog2("options", {
        content: "/Sublets/ajax_add3"
      });
    };

    SubletAdd.subletAddStep1 = function() {
      A2Cribs.SubletEdit.CacheStep1Data();
      return $('#server-notice').dialog2("options", {
        content: "/Sublets/ajax_add2"
      });
    };

    SubletAdd.subletAddStep2 = function() {
      A2Cribs.SubletEdit.CacheStep2Data();
      return $('#server-notice').dialog2("options", {
        content: "/Sublets/ajax_add3"
      });
    };

    SubletAdd.subletAddStep3 = function() {
      var url,
        _this = this;
      url = "/sublets/ajax_submit_sublet";
      if (!(this.postingDataInProgress != null) || this.postingDataInProgress === false) {
        this.postingDataInProgress = true;
        return $.post(url, A2Cribs.Cache.SubletEditInProgress, function(response) {
          var data;
          data = JSON.parse(response);
          console.log(data.status);
          if (data.status) {
            A2Cribs.UIManager.Alert(data.status);
            A2Cribs.ShareManager.SavedListing = data.newid;
            $('#server-notice').dialog2("options", {
              content: "/Sublets/ajax_add4"
            });
          } else {
            A2Cribs.UIManager.Alert(data.error);
          }
          return _this.postingDataInProgress = true;
        });
      } else {
        return false;
      }
    };

    SubletAdd.GetFormattedDate = function(date) {
      var beginDateFormatted, day, month, year;
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = month + "/" + day + "/" + year;
    };

    return SubletAdd;

  })();

  A2Cribs.SubletEdit = (function() {

    function SubletEdit() {}

    SubletEdit.Init = function(subletData) {
      A2Cribs.Cache.SubletEditInProgress = new A2Cribs.SubletInProgress();
      return A2Cribs.SubletEdit.InitLoadedSubletData();
    };

    SubletEdit.CacheStep1Data = function() {
      A2Cribs.Cache.SubletEditInProgress.Sublet.university_id = parseInt(A2Cribs.CorrectMarker.SelectedUniversity.id);
      A2Cribs.Cache.SubletEditInProgress.Sublet.university_name = $('#universityName').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.unit_number = $('#SubletUnitNumber').val();
      A2Cribs.Cache.SubletEditInProgress.Marker.street_address = $("#formattedAddress").val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.building_type_id = parseInt($('#SubletBuildingTypeId').val());
      A2Cribs.Cache.SubletEditInProgress.Marker.building_type_id = parseInt($('#SubletBuildingTypeId').val());
      A2Cribs.Cache.SubletEditInProgress.Marker.alternate_name = $('#SubletName').val();
      A2Cribs.Cache.SubletEditInProgress.Marker.latitude = $('#updatedLat').val();
      A2Cribs.Cache.SubletEditInProgress.Marker.longitude = $('#updatedLong').val();
      A2Cribs.Cache.SubletEditInProgress.Marker.city = $('#city').val();
      A2Cribs.Cache.SubletEditInProgress.Marker.state = $('#state').val();
      return A2Cribs.Cache.SubletEditInProgress.Marker.zip = $('#postal').val();
    };

    SubletEdit.CacheStep2Data = function() {
      A2Cribs.Cache.SubletEditInProgress.Sublet.date_begin = A2Cribs.SubletEdit.GetMysqlDateFormat($('#SubletDateBegin').val());
      A2Cribs.Cache.SubletEditInProgress.Sublet.date_end = A2Cribs.SubletEdit.GetMysqlDateFormat($('#SubletDateEnd').val());
      A2Cribs.Cache.SubletEditInProgress.Sublet.flexible_dates = $('#SubletFlexibleDates').is(':checked');
      A2Cribs.Cache.SubletEditInProgress.Sublet.number_bedrooms = $('#SubletNumberBedrooms').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.price_per_bedroom = $('#SubletPricePerBedroom').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.short_description = $('#SubletDescription').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.description = $('#SubletDescription').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.bathroom_type_id = $('#SubletBathroomTypeId').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.utility_type_id = $('#SubletUtilityTypeId').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.utility_cost = $('#SubletUtilityCost').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.parking = $('#SubletParking').is(':checked');
      A2Cribs.Cache.SubletEditInProgress.Sublet.ac = $('#SubletAc').is(':checked');
      A2Cribs.Cache.SubletEditInProgress.Sublet.furnished_type_id = $('#SubletFurnishedTypeId').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.deposit_amount = $('#SubletDepositAmount').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_description = $('#SubletAdditionalFeesDescription').val();
      A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_amount = $('#SubletAdditionalFeesAmount').val();
      return A2Cribs.Cache.SubletEditInProgress.Sublet.payment_type_id = 1;
    };

    SubletEdit.CacheStep3Data = function() {
      A2Cribs.Cache.SubletEditInProgress.Housemate.quantity = $("#HousemateQuantity").val();
      A2Cribs.Cache.SubletEditInProgress.Housemate.enrolled = $("#HousemateEnrolled").is(':checked');
      A2Cribs.Cache.SubletEditInProgress.Housemate.student_type_id = $("#HousemateStudentTypeId").val();
      A2Cribs.Cache.SubletEditInProgress.Housemate.major = $("#HousemateMajor").val();
      A2Cribs.Cache.SubletEditInProgress.Housemate.gender_type_id = $("#HousemateGenderTypeId").val();
      return A2Cribs.Cache.SubletEditInProgress.Housemate.type = $("#HousemateType").val();
    };

    /*
    	Populates fields in step 1 with data loaded from cache
    
    	this function is also used to load in cached values while going between steps in adding sublet
    */

    SubletEdit.InitStep1 = function(editing_sublet) {
      var subletData;
      if (editing_sublet == null) editing_sublet = false;
      subletData = A2Cribs.Cache.SubletEditInProgress;
      if (subletData.Sublet !== null && subletData.Sublet !== void 0) {
        $('#universityName').val(subletData.Sublet.university_name);
        $('#SubletUnitNumber').val(subletData.Sublet.unit_number);
      }
      if (subletData.Marker !== null && subletData.Marker !== void 0) {
        $('#SubletBuildingTypeId').val(subletData.Marker.building_type_id);
        if (editing_sublet === true) {
          A2Cribs.CorrectMarker.Disable();
        } else {
          $("#addressToMark").val(subletData.Marker.street_address);
        }
        $('#SubletName').val(subletData.Marker.alternate_name);
        $("#formattedAddress").val(subletData.Marker.street_address);
        $('#updatedLat').val(subletData.Marker.latitude);
        $('#updatedLong').val(subletData.Marker.longitude);
        $("#city").val(subletData.Marker.city);
        $("#state").val(subletData.Marker.state);
        $("#postal").val(subletData.Marker.zip);
      }
      if (subletData.Sublet.university_name !== null && subletData.Sublet.university_name !== void 0) {
        A2Cribs.CorrectMarker.FindSelectedUniversity();
      }
      if (subletData.Marker.street_address !== null && subletData.Marker.street_address !== void 0) {
        return A2Cribs.CorrectMarker.FindAddress();
      }
    };

    SubletEdit.InitStep2 = function() {
      var beginDate, endDate, formattedBeginDate, formattedEndDate;
      $('#SubletDateBegin').val("");
      $('#SubletDateEnd').val("");
      $('#SubletFlexibleDates').prop("checked", true);
      $('#SubletParking').prop("checked", false);
      $('#SubletAc').prop("checked", false);
      if (A2Cribs.Cache.SubletEditInProgress.Sublet === null || A2Cribs.Cache.SubletEditInProgress.Sublet === void 0) {
        return;
      }
      if (A2Cribs.Cache.SubletEditInProgress.Sublet.date_begin !== null) {
        beginDate = new Date(A2Cribs.Cache.SubletEditInProgress.Sublet.date_begin);
        formattedBeginDate = A2Cribs.SubletAdd.GetFormattedDate(beginDate);
      }
      if (A2Cribs.Cache.SubletEditInProgress.Sublet.date_end !== null) {
        endDate = new Date(A2Cribs.Cache.SubletEditInProgress.Sublet.date_end);
        formattedEndDate = A2Cribs.SubletAdd.GetFormattedDate(endDate);
      }
      $('#SubletDateBegin').val(formattedBeginDate);
      $('#SubletDateEnd').val(formattedEndDate);
      if (A2Cribs.Cache.SubletEditInProgress.Sublet.flexible_dates !== null) {
        $('#SubletFlexibleDates').prop('checked', A2Cribs.Cache.SubletEditInProgress.Sublet.flexible_dates);
      }
      $('#SubletNumberBedrooms').val(A2Cribs.Cache.SubletEditInProgress.Sublet.number_bedrooms);
      $('#SubletPricePerBedroom').val(A2Cribs.Cache.SubletEditInProgress.Sublet.price_per_bedroom);
      $('#SubletDescription').val(A2Cribs.Cache.SubletEditInProgress.Sublet.description);
      $('#SubletBathroomTypeId').val(A2Cribs.Cache.SubletEditInProgress.Sublet.bathroom_type_id);
      $('#SubletUtilityTypeId').val(A2Cribs.Cache.SubletEditInProgress.Sublet.utility_type_id);
      $('#SubletUtilityCost').val(A2Cribs.Cache.SubletEditInProgress.Sublet.utility_type_id);
      $('#SubletParking').prop("checked", A2Cribs.Cache.SubletEditInProgress.Sublet.parking);
      $('#SubletAc').prop("checked", A2Cribs.Cache.SubletEditInProgress.Sublet.ac);
      $('#SubletFurnishedTypeId').val(A2Cribs.Cache.SubletEditInProgress.Sublet.furnished_type_id);
      $('#SubletDepositAmount').val(A2Cribs.Cache.SubletEditInProgress.Sublet.deposit_amount);
      $('#SubletAdditionalFeesDescription').val(A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_description);
      return $('#SubletAdditionalFeesAmount').val(A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_amount);
    };

    SubletEdit.InitStep3 = function() {
      $("#HousemateEnrolled").prop("checked", false);
      if (A2Cribs.Cache.SubletEditInProgress.Housemate === null || A2Cribs.Cache.SubletEditInProgress.Housemate === void 0) {
        return;
      }
      $("#HousemateQuantity").val(A2Cribs.Cache.SubletEditInProgress.Housemate.quantity);
      $("#HousemateEnrolled").prop("checked", A2Cribs.Cache.SubletEditInProgress.Housemate.enrolled);
      $("#HousemateStudentTypeId").val(A2Cribs.Cache.SubletEditInProgress.Housemate.student_type_id);
      $("#HousemateMajor").val(A2Cribs.Cache.SubletEditInProgress.Housemate.major);
      $("#HousemateGenderTypeId").val(A2Cribs.Cache.SubletEditInProgress.Housemate.gender_type_id);
      return $("#HousemateType").val(A2Cribs.Cache.SubletEditInProgress.Housemate.type);
    };

    /*
    	Fully populates A2Cribs.Cache.SubletData with data loaded from database
    	Call from edit view
    */

    SubletEdit.InitLoadedSubletData = function() {
      var b, h, m, s, u;
      if (A2Cribs.Cache.SubletData === void 0) return;
      s = A2Cribs.Cache.SubletData.Sublet;
      h = A2Cribs.Cache.SubletData.Housemate[0];
      m = A2Cribs.Cache.SubletData.Marker;
      u = A2Cribs.Cache.SubletData.University;
      b = A2Cribs.Cache.SubletData.BuildingType;
      if (u !== null && u !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.university_id = parseInt(u.id);
        A2Cribs.Cache.SubletEditInProgress.Sublet.university_name = u.name;
      }
      if (b !== null && b !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.building_type_id = parseInt(b.id);
      }
      if (s !== null && s !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.id = parseInt(s.id);
        A2Cribs.Cache.SubletEditInProgress.Sublet.date_begin = s.date_begin;
        A2Cribs.Cache.SubletEditInProgress.Sublet.date_end = s.date_end;
        A2Cribs.Cache.SubletEditInProgress.Sublet.number_bedrooms = parseInt(s.number_bedrooms);
        A2Cribs.Cache.SubletEditInProgress.Sublet.price_per_bedroom = parseInt(s.price_per_bedroom);
        A2Cribs.Cache.SubletEditInProgress.Sublet.payment_type_id = parseInt(s.payment_type_id);
        A2Cribs.Cache.SubletEditInProgress.Sublet.short_description = s.short_description;
        A2Cribs.Cache.SubletEditInProgress.Sublet.description = s.description;
        A2Cribs.Cache.SubletEditInProgress.Sublet.utility_cost = parseInt(s.utility_cost);
        A2Cribs.Cache.SubletEditInProgress.Sublet.deposit_amount = s.deposit_amount;
        A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_description = s.additional_fees_description;
        A2Cribs.Cache.SubletEditInProgress.Sublet.additional_fees_amount = s.additional_fees_amount;
        A2Cribs.Cache.SubletEditInProgress.Sublet.unit_number = s.unit_number;
        A2Cribs.Cache.SubletEditInProgress.Sublet.flexible_dates = s.flexible_dates;
        A2Cribs.Cache.SubletEditInProgress.Sublet.furnished_type_id = s.furnished_type_id;
        A2Cribs.Cache.SubletEditInProgress.Sublet.ac = s.ac;
        A2Cribs.Cache.SubletEditInProgress.Sublet.parking = s.parking;
      }
      if (A2Cribs.Cache.SubletData.BathroomType !== null && A2Cribs.Cache.SubletData.BathroomType !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.bathroom_type_id = parseInt(A2Cribs.Cache.SubletData.BathroomType.id);
      }
      if (b !== null && b !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.building_type_id = parseInt(b.id);
      }
      if (A2Cribs.Cache.SubletData.UtilityType !== null && A2Cribs.Cache.SubletData.UtilityType !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Sublet.utility_type_id = parseInt(A2Cribs.Cache.SubletData.UtilityType.id);
      }
      if (m !== null && m !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Marker.marker_id = parseInt(m.marker_id);
        A2Cribs.Cache.SubletEditInProgress.Marker.street_address = m.street_address;
        A2Cribs.Cache.SubletEditInProgress.Marker.building_type_id = m.building_type_id;
        A2Cribs.Cache.SubletEditInProgress.Marker.alternate_name = m.alternate_name;
        A2Cribs.Cache.SubletEditInProgress.Marker.city = m.city;
        A2Cribs.Cache.SubletEditInProgress.Marker.state = m.state;
        A2Cribs.Cache.SubletEditInProgress.Marker.zip = m.zip;
        A2Cribs.Cache.SubletEditInProgress.Marker.latitude = m.latitude;
        A2Cribs.Cache.SubletEditInProgress.Marker.longitude = m.longitude;
      }
      if (h !== null && h !== void 0) {
        A2Cribs.Cache.SubletEditInProgress.Housemate.id = parseInt(h.id);
        A2Cribs.Cache.SubletEditInProgress.Housemate.enrolled = h.enrolled;
        A2Cribs.Cache.SubletEditInProgress.Housemate.student_type_id = h.student_type_id;
        A2Cribs.Cache.SubletEditInProgress.Housemate.major = h.major;
        A2Cribs.Cache.SubletEditInProgress.Housemate.gender_type_id = h.gender_type_id;
        A2Cribs.Cache.SubletEditInProgress.Housemate.type = h.type;
        return A2Cribs.Cache.SubletEditInProgress.Housemate.quantity = h.quantity;
      }
    };

    /*
    	Retrieves all necessary sublet data and then pulls up the modal for edit sublet
    */

    SubletEdit.EditSublet = function(sublet_id) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + "Sublets/getSubletDataById/" + sublet_id,
        type: "GET",
        success: function(subletData) {
          var modal_body;
          subletData = JSON.parse(subletData);
          A2Cribs.Cache.SubletData = subletData;
          A2Cribs.SubletEdit.Init();
          modal_body = $('<div/>').dialog2({
            title: "Edit " + subletData.Marker.street_address,
            content: "/Sublets/ajax_add",
            id: "server-notice",
            closeOnOverlayClick: false,
            closeOnEscape: false,
            removeOnClose: true
          });
          A2Cribs.SubletAdd.resizeModal(modal_body);
          return $(window).resize(function() {
            return A2Cribs.SubletAdd.resizeModal(modal_body);
          });
        },
        error: function() {
          return alertify.error("An error occured while loading your sublet data, please try again.", 2000);
        }
      });
    };

    /*
    	Replaces '/' with '-' to make convertible to mysql datetime format
    */

    SubletEdit.GetMysqlDateFormat = function(dateString) {
      var beginDateFormatted, date, day, month, year;
      date = new Date(dateString);
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = year + "-" + month + "-" + day;
    };

    SubletEdit.GetTodaysDate = function() {
      var dd, mm, today, yyyy;
      today = new Date();
      dd = today.getDate();
      mm = today.getMonth() + 1;
      yyyy = today.getFullYear();
      if (dd < 10) dd = '0' + dd;
      if (mm < 10) mm = '0' + mm;
      today = mm + '/' + dd + '/' + yyyy;
      return today;
    };

    return SubletEdit;

  })();

  A2Cribs.SubletInProgress = (function() {

    function SubletInProgress() {
      this.Sublet = {
        id: null,
        university_id: null,
        university_name: null,
        building_type_id: null,
        date_begin: null,
        date_end: null,
        number_bedrooms: null,
        price_per_bedroom: null,
        payment_type_id: null,
        short_description: null,
        description: null,
        bathroom_type_id: null,
        utility_type_id: null,
        utility_cost: null,
        deposit_amount: null,
        additional_fees_description: null,
        additional_fees_amount: null,
        unit_number: null,
        flexible_dates: null,
        furnished_type_id: null,
        ac: null,
        parking: null
      };
      this.Marker = {
        marker_id: null,
        alternate_name: null,
        street_address: null,
        building_type_id: null,
        city: null,
        state: null,
        zip: null,
        latitude: null,
        longitude: null
      };
      this.Housemate = {
        id: null,
        quantity: null,
        enrolled: null,
        student_type_id: null,
        major: null,
        gender_type_id: null,
        type: null
      };
    }

    return SubletInProgress;

  })();

  A2Cribs.Rental = (function(_super) {

    __extends(Rental, _super);

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

    Rental.prototype.GetId = function(id) {
      return parseInt(this["listing_id"], 10);
    };

    Rental.prototype.IsComplete = function() {
      if (this.rental_id != null) {
        return true;
      } else {
        return false;
      }
    };

    /*
    	@Template =
    		data =
    			Listing:
    				listing_type: 0
    			Rental:
    				street_address: A2Cribs.UILayer.Rentals.street_address()
    				city: A2Cribs.UILayer.Rentals.city()
    				state: A2Cribs.UILayer.Rentals.state()
    				zipcode: A2Cribs.UILayer.Rentals.zipcode()
    				unit_style_options: A2Cribs.UILayer.Rentals.unit_style_options()
    				unit_style_type: A2Cribs.UILayer.Rentals.unit_style_type()
    				unit_style_description: A2Cribs.UILayer.Rentals.unit_style_description()
    				building_name: A2Cribs.UILayer.Rentals.building_name()
    				beds: A2Cribs.UILayer.Rentals.beds()
    				min_occupancy: A2Cribs.UILayer.Rentals.min_occupancy()
    				max_occupancy: 100
    				building_type: A2Cribs.UILayer.Rentals.building_type()
    				rent: A2Cribs.UILayer.Rentals.rent()
    				rent_negotiable: A2Cribs.UILayer.Rentals.rent_negotiable()
    				unit_count: A2Cribs.UILayer.Rentals.unit_count()
    				start_date: A2Cribs.UILayer.Rentals.start_date()
    				alternate_start_date: A2Cribs.UILayer.Rentals.alternate_start_date()
    				end_date: A2Cribs.UILayer.Rentals.end_date()
    				dates_negotiable: 0
    				available: A2Cribs.UILayer.Rentals.available()
    				baths: 555
    				air: A2Cribs.UILayer.Rentals.air()
    				parking_type: A2Cribs.UILayer.Rentals.parking_type()
    				parking_spots: A2Cribs.UILayer.Rentals.parking_spots()
    				street_parking: A2Cribs.UILayer.Rentals.street_parking()
    				furnished_type: A2Cribs.UILayer.Rentals.furnished_type()
    				pets_type: A2Cribs.UILayer.Rentals.pets_type()
    				smoking: A2Cribs.UILayer.Rentals.smoking()
    				tv: 1
    				balcony: 1
    				fridge: 1
    				storage: 1
    				square_feet: A2Cribs.UILayer.Rentals.square_feet()
    				year_built: A2Cribs.UILayer.Rentals.year_built()
    				pool: 1
    				hot_tub: 1
    				fitness_center: 1
    				game_room: 1
    				front_desk: 1
    				security_system: 1
    				tanning_beds: 1
    				study_lounge: 1
    				patio_deck: 1
    				yard_space: 1
    				elevator: 1
    				electric: A2Cribs.UILayer.Rentals.electric()
    				water: A2Cribs.UILayer.Rentals.water()
    				gas: A2Cribs.UILayer.Rentals.gas()
    				heat: A2Cribs.UILayer.Rentals.heat()
    				sewage: A2Cribs.UILayer.Rentals.sewage()
    				trash: A2Cribs.UILayer.Rentals.trash()
    				cable: A2Cribs.UILayer.Rentals.cable()
    				internet: A2Cribs.UILayer.Rentals.internet()
    				utility_total_flat_rate: A2Cribs.UILayer.Rentals.utility_total_flat_rate()
    				utility_estimate_winter: A2Cribs.UILayer.Rentals.utility_estimate_winter()
    				utility_estimate_summer: A2Cribs.UILayer.Rentals.utility_estimate_summer()
    				deposit: A2Cribs.UILayer.Rentals.deposit()
    				highlights: A2Cribs.UILayer.Rentals.highlights()
    				description: "Its a new listing!!!!!"
    				waitlist: A2Cribs.UILayer.Rentals.waitlist()
    				waitlist_open_date: A2Cribs.UILayer.Rentals.waitlist_open_date()
    				lease_office_address: A2Cribs.UILayer.Rentals.lease_office_address()
    				contact_email: A2Cribs.UILayer.Rentals.contact_email()
    				contact_phone: A2Cribs.UILayer.Rentals.contact_phone()
    				website: A2Cribs.UILayer.Rentals.website()
    			Image:
    				0:
    					image_id:275
    					caption: "herefdf"
    					is_primary: 0
    				1:
    					image_id:276
    					caption: "heres the second one"
    					is_primary: 1
    */

    Rental.Required_Fields = {
      unit_style_options: "overview_grid",
      beds: "overview_grid",
      baths: "features_grid",
      min_occupancy: "overview_grid",
      max_occupancy: "overview_grid",
      rent: "overview_grid",
      unit_count: "overview_grid",
      start_date: "overview_grid",
      lease_length: "overview_grid",
      available: "overview_grid",
      contact_email: "contact_grid",
      contact_phone: "contact_grid"
    };

    return Rental;

  })(A2Cribs.Object);

  A2Cribs.FilterManager = (function() {

    function FilterManager() {}

    FilterManager.MinRent = 0;

    FilterManager.MaxRent = 999999;

    FilterManager.MaxSliderRent = 2000;

    FilterManager.MinBeds = 0;

    FilterManager.MaxBeds = 999999;

    FilterManager.MaxSliderBeds = 10;

    FilterManager.DateBegin = 'NOT_SET';

    FilterManager.DateEnd = 'NOT_SET';

    FilterManager.Geocoder = null;

    FilterManager.UpdateListings = function(visibleListingIds) {
      var all_listings, all_markers, listing, listing_id, marker, visible_listings, visible_markers, _i, _j, _k, _len, _len2, _len3, _ref, _ref2;
      visible_listings = JSON.parse(visibleListingIds);
      if ((_ref = A2Cribs.HoverBubble) != null) _ref.Close();
      if ((_ref2 = A2Cribs.ClickBubble) != null) _ref2.Close();
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
      return A2Cribs.Map.GMarkerClusterer.repaint();
    };

    FilterManager.WheneverButtonClicked = function(event) {
      if ($("#startDate").datepicker().valueOf()[0].value === "Whenever") {
        A2Cribs.FilterManager.DateBegin = "NOT_SET";
      }
      if ($("#endDate").datepicker().valueOf()[0].value === "Whenever") {
        A2Cribs.FilterManager.DateEnd = "NOT_SET";
      }
      return A2Cribs.FilterManager.ApplyFilter();
    };

    /*
    	Called immediately after user applies a filter.
    	start_date, end_date, minRent, maxRent, beds, house, apt, unit_type_other, male, female, students_only, grad, undergrad,
    	bathroom_type, ac, parking, utilities_included, no_security_deposit
    */

    FilterManager.ApplyFilter = function(event, ui) {
      var ac, ajaxData, apt, bathroom_type, beds, eventDate, female, grad, house, male, no_security_deposit, other, parking, students_only, undergrad, utilities;
      A2Cribs.Map.ClickBubble.Close();
      ajaxData = null;
      house = $("#houseCheck").is(':checked');
      ajaxData = "house=" + house;
      apt = $("#aptCheck").is(':checked');
      ajaxData += "&apt=" + apt;
      other = $("#otherCheck").is(':checked');
      ajaxData += "&unit_type_other=" + other;
      male = $("#maleCheck").is(':checked');
      ajaxData += "&male=" + male;
      female = $("#femaleCheck").is(':checked');
      ajaxData += "&female=" + female;
      students_only = $("#studentsOnlyCheck").is(':checked');
      ajaxData += "&students_only=" + students_only;
      grad = $("#gradCheck").is(':checked');
      ajaxData += "&grad=" + grad;
      undergrad = $("#undergradCheck").is(':checked');
      ajaxData += "&undergrad=" + undergrad;
      ac = $("#acCheck").is(':checked');
      ajaxData += "&ac=" + ac;
      parking = $("#parkingCheck").is(':checked');
      ajaxData += "&parking=" + parking;
      utilities = $("#utilitiesCheck").is(':checked');
      ajaxData += "&utilities_included=" + utilities;
      no_security_deposit = $("#noSecurityDepositCheck").is(':checked');
      ajaxData += "&no_security_deposit=" + no_security_deposit;
      beds = $("#bedsSelect").val();
      if (beds === "2+") beds = "2";
      ajaxData += "&beds=" + beds;
      bathroom_type = $("#bathSelect").val();
      ajaxData += "&bathroom_type=" + bathroom_type;
      if (event.target !== void 0 && event.target.id === "slider") {
        A2Cribs.FilterManager.MinRent = event.value[0];
        A2Cribs.FilterManager.MaxRent = event.value[1];
        if (A2Cribs.FilterManager.MaxRent === A2Cribs.FilterManager.MaxSliderRent) {
          A2Cribs.FilterManager.MaxRent = 999999;
        }
      }
      if (event.target !== void 0 && event.target.id === "startDate") {
        eventDate = event.valueOf().date;
        if (A2Cribs.FilterManager.DateEnd !== "NOT_SET" && A2Cribs.FilterManager.DateEnd !== "Whenever" && eventDate > new Date(A2Cribs.FilterManager.DateEnd)) {
          A2Cribs.UIManager.Alert("Start Date cannot occur after End Date.");
          A2Cribs.FilterManager.DateBegin = new Date(A2Cribs.FilterManager.DateEnd);
          A2Cribs.FilterManager.StartDateObject.setValue(A2Cribs.FilterManager.DateBegin);
          return;
        }
        A2Cribs.FilterManager.DateBegin = A2Cribs.FilterManager.GetFormattedDate(eventDate);
      }
      if (event.target !== void 0 && event.target.id === "endDate") {
        eventDate = event.valueOf().date;
        if (A2Cribs.FilterManager.DateBegin !== "NOT_SET" && A2Cribs.FilterManager.DateBegin !== "Whenever" && eventDate < new Date(A2Cribs.FilterManager.DateBegin)) {
          A2Cribs.UIManager.Alert("End Date cannot occur before Start Date.");
          A2Cribs.FilterManager.DateEnd = new Date(A2Cribs.FilterManager.DateBegin);
          A2Cribs.FilterManager.EndDateObject.setValue(A2Cribs.FilterManager.DateEnd);
          return;
        }
        A2Cribs.FilterManager.DateEnd = A2Cribs.FilterManager.GetFormattedDate(event.valueOf().date);
      }
      ajaxData += "&min_rent=" + A2Cribs.FilterManager.MinRent;
      ajaxData += "&max_rent=" + A2Cribs.FilterManager.MaxRent;
      ajaxData += "&start_date=" + A2Cribs.FilterManager.DateBegin;
      ajaxData += "&end_date=" + A2Cribs.FilterManager.DateEnd;
      return $.ajax({
        url: myBaseUrl + "Sublets/ApplyFilter",
        type: "GET",
        data: ajaxData,
        context: this,
        success: A2Cribs.FilterManager.UpdateMarkers
      });
    };

    FilterManager.GetFormattedDate = function(date) {
      var day, month, year;
      year = date.getUTCFullYear();
      month = date.getMonth() + 1;
      day = date.getDate();
      return year + '-' + month + '-' + day;
    };

    FilterManager.GetTodaysDate = function() {
      var dd, mm, today, yyyy;
      today = new Date();
      dd = today.getDate();
      mm = today.getMonth() + 1;
      yyyy = today.getUTCFullYear();
      if (dd < 10) dd = '0' + dd;
      if (mm < 10) mm = '0' + mm;
      today = mm + '-' + dd + '-' + yyyy;
      return new Date(today);
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
      return this.div.find("input[type='checkbox']").change(function(event) {
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
    };

    /*
    	Creates all listeners and jquery events for RentalFilter
    */

    RentalFilter.SetupUI = function() {
      var _this = this;
      this.div = $("#map_filter");
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
        lastTab.find(".filter_title").hide();
        lastTab.find(".filter_content").hide();
        lastTab.find(".filter_preview").show();
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
      return $.ajax({
        url: myBaseUrl + "Rentals/ApplyFilter",
        data: ajaxData,
        type: "GET",
        context: this,
        success: A2Cribs.FilterManager.UpdateListings
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

    RentalFilter.GetBeds = function() {
      var beds;
      beds = [3, 5, 6, 10];
      return JSON.stringify(beds);
    };

    RentalFilter.GetRent = function() {
      var rent;
      return this;
      rent = {
        "min": 100,
        "max": 5000
      };
      return JSON.stringify(rent);
    };

    RentalFilter.GetMonths = function() {
      var dates;
      dates = {
        "months": {
          "1": 1,
          "2": 0,
          "3": 1,
          "4": 0,
          "5": 1,
          "6": 0,
          "7": 1,
          "8": 0,
          "9": 1,
          "10": 0,
          "11": 1,
          "12": 0
        },
        "curYear": [13, 14],
        "leaseLength": {
          'min': 2,
          'max': 4
        }
      };
      return dates;
    };

    RentalFilter.GetUnitTypes = function() {
      var unit_types;
      return unit_types = {
        "house": 0,
        "apartment": 1,
        "duplex": 1,
        "other": 0
      };
    };

    RentalFilter.GetAmenities = function() {
      var amenities;
      return amenities = {
        'elevator': 1
      };
    };

    RentalFilter.prototype.FilterRent = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterBeds = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterBaths = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterBuildingType = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterDates = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterUnitFeatures = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterParking = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterPets = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterAmenities = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterSquareFeet = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterYearBuilt = function(listing) {
      return true;
    };

    RentalFilter.prototype.FilterUtilities = function(listing) {
      return true;
    };

    return RentalFilter;

  })(A2Cribs.FilterManager);

  A2Cribs.PostSubletProgress = (function() {

    function PostSubletProgress(Content, CurrentStep) {
      this.Content = Content;
      this.CurrentStep = CurrentStep != null ? CurrentStep : 0;
      this.MaxSteps = $('.prog-step').length - 1;
      this.updatePositionUI();
    }

    PostSubletProgress.prototype.reset = function() {
      this.CurrentStep = 0;
      return this.updatePositionUI();
    };

    PostSubletProgress.prototype.next = function() {
      if (this.CurrentStep === this.MaxSteps) return;
      this.CurrentStep++;
      return this.updatePositionUI();
    };

    PostSubletProgress.prototype.prev = function() {
      if (this.CurrentState === 0) return;
      this.CurrentStep--;
      return this.updatePositionUI();
    };

    PostSubletProgress.prototype.updatePositionUI = function() {
      var completed_step, current_step, incomplete_step,
        _this = this;
      current_step = '<i class="step-state current-step icon-circle-blank"></i>';
      completed_step = '<i class="step-state icon-circle background-icon"></i>\
            <i class="step-state complete-step icon-ok-sign"></i>';
      incomplete_step = '<i class="step-state incomplete-step icon-circle"></i>';
      return $('.prog-step > div:first-child').each(function(index, prog_step) {
        $(prog_step).find('.step-state').remove('.step-state');
        if (index < _this.CurrentStep) {
          return $(prog_step).append(completed_step);
        } else if (index === _this.CurrentStep) {
          return $(prog_step).append(current_step);
        } else {
          return $(prog_step).append(incomplete_step);
        }
      });
    };

    return PostSubletProgress;

  })();

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
      return $('#fl-search-icon').click(function() {
        return $("#listings_list div").show().filter(function() {
          if ($(this).text().toLowerCase().indexOf($("#fl-list-input").val().toLowerCase()) === -1) {
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

  A2Cribs.EditSublet = (function(_super) {

    __extends(EditSublet, _super);

    function EditSublet() {
      this.div = $('#edit_sublet_window');
      this.setupUI();
    }

    EditSublet.prototype.setupUI = function() {
      var _this = this;
      this.div.find(".step-button").click(function(event) {
        _this.div.find(".step-button").removeClass("active");
        $(event.currentTarget).closest(".step-button").addClass("active");
        return _this.GotoStep($(event.currentTarget).closest(".step-button").attr("step"));
      });
      return EditSublet.__super__.setupUI.call(this, this.div);
    };

    EditSublet.prototype.Reset = function() {
      this.div.find('.step').eq(0).show().siblings().hide();
      this.div.find(".step-button").removeClass("active");
      this.div.find('.step-button').eq(0).addClass("active");
      return EditSublet.__super__.Reset.call(this, this.div);
    };

    EditSublet.prototype.Edit = function(sublet_id) {
      var _this = this;
      return $.ajax({
        url: myBaseUrl + "Sublets/getSubletDataById/" + sublet_id,
        type: "GET",
        success: function(subletData) {
          _this.Close();
          subletData = JSON.parse(subletData);
          if (subletData.redirect != null) window.location = subletData.redirect;
          _this.MiniMap.SetMarkerPosition(new google.maps.LatLng(subletData.Marker.latitude, subletData.Marker.longitude));
          _this.PopulateInputFields(subletData);
          _this.PhotoManager.LoadImages(subletData.Image);
          _this.DisableInputFields();
          return _this.Open();
        },
        error: function() {
          return A2Cribs.UIManager.Alert("An error occured while loading your sublet data, please try again.");
        }
      });
    };

    EditSublet.prototype.Save = function() {
      if (this.Validate()) {
        return EditSublet.__super__.Save.call(this, this.GetSubletObject());
      }
    };

    EditSublet.prototype.Delete = function(sublet_id) {
      return alertify.confirm("Are you sure you want to delete this property? This can't be undone.", function(e) {
        var url;
        if (e) {
          url = myBaseUrl + ("sublets/remove/" + sublet_id);
          return window.location.href = url;
        }
      });
    };

    EditSublet.prototype.GetSubletObject = function() {
      return EditSublet.__super__.GetSubletObject.call(this, this.div);
    };

    EditSublet.prototype.Close = function() {
      this.Reset();
      return this.div.parent().hide();
    };

    EditSublet.prototype.Open = function() {
      var _this = this;
      return this.div.parent().show('slow', function() {
        return _this.MiniMap.Resize();
      });
    };

    EditSublet.prototype.PopulateInputFields = function(subletData) {
      var input, k, p, q, v, _results;
      _results = [];
      for (k in subletData) {
        v = subletData[k];
        _results.push((function() {
          var _results2;
          _results2 = [];
          for (p in v) {
            q = v[p];
            console.log(k + "_" + p);
            input = this.div.find("#" + k + "_" + p);
            if (input != null) {
              if ("checkbox" === input.attr("type")) {
                input.prop("checked", q);
              } else if (input.hasClass("date_field")) {
                input.val(this.GetFormattedDate(new Date(q)));
              } else if (typeof q === 'boolean') {
                input.val(+q);
              } else {
                input.val(q);
              }
              if (k === "Marker") {
                _results2.push(input.prop('disabled', true));
              } else {
                _results2.push(void 0);
              }
            } else {
              _results2.push(void 0);
            }
          }
          return _results2;
        }).call(this));
      }
      return _results;
    };

    EditSublet.prototype.DisableInputFields = function() {
      this.MiniMap.SetEnabled(false);
      this.div.find('#place_map_button').prop('disabled', true);
      return this.div.find('#University_name').prop('disabled', true);
    };

    EditSublet.prototype.GotoStep = function(step) {
      if (this.Validate()) {
        return this.div.find('.step').eq(step).show().siblings().hide();
      }
    };

    EditSublet.prototype.Validate = function() {
      return EditSublet.__super__.Validate.call(this, 3);
    };

    return EditSublet;

  })(A2Cribs.SubletSave);

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

  A2Cribs.PostSublet = (function(_super) {

    __extends(PostSublet, _super);

    function PostSublet() {
      this.div = $('#post-sublet-modal');
      this.currentStep = 0;
      /* INIT STEPS
      */
      this.setupUI();
    }

    PostSublet.prototype.setupUI = function() {
      var _this = this;
      this.ProgressBar = new A2Cribs.PostSubletProgress($('.post-sublet-progress'), this.currentStep);
      this.div.find("#address-step").siblings().hide();
      this.div.find(".next-btn").click(function(event) {
        if (_this.Validate(_this.currentStep + 1)) {
          $(event.currentTarget).closest(".step").hide().next(".step").show();
          _this.currentStep++;
          return _this.ProgressBar.next();
        }
      });
      this.div.find(".back-btn").click(function(event) {
        $(event.currentTarget).closest(".step").hide().prev(".step").show();
        _this.currentStep--;
        return _this.ProgressBar.prev();
      });
      this.div.on("shown", function() {
        return _this.MiniMap.Resize();
      });
      this.div.find("#University_name").focusout(function() {
        _this.FindSelectedUniversity(_this.div);
        if (_this.SelectedUniversity != null) {
          return _this.MiniMap.CenterMap(_this.SelectedUniversity.latitude, _this.SelectedUniversity.longitude);
        }
      });
      this.div.find(".post-btn").click(function() {
        return _this.Save();
      });
      this.InitUniversityAutocomplete();
      return PostSublet.__super__.setupUI.call(this, this.div);
    };

    PostSublet.prototype.Reset = function() {
      this.ProgressBar.reset();
      this.div.find('.step').eq(0).show();
      this.div.find('.step').eq(0).siblings().hide();
      this.currentStep = 0;
      return PostSublet.__super__.Reset.call(this, this.div);
    };

    PostSublet.prototype.Save = function() {
      if (this.Validate()) {
        return PostSublet.__super__.Save.call(this, this.GetSubletObject(), this.SaveRedirect);
      }
    };

    PostSublet.prototype.SaveRedirect = function(new_id) {
      return window.location.replace("/sublet/" + new_id);
    };

    PostSublet.prototype.Validate = function(step_) {
      if (step_ == null) step_ = 3;
      return PostSublet.__super__.Validate.call(this, step_, this.div);
    };

    PostSublet.prototype.GetSubletObject = function() {
      return PostSublet.__super__.GetSubletObject.call(this, this.div);
    };

    PostSublet.prototype.InitUniversityAutocomplete = function() {
      var _this = this;
      if (A2Cribs.Cache.SchoolList != null) {
        this.div.find("#University_name").typeahead({
          source: A2Cribs.Cache.SchoolList
        });
        return;
      }
      return $.ajax({
        url: "/University/getAll",
        success: function(response) {
          var university, _i, _len, _ref;
          A2Cribs.Cache.universitiesMap = JSON.parse(response);
          A2Cribs.Cache.SchoolList = [];
          A2Cribs.Cache.SchoolIDList = [];
          _ref = A2Cribs.Cache.universitiesMap;
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            university = _ref[_i];
            A2Cribs.Cache.SchoolList.push(university.University.name);
            A2Cribs.Cache.SchoolIDList.push(university.University.id);
          }
          return _this.div.find("#University_name").typeahead({
            source: A2Cribs.Cache.SchoolList
          });
        }
      });
    };

    PostSublet.prototype.FindAddress = function() {
      var address, addressObj,
        _this = this;
      if (this.SelectedUniversity != null) {
        address = this.div.find("#Marker_street_address").val();
        addressObj = {
          'address': address + " " + this.SelectedUniversity.city + ", " + this.SelectedUniversity.state
        };
        return A2Cribs.Geocoder.geocode(addressObj, function(response, status) {
          var component, street_name, street_number, type, _i, _j, _len, _len2, _ref, _ref2;
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
                    _this.div.find('#Marker_city').val(component.short_name);
                    break;
                  case "administrative_area_level_1":
                    _this.div.find('#Marker_state').val(component.short_name);
                    break;
                  case "postal_code":
                    _this.div.find('#Marker_zip').val(component.short_name);
                }
              }
            }
            if (!(street_number != null)) {
              A2Cribs.UIManager.Alert("Entered street address is not valid.");
              $("#Marker_street_address").text("");
              return;
            }
            _this.MiniMap.SetMarkerPosition(response[0].geometry.location);
            _this.div.find("#Marker_street_address").val(street_number + " " + street_name);
            _this.div.find("#Marker_latitude").val(response[0].geometry.location.lat());
            return _this.div.find("#Marker_longitude").val(response[0].geometry.location.lng());
          }
        });
      }
    };

    PostSublet.prototype.FindSelectedUniversity = function() {
      var index, selected;
      selected = this.div.find("#University_name").val();
      index = A2Cribs.Cache.SchoolList.indexOf(selected);
      if (index >= 0) {
        this.SelectedUniversity = A2Cribs.Cache.universitiesMap[index].University;
        return this.div.find("#Sublet_university_id").val(A2Cribs.Cache.SchoolIDList[index]);
      } else {
        return this.SelectedUniversity = null;
      }
    };

    return PostSublet;

  })(A2Cribs.SubletSave);

  A2Cribs.Map = (function() {

    function Map() {}

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
    	Load all markers from Markers table
    */

    Map.LoadMarkers = function() {
      if (!this.MarkerDeferred) this.MarkerDeferred = new $.Deferred();
      if (A2Cribs.Map.CurentSchoolId === void 0) {
        this.MarkerDeferred.resolve(null);
        return;
      }
      $.ajax({
        url: myBaseUrl + "Map/LoadMarkers/" + A2Cribs.Map.CurentSchoolId + "/" + 0,
        type: "GET",
        context: this,
        success: function(response) {
          return this.MarkerDeferred.resolve(response, this);
        },
        error: function() {
          return this.MarkerDeferred.resolve(null);
        }
      });
      return this.MarkerDeferred.promise();
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

    Map.Init = function(school_id, latitude, longitude, city, state, school_name, active_listing_type) {
      var imageStyles, mcOptions, zoom,
        _this = this;
      this.CurentSchoolId = school_id;
      mixpanel.register({
        'preferred_university': school_id
      });
      A2Cribs.FilterManager.CurrentCity = city;
      A2Cribs.FilterManager.CurrentState = state;
      A2Cribs.FilterManager.CurrentSchool = school_name;
      this.ACTIVE_LISTING_TYPE = active_listing_type;
      zoom = 15;
      this.MapCenter = new google.maps.LatLng(latitude, longitude);
      this.MapOptions = {
        zoom: zoom,
        center: A2Cribs.Map.MapCenter,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: this.style,
        panControl: false,
        streetViewControl: false,
        mapTypeControl: false
      };
      A2Cribs.Map.GMap = new google.maps.Map(document.getElementById('map_canvas'), A2Cribs.Map.MapOptions);
      google.maps.event.addListener(A2Cribs.Map.GMap, 'idle', A2Cribs.Map.ShowMarkers);
      google.maps.event.addListener(A2Cribs.Map.GMap, 'center_changed', function() {
        return A2Cribs.ClickBubble.Close();
      });
      /*imageStyles = [
      			{
      				"url": "/img/dots/group_dot.png",
      			}
      		]
      */
      imageStyles = [
        {
          height: 48,
          url: '/img/dots/group_dot.png',
          width: 48,
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
      this.GMarkerClusterer.ignoreHidden_ = true;
      A2Cribs.ClickBubble.Init(this.GMap);
      A2Cribs.HoverBubble.Init(this.GMap);
      A2Cribs.Map.InitBoundaries();
      this.LoadAllMapData();
      A2Cribs.MarkerTooltip.Init();
      return A2Cribs.FilterManager.InitAddressSearch();
    };

    Map.LoadBasicData = function() {
      var _this = this;
      if (!(this.BasicDataDeferred != null)) {
        this.BasicDataDeferred = new $.Deferred();
      }
      $.ajax({
        url: myBaseUrl + ("Map/GetBasicData/" + this.ACTIVE_LISTING_TYPE + "/" + this.CurentSchoolId),
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
      var all_listings, all_markers, key, listing, listings, marker, value, _i, _j, _k, _len, _len2, _len3, _results;
      if (response === null || response === void 0) return;
      listings = JSON.parse(response);
      for (_i = 0, _len = listings.length; _i < _len; _i++) {
        listing = listings[_i];
        for (key in listing) {
          value = listing[key];
          A2Cribs.UserCache.Set(new A2Cribs[key](value));
        }
      }
      Map.BasicDataCached.resolve();
      all_markers = A2Cribs.UserCache.Get("marker");
      for (_j = 0, _len2 = all_markers.length; _j < _len2; _j++) {
        marker = all_markers[_j];
        marker.Init();
        Map.GMarkerClusterer.addMarker(marker.GMarker);
      }
      all_listings = A2Cribs.UserCache.Get("listings");
      _results = [];
      for (_k = 0, _len3 = all_listings.length; _k < _len3; _k++) {
        listing = all_listings[_k];
        _results.push(listing.visible = true);
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
      basicData = this.LoadBasicData();
      this.BasicDataCached = new $.Deferred();
      A2Cribs.FavoritesManager.LoadFavorites();
      $.when(basicData).then(this.LoadBasicDataCallback);
      return A2Cribs.FeaturedListings.InitializeSidebar(this.CurentSchoolId, this.ACTIVE_LISTING_TYPE, basicData, this.BasicDataCached);
    };

    Map.CenterMap = function(latitude, longitude) {
      if (!(this.GMap != null)) return;
      return this.GMap.setCenter(new google.maps.LatLng(latitude, longitude));
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

    function MarkerModal() {
      this.TriggerMarkerUpdated = __bind(this.TriggerMarkerUpdated, this);
      this.TriggerMarkerAdded = __bind(this.TriggerMarkerAdded, this);      this.modal = $('#marker-modal');
      this.setupUI();
      this.ListingType = "Rental";
    }

    MarkerModal.prototype.Clear = function() {
      this.modal.find("#marker_select_container").show();
      this.modal.find("input").val("");
      this.modal.find('select option:first-child').attr("selected", "selected");
      return this.MiniMap.SetMarkerVisible(false);
    };

    MarkerModal.prototype.MarkerValidate = function() {
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
        A2Cribs.UIManager.Error("Your alternate name is too long.");
        this.modal.find('#Marker_alternate_name').parent().addClass("error");
        isValid = false;
      }
      return isValid;
    };

    MarkerModal.prototype.Save = function(trigger) {
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
        A2Cribs.MixPanel.PostListing("Marker Save", {
          "marker id": marker_id,
          "alternate name": this.modal.find('#Marker_alternate_name').val(),
          "building type id": this.modal.find('#Marker_building_type_id').val(),
          "street address": this.modal.find('#Marker_street_address').val(),
          "city": this.modal.find('#Marker_city').val(),
          "state": this.modal.find('#Marker_state').val(),
          "zip": this.modal.find('#Marker_zip').val(),
          "latitude": latLng['latitude'],
          "longitude": latLng['longitude']
        });
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
              A2Cribs.MixPanel.PostListing("Marker Save Complete", {
                "marker id": marker_object.marker_id
              });
              A2Cribs.UserCache.Set(new A2Cribs.Marker(marker_object));
              return trigger(marker_object.marker_id);
            }
          }
        });
      }
    };

    MarkerModal.prototype.setupUI = function() {
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
        A2Cribs.MixPanel.PostListing("Marker Selected", {
          "new marker": false,
          "marker_id": marker_selected
        });
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
          A2Cribs.MixPanel.PostListing("Marker Selected", {
            "new marker": true
          });
          return _this.Save();
        } else if (marker_selected !== "0") {
          marker = A2Cribs.UserCache.Get("marker", marker_selected);
          A2Cribs.MixPanel.PostListing("Marker Selected", {
            "new marker": false,
            "marker id": marker_selected,
            "marker name": marker != null ? marker.GetName() : void 0,
            "marker address": marker != null ? marker.street_address : void 0,
            "marker city": marker != null ? marker.city : void 0,
            "marker state": marker != null ? marker.state : void 0
          });
          _this.modal.modal("hide");
          return _this.TriggerMarkerAdded(marker_selected);
        }
      });
      return this.MiniMap = new A2Cribs.MiniMap(this.modal);
    };

    MarkerModal.prototype.Open = function() {
      return this.modal.modal('show');
    };

    MarkerModal.prototype.NewMarker = function() {
      var marker, markers, name, option, _i, _len,
        _this = this;
      this.Clear();
      this.modal.find('#marker_add').hide();
      this.modal.find("#continue-button").addClass("disabled");
      this.modal.find("#continue-button").text("Continue");
      this.modal.find(".title").text("Create a New Listing");
      markers = A2Cribs.UserCache.Get("marker");
      this.modal.find("#marker_select").empty();
      this.modal.find("#marker_select").append('<option value="0">--</option>\
			<option value="new_marker"><strong>New Location</strong></option>');
      this.modal.find("#continue-button").unbind('click');
      this.modal.find("#continue-button").click(function() {
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

    MarkerModal.prototype.LoadMarker = function(marker_id) {
      var key, marker, value,
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
      return this.FindAddress(this.modal);
    };

    MarkerModal.prototype.TriggerMarkerAdded = function(marker_id) {
      return $('body').trigger("" + this.ListingType + "_marker_added", [marker_id]);
    };

    MarkerModal.prototype.TriggerMarkerUpdated = function(marker_id) {
      return $('body').trigger("" + this.ListingType + "_marker_updated", [marker_id]);
    };

    MarkerModal.prototype.FindAddress = function(div) {
      var addressObj, latLng,
        _this = this;
      if (this.MarkerValidate()) {
        if (div.find("#Marker_latitude").val() && div.find("#Marker_longitude").val()) {
          latLng = new google.maps.LatLng(div.find("#Marker_latitude").val(), div.find("#Marker_longitude").val());
          this.MiniMap.SetMarkerPosition(latLng);
          return;
        }
        addressObj = {
          address: div.find("#Marker_street_address").val() + " " + div.find("#Marker_city").val() + ", " + div.find("#Marker_state").val()
        };
        return A2Cribs.Geocoder.geocode(addressObj, function(response, status) {
          var component, street_name, street_number, type, _i, _j, _len, _len2, _ref, _ref2;
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
                    div.find('#Marker_city').val(component.short_name);
                    break;
                  case "administrative_area_level_1":
                    div.find('#Marker_state').val(component.short_name);
                    break;
                  case "postal_code":
                    div.find('#Marker_zip').val(component.short_name);
                }
              }
            }
            if (!(street_number != null)) {
              A2Cribs.UIManager.Alert("Entered street address is not valid.");
              $("#Marker_street_address").text("");
              return;
            }
            _this.MiniMap.SetMarkerPosition(response[0].geometry.location);
            div.find("#Marker_street_address").val(street_number + " " + street_name);
            div.find("#Marker_latitude").val(response[0].geometry.location.lat());
            return div.find("#Marker_longitude").val(response[0].geometry.location.lng());
          }
        });
      }
    };

    return MarkerModal;

  })();

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

  A2Cribs.RentalSave = (function() {

    function RentalSave(dropdown_content) {
      this.SaveImages = __bind(this.SaveImages, this);      this.div = $('.rentals-content');
      this.EditableRows = [];
      this.Editable = false;
      this.VisibleGrid = 'overview_grid';
      this.SetupUI(dropdown_content);
      this.NextListing;
    }

    RentalSave.prototype.SetupUI = function(dropdown_content) {
      if (!(A2Cribs.Geocoder != null)) {
        A2Cribs.Geocoder = new google.maps.Geocoder();
      }
      $('#middle_content').height();
      this.div.find("grid-pane").height;
      this.CreateCallbacks();
      return this.CreateGrids(dropdown_content);
    };

    RentalSave.prototype.CreateCallbacks = function() {
      var _this = this;
      $('body').on("Rental_marker_added", function(event, marker_id) {
        var list_item, name;
        if ($("#rentals_list_content").find("#" + marker_id).length === 0) {
          name = A2Cribs.UserCache.Get("marker", marker_id).GetName();
          list_item = $("<li />", {
            text: name,
            "class": "rentals_list_item",
            id: marker_id
          });
          $("#rentals_list_content").append(list_item);
          $("#rentals_list_content").slideDown();
        }
        A2Cribs.Dashboard.Direct({
          classname: 'rentals',
          data: true
        });
        _this.Open(marker_id);
        return _this.AddNewUnit();
      });
      $('body').on("Rental_marker_updated", function(event, marker_id) {
        var list_item, name;
        if ($("#rentals_list_content").find("#" + marker_id).length === 1) {
          list_item = $("#rentals_list_content").find("#" + marker_id);
          name = A2Cribs.UserCache.Get("marker", marker_id).GetName();
          list_item.text(name);
          return _this.CreateListingPreview(marker_id);
        }
      });
      $("body").on('click', '.rentals_list_item', function(event) {
        return _this.Open(event.target.id);
      });
      this.div.find(".edit_marker").click(function() {
        A2Cribs.MixPanel.PostListing("Started", {});
        A2Cribs.MarkerModal.Open();
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
        var listings, row, selected, _i, _len;
        selected = _this.GridMap[_this.VisibleGrid].getSelectedRows();
        _this.FinishEditing();
        if (selected.length) {
          listings = [];
          for (_i = 0, _len = selected.length; _i < _len; _i++) {
            row = selected[_i];
            if (_this.GridMap[_this.VisibleGrid].getDataItem(row).listing_id != null) {
              listings.push(_this.GridMap[_this.VisibleGrid].getDataItem(row).listing_id);
            }
          }
          return _this.Delete(selected, listings);
        }
      });
      $(".rentals_tab").click(function(event) {
        var row, selected, _i, _len, _ref;
        _this.CommitSlickgridChanges();
        selected = _this.GridMap[_this.VisibleGrid].getSelectedRows();
        _this.VisibleGrid = $(event.target).attr("href").substring(1);
        A2Cribs.MixPanel.PostListing("" + _this.VisibleGrid + " selected", {
          "marker id": _this.CurrentMarker
        });
        _this.GridMap[_this.VisibleGrid].setSelectedRows(selected);
        _ref = _this.EditableRows;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          row = _ref[_i];
          _this.Validate(row);
        }
        return $(event.target).removeClass("highlight-tab");
      });
      return $(".rentals-content").on("shown", function(event) {
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

    RentalSave.prototype.FinishEditing = function() {
      var data, row, _i, _len, _ref;
      this.CommitSlickgridChanges();
      $("#rentals_edit").text("Edit");
      $(".rentals_tab").removeClass("highlight-tab");
      _ref = this.EditableRows;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        row = _ref[_i];
        data = this.GridMap[this.VisibleGrid].getDataItem(row);
        data.editable = false;
      }
      this.GridMap[this.VisibleGrid].setSelectedRows(this.EditableRows);
      this.EditableRows = [];
      return this.Editable = false;
    };

    RentalSave.prototype.Open = function(marker_id) {
      var _this = this;
      return $.ajax({
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
          A2Cribs.Dashboard.ShowContent($(".rentals-content"), true);
          return _this.PopulateGrid(marker_id);
        }
      });
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
        $("a[href='#" + tab + "']").addClass("highlight-tab");
      }
      $("a[href='#" + this.VisibleGrid + "']").removeClass("highlight-tab");
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
      if (((_ref2 = rental_object.Image) != null ? _ref2.length : void 0) === 0 && (data.Image != null)) {
        rental_object.Image = data.Image;
      }
      return rental_object;
    };

    RentalSave.prototype.Save = function(row) {
      var rental_object,
        _this = this;
      if (this.Validate(row)) {
        rental_object = this.GetObjectByRow(row);
        A2Cribs.MixPanel.PostListing("Listing Save", {
          "save type": rental_object.listing_id != null ? "edit" : "save",
          "marker id": this.CurrentMarker,
          "listing id": rental_object.listing_id
        });
        return $.ajax({
          url: myBaseUrl + "listings/Save/",
          type: "POST",
          data: rental_object,
          success: function(response) {
            var key, value;
            response = JSON.parse(response);
            if (response.listing_id != null) {
              A2Cribs.MixPanel.PostListing("Listing Save Completed", {
                "listing id": response.listing_id,
                "marker id": _this.CurrentMarker
              });
              A2Cribs.UIManager.Success("Save successful!");
              rental_object.Listing.listing_id = response.listing_id;
              rental_object.Rental.listing_id = response.listing_id;
              for (key in rental_object) {
                value = rental_object[key];
                if (A2Cribs[key] != null) {
                  A2Cribs.UserCache.Set(new A2Cribs[key](value));
                }
              }
              return console.log(response);
            } else {
              A2Cribs.UIManager.Error(response.error.message);
              return console.log(response);
            }
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
      var data, images;
      data = this.GridMap[this.VisibleGrid].getDataItem(row);
      images = data.listing_id != null ? A2Cribs.UserCache.Get("image", data.listing_id) : data.Image;
      A2Cribs.MixPanel.PostListing("Start Photo Editing", {
        "marker id": this.CurrentMarker,
        "number of images": images != null ? images.length : void 0
      });
      return A2Cribs.PhotoManager.LoadImages(images, row, this.SaveImages);
    };

    /*
    	Saves the images in either the cache or temp object in slickgrid
    */

    RentalSave.prototype.SaveImages = function(row, images) {
      var data, image, _i, _len;
      data = this.GridMap[this.VisibleGrid].getDataItem(row);
      if (data.listing_id != null) {
        for (_i = 0, _len = images.length; _i < _len; _i++) {
          image = images[_i];
          image.listing_id = data.listing_id;
        }
        A2Cribs.UserCache.Set(new A2Cribs.Image(images));
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
      var container, data, grid, row, row_number, _i, _len, _ref, _ref2, _results;
      A2Cribs.MixPanel.PostListing("Add New Unit", {
        "marker id": this.CurrentMarker
      });
      this.GridMap[this.VisibleGrid].getEditorLock().commitCurrentEdit();
      data = this.GridMap[this.VisibleGrid].getData();
      _ref = this.EditableRows;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        row = _ref[_i];
        data[row].editable = false;
      }
      row_number = data.length;
      this.EditableRows = [row_number];
      data.push({
        editable: true
      });
      this.GridMap[this.VisibleGrid].setSelectedRows(this.EditableRows);
      $("#rentals_edit").text("Finish Editing");
      this.Validate(row_number);
      _ref2 = this.GridMap;
      _results = [];
      for (container in _ref2) {
        grid = _ref2[container];
        grid.updateRowCount();
        _results.push(grid.render());
      }
      return _results;
    };

    RentalSave.prototype.PopulateGrid = function(marker_id) {
      var data, grid, key, listing, rental, rentals, _i, _len, _ref, _results;
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
            editor: A2Cribs.Editors.Dropdown(["0 months", "1 month", "2 months", "3 months", "4 months", "5 months", "6 months", "7 months", "8 months", "9 months", "10 months", "11 months", "12 months", "13 months"]),
            formatter: A2Cribs.Formatters.Dropdown(["0 months", "1 month", "2 months", "3 months", "4 months", "5 months", "6 months", "7 months", "8 months", "9 months", "10 months", "11 months", "12 months", "13 months"], true)
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
            formatter: A2Cribs.Formatters.RequiredText
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
            editor: Slick.Editors.Integer,
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
            editor: Slick.Editors.LongText,
            formatter: A2Cribs.Formatters.Text
          }, {
            id: "description",
            name: "Description",
            field: "description",
            editor: Slick.Editors.LongText,
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

    function Landing() {}

    Landing.Init = function(locations) {
      var location, that, _i, _len;
      this.schoolList = Array();
      for (_i = 0, _len = locations.length; _i < _len; _i++) {
        location = locations[_i];
        this.schoolList.push(location.University.name);
      }
      that = this;
      $(function() {
        return $(".typeahead").typeahead({
          source: that.schoolList
        });
      });
      return $(".typeahead").val("University of Michigan-Ann Arbor");
    };

    Landing.Submit = function() {
      var location;
      location = $("#search-text").val();
      if (__indexOf.call(this.schoolList, location) < 0) {
        A2Cribs.UIManager.Error(location + " is not a valid location.");
        return false;
      }
      return window.location = $('#sublet-redirect').attr('href') + "/" + location.split(' ').join('_');
    };

    return Landing;

  })();

  A2Cribs.Login = (function() {
    var createUser, validate,
      _this = this;

    function Login() {}

    Login.LANDING_URL = "cribspot.com";

    Login.HTTP_PREFIX = "http://";

    Login.setupUI = function() {
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
      this.div.find("#login_content").submit(function(event) {
        return _this.cribspotLogin(event.delegateTarget);
      });
      this.div.find("#student_submit").click(this.CreateStudent);
      this.div.find("#student_signup").submit(this.CreateStudent);
      this.div.find("#pm_submit").click(this.CreatePropertyManager);
      return this.div.find("#pm_signup").submit(this.CreatePropertyManager);
    };

    Login.cribspotLogin = function(div) {
      var request_data, url,
        _this = this;
      url = myBaseUrl + "users/AjaxLogin";
      request_data = {
        User: {
          email: $(div).find('#inputEmail').val(),
          password: $(div).find('#inputPassword').val()
        }
      };
      if ((request_data.User.email != null) && (request_data.User.password != null)) {
        $.post(url, request_data, function(response) {
          var data;
          data = JSON.parse(response);
          if (data.error != null) {
            if (data.error_type === "EMAIL_UNVERIFIED") {
              return A2Cribs.UIManager.Confirm("Your email address has not yet been confirmed. 							Please click the link provided in your confirmation email. 							Do you want us to resend you the email?", function(resend) {
                if (resend) return _this.ResendConfirmationEmail();
              });
            } else {
              A2Cribs.UIManager.CloseLogs();
              return A2Cribs.UIManager.Error(data.error);
            }
            /*
            					TODO: GIVE USER THE OPTION TO RESEND CONFIRMATION EMAIL
            					if data.error_type == "EMAIL_UNVERIFIED"
            						A2Cribs.UIManager.Alert data.error
            */
          } else {
            A2Cribs.MixPanel.AuthEvent('login', {
              'source': 'cribspot'
            });
            return window.location.reload();
          }
        });
      }
      return false;
    };

    Login.ResendConfirmationEmail = function() {
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

    validate = function(user_type, required_fields) {
      var field, isValid, phone_number, type_prefix, _i, _len;
      type_prefix = user_type === 0 ? "student_" : "pm_";
      A2Cribs.UIManager.CloseLogs();
      isValid = true;
      for (_i = 0, _len = required_fields.length; _i < _len; _i++) {
        field = required_fields[_i];
        if (Login.div.find("#" + type_prefix + field).val().length === 0) {
          isValid = false;
        }
      }
      if (!isValid) A2Cribs.UIManager.Error("Please fill in all of the fields!");
      if (user_type === 1) {
        phone_number = Login.div.find("#" + type_prefix + "phone").val().split("-").join("");
        if (phone_number.length !== 10 || isNaN(phone_number)) {
          isValid = false;
          A2Cribs.UIManager.Error("Please enter a valid phone number");
        }
      }
      if (Login.div.find("#" + type_prefix + "password").val().length < 6) {
        isValid = false;
        A2Cribs.UIManager.Error("Please enter a password of 6 or more characters");
      }
      return isValid;
    };

    createUser = function(user_type, required_fields, fields) {
      var field, request_data, type_prefix, _i, _len;
      type_prefix = user_type === 0 ? "student_" : "pm_";
      if (validate(user_type, required_fields)) {
        if (Login.div.find("#" + type_prefix + "password").val() !== Login.div.find("#" + type_prefix + "confirm_password").val()) {
          return A2Cribs.UIManager.Error("Make sure passwords match!");
        } else {
          request_data = {
            User: {
              user_type: user_type
            }
          };
          for (_i = 0, _len = fields.length; _i < _len; _i++) {
            field = fields[_i];
            if (Login.div.find("#" + type_prefix + field).val().length !== 0) {
              request_data.User[field] = Login.div.find("#" + type_prefix + field).val();
            }
          }
          return $.post("/users/AjaxRegister", request_data, function(response) {
            var data, email;
            data = JSON.parse(response);
            if (data.error != null) {
              A2Cribs.UIManager.CloseLogs();
              return A2Cribs.UIManager.Error(data.error);
            } else {
              email = null;
              if (user_type === 0) {
                email = $("#student_email").val();
              } else {
                email = $("#pm_email").val();
              }
              A2Cribs.MixPanel.AuthEvent('signup', {
                'user_id': response.success,
                'user_type': user_type,
                'email': email,
                'source': 'cribspot',
                'user_data': request_data
              });
              mixpanel.people.set({
                'user_id': response.success,
                'user_type': user_type,
                'email': email,
                'user_data': request_data
              });
              Login.div.find(".show_login").click();
              return A2Cribs.UIManager.Alert("Check your email to validate your credentials!");
            }
          });
        }
      }
    };

    Login.CreateStudent = function() {
      var fields, required_fields;
      required_fields = ["email", "password", "first_name", "last_name"];
      fields = required_fields.slice(0);
      createUser(0, required_fields, fields);
      return false;
    };

    Login.CreatePropertyManager = function() {
      var fields, required_fields;
      required_fields = ["email", "password", "company_name", "street_address", "phone", "city", "state"];
      fields = required_fields.slice(0);
      fields.push("website");
      createUser(1, required_fields, fields);
      return false;
    };

    return Login;

  }).call(this);

  A2Cribs.FeaturedListings = (function() {
    var Sidebar;

    function FeaturedListings() {}

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

    FeaturedListings.InitializeSidebar = function(university_id, active_listing_type, basicDataDeferred, basicDataCachedDeferred) {
      var NUM_RANDOM_LISTINGS, alt, getFlIdsDeferred, sidebar,
        _this = this;
      alt = active_listing_type;
      if (!(this.SidebarListingCache != null)) this.SidebarListingCache = {};
      if (!(this.FLListingIds != null)) this.FLListingIds = [];
      NUM_RANDOM_LISTINGS = 25;
      sidebar = new Sidebar($('#fl-side-bar'));
      getFlIdsDeferred = this.GetFlIds(university_id);
      this.GetSidebarImagePathsDeferred = new $.Deferred();
      $.when(getFlIdsDeferred, basicDataCachedDeferred).then(function(flIds) {
        var all_listing_ids, id, listing, listingObject, listings, marker, randomIds, rental, sidebar_listing_ids, _i, _j, _k, _l, _len, _len2, _len3, _len4, _len5, _m;
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
          randomIds = _this.GetRandomListingsFromMap(NUM_RANDOM_LISTINGS, all_listing_ids);
        }
        if (!(flIds != null) && !(randomIds != null)) return;
        sidebar_listing_ids = [];
        for (_j = 0, _len2 = flIds.length; _j < _len2; _j++) {
          id = flIds[_j];
          id = parseInt(id);
          _this.FLListingIds.push(id);
          sidebar_listing_ids.push(id);
        }
        if (randomIds != null) {
          for (_k = 0, _len3 = randomIds.length; _k < _len3; _k++) {
            id = randomIds[_k];
            sidebar_listing_ids.push(id);
          }
        }
        listings = [];
        for (_l = 0, _len4 = sidebar_listing_ids.length; _l < _len4; _l++) {
          id = sidebar_listing_ids[_l];
          listingObject = {};
          listing = A2Cribs.UserCache.Get('listing', id);
          marker = rental = null;
          if (listing != null) {
            marker = A2Cribs.UserCache.Get('marker', listing.marker_id);
            rental = A2Cribs.UserCache.GetAllAssociatedObjects('rental', 'listing', id);
            if (rental[0] != null) rental = rental[0];
          }
          if ((listing != null) && (marker != null) && (rental != null)) {
            listingObject.Listing = listing;
            listingObject.Marker = marker;
            listingObject.Rental = rental;
            listings.push(listingObject);
          } else {
            console.log(listing);
            console.log(marker);
            console.log(rental);
          }
        }
        sidebar.addListings(listings, 'ran');
        _this.GetSidebarImagePaths(sidebar_listing_ids);
        for (_m = 0, _len5 = listings.length; _m < _len5; _m++) {
          listing = listings[_m];
          if (listing.Listing != null) {
            A2Cribs.FavoritesManager.setFavoriteButton(listing.Listing.listing_id.toString(), null, A2Cribs.FavoritesManager.FavoritesListingIds);
          }
        }
        return $(".fl-sb-item").click(function(event) {
          var listing_id, markerPosition, marker_id;
          marker_id = parseInt($(event.currentTarget).attr('marker_id'));
          listing_id = parseInt($(event.currentTarget).attr('listing_id'));
          marker = A2Cribs.UserCache.Get('marker', marker_id);
          listing = A2Cribs.UserCache.Get('listing', listing_id);
          A2Cribs.Map.GMap.setZoom(16);
          A2Cribs.HoverBubble.Open(marker);
          A2Cribs.MixPanel.Click(listing, 'sidebar listing');
          markerPosition = marker.GMarker.getPosition();
          return A2Cribs.Map.CenterMap(markerPosition.lat(), markerPosition.lng());
        });
      });
      return $.when(this.GetSidebarImagePathsDeferred).then(function(images) {
        var image, img_element, _i, _len, _results;
        images = JSON.parse(images);
        _results = [];
        for (_i = 0, _len = images.length; _i < _len; _i++) {
          image = images[_i];
          if ((image != null) && (image.Image != null)) {
            img_element = $("#sb-img" + image.Image.listing_id);
            _results.push(img_element.attr('src', '/' + image.Image.image_path));
          } else {
            _results.push(void 0);
          }
        }
        return _results;
      });
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

    Sidebar = (function() {

      function Sidebar(SidebarUI) {
        this.SidebarUI = SidebarUI;
        this.ListItemTemplate = _.template(A2Cribs.FeaturedListings.ListItemHTML);
      }

      Sidebar.prototype.addListings = function(listings, list, clear) {
        var list_html;
        if (clear == null) clear = true;
        if (listings === null) return;
        list_html = this.getListHtml(listings);
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
        var beds, data, image, lease_length, list, listing, name, primary_image_path, rent, start_date, _i, _j, _len, _len2, _ref;
        list = "";
        for (_i = 0, _len = listings.length; _i < _len; _i++) {
          listing = listings[_i];
          rent = name = beds = lease_length = start_date = null;
          if (listing.Rental.rent != null) {
            rent = parseFloat(listing.Rental.rent).toFixed(0);
          } else {
            rent = ' --';
          }
          if (listing.Marker.alternate_name != null) {
            name = listing.Marker.alternate_name;
          } else {
            name = listing.Marker.street_address;
          }
          if (listing.Rental.lease_length != null) {
            lease_length = listing.Rental.lease_length;
          } else {
            lease_length = '-- ';
          }
          if (listing.Rental.beds > 1) {
            beds = "" + listing.Rental.beds + " beds";
          } else if (listing.Rental.beds != null) {
            beds = "" + listing.Rental.beds + " bed";
          } else {
            beds = "-- beds";
          }
          if (listing.Rental.start_date != null) {
            start_date = listing.Rental.start_date.toString().replace(' ', 'T');
            start_date = this.getDateString(new Date(start_date));
          } else {
            start_date = 'Start Date --';
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
            lease_length: lease_length,
            name: name,
            img: primary_image_path,
            listing_id: listing.Listing.listing_id,
            marker_id: listing.Marker.marker_id
          };
          list += this.ListItemTemplate(data);
        }
        return list;
      };

      return Sidebar;

    })();

    FeaturedListings.ListItemHTML = "<div class = 'fl-sb-item' listing_id=<%= listing_id %> marker_id=<%= marker_id %>>\n    <span class = 'img-wrapper'>\n        <img id='sb-img<%=listing_id %>' src = '<%=img%>'></img>\n    </span>\n    <span class = 'vert-line'></span>\n    <span class = 'info-wrapper'>\n        <div class = 'info-row'>\n            <span class = 'rent price-text'><%= \"$\" + rent %></span>\n            <span class = 'divider'>|</span>\n            <span class = 'beds'><%= beds %> </span>\n            <span class = 'favorite pull-right'><i class = 'icon-heart fav-icon share_btn favorite_listing' id='<%= listing_id %>'></i></span>    \n        </div>\n        <div class = 'row-div'></div>\n        <div class = 'info-row'>\n            <span class = 'building-type'><%= building_type %></span>\n            <span class = 'divider'>|</span>\n            <span class = 'lease-start'><%= start_date %></span> | <span class = 'lease_length'><%= lease_length %> months</span>\n        </div>\n        <div class = 'row-div'></div>\n        <div class = 'info-row'>\n            <i class = 'icon-map-marker'></i><span class = 'name'><%=name%></span>\n        </div>\n    </span>   \n</div>";

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

    FullListing.SetupUI = function() {
      var _this = this;
      this.div = $(".full_page");
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
        if (parseInt($("#contact_owner").attr('emailexists')) === 0) {
          _this.div.find('#contact_message').show();
          _this.div.find("#contact_owner").hide();
          return;
        }
        _this.div.find("#contact_owner").hide();
        return _this.div.find("#contact_message").slideDown();
      });
      this.div.find("#message_cancel").click(function() {
        return _this.div.find("#contact_message").slideUp('fast', function() {
          return _this.div.find("#contact_owner").show();
        });
      });
      return this.div.find("#message_send").click(function() {
        $("#message_send").button("loading");
        return $.ajax({
          url: myBaseUrl + "Messages/messageSublet",
          type: "POST",
          data: {
            listing_id: 1,
            message_body: $("#message_area").val()
          },
          success: function(response) {
            var data;
            data = JSON.parse(response);
            if (data.success) {
              $("#message_area").val("");
              A2Cribs.UIManager.Success("Message Sent!");
            } else {
              if (data.message != null) {
                A2Cribs.UIManager.Error(data.message);
              } else {
                A2Cribs.UIManager.Error("Message Failed! Please Try Again.");
              }
            }
            return $("#message_send").button("reset");
          }
        });
      });
    };

    FullListing.Directive = function(directive) {
      if (directive.contact_owner != null) {
        return this.div.find("#contact_owner").click();
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
          $(".list-dropdown").slideUp();
          $('.content-header.active').removeClass("active");
          $(event.delegateTarget).addClass("active");
          if (content_header.hasClass("list-dropdown-header")) {
            return $("#" + class_name + "_list").slideDown();
          } else {
            return _this.ShowContent(content, true);
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
      $("#create-listing").find("a").click(function(event) {
        A2Cribs.MarkerModal.NewMarker();
        return A2Cribs.MarkerModal.Open();
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
      return this.GetUserMarkerData();
    };

    /*
    	Retrieves all basic marker_data for the logged in user and updates nav bar in dashboard
    */

    Dashboard.GetUserMarkerData = function() {
      var url;
      url = myBaseUrl + "listings/GetMarkerDataByLoggedInUser";
      return $.ajax({
        url: url,
        type: "GET",
        success: this.GetUserMarkerDataCallback
      });
    };

    Dashboard.GetUserMarkerDataCallback = function(data) {
      var list_item, listing_types, listings_count, marker, marker_ids_processed, markers, name, _i, _len, _ref, _results;
      markers = JSON.parse(data);
      listings_count = [0, 0, 0];
      listing_types = ["rentals", "sublet", "parking"];
      $("#rentals_count").text(markers.length);
      marker_ids_processed = [];
      _results = [];
      for (_i = 0, _len = markers.length; _i < _len; _i++) {
        marker = markers[_i];
        if (marker.Marker != null) {
          marker = marker.Marker;
        } else {
          continue;
        }
        if ((marker.marker_id != null) && (_ref = marker.marker_id, __indexOf.call(marker_ids_processed, _ref) >= 0)) {
          continue;
        }
        name = marker.alternate_name;
        if (!marker.alternate_name || !marker.alternate_name.length) {
          name = marker.street_address;
        }
        list_item = $("<li />", {
          text: name,
          "class": "rentals_list_item",
          id: marker.marker_id
        });
        $("#rentals_list_content").append(list_item);
        _results.push(marker_ids_processed.push(marker.marker_id));
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
      content.siblings().addClass('hidden');
      content.removeClass('hidden');
      return content.trigger('shown');
    };

    Dashboard.HideContent = function(classname) {
      return $("." + classname + "-content").addClass('hidden');
    };

    Dashboard.Direct = function(directive) {
      var content_header;
      content_header = $('#' + directive.classname + "-content-header");
      content_header.trigger('click');
      if (directive.data != null) {
        return this.ShowContent($('.' + directive.classname + "-content"));
      }
    };

    return Dashboard;

  }).call(this);

  A2Cribs.Account = (function() {

    function Account() {}

    Account.setupUI = function() {
      var my_verification_info, url, veripanel,
        _this = this;
      url = myBaseUrl + "university/getAll/";
      $.get(url, function(data) {
        _this.UniversityData = JSON.parse(data);
        _this.UniversityNames = [];
        _this.UniversityID = [];
        _.each(_this.UniversityData, function(value, key, list) {
          _this.UniversityNames[key] = value['University']['name'];
          return _this.UniversityID[key] = value['University']['id'];
        });
        $('#university').typeahead({
          source: _this.UniversityNames
        });
        return $('#save_btn').click(function() {
          return _this.SaveAccount();
        });
      });
      my_verification_info = A2Cribs.VerifyManager.getMyVerification();
      veripanel = $('#my-verification-panel');
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
      $('.veridd').each(function(index, element) {
        return $(element).tooltip({
          'title': 'Verify?',
          'trigger': 'hover'
        });
      });
      $('#changePasswordButton').click(function() {
        return _this.ChangePassword($('#changePasswordButton'), $('#new_password').val(), $('#confirm_password').val());
      });
      $('#VerifyUniversityButton').click(function() {
        return _this.VerifyUniversity();
      });
      $('#changePhoneBtn').click(function() {
        return _this.SavePhone();
      });
      $('#changeAddressBtn').click(function() {
        return _this.SaveAddress();
      });
      return $('#changeCompanyNameBtn').click(function() {
        return _this.SaveCompanyName();
      });
    };

    Account.SaveCompanyName = function() {
      var pair;
      pair = {
        'company_name': $("#company_name_input").val()
      };
      return this.SaveAccount(pair);
    };

    Account.SavePhone = function() {
      var pair, phone;
      phone = $("#phone_input").val();
      if (this.ValidatePhone(phone)) {
        pair = {
          'phone': phone
        };
        return this.SaveAccount(pair);
      } else {
        return A2Cribs.UIManager.Error("Invalid phone number");
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
      return this.SaveAccount(pair);
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
      var data;
      if (id == null) id = null;
      if (reset_token == null) reset_token = null;
      if (redirect == null) redirect = null;
      change_password_button.attr('disabled', 'disabled');
      data = {
        'new_password': new_password,
        'confirm_password': confirm_password
      };
      if (id !== null && reset_token !== null) {
        data['id'] = id;
        data['reset_token'] = reset_token;
      }
      if (new_password !== confirm_password) {
        A2Cribs.UIManager.Alert("Passwords do not match.");
        return;
      }
      return $.post(myBaseUrl + 'users/AjaxChangePassword', data, function(response) {
        response = JSON.parse(response);
        if (response.error === void 0) {
          if (id === null && reset_token === null) {
            alertify.success('Password Changed', 3000);
            if (redirect !== null) window.location.href = redirect;
          } else {
            window.location.href = '/dashboard';
          }
        } else {
          A2Cribs.UIManager.Alert(response.error);
        }
        return change_password_button.removeAttr('disabled');
      });
    };

    Account.SaveAccount = function(keyValuePairs) {
      if (keyValuePairs == null) keyValuePairs = null;
      /*$('#save_btn').attr 'disabled','disabled'
      		first_name = $('#first_name_input').val()
      		last_name = $('#last_name_input').val()
      		data = {
      			'first_name': first_name,
      			'last_name': last_name,
      		}
      */
      return $.post(myBaseUrl + 'users/AjaxEditUser', keyValuePairs, function(response) {
        var json_response;
        json_response = JSON.parse(response);
        if (json_response.error === void 0) {
          alertify.success('Account Saved', 3000);
        } else {
          A2Cribs.UIManager.Error('Account Failed to Save: ' + json_response.error.message);
        }
        return $('#save_btn').removeAttr('disabled');
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
      this.NumMessagePages += 1;
      return this.loadMessages(this.NumMessagePages);
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
        return $('#message_count').html(response_data.unread_conversations);
      });
    };

    Messages.refreshConversations = function() {
      var url,
        _this = this;
      url = myBaseUrl + "messages/getConversations";
      return $.get(url, function(data) {
        var conversations, convo, list_item, _i, _len;
        conversations = JSON.parse(data);
        for (_i = 0, _len = conversations.length; _i < _len; _i++) {
          convo = conversations[_i];
          list_item = $("<li />", {
            text: convo.Conversation.title,
            "class": "messages_list_item",
            id: convo.Conversation.conversation_id,
            "data-participant": convo.Participant.id
          });
          $("#messages_list_content").append(list_item);
        }
        return _this.attachConversationListItemHandler();
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
      $(".from_participant").html("" + participant.first_name + " " + participant.last_name);
      return A2Cribs.VerifyManager.getVerificationFor(participant).then(function(verification_info) {
        var url, veripanel;
        veripanel = $('#verification-panel');
        if (verification_info.verified_email) {
          veripanel.find('#veri-email  i:last-child').removeClass('unverified icon-remove-sign').addClass('verified icon-ok-sign');
        }
        if (verification_info.verified_fb) {
          url = "https://graph.facebook.com/" + verification_info.fb_id + "/picture?width=480";
          console.log(url);
          return $('#p_pic').attr('src', url);
        }
      });
    };

    Messages.loadConversation = function(event) {
      /*
      		$('#cli_' + @CurrentConversation).removeClass 'selected_conversation'
      		$('#cli_' + @CurrentConversation).addClass 'read_conversation'	
      
      		$(event.currentTarget)
      			.addClass('selected_conversation')
      			.removeClass('unread_conversation')
      */
      var sublet_url, title;
      this.CurrentConversation = parseInt($(event.delegateTarget).attr('id'));
      this.CurrentParticipantID = $(event.delegateTarget).attr('data-participant');
      $('#message_reply').show();
      $('#participant_info_short').show();
      title = $('#cli_' + this.CurrentConversation).find('.conversation_title').text();
      sublet_url = $('#cli_' + this.CurrentConversation + ' a').attr('href');
      $('#listing_title').text(title).attr('href', sublet_url);
      this.refreshParticipantInfo();
      this.refreshUnreadCount();
      return this.refreshMessages();
    };

    Messages.loadMessages = function(page, align_bottom) {
      var url,
        _this = this;
      if (align_bottom == null) align_bottom = false;
      url = myBaseUrl + "messages/getMessages/" + this.CurrentConversation + "/" + page + "/";
      return $.get(url, function(data, textStatus) {
        var diff, initial_height, message_list;
        message_list = $('#message_list');
        initial_height = message_list.innerHeight();
        $(data).hide().prependTo('#message_list').fadeIn();
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
        return _this.attachConversationListItemHandler();
      }).fail(function() {
        return _this.NumMessagePages = 0;
      });
    };

    Messages.attachConversationListItemHandler = function() {
      var _this = this;
      return $('.messages_list_item').one('click', function(event) {
        return _this.loadConversation(event);
      });
    };

    Messages.refreshMessages = function(event) {
      var message_list;
      this.NumMessagePages = 1;
      message_list = $('#message_list');
      message_list.html('');
      return this.loadMessages(this.NumMessagePages, true);
    };

    Messages.sendReply = function(event) {
      var message_data, message_text, url,
        _this = this;
      message_text = $('#message_text textarea').val();
      if (message_text.length === 0) {
        A2Cribs.UIManager.Error("Message can not be empty");
        return false;
      }
      $('#send_reply').attr('disabled', 'disabled');
      message_text = $('#message_text textarea').val();
      message_data = {
        'message_text': message_text,
        'conversation_id': this.CurrentConversation
      };
      url = myBaseUrl + "messages/newMessage/";
      $.post(url, message_data, function(data) {
        var response;
        _this.refreshMessages();
        $('#message_text textarea').val('');
        response = JSON.parse(data);
        if ((data != null ? data.success : void 0) === false) {
          return A2Cribs.UIManager.Error("Something went wrong while sending a reply, please refresh the page and try again");
        }
      }).always(function() {
        return $('#send_reply').removeAttr('disabled');
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

    Messages.Direct = function(directive) {
      var conv_id, participant_id;
      if (directive.data != null) {
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

  A2Cribs.SubletSave = (function() {

    function SubletSave() {}

    SubletSave.prototype.setupUI = function(div) {
      var _this = this;
      if (!(A2Cribs.Geocoder != null)) {
        A2Cribs.Geocoder = new google.maps.Geocoder();
      }
      this.div = div;
      div.find("#Sublet_short_description").keyup(function() {
        if ($(this).val().length >= 160) $(this).val($(this).val().substr(0, 160));
        return div.find("#desc-char-left").text(160 - $(this).val().length);
      });
      div.find("#Sublet_utility_type_id").change(function() {
        if (+div.find("#Sublet_utility_type_id").val() === 1) {
          return div.find("#Sublet_utility_cost").val("0");
        }
      });
      div.find("#Housemate_student_type_id").change(function() {
        if (+div.find("#Housemate_student_type_id").val() === 1) {
          return _this.div.find("#Housemate_year").val(0);
        }
      });
      div.find(".required").keydown(function() {
        return $(this).parent().removeClass("error");
      });
      div.find(".date_field").datepicker();
      this.MiniMap = new A2Cribs.MiniMap(div);
      return this.PhotoManager = new A2Cribs.PhotoManager(div);
    };

    /*
    	Called before advancing steps
    	Returns true if validations pass; false otherwise
    */

    SubletSave.prototype.Validate = function(step_) {
      if (step_ >= 1) if (!this.ValidateStep1()) return false;
      if (step_ >= 2) if (!this.ValidateStep2()) return false;
      if (step_ >= 3) if (!this.ValidateStep3()) return false;
      return true;
    };

    SubletSave.prototype.ValidateStep1 = function() {
      var isValid;
      isValid = true;
      A2Cribs.UIManager.CloseLogs();
      if (!this.div.find('#Marker_street_address').val()) {
        A2Cribs.UIManager.Error("Please place your street address on the map using the Place On Map button.");
        this.div.find('#Marker_street_address').parent().addClass("error");
        isValid = false;
      }
      if (!this.div.find('#University_name').val()) {
        A2Cribs.UIManager.Error("You need to select a university.");
        this.div.find('#University_name').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find('#Marker_building_type_id').val().length === 0) {
        A2Cribs.UIManager.Error("You need to select a building type.");
        this.div.find('#Marker_building_type_id').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find('#Sublet_unit_number').val().length >= 249) {
        A2Cribs.UIManager.Error("Your unit number is too long.");
        this.div.find('#Sublet_unit_number').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find('#Marker_alternate_name').val().length >= 249) {
        A2Cribs.UIManager.Error("Your alternate name is too long.");
        this.div.find('#Marker_alternate_name').parent().addClass("error");
        isValid = false;
      }
      return isValid;
    };

    SubletSave.prototype.ValidateStep2 = function() {
      var descLength, isValid, parsedBeginDate, parsedEndDate, todayDate;
      isValid = true;
      A2Cribs.UIManager.CloseLogs();
      parsedBeginDate = new Date(Date.parse(this.div.find('#Sublet_date_begin').val()));
      parsedEndDate = new Date(Date.parse(this.div.find('#Sublet_date_end').val()));
      todayDate = new Date();
      if (parsedBeginDate.toString() === "Invalid Date" || parsedEndDate.toString() === "Invalid Date") {
        A2Cribs.UIManager.Error("Please enter a valid date.");
        this.div.find('#Sublet_date_begin').parent().addClass("error");
        this.div.find('#Sublet_date_end').parent().addClass("error");
        isValid = false;
      } else if (parsedEndDate.valueOf() <= parsedBeginDate.valueOf()) {
        A2Cribs.UIManager.Error("Please enter a valid date.");
        this.div.find('#Sublet_date_begin').parent().addClass("error");
        this.div.find('#Sublet_date_end').parent().addClass("error");
        isValid = false;
      }
      if (!this.div.find('#Sublet_number_bedrooms').val() || isNaN(parseInt(this.div.find("#Sublet_number_bedrooms").val())) || this.div.find('#Sublet_number_bedrooms').val() <= 0 || this.div.find('#Sublet_number_bedrooms').val() >= 30) {
        A2Cribs.UIManager.Error("Please enter a valid number of bedrooms.");
        this.div.find('#Sublet_number_bedrooms').parent().addClass("error");
        isValid = false;
      }
      if (!this.div.find('#Sublet_price_per_bedroom').val() || isNaN(parseInt(this.div.find("#Sublet_price_per_bedroom").val())) || this.div.find('#Sublet_price_per_bedroom').val() < 1 || this.div.find('#Sublet_price_per_bedroom').val() >= 20000) {
        A2Cribs.UIManager.Error("Please enter a valid price per bedroom.");
        this.div.find('#Sublet_price_per_bedroom').parent().parent().addClass("error");
        isValid = false;
      }
      if (this.div.find('#Sublet_short_description').val().length === 0) {
        A2Cribs.UIManager.Error("Please enter a description.");
        this.div.find('#Sublet_short_description').parent().addClass("error");
        isValid = false;
      }
      if (!this.div.find('#Sublet_utility_cost').val() || isNaN(parseInt(this.div.find("#Sublet_utility_cost").val())) || this.div.find('#Sublet_utility_cost').val() < 0 || this.div.find('#Sublet_utility_cost').val() >= 50000) {
        A2Cribs.UIManager.Error("Please enter a valid utility cost.");
        this.div.find('#Sublet_utility_cost').parent().addClass("error");
        isValid = false;
      }
      if (!this.div.find('#Sublet_deposit_amount').val() || isNaN(parseInt(this.div.find("#Sublet_deposit_amount").val())) || this.div.find('#Sublet_deposit_amount').val() < 0 || this.div.find('#Sublet_deposit_amount').val() >= 50000) {
        A2Cribs.UIManager.Error("Please enter a valid deposit amount.");
        this.div.find('#Sublet_deposit_amount').parent().parent().addClass("error");
        isValid = false;
      }
      descLength = this.div.find('#Sublet_additional_fees_description').val().length;
      if (descLength >= 161) {
        A2Cribs.UIManager.Error("Please keep the additional fees description under 160 characters.");
        this.div.find('#Sublet_additional_fees_description').parent().addClass("error");
        isValid = false;
      }
      if (descLength > 0) {
        if (!this.div.find('#Sublet_additional_fees_amount').val() || isNaN(parseInt(this.div.find("#Sublet_additional_fees_amount").val())) || this.div.find('#Sublet_additional_fees_amount').val() < 0 || this.div.find('#Sublet_additional_fees_amount').val() >= 50000) {
          A2Cribs.UIManager.Error("Please enter a valid additional fees amount.");
          this.div.find('#Sublet_additional_fees_amount').parent().addClass("error");
          isValid = false;
        }
      }
      if (this.div.find("#Sublet_furnished_type_id").val().length === 0) {
        A2Cribs.UIManager.Error("Please describe the situation with the furniture.");
        this.div.find('#Sublet_furnished_type_id').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find("#Sublet_utility_type_id").val().length === 0) {
        A2Cribs.UIManager.Error("Please describe the situation with the utilities.");
        this.div.find('#Sublet_utility_type_id').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find("#Sublet_parking").val().length === 0) {
        A2Cribs.UIManager.Error("Please describe the situation with parking.");
        this.div.find('#Sublet_parking').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find("#Sublet_ac").val().length === 0) {
        A2Cribs.UIManager.Error("Please describe the situation with parking.");
        this.div.find('#Sublet_ac').parent().addClass("error");
        isValid = false;
      }
      if (this.div.find("#Sublet_bathroom_type_id").val().length === 0) {
        A2Cribs.UIManager.Error("Please describe the situation with your bathroom.");
        this.div.find('#Sublet_bathroom_type_id').parent().addClass("error");
        isValid = false;
      }
      return isValid;
    };

    SubletSave.prototype.ValidateStep3 = function() {
      var isValid;
      isValid = true;
      if (this.div.find('#Housemate_quantity').val().length === 0) {
        isValid = false;
      } else {
        if (+this.div.find('#Housemate_quantity').val() !== 0) {
          if (this.div.find('#Housemate_enrolled option:selected').text().length === 0) {
            isValid = false;
          } else if (+this.div.find('#Housemate_enrolled').val() === 1) {
            if (+this.div.find('#Housemate_student_type_id').val() === 0) {
              isValid = false;
            } else if (+this.div.find('#Housemate_student_type_id').val() !== 1) {
              if (+this.div.find('#Housemate_year').val() === 0) isValid = false;
            }
            if (+this.div.find('#Housemate_gender_type_id').val() === 0) {
              isValid = false;
            }
            if (this.div.find('#Housemate_major').val().length >= 255) {
              isValid = false;
            }
          }
        }
      }
      return isValid;
    };

    SubletSave.prototype.Reset = function() {
      this.ResetAllInputFields();
      return this.PhotoManager.Reset();
    };

    /*
    	Reset all input fields for a new sublet posting process
    */

    SubletSave.prototype.ResetAllInputFields = function() {
      this.div.find('input:text').val('');
      this.div.find('input:hidden').val('');
      this.div.find('select option:first-child').attr("selected", "selected");
      return this.div.find("#Sublet_payment_type_id").val("1");
    };

    /*
    	Submits sublet to backend to save
    	Assumes all front-end validations have been passed.
    */

    SubletSave.prototype.Save = function(subletObject, success) {
      var url,
        _this = this;
      if (success == null) success = null;
      url = "/sublets/ajax_submit_sublet";
      return $.post(url, subletObject, function(response) {
        var data;
        data = JSON.parse(response);
        console.log(data.status);
        if (data.redirect != null) window.location = data.redirect;
        if (data.status != null) {
          A2Cribs.UIManager.Success(data.status);
          A2Cribs.ShareManager.SavedListing = data.newid;
          if (success != null) return success(data.newid);
        } else {
          return A2Cribs.UIManager.Alert(data.error);
        }
      });
    };

    /*
    	Returns an object containing all sublet data from all 4 steps.
    */

    SubletSave.prototype.GetSubletObject = function() {
      var input, k, p, q, v, _ref;
      _ref = A2Cribs.SubletObject;
      for (k in _ref) {
        v = _ref[k];
        for (p in v) {
          q = v[p];
          console.log(k + "_" + p);
          A2Cribs.SubletObject[k][p] = 0;
          input = this.div.find("#" + k + "_" + p);
          if (input != null) {
            if ("checkbox" === input.attr("type")) {
              A2Cribs.SubletObject[k][p] = input.prop("checked");
            } else if (input.hasClass("date_field")) {
              A2Cribs.SubletObject[k][p] = this.GetMysqlDateFormat(input.val());
            } else {
              A2Cribs.SubletObject[k][p] = input.val();
            }
          }
        }
      }
      A2Cribs.SubletObject.Image = this.PhotoManager.GetPhotos();
      return A2Cribs.SubletObject;
    };

    /*
    	Replaces '/' with '-' to make convertible to mysql datetime format
    */

    SubletSave.prototype.GetMysqlDateFormat = function(dateString) {
      var beginDateFormatted, date, day, month, year;
      date = new Date(dateString);
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = year + "-" + month + "-" + day;
    };

    SubletSave.prototype.GetTodaysDate = function() {
      var dd, mm, today, yyyy;
      today = new Date();
      dd = today.getDate();
      mm = today.getMonth() + 1;
      yyyy = today.getFullYear();
      if (dd < 10) dd = '0' + dd;
      if (mm < 10) mm = '0' + mm;
      today = mm + '/' + dd + '/' + yyyy;
      return today;
    };

    SubletSave.prototype.GetFormattedDate = function(date) {
      var beginDateFormatted, day, month, year;
      month = date.getMonth() + 1;
      if (month < 10) month = "0" + month;
      day = date.getDate();
      if (day < 10) day = "0" + day;
      year = date.getUTCFullYear();
      return beginDateFormatted = month + "/" + day + "/" + year;
    };

    return SubletSave;

  })();

  A2Cribs.UserCache = (function() {
    var _get;

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

  })();

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

}).call(this);
