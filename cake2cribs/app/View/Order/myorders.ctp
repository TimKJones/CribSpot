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
                        <a href = '#' class ='toggle-details'><i class = 'icon-plus'></i> Show Details</a>
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
                                    $date_str = "";
                                    foreach($item->dates as $index => $date){
                                        if($index == 0){
                                            //Proper coma usage
                                            $date_str = $date;
                                        }else{
                                            $date_str = $date_str . ", ". $date;
                                        }
                                    }
                                    echo "<span>$date_str</span>";
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
                    $(event.target).html("<i class = 'icon-plus'></i> Show Details");
                }else{
                    //Show
                    order_details.show();
                    $(event.target).html("<i class = 'icon-minus'></i> Hide Details");
                }
            }
        });
    });
</script>