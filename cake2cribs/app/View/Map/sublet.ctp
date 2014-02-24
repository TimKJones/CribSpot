<?php

/* ----------------------- BOTH ----------------------------------- */
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/VerifyManager');
	echo $this->Html->script('src/Rental');
	echo $this->Html->script('src/UserCache');
	echo $this->Html->script('src/FeaturedListings');
	echo $this->Html->script('src/Hotlist');
}
	
	echo $this->Html->css('DailyLogo');
	echo $this->Html->css('/less/mobile_map_hacks.less?v=4','stylesheet/less', array('inline' => false));
/* ------------------------ DIFFERENT --------------------------------------- */


	$this->set('title_for_layout', "College Student Sublets | " . $university["name"] . " | Cribspot");
	$url = 'https://cribspot.com/sublet/' . str_replace(" ", "-", $school_name);
	$description = "Find & advertise sublets at " . $university['name'] . " on Cribspot’s temporary housing map. Sublease your off-campus apartment / house to students in " . $university['city'] . ".";

	$this->set('meta_description', $description);
	$this->set('canonical_url', $url);

	echo $this->Html->meta('description', $description, array('inline' => false));
	echo $this->Html->meta('canonical', $url, array('rel'=>'canonical', 'type'=>null, 'title'=>null, 'inline' => false));
/* -------------------------- BOTH ------------------------------------ */

	echo $this->element('header', 
		array(
			'page' => 'sublet',
			'school_name' => str_replace(" ", "-", $school_name),
			'sublets_launch_date' => $university['sublets_launch_date'],
			'show_filter' => false,
			'show_user' => true,
			'show_personal' => true,
			'locations' => $locations,
			'user_years' => $user_years
	));
	echo $this->element('map', array('active_listing_type' => $active_listing_type, 'university' => $university));
	echo $this->element('FeaturedListings/fl_sidebar', $university);
	echo $this->element('SEO/places_rich_snippet', array('latitude' => $university["latitude"], 'longitude' => $university["longitude"]));
	echo $this->element('SEO/facebook_meta_tag', array('title' => $university["name"] . ' Sublets', 'url' => $url, 'image_path' => 'https://s3-us-west-2.amazonaws.com/cribspot-img/upright_logo.png', 'description' => $description));
?>
