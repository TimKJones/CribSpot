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
			fl_sidebar_item = $("#fl-sb-item-#{listing_id}")
			if fl_sidebar_item.length is 1 # the item on the side bar is found
				fl_sidebar_item.find(".favorite").attr 'onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + listing_id + ', this);'
				fl_sidebar_item.find(".favorite").attr 'title', 'Delete from Favorites'
				fl_sidebar_item.find(".favorite").addClass 'active'
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
			fl_sidebar_item = $("#fl-sb-item-#{listing_id}")
			if fl_sidebar_item.length is 1 # the item on the side bar is found
				fl_sidebar_item.find(".favorite").attr 'onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + listing_id + ', this);'
				fl_sidebar_item.find(".favorite").attr 'title', 'Add to Favorites'
				fl_sidebar_item.find(".favorite").removeClass 'active'
			if button?
				$(button).attr 'onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + listing_id + ', this);'
				$(button).attr 'title', 'Add to Favorites'
				$(button).removeClass 'active'
				@_setFavoriteCount()

	###
	response contains a list of listing_ids that have been favorited by the logged-in user
	###
	@InitializeFavorites: (listing_ids) ->
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
			success: (response) =>
				if response?
					@InitializeFavorites JSON.parse response

	###
	Called when user clicks the heart icon in the header.
	Toggles visibility of markers where user has favorited a listing.
	###
	@ToggleFavoritesVisibility: =>
		if A2Cribs.Map.ToggleListingVisibility(@FavoritesListingIds, "favorites")
			A2Cribs.Map.IsCluster yes
			$(".favorite_button").removeClass "active"
		else
			A2Cribs.Map.IsCluster no
			$(".favorite_button").addClass "active"

	@FavoritesVisibilityIsOn: () ->
		return $("#FavoritesHeaderIcon").hasClass("pressed")

	###
	Initialize a heart icon for adding favorites
	###
	@setFavoriteButton: (div, listing_id, favorites_list) ->
		if favorites_list.indexOf(parseInt(listing_id, 10)) is -1
			div.attr "onclick", "A2Cribs.FavoritesManager.AddFavorite(#{listing_id}, this)"
			div.removeClass "active"
		else
			div.attr "onclick", "A2Cribs.FavoritesManager.DeleteFavorite(#{listing_id}, this)"
			div.addClass "active"

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