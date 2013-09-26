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
	

	echo $this->element('header', array('show_filter' => false, 'show_user' => true, 'show_personal' => true));
	echo $this->element('map');
	echo $this->element('FeaturedListings/fl_sidebar', $university);
	//echo $this->element('login');
	//echo $this->element('register');
	/*
	$declare_marker_id_to_open =  'A2Cribs.marker_id_to_open = ' . $marker_id_to_open . ';';
	$declare_sublet_data =  'A2Cribs.loaded_sublet_data = ' . json_encode($sublet_data_for_tooltip) . ';';
	*/
	/* Create and initialize the map */
	$this->Js->buffer(	
		'A2Cribs.FBInitialized = false;' . 
		'A2Cribs.VerifyManager.init('.$user.');' . 
		'A2Cribs.Map.Init(' . $university["id"] . ',' . $university["latitude"] . ',' . $university["longitude"] . ',"' . $university["city"] . '","' . $university["state"] . '","' . $university["name"] . '","' . $active_listing_type . '");	
	'

	);
?>
