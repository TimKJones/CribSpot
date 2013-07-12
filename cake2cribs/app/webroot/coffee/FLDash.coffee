class A2Cribs.FLDash
    constructor:(@uiWidget)->
        @Listings = {}
        @OrderItems = {}
        @FL_Order = null
        @uiFL_Form = $('.featured-listing-order-item').first()
        @uiListingsList = @uiWidget.find('#listings_list')
        @uiOrderItemsList = @uiWidget.find('#orderItems_list')

        @initTemplates()
        @setupEventHandlers()
        @loadListings()

        # Hide the pricing details because they get that shit for free
        # @FL_Form.find(".total-tally").hide()


    setupEventHandlers:()->
        @uiWidget.find("#listings_list").on 'click', '.listing-item', (event)=>
            listing_id = $(event.currentTarget).data('id')
                 
            if not @OrderItems[listing_id]?
                #Need to add a new order item
                @addOrderItem(listing_id)

            @editOrderItem(listing_id)

        @uiOrderItemsList.on 'click', 'a', (event)=>
            target = $(event.currentTarget)
            id = target.data('id')
            if target.hasClass('edit')
                @editOrderItem(id)
            else if target.hasClass('remove')
                @removeOrderItem(id)

            
        @uiWidget.find("#buyNow").click ()=>
            @buy()

        @uiWidget.find(".feature-listing").click ()=> 
            @featureListing()

        @uiFL_Form.on 'orderItemChanged', (event, FL)=>
            listing_id = FL.listing_id
            @uiOrderItemsList.find(".orderItem[data-id=#{listing_id}] .price").html " $#{FL.getPrice().toFixed(2)}"


    
    loadListings:()->
       
        $.getJSON '/listings/GetListing', (listings)=>
            @Listings = {} #Remove all the old listings
            list = ""
            for listing in listings
                # Cache some data about the listings loaded
                data = {
                    listing_id: listing.Listing.listing_id
                    address: listing.Marker.street_address
                    altName: listing.Marker.alternate_name
                }
                @Listings[data.listing_id] = data
                list += @ListingTemplate(data)
            

            @uiListingsList.html list

    # Add an orderitem to the list of order items
    addOrderItem:(listing_id)->
        listing = @Listings[listing_id]
        
        data = {
            address: listing.address
            price: 0.00
            id: listing.listing_id
        }
        #Set a place holder the OrderItems map
        @OrderItems[listing_id] = {}

        @uiOrderItemsList.append @OrderItemTemplate(data)


    editOrderItem:(listing_id)->
        listing = @Listings[listing_id]
        # If there is order item being edited we need to save it first
        # before loading in the new one.
        if @FL_Order?
            @OrderItems[@FL_Order.listing_id] = @FL_Order.getOrderItem()
            @FL_Order.clear(false) #Tell it not to refresh after, this is a hack to prevent
                                   #The order changed event from firing with an empty calendar and
                                   #Making the price go down to 0
        
        # This sets up the featured listing form
        @FL_Order = new A2Cribs.Order.FeaturedListing(@uiFL_Form, listing.listing_id, listing.address, {selected_dates:@OrderItems[listing_id].item?.dates})

        console.log(@FL_Order)
        # Do any UI updates to css classes and shit



    removeOrderItem:(listing_id)->

        # Remove the corresponding orderitem from the list, if its currently being edited
        # clear and hide the form

        if @FL_Order?.listing_id == listing_id
            #Being edited
            @FL_Order.clear()

            #Hide it.

        @uiOrderItemsList.find(".orderItem[data-id=#{listing_id}]").remove()
        delete @OrderItems[listing_id]

        #Update any other UI info like total


    initTemplates:()->
        ListingHTML = """
                <div class = 'listing-item' data-id='<%= listing_id %>'>
                    <strong><%= address %></strong> <%= altName %>
                </div>
                    """
        @ListingTemplate = _.template(ListingHTML)

        OrderItemHTML = """
            <tr class = 'orderItem' data-id = '<%= id %>'>
                <td><span  class = 'address'><%= address %></span></td>
                <td><span class = 'price'?>$<%= price %></span></td>
                <td class = 'actions'>
                    <a href = '#' class = 'edit' data-id = '<%= id %>'><i class = 'icon-edit'></i></a>   
                    <a href = '#' class = 'remove' data-id = '<%= id %>'><i class = 'icon-remove-circle'></i></a>
                </td>
            </tr>
        """

        @OrderItemTemplate = _.template(OrderItemHTML)


    buy:()->
        if @FL_Order then @OrderItems[@FL_Order.listing_id] = @FL_Order.getOrderItem()
        order = []

        for own key, orderItem of @OrderItems
            order.push(orderItem)

        A2Cribs.Order.BuyItems(order)

    featureListing:()->

        if not @FL?
            return

        url = myBaseUrl + "order/suFeatureListing"
        post_data = {'orderItem': JSON.stringify(@FL.getOrderItem())}
        $.post url, post_data, (data)=>
            response = JSON.parse(data)
            if not response?
                alertify.error("Something went horribly wrong")
            else if response.success
                alertify.success(response.msg)
                @uiWidget.find(".fl_form").fadeOut("fast")
            else
                alertify.error(response.msg)

    





            




