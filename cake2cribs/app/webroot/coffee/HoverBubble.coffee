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
		@template.find(".building_type").text A2Cribs.Marker.BuildingType[+marker.building_type_id]
		@template.find(".unit_div").empty()
		for listing in listings
			listing_info = A2Cribs.UserCache.Get A2Cribs.Map.ACTIVE_LISTING_TYPE, listing.GetId()
			unit_template = @template.find('.templates:first').children().clone()
			unit_template.attr "onclick", "A2Cribs.ClickBubble.Open(#{listing.GetId()})"
			for key,value of listing_info
				if key is "rent"
					unit_template.find(".#{key}").text "$#{value}"
				else if key is "beds"
					num_beds = parseInt value, 10
					if num_beds is 0
						unit_template.find(".#{key}").text "Studio"
						unit_template.find(".bed_desc").text ""
					else
						unit_template.find(".#{key}").text num_beds
						unit_template.find(".bed_desc").text if num_beds is 1 then "Bed" else "Beds"
			@template.find(".unit_div").append unit_template



		@InfoBubble.setContent @template.html()

	@resolveDate: (minDate, maxDate) ->
		minSplit = minDate.split "-"
		maxSplit = maxDate.split "-"
		+minSplit[1] + "/" + +minSplit[2] + "-" + +maxSplit[1] + "/" + +maxSplit[2]

