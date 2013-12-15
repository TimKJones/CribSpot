###
LargeBubble class
###

class LargeBubble
	@OFFSET = 
		TOP: -190
		LEFT: 140
	@PADDING = 50 #padding on sides of click bubble after panning to make click bubble fit on map
	@IsOpen = false

	###
	When the map is initialized, call init for the map
	###
	$(document).ready =>
		$("#map_region").on "map_initialized", (event, map) =>
			@Init map

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
		@div = $(".large-bubble:first")
		google.maps.event.addListener @map, 'center_changed', => 
			@Close()
		@div.find(".close_button").click =>
			@Close()
		$("#map_region").on 'close_bubbles', =>
			@Close()

		$("#map_region").on "marker_clicked", (event, marker) =>
			# Pan map to leave enough room for click bubble
			marker_pixel_position = @ConvertLatLongToPixels marker.GMarker.getPosition()
			pixels_to_pan = @GetAdjustedLargeBubblePosition marker_pixel_position.x, marker_pixel_position.y
			@map.panBy pixels_to_pan.x, pixels_to_pan.y

		$('#map_region').on 'listing_click', (event, listing_id) =>
			@Open listing_id

		@div.draggable({
      revert: true
      opacity: 0.7
      cursorAt: {
        top: -12
        right: -20
      }
      helper: (event) ->
        name = $(this).find('.building_name').html() || "this listing"
        $( "<div class='listing-drag-helper'>Share #{name}</div>" )
      start: (event) ->
        if A2Cribs.Login?.logged_in
            $('ul.friends, #hotlist').addClass('dragging')
            A2Cribs.HotlistObj.startedDragging()
      stop: (event) ->
      	$('ul.friends, #hotlist').removeClass('dragging')
      	A2Cribs.HotlistObj.stoppedDragging()
      appendTo: 'body'
     })

	###
	Opens the tooltip given a marker, with popping animation
	Returns deferred object that gets resolved after LargeBubble is loaded.
	After it is loaded and visible, load its image.
	###
	@Open: (listing_id) ->
		@IsOpen = true
		$("#map_canvas").trigger "click_bubble_open", [listing_id]
		openDeferred = new $.Deferred()

		if listing_id?
			$("#loader").show()
			A2Cribs.UserCache.GetListing(A2Cribs.Map.ACTIVE_LISTING_TYPE, listing_id)
			.done (listing) =>
				A2Cribs.MixPanel.Click listing, "large popup"
				@SetContent listing.GetObject()
				@Show listing_id
				openDeferred.resolve listing_id
			.fail =>
				A2Cribs.UIManager.Error "Sorry - We could not find this listing!"
			.always =>
				$("#loader").hide()

		return openDeferred.promise()

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
		@div.data('listing_id', listing_object.listing_id)
		for key,value of listing_object
			@div.find(".#{key}").text value
		@div.find(".start_date").text @resolveDateRange listing_object.start_date
		if listing_object.end_date?
			@div.find(".lease_length").text @resolveDateRange listing_object.end_date
			@div.find(".lease_box").hide()
			@div.find(".end_date_box").show()

		else
			@div.find(".end_date_box").hide()
			@div.find(".lease_box").show()
		marker = A2Cribs.UserCache.Get "marker", A2Cribs.UserCache.Get("listing", listing_object.listing_id).marker_id
		@div.find(".building_name").text marker.GetName()
		@div.find(".unit_type").text marker.GetBuildingType()
		unit_style_description = ''
		if (listing_object.unit_style_options? and listing_object.unit_style_description?)
			unit_style_description = listing_object.unit_style_options + '-' + listing_object.unit_style_description
		else if listing_object.unit_style_options == 'Entire House'
			unit_style_description = 'Entire House'

		@div.find('.unit_style_description').text unit_style_description
		@setBeds listing_object.beds
		@linkWebsite ".website_link", listing_object.website, listing_object.listing_id
		@setRent listing_object.rent
		@setAvailability "available", listing_object.available
		@setOwnerName "property_manager", listing_object.listing_id
		@setPrimaryImage "property_image", listing_object.listing_id
		@setFullPage "full_page_link", listing_object.listing_id
		@setFullPageContact "full_page_contact", listing_object.listing_id
		@setFullPageSchedule "schedule_tour", listing_object.listing_id
		@div.find(".share_btn").unbind "click"
		@div.find(".facebook_share").click ()->
			A2Cribs.ShareManager.ShareListingOnFacebook(listing_object.listing_id,
				marker.street_address, marker.city, marker.state, marker.zip, listing_object.description, 
				listing_object.building_type_id)
		@div.find(".link_share").click ()->
			A2Cribs.ShareManager.CopyListingUrl(listing_object.listing_id,
				marker.street_address, marker.city, marker.state, marker.zip)
		@div.find(".twitter_share").click ()->
			A2Cribs.ShareManager.ShareListingOnTwitter(listing_object.listing_id,
				marker.street_address, marker.city, marker.state, marker.zip)
		@div.find(".hotlist_share").popover
			content: ->
				# console.log('init popover', listing_object.listing_id)
				A2Cribs.HotlistObj.getHotlistForPopup(listing_object.listing_id)
				# "Hello<a href='#'>hello</a>"
			html: true
			trigger: 'manual'
			container: 'body'
			title: 'Share this listing'
		.click (e) -> 
			e.preventDefault()
			$(this).popover('show')
			$('.popover a').on 'click', =>
        $('.popover').popover('hide').hide()
        $('.popover').off('click')
    .find("#share-to-email").keyup (event) ->
      $(".share-to-email-btn").click() if event.keyCode is 13


		@div.find(".favorite_listing").data "listing-id", listing_object.listing_id
		A2Cribs.FavoritesManager.setFavoriteButton @div.find(".favorite_listing"), listing_object.listing_id

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
			@div.find(".#{div_name}").hide()
		else if availability
			@div.find(".#{div_name}").show().text "Available"
			@div.find(".#{div_name}").removeClass "leased"
		else
			@div.find(".#{div_name}").show().text "Leased"
			@div.find(".#{div_name}").addClass "leased"

	@linkWebsite: (div_name, link, listing_id) ->
		mix_object = A2Cribs.UserCache.Get("listing", listing_id)
		if not mix_object?
			mix_object = {}
		mix_object["logged_in"] = A2Cribs.Login?.logged_in

		if link?
			@div.find(div_name).unbind("click").click () =>
				if A2Cribs.Login?.logged_in is yes
					A2Cribs.MixPanel.Click mix_object, "go to realtor's website"
					window.open "/listings/website/#{listing_id}", '_blank'
				else 
					$("#signup_modal").modal("show").find(".signup_message").text "Please signup to view this website"
					A2Cribs.MixPanel.Event "login required",
						"listing_id": listing_id
						action: "go to realtor's website"
		else
			@div.find(div_name).unbind("click").click () => A2Cribs.UIManager.Error('This owner does not have a website for this listing')

	@setRent: (rent) ->
		if not rent?
			@div.find(".rent").text "Ask for Rent"
			@div.find(".per_month").text ""
			@div.find(".price_label").text ""
		else if parseInt(rent, 10) isnt 0
			@div.find(".rent").text rent
			@div.find(".per_month").text "/m"
			@div.find(".price_label").text "$"
		else
			@div.find(".rent").text "Call for Rent"
			@div.find(".per_month").text ""
			@div.find(".price_label").text ""


	@setOwnerName: (div_name, listing_id) ->
		listing = A2Cribs.UserCache.Get "listing", listing_id
		user = A2Cribs.UserCache.Get "user", listing.user_id
		if user?.company_name?
			$(".#{div_name}").show().text user.company_name
		else if user?.first_name? and user.last_name
			$(".#{div_name}").show().text "#{user.first_name}"
		else
			$(".#{div_name}").hide()
		if user?.verified
			@div.find(".verified").show()
		else
			@div.find(".verified").hide()

	@setPrimaryImage: (div_name, listing_id) ->
		if A2Cribs.UserCache.Get("image", listing_id)?
			image_url = A2Cribs.UserCache.Get("image", listing_id).GetPrimary()
			if image_url? and div_name?
				image_url = @_processImagePath image_url
				$(".#{div_name}").css "background-image", "url(/#{image_url})"
		else if div_name?
			$(".#{div_name}").css "background-image", "url(/img/tooltip/no_photo.jpg)"

	###
	Prepends 'med_' to the filename and returns result
	###
	@_processImagePath: (path) ->
		directory = path.substr(0, path.lastIndexOf '/')
		filename = 'med_' + path.substr(path.lastIndexOf('/') + 1)
		return directory + '/' + filename

	@setFullPage: (div_name, listing_id) ->
		$(".#{div_name}").unbind "click"
		$(".#{div_name}").click () ->
			A2Cribs.MixPanel.Click A2Cribs.UserCache.Get("listing", listing_id), "full page"
			link = "/listings/view/#{listing_id}"
			win = window.open link, '_blank'
			win.focus()

	@setFullPageContact: (div_name, listing_id) ->
		$(".#{div_name}").unbind "click"
		$(".#{div_name}").click () ->
			link = "/messages/contact/#{listing_id}"
			win = window.open link, '_blank'
			win.focus()

	@setFullPageSchedule: (div_name, listing_id) ->
		listing = A2Cribs.UserCache.Get("listing", listing_id)
		if listing?.available is yes
			$(".#{div_name}").show()
		else
			$(".#{div_name}").hide()
		$(".#{div_name}").unbind "click"
		$(".#{div_name}").click () ->
			link = "/tours/schedule/#{listing_id}"
			win = window.open link, '_blank'
			win.focus()

	@setBeds: (bed_count) ->
		if not bed_count? or parseInt(bed_count, 10) is NaN
			@div.find(".beds").text "??"
			@div.find(".bed_desc").text "Beds"
		else if parseInt(bed_count, 10) is 0
			@div.find(".beds").text "Studio"
			@div.find(".bed_desc").text ""
		else if parseInt(bed_count, 10) is 1
			@div.find(".bed_desc").text bed_count
			@div.find(".bed_desc").text "Bed"
		else
			@div.find(".bed_desc").text bed_count
			@div.find(".bed_desc").text "Beds"

	###
	takes as arguments the x and y position of the clicked marker
	returns the x and y amounts to pan the map so that the click bubble fits on the screen
	###
	@GetAdjustedLargeBubblePosition: (marker_x, marker_y) ->
		# for y, high and low refer to high and low on the page, not numerically, since it is opposite.
		y_high = marker_y + @OFFSET['TOP']
		y_low = marker_y + @OFFSET['TOP'] + $(".large-bubble").height()
		x_max = marker_x + @OFFSET['LEFT'] + $(".large-bubble").width()

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
