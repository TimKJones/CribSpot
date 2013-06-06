<?php echo $this->Html->css('myorders')?>

<div class = 'orcontainer'>
        <div class = 'myOrders'>
            
            <?php foreach ($orders as $order) {
                
                $date = new DateTime($order['Order']['created']);
                $items = json_decode($order['Order']['items']);
                ?>
                <div class = 'order-item'>
                    <div class = 'order-info'>    
                        <strong><?php echo $order['Order']['name'];?></strong>
                        <span class = 'pull-right'> <?php echo $date->format('m/d/Y');?></span>
                        <br>
                        <a href = '#' class ='toggle-details'>Show Details</a>
                    </div>
                    <div class = 'order-details' style = 'display:none'>
                        
                        <?php foreach ($items as $item) {?>
                            <div>
                                <a href = '/listings/<?php echo $item->listing_id;?>'>
                                    <strong><?php echo $item->street_address; ?></strong>
                                </a>
                                <span class = 'pull-right'>
                                    <?php echo "$".number_format($item->price, 2);?>
                                </span>
                                <br>
                                <i class = 'icon-calendar'></i>
                                <?php 
                                    $start = date("m/d/Y", $item->start/1000);
                                    $end = date("m/d/Y", $item->end/1000); 
                                    echo "From $start To $end";
                                ?> 

                            </div>
                        <?php }?>
                    </div>
                    <span class = 'total'>
                        <strong><?php echo "$".number_format($order['Order']['price'], 2);?></strong>
                    </span>
                </div>
            
            <?php }?>
    </div>

</div>



<script type="text/javascript">
    $(function(){
        $('.order-item').click(function(event){
            // console.log($(event.target)
            if($(event.target).hasClass('toggle-details')){
                var order_details = $(event.currentTarget).find('.order-details').first();
                var visible = order_details.is(':visible');
                if(visible){
                    // Hide the details
                    order_details.hide();
                    $(event.target).html('Show Details');
                }else{
                    //Show
                    order_details.show();
                    $(event.target).html('Hide Details');
                }
            }
        });
    });
</script>