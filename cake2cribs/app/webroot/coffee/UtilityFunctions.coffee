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
    @MonthArray : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

	###
	Returns a date (year, month, day) formatted for Mysql
	###
	@GetFormattedDate: (date) ->
		year = date.getUTCFullYear()
		month = date.getMonth() + 1
		day = date.getDate()
		return year + '-' + month + '-' + day

	@getDateRange:(startDate, endDate)->
            `Date.prototype.addDays = function(days) {
                var dat = new Date(this.valueOf())
                dat.setDate(dat.getDate() + days);
                return dat; 
            }`
            dateArray = new Array()
            currentDate = startDate
            while currentDate <= endDate
                dateArray.push(currentDate)
                currentDate = currentDate.addDays(1)
            
            return dateArray
