<?php
	$this->set('title_for_layout', 'Cribspot Sublet');
	echo '<div id="container">';
		echo $this->element('header');
		echo $this->element('map');
		echo $this->element('login');
		echo $this->element('register');
		//echo $this->element('legend');
		//echo $this->element('favorites');

		/* Hidden Elements */
		echo $this->element('tooltips');
		echo $this->element('popups');
	echo '</div>';
	$declare_marker_id_to_open =  'var marker_id_to_open = ' . $marker_id_to_open . ';';
	$declare_sublet_data =  'var sublet_data = ' . json_encode($sublet_data_for_tooltip) . ';';
	/* Create and initialize the map */
	$this->Js->buffer(	
		$declare_marker_id_to_open . 
		$declare_sublet_data . 
		'A2Cribs.Map.Init(' . $school_id . ',' . $school_lat . ',' . $school_lng . ',"' . $school_city . '","' . $school_state . '");
		$("#addressSearchBar").keyup(function(event){
    		if(event.keyCode == 13){
        		$("#addressSearchSubmit").click();
    		}
		});
		

		A2Cribs.Map.OpenMarker(marker_id_to_open, sublet_data);
	'

	);
?>
