###
ListingPopup class
###

class A2Cribs.ListingPopup
	###
	Constructor
	-creates infobubble object
	###
	constructor: () ->
		@modal = $('.listing-popup').modal {
			show: false
		}

	###
	Opens the tooltip given a marker, with popping animation
	###
	Open: (subletId) ->
		if subletId
			@SetContent subletId
			@modal.modal 'show'


	###
f	Closes the tooltip, no animation
	###
	Close: ->
		@modal.modal 'hide'

	###
	Sets the content of the tooltip
	###
	SetContent: (marker) ->
		1

	resolveDateRange: (startDate, endDate) ->
		rmonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"]
		range = ""
		startSplit = startDate.split "-"
		endSplit = endDate.split "-"
		range += rmonth[startSplit[1] - 1]
		range += " " + parseInt(startSplit[2]) + "-"
		range + rmonth[endSplit[1] - 1] + " " + parseInt endSplit[2]



