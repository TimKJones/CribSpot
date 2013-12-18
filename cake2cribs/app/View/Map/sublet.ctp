<?php

/* ----------------------- BOTH ----------------------------------- */
	echo $this->Html->script('src/VerifyManager');
	echo $this->Html->script('src/Rental');
	echo $this->Html->script('src/UserCache');
	echo $this->Html->script('src/FeaturedListings');
	echo $this->Html->script('src/Hotlist');
/* ------------------------ DIFFERENT --------------------------------------- */

	echo $this->Html->css('DailyLogo');
	$this->set('title_for_layout', $university["name"] . ' Sublets');

	$this->Html->meta('keywords', 
		$university["name"] . " sublets, " . $university["name"] . " subleases, " . $university["name"] . " temporary housing, " .$university["name"] . " off campus housing, " . $university["name"] . " student housing, " . $university["city"] . " campus apartments, " . $university["city"] . " college apartments, " . $university["city"] . " college housing, " . $university["state"] . " college housing", array('inline' => false)
	);

	$this->Html->meta('description', "Welcome to Cribspot for " . $university["name"]  . "! Looking for sublets in " . $university["city"] . "? Browse the many sublets Cribspot has to offer.", array('inline' => false));
/* -------------------------- BOTH ------------------------------------ */

	echo $this->element('header', 
		array(
			'page' => 'sublet',
			'school_name' => str_replace(" ", "_", $school_name),
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
?>
