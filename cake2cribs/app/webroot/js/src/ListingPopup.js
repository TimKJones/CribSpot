
/*
ListingPopup class
*/

(function() {
  var __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

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
        this.SetContent(subletId);
        $(".side-pane").hide();
        $("#overview").show();
        return this.modal.modal('show');
      }
    };

    ListingPopup.prototype.Message = function(subletId) {
      if (subletId != null) {
        this.SetContent(subletId);
        $(".side-pane").hide();
        $("#contact").show();
        $('#message-button').hide();
        $("#verify-table").hide();
        $("#message-area").show();
        $("#message-submit-buttons").show();
        $("#message-area").focus();
        return this.modal.modal('show');
      }
    };

    ListingPopup.prototype.OpenLoaded = function(sublet) {
      if (sublet != null) {
        this.SetPreloadedContent(sublet);
        $(".side-pane").hide();
        $("#overview").show();
        return this.modal.modal('show');
      }
    };

    /*
    	Closes the tooltip, no animation
    */

    ListingPopup.prototype.Close = function() {
      return this.modal.modal('hide');
    };

    ListingPopup.prototype.SetPreloadedContent = function(subletObject) {
      var content, is_favorite, sublet, subletId, template;
      template = $(".listing-popup:first").wrap('<p/>').parent();
      content = template.children().first();
      sublet = subletObject.Sublet;
      content.find('#sublet-id').text(sublet.id);
      if (subletObject.Marker.alternate_name) {
        content.find('.sublet-name').text(subletObject.Marker.alternate_name);
      } else {
        content.find('.sublet-name').text(subletObject.Marker.street_address);
      }
      content.find('.bed-price').text(sublet.price_per_bedroom);
      content.find('.full-date').text(this.resolveDateRange(sublet.date_begin, sublet.date_end));
      content.find('.building-type').text(subletObject.BuildingType.name);
      content.find('.school-name').text(subletObject.User.university_verified);
      content.find('.full-address').text(subletObject.Marker.street_address + ", " + subletObject.Marker.city + ", " + subletObject.Marker.state);
      content.find('.bath-type').text(subletObject.BathroomType.name);
      content.find('.parking-avail').text("LOL");
      content.find('.ac-avail').text("Maybe");
      content.find('.furnish-avail').text(subletObject.FurnishedType.name);
      content.find('.first-name').text(subletObject.User.first_name);
      content.find('.short-description').find('p').text(sublet.description);
      subletId = sublet.id;
      is_favorite = __indexOf.call(A2Cribs.Cache.FavoritesSubletIdsList, subletId) >= 0;
      if (is_favorite) {
        content.find('.favorite-clickable').attr('title', 'Delete from Favorites');
        content.find('.favorite-clickable').attr('onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ')');
        $('#favorite-btn').addClass("active");
        $('#favorite-btn').addClass("btn-danger");
      } else {
        content.find('.favorite-clickable').attr('title', 'Add to Favorites');
        content.find('.favorite-clickable').attr('onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ')');
        if ($('#favorite-btn').hasClass("active")) {
          $('#favorite-btn').removeClass("active");
        }
        if ($('#favorite-btn').hasClass("btn-danger")) {
          $('#favorite-btn').removeClass("btn-danger");
        }
      }
      return $(".listing-popup:first").unwrap();
    };

    /*
    	Sets the content of the tooltip
    */

    ListingPopup.prototype.SetContent = function(subletId) {
      var content, is_favorite, sublet, template;
      template = $(".listing-popup:first").wrap('<p/>').parent();
      content = template.children().first();
      sublet = A2Cribs.Cache.IdToSubletMap[subletId];
      content.find('#sublet-id').text(subletId);
      content.find('.sublet-name').text(sublet.Name ? sublet.Name : sublet.StreetAddress);
      content.find('.bed-price').text(sublet.PricePerBedroom);
      content.find('.full-date').text(this.resolveDateRange(sublet.StartDate, sublet.EndDate));
      content.find('.building-type').text(sublet.BuildingType);
      content.find('.school-name').text(A2Cribs.Cache.SubletIdToOwnerMap[subletId].VerifiedUniversity);
      content.find('.full-address').text(sublet.StreetAddress + ", " + sublet.City + ", " + sublet.State);
      content.find('.bath-type').text(sublet.BathroomType);
      content.find('.parking-avail').text("LOL");
      content.find('.ac-avail').text("Maybe");
      content.find('.furnish-avail').text(sublet.Furnished ? "Fully" : "No");
      content.find('.first-name').text(A2Cribs.Cache.SubletIdToOwnerMap[subletId].FirstName);
      content.find('.short-description').find('p').text(sublet.Description);
      subletId = sublet.SubletId;
      is_favorite = __indexOf.call(A2Cribs.Cache.FavoritesSubletIdsList, subletId) >= 0;
      if (is_favorite) {
        content.find('.favorite-clickable').attr('title', 'Delete from Favorites');
        content.find('.favorite-clickable').attr('onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ')');
        $('#favorite-btn').addClass("active");
        $('#favorite-btn').addClass("btn-danger");
      } else {
        content.find('.favorite-clickable').attr('title', 'Add to Favorites');
        content.find('.favorite-clickable').attr('onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ')');
        if ($('#favorite-btn').hasClass("active")) {
          $('#favorite-btn').removeClass("active");
        }
        if ($('#favorite-btn').hasClass("btn-danger")) {
          $('#favorite-btn').removeClass("btn-danger");
        }
      }
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

    return ListingPopup;

  })();

}).call(this);
