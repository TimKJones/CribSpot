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
				if A2Cribs.RentalSave?.Editable
					A2Cribs.UIManager.ConfirmBox "By leaving this page, all unsaved changes will be lost.",
						{
							"ok": "Abort Changes & Continue"
							"cancel": "Return to Editor"
						}, (success) =>
							if success
								A2Cribs.RentalSave.CancelEditing()
								@ContentHeaderClick event
				else
					@ContentHeaderClick event

			content_header.next?('.drop-down')
				.find('.drop-down-list').click =>
					@ShowContent content

		$("#feature-btn").click (event)=>
			@Direct({'classname' : 'featured-listing'})

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

		@AttachListeners()
		#@GetListings()
		@GetUserMarkerData()

	###
	Attach Listeners
	Attaches events listeners to objects
	###
	@AttachListeners: ->
		$(".list_content").on "marker_added", (event, marker_id) =>
			listing_type = $(event.currentTarget).data "listing-type"
			if $(event.currentTarget).find("##{marker_id}").length is 0
				name = A2Cribs.UserCache.Get("marker", marker_id).GetName()
				list_item = $ "<li />", {
					text: name
					class: "#{listing_type}_list_item"
					id: marker_id
				}
				$(event.currentTarget).append list_item
				$(event.currentTarget).slideDown()
			A2Cribs.Dashboard.Direct { classname: listing_type, data: true }

	###

	###
	@ContentHeaderClick: (event) ->
		content_header = $(event.delegateTarget)
		class_name = content_header.attr 'classname'
		content = $('.' + class_name + '-content')
		$('.content-header.active').removeClass "active"
		$(event.delegateTarget).addClass "active"

		if content_header.hasClass "list-dropdown-header"
			if not $("##{class_name}_list").is(":visible")
				if $(".list-dropdown.active").size() isnt 0
					$(".list-dropdown.active").removeClass("active").slideUp 'fast', () ->
						$("##{class_name}_list").addClass("active").slideDown()
				else
					$("##{class_name}_list").addClass("active").slideDown()
		else
			$(".list-dropdown").slideUp()
			@ShowContent content, true

	
	###
	Retrieves all basic marker_data for the logged in user and updates nav bar in dashboard
	###
	@GetUserMarkerData: () ->
		url = myBaseUrl + "listings/GetMarkerDataByLoggedInUser"
		$.ajax 
            url: url
            type:"GET"
            success: @GetUserMarkerDataCallback

	@GetUserMarkerDataCallback: (data) =>

		# Counts listings and adds them to the dropdown list
		listings_count = [0, 0, 0]
		listing_types = ["rental", "sublet", "parking"]

		A2Cribs.UserCache.CacheData JSON.parse data

		listings = A2Cribs.UserCache.Get "listing"

		for listing in listings
			marker = A2Cribs.UserCache.Get "marker", listing.marker_id
			if $("##{listing_types[listing.listing_type]}_list_content").find("##{marker.GetId()}").length is 0
				list_item = $ "<li />", {
					text: marker.GetName()
					class: "#{listing_types[listing.listing_type]}_list_item"
					id: marker.GetId()
				}
			$("##{listing_types[listing.listing_type]}_list_content").append list_item
			listings_count[listing.listing_type] += 1
		for listing_type, i in listing_types	
			$("##{listing_type}_count").text listings_count[i]

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
		$.ajax 
            url: url
            type:"GET"
            success: @GetListingsCallback

        return @DeferedListings.promise()

	@GetListingsCallback: (data) =>
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
		content.siblings().addClass('hidden').hide()
		content.removeClass('hidden').hide().fadeIn()
		content.trigger 'shown'

	@HideContent: (classname)->
		$(".#{ classname }-content").addClass 'hidden'

	@Direct: (directive)->
		content_header = $("##{directive.classname}-content-header")
		content_header.trigger 'click'
		if directive.data?
			@ShowContent($(".#{directive.classname}-content"))
