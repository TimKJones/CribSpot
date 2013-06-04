<?php echo $this->Html->script('less.js'); ?>

<?php $this->Html->css('/less/order-featured-listing.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('datepicker'); ?>


<?php echo $this->Html->script('bootstrap-datepicker'); ?>
<?php echo $this->Html->script('src/Checkout'); ?>
<script src="https://sandbox.google.com/checkout/inapp/lib/buy.js"></script>

<div class = 'checkout-flow featured-listings-flow container-fluid'>
    <div class = 'row-fluid'>
        <div class = 'span8'>
            <div class= 'order-items'>
                <?php 
                foreach($listings as $listing){
                    echo $this->element('Order/featured-listing-item', array(
                        'listing'=> $listing));
                }
                ?>
            </div>
            <hr>
            <div class = 'pull-right total-tally' style = 'display:none'>
                <Strong>Weekdays: </strong><span class = 'weekdays'></span> x <?php echo "$". $rules['FeaturedListings']['costs']['weekday'] ."/day";?><br>
                <Strong>Weekends: </strong><span class = 'weekends'></span> x <?php echo "$". $rules['FeaturedListings']['costs']['weekend'] ."/day";?><br>
                <strong>Total: </strong><span class = 'total'></span>
            </div>


        </div>

        <div class = 'span4'>
            <h3> How it works </h3> 
            <p>
                Select the range of days that you want each of your properties to 
                be featured on. 
            </p>
            <p>
                Once you are satisfied with your selections click Buy and complete our simple checkout flow where
                you can pay for listings via Credit Card.
            </p>
            <h3>Rates</h3>
            <strong><?php echo "$". $rules['FeaturedListings']['costs']['weekday'] ."/per weekday\n";?></strong>
            <br>
            <strong><?php echo "$" . $rules['FeaturedListings']['costs']['weekend']. "/per weekend.";?></strong>
        </div>
        <button class = 'btn buy'>Buy</button>
    </div>
    

</div>

<script>
    $(function(){
        var Checkout = new A2Cribs.Checkout($('.checkout-flow')[0], <?php echo $rules_json;?>);
    });

</script>