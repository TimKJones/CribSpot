<?php
	echo $this->Html->script('src/VerifyManager');
	echo $this->Html->script('src/Rental');
	echo $this->Html->script('src/UserCache');
	echo $this->Html->css('DailyLogo');
	$this->set('title_for_layout', 'Cribspot Off-Campus Housing');
	echo $this->element('header', array('show_filter' => false, 'show_user' => true, 'show_personal' => true));
	echo $this->element('map');
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
		'A2Cribs.Map.Init(' . $school_id . ',' . $school_lat . ',' . $school_lng . ',"' . $school_city . '","' . $school_state . '","' . $school_name . '","' . $active_listing_type . '");	
	'

	);
?>
