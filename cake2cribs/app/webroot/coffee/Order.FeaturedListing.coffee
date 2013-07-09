class A2Cribs.Order.FeaturedListing
        constructor:(@Widget, @listing_id, @address, dates=null)->
            
            @Weekdays = 0
            @Weekends = 0
            @Price = 0

            @WD_price = 15
            @WE_price = 5
            @MIN_DAY_OFFSET = 3

            @initMultiDatesPicker()
            @shiftKey = false;
            $(window).keydown (event)=>
                if event.shiftKey or event.keyCode is 16
                    @shiftKey = true

            $(window).keyup (event)=>
                if event.shiftKey or event.keyCode is 16
                    @shiftKey = false


            @Widget.find('.address').html @address
            if dates?
                @datepicker.multiDatesPicker('addDates', dates)
                @refresh()


        getPrice:()->
            return @Price

        getOrderItem:()->
            return {
                type: 'FeaturedListing'
                price: @getPrice()
                item: {
                    address: @address
                    listing_id: @listing_id
                    dates: @getDates('string')
                }
            }

        # Removes all the selected dates

        clear:()->
            @datepicker.multiDatesPicker 'resetDates'
            @refresh()


        getDates:(type = 'object')->
            @datepicker.multiDatesPicker 'getDates', type

        updatePrice:()->
            @Price = @Weekdays * @WD_price + @Weekends * @WE_price

        # Returns a two element array first element being weekdays and second being weekends
        # in coffee script you can do [weekdays, weekends] = @getDayCounts() and it'll automatically
        # break the array up
        updateDayCounts:()->
            
            @Weekends = 0
            @Weekdays = 0

            for d in @.getDates()
                day = d.getDay()
                if(day is 0 or day is 6)
                    # Sunday or Saturday
                    @Weekends++
                else
                    @Weekdays++

            return [@Weekdays, @Weekends]


        initMultiDatesPicker:()->
            today = new Date()
            
            @datepicker = $(@Widget).find('.mdp').multiDatesPicker({
                # minDate available is 3 days into the future
                minDate: new Date(today.setDate(today.getDate() + @MIN_DAY_OFFSET))
                onSelect: (dateText, inst)=>
                    @refresh()
                    # if @shiftKey

                        
            });

            @datepicker.click()

        refresh:()->
            @updateDayCounts()
            @updatePrice()

            $(@Widget).find('.price').html " $#{@Price.toFixed(2)}"
            $(@Widget).find('.weekdays').html @Weekdays
            $(@Widget).find('.weekends').html @Weekends
            
            @Widget.trigger('orderItemChanged', @)