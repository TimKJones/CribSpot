class A2Cribs.UserCache
	@Cache = {}

	_get = (object_type, id, callback) ->
		if object_type is "listing" or  object_type is "rental"
				url = myBaseUrl + "Listings/GetListing/" + id
		$.ajax 
			url: url
			type:"GET"
			success: (data) =>
				callback?.success JSON.parse data
			error: =>
				callback?.error()

	###
	Cache Data
	Caches an array of Listing objects
	###
	@CacheData: (object) ->
		for item in object
			for key, value of item
				if A2Cribs[key]?
					a2_object = new A2Cribs[key] value
					old_object = @Get key.toLowerCase(), a2_object.GetId()
					if old_object? and not old_object.length?
						a2_object = old_object.Update value
					@Set a2_object

	###
	Get Listing
	Retrieves a listing_type (rental, sublet, parking) by a listing id
	Uses deferred object to fetch from async
	###
	@GetListing: (listing_type, listing_id) ->
		deferred = new $.Deferred()
		if not listing_type? or not listing_id?
			return deferred.reject()

		listing = A2Cribs.UserCache.Get listing_type, listing_id
		if listing?.IsComplete()
			return deferred.resolve listing

		$.ajax
			url: myBaseUrl + "Listings/GetListing/" + listing_id
			type:"GET"
			success: (data) =>
				response_data = JSON.parse data
				@CacheData response_data
				listing = @Get listing_type, listing_id
				return deferred.resolve listing

			error: ->
				return deferred.reject()

		return deferred.promise()

	@GetDiferred: (object_type, id) ->
		deferred = new $.Deferred()
		item = @Get object_type, id
		if not item? or not item.IsComplete()
			_get object_type, id,
				success: (data) =>
					for listing_object in data
						for key, value of listing_object
							if A2Cribs[key]?
								@Set new A2Cribs[key] value
					item = @Get object_type, id
				error: =>
					deferred.resolve null
			return deferred.promise()
		else
			return deferred.resolve item

	@Set: (object) ->
		class_name = object.class_name
		if not @Cache[object.class_name]?
			@Cache[object.class_name] = {}

		@Cache[object.class_name][object.GetId()] = object

	@Get: (object_type, id) ->
		if @Cache[object_type]?
			if id?
				return @Cache[object_type][id]
			else
				list = []
				for item of @Cache[object_type]
					list.push @Cache[object_type][item]
				return list
		return if id? then null else []

	@Remove: (object_type, id) ->
		if @Cache[object_type]? and id?
			delete @Cache[object_type][id]

	###
	Think of it as Get all {return_type} with a sorted_type_id that equals
	sorted_id
	Get all images with a listing_id of 3 would be
	GetAllAssociatedObjects("image", "listing", listing_id)
	###
	@GetAllAssociatedObjects: (return_type, sorted_type, sorted_id) ->
		if return_type? and sorted_type? and sorted_id?
			list = {}
			return_list = []
			sorted_id = parseInt sorted_id, 10
			for item of @Cache[return_type]
				if @Cache[return_type][item]["#{sorted_type}_id"]?
					return_id = parseInt @Cache[return_type][item]["#{sorted_type}_id"], 10
					if return_id is sorted_id
						list[@Cache[return_type][item].GetId()] = true
			for item of list
				return_list.push @Get return_type, item
			return return_list
