class A2Cribs.FeaturedListings

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

    @GetRandomListingsFromMap:(num_)->
        if not @RanListingsDeferred?
            @RanListingsDeferred = new $.Deferred()
        
        num = num_

        $.when(A2Cribs.Map.LoadBasicData()).then (data)=>
            basic_data = JSON.parse(data)
            shuf = _.shuffle(basic_data)
            sliced = shuf.slice 0, num
            @RanListingsDeferred.resolve(sliced)

        return @RanListingsDeferred.promise()

            


    @InitializeSidebar:(university_id, active_listing_type)->
        alt = active_listing_type
        if not @SidebarListingCache?
            @SidebarListingCache = {}
        if not @FLListingIds?
            @FLListingIds = []

        NUM_RANDOM_LISTINGS = 35
        
        sidebar = new Sidebar($('#fl-side-bar'))
    
        @GetFlIds(university_id).done (ids)=>
            if ids is null then return
            for id in ids
                @FLListingIds.push parseInt id
            @FetchListingsByIds(ids, alt).done (listings)=>
                sidebar.addListings listings, 'featured'

        $.when(@GetRandomListingsFromMap(NUM_RANDOM_LISTINGS)).then (listings)=>
            if listings is null then return
            sidebar.addListings listings, 'ran'
            for listing in listings
                if listing.Listing?
                    A2Cribs.FavoritesManager.setFavoriteButton listing.Listing.listing_id.toString(), null, A2Cribs.FavoritesManager.FavoritesListingIds            
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

    

    class Sidebar
        constructor:(@SidebarUI)->
            @ListItemTemplate = _.template(A2Cribs.FeaturedListings.ListItemHTML)

        addListings:(listings, list, clear=true)->
            if listings is null then return
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

        
        getListHtml:(listings)->
            list = ""
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
                else
                    beds = "#{listing.Rental.beds} bed"

                if listing.Rental.start_date? 
                    start_date = @getDateString(new Date(listing.Rental.start_date))
                else
                    start_date = 'Start Date --'

                if start_date == 'Dec 1969'
                    alert('stop')

                #Process images
                primary_image_path = '/img/tooltip/no_photo.jpg'
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

                list += @ListItemTemplate(data)

            return list

        
                



    @ListItemHTML: """
    <div class = 'fl-sb-item' listing_id=<%= listing_id %> marker_id=<%= marker_id %>>
        <span class = 'img-wrapper'>
            <img src = '<%=img%>'></img>
        </span>
        <span class = 'vert-line'></span>
        <span class = 'info-wrapper'>
            <div class = 'info-row'>
                <span class = 'rent price-text'><%= "$" + rent %></span>
                <span class = 'divider'>|</span>
                <span class = 'beds'><%= beds %> </span>
                <span class = 'favorite pull-right'><i class = 'icon-heart fav-icon share_btn favorite_listing' id='<%= listing_id %>'></i></span>    
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







