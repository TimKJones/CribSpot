<?php
	echo $this->Html->script('src/VerifyManager');
	echo $this->Html->css('DailyLogo');
	$this->set('title_for_layout', 'Cribspot Sublet');
	echo '<div id="container">';
		echo $this->element('header');
		echo $this->element('FeaturedListings/fl_sidebar');	
		echo $this->element('map');
		echo $this->element('login');
		echo $this->element('register');
		echo $this->element('post-sublet');

		
		//echo $this->element('favorites');

		/* Hidden Elements */
		echo $this->element('tooltips');
		echo $this->element('popups');


		if ($school_name == "University of Michigan-Ann Arbor")
			echo '<a href="http://www.michigandaily.com/classifieds"><div id="DailyLogo"></div></a>';
	echo '</div>';
	$declare_marker_id_to_open =  'A2Cribs.marker_id_to_open = ' . $marker_id_to_open . ';';
	$declare_sublet_data =  'A2Cribs.loaded_sublet_data = ' . json_encode($sublet_data_for_tooltip) . ';';
	/* Create and initialize the map */
	$this->Js->buffer(	
		'A2Cribs.FBInitialized = false;' . 
		'A2Cribs.VerifyManager.init('.$user.');' .
		$declare_marker_id_to_open . 
		$declare_sublet_data . 
		'A2Cribs.Map.Init(' . $school_id . ',' . $school_lat . ',' . $school_lng . ',"' . $school_city . '","' . $school_state . '","' . $school_name . '");
		$("#addressSearchBar").keyup(function(event){
    		if(event.keyCode == 13){
        		$("#addressSearchSubmit").click();
    		}
		});

		
	'

	);
?>
