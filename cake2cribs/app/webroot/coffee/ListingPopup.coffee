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
	SetContent: (subletId) ->
		template = $(".listing-popup:first").wrap('<p/>').parent()
		content = template.children().first()
		sublet = A2Cribs.Cache.IdToSubletMap[subletId]
		content.find('.sublet-name').text if sublet.Name then sublet.Name else sublet.StreetAddress
		content.find('.bed-price').text sublet.PricePerBedroom
		
		$(".listing-popup:first").unwrap()

	resolveDateRange: (startDate, endDate) ->
		rmonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"]
		range = ""
		startSplit = startDate.split "-"
		endSplit = endDate.split "-"
		range += rmonth[startSplit[1] - 1]
		range += " " + parseInt(startSplit[2]) + "-"
		range + rmonth[endSplit[1] - 1] + " " + parseInt endSplit[2]



