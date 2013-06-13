class A2Cribs.Order.FeaturedListing
        constructor:(@item, @rules)->
            
            @address = @item.find('.address').text()
            @listing_id = @item.attr('id')
            
            @Weekdays = 0
            @Weekends = 0
            @Price = 0

            @WD_price = @rules.FeaturedListings.costs.weekday
            @WE_price = @rules.FeaturedListings.costs.weekend

            @initMultiDatesPicker()
            @shiftKey = false;
            $(window).keydown (event)=>
                if event.shiftKey or event.keyCode is 16
                    @shiftKey = true

            $(window).keyup (event)=>
                if event.shiftKey or event.keyCode is 16
                    @shiftKey = false

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
            @datepicker = $(@item).find('.mdp').multiDatesPicker({
                minDate: new Date(),
                onSelect: (dateText, inst)=>
                    @dateSelected(dateText, inst)
                    # if @shiftKey

                        
            });

            @datepicker.click()

        dateSelected:(dateText, picker_inst)->
            @updateDayCounts()
            @updatePrice()

            $(@item).find('.price').html " $#{@Price.toFixed(2)}"
            $(@item).find('.weekdays').html @Weekdays
            $(@item).find('.weekends').html @Weekends
            
            @item.trigger('orderItemChanged', @)