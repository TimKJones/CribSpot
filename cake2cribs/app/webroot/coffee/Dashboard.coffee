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
				if content_header.hasClass "list-dropdown-header"
					#Toggle Drop down
					$("##{class_name}_list").slideDown()
				else
					@ShowContent content, true

			content_header.next?('.drop-down')
				.find('.drop-down-list').click =>
					@ShowContent content

		@GetListings()

	# Returns a promise that will return the cache when complete.
	# This can be used by other module who want to know when the dashboard
	# has the listinngs loaded. 
	
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
					if A2Cribs[key]? and not value.length?
						A2Cribs.UserCache.Set new A2Cribs[key] value
					else if A2Cribs[key]? and value.length? # Is an array
						for i in value
							A2Cribs.UserCache.Set new A2Cribs[key] i

			@DeferedListings.resolve() # Resolve the deffered so anyone
			# Waiting on the data to be loaded now knows the listings 
			# are in the cache.

			#Get count of sublets/parking/rentals (TODO)

			#Create lists for everything
			listings = A2Cribs.UserCache.Get "listing"
			marker_set = {}
			for listing in listings
				if not marker_set[listing.listing_type]? then marker_set[listing.listing_type] = {}
				marker_set[listing.listing_type][listing.marker_id] = true

			for listing_type, marker_id_array of marker_set
				for marker_id of marker_id_array
					marker = A2Cribs.UserCache.Get "marker", marker_id
					name = if marker.alternate_name? and marker.alternate_name.length then marker.alternate_name else marker.street_address
					type = null
					if parseInt(listing_type, 10) is 0 then type = "rentals"
					if parseInt(listing_type, 10) is 1 then type = "sublet"
					if parseInt(listing_type, 10) is 2 then type = "parking"
					list_item = $ "<li />", {
						text: name
						class: "#{type}_list_item"
						id: marker.marker_id
					}
					$("##{type}_list").append list_item

			
			

		return @DeferedListings.promise()


	@SizeContent:()->
		# Strech the widget to the bottom of the window
		main_content = $('#main_content')
		middle_content = $('#middle_content')
		main_content.css 'height', Math.max((window.innerHeight - main_content.offset().top), 750) + 'px'

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
