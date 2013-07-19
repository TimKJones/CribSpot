<?php echo $this->Html->script('less.js'); ?>
<?php echo $this->Html->script('underscore'); ?>
<?php echo $this->Html->script('src/Order'); ?>
<?php echo $this->Html->script('src/Order.FeaturedListing'); ?>


<?php echo $this->Html->script('src/FLDash'); ?>
<?php $this->Html->css('/less/order.less?','stylesheet/less', array('inline' => false)); ?>
<?php $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-ui.multidatespicker'); ?>
<?php $this->Html->css('/less/order.less?','stylesheet/less', array('inline' => false)); ?>
<?php $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('multi-date-picker'); ?>
<script src="https://sandbox.google.com/checkout/inapp/lib/buy.js"></script>


<div class = 'FLDash'>
    <div class = 'left-content'>
        <div id = 'listings_list'></div>
    </div>
    <div class = 'right-content'>
        <div class = 'fl_form'>
            <?php echo $this->element('Order/featured-listing-item');?>
        </div>
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
                    <th></th>
                </tr>
            </tfoot>

        </table>
        <button class = 'btn' id = 'buyNow'>Buy</button>
    </div>
    

</div>

<script>
    var FLDash;
    $(function(){
        FLDash = new A2Cribs.FLDash($(".FLDash").first(), <?php echo $unavaildates;?>)
    });
</script>