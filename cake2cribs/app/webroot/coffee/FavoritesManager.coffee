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
	@AddFavorite: (listing_id) ->
		$('#addFavoriteImg').toggleClass "starNotFavorite starFavorite"
		$('#addFavoriteImg').attr 
			title: "Delete from Favorites"
			onclick: "A2Cribs.FavoritesManager.DeleteFavorite(" + listing_id + ")"
		$.ajax
			url: myBaseUrl + "Favorites/AddFavorite/" + listing_id
			type:"POST"
			context: this
			success: () ->
				@_insertFavoriteCache listing_id
				@_insertIntoFavoriteDiv listing_id

	###
	Delete a favorite
	###
	@DeleteFavorite: (listing_id) ->
		$('#addFavoriteImg').toggleClass "starFavorite starNotFavorite"
		$('#addFavoriteImg').attr
			title: "Add to Favorites"
			onclick: "A2Cribs.FavoritesManager.AddFavorite(" + listing_id + ")"
		$.ajax
			url: myBaseUrl + "Favorites/DeleteFavorite/" + listing_id
			type:"POST"
			context: this
			success: () ->
				@_removeFavoriteCache listing_id
				@_removeFromFavoriteDiv listing_id


	@InitializeFavorites: (favoritesList) ->
		favorites = JSON.parse favoritesList
		A2Cribs.Map.CacheListings favorites
		for favorite in favorites
			@_insertFavoriteCache favorite.Listing.listing_id
			@_insertIntoFavoriteDiv favorite.Listing.listing_id
		


	###
	Loads all favorites for current user.
	###
	@LoadFavorites: () ->
		$.ajax
			url: myBaseUrl + "Favorites/LoadFavorites"
			type:"GET"	
			context: this
			success: @.InitializeFavorites

	###
	Inserts the recent favorite into the favorites tab
	###
	@_insertIntoFavoriteDiv: (listing_id) ->
		if @FavoritesCache.size is 1
			$('#noFavorites').hide()

		listing = A2Cribs.Map.IdToListingMap[listing_id]
		marker = A2Cribs.Map.IdToMarkerMap[listing.MarkerId]
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