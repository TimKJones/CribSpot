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
