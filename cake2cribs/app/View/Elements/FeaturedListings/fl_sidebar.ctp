<?php 
echo $this->Html->css('/less/featured-listings.less?v=4','stylesheet/less', array('inline' => false));
echo $this->Html->css('/less/hotlist.less?v=4','stylesheet/less', array('inline' => false));
?>

<div class = 'featured-listings-wrapper'>
    <div id = 'fl-side-bar'>
        <div id='hotlist'>
            <div id='top-section'></div>
            <div id='friends'></div>
            <div id='bottom-section'></div>
        </div>
        <div id='listings-list-container'>
            <div id='listings-list' class='<?= (strpos($university['name'], 'Ann') !== false) ? 'has_featured_pm' : '' ;?>'>
                <div id='featured-listings'></div>
                <div id='ran-listings'></div>
            </div>
        </div>
        <?php if (strpos($university['name'], 'Ann') !== false) { ?>
            <div class='featured_pm' data-user-id="30">
                <img src="/img/sidebar/cmb_logo.png">
                <p>Most Awarded Property Manager in A2!</p>
                <button>Click to View 20+ Locations</button>
            </div>
        <?php } ?>
        <div class='legal-bar'>
            <span>Cribspot, LLC. | <a href='/TermsOfUse'>Terms</a> | <a href='/PrivacyPolicy'>Privacy</a> | <a href='/Disclaimer'>Disclaimer</a></span>
        </div>
    </div>
</div>