
/*
Static class handling all Favorites functionality.
Call functions using FavoritesManager.FunctionName()
*/

(function() {

  A2Cribs.FavoritesManager = (function() {

    function FavoritesManager() {}

    FavoritesManager.FavoritesCache = {
      size: 0
    };

    /*
    	Add a favorite
    */

    FavoritesManager.AddFavorite = function(listing_id) {
      $('#addFavoriteImg').toggleClass("starNotFavorite starFavorite");
      $('#addFavoriteImg').attr({
        title: "Delete from Favorites",
        onclick: "A2Cribs.FavoritesManager.DeleteFavorite(" + listing_id + ")"
      });
      return $.ajax({
        url: myBaseUrl + "Favorites/AddFavorite/" + listing_id,
        type: "POST",
        context: this,
        success: function() {
          this._insertFavoriteCache(listing_id);
          return this._insertIntoFavoriteDiv(listing_id);
        }
      });
    };

    /*
    	Delete a favorite
    */

    FavoritesManager.DeleteFavorite = function(listing_id) {
      $('#addFavoriteImg').toggleClass("starFavorite starNotFavorite");
      $('#addFavoriteImg').attr({
        title: "Add to Favorites",
        onclick: "A2Cribs.FavoritesManager.AddFavorite(" + listing_id + ")"
      });
      return $.ajax({
        url: myBaseUrl + "Favorites/DeleteFavorite/" + listing_id,
        type: "POST",
        context: this,
        success: function() {
          this._removeFavoriteCache(listing_id);
          return this._removeFromFavoriteDiv(listing_id);
        }
      });
    };

    FavoritesManager.InitializeFavorites = function(favoritesList) {
      var favorite, favorites, _i, _len, _results;
      favorites = JSON.parse(favoritesList);
      A2Cribs.Map.CacheSublets(favorites);
      _results = [];
      for (_i = 0, _len = favorites.length; _i < _len; _i++) {
        favorite = favorites[_i];
        this._insertFavoriteCache(favorite.Listing.listing_id);
        _results.push(this._insertIntoFavoriteDiv(favorite.Listing.listing_id));
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
