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
		content.find('.full-date').text @resolveDateRange sublet.StartDate, sublet.EndDate
		content.find('.building-type').text sublet.BuildingType
		content.find('.school-name').text A2Cribs.Cache.SubletIdToOwnerMap[subletId].VerifiedUniversity
		content.find('.full-address').text sublet.StreetAddress + ", " + sublet.City + ", " + sublet.State
		content.find('.bath-type').text sublet.BathroomType
		content.find('.parking-avail').text "LOL"
		content.find('.ac-avail').text "Maybe"
		content.find('.furnish-avail').text if sublet.Furnished then "Fully" else "No"
		content.find('.first-name').text A2Cribs.Cache.SubletIdToOwnerMap[subletId].FirstName
		content.find('.short-description').find('p').text sublet.Description
		#content.find('.housemate-count').text A2Cribs.Cache.SubletIdToHousemateIdsMap[subletId].length
		#content.find('.').text
		#content.find('.').text
		#content.find('.').text
		#content.find('.').text
		
		$(".listing-popup:first").unwrap()

	resolveDateRange: (startDate, endDate) ->
		rmonth = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
		range = ""
		startSplit = startDate.split "-"
		endSplit = endDate.split "-"
		range += rmonth[startSplit[1] - 1]
		range += " " + parseInt(startSplit[2]) + ", " + startSplit[0] + " to "
		range + rmonth[endSplit[1] - 1] + " " + parseInt(endSplit[2]) + ", " + endSplit[0]



