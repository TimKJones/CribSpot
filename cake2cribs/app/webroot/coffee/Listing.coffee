class A2Cribs.Listing extends A2Cribs.Object
	@LISTING_TYPES = ['Rental', 'Sublet']
	constructor: (listing) ->
		super "listing", listing

	###
	Checks/Sets if the listing is visible
	on the map
	Defaults to true
	###
	IsVisible: (visible = null) ->
		if typeof(visible) is "boolean"
			@visible = visible
		if @visible is no
			return no
		return yes

	###
	Checks/Sets if the listing is in the sidebar
	This variable is set when the listing
	is loaded in the sidebar
	###
	InSidebar: (in_sidebar = null) ->
		if typeof(in_sidebar) is "boolean"
			@in_sidebar = in_sidebar
		if @in_sidebar is yes
			return yes
		return no

	###
	Check/Sets if the listing is featured
	###
	IsFeatured: (is_featured = null) ->
		if typeof(is_featured) is "boolean"
			@is_featured = is_featured
		if @is_featured is yes
			return yes
		return no

	###
	Returns the string of the listing type
	###
	GetListingType: ->
		return A2Cribs.Listing.LISTING_TYPES[parseInt(@listing_type, 10)]

	###
	Gets all objects connected to the listing
	###
	GetConnectedObject: ->
		listing_string = A2Cribs.Listing.LISTING_TYPES[parseInt(@listing_type, 10)]
		a2objects = ['Listing', 'Image', listing_string]
		ret_object = {}
		for a2object in a2objects
			obj = A2Cribs.UserCache.Get a2object.toLowerCase(), @GetId()
			if obj?
				ret_object[a2object] = obj.GetObject()
				
		return ret_object

