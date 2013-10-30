class MapActivity

	@MonitorClickBubbleOpen: (listing_id) ->
		@_clickbubble_count += 1
		if not A2Cribs.Login?.logged_in
			if @_clickbubble_count > @_increments[@_increment_index]
				@_increment_index += 1
				$("#signup_modal").modal("show").find(".signup_message").text "Join the party! Sign up for Cribspot today."
				A2Cribs.MixPanel.Event "login required",
					"listing_id": listing_id
					action: "click limit"
					"click count": @_clickbubble_count


	$("#map_canvas").ready =>
		@_increments = [3, 10, 15, 20, 25, 10000000]
		@_increment_index = 0
		@_clickbubble_count = 0
		$("#map_canvas").on "click_bubble_open", (event, listing_id) => 
			@MonitorClickBubbleOpen listing_id