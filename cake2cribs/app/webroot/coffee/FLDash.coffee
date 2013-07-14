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
        

        #Setup the listings list so when the user hovers over the star
        #it changes colors and make is so when the user clicks the star
        #it adds its to the order items.

        @uiListingsList.on 'mouseenter', '.feature-star', (event)=>
            $(event.currentTarget).removeClass 'icon-star-empty'
            $(event.currentTarget).addClass 'icon-star'
        
        .on 'mouseleave', '.feature-star', (event)=>    
            $(event.currentTarget).removeClass 'icon-star' 
            $(event.currentTarget).addClass 'icon-star-empty'

        .on 'click', '.feature-star', (event)=>

            listing_id = $(event.currentTarget).parent('.listing-item').data('id')
                 
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
            @uiOrderItemsList.find(".orderItem[data-id=#{listing_id}] .price").html "#{FL.getPrice().toFixed(2)}"
            total = 0
            @uiOrderItemsList.find(".price").each (index, element)=>
                total += Number($(element).html())
            @uiOrderItemsList.siblings('tfoot').find('.total').html "#{total.toFixed(2)}"


    
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
            old_id = @FL_Order.listing_id
            @uiOrderItemsList.find(".orderItem[data-id=#{old_id}]").removeClass('editing')
            @OrderItems[old_id] = @FL_Order.getOrderItem()
            @FL_Order.clear(false) #Tell it not to refresh after, this is a hack to prevent
                                   #The order changed event from firing with an empty calendar and
                                   #Making the price go down to 0
        
        # This sets up the featured listing form
        options = null
        if @OrderItems[listing_id].item?.dates.length > 0
            options = {selected_dates:@OrderItems[listing_id].item.dates}

        @FL_Order = new A2Cribs.Order.FeaturedListing(@uiFL_Form, listing.listing_id, listing.address, options)
        
        @uiOrderItemsList.find(".orderItem[data-id=#{listing_id}]").addClass('editing')

        @uiWidget.find(".right-content").show()
        # Do any UI updates to css classes and shit



    removeOrderItem:(listing_id)->

        # Remove the corresponding orderitem from the list, if its currently being edited
        # clear and hide the form

        @uiOrderItemsList.find(".orderItem[data-id=#{listing_id}]").remove()
        delete @OrderItems[listing_id]
        if(parseInt(@FL_Order?.listing_id,10) == listing_id)
            @FL_Order = null


        # If there are no more  orderitems left hide the right content
        if @uiOrderItemsList.find(".orderItem").length == 0
            @uiWidget.find(".right-content").hide()
        else
            #There are still orderItems left so switch to editing one of those
            different_id = @uiOrderItemsList.find(".orderItem").first().data('id')
            @editOrderItem(different_id)

        #Update any other UI info like total

        $(".validation-error-list").children("[data-id=#{listing_id}]").remove()


    initTemplates:()->
        ListingHTML = """
                <div class = 'listing-item' data-id='<%= listing_id %>'>
                    <strong><%= address %></strong> <%= altName %>
                    <i class = 'pull-right feature-star icon-star-empty'></i>
                </div>
                    """
        @ListingTemplate = _.template(ListingHTML)

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

        ValidationErrorHTML = """
            <dd class = 'validation-error'><%= msg %></dd>
        """

        @ValidationErrorTemplate = _.template(ValidationErrorHTML)


    showErrors:(errors)->
        
        html = ""
        for own listing_id, error_msgs of errors
            oi = @uiOrderItemsList.find(".orderItem[data-id=#{listing_id}]")
            oi.addClass('error')
            addr = oi.find('.address').html()
            html += "<dt data-id='#{listing_id}''>Validation Errors for #{addr}</dt>"

            for msg, index in error_msgs
                html+="<dd data-id='#{listing_id}' class = 'validation-error'>#{index+1}. #{msg}</dd>"
            

        $('.validation-error-list').html html




    buy:()->
        # Clear the validation information
        @uiOrderItemsList.find(".orderItem").removeClass("error")
        $('.validation-error-list').html ""


        if @FL_Order then @OrderItems[@FL_Order.listing_id] = @FL_Order.getOrderItem()
        order = []

        for own key, orderItem of @OrderItems
            order.push(orderItem)

        A2Cribs.Order.BuyItems order, (errors)=>
            @showErrors(errors)


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

    





            




