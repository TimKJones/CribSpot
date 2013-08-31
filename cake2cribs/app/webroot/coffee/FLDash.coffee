class A2Cribs.FLDash

    constructor:(@uiWidget)->
        
        
        #Map of listing id's to order stats
        #an order states contains a list of selected dates
        #and enableness of the universities associated with the listing
        @OrderStates = {}

        @ListingUniPricing = {}
        # @UnavailableDates = null
        

        @FL_Order = null
        @uiFL_Form = $('.featured-listing-order-item').first()
        

        @uiListingsList = @uiWidget.find('#listings_list')
        @uiOrderItemsList = @uiWidget.find('#orderItems_list')
        @uiErrorsList = @uiWidget.find("#validation-error-list")

        @initTemplates()
        @setupEventHandlers()
        $.when A2Cribs.Dashboard.GetListings() .then ()=>
            @loadListings()

        # Hide the pricing details because they get that shit for free
        # @FL_Form.find(".total-tally").hide()

    setupEventHandlers:()->
        

        #Setup the listings list so when the user hovers over the star
        #it changes colors and make is so when the user clicks the star
        #it adds its to the order items.
        @uiListingsList
        .on 'mouseenter', '.listing-item', (event)=>
            $(event.currentTarget).find('.feature-star').removeClass 'icon-star-empty'
            $(event.currentTarget).find('.feature-star').addClass 'icon-star'
        
        .on 'mouseleave', '.listing-item', (event)=>    
            $(event.currentTarget).find('.feature-star').removeClass 'icon-star' 
            $(event.currentTarget).find('.feature-star').addClass 'icon-star-empty'

        .on 'click', '.listing-item', (event)=>

            listing_id = $(event.currentTarget).data('id')
                 
            if not @OrderStates[listing_id]?
                #Need to add a new order item
                @addOrderItem(listing_id)

            @editOrderItem(listing_id)

        .on 'click', '.marker-info', (event)=>
            marker_info = $(event.currentTarget)
            marker_info.siblings('ul').slideToggle('fast')
            marker_info.find('i').toggleClass("icon-plus").toggleClass('icon-minus')


        @uiOrderItemsList.on 'click', 'a', (event)=>
            target = $(event.currentTarget)
            id = target.data('id')
            if target.hasClass('edit')
                @editOrderItem(id)
            else if target.hasClass('remove')
                @removeOrderItem(id)

        @uiErrorsList.on 'click', '.icon-remove', (event)=>
            listing_id = $(event.currentTarget).parent().data('id')
            @removeErrors(listing_id)

            
        @uiWidget.find("#buyNow").click ()=>
            @buy()


        @uiWidget.find(".feature-listing").click ()=> 
            @featureListing()

        @uiFL_Form.on 'orderItemChanged', (event, FL)=>
            
            listing_id = FL.listing_id
            @uiOrderItemsList.find(".orderItem[data-id=#{listing_id}] .price").html "#{FL.getPrice().toFixed(2)}"
            total = 0
            @uiOrderItemsList.find(".price").each (index, element)=>
                total += Number($(element).html())
            @uiOrderItemsList.siblings('tfoot').find('.total').html "#{total.toFixed(2)}"

        $('#fl-search-icon').click ()=>
            $("#listings_list div").show().filter () ->
                if $(this).text().toLowerCase().indexOf($("#fl-list-input").val().toLowerCase()) is -1
                    return true
                return false
            .hide()
    
    loadListings:()->
        
        list = ""
        # We want to keep track which listings belond with which markers 
        # so we can easily group the listings with the same address under one title
        marker_data = {}
        for listing in A2Cribs.UserCache.Get('listing')
            if not marker_data[listing.marker_id]?
                marker_data[listing.marker_id] = []

            marker_data[listing.marker_id].push(listing.listing_id)



        # We now need to go through each marker and construct the results
        # list. Which will be a marker item with all the listing items inside it
        for own marker_id, listing_ids of marker_data
            marker = A2Cribs.UserCache.Get 'marker', marker_id

            listing_list = ""
            address = marker.street_address
            alt_name = marker_data.alt_name
            
            for listing_id in listing_ids
                 
                listing = A2Cribs.UserCache.Get('listing', listing_id)
                icon = ''
                switch parseInt(listing.listing_type)
                    when 0 then icon = 'icon-home' #rental
                    when 1 then icon = 'icon-lemon' #WTF should sublet icon be??
                    when 2 then icon = 'icon-truck' #Font awesome doesn't have a car icon

                rental = A2Cribs.UserCache.GetAllAssociatedObjects 'rental', 'listing', listing.listing_id
                unit_style_options = ""
                unit_style_description = ""
                if rental? and rental[0] != undefined
                    formattedRental = rental[0]
                description = 'Listing ' + listing_id
                if formattedRental? and formattedRental.unit_style_options != undefined and formattedRental.unit_style_description != undefined
                    if parseInt(formattedRental.unit_style_options) == 0 then unit_style_options = "Unit"
                    if parseInt(formattedRental.unit_style_options) == 1 then unit_style_options = "Layout"
                    if parseInt(formattedRental.unit_style_options) == 2 then unit_style_options = "Entire House"
                    description += unit_style_options
                    if unit_style_options != "Entire House"
                        description += " - " + formattedRental.unit_style_description

                data = {
                    icon:icon
                    address: address
                    description: description
                    listing_id: listing_id 
                }

                list_item = @ListingTemplate(data)
                listing_list += list_item
                
            
            data = {
                marker: marker
                num_listings: listing_ids.length
                listing_list: listing_list
            }
            marker_item = @MarkerTemplate(data)
            list += marker_item
            $("#listings_list_content").append marker_item
        
        @uiListingsList.html list          

   

    # getUnavailableDates:()->
    #     if not @UnavailableDates?
    #         d = new $.Deferred()
    #         $.getJSON '/featuredListings/getUnavailableDates', (data)=>
    #             d.resolve(data)    
    #         @UnavailableDates = d.promise()

    #     return @UnavailableDates
        
    
    getUniData:(listing_id = null)->

        if not @ListingUniPricing[listing_id]?
            d = new $.Deferred()
            url = "/featuredListings/getUniDataForListing/#{listing_id}"
            $.ajax
                url: url
                type: 'GET'
                success: (data) =>
                    d.resolve(JSON.parse data)
                

            @ListingUniPricing[listing_id] = d.promise()


        return @ListingUniPricing[listing_id]

     # Add an orderitem to the list of order items
    addOrderItem:(listing_id)->
        listing = A2Cribs.UserCache.Get 'listing', listing_id
        marker = A2Cribs.UserCache.Get 'marker', listing.marker_id
        
        data = {
            address: marker.street_address
            price: 0.00
            id: listing.listing_id
        }
        #Set a place holder the OrderItems map
        @OrderStates[listing_id] = {}

        @uiOrderItemsList.append @OrderItemTemplate(data)

    editOrderItem:(listing_id)->
        listing = A2Cribs.UserCache.Get 'listing', listing_id
        # If there is order item being edited we need to save it first
        # before loading in the new one.
        if @FL_Order?
            old_id = @FL_Order.listing_id
            @uiOrderItemsList.find(".orderItem[data-id=#{old_id}]").removeClass('editing')
            @OrderStates[old_id] = @FL_Order.getState()
            
            @FL_Order.reset(false) #Tell it not to refresh after, this is a hack to prevent
                                   #The order changed event from firing with an empty calendar and
                                   #Making the price go down to 0
        
        # This sets up the featured listing form
        
        initialState = if @OrderStates[listing_id]? then @OrderStates[listing_id] else null
        address = A2Cribs.UserCache.Get('marker', listing.marker_id).street_address

        # We deffered the fetching of unavailable dates so when its ready we can 
        # continue and make the featured listing order form
        id = listing_id
        $.when(@getUniData(listing_id)).then (uniData)=>
            # unavailDates = unavailableDates.full_dates.concat unavailableDates.listing_dates[id]
            # options['disabled_dates'] = unavailDates
            @FL_Order = new A2Cribs.Order.FeaturedListing(@uiFL_Form, listing.listing_id, address, uniData, initialState)
        
        @uiOrderItemsList.find(".orderItem[data-id=#{listing_id}]").addClass('editing')

        @toggleOrderDetailsUI(true) #Show the order detail info
        # Do any UI updates to css classes and shit



    removeOrderItem:(listing_id=null)->

        if listing_id == null
            @uiOrderItemsList.find(".orderItem").remove()
            @OrderStates = {}
            @FL_Order.reset()
            @FL_Order = null

        else
            # Remove the corresponding orderitem from the list and any validation
            # errors that may be associated with it.

            @uiOrderItemsList.find(".orderItem[data-id=#{listing_id}]").remove()
            @removeErrors(listing_id)

            delete @OrderStates[listing_id]
            if(parseInt(@FL_Order?.listing_id,10) == listing_id)
                @FL_Order.reset()
                @FL_Order = null


        # If there are no more  orderitems left hide the right content
        if @uiOrderItemsList.find(".orderItem").length == 0
            @toggleOrderDetailsUI(false) #Hide order details stuff
        else
            #There are still orderItems left so switch to editing one of those
            different_id = @uiOrderItemsList.find(".orderItem").first().data('id')
            @editOrderItem(different_id)

        #Update any other UI info like total

        


    initTemplates:()->
        ListingHTML = """
                <li class = 'listing-item' data-id='<%= listing_id %>'>
                    <i class = 'icon-large <%= icon %> listing-icon'></i><strong><%= description %></strong>
                    <i class = 'pull-right feature-star icon-star-empty'></i>
                </li>
                    """
        @ListingTemplate = _.template(ListingHTML)

        MarkerHTML = """
                <div class = 'marker-item' data-id='<%= marker.marker_id %>'>
                    <div class = 'marker-info'><i class = 'icon-plus'></i><strong><%= marker.street_address %></strong>  <%= marker.alternate_name %> (<%=num_listings%>)</div>
                    <ul><%= listing_list %></ul>
                </div>
                     """

        @MarkerTemplate = _.template(MarkerHTML)


        OrderItemHTML = """
            <tr class = 'orderItem' data-id = '<%= id %>'>
                <td><span  class = 'address'><%= address %></span></td>
                <td>$<span class = 'price'?><%= price %></span></td>
                <td class = 'actions'>
                    <a href = '#' class = 'edit' data-id = '<%= id %>'><i class = 'icon-edit'></i></a>   
                    <a href = '#' class = 'remove' data-id = '<%= id %>'><i class = 'icon-remove-circle'></i></a>
                </td>
            </tr>

        """

        @OrderItemTemplate = _.template(OrderItemHTML)



    showErrors:(errors)->
        
        html = ""
        for own listing_id, error_msgs of errors
            oi = @uiOrderItemsList.find(".orderItem[data-id=#{listing_id}]")
            oi.addClass('error')
            addr = oi.find('.address').html()
            html += "<dt data-id='#{listing_id}'>Validation Errors for #{addr}<i class = 'icon-remove'></i></dt>"

            for msg, index in error_msgs
                html+="<dd data-id='#{listing_id}' class = 'validation-error'>#{index+1}. #{msg}</dd>"
            

        @uiErrorsList.html html

    # if listing_id is null then all the errors will be removed
    removeErrors:(listing_id=null)->
        if listing_id?
            @uiOrderItemsList.find(".orderItem[data-id=#{listing_id}]").removeClass("error")
            @uiErrorsList.children("[data-id=#{listing_id}]").remove()

        else
            @uiOrderItemsList.find(".orderItem").removeClass("error")
            @uiErrorsList.html ""

    buy:()->
        
        @removeErrors()

        if @FL_Order then @OrderStates[@FL_Order.listing_id] = @FL_Order.getState()
        
        uniDataDefereds = []
        for own listing_id of @OrderStates
            uniDataDefereds.push(@getUniData(listing_id))

        $.when.apply($, uniDataDefereds).then ()=>
            # console.log(arguments)
            order = []
            orderData = _.zip(arguments, _.values(@OrderStates))
            # OrderData is an array that contains a two item array for
            # each listing pending to be ordered. First elemtn is the uniData
            # and the second it the orderstate

            for od in orderData
                uniData = od[0]
                orderState = od[1]
                if orderState.selectedDates.length < 1
                    continue
                
                # Need to generate an orderItem per uni
                for uni in uniData
                    if not uni.enabled
                        continue
                    oi = A2Cribs.Order.FeaturedListing.GenerateOrderItem(orderState, uni)
                    order.push oi

            A2Cribs.Order.BuyItems order, 0, (errors)=>
                @showErrors(errors)

            ,()=>
                #Success
                @removeOrderItem()

    
    toggleOrderDetailsUI:(show)->
        if show
            $("#noListingSelected").fadeOut('fast')
            @uiWidget.find(".orderingInfo").slideDown()
        else
            @uiWidget.find(".orderingInfo").slideUp()
            $("#noListingSelected").fadeIn('fast')
                




            




