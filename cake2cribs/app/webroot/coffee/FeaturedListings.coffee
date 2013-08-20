class A2Cribs.FeaturedListings

    # widget is a div where the featured listings for the supplied lat, lon
    # will be loaded into
    constructor: (@widget)->

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
            listingDefereds = []
            for id in listing_ids
                listingDefereds.push A2Cribs.FeaturedListings.GetListingDeferred(id, active_listing_type)

            $.when.apply($, listingDefereds).then ()=>
                deferred.resolve(arguments)



    @InitializeSidebar:(university_id, active_listing_type)->
        alt = active_listing_type
        if not @SidebarListingCache?
            @SidebarListingCache = {}
        
        getFLIds = @GetFlIds(university_id)
        getRanIds = @GetRandomListingIdsFromMap(5)
        console.log("Initing sidebar")
        
        $.when(getFLIds, getRanIds).then (fl_ids, ran_ids)=>
            get_fl_listings = @FetchListingsByIds(fl_ids, alt)
            get_ran_listings = @FetchListingsByIds(ran_ids, alt)

            $.when(get_fl_listings, get_ran_listings).then (fl_listings, ran_listings)=>
                sidebar = new Sidebar($('#fl-side-bar'), fl_listings, ran_listings)

            # @FetchListingsByIds listing_ids, alt, (listings)=>
            #     sidebar = new Sidebar($('#fl-side-bar'), listings)

        @GetRandomListingIdsFromMap 5, (listing_ids)=>
            console.log listing_ids

    @GetRandomListingIdsFromMap:(num_)->
        if not @RanIdDeferred?
            @RanIdDeferred = new $.Deferred()
        
        num = num_

        $.when(A2Cribs.Map.LoadBasicData()).then (data)=>
            basic_data = JSON.parse(data)
            ids = []
            for d in basic_data
                ids.push d.Listing.listing_id
            shuf_ids = _.shuffle(ids)

            @RanIdDeferred.resolve(shuf_ids.slice 0, shuf_ids.length % num)

        return @RanIdDeferred.promise()

            

    class Sidebar
        constructor:(@SidebarUI, fl_listings, ran_listings)->
            @ListItemTemplate = _.template(A2Cribs.FeaturedListings.ListItemHTML)
            fl_list = @getListHtml(fl_listings)
            ran_list = @getListHtml(ran_listings)

            @SidebarUI.find('#featured-listings').html fl_list
            @SidebarUI.find('#ran-listings').html ran_list


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
                end_date = new Date(listing.Rental.end_date)

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







