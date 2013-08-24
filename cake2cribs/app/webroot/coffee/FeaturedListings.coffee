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

        NUM_RANDOM_LISTINGS = 35
        
        getFLIds = @GetFlIds(university_id)
        
        sidebar = new Sidebar($('#fl-side-bar'))
    
        @GetFlIds(university_id).done (ids)=>
            if ids is null then return
            @FetchListingsByIds(ids, alt).done (listings)=>
                sidebar.addListings listings, 'featured'

        $.when(@GetRandomListingsFromMap(NUM_RANDOM_LISTINGS)).then (listings)=>
            if listings is null then return
            sidebar.addListings listings, 'ran'             
    

    

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
                start_date = new Date(listing.Rental.start_date)
                end_date = new Date(new Date(start_date).setMonth(start_date.getMonth()+listing.Rental.lease_length))

                if listing.Marker.alternate_name? 
                    name = listing.Marker.alternate_name
                else
                    name = listing.Marker.street_address
                
                if listing.Rental.beds > 1
                    beds = "#{listing.Rental.beds} beds"
                else
                    beds = "#{listing.Rental.beds} bed"

                data = {
                    rent: parseFloat(listing.Rental.rent).toFixed(2)
                    beds: beds
                    building_type: listing.Marker.building_type_id
                    start_date: @getDateString(start_date)
                    end_date: @getDateString(end_date)
                    name: name
                    img: "http://lorempixel.com/96/64/city/"

                }

                list += @ListItemTemplate(data)

            return list

        
                



    @ListItemHTML: """
    <div class = 'fl-sb-item'>
        <span class = 'img-wrapper'>
            <img src = '<%=img%>'></img>
        </span>
        <span class = 'vert-line'></span>
        <span class = 'info-wrapper'>
            <div class = 'info-row'>
                <span class = 'rent price-text'><%= "$" + rent %></span>
                <span class = 'divider'>|</span>
                <span class = 'beds'><%= beds %> </span>
                <span class = 'favorite pull-right'><i class = 'icon-heart fav-icon'></i></span>    
            </div>
            <div class = 'row-div'></div>
            <div class = 'info-row'>
                <span class = 'building-type'><%= building_type %></span>
                <span class = 'divider'>|</span>
                <span class = 'lease-start'><%= start_date %></span> - <span class = 'lease-end'><%= end_date %></span>
            </div>
            <div class = 'row-div'></div>
            <div class = 'info-row'>
                <i class = 'icon-map-marker'></i><span class = 'name'><%=name%></span>
            </div>
        </span>   
    </div>
    """







