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
		if marker
			@SetContent marker
			@InfoBubble.open A2Cribs.Map.GMap, marker.GMarker

	###
	Refreshes the tooltip with the new content, no animation
	###
	@Refresh: () ->
		@InfoBubble.open()

	###
f	Closes the tooltip, no animation
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
		for listing in listings
			if not listing.visible? or listing.visible
				listing_info = A2Cribs.UserCache.Get A2Cribs.Map.ACTIVE_LISTING_TYPE, listing.GetId()
				unit_template = $ "<div />",
					class: "unit"
				unit_template.attr "onclick", "A2Cribs.ClickBubble.Open(#{listing.GetId()})"
				$ "<div />",
					class: "beds"
					text: listing_info["beds"]
				.appendTo unit_template
				$ "<div />",
					class: "bed_desc"
					text: if listing_info["beds"]? is 1 then "Bed" else "Beds"
				.appendTo unit_template
				$ "<div />",
					class: "rent"
					text: "$#{listing_info["rent"]}"
				.appendTo unit_template

				@template.find(".unit_div").append unit_template

		@InfoBubble.setContent @template.html()

	@resolveDate: (minDate, maxDate) ->
		minSplit = minDate.split "-"
		maxSplit = maxDate.split "-"
		+minSplit[1] + "/" + +minSplit[2] + "-" + +maxSplit[1] + "/" + +maxSplit[2]

