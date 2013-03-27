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
		if subletId?
			@SetContent subletId
			$("#overview-btn").click();
			@modal.modal 'show'

	Message: (subletId) ->
		if subletId?
			@SetContent subletId
			$("#contact-btn").click()
			$("#message-button").click()
			$("#message-area").focus();
			@modal.modal 'show'

	OpenLoaded: (sublet) ->
		if sublet?
			@SetPreloadedContent sublet
			$(".side-pane").hide();
			$("#overview").show();
			@modal.modal 'show'


	###
	Closes the tooltip, no animation
	###
	Close: ->
		@modal.modal 'hide'

	SetPreloadedContent: (subletObject) ->
		template = $(".listing-popup:first").wrap('<p/>').parent()
		content = template.children().first()
		sublet = subletObject.Sublet
		content.find('#sublet-id').text sublet.id
		if subletObject.Marker.alternate_name
			content.find('.sublet-name').text subletObject.Marker.alternate_name
		else
			content.find('.sublet-name').text subletObject.Marker.street_address
		content.find('.bed-price').text sublet.price_per_bedroom
		content.find('.full-date').text @resolveDateRange sublet.date_begin, sublet.date_end
		content.find('.building-type').text subletObject.BuildingType.name
		content.find('.school-name').text subletObject.User.university_verified
		content.find('.full-address').text subletObject.Marker.street_address + ", " + subletObject.Marker.city + ", " + subletObject.Marker.state
		content.find('.bath-type').text subletObject.BathroomType.name
		content.find('.parking-avail').text "LOL"
		content.find('.ac-avail').text "Maybe"
		content.find('.furnish-avail').text subletObject.FurnishedType.name
		content.find('.first-name').text subletObject.User.first_name
		content.find('.short-description').find('p').text sublet.description
		subletId = sublet.id
		is_favorite = subletId in A2Cribs.Cache.FavoritesSubletIdsList
		if is_favorite
			content.find('.favorite-clickable').attr 'title', 'Delete from Favorites'
			content.find('.favorite-clickable').attr 'onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ')'
			$('#favorite-btn').addClass "active"
			$('#favorite-btn').addClass "btn-danger"
		else
			content.find('.favorite-clickable').attr 'title', 'Add to Favorites'
			content.find('.favorite-clickable').attr 'onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ')'
			if $('#favorite-btn').hasClass "active"
				$('#favorite-btn').removeClass "active"
			if $('#favorite-btn').hasClass "btn-danger"
				$('#favorite-btn').removeClass "btn-danger"
		#content.find('.housemate-count').text A2Cribs.Cache.SubletIdToHousemateIdsMap[subletId].length
		#content.find('.').text
		#content.find('.').text
		#content.find('.').text
		#content.find('.').text
		
		$(".listing-popup:first").unwrap()

	###
	Sets the content of the tooltip
	###
	SetContent: (subletId) ->
		template = $(".listing-popup:first").wrap('<p/>').parent()
		content = template.children().first()
		sublet = A2Cribs.Cache.IdToSubletMap[subletId]
		marker = A2Cribs.Cache.IdToMarkerMap[sublet.MarkerId]
		school = A2Cribs.FilterManager.CurrentSchool.split(" ").join "_"
		short_address = marker.Address.split(" ").join "_"

		content.find('.photos').empty()
		if A2Cribs.Cache.SubletIdToImagesMap[subletId]? and A2Cribs.Cache.SubletIdToImagesMap[subletId].length
			for image in A2Cribs.Cache.SubletIdToImagesMap[subletId]
				content.find('.photos').append
				$('<a href="#" class="preview-thumbnail">').appendTo(content.find('.photos')).css
					'background-image': image.Path
				if image.IsPrimary
					content.find('#main-photo').css
						'background-image': 'url(' + image.Path + ')'
		else
			content.find('#main-photo').css
						'background-image': 'url(/img/tooltip/default_house.png)'

		content.find('.facebook-share').attr 'onclick', 'A2Cribs.ShareManager.ShareListingOnFacebook("' + school + '","' + short_address + '", ' + subletId + ')'
		content.find('.twitter-share').attr 'href', A2Cribs.ShareManager.GetTwitterShareUrl(school, short_address, subletId)
		content.find('#sublet-id').text subletId
		content.find('.sublet-name').text if sublet.Title then sublet.Title else marker.Address
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
		subletId = sublet.SubletId
		is_favorite = subletId in A2Cribs.Cache.FavoritesSubletIdsList
		if is_favorite
			content.find('.favorite-clickable').attr 'title', 'Delete from Favorites'
			content.find('.favorite-clickable').attr 'onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ')'
			$('#favorite-btn').addClass "active"
			$('#favorite-btn').addClass "btn-danger"
		else
			content.find('.favorite-clickable').attr 'title', 'Add to Favorites'
			content.find('.favorite-clickable').attr 'onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ')'
			if $('#favorite-btn').hasClass "active"
				$('#favorite-btn').removeClass "active"
			if $('#favorite-btn').hasClass "btn-danger"
				$('#favorite-btn').removeClass "btn-danger"
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



