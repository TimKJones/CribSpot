class A2Cribs.UtilityFunctions
	###
	returns the left and top offsets of an element relative to the entire page
	###
	@getPosition: (el) ->
		lx = 0
		ly = 0
		loop
			break if !el
			lx += el.offsetLeft
			ly += el.offsetTop
			el = el.offsetParent
		x = 
			x: lx
			y: ly
		return x


	###
	Returns a date (year, month, day) formatted for Mysql
	###
	@GetFormattedDate: (date) ->
		year = date.getUTCFullYear()
		month = date.getMonth() + 1
		day = date.getDate()
		return year + '-' + month + '-' + day
