class A2Cribs.Sublet extends A2Cribs.Object
	constructor: (rental) ->
		super "sublet", rental
		dates = ["start_date", "end_date"]
		for date in dates
			if @[date]
				if (index = @[date].indexOf " ") isnt -1
					@[date] = @[date].substring 0, index

	GetId: (id) ->
		return parseInt this["listing_id"], 10

	IsComplete: ->
		return if @sublet_id? then true else false