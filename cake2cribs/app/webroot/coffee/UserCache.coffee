class A2Cribs.UserCache
	@Cache = {}

	@Set: (object) ->
		class_name = object.class_name
		if not @Cache[object.class_name]?
			@Cache[object.class_name] = {}

		@Cache[object.class_name][object.GetId()] = object

	@Get: (object_type, id) ->
		list = []
		if @Cache[object_type]?
			if id?
				return @Cache[object_type][id]
			else
				for item of @Cache[object_type]
					list.push @Cache[object_type][item]
		return list

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
