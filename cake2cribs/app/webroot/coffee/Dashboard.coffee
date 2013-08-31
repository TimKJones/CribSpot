class A2Cribs.Dashboard
	
	@SetupUI:()->
		$(window).resize =>
			@SizeContent()
		@SizeContent()
		#set up click for listings
		# Set up handlers to handle content drop downs and content switching
		$('.content-header').each (index, element)=>
			content_header = $(element)
			class_name = content_header.attr 'classname'
			content = $('.' + class_name + '-content')
			
			$(element).click (event)=>
				$(".list-dropdown").slideUp()
				$('.content-header.active').removeClass "active"
				$(event.delegateTarget).addClass "active"
				if content_header.hasClass "list-dropdown-header"
					#Toggle Drop down
					$("##{class_name}_list").slideDown()
				else
					@ShowContent content, true

			content_header.next?('.drop-down')
				.find('.drop-down-list').click =>
					@ShowContent content

		$("#feature-btn").click (event)=>
			@Direct({'classname' : 'featured-listing'})

		$("#create-listing").find("a").click (event) =>
			A2Cribs.MarkerModal.NewMarker()
			A2Cribs.MarkerModal.Open()

		$("body").on 'click', '.messages_list_item', (event) =>
			@ShowContent $('.messages-content')

		list_content_height = $("#navigation-bar").parent().height() - $("#navigation-bar").height() - 68
		$(".list_content").css "height", list_content_height + "px"

		###
		Search listener
		###
		$('.dropdown-search').keyup (event) ->
			list = $(event.delegateTarget).attr "data-filter-list"
			$("#{list} li").show().filter () ->
				if $(this).text().toLowerCase().indexOf($(event.delegateTarget).val().toLowerCase()) is -1
					return true
				return false
			.hide()

		#@GetListings()
		@GetUserMarkerData()


	
	###
	Retrieves all basic marker_data for the logged in user and updates nav bar in dashboard
	###
	@GetUserMarkerData: () ->
		url = myBaseUrl + "listings/GetMarkerDataByLoggedInUser"
		$.get url, (data) =>
			markers = JSON.parse data
			###
			for item in response_data
				for key, value of item
					if A2Cribs[key]?
						A2Cribs.UserCache.Set new A2Cribs[key] value
					else if A2Cribs[key]? and value.length? # Is an array
						for i in value
							A2Cribs.UserCache.Set new A2Cribs[key] i
			###

			# Counts listings and adds them to the dropdown list
			listings_count = [0, 0, 0]
			listing_types = ["rentals", "sublet", "parking"]
			$("#rentals_count").text markers.length
			marker_ids_processed = []

			for marker in markers
				if marker.Marker?
					marker = marker.Marker
				else
					continue

				if marker.marker_id? and marker.marker_id in marker_ids_processed
					continue

				name = marker.alternate_name
				if !marker.alternate_name || !marker.alternate_name.length
					name = marker.street_address
				list_item = $ "<li />", {
					text: name
					class: "rentals_list_item"
					id: marker.marker_id
				}
				$("#rentals_list_content").append list_item
				marker_ids_processed.push marker.marker_id

	###
	Retrieves all listings for logged-in user and adds them to the cache.

	Returns a promise that will return the cache when complete.
	This can be used by other module who want to know when the dashboard
	has the listinngs loaded. 
	###

	@GetListings: ->
		if not @DeferedListings?
			@DeferedListings = new $.Deferred()
		else
			return @DeferedListings.promise()

		url = myBaseUrl + "listings/GetListing"
		$.get url, (data) =>
			response_data = JSON.parse data
			for item in response_data
				for key, value of item
					if A2Cribs[key]?
						A2Cribs.UserCache.Set new A2Cribs[key] value

			#Create lists for everything
			listings = A2Cribs.UserCache.Get "listing"
			marker_set = {}
			for listing in listings
				if not marker_set[listing.listing_type]? then marker_set[listing.listing_type] = {}
				marker_set[listing.listing_type][listing.marker_id] = true

			@DeferedListings.resolve()

			# Counts listings and adds them to the dropdown list
			listings_count = [0, 0, 0]
			listing_types = ["rentals", "sublet", "parking"]

			for listing_type, marker_id_array of marker_set
				for marker_id of marker_id_array
					marker = A2Cribs.UserCache.Get "marker", marker_id
					name = marker.GetName()
					type = listing_types[parseInt(listing_type, 10)]
					listings_count[parseInt(listing_type, 10)]++
					list_item = $ "<li />", {
						text: name
						class: "#{type}_list_item"
						id: marker.marker_id
					}
					$("##{type}_list_content").append list_item

			for type, i in listing_types
				$("##{type}_count").text listings_count[i]


			return @DeferedListings.promise()

	@SizeContent:()->
		# Strech the widget to the bottom of the window
		# main_content = $('#main_content')
		# middle_content = $('#middle_content')
		# main_content.css 'height', Math.max((window.innerHeight - main_content.offset().top), 50) + 'px'

		# content_header is the jquery object for the side content headers that HAS a
		# drop down sibling. mode is need to be either ['toggle', 'hide', 'show']
	@SlideDropDown:(content_header, show_content)->
		
		dropdown = content_header.next('.drop-down')
		if dropdown.length == 0
			return

		toggle_icon = content_header.children('i')[0] #arrow icon
		$(toggle_icon)
			.toggleClass('icon-caret-right', !show_content)
			.toggleClass('icon-caret-down', show_content)

		
		$(content_header)
			.toggleClass('shadowed', show_content)
			.toggleClass('expanded', show_content)
			.toggleClass('minimized', !show_content)

		if show_content
			dropdown.slideDown 'fast'
		else
			dropdown.slideUp 'fast'

	@ShowContent:(content)->
		content.siblings().addClass 'hidden'
		content.removeClass 'hidden'
		content.trigger 'shown'

	@HideContent: (classname)->
		$(".#{ classname }-content").addClass 'hidden'

	@Direct: (directive)->
		content_header = $('#' + directive.classname + "-content-header")
		content_header.trigger 'click'
		if directive.data?
			@ShowContent($('.' + directive.classname + "-content"))
