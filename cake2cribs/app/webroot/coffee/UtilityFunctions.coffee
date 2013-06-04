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


	# Algo src http://partialclass.blogspot.com/2011/07/calculating-working-days-between-two.html
	@getWeekdaysBetweenDates: (startDate, endDate)->
	  
	    # Validate input
	    if endDate < startDate
	        return 0
	    
	    # Calculate days between dates
	    millisecondsPerDay = 86400 * 1000 # Day in milliseconds
	    startDate.setHours(0,0,0,1)  # Start just after midnight
	    endDate.setHours(23,59,59,999)  # End just before midnight
	    diff = endDate - startDate  # Milliseconds between datetime objects    
	    days = Math.ceil(diff / millisecondsPerDay)
	    
	    # Subtract two weekend days for every week in between
	    weeks = Math.floor(days / 7)
	    days = days - (weeks * 2)

	    # Handle special cases
	    startDay = startDate.getDay()
	    endDay = endDate.getDay()
	    
	    # Remove weekend not previously removed.   
	    if (startDay - endDay > 1)         
	        days = days - 2      
	    
	    # Remove start day if span starts on Sunday but ends before Saturday
	    if startDay == 0 and endDay != 6
	        days = days - 1  
	            
	   	# Remove end day if span ends on Saturday but starts after Sunday
	    if endDay == 6 and startDay != 0
	        days = days - 1  
	    
	    return days

	@getDaysBetweenDates: (startDate, endDate)->
		# Validate input
	    if endDate < startDate
	        return 0
	    
	    # Calculate days between dates
	    millisecondsPerDay = 86400 * 1000 # Day in milliseconds
	    startDate.setHours(0,0,0,1)  # Start just after midnight
	    endDate.setHours(23,59,59,999)  # End just before midnight
	    diff = endDate - startDate  # Milliseconds between datetime objects    
	    days = Math.ceil(diff / millisecondsPerDay)

	    return days