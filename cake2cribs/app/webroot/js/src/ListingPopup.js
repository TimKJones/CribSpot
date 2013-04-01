
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
          'background-image': 'url(/img/tooltip/default_house.png)'
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
      if (housemates !== void 0 && housemates !== null) {
        content.find('.housemate-count').text(housemates.Quantity);
        content.find('.housemate-enrolled').text(housemates.Enrolled ? "Yes" : "No");
        content.find('.housemate-type').text(housemates.GradType);
        content.find('.housemate-major').text(housemates.Major);
        content.find('.housemate-gender').text(housemates.Gender);
        content.find('.housemate-year').text(housemates.Year);
      }
      content.find('.utilities-cost').text(sublet.UtilityCost === 0 ? "Included" : "$" + sublet.UtilityCost);
      content.find('.deposit-cost').text(sublet.DepositAmount === 0 ? "None" : "$" + sublet.DepositAmount);
      content.find('.additional-fee').text(sublet.AdditionalFeesAmount === 0 ? "None" : "$" + sublet.AdditionalFeesAmount);
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
