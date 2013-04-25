<?= $this->Html->css('/less/post-sublet.less?','stylesheet/less', array('inline' => false)); ?>
<?= $this->Html->css('/less/checkbox.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('listing-popup-verifications'); ?>

<div class="listing-popup modal">
	<div id="sublet-id" class="hide"></div>
	<div class="modal-header">
		<i class="sublet-name title">Post Your Sublet</i>
		<div id="modal-close-button" class="close" data-dismiss="modal"></div>
	</div>
	<div>
		<div class="modal-body step" id="address-step">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span7">
						<div class="row-fluid">
							<label class="span3"><strong>College:</strong></label>
							<input type="text" class="span9">
						</div>
						<div class="row-fluid">
							<label class="span5"><strong>Building Name:</strong></label>
							<input type="text" class="span7">
						</div>
						<div class="row-fluid">
							<label class="span5"><strong>Street Address:</strong></label>
							<input type="text" class="span7">
						</div>
						<div class="row-fluid">
							<label class="span2"><strong>Type:</strong></label>
							<select class="span5">
								<option>Apartment</option>
								<option>House</option>
								<option>Duplex</option>
							</select>
							<button class="btn btn-info btn-small span5 pull-right"><i class="icon-map-marker icon-large"></i> Place on Map</button>
						</div>
						<div class="row-fluid">
							<div class="span12" id="map-message">Please verify that the marker to the right is on the correct location. If not, please click and drag the marker to the correct spot on the map.</div>
							<img id="map-message-arrow" src="/img/messages/arrow-right.png">
						</div>
						<div class="row-fluid">
							<label class="span4"><strong>Unit Number:</strong></label>
							<input type="text" class="span4">
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
			</div>
			<div class="modal-footer">
				<button class="btn btn-inverse pull-right next-btn">Next</button>
			</div>
		</div>
		<div class="modal-body step" id="info-step">
			<form class="form-inline" id="top-form">
				<label><strong>Available From:</strong></label>
				<input type="text" class="input-small" placeholder="Email">
				<input type="password" class="input-small" placeholder="Password">
				<input type="checkbox" id="inputFlexDates"><label for="inputFlexDates"><strong>Flexible Dates:</strong></label>
				<label class="control-label" for="inputType"><strong>Bedrooms:</strong></label>
				<select id="inputType" class="input-mini">
					<option value="">1</option>
					<option value="">2</option>
					<option value="">3</option>
				</select>
				<label><strong>Price </strong><small><b>/Bed</b></small><strong>:</strong></label>
				<div class="input-prepend">
					<span class="add-on">$</span>
					<input class="input-mini" id="appendedPrependedInput" type="text">
				</div>
				<select class="input-small">
					<option>Monthly</option>
				</select>
				<label><strong>Unique Description:</strong></label>
				<textarea rows="3"></textarea>
			</form>
			<form class="form-inline" id="bottom-form">
				<h4>Additional Details (Optional)</h4>
				<label><strong>Deposit:</strong></label>
				<div class="input-prepend">
					<span class="add-on">$</span>
					<input class="input-mini" id="appendedPrependedInput" type="text">
				</div>
				<label class="control-label" for="inputType"><strong>Furnished:</strong></label>
				<select id="inputType" class="input-mini">
					<option value="">No</option>
					<option value="">Paritally</option>
					<option value="">Fully</option>
				</select>
				<label><strong>Utilities:</strong></label>
				<div class="input-prepend">
					<span class="add-on">$</span>
					<input class="input-mini" id="appendedPrependedInput" type="text">
				</div>
				<select class="input-small">
					<option>Included</option>
					<option>Monthly Fee</option>
				</select>
				<label><strong>Parking:</strong></label>
				<select class="input-small">
					<option>Available</option>
					<option>Monthly Fee</option>
				</select>
				<label><strong>Bathroom:</strong></label>
				<select class="input-small">
					<option>Included</option>
					<option>Monthly Fee</option>
				</select>
				<label><strong>A/C:</strong></label>
				<select class="input-small">
					<option>Yes</option>
					<option>No</option>
				</select>
				<label><strong>Other Fees:</strong></label>
				<input type="text" class="input-small" placeholder="Extra Key Cost">
				<div class="input-prepend">
					<span class="add-on">$</span>
					<input class="input-mini" id="appendedPrependedInput" type="text">
				</div>			
			</form>
			<div class="modal-footer">
				<button class="btn btn-inverse pull-left back-btn">Back</button>
				<button class="btn btn-inverse pull-right next-btn">Next</button>
			</div>
		</div>
		<div class="modal-body step" id="addinfo-step">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<strong>Housemate Information</strong>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span6">
						<div class="row-fluid">
							<label class="span8"><strong>Estimated Housemates:</strong></label>
							<select class="span3">
								<option>0</option>
								<option>1</option>
								<option>2</option>
								<option>3</option>
								<option>4</option>
								<option>5+</option>
							</select>
						</div>
					</div>
					<div class="span6">
						<div class="row-fluid">
							<label class="span7"><strong>Are They Students?:</strong></label>
							<select class="span4">
								<option>Yes</option>
								<option>No</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span4">
						<div class="row-fluid">
							<label class="span4"><strong>Type:</strong></label>
							<select class="span8">
								<option>Undergrad</option>
								<option>Graduate</option>
							</select>
						</div>
					</div>
					<div class="span4">
						<div class="row-fluid">
							<label class="span4"><strong>Year:</strong></label>
							<select class="span8">
								<option>Freshman</option>
								<option>Sophomore</option>
								<option>Junior</option>
								<option>Senior</option>
							</select>
						</div>
					</div>
					<div class="span4">
						<div class="row-fluid">
							<label class="span5"><strong>Gender:</strong></label>
							<select class="span6">
								<option>Male</option>
								<option>Female</option>
								<option>Mixed</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<label class="span2"><strong>Majors:</strong></label>
					<input type="text" class="span10">
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-inverse pull-left back-btn">Back</button>
				<button class="btn btn-inverse pull-right next-btn">Next</button>
			</div>			
		</div>
		<div class="modal-body step" id="photo-step">
			Photo Step
			<div class="modal-footer">
				<button class="btn btn-inverse pull-left back-btn">Back</button>
				<button class="btn btn-warning pull-right post-btn">Post</button>
			</div>			
		</div>
		<div class="modal-body step" id="share-step">
			Share Step
		</div>
	</div>
</div>


<?php 
	$this->Js->buffer('
		$("#addinfo-step").siblings().hide();
		$(".next-btn").click(function(){
			$(this).closest(".step").hide().next(".step").show();
		});
		$(".back-btn").click(function(){
			$(this).closest(".step").hide().prev(".step").show();
		});
	');
?>
