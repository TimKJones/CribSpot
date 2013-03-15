<?php
	$this->set('title_for_layout', 'Cribspot Sublet');
	echo '<div id="container">';
		echo $this->element('header');
		echo $this->element('map');
		echo $this->element('sidebar');
		echo $this->element('login');
		//echo $this->element('legend');
		//echo $this->element('favorites');

		/* Hidden Elements */
		//echo $this->element('tooltips');
		echo $this->element('popups');
	echo '</div>';

	/* Create and initialize the map */
	$this->Js->buffer(	
		'A2Cribs.Map.Init();
		$("#addressSearchBar").keyup(function(event){
    		if(event.keyCode == 13){
        		$("#addressSearchSubmit").click();
    		}
		});'
	);
?>
