###
Static class handling all Favorites functionality.
Call functions using FavoritesManager.FunctionName()
###

class A2Cribs.FavoritesManager
	@FavoritesListingIds = []
	@FavoritesVisible = false

	###
	Add a favorite
	###
	@AddFavorite: (listing_id, button) ->
		$.ajax
			url: myBaseUrl + "Favorites/AddFavorite/" + listing_id
			type:"POST"
			context: this
			success: (response) ->
				A2Cribs.FavoritesManager.AddFavoriteCallback(response, listing_id, button)

	@AddFavoriteCallback: (response, listing_id, button) ->
		response = JSON.parse response
		if response.success == undefined
			if response.error.message != undefined
				A2Cribs.UIManager.Alert response.error.message
			else
				A2Cribs.UIManager.Alert "There was an error adding your favorite. Contact help@cribspot.com if the error persists."
		else
			@FavoritesListingIds.push listing_id
			if button?
				$(button).attr 'onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + listing_id + ', this);'
				$(button).attr 'title', 'Delete from Favorites'
				$(button).addClass 'active'
				@_setFavoriteCount()

	###
	Delete a favorite
	###
	@DeleteFavorite: (listing_id, button) ->
		$.ajax
			url: myBaseUrl + "Favorites/DeleteFavorite/" + listing_id
			type:"POST"
			context: this
			success: (response) ->
				A2Cribs.FavoritesManager.DeleteFavoriteCallback(response, listing_id, button)

	@DeleteFavoriteCallback: (response, listing_id, button) ->
		response = JSON.parse response
		if response.error != undefined
			A2Cribs.UIManager.Alert response.error.message
		else
			# remove listing_id from list of favorites
			index = A2Cribs.FavoritesManager.FavoritesListingIds.indexOf listing_id
			if index != -1
				A2Cribs.FavoritesManager.FavoritesListingIds.splice index
			if button?
				$(button).attr 'onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + listing_id + ', this);'
				$(button).attr 'title', 'Add to Favorites'
				$(button).removeClass 'active'
				@_setFavoriteCount()

	###
	response contains a list of listing_ids that have been favorited by the logged-in user
	###
	@InitializeFavorites: (response) ->
		if response == null or response == undefined
			return

		listing_ids = JSON.parse response
		for listing_id in listing_ids
			A2Cribs.FavoritesManager.FavoritesListingIds.push parseInt(listing_id)

		@_setFavoriteCount()

	###
	Loads all favorites for current user.
	###
	@LoadFavorites: () ->
		$.ajax
			url: myBaseUrl + "Favorites/LoadFavorites"
			type:"GET"	
			context: this
			success: A2Cribs.FavoritesManager.InitializeFavorites

	###
	Called when user clicks the heart icon in the header.
	Toggles visibility of markers where user has favorited a listing.
	###
	@ToggleFavoritesVisibility: (button) ->
		$(button).toggleClass 'active'
		A2Cribs.HoverBubble?.Close()
		A2Cribs.ClickBubble?.Close()

		all_markers = A2Cribs.UserCache.Get 'marker'
		all_listings = A2Cribs.UserCache.Get 'listing'

		if !A2Cribs.FavoritesManager.FavoritesVisible
			# make only markers that are in user's favorites visible
			$("#FavoritesHeaderIcon").addClass("pressed")

			# Set visibility of ALL markers to false
			for marker in all_markers
				marker.GMarker?.setVisible false

			for listing in all_listings
				listing.visible = false

			# Set visibility of all markers with listings in user's favorites to true
			for listing_id in A2Cribs.FavoritesManager.FavoritesListingIds
				listing = A2Cribs.UserCache.Get 'listing', listing_id
				marker = A2Cribs.UserCache.Get 'marker', listing.marker_id
				marker.GMarker?.setVisible true
				listing.visible = true
		else
			# make all markers visible
			for marker in all_markers
				marker?.GMarker?.setVisible true

			for listing in all_listings
				listing.visible = true

			$("#FavoritesHeaderIcon").removeClass("pressed")

		A2Cribs.Map.GMarkerClusterer.repaint()
		A2Cribs.FavoritesManager.FavoritesVisible = !A2Cribs.FavoritesManager.FavoritesVisible

	@FavoritesVisibilityIsOn: () ->
		return $("#FavoritesHeaderIcon").hasClass("pressed")

	###
	Inserts the recent favorite into the favorites tab
	###
	@_insertIntoFavoriteDiv: (listing_id) ->
		if @FavoritesCache.size is 1
			$('#noFavorites').hide()

		sublet = A2Cribs.Map.IdToSubletMap[listing_id]
		marker = A2Cribs.Map.IdToMarkerMap[sublet.MarkerId]
		title = if marker.Title then marker.Title else marker.Address
		template = $ '#favoriteTemplate'
		template.find('.favoriteDiv').attr id: "favoriteDiv" + listing_id
		template.find('.favoritesAddress').html title
		template.find('.removeButton').attr onclick: "A2Cribs.FavoritesManager.DeleteFavorite(" + listing_id + ")"
		template.find('a').attr href: listing.Url
		template.find('#price').html if listing.Rent then '$' + listing.Rent else "???"
		template.find('#beds').html listing.Beds + if listing.Beds > 1 then " Beds" else " Bed"
		template.find('#baths').html listing.Baths + if listing.Baths > 1 then " Baths" else " Bath"
		template.find('#payMonth').html listing.LeaseRange
		template.find('#aptType').html listing.UnitType
		template.find('#electric').find('div').addClass if listing.Electric then "electric_selected" else "electric_unselected"
		template.find('#heat').find('div').addClass if listing.Heat then "heat_selected" else "heat_unselected"
		template.find('#water').find('div').addClass if listing.Water then "water_selected" else "water_unselected"
		template.find('#furnished').find('div').addClass if listing.Furnished then "furnished_selected" else "furnished_unselected"
		template.find('#parking').find('div').addClass if listing.Parking then "parking_selected" else "parking_unselected"
		template.find('#ac').find('div').addClass if listing.Air then "ac_selected" else "ac_unselected"

		content = $('#favoriteTemplate').html()
		$('#personalFavoritesList').append content


	@_setFavoriteCount: () ->
		if @FavoritesListingIds.length is 0
			$(".favorite_count").hide()
		else
			$(".favorite_count").show().text @FavoritesListingIds.length

	###
	Removes the recent favorite into the favorites tab
	###
	@_removeFromFavoriteDiv: (listing_id) ->
		$('#personalFavoritesList').find('#favoriteDiv' + listing_id).remove()
		if @FavoritesCache.size is 0
			$('#noFavorites').show()

	@_insertFavoriteCache: (listing_id) ->
		@FavoritesCache[listing_id] = true
		++ @FavoritesCache.size
		$('#numFavorites').html @FavoritesCache.size

	@_removeFavoriteCache: (listing_id) ->
		@FavoritesCache[listing_id]	= null
		-- @FavoritesCache.size
		$('#numFavorites').html @FavoritesCache.size