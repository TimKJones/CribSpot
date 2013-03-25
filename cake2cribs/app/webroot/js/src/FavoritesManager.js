
/*
Static class handling all Favorites functionality.
Call functions using FavoritesManager.FunctionName()
*/

(function() {
  var __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  A2Cribs.FavoritesManager = (function() {

    function FavoritesManager() {}

    FavoritesManager.FavoritesCache = {
      size: 0
    };

    /*
    	Add a favorite
    */

    FavoritesManager.AddFavorite = function(sublet_id) {
      $('.favorite-clickable').addClass("active");
      $('.favorite-clickable').attr({
        title: "Delete from Favorites",
        onclick: "A2Cribs.FavoritesManager.DeleteFavorite(" + sublet_id + ")"
      });
      return $.ajax({
        url: myBaseUrl + "Favorites/AddFavorite/" + sublet_id,
        type: "POST",
        context: this,
        success: function(response) {
          return A2Cribs.FavoritesManager.AddFavoriteCallback(response, sublet_id);
        }
      });
    };

    FavoritesManager.AddFavoriteCallback = function(response, sublet_id) {
      response = JSON.parse(response);
      if (response.SUCCESS === void 0) {
        A2Cribs.UIManager.Alert("There was an error adding your favorite. Contact help@cribspot.com if this error persists.");
        $('.favorite-clickable').removeClass("active");
        return $('.favorite-clickable').attr({
          title: "Add to Favorites",
          onclick: "A2Cribs.FavoritesManager.AddFavorite(" + sublet_id + ")"
        });
      }
    };

    /*
    	Delete a favorite
    */

    FavoritesManager.DeleteFavorite = function(sublet_id) {
      $('.favorite-clickable').removeClass("active");
      $('.favorite-clickable').attr({
        title: "Add to Favorites",
        onclick: "A2Cribs.FavoritesManager.AddFavorite(" + sublet_id + ")"
      });
      return $.ajax({
        url: myBaseUrl + "Favorites/DeleteFavorite/" + sublet_id,
        type: "POST",
        context: this,
        success: function(response) {
          return A2Cribs.FavoritesManager.DeleteFavoriteCallback(response, sublet_id);
        }
      });
    };

    FavoritesManager.DeleteFavoriteCallback = function(response, sublet_id) {
      response = JSON.parse(response);
      if (response.SUCCESS === void 0) {
        A2Cribs.UIManager.Alert("There was an error deleting your favorite. Contact help@cribspot.com if this error persists.");
        $('.favorite-clickable').addClass("active");
        return $('.favorite-clickable').attr({
          title: "Delete from Favorites",
          onclick: "A2Cribs.FavoritesManager.DeleteFavorite(" + sublet_id + ")"
        });
      }
    };

    FavoritesManager.InitializeFavorites = function(response) {
      var marker_id, marker_ids, sublet_id, sublet_ids, _i, _j, _len, _len2, _results;
      response = JSON.parse(response);
      if (response === null || response === void 0 || response[0] === void 0 || response[1] === void 0) {
        return;
      }
      sublet_ids = response[0];
      marker_ids = response[1];
      for (_i = 0, _len = sublet_ids.length; _i < _len; _i++) {
        sublet_id = sublet_ids[_i];
        A2Cribs.Cache.FavoritesSubletIdsList.push(parseInt(sublet_id.Favorite.sublet_id));
      }
      _results = [];
      for (_j = 0, _len2 = marker_ids.length; _j < _len2; _j++) {
        marker_id = marker_ids[_j];
        _results.push(A2Cribs.Cache.FavoritesMarkerIdsList.push(parseInt(marker_id.Sublet.marker_id)));
      }
      return _results;
    };

    /*
    	Loads all favorites for current user.
    */

    FavoritesManager.LoadFavorites = function() {
      return $.ajax({
        url: myBaseUrl + "Favorites/LoadFavorites",
        type: "GET",
        context: this,
        success: this.InitializeFavorites
      });
    };

    FavoritesManager.ToggleFavoritesVisibility = function() {
      var marker, marker_id, markerid, _len, _len2, _ref, _ref2;
      if (!A2Cribs.FavoritesManager.FavoritesVisibilityIsOn()) {
        $("#FavoritesHeaderIcon").addClass("pressed");
        _ref = A2Cribs.Cache.IdToMarkerMap;
        for (markerid = 0, _len = _ref.length; markerid < _len; markerid++) {
          marker = _ref[markerid];
          if (__indexOf.call(A2Cribs.Cache.FavoritesMarkerIdsList, markerid) >= 0) {
            if (marker) marker.GMarker.setVisible(true);
          } else {
            if (marker) marker.GMarker.setVisible(false);
          }
        }
      } else {
        _ref2 = A2Cribs.Cache.IdToMarkerMap;
        for (marker_id = 0, _len2 = _ref2.length; marker_id < _len2; marker_id++) {
          marker = _ref2[marker_id];
          if (marker) marker.GMarker.setVisible(true);
        }
        $("#FavoritesHeaderIcon").removeClass("pressed");
      }
      return A2Cribs.Map.GMarkerClusterer.repaint();
    };

    FavoritesManager.FavoritesVisibilityIsOn = function() {
      return $("#FavoritesHeaderIcon").hasClass("pressed");
    };

    /*
    	Inserts the recent favorite into the favorites tab
    */

    FavoritesManager._insertIntoFavoriteDiv = function(sublet_id) {
      var content, marker, sublet, template, title;
      if (this.FavoritesCache.size === 1) $('#noFavorites').hide();
      sublet = A2Cribs.Map.IdToSubletMap[sublet_id];
      marker = A2Cribs.Map.IdToMarkerMap[sublet.MarkerId];
      title = marker.Title ? marker.Title : marker.Address;
      template = $('#favoriteTemplate');
      template.find('.favoriteDiv').attr({
        id: "favoriteDiv" + sublet_id
      });
      template.find('.favoritesAddress').html(title);
      template.find('.removeButton').attr({
        onclick: "A2Cribs.FavoritesManager.DeleteFavorite(" + sublet_id + ")"
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
