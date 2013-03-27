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
		template = $(".click-bubble:first").wrap('<p/>').parent()
		content = template.children().first()
		content.find('.listings').empty()
		dataTemplate = content.find('.listing-block').first()

		content.find('#listing-count').text subletIds.length
		if (marker.Title)
			content.find('.sublet-name').text marker.Title
		else
			content.find('.sublet-name').text marker.Address
		
		if subletIds.length is 1
			content.addClass "single-listing"
			content.removeClass "multi-listing"
		else
			content.addClass "multi-listing"
			content.removeClass "single-listing"
		for subletId in subletIds
			div = dataTemplate.clone()
			div.removeClass "hide"
			firstSublet = A2Cribs.Cache.IdToSubletMap[subletId]
			subletOwner = A2Cribs.Cache.SubletIdToOwnerMap[subletId]
			div.removeClass "single-content"
			div.find('.username').text subletOwner.FirstName
			if subletOwner.FBUserId
				div.find('.friend-count').text 100
			else
				div.find('.fb-mutual').hide()

			div.find('.date-range').text @resolveDateRange firstSublet.StartDate, firstSublet.EndDate
			div.find('.bed-price').text firstSublet.PricePerBedroom
			div.find('.bed-count').text firstSublet.Bedrooms
			div.find('.building-type').text firstSublet.BuildingType
			div.find('.listing-popup-link').attr 'onclick', 'A2Cribs.Map.ListingPopup.Open(' + subletId + ')'
			div.find('.listing-message').attr 'onclick', 'A2Cribs.Map.ListingPopup.Message(' + subletId + ')'
			is_favorite = subletId in A2Cribs.Cache.FavoritesSubletIdsList
			if is_favorite
				div.find('.favorite-clickable').attr 'title', 'Delete from Favorites'
				div.find('.favorite-clickable').attr 'onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ')'
			else
				div.find('.favorite-clickable').attr 'title', 'Add to Favorites'
				div.find('.favorite-clickable').attr 'onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ')'
			content.find('.listings').append div
		
		@InfoBubble.setContent template.html()
		$(".click-bubble:first").unwrap()

	resolveDateRange: (startDate, endDate) ->
		rmonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
		range = ""
		startSplit = startDate.split "-"
		endSplit = endDate.split "-"
		range += rmonth[startSplit[1] - 1]
		range += " " + parseInt(startSplit[2]) + "-"
		range + rmonth[endSplit[1] - 1] + " " + parseInt endSplit[2]


