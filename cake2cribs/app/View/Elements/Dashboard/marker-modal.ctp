<?= $this->Html->css('/less/Dashboard/marker-modal.less?','stylesheet/less', array('inline' => false)); ?>
<?php 
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/MiniMap.js', array('inline' => false));
	echo $this->Html->script('src/MarkerModal.js', array('inline' => false));
}
?>

<div id ="marker-modal" class="modal hide fade">
	<div class="modal-header">
		<i class="sublet-name title">Create a New Listing</i>
		<div id="modal-close-button" class="close" data-dismiss="modal"></div>
	</div>
	<div>
		<div class="modal-body step">
			<div class="container-fluid">
				<div class="row-fluid"  id="marker_select_container">
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
							<label class="span5"><strong>Building Name:</strong></label>
							<input id="Marker_alternate_name" type="text" class="span7">
						</div>
						<div class="row-fluid control-group">
							<label class="span3"><strong>Type:</strong></label>
							<select id="Marker_building_type_id" class="span9 required" required>
								<option></option>
								<option value="1">House</option>
								<option value="2">Apartment</option>
								<option value="3">Duplex</option>
							</select>
						</div>
						<div class="row-fluid control-group">
							<label class="span5"><strong>Street Address:</strong></label>
							<input id="Marker_street_address" type="text" class="span7 required" required>
							<input type="hidden" id="Marker_marker_id">
							<input type="hidden" id="Marker_zip">
							<input type="hidden" id="Marker_latitude">
							<input type="hidden" id="Marker_longitude">
						</div>
						<div class="row-fluid control-group">
							<label class="span3"><strong>City:</strong></label>
							<input id="Marker_city" type="text" class="span9 required" required>
						</div>
						<div class="row-fluid control-group">
							<label class="span2"><strong>State:</strong></label>
							<select id="Marker_state" class="span5 required" required>
								<option></option>
								<option value="AL">AL</option>
								<option value="AK">AK</option>
								<option value="AZ">AZ</option>
								<option value="AR">AR</option>
								<option value="CA">CA</option>
								<option value="CO">CO</option>
								<option value="CT">CT</option>
								<option value="DE">DE</option>
								<option value="DC">DC</option>
								<option value="FL">FL</option>
								<option value="GA">GA</option>
								<option value="HI">HI</option>
								<option value="ID">ID</option>
								<option value="IL">IL</option>
								<option value="IN">IN</option>
								<option value="IA">IA</option>
								<option value="KS">KS</option>
								<option value="KY">KY</option>
								<option value="LA">LA</option>
								<option value="ME">ME</option>
								<option value="MD">MD</option>
								<option value="MA">MA</option>
								<option value="MI">MI</option>
								<option value="MN">MN</option>
								<option value="MS">MS</option>
								<option value="MO">MO</option>
								<option value="MT">MT</option>
								<option value="NE">NE</option>
								<option value="NV">NV</option>
								<option value="NH">NH</option>
								<option value="NJ">NJ</option>
								<option value="NM">NM</option>
								<option value="NY">NY</option>
								<option value="NC">NC</option>
								<option value="ND">ND</option>
								<option value="OH">OH</option>
								<option value="OK">OK</option>
								<option value="OR">OR</option>
								<option value="PA">PA</option>
								<option value="RI">RI</option>
								<option value="SC">SC</option>
								<option value="SD">SD</option>
								<option value="TN">TN</option>
								<option value="TX">TX</option>
								<option value="UT">UT</option>
								<option value="VT">VT</option>
								<option value="VA">VA</option>
								<option value="WA">WA</option>
								<option value="WV">WV</option>
								<option value="WI">WI</option>
								<option value="WY">WY</option>
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
<?php 
	$this->Js->buffer('
		A2Cribs.MarkerModal = new A2Cribs.MarkerModal();
	');
?>