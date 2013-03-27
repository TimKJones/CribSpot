###
Static class handling all Favorites functionality.
Call functions using FavoritesManager.FunctionName()
###

class A2Cribs.FavoritesManager
	@FavoritesCache = {
		size : 0
	}; #list of Listing IDs the current user has favorited

	###
	Add a favorite
	###
	@AddFavorite: (sublet_id, button) ->
		A2Cribs.Cache.FavoritesSubletIdsList.push sublet_id
		marker_id = A2Cribs.Cache.IdToSubletMap[sublet_id].MarkerId
		A2Cribs.Cache.FavoritesMarkerIdsList.push marker_id
		$.ajax
			url: myBaseUrl + "Favorites/AddFavorite/" + sublet_id
			type:"POST"
			context: this
			success: (response) ->
				#@_insertFavoriteCache sublet_id
				#@_insertIntoFavoriteDiv sublet_id
				A2Cribs.FavoritesManager.AddFavoriteCallback(response, sublet_id, button)

	@AddFavoriteCallback: (response, sublet_id, button) ->
		response = JSON.parse response
		if response.SUCCESS == undefined
			message = "There was an error adding your favorite. Contact help@cribspot.com if the error persists."
			if response.ERROR == "USER_NOT_LOGGED_IN"
				message = "You must log in to add favorites."
			A2Cribs.UIManager.Alert message
			sublet_index = A2Cribs.Cache.FavoritesSubletIdsList.indexOf sublet_id
			marker_id = A2Cribs.Cache.IdToSubletMap[sublet_id].MarkerId
			markerid_index = A2Cribs.Cache.FavoritesMarkerIdsList.indexOf marker_id
			A2Cribs.Cache.FavoritesSubletIdsList.splice sublet_index, 1
			A2Cribs.Cache.FavoritesMarkerIdsList.splice markerid_index, 1
		else
			if button?
				$(button).attr 'onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + sublet_id + ', this);'
				$(button).attr 'title', 'Delete from Favorites'
				$(button).addClass 'active'

	###
	Delete a favorite
	###
	@DeleteFavorite: (sublet_id, button) ->
		sublet_index = A2Cribs.Cache.FavoritesSubletIdsList.indexOf sublet_id
		marker_id = A2Cribs.Cache.IdToSubletMap[sublet_id].MarkerId
		markerid_index = A2Cribs.Cache.FavoritesMarkerIdsList.indexOf marker_id
		A2Cribs.Cache.FavoritesSubletIdsList.splice sublet_index, 1
		A2Cribs.Cache.FavoritesMarkerIdsList.splice markerid_index, 1
		$.ajax
			url: myBaseUrl + "Favorites/DeleteFavorite/" + sublet_id
			type:"POST"
			context: this
			success: (response) ->
				#@_removeFavoriteCache sublet_id
				#@_removeFromFavoriteDiv sublet_id
				A2Cribs.FavoritesManager.DeleteFavoriteCallback(response, sublet_id, button)

	@DeleteFavoriteCallback: (response, sublet_id, button) ->
		response = JSON.parse response
		if response.SUCCESS == undefined
			A2Cribs.UIManager.Alert "There was an error deleting your favorite. Contact help@cribspot.com if the error persists."
			A2Cribs.Cache.FavoritesSubletIdsList.push sublet_id
			A2Cribs.Cache.FavoritesSubletIdsList.push sublet_id
			marker_id = A2Cribs.Cache.IdToSubletMap[sublet_id].MarkerId
			A2Cribs.Cache.FavoritesMarkerIdsList.push marker_id
		else
			if button?
				$(button).attr 'onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + sublet_id + ', this);'
				$(button).attr 'title', 'Add to Favorites'
				$(button).removeClass 'active'

	@InitializeFavorites: (response) ->
		response = JSON.parse response
		if response == null or response == undefined or response[0] == undefined or response[1] == undefined
			return

		sublet_ids = response[0]
		marker_ids = response[1]
		#A2Cribs.Map.CacheSublets favorites 
		for sublet_id in sublet_ids
			A2Cribs.Cache.FavoritesSubletIdsList.push parseInt sublet_id.Favorite.sublet_id

		for marker_id in marker_ids
			A2Cribs.Cache.FavoritesMarkerIdsList.push parseInt marker_id.Sublet.marker_id
			#@_insertFavoriteCache favorite.Listing.listing_id
			#@_insertIntoFavoriteDiv favorite.Listing.listing_id
		


	###
	Loads all favorites for current user.
	###
	@LoadFavorites: () ->
		$.ajax
			url: myBaseUrl + "Favorites/LoadFavorites"
			type:"GET"	
			context: this
			success: @.InitializeFavorites

	@ToggleFavoritesVisibility: (button) ->
		$(button).toggleClass 'active'
		A2Cribs.Map.ClickBubble.Close()
		if !A2Cribs.FavoritesManager.FavoritesVisibilityIsOn()
			$("#FavoritesHeaderIcon").addClass("pressed")
			for marker, markerid in A2Cribs.Cache.IdToMarkerMap
				if markerid in A2Cribs.Cache.FavoritesMarkerIdsList
					if (marker)
						marker.GMarker.setVisible true
				else
					if (marker)
						marker.GMarker.setVisible false
		else
			for marker, marker_id in A2Cribs.Cache.IdToMarkerMap
				if (marker)
						marker.GMarker.setVisible true
			$("#FavoritesHeaderIcon").removeClass("pressed")

		A2Cribs.Map.GMarkerClusterer.repaint()

	@FavoritesVisibilityIsOn: () ->
		return $("#FavoritesHeaderIcon").hasClass("pressed")

	###
	Inserts the recent favorite into the favorites tab
	###
	@_insertIntoFavoriteDiv: (sublet_id) ->
		if @FavoritesCache.size is 1
			$('#noFavorites').hide()

		sublet = A2Cribs.Map.IdToSubletMap[sublet_id]
		marker = A2Cribs.Map.IdToMarkerMap[sublet.MarkerId]
		title = if marker.Title then marker.Title else marker.Address
		template = $ '#favoriteTemplate'
		template.find('.favoriteDiv').attr id: "favoriteDiv" + sublet_id
		template.find('.favoritesAddress').html title
		template.find('.removeButton').attr onclick: "A2Cribs.FavoritesManager.DeleteFavorite(" + sublet_id + ")"
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