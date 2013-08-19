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

    @GetListing:(id, type)->
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



    @InitializeSidebar:(university_id, active_listing_type)->
        
        if not @SidebarListingCache?
            @SidebarListingCache = {}
        
        getFLIds = @GetFlIds(university_id)
        console.log("Initing sidebar")
        
        $.when(getFLIds).then (listing_ids)=>
            console.log(listing_ids)
            sidebar = new Sidebar($('#fl-side-bar'), listing_ids, active_listing_type)



    class Sidebar
        constructor:(@SidebarUI, @FL_Listing_Ids, @ActiveListingType)->
            @ListItemTemplate = _.template(A2Cribs.FeaturedListings.ListItemHTML)
            # list = ""
            @fetchListings(@FL_Listing_Ids)

            # for listing in fl_listings
            #     list += ListItemTemplate(listing)

            # @SidebarUI.find('featured_listings').html list

        getDateString:(date)->
            
            if not @MonthArray?
                @MonthArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']


            month = @MonthArray[date.getMonth()]
            year = date.getFullYear()
            return "#{month} #{year}"

        fetchListings:(listing_ids)->
            deferred = new $.Deferred()
            
            listingDefereds = []
            for id in @FL_Listing_Ids
                listingDefereds.push A2Cribs.FeaturedListings.GetListing(id, @ActiveListingType)

            $.when.apply($, listingDefereds).then ()=>
                list = ""
                for listing in arguments
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

                @SidebarUI.find('#featured-listings').html list




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







