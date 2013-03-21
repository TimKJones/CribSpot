(function() {

  A2Cribs.Marker = (function() {
    var FilterVisibleListings, UpdateMarkerContent;

    function Marker(MarkerId, Address, Title, UnitType, Latitude, Longitude) {
      this.MarkerId = MarkerId;
      this.Address = Address;
      this.Title = Title;
      this.UnitType = UnitType;
      this.Latitude = Latitude;
      this.Longitude = Longitude;
      this.ListingIds = null;
      this.MarkerId = parseInt(this.MarkerId);
      this.GMarker = new google.maps.Marker({
        position: new google.maps.LatLng(this.Latitude, this.Longitude),
        icon: "/img/dots/available_dot.png",
        id: this.MarkerId
      });
      this.Clicked = false;
    }

    /*
    	Filters the listing_ids at the current marker according to the user's current filter settings.
    	Returns list of listing_ids that should be visible in marker tooltip.
    */

    FilterVisibleListings = function(subletIdList) {
      var ac, apt, bathType, beds, end_date, female, grad, house, l, male, max_rent, min_rent, no_security_deposit, other, parking, start_date, students_only, subletId, undergrad, unitType, utilities, visibleListingIds, _i, _len;
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
      min_rent = 0;
      max_rent = 999999;
      beds = 0;
      start_date = 0;
      end_date = 999999;
      visibleListingIds = [];
      for (_i = 0, _len = subletIdList.length; _i < _len; _i++) {
        subletId = subletIdList[_i];
        l = A2Cribs.Cache.IdToSubletMap[subletId];
        unitType = null;
        bathType = null;
        if (l.BuildingType !== void 0) {
          unitType = A2Cribs.Cache.BuildingIdToNameMap[l.BuildingType];
        }
        if (l.BathroomType !== void 0) {
          bathType = A2Cribs.Cache.BathroomIdToNameMap[l.BathroomType];
        }
        if ((((unitType === 'House' || unitType === null) && house) || ((unitType === 'Apartment' || unitType === null) && apt) || ((unitType === 'Duplex' || unitType === null) && other) || (unitType !== 'House' && unitType !== 'Duplex' && unitType !== 'Apartment')) && (l.PricePerBedroom >= min_rent && l.PricePerBedroom <= max_rent) && (l.Bedrooms >= beds)) {
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
      return A2Cribs.Map.MarkerTooltip.Display(visibleListingIds, this.GMarker);
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
        return A2Cribs.Map.MarkerTooltip.Display(visibleListingIds, this.GMarker);
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
      var leftBound, markerLocation, oldX, oldY, tooltipOffset;
      leftBound = ($("#favoritesBar").css('display') === 'block') * $("#favoritesBar").width();
      if (leftBound === 0) {
        leftBound = A2Cribs.Map.Bounds.CONTROL_BOX_LEFT;
      } else {
        leftBound += A2Cribs.MarkerTooltip.Padding;
      }
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
      if ((markerLocation.x + tooltipOffset.x + A2Cribs.MarkerTooltip.Width - A2Cribs.MarkerTooltip.ArrowOffset + A2Cribs.MarkerTooltip.Padding > A2Cribs.Map.Bounds.FILTER_BOX_LEFT) && (markerLocation.y + tooltipOffset.y - A2Cribs.MarkerTooltip.Height - A2Cribs.MarkerTooltip.ArrowHeight < A2Cribs.Map.Bounds.FILTER_BOX_BOTTOM)) {
        oldX = tooltipOffset.x;
        oldY = tooltipOffset.y;
        tooltipOffset.x = markerLocation.x + A2Cribs.MarkerTooltip.Width - A2Cribs.MarkerTooltip.ArrowOffset + A2Cribs.MarkerTooltip.Padding - A2Cribs.Map.Bounds.FILTER_BOX_LEFT;
        tooltipOffset.y = markerLocation.y - A2Cribs.MarkerTooltip.Height - A2Cribs.MarkerTooltip.ArrowHeight - A2Cribs.Map.Bounds.FILTER_BOX_BOTTOM;
        if (Math.abs(tooltipOffset.x) > Math.abs(tooltipOffset.y)) {
          tooltipOffset.x = oldX;
        } else {
          tooltipOffset.y = oldY;
        }
      }
      return A2Cribs.Map.GMap.panBy(tooltipOffset.x, tooltipOffset.y);
    };

    return Marker;

  })();

}).call(this);
