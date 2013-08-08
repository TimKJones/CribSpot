class A2Cribs.User extends A2Cribs.Object
	constructor: (user) ->
		super "user", user

	GetId: ->
		return @id