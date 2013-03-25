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
		content = template.children().first()
		firstSublet = A2Cribs.Cache.IdToSubletMap[subletIds[0]]
		content.addClass "multi-listing"
		content.removeClass "single-listing"
		content.find('#listing-count').text subletIds.length
		if (firstSublet.Name)
			content.find('.sublet-name').text firstSublet.Name
		else
			content.find('.sublet-name').text firstSublet.StreetAddress

		dataTemplate = content.find('.click-bubble-data').first()
		content.find('.bubble-container').first().empty()
		for subletId in subletIds
			div = dataTemplate.clone()
			firstSublet = A2Cribs.Cache.IdToSubletMap[subletId]
			subletOwner = A2Cribs.Cache.SubletIdToOwnerMap[subletId]
			div.removeClass "single-listing"
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
			is_favorite = $(".favorite-clickable").hasClass("active")
			if is_favorite
				div.find('.favorite-clickable').attr 'onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ')'
			else
				div.find('.favorite-clickable').attr 'onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ')'
			content.find('.bubble-container').first().append div

		@InfoBubble.setContent template.html()
		$(".click-bubble:first").unwrap()

	setSingleContent: (subletId) ->
		template = $(".click-bubble:first").wrap('<p/>').parent()
		content = template.children().first()
		firstSublet = A2Cribs.Cache.IdToSubletMap[subletId]
		subletOwner = A2Cribs.Cache.SubletIdToOwnerMap[subletId]
		template.children().addClass "single-listing"
		template.children().removeClass "multi-listing"
		if (firstSublet.Name)
			content.find('.sublet-name').text firstSublet.Name
		else
			content.find('.sublet-name').text firstSublet.StreetAddress
		content.find('.username').text subletOwner.FirstName
		if subletOwner.FBUserId
			content.find('.friend-count').text 100
		else
			content.find('.fb-mutual').hide()
		content.find('.date-range').text @resolveDateRange firstSublet.StartDate, firstSublet.EndDate
		content.find('.bed-price').text firstSublet.PricePerBedroom
		content.find('.bed-count').text firstSublet.Bedrooms
		content.find('.building-type').text firstSublet.BuildingType
		content.find('.listing-popup-link').attr 'onclick', 'A2Cribs.Map.ListingPopup.Open(' + subletId + ')'
		content.find('.listing-message').attr 'onclick', 'A2Cribs.Map.ListingPopup.Message(' + subletId + ')'
		is_favorite = $(".favorite-clickable").hasClass("active")
		if is_favorite
			content.find('.favorite-clickable').attr 'onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ')'
		else
			content.find('.favorite-clickable').attr 'onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ')'

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



