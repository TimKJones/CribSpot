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
			A2Cribs.Map.ClickBubble.Close()
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

	###
	Closes the tooltip, no animation
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
		marker = A2Cribs.Cache.IdToMarkerMap[sublet.MarkerId]
		housemates = A2Cribs.Cache.IdToHousematesMap[A2Cribs.Cache.SubletIdToHousemateIdsMap[subletId]]
		school = A2Cribs.FilterManager.CurrentSchool.split(" ").join "_"
		short_address = marker.Address.split(" ").join "_"

		content.find('.photos').empty()
		content.find('#main-photo').css
						'background-image': 'url(/img/tooltip/default_house_large.jpg)'
		content.find('#photo-description').text ""
		if A2Cribs.Cache.SubletIdToImagesMap[subletId]? and A2Cribs.Cache.SubletIdToImagesMap[subletId].length
			for image in A2Cribs.Cache.SubletIdToImagesMap[subletId]
				$('<a href="#" caption="' + image.Caption + '" class="preview-thumbnail">').appendTo(content.find('.photos')).css
					'background-image': 'url(' + image.Path + ')'
				$(".preview-thumbnail").on("click", ( ->
					url = $(this).css("background-image");
					$("#main-photo").css("background-image", url);
					$("#photo-description").html($(this).attr("caption"))
					return false;
				));
				if image.IsPrimary
					content.find('#main-photo').css
						'background-image': 'url(' + image.Path + ')'
					content.find('#photo-description').text image.Caption

		else
			content.find('#main-photo').css
						'background-image': 'url(/img/tooltip/default_house_large.jpg)'

		content.find('.facebook-share').attr 'onclick', 'A2Cribs.ShareManager.ShareListingOnFacebook("' + school + '","' + short_address + '", ' + subletId + ')'
		content.find('.twitter-share').attr 'href', A2Cribs.ShareManager.GetTwitterShareUrl(school, short_address, subletId)
		content.find('#sublet-id').text subletId
		content.find('.sublet-name').text if sublet.Title then sublet.Title else marker.Address
		content.find('.bed-price').text sublet.PricePerBedroom
		content.find('.full-date').text @resolveDateRange sublet.StartDate, sublet.EndDate
		content.find('.building-type').text sublet.BuildingType
		content.find('.school-name').text A2Cribs.Cache.SubletIdToOwnerMap[subletId].VerifiedUniversity
		content.find('.full-address').text marker.Address + ", " + marker.City + ", " + marker.State
		content.find('.bath-type').text sublet.BathroomType
		content.find('.parking-avail').text if sublet.Parking then "Yes" else "No"
		content.find('.ac-avail').text if sublet.Air then "Yes" else "No"
		content.find('.furnish-avail').text if sublet.Furnished is 3 then "No" else "Yes"
		content.find('.first-name').text A2Cribs.Cache.SubletIdToOwnerMap[subletId].FirstName
		content.find('.short-description').find('p').text sublet.Description
		

		subletId = sublet.SubletId
		is_favorite = subletId in A2Cribs.Cache.FavoritesSubletIdsList
		if is_favorite
			content.find('#favorite-btn').attr 'title', 'Delete from Favorites'
			content.find('#favorite-btn').attr 'onclick', 'A2Cribs.FavoritesManager.DeleteFavorite(' + subletId + ', this)'
			$('#favorite-btn').addClass "active"
		else
			content.find('#favorite-btn').attr 'title', 'Add to Favorites'
			content.find('#favorite-btn').attr 'onclick', 'A2Cribs.FavoritesManager.AddFavorite(' + subletId + ', this)'
			$('#favorite-btn').removeClass "active"

		if housemates != undefined and housemates != null
			content.find('.housemate-count').text housemates.Quantity
			content.find('.housemate-enrolled').text if housemates.Enrolled then "Yes" else "No"
			content.find('.housemate-type').text housemates.GradType
			content.find('.housemate-major').text housemates.Major
			content.find('.housemate-gender').text housemates.Gender
			content.find('.housemate-year').text housemates.Year
		content.find('.utilities-cost').text if sublet.UtilityCost is 0 then "Included" else "$" + sublet.UtilityCost
		content.find('.deposit-cost').text if sublet.DepositAmount is 0 then "None" else "$" + sublet.DepositAmount
		content.find('.additional-fee').text if sublet.AdditionalFeesAmount is 0 then "None" else "$" + sublet.AdditionalFeesAmount

		# Pull in verification information for this sublet lister
		@loadVerificationInfo(subletId, content)
		
		$(".listing-popup:first").unwrap()

	resolveDateRange: (startDate, endDate) ->
		rmonth = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
		range = ""
		startSplit = startDate.split "-"
		endSplit = endDate.split "-"
		range += rmonth[startSplit[1] - 1]
		range += " " + parseInt(startSplit[2]) + ", " + startSplit[0] + " to "
		range + rmonth[endSplit[1] - 1] + " " + parseInt(endSplit[2]) + ", " + endSplit[0]


	loadVerificationInfo: (sublet_id, content)->
		if !A2Cribs.FBInitialized && A2Cribs.marker_id_to_open > 0
			A2Cribs.FBInitialized =true
			return
		user = A2Cribs.Cache.SubletIdToOwnerMap[sublet_id]
		A2Cribs.VerifyManager.getVerificationFor(user).then (verification_info)->
			if parseInt(verification_info.mut_friends) == 0 or verification_info.mut_friends == undefined or verification_info.mut_friends == null
				$(".facebookFriendLabel").html("Total Friends:")
				if verification_info.tot_friends != null and !isNaN(verification_info.tot_friends)
					$(".numFacebookFriends").html(verification_info.tot_friends)
				else
					$(".numFacebookFriends").html("--")
			else
				$(".facebookFriendLabel").html("Mutual Friends:")
				$(".numFacebookFriends").html(verification_info.mut_friends)

			if verification_info.tot_followers != null and !isNaN(verification_info.tot_followers)
				$(".numTwitterFollowers").html(verification_info.tot_followers)
			else
				$(".numTwitterFollowers").html("--")

			if verification_info.verified_edu
				$("#universityVerified").removeClass("unverified");
				$("#universityVerified").addClass("verified");
			else
				$("#universityVerified").removeClass("verified");
				$("#universityVerified").addClass("unverified");

			if verification_info.verified_email or verification_info.verified_edu
				$("#emailVerified").removeClass("unverified");
				$("#emailVerified").addClass("verified");
			else
				$("#emailVerified").removeClass("verified");
				$("#emailVerified").addClass("unverified");

			if verification_info.verified_fb
				$("#fbVerified").removeClass("unverified");
				$("#fbVerified").addClass("verified");
			else
				$("#fbVerified").removeClass("verified");
				$("#fbVerified").addClass("unverified");

			if verification_info.verified_tw
				$("#twitterVerified").removeClass("unverified");
				$("#twitterVerified").addClass("verified");
			else
				$("#twitterVerified").removeClass("verified");
				$("#twitterVerified").addClass("unverified");

			if verification_info.verified_fb

				pic_url = "https://graph.facebook.com/" + verification_info.fb_id + "/picture?width=480"
				$(".user_contact_pic").attr("src", pic_url)

			else
				# Set to default img, if we move to using sprites this code may need to be changed
				$(".user_contact_pic").attr 'src', "/img/head_large.jpg"
