<?php echo $this->Html->script('less.js'); ?>

<?php $this->Html->css('/less/order.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('multi-date-picker'); ?>

<?php echo $this->Html->script('jquery-ui.multidatespicker'); ?>


<?php echo $this->Html->script('src/Order'); ?>
<?php echo $this->Html->script('src/Order.FeaturedListing'); ?>
<script src="https://sandbox.google.com/checkout/inapp/lib/buy.js"></script>


<div class = 'order-window'>
    <div class = 'left-sec'>
        <div class= 'order-items'>
            <?php echo $this->element('Order/featured-listing-item'); ?>
        </div>
        
        <button class = 'btn' id = 'buy'>Buy</button>
        <button class = 'btn' id = 'addToCart'>Add To Cart</button>
    </div>

    <div class = 'span5 right-sec'>
        <i class = 'close-checkout icon-remove-circle'></i>
        <div class = 'title'> How It Works </div> 
        <p>
            Click on the calendar to select any
            days that you wish to feature your
            listing. You may select day by day, or 
            you can select a day, hold shift, and 
            then select a future date to select a 
            range of dates.
        </p>
        <p>
            Once you’re satisified with your
            selections, click Buy Now to complete 
            our simple checkout flow where you 
            can pay for listings via credit card. You 
            may also add your listing to your cart
            if you wish to feature additional listings.   
        </p>
        <div class = 'rates'>
            <strong>Advertising Rates:</strong>
            <div class = 'rates-box'>
                <span class = 'weekdays-price'>
                    <div>Weekdays</div>
                    <div>
                    <span class = 'big-price'><?php echo "$". $wd_price;?></span> /day
                    </div>
                </span>
                <span class = 'weekends-price'>
                    <div>Weekends</div>
                    <div>
                        <span class = 'big-price'><?php echo "$" . $we_price;?></span> /day
                    </div>
                </span>
            </div>
        </div>
    </div>    
</div>

<script>
    var FeaturedListing;
    $(function(){

        FeaturedListing = new A2Cribs.Order.FeaturedListing(
            $('.featured-listing-order-item').first(), <?php echo $listing_id;?>, "<?php echo $address;?>");

        $('#buy').click(function(){
            A2Cribs.Order.BuyItem(FeaturedListing.getOrderItem());
        });

        $('#addToCart').click(function(){
            A2Cribs.Order.AddToCart([FeaturedListing.getOrderItem()]);
        });

    });

</script>