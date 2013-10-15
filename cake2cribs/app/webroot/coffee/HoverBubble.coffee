###
HoverBubble class
Wrapper for google infobubble
###

class A2Cribs.HoverBubble
	###
	Constructor
	-creates infobubble object
	###
	@Init: (map) ->
		@template = $(".hover-bubble:first").parent()
		obj = 
			map: map
			arrowStyle: 0
			arrowPosition: 20
			shadowStyle: 0
			borderRadius: 5
			arrowSize: 17
			borderWidth: 0
			disableAutoPan: true
			padding: 0
			disableAnimation: true

		@InfoBubble = new InfoBubble obj
		@InfoBubble.hideCloseButton()
		@InfoBubble.setBackgroundClassName "map_bubble"
		@template.find(".close_button").attr "onclick", "A2Cribs.HoverBubble.Close();"

	###
	Opens the tooltip given a marker, with popping animation
	###
	@Open: (marker) ->
		@Close()
		A2Cribs.ClickBubble?.Close()
		if marker
			@SetContent marker
			# Pan map to leave enough room for click bubble
			marker_pixel_position = A2Cribs.ClickBubble.ConvertLatLongToPixels marker.GMarker.getPosition()
			pixels_to_pan = A2Cribs.ClickBubble.GetAdjustedClickBubblePosition marker_pixel_position.x, marker_pixel_position.y
			A2Cribs.Map.GMap.panBy pixels_to_pan.x, pixels_to_pan.y

			@InfoBubble.open A2Cribs.Map.GMap, marker.GMarker


	###
	Refreshes the tooltip with the new content, no animation
	###
	@Refresh: () ->
		@InfoBubble.open()

	###
	Closes the tooltip, no animation
	###
	@Close: ->
		@InfoBubble.close()

	###
	Sets the content of the tooltip
	###
	@SetContent: (marker) ->
		listings = A2Cribs.UserCache.GetAllAssociatedObjects "listing", "marker", marker.GetId()
		@template.find(".building_type").text marker.GetBuildingType()
		@template.find(".unit_div").empty()
		sortedListings = listings.sort (a, b) ->
			listing_a = A2Cribs.UserCache.Get A2Cribs.Map.ACTIVE_LISTING_TYPE, a.GetId()
			listing_b = A2Cribs.UserCache.Get A2Cribs.Map.ACTIVE_LISTING_TYPE, b.GetId()
			if not listing_a.rent? and not listing_b.rent?
				return 0
			else if listing_a.rent? and not listing_b.rent?
				return 1
			else if not listing_a.rent? and listing_b.rent?
				return -1
			return parseInt(listing_a.rent, 10) - parseInt(listing_b.rent, 10);

		for listing in sortedListings
			if not listing.visible? or listing.visible
				listing_info = A2Cribs.UserCache.Get A2Cribs.Map.ACTIVE_LISTING_TYPE, listing.GetId()

				codes = (k for k of listings)
				sortedCodes = codes.sort (a, b) -> listings[b] - listings[a]

				bed_count = listing_info.beds
				bed_desc = "Beds"

				if not listing_info["beds"]?
					bed_count = "??"
				else if parseInt(listing_info["beds"], 10) is 0
					bed_count = "Studio"
					bed_desc = ""
				else if parseInt(listing_info["beds"], 10) is 1
					bed_desc = "Bed"

				available_dot = "unknown"
				if listing.available? and listing.available is yes
					available_dot = "available"
				else if listing.available? and listing.available isnt yes
					available_dot = "leased"

				unit_template = $ "<div />",
					class: "unit"
				unit_template.attr "onclick", "A2Cribs.ClickBubble.Open(#{listing.GetId()})"
				$ "<div />",
					class: "dot #{available_dot}"
				.appendTo unit_template
				$ "<div />",
					class: "beds"
					text: bed_count
				.appendTo unit_template
				$ "<div />",
					class: "bed_desc"
					text: bed_desc
				.appendTo unit_template
				$ "<div />",
					class: "rent"
					text: if listing_info["rent"]? then "$#{listing_info["rent"]}" else "??"
				.appendTo unit_template

				@template.find(".unit_div").append unit_template

		@InfoBubble.setContent @template.html()

	@resolveDate: (minDate, maxDate) ->
		minSplit = minDate.split "-"
		maxSplit = maxDate.split "-"
		+minSplit[1] + "/" + +minSplit[2] + "-" + +maxSplit[1] + "/" + +maxSplit[2]

