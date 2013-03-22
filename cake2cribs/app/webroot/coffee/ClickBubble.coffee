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
		subletIds = A2Cribs.Cache.MarkerIdToSubletIdsMap[marker.MarkerId]
		if subletIds.length > 1
			@setMultiContent subletIds
		else
			@setSingleContent subletIds
		



	setMultiContent: (subletIds) ->
		template = $(".click-bubble:first").wrap('<p/>').parent()
		content = template.children()
		firstSublet = A2Cribs.Cache.IdToSubletMap subletIds[0]
		content.addClass "multi-listing"
		content.removeClass "single-listing"
		content.find('#listing-count').text subletIds.length
		content.find('.sublet-name').text firstSublet.Name

		@InfoBubble.setContent template.html()
		$(".click-bubble:first").unwrap()

	setSingleContent: (subletId) ->
		template = $(".click-bubble:first").wrap('<p/>').parent()
		content = template.children()
		firstSublet = A2Cribs.Cache.IdToSubletMap[subletId[0]]
		template.children().addClass "single-listing"
		template.children().removeClass "multi-listing"
		content.find('.sublet-name').text firstSublet.Name
		content.find('.username').text "Evan"
		content.find('.date-range').text @resolveDateRange firstSublet.StartDate, firstSublet.EndDate
		content.find('.bed-price').text firstSublet.PricePerBedroom
		content.find('.bed-count').text firstSublet.Bedrooms
		content.find('.building-type').text firstSublet.BuildingType

		@InfoBubble.setContent template.html()
		$(".click-bubble:first").unwrap()

	resolveDateRange: (startDate, endDate) ->
		rmonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"]
		range = ""
		startSplit = startDate.split "-"
		endSplit = endDate.split "-"
		range += rmonth[startSplit[1] - 1]
		range += " " + parseInt(startSplit[2]) + "-"
		range + rmonth[endSplit[1] - 1] + " " + parseInt endSplit[2]



