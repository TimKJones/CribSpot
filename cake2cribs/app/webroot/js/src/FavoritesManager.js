
/*
Static class handling all Favorites functionality.
Call functions using FavoritesManager.FunctionName()
*/

(function() {

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

}).call(this);
