<div id="filterBoxBackground">
	<input id="addressSearchBar" type="text" size="29" placeholder=" Search for a place or address">
	<button id="addressSearchSubmit" onclick = 'A2Cribs.FilterManager.SearchForAddress()'>Search</button>
	<div id="filterBox">
		<form id="filterForm" method="get" action="index.php">
			<div class="filterRegion"> <!-- Start of rent section -->
				<div class="filterIcon" id="priceIcon" title="Rent"></div>
				<input type="text" id="rentMin" class="filterRangeInputMin filterRangeInput" readonly="readonly" value="$0"/>
				<div class="sliderDiv">
					<div id="rentSlider"></div>
				</div>
				<input type="text" id="rentMax" class="filterRangeInputMax filterRangeInput" readonly="readonly" value="$4000"/>
			</div> <!-- End of rent section -->
			<div class="filterRegion"> <!-- Start of bedrooms section -->
				<div class="filterIcon" id="bedIcon" title="Bedrooms"></div>
				<input type="text" id="bedsMin" class="filterRangeInputMin filterRangeInput" readonly="readonly" value="0"/>
				<div class="sliderDiv">
					<div id="bedSlider"></div>
				</div>
				<input type="text" id="bedsMax" class="filterRangeInputMax filterRangeInput" readonly="readonly" value="10"/>
			</div> <!-- End of bedroom section -->
			<div class="filterRegion"> <!-- Start of Lease Period section -->
				<div class="filterIcon" id="leaseIcon" title="Lease Period"></div>
				<div class="filterOptions" id="rentTermFilter">
				<input type="checkbox" class="filterCheck" id="fallCheck" checked="checked" onclick="A2Cribs.FilterManager.ApplyFilter()"/>
				<div class="filterOptionsText">Fall-Fall</div>
				<input type="checkbox" class="filterCheck" id="springCheck" checked="checked" onclick="A2Cribs.FilterManager.ApplyFilter()"/>
				<div class="filterOptionsText">Spring-Spring</div>
				<input type="checkbox" class="filterCheck" id="otherCheck" checked="checked" onclick="A2Cribs.FilterManager.ApplyFilter()"/>
				<div class="filterOptionsText">Other </div>
				</div>
			</div> <!-- End of Lease Period section -->
			<div class="filterRegion"> <!-- Start of Unit Type section -->
				<div class="filterIcon" id="houseIcon" title="Unit Type"></div>
				<div class="filterOptions" id="houseTypeFilter">
					<input type="checkbox" class="filterCheck" id="houseCheck" checked="checked" onclick="A2Cribs.FilterManager.ApplyFilter()"/>
					<div class="filterOptionsText">House</div>
					<input type="checkbox" class="filterCheck" id="aptCheck" checked="checked" onclick="A2Cribs.FilterManager.ApplyFilter()"/>
					<div class="filterOptionsText">Apartment</div>
					<input type="checkbox" class="filterCheck" id="duplexCheck" checked="checked" onclick="A2Cribs.FilterManager.ApplyFilter()"/>
					<div class="filterOptionsText">Duplex</div>
				</div>
			</div> <!-- End of Unit Type section -->
		</form>
	</div>
</div> <!-- end of filterBoxBackground -->

<?php
	$this->Js->get('#rentSlider');
	$this->Js->slider(array(
		'range' => true,
		'slide' => 'function(event, ui) { $( "#rentMin" ).val( "$" + ui.values[ 0 ] ); $( "#rentMax" ).val( "$" + ui.values[ 1 ] ); }',
		'change' => 'A2Cribs.FilterManager.ApplyFilter',
		'min' => 0,
		'max' => 4000,
		'step' => 100,
		'values' => array(0, 4000),
		'direction' => 'horizontal',
		'wrapCallbacks' => false
	));

	$this->Js->get('#bedSlider');
	$this->Js->slider(array(
		'range' => true,
		'slide' => 'function(event, ui) { $( "#bedsMin" ).val( ui.values[ 0 ] ); $( "#bedsMax" ).val( ui.values[ 1 ] ); }',
		'change' => 'A2Cribs.FilterManager.ApplyFilter',
		'min' => 0,
		'max' => 10,
		'values' => array(0, 10),
		'direction' => 'horizontal',
		'wrapCallbacks' => false
	));
?>