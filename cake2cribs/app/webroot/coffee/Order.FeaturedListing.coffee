class A2Cribs.Order.FeaturedListing
        
    # initialState is used to setup the widget to a previous state
    # it contains the selected dates and the state of enableness of the universities
    
    constructor:(@Widget, @listing_id, @address, @UniData, initialState=null)->
        
        @Weekdays = 0
        @Weekends = 0
        @Price = 0

        @WD_price = 0
        @WE_price = 0

        @MIN_DAY_OFFSET = 3
        @initMultiDatesPicker(initialState)
        @initTemplates()
        @PrevSelectedDate = null
        @RangeSelectEnabled = true

        @Widget.find('.address').html @address

        @setupHandlers()            

        @setupUniPriceTable(initialState)

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
            @UniData[index].enabled = $(event.currentTarget).prop 'checked'
            @refresh()


    setupUniPriceTable:(intialState)->
        rows = ""
        for uniPrice in @UniData
            if initialState?.universities?[uniPrice.university_id]?
                uniPrice.enabled = initialState.universities[uniPrice.university_id]
            else
                uniPrice.enabled = true
            
            rows += @UniPriceRow uniPrice

        @Widget.find('.uniPriceTable>tbody').html rows


    # Returns an order object than can be sent to the 
    # back end to process an order. It simply contains
    # the listing_id, 
    @GenerateOrderItem:(orderState, uni_data)->
        #without expects a list of args, this is a hacky way of
        #passing an array for the elements you don't want
        dates = _.without.apply(_,[orderState.selectedDates].concat(uni_data.unavailable_dates))
        return {
            listing_id: orderState.listing_id,
            university_id: uni_data.university_id
            dates: dates
        }


    # getOrderItem:()->
    #     unis = {}
    #     orderItems
    #     for uni in @UniData
    #         unis[uni.university_id] = uni.enabled

    #     orderItem =  {
    #         type: 'FeaturedListing'
    #         price: @getPrice()
    #         item: {
    #             address: @address
    #             listing_id: @listing_id
    #             dates: @getDates('string')
    #             universities: unis
    #         }
    #     }


    # Called when we are prepping to save the state of the widget
    # So that another listing can be loaded into the form
    # We want to include data like what universities are enabled
    # and what dates are selected.

    getState:()->
        unis = {}
        for uni in @UniData
            unis[uni.university_id] = uni.enabled
        return {
            selectedDates: @getDates('string')
            universities: unis
            listing_id: @listing_id
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
        @Widget.off 'click', 'input.uni-toggle',

        if refresh_after then @refresh()
        


    getDates:(type = 'object')->
        @datepicker.multiDatesPicker 'getDates', type

    updatePrice:()->
        @Price = @Weekdays * @WD_price + @Weekends * @WE_price

    updateRates:()->
        @WE_price = 0
        @WD_price = 0
        for uni in @UniData
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


    initMultiDatesPicker:(initialState)->
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

        if initialState?
            pickeroptions.addDates = initialState.selectedDates

        # Note 8/3/2013 not doing any disabled dates right now
        # until jason further defines requirements

        # if options?.disabled_dates?
        #     pickeroptions.addDisabledDates = options.disabled_dates
        
        

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

        dateConflictNoticeHTML = """
        <li><i class = 'icon-warning-sign'></i> Listing already featured at <%=name%> on <%
            $.each(dates, function(index, date){
                d = new Date(date)
                if(index != dates.length-1)
                    print(d.getMonth()+1 + "-" + d.getDate() +"-"+ d.getFullYear() + ", ");
                else
                    print(d.getMonth()+1 + "-" + d.getDate()+"-"+ d.getFullYear());
            });
            %></li>
        """
        @DateConflictNotice = _.template(dateConflictNoticeHTML)

    # If for some reason the user has selected to feature on a date
    # such that the listing is already featured on that date for one
    # of the enabled universities we need to notify them of that case

    # Returns the difference in price due to ineligable dates
    checkForDateConflicts:()->
        selected_dates = @getDates('string')
        conflictNotices = ""
        priceDif = 0
        for uni in @UniData
            if not uni.enabled
                continue
            dates = []
            for unavailDate in uni.unavailable_dates
                if $.inArray(unavailDate, selected_dates) != -1
                    dates.push unavailDate
                    d = new Date(unavailDate)
                    # dif a weekend
                    day = d.getDay()
                    if !day?
                        continue
                    # BUG-d is always one day less than that the actual date
                    day = (day + 1)%7
                    if day == 0 or day == 6
                        priceDif += uni.weekend_price
                    else
                        priceDif += uni.weekday_price

            if dates.length > 0
                conflictNotices += @DateConflictNotice({name:uni.name, dates:dates})        

        @Widget.find('.DateConflicts').html conflictNotices

        return priceDif


    refresh:()->
        @updateDayCounts()
        @updateRates()
        @updatePrice()
        
        priceDiffDueToConflicts = @checkForDateConflicts()
        @Price -= priceDiffDueToConflicts


        $(@Widget).find('.price').html " $#{@Price.toFixed(2)}"
        $(@Widget).find('.weekdays').html @Weekdays
        $(@Widget).find('.weekends').html @Weekends
        @Widget.trigger('orderItemChanged', @)

