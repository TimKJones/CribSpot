<?php 
echo $this->Html->css('/less/featured-listings.less?v=4','stylesheet/less', array('inline' => false));
echo $this->Html->css('/less/hotlist.less?v=4','stylesheet/less', array('inline' => false));
?>

<div class = 'featured-listings-wrapper'>
    <div id = 'fl-side-bar'>
        <div id='friends-list'></div>
        <?php
        if (strpos($university['name'], 'Ann') !== false)
        { ?>
            <div class='featured_pm' data-user-id="30">
                <img src="/img/sidebar/cmb_logo.png">
                <p>Most Awarded Property Manager in A2!</p>
                <button>Click to View 20+ Locations</button>
            </div>
        <?php
        }
        ?>
        <!--<div id = 'list-info'>
            <span>Listings: </span>
        </div> -->
        <div id = 'listings-list' class = '<?= (strpos($university['name'], 'Ann') !== false) ? 'has_featured_pm' : '' ;?>'>
            <div id = 'featured-listings'></div>
            <div id = 'ran-listings'></div>
        </div>

        </div></a>
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