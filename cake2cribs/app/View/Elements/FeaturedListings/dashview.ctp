<?php echo $this->Html->script('underscore'); ?>
<?php echo $this->Html->script('src/Order'); ?>
<?php echo $this->Html->script('src/Order.FeaturedListing'); ?>
<?php echo $this->Html->script('less.js'); ?>

<?php echo $this->Html->script('src/FLDash'); ?>
<?php $this->Html->css('/less/order.less?','stylesheet/less', array('inline' => false)); ?>
<?php $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-ui.multidatespicker'); ?>
<?php echo $this->Html->css('multi-date-picker'); ?>
<script src="https://sandbox.google.com/checkout/inapp/lib/buy.js"></script>

<div id = 'FLDash'>
    <div class = 'span4 left-content'>
        <div class='listing-wrapper'>
            <h4 class = 'header'>Listings</h4>
            <div id = 'listings_list'></div>
        </div>
    </div>
    <div class = 'span4 middle-content'>
        <div class = 'orderingInfo'>
            <div class = 'listing-wrapper'>
                <h4 class ='header'>Order Details</h4>
                <dl id = 'validation-error-list'></dl>
                <table class = 'table orderItems_table'>
                    <thead> 
                            <th>Address</th>
                            <th>Price</th>
                            <th></th>
                    </thead>
                    
                    <tbody id = 'orderItems_list'></tbody>
                    <tfoot>
                        <tr>
                            <th><span class = 'pull-right'>Total:</span></th>
                            <td>$<span class = 'total'></span></td>
                            <th> <button class = 'btn' id = 'buyNow'>Buy</button></th>
                        </tr>
                    </tfoot>

                </table>
               
            </div>
        </div>
    </div>
    <div class = 'span4 right-content'>
        <div class = 'listing-wrapper'>
        <div class = 'orderingInfo'>
        <div class = 'fl_form'>
                <?php echo $this->element('Order/featured-listing-item');?>
            </div>
        </div>
        <div id = 'orderingInstructions'>
            <h4> How It Works</h4>
            <p>
                Click the star next to a listing you want to feature.
                Select the days you want to feature it. Repeat for as
                many listings as you want. When you are satisfied with
                the order click the buy button to procede with
                the checkout process.
            </p>
        </div>
    </div>
    </div>


    

</div>

<script>
    var FLDash;
    $(function(){
        FLDash = new A2Cribs.FLDash($("#FLDash"))
    });
</script>