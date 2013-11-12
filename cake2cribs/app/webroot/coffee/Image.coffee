class A2Cribs.Image extends A2Cribs.Object
	###
	Image is an array of all the images associated with a listing
	###
	constructor: (image) ->
		if image.length isnt 0
			@class_name = "image"
			@image_array = image
			for image_object, i in @image_array
				if image_object.is_primary
					@primary = i
			@listing_id = @image_array[0].listing_id
			

	GetId: ->
		return @listing_id

	GetPrimary: (field = 'image_path') ->
		if @primary?
			return @image_array[@primary][field]
		else if @image_array.length isnt 0
			@image_array[0][field]

	GetImages: ->
		return @image_array

	GetObject: ->
		return_array = []
		for image in @image_array
			img_copy = {}
			for key, value of image
				if typeof value isnt "function"
					if typeof value is "boolean"
						value = +value
					img_copy[key] = value
			return_array.push img_copy
		return return_array

