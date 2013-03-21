###
ClickBubble class
Wrapper for google infobubble
###

class A2Cribs.ClickBubble
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
			maxWidth: 350
			maxHeight: 400
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
		#Find 
		template = $(".click-bubble:first").wrap('<p/>').parent()
		@InfoBubble.setContent template.html()
		$(".click-bubble:first").unwrap()

