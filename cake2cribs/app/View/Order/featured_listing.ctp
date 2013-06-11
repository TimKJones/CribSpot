<?php echo $this->Html->script('less.js'); ?>

<?php $this->Html->css('/less/order-featured-listing.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('multi-date-picker'); ?>

<?php echo $this->Html->script('jquery-ui.multidatespicker'); ?>

<?php echo $this->Html->script('src/Checkout'); ?>
<script src="https://sandbox.google.com/checkout/inapp/lib/buy.js"></script>

<div class = 'checkout-flow featured-listings-flow'>
    <div class = 'left-sec'>
        <div class= 'order-items'>
            <?php 
            foreach($listings as $listing){
                echo $this->element('Order/featured-listing-item', array(
                    'listing'=> $listing));
            }
            ?>
        </div>
        <table class = 'total-tally'>
            <tr>
                <td>Weekdays:</td>
                <td><strong class = 'weekdays'>0</strong><strong> x <?php echo "$". $rules['FeaturedListings']['costs']['weekday'];?></strong></td>
            </tr>
            <tr>
                <td>Weekends:</td>
                <td><strong class = 'weekends'>0</strong><strong> x <?php echo "$". $rules['FeaturedListings']['costs']['weekend'];?></strong></td>
            </tr>
            <tr>
                <td><strong>Total: </td>
                <td></strong><span class = 'total'>$0</span></td>
            </tr>
        </table>
        <button class = 'btn buy'>Buy</button>
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
                    <span class = 'big-price'><?php echo "$". $rules['FeaturedListings']['costs']['weekday'];?></span> /day
                    </div>
                </span>
                <span class = 'weekends-price'>
                    <div>Weekends</div>
                    <div>
                        <span class = 'big-price'><?php echo "$" . $rules['FeaturedListings']['costs']['weekend'];?></span> /day
                    </div>
                </span>
            </div>
        </div>
    </div>    
</div>

<script>
    var Checkout;
    $(function(){
        Checkout = new A2Cribs.Checkout($('.checkout-flow')[0], <?php echo $rules_json;?>);
    });

</script>