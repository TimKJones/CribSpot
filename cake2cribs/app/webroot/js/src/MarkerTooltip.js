
/*
MarkerTooltip class
Wrapper for google infobubble
*/

(function() {

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
      this.Height = 175;
      this.Width = 309;
      this.ArrowOffset = 25;
      this.ArrowHeight = 15;
      return this.Padding = 20;
    };

    return MarkerTooltip;

  })();

}).call(this);
