<?php $this->Html->css('/less/order.less?','stylesheet/less', array('inline' => false)); ?>
<?php $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false)); ?>
<?php $this->Html->css('/less/order.less?','stylesheet/less', array('inline' => false)); ?>
<?php $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('multi-date-picker'); ?>

<?php
echo $this->Html->script('underscore');

if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
    echo $this->Html->script('src/Order');
    echo $this->Html->script('src/Order.FeaturedListing');
    echo $this->Html->script('src/FLDash');
}

echo $this->Html->script('jquery-ui.multidatespicker');
?>

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
<?php
    if (intval($AuthUser['user_type']) === 2)
    {
    ?>
<script>
    var FLDash;
    $(function(){
        FLDash = new A2Cribs.FLDash($(".FLDash").first(), <?php echo $unavaildates;?>)
    });
</script>

<?php } ?>