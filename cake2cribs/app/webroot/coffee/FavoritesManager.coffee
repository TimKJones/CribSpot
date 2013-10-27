###
Static class handling all Favorites functionality.
Call functions using FavoritesManager.FunctionName()
###

class A2Cribs.FavoritesManager
	@FavoritesListingIds = []
	@FavoritesVisible = false

	$(document).ready =>
		$("body").on 'click', '.favorite_listing', (event) =>
			listing_id = $(event.currentTarget).data "listing-id"
			if @FavoritesListingIds.indexOf(parseInt(listing_id, 10)) is -1
				@AddFavorite listing_id, event.currentTarget
			else
				@DeleteFavorite listing_id, event.currentTarget
			$(event.currentTarget).toggleClass "active"

	###
	Add a favorite
	###
	@AddFavorite: (listing_id, button) ->
		return $.ajax
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
			@FavoritesListingIds.push parseInt(listing_id, 10)
			fl_sidebar_item = $("#fl-sb-item-#{listing_id}")
			if fl_sidebar_item.length is 1 # the item on the side bar is found
				fl_sidebar_item.find(".favorite").attr 'title', 'Delete from Favorites'
				fl_sidebar_item.find(".favorite").addClass 'active'
			if button?
				$(button).attr 'title', 'Delete from Favorites'
				$(button).addClass 'active'

			@_setFavoriteCount()

	###
	Delete a favorite
	###
	@DeleteFavorite: (listing_id, button) ->
		return $.ajax
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
			index = A2Cribs.FavoritesManager.FavoritesListingIds.indexOf parseInt(listing_id, 10)
			if index != -1
				A2Cribs.FavoritesManager.FavoritesListingIds.splice index, 1
			fl_sidebar_item = $("#fl-sb-item-#{listing_id}")
			if fl_sidebar_item.length is 1 # the item on the side bar is found
				fl_sidebar_item.find(".favorite").attr 'title', 'Add to Favorites'
				fl_sidebar_item.find(".favorite").removeClass 'active'
			if button?
				$(button).attr 'title', 'Add to Favorites'
				$(button).removeClass 'active'
				@_setFavoriteCount()

	###
	response contains a list of listing_ids that have been favorited by the logged-in user
	###
	@InitializeFavorites: (listing_ids) ->
		for listing_id in listing_ids
			A2Cribs.FavoritesManager.FavoritesListingIds.push parseInt(listing_id, 10)

		@_setFavoriteCount()

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
	@setFavoriteButton: (div, listing_id) =>
		if @FavoritesListingIds.indexOf(parseInt(listing_id, 10)) is -1
			div.removeClass "active"
		else
			div.addClass "active"

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