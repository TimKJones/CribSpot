<?php echo $this->Html->script('less.js'); ?>
<?php $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false)); ?>


<div class = 'featured-listing-map-sidebar'>

<?php 
    foreach ($listings as $listing) {
        echo $this->element('FeaturedListings/featured_listings_list_item', array('listing'=>$listing));
    }
?> 

</div>