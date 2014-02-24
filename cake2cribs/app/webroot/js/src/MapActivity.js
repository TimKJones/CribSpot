// Generated by CoffeeScript 1.6.3
(function() {
  var MapActivity;

  MapActivity = (function() {
    var _this = this;

    function MapActivity() {}

    MapActivity.MonitorClickBubbleOpen = function(listing_id) {
      var _ref;
      this._clickbubble_count += 1;
      if (!((_ref = A2Cribs.Login) != null ? _ref.logged_in : void 0)) {
        if (this._clickbubble_count > this._increments[this._increment_index]) {
          this._increment_index += 1;
          $("#signup_modal").modal("show").find(".signup_message").text("Join the party! Sign up for Cribspot today.");
          return $(document).trigger("track_event", ["Login", "Login Required", "", listing_id]);
        }
      }
    };

    $("#map_canvas").ready(function() {
      MapActivity._increments = [10, 15, 20, 25, 10000000];
      MapActivity._increment_index = 0;
      MapActivity._clickbubble_count = 0;
      return $("#map_canvas").on("click_bubble_open", function(event, listing_id) {
        return MapActivity.MonitorClickBubbleOpen(listing_id);
      });
    });

    return MapActivity;

  }).call(this);

}).call(this);
