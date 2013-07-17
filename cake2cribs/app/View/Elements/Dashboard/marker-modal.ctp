<?= $this->Html->css('/less/Dashboard/marker-modal.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('src/MiniMap.js', array('inline' => false)); ?>

<div id ="marker-modal" class="modal hide fade">
	<div class="modal-header">
		<i class="sublet-name title">Create a New Listing</i>
		<div id="modal-close-button" class="close" data-dismiss="modal"></div>
	</div>
	<div>
		<div class="modal-body step">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span6">
						<h5>Choose a Location</h5>
					</div>
					<div class="span6">
						<select id="marker_select">
							<option value="0">--</option>
							<option value="new_marker"><strong>New Location</strong></option>
						</select>
					</div>
				</div>
				<div class="row-fluid" id="marker_add">
					<div class="span7">
						<div class="row-fluid control-group">
							<label class="span3"><strong>College:</strong></label>
							<input id="University_name" type="text" class="span9 required" required>
							<input type="hidden" id="Sublet_university_id">
						</div>
						<div class="row-fluid control-group">
							<label class="span5"><strong>Building Name:</strong></label>
							<input id="Marker_alternate_name" type="text" class="span7">
						</div>
						<div class="row-fluid control-group">
							<label class="span5"><strong>Street Address:</strong></label>
							<input id="Marker_street_address" type="text" class="span7 required" required>
							<input type="hidden" id="Marker_marker_id">
							<input type="hidden" id="Marker_city">
							<input type="hidden" id="Marker_state">
							<input type="hidden" id="Marker_zip">
							<input type="hidden" id="Marker_latitude">
							<input type="hidden" id="Marker_longitude">
						</div>
						<div class="row-fluid control-group">
							<label class="span2"><strong>Type:</strong></label>
							<select id="Marker_building_type_id" class="span5 required" required>
								<option></option>
								<option value="1">House</option>
								<option value="2">Apartment</option>
								<option value="3">Duplex</option>
							</select>
							<button id="place_map_button" class="btn btn-info btn-small span5 pull-right"><i class="icon-map-marker icon-large"></i> Place on Map</button>
						</div>
						<div class="row-fluid">
							<div class="span12" id="map-message">Please verify that the marker to the right is on the correct location. If not, click and drag the marker to the correct spot on the map.</div>
							<img id="map-message-arrow" src="/img/messages/arrow-right.png">
						</div>
					</div>
					<div class="span5">
						<div class="row-fluid">
							<div id="map-background" class="span12">
								<div id="correctLocationMap"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<button id="continue-button" class="btn btn-primary pull-right">Continue</button>
				</div>
			</div>
		</div>
	</div>
</div>
