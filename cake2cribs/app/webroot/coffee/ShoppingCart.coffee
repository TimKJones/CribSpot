class A2Cribs.ShoppingCart
    constructor:(@Widget)->
        @Widget.on 'click', '.edit', (event)=>
            index = $(event.currentTarget).attr 'id'
            @edit(index)
            

        @Widget.find('.fl-cart-item').each (index, element)=>
            
            $(element).find(".edit").click ()=>
                @edit(index)

            $(element).find(".remove").click ()=>
                @remove(index)

        @Widget.find('.buy').click ()=>
            A2Cribs.Order.BuyCart()

        @Widget.find('.hide-edit').click ()=>
            $('.edit-form').fadeOut()

        @Widget.find('.save').click ()=>
            @save(@EditingIndex)

        @Editing = false;
        @EditingIndex = -1
        @orderItem = null

        @refresh()

    remove:(index)->
        url = myBaseUrl + "shoppingCart/remove"
        data = {'index':index}
        $.post url, data, (response)=>
            success = JSON.parse(response).success
            if success
                @Widget.find('.fl-cart-item').get(index).remove()
            else
                alertify.error("Removing item #{index+1} failed");

    refresh:()->
        $('.orderItems > tbody').load '/shoppingCart/get', ()->
            $('table.orderItems').fadeIn()

    edit:(index)->
        @orderItem = new A2Cribs.Order.FeaturedListing($('.featured-listing-order-item').first(), {address:'123 Fake Street', listing_id:12, dates:['6/26/13']});
        $('.edit-form').fadeIn('fast')
        @EditingIndex = index
             

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
                    @refresh()
                else
                    alertify.error(data.msg)


                
        




    

        


