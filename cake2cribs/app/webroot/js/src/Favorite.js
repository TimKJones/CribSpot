(function() {

  A2Cribs.Favorite = (function() {

    function Favorite(FavoriteId, ListingId, UserId) {
      this.FavoriteId = FavoriteId;
      this.ListingId = ListingId;
      this.UserId = UserId;
    }

    return Favorite;

  })();

}).call(this);
