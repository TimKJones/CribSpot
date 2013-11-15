<?php

	echo $this->Html->script('src/VerifyManager');
	echo $this->Html->script('src/Rental');
	echo $this->Html->script('src/UserCache');
	echo $this->Html->script('src/FeaturedListings');


	echo $this->Html->css('DailyLogo');
	$this->set('title_for_layout', $university["name"] . ' Off-Campus Housing');

	$this->Html->meta('keywords', 
		$university["name"] . " off campus housing, " . $university["name"] . " student housing, " . $university["city"] . " campus apartments, " . $university["city"] . " college apartments, " . $university["city"] . " college housing, " . $university["state"] . " college housing", array('inline' => false)
	);

	$this->Html->meta('description', "Welcome to Cribspot for " . $university["name"]  . "! Looking for off campus housing in " . $university["city"] . "? Browse the many full year listings Cribspot has to offer.", array('inline' => false));

	echo $this->Html->meta('canonical', 'https://cribspot.com/rental/' . str_replace(" ", "_", $school_name), array('rel'=>'canonical', 'type'=>null, 'title'=>null, 'inline' => false));

	echo $this->element('header', 
		array(
			'page' => 'rental',
			'school_name' => str_replace(" ", "_", $school_name),
			'sublets_live' => $university['sublets_live'],
			'show_filter' => false,
			'show_user' => true,
			'show_personal' => true,
			'locations' => $locations,
			'user_years' => $user_years
		));
	echo $this->element('map', array('active_listing_type' => $active_listing_type));
	echo $this->element('FeaturedListings/fl_sidebar', $university);
	echo $this->element('SEO/places_rich_snippet', array('latitude' => $university["latitude"], 'longitude' => $university["longitude"]));

	/* Create and initialize the map */
	$this->Js->buffer(
		'A2Cribs.Map.Init(' . $university["id"] . ',' . $university["latitude"] . ',' . $university["longitude"] . ',"' . $university["city"] . '","' . $university["state"] . '","' . $university["name"] . '","' . $active_listing_type . '");	
	'

	);
?>
