<?php 

    foreach ($listings as $listing) {
        echo $this->element('FeaturedListings/featured_listings_list_item', array('listing'=>$listing));
    }

?> 