<?php 
echo $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false));
?>

<div class = 'featured-listings-wrapper'>
    <div id = 'fl-side-bar'>
        <div id = 'uni-banner'>
            <img id="sidebar_top_image" src ='<?= $university["sidebar_img_path"] ?>'></img>
        </div>
        <div id = 'uni-name'>
            <span id ='name'><?php echo $university["name"]; ?></span>
            <span id = 'like-us'class = 'pull-right'><a href = "<?= $university["facebook_url"] ?>">Like</a> on Facebook</span>
        </div>
        <div id = 'list-info'>
            <span>Listings: </span>
        </div>
        <div id = 'listings-list' class = '<?= (strpos($university['name'], 'Michigan') !== false) ? 'daily_ad' : '' ;?>'>
            <div id = 'featured-listings'></div>
            <div id = 'ran-listings'></div>
        </div>
        <?php
        if (strpos($university['name'], 'Michigan') !== false)
            echo "<a href='http://michigandaily.com'<div class='sidebar-bottom-bar'>";
        ?>

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