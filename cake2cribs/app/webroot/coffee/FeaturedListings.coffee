class A2Cribs.FeaturedListings
	@FeaturedPMIdToListingIdsMap = []
	@FeaturedPMListingsVisible = false
	
	@resizeHandler: ->
		h = $(window).height() - $('#listings-list').offset().top - $('.legal-bar').height()
		$('#listings-list').height(h)

		w = $(window).width()
		if w < 900
			$('.fl-sb-item').draggable('disable')
		else
			$('.fl-sb-item').draggable('enable')

	@SetupResizing: ->
		@resizeHandler()
		$(window).on('resize', @resizeHandler)

	@GetFlIds:(university_id)->
		deferred = new $.Deferred()
		$.get "/featuredListings/cycleIds/#{university_id}/#{@FL_LIMIT}", (response)=>
			listing_ids = JSON.parse(response)
			if listing_ids?
				deferred.resolve(listing_ids)
			else
				deferred.resolve(null)
		return deferred.promise()

	@FL_LIMIT: 5

	@GetListingDeferred:(id, type)->
		deferred = new $.Deferred()
		# Need closure
		listing_id = id
		listing_type = type
		$.ajax
			url: myBaseUrl + "Listings/GetListing/" + listing_id
			type:"GET"
			success: (data) =>
				response_data = JSON.parse data
				for item in response_data
					for key, value of item
						if A2Cribs[key]?
							A2Cribs.UserCache.Set new A2Cribs[key] value
				listing = A2Cribs.UserCache.Get listing_type, listing_id
				deferred.resolve(item)
			error: ()=>
				deferred.resolve(null)
				
		return deferred.promise()


	@FetchListingsByIds:(listing_ids, active_listing_type)->
			
		deferred = new $.Deferred()

		if not listing_ids or listing_ids.length < 1
			deferred.resolve(null)
			return deferred

		listingDefereds = []
		for id in listing_ids
			listingDefereds.push A2Cribs.FeaturedListings.GetListingDeferred(id, active_listing_type)

		$.when.apply($, listingDefereds).then ()=>
			deferred.resolve(arguments)

		return deferred.promise()

	@GetRandomListingsFromMap:(num, all_listing_ids)->
		shuf = _.shuffle(all_listing_ids)
		sliced = shuf.slice 0, num
		return sliced

	@SetupListingItemEvents: ->
		$el = $('.fl-sb-item')
		$el.unbind()

		$el.draggable
			revert: true
			opacity: 0.7
			cursorAt:
				top: -12
				right: -20
			helper: (event) ->
				name = $(this).find('.name').html() || "this listing"
				$( "<div class='listing-drag-helper'>Share #{name}</div>" )
			start: (event) ->
				if A2Cribs.Login?.logged_in
					$('ul.friends, #hotlist').addClass('dragging')
					A2Cribs.HotlistObj.startedDragging()
			stop: (event) ->
				$('ul.friends, #hotlist').removeClass('dragging')
				A2Cribs.HotlistObj.stoppedDragging()
			appendTo: 'body'

	@GetListingObjects: (listing_ids) ->
		listings = []
		for id in listing_ids
			listingObject = {}
			listing = A2Cribs.UserCache.Get('listing', id)
			marker = listing_object = null
			if listing?
				listing.InSidebar yes
				marker = A2Cribs.UserCache.Get('marker', listing.marker_id)
				listing_object = A2Cribs.UserCache.Get(A2Cribs.Map.ACTIVE_LISTING_TYPE, id)
				if listing_object[0]?
					listing_object = listing_object[0]
			if listing? and marker? and listing_object?
				listingObject.Listing = listing
				listingObject.Marker = marker
				listingObject.ListingObject = listing_object
				listings.push listingObject
		return listings

	@BuildListingIds: (flIds) ->
		NUM_RANDOM_LISTINGS = 2500

		listings = A2Cribs.UserCache.Get('listing')
		# get list of all listing_ids loaded...then get random set for sidebar
		all_listing_ids = []
		for listing in listings
			if listing? and listing.listing_id
				all_listing_ids.push parseInt listing.listing_id
		randomIds = null
		if all_listing_ids.length > 0
			randomIds = @GetRandomListingsFromMap(NUM_RANDOM_LISTINGS, all_listing_ids)

		if not flIds? and not randomIds?
			return null

		# combine featured listings and random listings to get list of all sidebar listing ids
		sidebar_listing_ids = []
		for id in flIds
			id = parseInt id
			@FLListingIds.push id
			sidebar_listing_ids.push id
		if randomIds?
			for id in randomIds
				sidebar_listing_ids.push id

		return sidebar_listing_ids

	@SetupScrollEvents: ->
		$(window).scroll ->
			unless $('.fl-sb-item').hasClass('no-listings')
				if $(this).scrollTop() + $(this).innerHeight() >= $('.featured-listings-wrapper').height()
					A2Cribs.FeaturedListings.LoadMoreListings()

		$('#listings-list').scroll ->
			unless $('.fl-sb-item').hasClass('no-listings')
				if $(this).scrollTop() + $(this).innerHeight() >= $('#ran-listings').height()
					A2Cribs.FeaturedListings.LoadMoreListings()

	@LoadMoreListings: ->
		@GetSidebarImagePathsDeferred = new $.Deferred()
		$('#loading-spinner').show()
		
		if @current_index? and @listing_ids?
			@listingObjects = @GetListingObjects(@listing_ids[@current_index..@current_index + 24])
			@GetSidebarImagePaths(@listing_ids[@current_index..@current_index + 24])
			@sidebar.addListings @listingObjects, 'ran'
			@current_index += 25
		else
			console.log 'warning: no listing ids or current index found.'

		@SetupListingItemEvents()
		$.when(@GetSidebarImagePathsDeferred).then (images) =>
			images = JSON.parse images
			for image in images
				if image? and image.Image?
					$("#fl-sb-item-#{image.Image.listing_id} .img-wrapper").css('background-image', "url(/#{image.Image.image_path})")
			$('#loading-spinner').hide()

	@UpdateSidebar: (listing_ids) ->
		# resolved after image paths have been loaded
		@GetSidebarImagePathsDeferred = new $.Deferred()
		@listing_ids = listing_ids

		@current_index = 0
		if @listing_ids?
			#fetch listing data for these listing_ids from the cache
			@listingObjects = @GetListingObjects(@listing_ids[0..24])
			@sidebar.addListings @listingObjects, 'ran', true

			# Fetch primary image paths for all listings in sidebar
			@GetSidebarImagePaths(@listing_ids[0..24])

			# Setup events related to individual listing items
			@SetupListingItemEvents()

		$.when(@GetSidebarImagePathsDeferred).then (images) =>
			images = JSON.parse images
			if images?
				for image in images
					if image? and image.Image?
						$("#fl-sb-item-#{image.Image.listing_id} .img-wrapper").css('background-image', "url(/#{image.Image.image_path})")
			$('#loading-spinner').hide()


	@InitializeSidebar:(university_id, active_listing_type, basicDataDeferred, basicDataCachedDeferred)->
		alt = active_listing_type
		if not @SidebarListingCache?
			@SidebarListingCache = {}
		if not @FLListingIds?
			@FLListingIds = []

		
		@sidebar = new Sidebar($('#fl-side-bar'))
		@current_index = 0
	
		# get listing_ids for featured listings for today
		getFlIdsDeferred = @GetFlIds(university_id)

		# resolved after image paths have been loaded
		@GetSidebarImagePathsDeferred = new $.Deferred()

		@SetupResizing()
		@SetupScrollEvents()

		# We have the featured listing listing ids for the sidebar
		# Now get random listing ids from the basic data (already loaded) to fill out the sidebar
		$.when(getFlIdsDeferred, basicDataCachedDeferred).then (flIds) =>
			#build list of listing ids for sidebar
			sidebar_listing_ids = @BuildListingIds(flIds)

			if sidebar_listing_ids?
				#fetch listing data for these listing_ids from the cache
				@sidebar.addListings @GetListingObjects(sidebar_listing_ids[0..24]), 'ran', true
				@listing_ids = sidebar_listing_ids

				# Fetch primary image paths for all listings in sidebar
				@GetSidebarImagePaths(sidebar_listing_ids[0..24])

				# Setup events related to individual listing items
				@SetupListingItemEvents()

				$('#listings-list').on 'click', '.fl-sb-item', (event) ->
					$map = $('#map_region')
					if $map.is(':visible')
						marker_id = parseInt($(this).attr('marker_id'))
						listing_id = parseInt($(this).attr('listing_id'))
						marker = A2Cribs.UserCache.Get('marker', marker_id)
						listing = A2Cribs.UserCache.Get('listing', listing_id)  
						markerPosition = marker.GMarker.getPosition()

						A2Cribs.Map.GMap.setZoom 16
												# A2Cribs.MixPanel.Click listing, 'sidebar listing'
						A2Cribs.Map.CenterMap markerPosition.lat(), markerPosition.lng()

						$map.trigger "marker_clicked", [marker]
					else
						if $(this).hasClass('expanded')
							$(this).removeClass('expanded')
						else
							$(this).addClass('expanded')
							setTimeout ->
								$('.fl-sb-item.expanded').not(event.currentTarget).removeClass('expanded')
							, 200
			else
				@listing_ids = []


			# Set favorite add/delete event handlers for sidebar
			#for listing in listings
			#	if listing.Listing?
			#		A2Cribs.FavoritesManager.setFavoriteButton listing.Listing.listing_id.toString(), null, A2Cribs.FavoritesManager.FavoritesListingIds
			$(".fl-sb-item")
			.click (event) =>
				marker_id = parseInt($(event.currentTarget).attr('marker_id'))
				listing_id = parseInt($(event.currentTarget).attr('listing_id'))
				marker = A2Cribs.UserCache.Get('marker', marker_id)
				listing = A2Cribs.UserCache.Get('listing', listing_id)
				A2Cribs.Map.GMap.setZoom 16
				$("#map_region").trigger "marker_clicked", [marker]
				featured_text = if listing.IsFeatured() then "Featured Listing" else "Normal Listing"
				$(document).trigger "track_event", ["Listing", "Sidebar Click", featured_text, listing_id]
				markerPosition = marker.GMarker.getPosition()
				A2Cribs.Map.CenterMap markerPosition.lat(), markerPosition.lng()
			.draggable
				revert: true
				opacity: 0.7
				cursorAt:
					top: -12
					right: -20
				helper: (event) ->
					name = $(this).find('.name').html() || "this listing"
					$( "<div class='listing-drag-helper'>Share #{name}</div>" )
				start: (event) ->
					if A2Cribs.Login?.logged_in
						$('ul.friends, #hotlist').addClass('dragging')
						A2Cribs.HotlistObj.startedDragging()
				stop: (event) ->
					$('ul.friends, #hotlist').removeClass('dragging')
					A2Cribs.HotlistObj.stoppedDragging()
				appendTo: 'body'
			
		$.when(@GetSidebarImagePathsDeferred).then (images) =>
			images = JSON.parse images
			if images?
				for image in images
					if image? and image.Image?
						$("#fl-sb-item-#{image.Image.listing_id} .img-wrapper").css('background-image', "url(/#{image.Image.image_path})")

		$('#loading-spinner').hide()


	# Fetch the primary images for listings in listing_ids
	@GetSidebarImagePaths: (listing_ids) =>
		$.ajax
			url: myBaseUrl + "Images/GetPrimaryImages/" + JSON.stringify listing_ids
			type:"GET"
			success: (data) =>
				@GetSidebarImagePathsDeferred.resolve(data)
			error: ()=>
				@GetSidebarImagePathsDeferred.resolve(null)

	@LoadFeaturedPMListings: () =>
		$.ajax
			url: myBaseUrl + "Listings/GetFeaturedPMListings/" + A2Cribs.Map.CurentSchoolId
			type:"GET"
			success: (data) =>
				@FeaturedPMIdToListingIdsMap = JSON.parse data

				$(".featured_pm").click (event) =>
					user_id = $(event.delegateTarget).data "user-id"
					if @FeaturedPMIdToListingIdsMap[user_id]?
						listing_ids = @FeaturedPMIdToListingIdsMap[user_id]
						if A2Cribs.Map.ToggleListingVisibility(listing_ids, "PM_#{user_id}")
							A2Cribs.Map.IsCluster yes
						else
							A2Cribs.Map.IsCluster no
							$(event.delegateTarget).addClass "active"
							$(document).trigger "track_event", ["Advertising", "Featured PM", "", user_id]

			error: ()=>
				@FeaturedPMIdToListingIdsMap = []

	class Sidebar
		constructor:(@SidebarUI)->
			@ListItemTemplate = _.template(A2Cribs.FeaturedListings.ListItemHTML)
			@EmptyListingsTemplate = _.template(A2Cribs.FeaturedListings.EmptyListingsHTML)

		addListings:(listings, list, clear=false)->
			if listings is null or listings.length is 0
				list_html = $(@EmptyListingsTemplate({clear: clear}))
			else
				list_html = @getListHtml(listings)
			if clear
				@SidebarUI.find("##{list}-listings").html list_html
			else
				@SidebarUI.find("##{list}-listings").append list_html


		getDateString:(date)->
			
			if not @MonthArray?
				@MonthArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']


			month = @MonthArray[date.getMonth()]
			year = date.getFullYear()
			return "#{month} #{year}"

		getListHtml: (listings) ->
			list = $("<div />")
			for listing in listings
				rent = name = beds = lease_length = start_date = null

				if listing.ListingObject.rent?
					rent = parseFloat(listing.ListingObject.rent).toFixed(0)
				else
					rent = ' --'

				if listing.Marker.alternate_name?
					name = listing.Marker.alternate_name
				else
					name = listing.Marker.street_address

				if listing.ListingObject.lease_length?
					lease_length = listing.ListingObject.lease_length
				else
					lease_length = '-- '
				
				if listing.ListingObject.beds > 1
					beds = "#{listing.ListingObject.beds} beds"
				else if listing.ListingObject.beds?
					beds = "#{listing.ListingObject.beds} bed"
				else
					beds = "?? beds"

				if listing.ListingObject.start_date?
					# Fix date bug in firefox
					start_date = listing.ListingObject.start_date.toString().replace(' ', 'T')
					start_date = @getDateString(new Date(start_date))
				else
					start_date = 'Start Date --'

				if listing.ListingObject.end_date?
					# Fix date bug in firefox
					end_date = listing.ListingObject.end_date.toString().replace(' ', 'T')
					end_date = @getDateString(new Date(end_date))

				#Process images
				primary_image_path = '/img/sidebar/no_photo_small.jpg'
				if listing.Image?
					for image in listing.Image
						if image.is_primary
							primary_image_path = '/' + image.image_path


				data = {
					rent: rent
					beds: beds
					building_type: listing.Marker.building_type_id
					start_date: start_date
					end_date: end_date
					lease_length: lease_length
					name: name
					img: primary_image_path
					listing_id: listing.Listing.listing_id
					marker_id: listing.Marker.marker_id
					available: (->
						if typeof listing.Listing.available is 'undefined'
							return 'unknown'
						if listing.Listing.available
							return 'available'
						else
							return 'unavailable'
					)()
				}

				listing_item = $(@ListItemTemplate data)
				A2Cribs.FavoritesManager.setFavoriteButton(listing_item.find(".favorite"), listing.Listing.listing_id, A2Cribs.FavoritesManager.FavoritesListingIds)
				listing_item.find(".hotlist_share a").popover
					content: ->
						A2Cribs.HotlistObj.getHotlistForPopup($(this).data('listing'))
					html: true
					trigger: 'manual'
					placement: 'left'
					container: 'body'
					title: 'Share this listing'
				.click (e) -> 
					e.preventDefault()
					$(this).popover('show')
					# $('.popover input').typeahead(A2Cribs.HotlistObj.sources)
					$('.popover a').on 'click', =>
						$('.popover').popover('hide').hide()
						$('.popover').off('click')

				listing_item.find("#share-to-email").keyup (event) ->
				  $(".share-to-email-btn").click() if event.keyCode is 13

				list.append listing_item

	@EmptyListingsHTML: """
	<div class='fl-sb-item no-listings'>
		<% if (clear) { %>
			<span class='no-listings-text'>No listings found for these filter settings. Try adjusting the filter for more results.</span>
		<% } else { %>
			<span class='no-listings-text'>No more listings found for these filter settings.</span>
		<% } %>
	</div>
	"""

	@ListItemHTML: """
	<div id = 'fl-sb-item-<%= listing_id %>' class = 'fl-sb-item' listing_id=<%= listing_id %> marker_id=<%= marker_id %>>
		<div class='listing-content'>
			<div class = 'img-wrapper' style='background-image:url("<%=img%>")'> </div>
			<div class = 'info-wrapper'>
				<div class = 'info-row'>
					<span class = 'rent price-text'><%= "$" + rent %></span>
					<span class = 'divider'>|</span>
					<span class = 'beds'><%= beds %> </span>
					<span class = 'favorite pull-right'><i class = 'icon-heart fav-icon share_btn favorite_listing' id='<%= listing_id %>' data-listing-id='<%= listing_id %>'></i></span>	
					<span class = 'hotlist_share pull-right'><a href='#' data-listing="<%=listing_id%>"><i class='fav-icon icon-user'></i></a></span>
					<span class = 'hotlist-share-grab grab pull-right'><i class='icon-reorder'></i><i class='icon-reorder'></i><i class="icon-reorder"></i></span>
				</div>
				<div class = 'info-row'>
					<span class = 'building-type'><%= building_type %></span>
					<span class = 'divider'>|</span>
					<% if (typeof(end_date) != "undefined") { %>
					<span class = 'lease-start'><%= start_date %></span> - <span class = 'lease_length'><%= end_date %></span>
					<% } else { %>
					<span class = 'lease-start'><%= start_date %></span> | <span class = 'lease_length'><%= lease_length %> mo.</span>
					<% } %>
				</div>
				<div class = 'info-row'>
					<i class = 'icon-map-marker <%= available %>'></i><span class = 'name'><%=name%></span>
				</div>
			</div>   
		</div>
		<div class='listing-actions'>
			<ul class='action-row'>
				<li class='hotlist-share'><a href='#'><i class="action-icon icon-heart favorite_listing" data-listing-id='<%=listing_id%>'></i></a></li>
				<li class='hotlist-share'><a href='/listing/<%=listing_id%>'><i class="action-icon icon-share"></i></a></li>
			</ul>
		</div>
	</div>
	"""
