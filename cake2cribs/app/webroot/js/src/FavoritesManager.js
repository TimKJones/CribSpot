
/*
Static class handling all Favorites functionality.
Call functions using FavoritesManager.FunctionName()
*/

(function() {

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
      var fl_sidebar_item;
      response = JSON.parse(response);
      if (response.success === void 0) {
        if (response.error.message !== void 0) {
          return A2Cribs.UIManager.Alert(response.error.message);
        } else {
          return A2Cribs.UIManager.Alert("There was an error adding your favorite. Contact help@cribspot.com if the error persists.");
        }
      } else {
        this.FavoritesListingIds.push(listing_id);
        fl_sidebar_item = $("#fl-sb-item-" + listing_id);
        if (fl_sidebar_item.length === 1) {
          fl_sidebar_item.find(".favorite").attr('onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + listing_id + ', this);');
          fl_sidebar_item.find(".favorite").attr('title', 'Delete from Favorites');
          fl_sidebar_item.find(".favorite").addClass('active');
        }
        if (button != null) {
          $(button).attr('onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + listing_id + ', this);');
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
        return A2Cribs.UIManager.Alert(response.error.message);
      } else {
        index = A2Cribs.FavoritesManager.FavoritesListingIds.indexOf(listing_id);
        if (index !== -1) {
          A2Cribs.FavoritesManager.FavoritesListingIds.splice(index);
        }
        fl_sidebar_item = $("#fl-sb-item-" + listing_id);
        if (fl_sidebar_item.length === 1) {
          fl_sidebar_item.find(".favorite").attr('onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + listing_id + ', this);');
          fl_sidebar_item.find(".favorite").attr('title', 'Add to Favorites');
          fl_sidebar_item.find(".favorite").removeClass('active');
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
      A2Cribs.Map.ToggleListingVisibility(A2Cribs.FavoritesManager.FavoritesListingIds, A2Cribs.FavoritesManager.FavoritesVisible, button);
      A2Cribs.FavoritesManager.FavoritesVisible = !A2Cribs.FavoritesManager.FavoritesVisible;
      if (A2Cribs.FavoritesManager.FavoritesVisible) {
        return $("#FavoritesHeaderIcon").addClass("pressed");
      } else {
        return $("#FavoritesHeaderIcon").removeClass("pressed");
      }
    };

    FavoritesManager.FavoritesVisibilityIsOn = function() {
      return $("#FavoritesHeaderIcon").hasClass("pressed");
    };

    /*
    	Initialize a heart icon for adding favorites
    */

    FavoritesManager.setFavoriteButton = function(div, listing_id, favorites_list) {
      if (favorites_list.indexOf(parseInt(listing_id, 10)) === -1) {
        div.attr("onclick", "A2Cribs.FavoritesManager.AddFavorite(" + listing_id + ", this)");
        return div.removeClass("active");
      } else {
        div.attr("onclick", "A2Cribs.FavoritesManager.DeleteFavorite(" + listing_id + ", this)");
        return div.addClass("active");
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

}).call(this);
