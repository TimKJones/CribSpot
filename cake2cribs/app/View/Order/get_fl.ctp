<html>

<script src="https://sandbox.google.com/checkout/inapp/lib/buy.js"></script>

<div id = 'buy-featured'>
    <input id = 'lid' type = 'text' placeholder = 'listing_id' name = 'listing_id' value = '3'></input>
    <input id = 'dur' type = 'text' placeholder = 'duration' name = 'duration' value = '2'></input>
    <button id = 'purchase'> Purchase </button>

</div>



<script>

    $(function(){

        var purchase = function(){
            
            var start = (new Date).getTime();
            var listing_id = $('#lid').val();
            var duration = $('#dur').val();

            var data = {
                'type':'featured-listing',
                'info':JSON.stringify({
                    'start':start,
                    'listing_id':listing_id,
                    'duration':duration,
                })
            }
            var url = "http://localhost:8888/orders/getJwt";
            $.post(url, data, function(response_raw){
                response = JSON.parse(response_raw);
                if(!response.success){
                    console.log(response.message);
                    return;
                }
                google.payments.inapp.buy({
                    parameters: {},
                    jwt: response.jwt,
                    success: function() {window.alert("success")},
                    failure: function() {window.alert("failure")}
                });


            });
        };


        $('#purchase').click(function(){
            purchase();
        });


    });
</script>

</html>