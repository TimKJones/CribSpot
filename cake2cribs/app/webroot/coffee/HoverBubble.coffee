###
HoverBubble class
Wrapper for google infobubble
###

class A2Cribs.HoverBubble
	###
	Constructor
	-creates infobubble object
	###
	constructor: (map) ->
		obj = 
			map: map
			arrowStyle: 0
			arrowPosition: 20
			shadowStyle: 1
			borderRadius: 5
			arrowSize: 17
			borderWidth: 0
			disableAutoPan: true
			padding: 7
			maxWidth: 300
			maxHeight: 200
			disableAnimation: true

		@InfoBubble = new InfoBubble obj
		@InfoBubble.hideCloseButton()
		#@InfoBubble.setBackgroundClassName "markerTooltip"

	###
	Opens the tooltip given a marker, with popping animation
	###
	Open: (marker) ->
		if marker
			@SetContent marker
			@InfoBubble.open A2Cribs.Map.GMap, marker.GMarker

	###
	Refreshes the tooltip with the new content, no animation
	###
	Refresh: () ->
		@InfoBubble.open()

	###
f	Closes the tooltip, no animation
	###
	Close: ->
		@InfoBubble.close()

	###
	Sets the content of the tooltip
	###
	SetContent: (marker) ->
		hoverdata =  A2Cribs.Cache.MarkerIdToHoverDataMap[marker.MarkerId]
		template = $(".hover-bubble:first").wrap('<p/>').parent()
		content = template.children().first();
		if hoverdata.NumListings > 1
			content.find('.hover-listing-count').text hoverdata.NumListings + " Listings:"
			if hoverdata.MinBeds is hoverdata.MaxBeds
				content.find('.hover-bed-count').text hoverdata.MinBeds
			else
				content.find('.hover-bed-count').text hoverdata.MinBeds + "-" + hoverdata.MaxBeds
			if hoverdata.MinRent is hoverdata.MaxRent
				content.find('.hover-price').text "$" + hoverdata.MinRent
			else
				content.find('.hover-price').text "$" + hoverdata.MinRent + "-$" + hoverdata.MaxRent
		else
			content.find('.hover-listing-count').empty()
			content.find('.hover-bed-count').text hoverdata.MinBeds
			content.find('.hover-price').text "$" + hoverdata.MinRent
		
		content.find('.hover-apt-type').text hoverdata.UnitType
		content.find('.hover-date-range').text @resolveDate(hoverdata.MinDate, hoverdata.MaxDate)
		@InfoBubble.setContent template.html()
		$(".hover-bubble:first").unwrap()

	resolveDate: (minDate, maxDate) ->
		minSplit = minDate.split "-"
		maxSplit = maxDate.split "-"
		+minSplit[1] + "/" + +minSplit[2] + "-" + +maxSplit[1] + "/" + +maxSplit[2]

