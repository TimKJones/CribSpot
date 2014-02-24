class MapActivity

	@MonitorClickBubbleOpen: (listing_id) ->
		@_clickbubble_count += 1
		if not A2Cribs.Login?.logged_in
			if @_clickbubble_count > @_increments[@_increment_index]
				@_increment_index += 1
				$("#signup_modal").modal("show").find(".signup_message").text "Join the party! Sign up for Cribspot today."
				$(document).trigger "track_event", ["Login", "Login Required", "", listing_id]

	$("#map_canvas").ready =>
		@_increments = [10, 15, 20, 25, 10000000]
		@_increment_index = 0
		@_clickbubble_count = 0
		$("#map_canvas").on "click_bubble_open", (event, listing_id) =>
			@MonitorClickBubbleOpen listing_id
