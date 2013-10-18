<?php 
echo $this->Html->css('/less/featured-listings.less?','stylesheet/less', array('inline' => false));
?>

<div class = 'featured-listings-wrapper'>
    <div id = 'fl-side-bar'>
        <div id = 'uni-banner'>
            <img id="sidebar_top_image" src ='<?= (array_key_exists("sidebar_img_path", $university) &&  $university["sidebar_img_path"] != null) ?  $university["sidebar_img_path"] : "/img/sidebar/default_university.png" ; ?>'></img>            
        </div>
        <div id = 'uni-name'>
            <span id ='name'><?php echo $university["name"]; ?></span>
            <span id = 'like-us'class = 'pull-right'><a href = "<?= (array_key_exists("facebook_url", $university) &&  $university["facebook_url"] != null) ?  $university["facebook_url"] : "https://facebook.com/Cribspot" ; ?>">Like</a> on Facebook</span>
            
        </div>
        <div id = 'list-info'>
            <span>Listings: </span>
        </div>
        <?php
        if (strpos($university['name'], 'Ann') !== false)
            echo "<div id='featured_pm' class='sidebar-bottom-bar'></div>";
        ?>p
        <div id = 'listings-list' class = '<?= (strpos($university['name'], 'Ann') !== false) ? 'daily_ad' : '' ;?>'>
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