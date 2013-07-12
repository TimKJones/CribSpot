<?php echo $this->Html->script('less.js'); ?>
<?php echo $this->Html->script('underscore'); ?>
<?php echo $this->Html->script('src/Order'); ?>
<?php echo $this->Html->script('src/Order.FeaturedListing'); ?>


<?php echo $this->Html->script('src/FLDash'); ?>
<?php $this->Html->css('/less/order.less?','stylesheet/less', array('inline' => false)); ?>
<?php $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-ui.multidatespicker'); ?>
<?php echo $this->Html->css('multi-date-picker'); ?>
<script src="https://sandbox.google.com/checkout/inapp/lib/buy.js"></script>


<div class = 'FLDash'>
    <div class = 'left-content'>
        <div id = 'listings_list'></div>
    </div>

    <div class = 'fl_form'>
        <?php echo $this->element('Order/featured-listing-item');?>
        <button class = 'btn feature-listing'>Feature</button>
    
    <table class = 'table'>
        <thead> 
                <th>Address</th>
                <th>Price</th>
                <th></th>
        </thead>
        
        <tbody id = 'orderItems_list'></tbody>
    </table>
    <button class = 'btn' id = 'buyNow'>Buy</button>
    </div>

</div>

<script>
    var FLDash;
    $(function(){
        FLDash = new A2Cribs.FLDash($(".FLDash").first())
    });
</script>