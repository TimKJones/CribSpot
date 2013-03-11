###
Static class handling all Favorites functionality.
Call functions using FavoritesManager.FunctionName()
###

class A2Cribs.FavoritesManager
	#TODO: FIGURE OUT IF THIS REALLY NEEDS TO BE CACHED
	@FavoritesListingIds = null; #list of Listing IDs the current user has favorited

	###
	Add a favorite
	###
	@AddFavorite: (listingid) ->
		$.ajax
			url: myBaseUrl + "Favorites/AddFavorite/" + listingid
			type:"GET"
			context: this

	###
	Delete a favorite
	###
	@DeleteFavorite: (favorite_id) ->
		$.ajax
			url: myBaseUrl + "Favorites/DeleteFavorite/" + favorite_id
			type:"GET"
			context: this			

	@InitializeFavorites: (favoritesList) ->
		A2Cribs.Map.CacheListings favoritesList
		


	###
	Loads all favorites for current user.
	###
	@LoadFavorites: () ->
		$.ajax
			url: myBaseUrl + "Favorites/LoadFavorites"
			type:"GET"	
			context: this
			success: A2Cribs.FavoritesManager.InitializeFavorites

