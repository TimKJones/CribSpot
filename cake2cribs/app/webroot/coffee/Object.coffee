class A2Cribs.Object
	constructor: (@class_name = "object", a2_object)->
		for key, value of a2_object
			if value?
				@[key] = value

	Update: (a2_object) ->
		for key, value of a2_object
			if value?
				@[key] = value
		return this

	GetId: (id) ->
		return parseInt this["#{@class_name}_id"], 10

	GetObject: ->
		return_object = {}
		for key, value of @
			if typeof value isnt "function"
				if typeof value is "boolean"
					value = +value
				return_object[key] = value
		return return_object

	IsComplete: ->
		return true

