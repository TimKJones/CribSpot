class A2Cribs.Checkout
    constructor:(@widget, @rules)->
        
        @FeaturedListings = []
        
        # Create a FeaturedListringOrder instance for each one found on the dom

        $('.featured-listing-order-item').each (index, element)=>
            @FeaturedListings.push new FeaturedListingOrder($(element), @rules.FeaturedListings, @orderChanged)


        $(@widget).on 'dateChange', '.featured-listing-order-item', (event, floi)=> 
            @calculateTotal()

        $(@widget).find('.buy').click ()=> @startWalletFlow()

    
    calculateTotal:()->
        total = 0
        weekdays = 0
        weekends = 0

        for listing in @FeaturedListings
            for d in listing.getDates()
                day = d.getDay()
                if(day is 0 or day is 6)
                    # Sunday or Saturday
                    weekends++
                    total += @rules.FeaturedListings.costs.weekend
                else
                    weekdays++
                    total += @rules.FeaturedListings.costs.weekday

        $(@widget).find('.total').html " $#{total.toFixed(2)}"
        $(@widget).find('.weekdays').html weekdays
        $(@widget).find('.weekends').html weekends

        $(@widget).find('.total-tally').show()
        

    getOrderRequest:()->
        
        request = []

        for listing in @FeaturedListings
            request.push listing.getOrder()

        console.log(request)
        return request



    startWalletFlow:()->
        
        # fl = @FeaturedListings[0]
        # duration = A2Cribs.UtilityFunctions.getDaysBetweenDates(fl.start, fl.end)
        # listing_id = fl.listing_id

        data = {
            'type': 'featured-listing'
            'order': JSON.stringify @getOrderRequest()
        }

        url = '/order/getJwt'
        $.post url, data, (response_raw)=>
            response = JSON.parse(response_raw)
            if !response.success
                console.log response.message
            google.payments.inapp.buy({
                parameters:{},
                jwt: response.jwt,
                success: ()->alert("success")
                failture: ()->alert("fail")
            })

    class FeaturedListingOrder
        constructor:(@item, @rules)->
            @address = @item.find('.address').text()
            @listing_id = @item.attr('id')

            @initMultiDatesPicker()

        initMultiDatesPicker:()->
            @datepicker = $(@item).find('.mdp').multiDatesPicker({
                minDate: new Date(),
                onSelect: (dateText, inst)=>
                    @dateSelected(dateText, inst)
            });

        dateSelected:(dateText, picker_inst)->
            @item.trigger('dateChange', @)

        getDates:(type = 'object')->
            @datepicker.multiDatesPicker 'getDates', type

        getOrder:()->
            date = {
                address: @address,
                listing_id: @listing_id,
                dates: @getDates('string'),
            }

                    


           
                



