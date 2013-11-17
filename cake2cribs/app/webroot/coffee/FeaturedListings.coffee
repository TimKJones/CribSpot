class A2Cribs.FeaturedListings
    @FeaturedPMIdToListingIdsMap = []
    @FeaturedPMListingsVisible = false

    @resizeHandler: ->
        h = $(window).height() - $('#listings-list').offset().top - $('.legal-bar').height()
        console.log $(window).height(), $('#listings-list').offset().top, $('.legal-bar').height(), h
        $('#listings-list').height(h)

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


    @InitializeSidebar:(university_id, active_listing_type, basicDataDeferred, basicDataCachedDeferred)->
        alt = active_listing_type
        if not @SidebarListingCache?
            @SidebarListingCache = {}
        if not @FLListingIds?
            @FLListingIds = []

        NUM_RANDOM_LISTINGS = 25
        
        sidebar = new Sidebar($('#fl-side-bar'))
    
        # get listing_ids for featured listings for today
        getFlIdsDeferred = @GetFlIds(university_id)

        # resolved after image paths have been loaded
        @GetSidebarImagePathsDeferred = new $.Deferred()

        @SetupResizing()

        # We have the featured listing listing ids for the sidebar
        # Now get random listing ids from the basic data (already loaded) to fill out the sidebar
        $.when(getFlIdsDeferred, basicDataCachedDeferred).then (flIds) =>
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
                return

            # combine featured listings and random listings to get list of all sidebar listing ids
            sidebar_listing_ids = []
            for id in flIds
                id = parseInt id
                @FLListingIds.push id
                sidebar_listing_ids.push id
            if randomIds?
                for id in randomIds
                    sidebar_listing_ids.push id

            listings = []

            #fetch listing data for these listing_ids from the cache
            for id in sidebar_listing_ids
                listingObject = {}
                listing = A2Cribs.UserCache.Get('listing', id)
                marker = rental = null
                if listing?
                    listing.InSidebar yes
                    marker = A2Cribs.UserCache.Get('marker', listing.marker_id)
                    rental = A2Cribs.UserCache.GetAllAssociatedObjects('rental', 'listing', id)
                    if rental[0]?
                        rental = rental[0]
                if listing? and marker? and rental?
                    listingObject.Listing = listing
                    listingObject.Marker = marker
                    listingObject.Rental = rental
                    listings.push listingObject
                else
                    console.log listing
                    console.log marker
                    console.log rental

            sidebar.addListings listings, 'ran'

            # Fetch primary image paths for all listings in sidebar
            @GetSidebarImagePaths(sidebar_listing_ids)

            # Set favorite add/delete event handlers for sidebar
            #for listing in listings
            #    if listing.Listing?
            #        A2Cribs.FavoritesManager.setFavoriteButton listing.Listing.listing_id.toString(), null, A2Cribs.FavoritesManager.FavoritesListingIds
            $(".fl-sb-item").click (event) =>
                marker_id = parseInt($(event.currentTarget).attr('marker_id'))
                listing_id = parseInt($(event.currentTarget).attr('listing_id'))
                marker = A2Cribs.UserCache.Get('marker', marker_id)
                listing = A2Cribs.UserCache.Get('listing', listing_id)  
                A2Cribs.Map.GMap.setZoom 16
                A2Cribs.HoverBubble.Open marker
                A2Cribs.MixPanel.Click listing, 'sidebar listing'
                markerPosition = marker.GMarker.getPosition()
                A2Cribs.Map.CenterMap markerPosition.lat(), markerPosition.lng()
            
        $.when(@GetSidebarImagePathsDeferred).then (images) =>
            images = JSON.parse images
            for image in images
                if image? and image.Image?
                    img_element = $("#sb-img" + image.Image.listing_id)
                    img_element.attr('src', '/' + image.Image.image_path)

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
                            A2Cribs.MixPanel.Event 'Sidebar Featured PM', 
                                pm_id: user_id

            error: ()=>
                @FeaturedPMIdToListingIdsMap = []

    class Sidebar
        constructor:(@SidebarUI)->
            @ListItemTemplate = _.template(A2Cribs.FeaturedListings.ListItemHTML)

        addListings:(listings, list, clear=true)->
            if listings is null then return
            list_html = @getListHtml(listings)
            if clear
                @SidebarUI.find("##{list}-listings").append list_html
            else
                @SidebarUI.find("##{list}-listings").append list_html


        getDateString:(date)->
            
            if not @MonthArray?
                @MonthArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']


            month = @MonthArray[date.getMonth()]
            year = date.getFullYear()
            return "#{month} #{year}"

        
        getListHtml:(listings)->
            list = $("<div />")
            for listing in listings
                rent = name = beds = lease_length = start_date = null

                if listing.Rental.rent? 
                    rent = parseFloat(listing.Rental.rent).toFixed(0)
                else
                    rent = ' --'

                if listing.Marker.alternate_name? 
                    name = listing.Marker.alternate_name
                else
                    name = listing.Marker.street_address

                if listing.Rental.lease_length? 
                    lease_length = listing.Rental.lease_length
                else
                    lease_length = '-- '
                
                if listing.Rental.beds > 1
                    beds = "#{listing.Rental.beds} beds"
                else if listing.Rental.beds?
                    beds = "#{listing.Rental.beds} bed"
                else
                    beds = "?? beds"

                if listing.Rental.start_date?
                    # Fix date bug in firefox
                    start_date = listing.Rental.start_date.toString().replace(' ', 'T')
                    start_date = @getDateString(new Date(start_date))
                else
                    start_date = 'Start Date --'

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
                    lease_length: lease_length
                    name: name
                    img: primary_image_path
                    listing_id: listing.Listing.listing_id
                    marker_id: listing.Marker.marker_id
                }

                listing_item = $(@ListItemTemplate data)
                A2Cribs.FavoritesManager.setFavoriteButton(listing_item.find(".favorite"), listing.Listing.listing_id, A2Cribs.FavoritesManager.FavoritesListingIds)

                list.append listing_item

            return list

        
    @ListItemHTML: """
    <div id = 'fl-sb-item-<%= listing_id %>' class = 'fl-sb-item' listing_id=<%= listing_id %> marker_id=<%= marker_id %>>
        <span class = 'img-wrapper'>
            <img id='sb-img<%=listing_id %>' src = '<%=img%>'></img>
        </span>
        <span class = 'vert-line'></span>
        <span class = 'info-wrapper'>
            <div class = 'info-row'>
                <span class = 'rent price-text'><%= "$" + rent %></span>
                <span class = 'divider'>|</span>
                <span class = 'beds'><%= beds %> </span>
                <span class = 'favorite pull-right'><i class = 'icon-heart fav-icon share_btn favorite_listing' id='<%= listing_id %>' data-listing-id='<%= listing_id %>'></i></span>    
            </div>
            <div class = 'row-div'></div>
            <div class = 'info-row'>
                <span class = 'building-type'><%= building_type %></span>
                <span class = 'divider'>|</span>
                <span class = 'lease-start'><%= start_date %></span> | <span class = 'lease_length'><%= lease_length %> months</span>
            </div>
            <div class = 'row-div'></div>
            <div class = 'info-row'>
                <i class = 'icon-map-marker'></i><span class = 'name'><%=name%></span>
            </div>
        </span>   
    </div>
    """







