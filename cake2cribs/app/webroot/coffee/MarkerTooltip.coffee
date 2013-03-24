###
MarkerTooltip class
Wrapper for google infobubble
###

class A2Cribs.MarkerTooltip
	###
	Constructor
	-creates infobubble object
	###
	constructor: (map) ->
		obj = 
			map: map
			arrowStyle: 0
			arrowPosition: 20
			backgroundColor:'#333333'
			shadowStyle: 1
			borderRadius: 5
			arrowSize: 17
			borderWidth: 0
			disableAutoPan: true
			padding: 7

		@InfoBubble = new InfoBubble obj
		@InfoBubble.setBackgroundClassName "markerTooltip"
		@previousContent = ''

	###
	Opens the tooltip given a marker, with popping animation
	###
	Open: (marker) ->
		if marker
			@InfoBubble.open A2Cribs.Map.GMap, marker
		else
			@InfoBubble.open()

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
	SetContent: (content) ->
		@InfoBubble.setContent content

	###
	Sets the content and opens tooltip over marker
	###
	Display: (visibleIds, marker) ->
		if visibleIds.length
			@CreateContent visibleIds
			@Open marker

	###
	Creates the content based on how many listings on the marker
	###
	CreateContent: (visibleIds, fromMultipleListings = false) ->
		if visibleIds.length < 2 then @createSingleContent_ visibleIds, fromMultipleListings else @CreateMultipleContent visibleIds

	###
	Creates single listing content based on the unit type
	###
	createSingleContent_: (visibleId, fromMultipleListings) ->
		if A2Cribs.Map.IdToSubletMap[visibleId[0]].UnitType is "Greek"
			@createGreekContent_ visibleId
		else
			@createGeneralContent_ visibleId, fromMultipleListings

	###
	Creates single listing content for Greek Housing
	###
	createGreekContent_: (visibleId) ->
		@SetHeight 80
		@SetWidth 210

	###
	Creates single listing content for General Housing
	###
	createGeneralContent_: (visibleId, fromMultipleListings) ->
		@SetHeight 198
		@SetWidth 250
		@previousContent = if fromMultipleListings then @InfoBubble.getContent() else null
		visibleListing = A2Cribs.Map.IdToSubletMap[visibleId]
		visibleMarker = A2Cribs.Map.IdToMarkerMap[visibleListing.MarkerId]
		title = if visibleMarker.Title then visibleMarker.Title else visibleMarker.Address

		tooltipDiv = $('#generalTooltip')
		tooltipDiv.find('#tooltipAddress').html title
		tooltipDiv.find('a').attr href: visibleListing.Url
		if fromMultipleListings
			tooltipDiv.find('.backToMultipleListings').show()
		else
			tooltipDiv.find('.backToMultipleListings').hide()
		# favorite
		if A2Cribs.FavoritesManager.FavoritesCache[visibleId]?
			tooltipDiv.find('#addFavoriteImg').addClass "starFavorite"
			tooltipDiv.find('#addFavoriteImg').removeClass "starNotFavorite"
			tooltipDiv.find('#addFavoriteImg').attr title: "Delete from Favorites"
			tooltipDiv.find('#addFavoriteImg').attr onclick: "A2Cribs.FavoritesManager.DeleteFavorite(" + visibleId + ")"
		else
			tooltipDiv.find('#addFavoriteImg').addClass "starNotFavorite"
			tooltipDiv.find('#addFavoriteImg').removeClass "starFavorite"
			tooltipDiv.find('#addFavoriteImg').attr title: "Add to Favorites"
			tooltipDiv.find('#addFavoriteImg').attr onclick: "A2Cribs.FavoritesManager.AddFavorite(" + visibleId + ")"
		
		tooltipDiv.find('#tooltipPrice').html if visibleListing.Rent then '$' + visibleListing.Rent else "Not Available"
		tooltipDiv.find('#tooltipBeds').html visibleListing.Beds + if visibleListing.Beds > 1 then " Beds" else " Bed"
		tooltipDiv.find('#tooltipBaths').html visibleListing.Baths + if visibleListing.Baths > 1 then " Baths" else " Bath"
		tooltipDiv.find('#tooltipLeaseRange').html visibleListing.LeaseRange
		tooltipDiv.find('#tooltipType').html visibleListing.UnitType
		tooltipDiv.find('#tooltipFurnished').html if visibleListing.Furnished is "Y" then "Yes" else "No"
		tooltipDiv.find('#tooltipParking').html if visibleListing.Parking is "Y" then "Yes" else "No"
		tooltipDiv.find('#tooltipAir').html if visibleListing.Air is "Y" then "Yes" else "No"
		tooltipDiv.find('#tooltipCompany').html A2Cribs.Map.IdToRealtorMap[visibleListing.RealtorId].Company
		utilities = ''
		utilities += "Water, " if visibleListing.Water is "Y"
		utilities += "Heat, " if visibleListing.Heat is "Y"
		utilities += "Electric, " if visibleListing.Electric is "Y"
		tooltipDiv.find('#tooltipUtilities').html if utilities.length then utilities.substring 0, utilities.length - 2 else "Not Included"

		content = $('#generalTooltip').html()
		@SetContent content
		@Refresh() if fromMultipleListings

	###
	Creates bubbles for multiple listings on a single marker
	###
	CreateMultipleContent: (visibleIds) ->
		@SetHeight 198
		@SetWidth 250
		if not visibleIds?
			@SetContent @previousContent
			return @Refresh()

		rootMarker = A2Cribs.Map.IdToMarkerMap[A2Cribs.Map.IdToSubletMap[visibleIds[0]].MarkerId];
		title = if rootMarker.Title then rootMarker.Title else rootMarker.Address

		tooltipDiv = $('#multiTooltip')
		tooltipDiv.find('#tooltipAddress').html title
		tooltipDiv.find('#multiBubbleContainer').empty()

		for id in visibleIds
			do (id) ->
				currentListing = A2Cribs.Map.IdToSubletMap[id]
				unitSummary = if currentListing.Beds > 1 then currentListing.Beds + " Beds, " else currentListing.Beds + " Bed, "
				unitSummary += if currentListing.Baths > 1 then currentListing.Baths + " Baths, " else currentListing.Baths + " Baths, "
				unitSummary += currentListing.LeaseRange
				$('<div/>', 
					id: id
					class: 'multiBubble'
					html: '<b>' + currentListing.UnitDescription + '</b><br>' + unitSummary
					onclick: 'A2Cribs.Map.MarkerTooltip.CreateContent([' + id + '], true)'
				).appendTo tooltipDiv.find('#multiBubbleContainer')

		content = $('#multiTooltip').html()
		@SetContent content

	###
	###
	SetWidth: (width) ->
		@InfoBubble.setMaxWidth width
		@InfoBubble.setMinWidth width

	SetHeight: (height) ->
		@InfoBubble.setMaxHeight height
		@InfoBubble.setMinHeight height

	@Init: () ->
		@Height = 258
		@Width = 250
		@ArrowOffset = 20
		@ArrowHeight = 15
		@Padding = 20
