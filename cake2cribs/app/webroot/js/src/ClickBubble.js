// Generated by CoffeeScript 1.6.1

/*
ClickBubble class
Wrapper for google infobubble
*/


(function() {

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
      var subletIds;
      subletIds = A2Cribs.Cache.MarkerIdToSubletIdsMap[marker.MarkerId];
      if (subletIds.length > 1) {
        return this.setMultiContent(subletIds);
      } else {
        return this.setSingleContent(subletIds);
      }
    };

    ClickBubble.prototype.setMultiContent = function(subletIds) {
      var content, dataTemplate, div, firstSublet, is_favorite, subletId, subletOwner, template, _i, _len;
      template = $(".click-bubble:first").wrap('<p/>').parent();
      content = template.children().first();
      firstSublet = A2Cribs.Cache.IdToSubletMap[subletIds[0]];
      content.addClass("multi-listing");
      content.removeClass("single-listing");
      content.find('#listing-count').text(subletIds.length);
      if (firstSublet.Name) {
        content.find('.sublet-name').text(firstSublet.Name);
      } else {
        content.find('.sublet-name').text(firstSublet.StreetAddress);
      }
      dataTemplate = content.find('.click-bubble-data').first();
      content.find('.bubble-container').first().empty();
      for (_i = 0, _len = subletIds.length; _i < _len; _i++) {
        subletId = subletIds[_i];
        div = dataTemplate.clone();
        firstSublet = A2Cribs.Cache.IdToSubletMap[subletId];
        subletOwner = A2Cribs.Cache.SubletIdToOwnerMap[subletId];
        div.removeClass("single-listing");
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
        is_favorite = $(".favorite-clickable").hasClass("active");
        if (is_favorite) {
          div.find('.favorite-clickable').attr('onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ')');
        } else {
          div.find('.favorite-clickable').attr('onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ')');
        }
        content.find('.bubble-container').first().append(div);
      }
      this.InfoBubble.setContent(template.html());
      return $(".click-bubble:first").unwrap();
    };

    ClickBubble.prototype.setSingleContent = function(subletId) {
      var content, firstSublet, is_favorite, subletOwner, template;
      template = $(".click-bubble:first").wrap('<p/>').parent();
      content = template.children().first();
      firstSublet = A2Cribs.Cache.IdToSubletMap[subletId];
      subletOwner = A2Cribs.Cache.SubletIdToOwnerMap[subletId];
      template.children().addClass("single-listing");
      template.children().removeClass("multi-listing");
      if (firstSublet.Name) {
        content.find('.sublet-name').text(firstSublet.Name);
      } else {
        content.find('.sublet-name').text(firstSublet.StreetAddress);
      }
      content.find('.username').text(subletOwner.FirstName);
      if (subletOwner.FBUserId) {
        content.find('.friend-count').text(100);
      } else {
        content.find('.fb-mutual').hide();
      }
      content.find('.date-range').text(this.resolveDateRange(firstSublet.StartDate, firstSublet.EndDate));
      content.find('.bed-price').text(firstSublet.PricePerBedroom);
      content.find('.bed-count').text(firstSublet.Bedrooms);
      content.find('.building-type').text(firstSublet.BuildingType);
      content.find('.listing-popup-link').attr('onclick', 'A2Cribs.Map.ListingPopup.Open(' + subletId + ')');
      content.find('.listing-message').attr('onclick', 'A2Cribs.Map.ListingPopup.Message(' + subletId + ')');
      is_favorite = $(".favorite-clickable").hasClass("active");
      if (is_favorite) {
        content.find('.favorite-clickable').attr('onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ')');
      } else {
        content.find('.favorite-clickable').attr('onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ')');
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
