<?= $this->Html->css('/less/Dashboard/sublets_window.less?','stylesheet/less', array('inline' => false)); ?>

<div id="sublet_window" class="row-fluid">

	<!-- SUBLET SECTION -->
	<div class="span8 sublet_section">

		<input type="hidden" class="marker_id">
		<input type="hidden" class="listing_id">
		<input type="hidden" class="sublet_fields" data-field-name="sublet_id">

		<!-- PHOTO SECTION -->
		<div class="row-fluid text-center">
			<button class="btn btn-large photo_adder" type="button"><i class="icon-plus-sign"></i> Add Some Photos</button>
		</div>

		<div class="row-fluid sublet_row">
			<!-- MINI MAP -->
			<div class="mini_map pull-left">
				<div id="correctLocationMap"></div>
			</div>

			<!-- MARKER INFO -->
			<div class="marker_info">

				<!-- SEARCH FOR MARKER -->
				<div class="marker_searchbox">
					<div><strong>Address:</strong></div>
					<div class="row-fluid">
						<input class="span12 location_fields" type="text" placeholder="Street Address" data-field-name="street_address">
					</div>
					<div class="row-fluid">
						<input class="span8 location_fields" type="text" placeholder="City" data-field-name="city">
						<?php 
							$states = array("AL","AK","AZ","AR","CA","CO","CT","DE","DC","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY");
							echo "<select class='span4 location_fields' data-field-name='state'><option>State</option>";
							foreach ($states as $state)
							{
								echo "<option value='" . $state . "'>" . $state . "</option>";
							}
							echo "</select>";
						?>
					</div>
				</div>
				<!-- DISPLAY MARKER -->
				<div class="marker_card hide">
					<div class='building_name'>722 Packard Street</div>
					<div class="building_type">House</div>
					<div class="full_address"><em><i class="icon-map-marker"></i> 722 Packard Ann Arbor, MI</em></div>
				</div>
			</div>
			<button id="find_address" class="btn btn-primary pull-right">Place on map</button>
		</div>

			<!-- RENT -->
			<!--
			<div class="span6">
				<div>Rent:</div>
				<input type="text" class="sublet_fields" data-field-name="rent">
			</div> -->

		<div class="more_info hide">
			<div class="row-fluid sublet_row">
				<!-- BEDS -->
				<div class="span4">
					<div><strong>Beds:</strong></div>
					<div class="btn-group sublet_fields" data-toggle="buttons-radio" data-field-name="beds">
						<button type="button" class="btn" value="0">Studio</button>
						<button type="button" class="btn" value="1">1</button>
						<button type="button" class="btn" value="2">2</button>
						<button type="button" class="btn" value="3">3</button>
						<button type="button" class="btn" value="4">4</button>
					</div>
				</div>

				<!-- BATHS -->
				<div class="span8">
					<div><strong>Baths:</strong></div>
					<div class="btn-group pull-left sublet_fields" data-toggle="buttons-radio" data-field-name="baths">
						<button type="button" class="btn" value="0.5">&frac12;</button>
						<button type="button" class="btn" value="1">1</button>
						<button type="button" class="btn" value="1.5">1&frac12;</button>
						<button type="button" class="btn" value="2">2</button>
						<button type="button" class="btn" value="2.5">2&frac12;</button>
						<button type="button" class="btn" value="3">3</button>
					</div>
					<!-- PRIVATE/SHARED BATHROOM -->
					<div class="btn-group sublet_fields" data-toggle="buttons-radio" data-field-name="bathroom_type">
						<button type="button" class="btn" value="1">Shared</button>
						<button type="button" class="btn" value="0">Private</button>
					</div>
				</div>
			</div>

			<div class="row-fluid sublet_row">

				<!-- START DATE -->
				<div class="span4">
					<div><strong>Start Date:</strong></div>
					<input type="text" class="sublet_fields date-field" data-field-name="start_date">
				</div>

				<!-- END DATE -->
				<div class="span4">
					<div><strong>End Date:</strong></div>
					<input type="text" class="sublet_fields date-field" data-field-name="end_date">
				</div>

				<!-- AVAILABLE NOW -->
				<div class="span4">
					<div><strong>Available Now?</strong></div>
					<div class="btn-group sublet_fields" data-toggle="buttons-radio" data-field-name="available_now">
						<button type="button" class="btn" value="1">Yes</button>
						<button type="button" class="btn" value="0">No</button>
					</div>
				</div>
			</div>

			<div class="row-fluid sublet_row">

				<!-- AIR CONDITIONING -->
				<div class="span4">
					<div><strong>A/C:</strong></div>
					<div class="btn-group sublet_fields" data-toggle="buttons-radio" data-field-name="air">
						<button type="button" class="btn" value="1">Yes</button>
						<button type="button" class="btn" value="0">No</button>
					</div>
				</div>

				<!-- UTILITIES INCLUDED -->
				<div class="span4">
					<div><strong>Utilities Included?</strong></div>
					<div class="btn-group sublet_fields" data-toggle="buttons-radio" data-field-name="utilities_included">
						<button type="button" class="btn" value="1">Yes</button>
						<button type="button" class="btn" value="0">No</button>
					</div>
					<p type="text" class="triangle-isosceles after">
						<input type="text" class="sublet_fields" placeholder="Utilities Desc. (Optional)" data-field-name="utilities_description">
					</p>
				</div>

				<!-- PARKING -->
				<div class="span4">
					<div><strong>Parking:</strong></div>
					<div class="btn-group sublet_fields" data-toggle="buttons-radio" data-field-name="parking_available">
						<button type="button" class="btn" value="1">Yes</button>
						<button type="button" class="btn" value="0">No</button>
					</div>
					<p type="text" class="triangle-isosceles after">
						<input type="text" class="sublet_fields" placeholder="Parking Desc. (Optional)" data-field-name="parking_description">
					</p>
				</div>

			</div>

			<div class="row-fluid sublet_row">

				<!-- WASHER/DRYER -->
				<div class="span6">
					<div><strong>Washer/Dryer:</strong></div>
					<div class="btn-group sublet_fields" data-toggle="buttons-radio" data-field-name="washer">
						<button type="button" class="btn" value="0">None</button>
						<button type="button" class="btn" value="1">In-Unit</button>
						<button type="button" class="btn" value="2">On-Site</button>
						<button type="button" class="btn" value="3">Off-Site</button>
					</div>
				</div>

				<!-- FURNITURE TYPE -->
				<div class="span6">
					<div><strong>Furnished?</strong></div>
					<div class="btn-group sublet_fields" data-toggle="buttons-radio" data-field-name="furnished">
						<button type="button" class="btn" value="2">Fully</button>
						<button type="button" class="btn" value="1">Partially</button>
						<button type="button" class="btn" value="0">No</button>
					</div>
				</div>

			</div>

			<div class="row-fluid sublet_row">
				<div><strong>Description:</strong></div>
				<textarea class="span12 sublet_fields" rows="3" data-field-name="description" placeholder="Insert a description here"></textarea>
			</div>

			<div class="row-fluid">
				<button id="sublet_save_button" type="button" class="btn btn-primary">Save</button>
			</div>

		</div>
	</div>

	<!-- SUBLET AWESOMENESS SECTION -->
	<div class="span4 sublet_section">
		<h3>Sublet Awesomeness</h3>
		<button type="button" class="btn">SAVE</button>
	</div>



</div>