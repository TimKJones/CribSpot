<?php echo $this->Html->css('/less/Filter/filter.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('src/FilterManager', array('inline' => false)); ?>
<?php echo $this->Html->script('src/RentalFilter', array('inline' => false)); ?>

<?php echo $this->Html->css('/less/slider.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('bootstrap-slider', array('inline' => false)); ?>

<div id="map_filter">
	<div id="filter_label_group">
		<div class="btn-group filter_div">
			<a class="btn listing_type" href="#">Rentals</a>
			<a class="btn disabled filter_label" href="#">Searching for:</a>
			<?php
				$filters = array('Bedrooms', 'Budget', 'Starts In', 'Length', 'Type', 'More');
				$filter_links = array('bed', 'rent', 'start', 'lease', 'building', 'more');
				$length = count($filters);
				for ($i = 0; $i < $length; $i++) {
					echo '<a id="' . $filter_links[$i] . '_filter_link" class="btn filter_link" data-filter="#' . $filter_links[$i] . '_filter_content" href="#">
						<div class="filter_title">' . $filters[$i] . '</div><div class="filter_preview hide"></div>
					</a>';
				}
			?>
		</div>
	</div>
	<div id="filter_dropdown">
		<div id="bed_filter_content" class="filter_content hide" data-link="#bed_filter_link"> <!-- Beds -->
			<div class="content_label">Bedrooms:</div>
			<div id="bed_filter" class="btn-group">
				<button value="0" type="button" class="btn">Studio</button>
				<?php
				for ($i=1; $i < 10; $i++) { 
					echo '<button type="button" class="btn" value="' . $i . '">' . $i . '</button>';
				}
				?>
				<button type="button" class="btn" value="10">10+</button>
			</div>
		</div>
		<div id="building_filter_content" class="filter_content hide" data-link="#building_filter_link"> <!-- Building Type -->
			<?php
				$building_types = array('House', 'Apartment', 'Duplex', 'Other');
				$length = count($building_types);
				for ($i=0; $i < $length; $i++) { 
					echo '
						<label class="checkbox">
							<input data-value="' . $i . '" data-filter="UnitTypes" type="checkbox"> ' . $building_types[$i] . '
						</label>';
				}
			?>
		</div>
		<div id="start_filter_content" class="filter_content hide" data-link="#start_filter_link"> <!-- Start Date -->
			<div class="content_label">Start In:</div>
			<div id="start_filter" class="btn-group">
				<?php
				$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
				$length = count($months);
				for ($i=0; $i < $length; $i++) { 
					echo '<button type="button" class="btn" data-month="' . ($i + 1) . '">' . $months[$i] . '</button>';
				}
				?>
			</div>
			<select id="year_filter">
				<?php
					$currentYear = intval(date("Y"));
					for ($i=0; $i < 3; $i++) { 
						echo "<option value='" . substr($currentYear, -2) . "'>" . $currentYear++ . "</option>";
					}
				?>
			</select>
		</div>
		<div id="more_filter_content" class="filter_content hide" data-link="#more_filter_link"> <!-- More Info -->
			<?php
				$building_types = array('Pets Allowed', 'Parking Available', 'A/C In-Unit', 'Utilities Included');
				$building_filters = array('PetsAllowed', 'ParkingAvailable', 'Air', 'UtilitiesIncluded');
				$length = count($building_types);
				for ($i=0; $i < $length; $i++) {
					echo '
						<label class="checkbox">
							<input data-value="0" data-filter="' . $building_filters[$i] . '" type="checkbox"> ' . $building_types[$i] . '
						</label>';
				}
			?>
		</div>
		<div id="lease_filter_content" class="filter_content hide" data-link="#lease_filter_link"> <!-- Lease Length -->
			<div class="content_label">Lease:</div>
			<div id="lease_min" class="slider_data filter_data">0</div><div id="lease_min_desc" class="slider_data filter_label">&nbsp;months</div>
			<div class="lease_slider slider"></div>
			<div id="lease_max" class="slider_data filter_data">12</div><div id="lease_max_desc" class="slider_data filter_label">&nbsp;months</div>
		</div>
		<div id="rent_filter_content" class="filter_content hide" data-link="#rent_filter_link"> <!-- Rent Amount -->
			<div class="content_label">Monthly Rent:</div>
			<div id="rent_min" class="slider_data filter_data">$0</div>
			<div class="rent_slider slider"></div>
			<div id="rent_max" class="slider_data filter_data">$5,000+</div>
		</div>

	</div>
</div>

<div id="filter_search_container">
	<div id="filter_search_btn"><i class="icon-search icon-large"></i></div>
	<input id="filter_search_content" type="text" placeholder="Search By Address">
</div>
<?php 
	$this->Js->buffer('
		A2Cribs.RentalFilter.SetupUI();
	');
?>
