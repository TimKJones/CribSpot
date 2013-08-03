class A2Cribs.Order.FeaturedListing
        
        # Options can contain the properties selected_dates, and/or disabled_dates which are arrays of date strings
        constructor:(@Widget, @listing_id, @address, @UniPricing, options=null)->
            
            @Weekdays = 0
            @Weekends = 0
            @Price = 0

            @WD_price = 0
            @WE_price = 0

            @MIN_DAY_OFFSET = 3
            @initMultiDatesPicker(options)
            @initTemplates()
            @PrevSelectedDate = null
            @RangeSelectEnabled = true

            @Widget.find('.address').html @address

            @setupHandlers()            

            @setupUniPriceTable(options)

            @refresh()

        getPrice:()->
            return @Price

        setupHandlers:()->
            @Widget.on 'click', '.rst input', (event)=>
                @RangeSelectEnabled = !@RangeSelectEnabled
                @PrevSelectedDate = null

            .on 'click', '.rst .clear-selected-dates', (event)=>
                @clear()

            @Widget.on 'click', 'input.uni-toggle', (event)=>
                index = $(event.currentTarget).parents().eq(1).index()
                @UniPricing[index].enabled = $(event.currentTarget).prop 'checked'
                @refresh()


        setupUniPriceTable:(options)->
            rows = ""
            for uniPrice in @UniPricing
                if options.universities?[uniPrice.university_id]?
                    uniPrice.enabled = options.universities[uniPrice.university_id]
                else
                    uniPrice.enabled = true
                
                rows += @UniPriceRow uniPrice

            @Widget.find('.uniPriceTable>tbody').html rows

        getOrderItem:()->
            unis = {}
            
            for uni in @UniPricing
                unis[uni.university_id] = uni.enabled

            orderItem =  {
                type: 'FeaturedListing'
                price: @getPrice()
                item: {
                    address: @address
                    listing_id: @listing_id
                    dates: @getDates('string')
                    universities: unis
                }
            }

        # Clear all the picked dates
        clear:()->
            @datepicker.multiDatesPicker 'resetDates', 'picked'
            @refresh()

        # Removes all the dates picked and disabled, sets the form
        # up to have a different order loaded into it. Basically
        # invalidates the form. Removes all the event handlers

        reset:(refresh_after=true)->
            @datepicker.multiDatesPicker 'resetDates', 'picked'
            @datepicker.multiDatesPicker 'resetDates', 'disabled'
            @Widget.off 'click', '.rst input'
            @Widget.off 'click', '.rst .clear-selected-dates'
            if refresh_after then @refresh()
            


        getDates:(type = 'object')->
            @datepicker.multiDatesPicker 'getDates', type

        updatePrice:()->
            @Price = @Weekdays * @WD_price + @Weekends * @WE_price

        updateRates:()->
            @WE_price = 0
            @WD_price = 0
            for uni in @UniPricing
                if uni.enabled
                    @WD_price += uni.weekday_price
                    @WE_price += uni.weekend_price

            @Widget.find('#wd_rate').html @WD_price.toFixed(2)
            @Widget.find('#we_rate').html @WE_price.toFixed(2)

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
            
            pickeroptions = 
            {
                dateFormat: "yy-mm-dd"
                # minDate available is 3 days into the future
                minDate: new Date(today.setDate(today.getDate() + @MIN_DAY_OFFSET))
                onSelect: (dateText, inst)=>
                    
                    if(@RangeSelectEnabled)
                        @rangeSelect(dateText)
                    
                    @refresh()              
            }

            if options?.selected_dates?
                pickeroptions.addDates = options.selected_dates

            if options?.disabled_dates?
                pickeroptions.addDisabledDates = options.disabled_dates
            
            

            @datepicker = $(@Widget).find('.mdp').first().multiDatesPicker(pickeroptions);

            @datepicker.click()

        
        #Takes in a selected date and will select a range of dates if
        #two date selections are defined.

        # Usage, user clicks one date, then another date. The range of dates between
        # them is selected. The user clicks another date, the previous range is removed
        # and when the next date is clicked a new range is selected
        rangeSelect:(dateText)->
            
            if @PrevSelectedDate?
                _date = new Date(dateText)
                selectedDate = new Date(_date.getUTCFullYear(), _date.getUTCMonth(), _date.getUTCDate())
                if(@PrevSelectedDate > selectedDate)
                    [@PrevSelectedDate, selectedDate] = [selectedDate, @PrevSelectedDate] # Swap 'em


                @SelectedDateRange = A2Cribs.UtilityFunctions.getDateRange(@PrevSelectedDate, selectedDate)
                
                # Go through and remove any dates in the range that are disabled
                # go through it in reverse as to not screw up the indexing while splicing
                for i in [@SelectedDateRange.length - 1..0] by -1
                    date = @SelectedDateRange[i]
                    if @datepicker.multiDatesPicker('gotDate', date, 'disabled') != false
                       @SelectedDateRange.splice(i, 1)
                
                @PrevSelectedDate = null

                @datepicker.multiDatesPicker 'addDates', @SelectedDateRange
                

            else
                # We need to see if there was already a selected range
                # Since we are starting a new range selection we need to remove
                # the old range if it exists.
                if @SelectedDateRange?
                    @datepicker.multiDatesPicker 'removeDates', @SelectedDateRange
                
                @SelectedDateRange = null
                _date = new Date(dateText)
                @PrevSelectedDate = new Date(_date.getUTCFullYear(), _date.getUTCMonth(), _date.getUTCDate())
                @datepicker.multiDatesPicker 'addDates', [@PrevSelectedDate]



        initTemplates:()->
            uniPriceRowHTML = """
            <tr data-university_id='<%= university_id %>' >
                <td><%=name%></td>
                <td class = 'rates'>$<%=weekday_price.toFixed(2)%></td>
                <td class = 'rates'>$<%=weekend_price.toFixed(2)%></td>
                <td><input class = 'uni-toggle' type='checkbox' <% if(enabled){print('checked');} %> />
            </tr>
            """
            @UniPriceRow = _.template(uniPriceRowHTML)


        refresh:()->
            @updateDayCounts()
            @updateRates()
            @updatePrice()


            $(@Widget).find('.price').html " $#{@Price.toFixed(2)}"
            $(@Widget).find('.weekdays').html @Weekdays
            $(@Widget).find('.weekends').html @Weekends
            @Widget.trigger('orderItemChanged', @)

