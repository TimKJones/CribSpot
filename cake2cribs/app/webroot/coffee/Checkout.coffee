class A2Cribs.Checkout
    constructor:(@widget, @rules)->
        console.log rules
        @FeaturedListings = []
        $('.featured-listing-order-item').each (index, element)=>
            @FeaturedListings.push new FeaturedListingOrder($(element), @rules.FeaturedListings, @orderChanged)

        $('.featured-listing-order-item').on 'priceChange', ()=> @orderChanged()


        $(@widget).find('.buy').click ()=> @startWalletFlow()

    orderChanged:()->
        total = 0
        weekdays = 0
        weekends = 0
        for listing in @FeaturedListings
            weekdays += listing.weekdays
            weekends += listing.weekends
            total += listing.getPrice()


        $(@widget).find('.total').html " $#{total.toFixed(2)}"
        $(@widget).find('.weekdays').html weekdays
        $(@widget).find('.weekends').html weekends

        $(@widget).find('.total-tally').show()

    startWalletFlow:()->
        
        fl = @FeaturedListings[0]
        duration = A2Cribs.UtilityFunctions.getDaysBetweenDates(fl.start, fl.end)
        listing_id = fl.listing_id

        data = {
            'type': 'featured-listing'
            'info': JSON.stringify({
                        'start': fl.start.getTime()
                        'listing_id': listing_id
                        'duration': duration
                    })
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
        constructor:(@item, @rules, @OrderChangeCheckoutCallback)->
            @address = @item.find('.address').text()
            @listing_id = @item.attr('id')
            @start = null;
            @end = null;
            @days = null;
            @weekdays = null;
            @weekends = null;

            @initDatePickers()

        getPrice:()->
            # Calculate the cost of the featured listings.
            # Num Weekdays * price/weekday + num weekends * price/weekend
            return @weekdays * @rules.costs.weekday + @weekends * @rules.costs.weekend
        

        orderChanged:()->
            # console.log("Price is #{@getPrice()}")

            @days = A2Cribs.UtilityFunctions.getDaysBetweenDates(@start, @end)
            @weekdays = A2Cribs.UtilityFunctions.getWeekdaysBetweenDates(@start, @end)
            @weekends = @days - @weekdays;


            @item.find(".pricing").show()
            @item.find('.weekdays').first().html @weekdays
            @item.find('.weekends').first().html @weekends
            @item.find('.price').first().html " $#{@getPrice().toFixed(2)}";

            @item.trigger("priceChange")
                

        initDatePickers:()->
            
            nowTemp = new Date()
            now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0,0,0,0)
            @start = now;
            @end = now;
            
            start_picker = @item.find('.start').first().datepicker({
                    onRender: (date)=>
                        if  date.valueOf() < now.valueOf() then 'disabled' else ''
                    }
            )
            .on( 'changeDate', (event)=>
                if event.date.valueOf() > @end.valueOf()
                    newDate = new Date(event.date)
                    newDate.setDate(newDate.getDate()+1)
                    end_picker.setValue newDate
                    @end = newDate

                @start = event.date
                start_picker.hide()
                @orderChanged()  #Dates have changed => order has changed
                @item.find('.end').focus()
            )
            .data( 'datepicker' )


            end_picker = @item.find('.end').first().datepicker({
                onRender: (date)=>
                    if  date.valueOf() <= @start.valueOf() then 'disabled' else ''
            })
            .on 'changeDate', (event)=>
                @end = event.date
                @orderChanged()  #Dates have changed => order has changed
                end_picker.hide()
            .data 'datepicker'


       
            



