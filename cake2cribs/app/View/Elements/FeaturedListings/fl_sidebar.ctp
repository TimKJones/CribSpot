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
            <span id = 'like-us'class = 'pull-right'><a href = "http://www.facebook.com/Cribspot">Like</a> on Facebook</span>
        </div>
        <div id = 'list-info'>
            <span>Listings: </span>
        </div>
        <div id = 'listings-list'>
            <div id = 'featured-listings'></div>
            <div id = 'ran-listings'></div>
        </div>
        <!--div class = 'cycle-listings-bar'>
            <div>
                <i class = 'icon-caret-down dir' data-dir = "down"></i>
                    <span>Click to Cycle Through Listings</span>
                <i class = 'icon-caret-up dir' data-dir = "up"></i>
            </div>
        </div>
        <div class = 'marketplace-bar'>
            <a class = 'blue-button' href = '#Marketplace'>Marketplace</a>
        </div-->
        <div class = 'legal-bar'>
            <span>Cribspot, LLC. | <a href = '/TermsOfUse'>Terms</a> | <a href = '/PrivacyPolicy'>Privacy</a> | <a href = '/Disclaimer'>Disclaimer</a></span>
        </div>
    </div>
</div>