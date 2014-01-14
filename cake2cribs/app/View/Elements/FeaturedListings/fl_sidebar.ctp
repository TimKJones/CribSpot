<?php 
echo $this->Html->css('/less/featured-listings.less?v=4','stylesheet/less', array('inline' => false));
echo $this->Html->css('/less/hotlist.less?v=4','stylesheet/less', array('inline' => false));
?>

<div class = 'featured-listings-wrapper'>
    <div id = 'fl-side-bar'>
        <div id = 'uni-name'>
            <span id ='name'><?php echo $university["name"]; ?></span>
            <div class="fb-like pull-right" data-href="<?= (array_key_exists('facebook_url', $university) &&  $university['facebook_url'] != null) ?  $university['facebook_url'] : 'https://facebook.com/Cribspot'; ?>" data-width="The pixel width of the plugin" data-height="The pixel height of the plugin" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false"></div>            
        </div>
        <div id='hotlist'>
            <div id='top-section'></div>
            <div id='friends'></div>
            <div id='bottom-section'></div>
        </div>
        <div id='listings-list-container'>
            <div id = 'listings-list' class = '<?= (strpos($university['name'], 'Ann') !== false || strpos($university['name'], 'Bloom') !== false) ? 'has_featured_pm' : '' ;?>'>
                <div id='featured-listings'></div>
                <div id='ran-listings'></div>
                <div id='loading-spinner'><img src="/img/loading.gif"></img></div>
            </div>
        </div>
        <?php if (strpos($university['name'], 'Ann') !== false) { ?>
            <div class='featured_pm' data-user-id="30">
                <img src="/img/sidebar/cmb_logo.png">
                <p>Most Awarded Property Manager in A2!</p>
                <button>Click to View 20+ Locations</button>
            </div>
        <? } else if (strpos($university['name'], 'Bloom') !== false) { ?>
            <div class='featured_banner'>
                <a href="http://iusa.indiana.edu/" target="_blank"><img src="/img/sidebar/IUSA_logo.jpg"></a>
            </div>
        <? } ?>
        <div class='legal-bar'>
            <span>Cribspot, LLC. | <a href='/TermsOfUse'>Terms</a> | <a href='/PrivacyPolicy'>Privacy</a> | <a href='/Disclaimer'>Disclaimer</a></span>
        </div>
    </div>
</div>