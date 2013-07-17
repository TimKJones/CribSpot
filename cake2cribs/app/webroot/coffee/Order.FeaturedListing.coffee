class A2Cribs.Order.FeaturedListing
        
        # Options can contain the properties selected_dates, and/or disabled_dates which are arrays of date strings
        constructor:(@Widget, @listing_id, @address, options=null)->
            
            @Weekdays = 0
            @Weekends = 0
            @Price = 0

            @WD_price = 15
            @WE_price = 5
            @MIN_DAY_OFFSET = 3
            @initMultiDatesPicker(options)

            @Widget.find('.address').html @address
            
            # if options.selected_dates?
            #     @datepicker.multiDatesPicker('addDates', dates)

            # if options.disabled_dates?
            #     @datepicker.multiDatesPicker {addDisabledDates: options.disabled_dates}

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

        clear:(refresh_after=true)->
            @datepicker.multiDatesPicker 'resetDates', 'picked'
            @datepicker.multiDatesPicker 'resetDates', 'disabled'
            if refresh_after then @refresh()
            


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


        initMultiDatesPicker:(options=null)->
            today = new Date()
            selected_dates = null
            disabled_dates = null

            if options?.selected_dates?
                selected_dates = options.selected_dates

            if options?.disabled_dates?
                disabled_dates = options.disabled_dates
            selected_dates
            @datepicker = $(@Widget).find('.mdp').multiDatesPicker({
                dateFormat: "yy-mm-dd"
                addDates: selected_dates
                addDisabledDates: disabled_dates
                # minDate available is 3 days into the future
                minDate: new Date(today.setDate(today.getDate() + @MIN_DAY_OFFSET))
                onSelect: (dateText, inst)=>
                    @refresh()


                        
            });

            @datepicker.click()

        refresh:()->
            @updateDayCounts()
            @updatePrice()

            $(@Widget).find('.price').html " $#{@Price.toFixed(2)}"
            $(@Widget).find('.weekdays').html @Weekdays
            $(@Widget).find('.weekends').html @Weekends
            
            @Widget.trigger('orderItemChanged', @)

