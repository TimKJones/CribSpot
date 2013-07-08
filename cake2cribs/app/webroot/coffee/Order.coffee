class A2Cribs.Order

    @BuyItem:(orderItem, successHandler=null, failHandler=null)->
        data = {
            'orderItem': JSON.stringify(orderItem)
        }
        url = "#{myBaseUrl}order/buyItem"
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

    @BuyCart:(successHandler=null, failHandler=null)->
        url = "#{myBaseUrl}order/buyCart"
        $.post url, (response_raw)=>
            response = JSON.parse(response_raw)
            if !response.success
                console.log response.message
            google.payments.inapp.buy({
                parameters:{},
                jwt: response.jwt,
                success: ()->alert("success")
                failture: ()->alert("fail")
            })

    @AddToCart:(orderItems)->
        data = {
            'orderItems': JSON.stringify(orderItems)
        }
        url = myBaseUrl + "shoppingCart/add"
        $.post url, data, (response_raw)=>
            response = JSON.parse(response_raw)

            if response.success
               alertify.success('Added to cart', 1500)
            else
                alertify.error("Adding to cart failed", 1500)



            


  
    
                    


           
                



