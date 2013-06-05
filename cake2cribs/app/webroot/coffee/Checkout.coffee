class A2Cribs.Checkout
    constructor:(@widget, @rules)->
        console.log rules
        @FeaturedListings = []
        $('.featured-listing-order-item').each (index, element)=>
            @FeaturedListings.push new FeaturedListingOrder($(element), @rules.FeaturedListings, @orderChanged)

        $(@widget).on 'priceChange', '.date-range', ()=> 
            @priceChanged()


        $(@widget).find('.buy').click ()=> @startWalletFlow()

    priceChanged:()->
        total = 0
        weekdays = 0
        weekends = 0

        for listing in @FeaturedListings
            details = listing.getOrderDetails()
            total += details.price
            weekdays += details.weekdays
            weekends += details.weekends


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
        constructor:(@item, @rules)->
            @address = @item.find('.address').text()
            @listing_id = @item.attr('id')
            @start = null
            @end = null
            @days = null
            @weekdays = null
            @weekends = null

            @Ranges = []
            @newRange()
            @item.find('.add-dates').click ()=>
                @newRange()

        getOrderDetails:()-> 
            price = 0
            weekdays = 0
            weekends = 0 
            for range in @Ranges
                price += range.getPrice()
                weekdays += range.weekdays
                weekends += range.weekends

            details = {
                price:price
                weekdays: weekdays
                weekends: weekends
            }
                

            return details

        newRange:()->
                widget = $(
                    """
                    <div class = 'date-range row-fluid'>
                        <div class = 'span10'>
                            Start: <input type = 'text' class = 'date-input start'></input>
                            End: <input type = 'text' class = 'date-input end'></input> 
                            <a href = '#' class ='remove-range'><i class = 'icon-trash icon-large'></i></a>
                        </div>
                        <div class = 'span2'>
                            <span class ='pull-right price'></>
                        </div>
                    </div>
                    """
                    ).appendTo(@item)

                # @item.append(widget)

                @Ranges.push new DateRange(widget, @rules)


        
        class DateRange
            constructor: (@widget, @rules)->
                @days = 0
                @weekends = 0
                @weekdays = 0
                @initDatePickers()

                @widget.find('.remove-range').click ()=>
                    @widget.trigger('removeRange', @)

            initDatePickers:()->
                nowTemp = new Date()
                now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0,0,0,0)
                @start = now;
                @end = now;
                
                start_picker = @widget.find('.start').first().datepicker({
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
                    @widget.find('.end').focus()
                )
                .data( 'datepicker' )


                end_picker = @widget.find('.end').first().datepicker({
                    onRender: (date)=>
                        if  date.valueOf() <= @start.valueOf() then 'disabled' else ''
                })
                .on 'changeDate', (event)=>
                    @end = event.date
                    @orderChanged()  #Dates have changed => order has changed
                    end_picker.hide()
                .data 'datepicker'

            orderChanged:()->
                # console.log("Price is #{@getPrice()}")

                @days = A2Cribs.UtilityFunctions.getDaysBetweenDates(@start, @end)
                @weekdays = A2Cribs.UtilityFunctions.getWeekdaysBetweenDates(@start, @end)
                @weekends = @days - @weekdays
                
                @setPrice(@getPrice())
                
                @widget.trigger("priceChange")

            
            setPrice:(price)->
                @widget.find('.price').html "$#{price.toFixed(2)}"

            
            getPrice:()->
                # Calculate the cost of the featured listings.
                # Num Weekdays * price/weekday + num weekends * price/weekend
                if @weekdays? and @weekends?
                    return @weekdays * @rules.costs.weekday + @weekends * @rules.costs.weekend
                else
                    return 0


                    


           
                



