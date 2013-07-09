class A2Cribs.FLDash
    constructor:(@Widget)->
        @Widget.find(".listings_list").click (event)=>
            @loadForm($(event.target))

        @Widget.find(".feature-listing").click ()=> 
            @featureListing()

        @FL = null
        @CurrSelected = null
        @FL_Form = $('.featured-listing-order-item').first()
        
        # Hide the pricing details because they get that shit for free
        @FL_Form.find(".total-tally").hide()


    # Takes in a jquery object of the listing
    # that we want load the form for
    loadForm:(listing)->
        @CurrSelected = listing
        id = listing.data('id')
        addr = listing.data('addr')
        

        @FL?.clear()
        @FL = new A2Cribs.Order.FeaturedListing(@FL_Form, id, addr)
        
        @Widget.find(".fl_form").fadeIn("fast")

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
                @Widget.find(".fl_form").fadeOut("fast")
            else
                alertify.error(response.msg)
            




