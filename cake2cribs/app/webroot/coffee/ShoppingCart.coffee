class A2Cribs.ShoppingCart
    constructor:(@Widget)->
        @Widget

        .on 'click', '.edit', (event)=>
            index = $(event.currentTarget).attr 'id'
            @edit(index)

        .on 'click', '.remove', (event)=>
            index = $(event.currentTarget).attr 'id'
            @remove(index)
           

        
        @Widget.find('.buy').click ()=>
            A2Cribs.Order.BuyCart()

        @Widget.find('.hide-edit').click ()=>
            $('.fl-cart-item').removeClass('editing')
            $('.edit-form').fadeOut()

        @Widget.find('.save').click ()=>
            @save(@EditingIndex)

        @Editing = false;
        @EditingIndex = -1
        @orderItem = null


        ListItemHTML = """
            <tr class = 'fl-cart-item'>
                <td><span  class = 'address'><%= address %></span></td>
                <td><span class = 'price'?>$<%= price %></span></td>
                <td class = 'actions'>
                    <a href = '#' class = 'edit' id = '<%= id %>'><i class = 'icon-edit'></i></a>   
                    <a href = '#' class = 'remove' id = '<%= id %>'><i class = 'icon-remove-circle'></i></a>
                </td>
            </tr>
        """
        @ListItemTemplate = _.template(ListItemHTML)



        @refresh()


    remove:(index)->
        url = myBaseUrl + "shoppingCart/remove"
        data = {'index':index}
        $.post url, data, (response)=>
            success = JSON.parse(response).success
            if success
                @refresh()
            else
                alertify.error("Removing item #{index+1} failed");

    refresh:()->

        $.getJSON '/shoppingCart/get', (orderItems)=>
            $('.orderItems > tbody').html('')
            html = ""
            i = 0
            for oi in orderItems
                data = {
                    price: oi.price.toFixed(2)
                    address: oi.item.address
                    id: i++
                }
                html += @ListItemTemplate(data)
            $('.orderItems > tbody').html html

            $('table.orderItems').show()

            @orderItems = orderItems

    edit:(index)->
        fl = @orderItems[index]
        @orderItem?.clear() # Hack solution to solve the issue with the already selected dates
                           # in the calendar. There is one datepicker widget that is shared, swe
                           # Just need to remove the dates so someone else can use it since

        @orderItem = new A2Cribs.Order.FeaturedListing($('.featured-listing-order-item').first(), fl.item.listing_id, fl.item.address, fl.item.dates)
        $('.edit-form').fadeIn('fast')
        @EditingIndex = index

        $(".fl-cart-item:eq(#{index})").addClass('editing').siblings().removeClass('editing')




             

    save:()-> 
        if @EditingIndex >= 0
            data = { 
                orderItem : JSON.stringify(@orderItem.getOrderItem()) 
                index: @EditingIndex
            }
            $.post '/shoppingCart/edit', data, (response)=>
                data = JSON.parse(response)

                if data.success
                    alertify.success("Save Successful")
                    @Widget.find('.hide-edit').click()
                    @refresh()
                else
                    alertify.error(data.message)

    


                
        




    

        


