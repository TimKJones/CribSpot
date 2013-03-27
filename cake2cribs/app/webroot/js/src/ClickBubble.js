// Generated by CoffeeScript 1.3.3

/*
ClickBubble class
Wrapper for google infobubble
*/


(function() {
  var __indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  A2Cribs.ClickBubble = (function() {
    /*
    	Constructor
    	-creates infobubble object
    */

    function ClickBubble(map) {
      var obj;
      obj = {
        map: map,
        arrowStyle: 0,
        arrowPosition: 20,
        shadowStyle: 1,
        borderRadius: 5,
        arrowSize: 17,
        borderWidth: 0,
        disableAutoPan: true,
        padding: 7,
        maxWidth: 350,
        maxHeight: 400,
        disableAnimation: true
      };
      this.InfoBubble = new InfoBubble(obj);
      this.InfoBubble.hideCloseButton();
    }

    /*
    	Opens the tooltip given a marker, with popping animation
    */


    ClickBubble.prototype.Open = function(marker) {
      if (marker) {
        this.SetContent(marker);
        return this.InfoBubble.open(A2Cribs.Map.GMap, marker.GMarker);
      }
    };

    /*
    	Refreshes the tooltip with the new content, no animation
    */


    ClickBubble.prototype.Refresh = function() {
      return this.InfoBubble.open();
    };

    /*
    f	Closes the tooltip, no animation
    */


    ClickBubble.prototype.Close = function() {
      return this.InfoBubble.close();
    };

    /*
    	Sets the content of the tooltip
    */


    ClickBubble.prototype.SetContent = function(marker) {
      var content, dataTemplate, div, firstSublet, is_favorite, subletId, subletIds, subletOwner, template, _i, _len;
      subletIds = A2Cribs.Cache.MarkerIdToSubletIdsMap[marker.MarkerId];
      template = $(".click-bubble:first").wrap('<p/>').parent();
      content = template.children().first();
      content.find('.listings').empty();
      dataTemplate = content.find('.listing-block').first();
      content.find('#listing-count').text(subletIds.length);
      if (marker.Title) {
        content.find('.sublet-name').text(marker.Title);
      } else {
        content.find('.sublet-name').text(marker.Address);
      }
      if (subletIds.length === 1) {
        content.addClass("single-listing");
        content.removeClass("multi-listing");
      } else {
        content.addClass("multi-listing");
        content.removeClass("single-listing");
      }
      for (_i = 0, _len = subletIds.length; _i < _len; _i++) {
        subletId = subletIds[_i];
        div = dataTemplate.clone();
        div.removeClass("hide");
        firstSublet = A2Cribs.Cache.IdToSubletMap[subletId];
        subletOwner = A2Cribs.Cache.SubletIdToOwnerMap[subletId];
        div.removeClass("single-content");
        div.find('.username').text(subletOwner.FirstName);
        if (subletOwner.FBUserId) {
          div.find('.friend-count').text(100);
        } else {
          div.find('.fb-mutual').hide();
        }
        div.find('.date-range').text(this.resolveDateRange(firstSublet.StartDate, firstSublet.EndDate));
        div.find('.bed-price').text(firstSublet.PricePerBedroom);
        div.find('.bed-count').text(firstSublet.Bedrooms);
        div.find('.building-type').text(firstSublet.BuildingType);
        div.find('.listing-popup-link').attr('onclick', 'A2Cribs.Map.ListingPopup.Open(' + subletId + ')');
        div.find('.listing-message').attr('onclick', 'A2Cribs.Map.ListingPopup.Message(' + subletId + ')');
        is_favorite = __indexOf.call(A2Cribs.Cache.FavoritesSubletIdsList, subletId) >= 0;
        if (is_favorite) {
          div.find('.favorite-clickable').attr('title', 'Delete from Favorites');
          div.find('.favorite-clickable').attr('onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ')');
        } else {
          div.find('.favorite-clickable').attr('title', 'Add to Favorites');
          div.find('.favorite-clickable').attr('onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ')');
        }
        content.find('.listings').append(div);
      }
      this.InfoBubble.setContent(template.html());
      return $(".click-bubble:first").unwrap();
    };

    ClickBubble.prototype.resolveDateRange = function(startDate, endDate) {
      var endSplit, range, rmonth, startSplit;
      rmonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      range = "";
      startSplit = startDate.split("-");
      endSplit = endDate.split("-");
      range += rmonth[startSplit[1] - 1];
      range += " " + parseInt(startSplit[2]) + "-";
      return range + rmonth[endSplit[1] - 1] + " " + parseInt(endSplit[2]);
    };

    return ClickBubble;

  })();

}).call(this);
