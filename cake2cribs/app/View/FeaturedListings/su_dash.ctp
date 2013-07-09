<?php echo $this->Html->script('less.js'); ?>
<?php echo $this->Html->script('src/Order'); ?>
<?php echo $this->Html->script('src/Order.FeaturedListing'); ?>

<?php echo $this->Html->script('src/FLDash'); ?>
<?php $this->Html->css('/less/order.less?','stylesheet/less', array('inline' => false)); ?>
<?php $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery-ui.multidatespicker'); ?>
<?php echo $this->Html->css('multi-date-picker'); ?>

<div class = 'FLDash'>
    <div class = 'listings_list'>
        <?php 
            foreach ($listings as $key => $listing) {
                $id = $listing['Listing']['listing_id'];
                $addr = $listing['Marker']['street_address'];
                ?>
                <div class = 'listing' data-id="<?php echo $id;?>" data-addr="<?php echo $addr; ?>" >
                    <strong><?php echo $addr;?></strong>
                </div>
            <?php }?>
    </div>

    <div class = 'fl_form'>
        <?php echo $this->element('Order/featured-listing-item');?>
        <button class = 'btn feature-listing'>Feature</btn>
    </div>

</div>

<script>
    var FLDash;
    $(function(){
        FLDash = new A2Cribs.FLDash($(".FLDash").first())
    });
</script>