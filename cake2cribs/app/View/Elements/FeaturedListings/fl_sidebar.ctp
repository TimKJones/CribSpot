<?php 
echo $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false));
?>

<div class = 'featured-listings-wrapper'>
    <div id = 'fl-side-bar'>
        <div id = 'uni-banner'>
            <img src ='http://placehold.it/340x80'></img>
        </div>
        <div id = 'uni-name'>
            <span id ='name'><?php echo $school_name; ?></span>
            <span id = 'like-us'class = 'pull-right'><a href = '#'>Like</a> on Facebook</span>
        </div>
        <div id = 'list-info'>
            <span>Listings: </span>
        </div>
        <div id = 'listings-list'>
            <div id = 'featured-listings'></div>
            <div id = 'normal-listings'></div>
        </div>
    </div>
</div>