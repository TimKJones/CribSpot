// Generated by CoffeeScript 1.4.0
(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  A2Cribs.Marker = (function(_super) {
    var FilterVisibleListings, UpdateMarkerContent;

    __extends(Marker, _super);

    function Marker(marker) {
      Marker.__super__.constructor.call(this, "marker", marker);
    }

    Marker.prototype.GetName = function() {
      if ((this.alternate_name != null) && this.alternate_name.length) {
        return this.alternate_name;
      } else {
        return this.street_address;
      }
    };

    /*
    	constructor: (@MarkerId, @Address, @Title, @UnitType, @Latitude, @Longitude, @City, @State) -> 
    		@ListingIds = null
    		@MarkerId = parseInt(@MarkerId)
    		@GMarker = new google.maps.Marker
    			position: new google.maps.LatLng(@Latitude, @Longitude)
    			icon: "/img/dots/available_dot.png"
    			id: @MarkerId
    		@Clicked = false #Used to determine if data is already in the cache
    */


    /*
    	Filters the listing_ids at the current marker according to the user's current filter settings.
    	Returns list of listing_ids that should be visible in marker tooltip.
    */


    FilterVisibleListings = function(subletIdList) {
      var ac, apt, bathType, bathroom, bathrooms_match, beds, end_date, female, grad, has_females, has_grads, has_males, has_students_only, has_undergrads, house, housemate, housemate_id, l, male, max_rent, min_rent, no_security_deposit, no_security_deposit_match, other, parking, start_date, students_only, subletId, sublet_end_date, sublet_start_date, undergrad, unitType, utilities, utilities_included_match, visibleListingIds, _i, _len;
      if (subletIdList === void 0) {
        return null;
      }
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
      if (beds === "2+") {
        beds = "2";
      }
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
      var visibleListingIds;
      if (!this.Clicked) {
        A2Cribs.Cache.CacheMarkerData(JSON.parse(markerData));
        A2Cribs.Cache.IdToMarkerMap[this.MarkerId].GMarker.setIcon("/img/dots/clicked_dot.png");
      }
      this.Clicked = true;
      visibleListingIds = FilterVisibleListings(A2Cribs.Cache.MarkerIdToSubletIdsMap[this.MarkerId]);
      return A2Cribs.Map.ClickBubble.Open(this, visibleListingIds);
    };

    /*
    	Load all listing data for this marker
    	Called when a marker is clicked
    */


    Marker.prototype.LoadMarkerData = function() {
      var visibleListingIds;
      this.CorrectTooltipLocation();
      if (this.Clicked) {
        visibleListingIds = FilterVisibleListings(A2Cribs.Cache.MarkerIdToSubletIdsMap[this.MarkerId]);
        return A2Cribs.Map.ClickBubble.Open(this, visibleListingIds);
      } else {
        return $.ajax({
          url: myBaseUrl + "Sublets/LoadMarkerData/" + this.MarkerId,
          type: "GET",
          context: this,
          success: UpdateMarkerContent
        });
      }
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

}).call(this);
