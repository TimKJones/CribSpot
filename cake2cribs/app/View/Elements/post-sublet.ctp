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
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label" for="inputSchool"><strong>College:</strong></label>
					<div class="controls">
						<input type="text" id="inputSchool" class="span2" placeholder="lol">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="inputName"><strong>Building Name:</strong></label>
					<div class="controls">
						<input type="text" id="inputName" class="span2" placeholder="lol">
					</div>
				</div>
				<div class="control-group">					
					<label class="control-label" for="inputAddress"><strong>Street Address:</strong></label>
					<div class="controls">
						<input type="text" id="inputAddress" class="span2" placeholder="lol">
						<button class="btn btn-primary" id="placeOnMap"><i class="icon-map-marker"></i> Place on Map</button>
					</div>

				</div>
				<div class="control-group">
					<label class="control-label" for="inputType"><strong>Type:</strong></label>
					<div class="controls">
						<select id="inputType" class="span2">
							<option value="">House</option>
							<option value="">Apartment</option>
							<option value="">Duplex</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="inputUnit"><strong>Unit Number:</strong></label>
					<div class="controls">
						<input type="text" id="inputUnit" class="span1" placeholder="lol">
					</div>
				</div>
			</form>
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
			Additional Info Step
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
		$("#address-step").siblings().hide();
		$(".next-btn").click(function(){
			$(this).closest(".step").hide().next(".step").show();
		});
		$(".back-btn").click(function(){
			$(this).closest(".step").hide().prev(".step").show();
		});
	');
?>
