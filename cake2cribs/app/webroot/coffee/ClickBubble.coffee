###
ClickBubble class
###

class A2Cribs.ClickBubble
	@OFFSET = 
		TOP: -190
		LEFT: 140
	@PADDING = 50 #padding on sides of click bubble after panning to make click bubble fit on map
	@IsOpen = false
	###
	Private function that relocates the bubble near the marker
	###
	move_near_marker = (listing_id) =>
		listing = A2Cribs.UserCache.Get "listing", listing_id
		marker = A2Cribs.UserCache.Get("marker", listing.marker_id)
		position = null
		if marker? and marker.GMarker?
			position = marker.GMarker.getPosition()
		else if marker?
			postition = new google.maps.LatLng(marker.latitude, marker.longitude)

		if position == null
			return
		#calculate marker position with respect to latLng boundaries
		marker_pixel_position = @ConvertLatLongToPixels position
		@div.css "left", marker_pixel_position.x + @OFFSET.LEFT
		@div.css "top", marker_pixel_position.y + @OFFSET.TOP

	@ConvertLatLongToPixels: (latLng) ->
		scale = Math.pow 2, @map.getZoom()
		nw = new google.maps.LatLng @map.getBounds().getNorthEast().lat(), @map.getBounds().getSouthWest().lng()
		worldCoordinateNW = @map.getProjection().fromLatLngToPoint nw
		worldCoordinate = @map.getProjection().fromLatLngToPoint latLng
		position = {}
		position.x = Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale)
		position.y = Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale)
		return position

	###
	Constructor
	###
	@Init: (@map) ->
		@div = $(".click-bubble:first")
		@div.find(".close_button").click () =>
			@Close()

	###
	Opens the tooltip given a marker, with popping animation
	###
	@Open: (listing_id) ->
		@IsOpen = true
		if listing_id?
			listing = A2Cribs.UserCache.Get A2Cribs.Map.ACTIVE_LISTING_TYPE, listing_id
			A2Cribs.MixPanel.Click listing, "large popup"
			if listing.rental_id? # if the rental is cached
				@SetContent listing.GetObject()
				@Show listing_id
			else
				$.ajax 
					url: myBaseUrl + "Listings/GetListing/" + listing_id
					type:"GET"
					success: (data) =>
						response_data = JSON.parse data
						for item in response_data
							for key, value of item
								if key isnt "Marker" and A2Cribs[key]?
									A2Cribs.UserCache.Set new A2Cribs[key] value

						listing = A2Cribs.UserCache.Get A2Cribs.Map.ACTIVE_LISTING_TYPE, listing_id
						@SetContent listing.GetObject()
						@Show listing_id

	@Show: (listing_id) ->
		@IsOpen = true
		move_near_marker listing_id
		@div.show 'fade'

	###
	Refreshes the tooltip with the new content, no animation
	###
	@Refresh: () ->
		@div.show 'fade'

	###
	Closes the tooltip, no animation
	###
	@Close: ->
		@IsOpen = false
		@div.hide 'fade'


	@Clear: ->
		@div.find(".clear_field").text("?").html("?").val("?")

	###
	Sets the content of the tooltip
	###
	@SetContent: (listing_object) ->
		@Clear()
		for key,value of listing_object
			@div.find(".#{key}").text value
		@div.find(".date_range").text @resolveDateRange listing_object.start_date
		marker = A2Cribs.UserCache.Get "marker", A2Cribs.UserCache.Get("listing", listing_object.listing_id).marker_id
		@div.find(".building_name").text marker.GetName()
		@div.find(".unit_type").text marker.GetBuildingType()
		unit_style_description = ''
		if listing_object.unit_style_options? and listing_object.unit_style_description?
			unit_style_description = listing_object.unit_style_options + '-' + listing_object.unit_style_description
		@div.find('.unit_style_description').text unit_style_description
		@div.find('unit_style_description').text 
		@linkWebsite ".website_link", listing_object.website
		@setAvailability "available", listing_object.available
		@setOwnerName "property_manager", listing_object.listing_id
		@setPrimaryImage "property_image", listing_object.listing_id
		@setFullPage "full_page_link", listing_object.listing_id
		@setFullPageContact "full_page_contact", listing_object.listing_id
		@div.find(".share_btn").unbind "click"
		@div.find(".facebook_share").click ()->
			A2Cribs.ShareManager.ShareListingOnFacebook(listing_object.listing_id,
				marker.street_address, marker.city, marker.state, marker.zip)
		@div.find(".link_share").click ()->
			A2Cribs.ShareManager.CopyListingUrl(listing_object.listing_id,
				marker.street_address, marker.city, marker.state, marker.zip)
		@div.find(".twitter_share").click ()->
			A2Cribs.ShareManager.ShareListingOnTwitter(listing_object.listing_id,
				marker.street_address, marker.city, marker.state, marker.zip)
		A2Cribs.FavoritesManager.setFavoriteButton "favorite_listing", listing_object.listing_id, A2Cribs.FavoritesManager.FavoritesListingIds

	@resolveDateRange: (startDate) ->
		range = "Unknown Start Date"
		if startDate?
			rmonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
			range = ""
			startSplit = startDate.split "-"
			range = "#{rmonth[+startSplit[1] - 1]} #{parseInt(startSplit[2], 10)}, #{startSplit[0]}"
		return range

	@setAvailability: (div_name, availability) ->
		if not availability?
			$(".#{div_name}").hide()
		else if availability
			$(".#{div_name}").show().text "Available"
			$(".#{div_name}").removeClass "leased"
		else
			$(".#{div_name}").show().text "Leased"
			$(".#{div_name}").addClass "leased"

	@linkWebsite: (div_name, link) ->
		if link?
			if link.indexOf("http") is -1
				link = "http://" + link
			@div.find(div_name).attr "href", link
			@div.find(div_name).attr "onclick", ""
		else
			@div.find(div_name).attr "onclick", "A2Cribs.UIManager.Error('This owner does not have a website for this listing')"

	@setOwnerName: (div_name, listing_id) ->
		listing = A2Cribs.UserCache.Get "listing", listing_id
		user = A2Cribs.UserCache.Get "user", listing.user_id
		if user?.company_name?
			$(".#{div_name}").show().text user.company_name
		else if user?.first_name? and user.last_name
			$(".#{div_name}").show().text "#{user.first_name} #{user.last_name}"
		else
			$(".#{div_name}").hide()
		if user?.verified
			@div.find(".verified").show()
		else
			@div.find(".verified").hide()

	@setPrimaryImage: (div_name, listing_id) ->
		if A2Cribs.UserCache.Get("image", listing_id)?
			image_url = A2Cribs.UserCache.Get("image", listing_id).GetPrimary()
			if image_url?
				$(".#{div_name}").css "background-image", "url(/#{image_url})"
		else
			$(".#{div_name}").css "background-image", "url(/img/tooltip/no_photo.jpg)"

	@setFullPage: (div_name, listing_id) ->
		$(".#{div_name}").unbind "click"
		$(".#{div_name}").click () ->
			A2Cribs.MixPanel A2Cribs.UserCache.Get("listing", listing_id), "full page"
			link = "/listings/view/#{listing_id}"
			win = window.open link, '_blank'
			win.focus()

	@setFullPageContact: (div_name, listing_id) ->
		$(".#{div_name}").unbind "click"
		$(".#{div_name}").click () ->
			A2Cribs.MixPanel A2Cribs.UserCache.Get("listing", listing_id), "full page contact user"
			link = "/messages/contact/#{listing_id}"
			win = window.open link, '_blank'
			win.focus()

	###
	takes as arguments the x and y position of the clicked marker
	returns the x and y amounts to pan the map so that the click bubble fits on the screen
	###
	@GetAdjustedClickBubblePosition: (marker_x, marker_y) ->
		# for y, high and low refer to high and low on the page, not numerically, since it is opposite.
		y_high = marker_y + @OFFSET['TOP']
		y_low = marker_y + @OFFSET['TOP'] + $(".click-bubble").height()
		x_max = marker_x + @OFFSET['LEFT'] + $(".click-bubble").width()

		# compare to map boundaries dimensions
		offset = {}
		offset.x = 0
		offset.y = 0

		RIGHT = $("#map_region").width()
		BOTTOM =$(window).height() - 5
		filter_offset = $("#map_filter").offset()
		TOP = filter_offset.top
		if y_high < (TOP + @PADDING)
			offset.y = y_high - (TOP + @PADDING)

		if y_low > (BOTTOM - @PADDING)
			offset.y = y_low - (BOTTOM - @PADDING)

		if x_max > (RIGHT - @PADDING)
			offset.x = x_max - (RIGHT - @PADDING)

		return offset
