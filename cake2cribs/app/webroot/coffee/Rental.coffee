class A2Cribs.Rental extends A2Cribs.Object
	@UNIT_STYLE = [
		"Unit"
		"Layout"
		"Entire House"
	]
	constructor: (rental) ->
		super "rental", rental
		dates = ["start_date", "end_date", "alternate_start_date"]
		for date in dates
			if @[date]
				if (index = @[date].indexOf " ") isnt -1
					@[date] = @[date].substring 0, index

	GetUnitStyle: ->
		return A2Cribs.Rental.UNIT_STYLE[@unit_style_options]

	GetId: ->
		return parseInt this["listing_id"], 10

	IsComplete: ->
		return if @rental_id? then true else false

	@Required_Fields = {
	}