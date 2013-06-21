<?php echo $this->Html->script('less.js'); ?>
<?php $this->Html->css('/less/order.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('src/Order'); ?>
<?php echo $this->Html->script('src/ShoppingCart'); ?>
<?php echo $this->Html->script('src/Order.FeaturedListing'); ?>
<?php echo $this->Html->css('multi-date-picker'); ?>

<?php echo $this->Html->script('jquery-ui.multidatespicker'); ?>

<script src="https://sandbox.google.com/checkout/inapp/lib/buy.js"></script>


<div class = 'ShoppingCart order-window'>
    <h3 class = 'title'>Shopping Cart</h2>
    <table class = 'table table-hover orderItems'>
        <thead> 
                <th>Address</th>
                <th>Price</th>
                <th></th>
        </thead>
        
        <tbody></tbody>
        
    </table>
    <div class = 'edit-form'>
         <?php echo $this->element('Order/featured-listing-item'); ?> 
         <button class = 'btn save'>Save</button>
         <button class = 'btn hide-edit'>Close</button>
    </div>
    <button class = 'buy btn'>Buy</button>
</div>

<script>
    var ShoppingCart;
    $(function(){
        ShoppingCart = new A2Cribs.ShoppingCart($('.ShoppingCart').first());
    });




</script>