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

	@GetListings: ->
		url = myBaseUrl + "listings/GetListing"
		$.get url, (data) =>
			response_data = JSON.parse data
			A2Cribs.UserCache.CacheListings response_data
			#Get count of sublets/parking/rentals (TODO)

			#Create lists for everything
			listing_markers = A2Cribs.UserCache.GetListingMarkers()
			for type, marker_set of listing_markers
				for marker in marker_set
					name = if marker.alternate_name? and marker.alternate_name.length then marker.alternate_name else marker.street_address
					list_item = $ "<li />", {
						text: name
						class: "#{type}_list_item"
						id: marker.marker_id
					}
					$("##{type}_list").append list_item



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
