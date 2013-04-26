<?= $this->Html->css('/less/post-sublet.less?','stylesheet/less', array('inline' => false)); ?>
<?= $this->Html->css('/less/checkbox.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('listing-popup-verifications'); ?>


<?= $this->Html->css('datepicker'); ?>
<?= $this->Html->script('bootstrap-datepicker'); ?>


<div class="listing-popup modal container-fluid">
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
							<input id="universityName" type="text" class="span9">
						</div>
						<div class="row-fluid">
							<label class="span5"><strong>Building Name:</strong></label>
							<input id="SubletName" type="text" class="span7">
						</div>
						<div class="row-fluid">
							<label class="span5"><strong>Street Address:</strong></label>
							<input id="formattedAddress" type="text" class="span7">
						</div>
						<div class="row-fluid">
							<label class="span2"><strong>Type:</strong></label>
							<select class="span5">
								<option>Apartment</option>
								<option>House</option>
								<option>Duplex</option>
							</select>
							<button class="btn btn-info btn-small span5 pull-right" onclick="A2Cribs.CorrectMarker.FindAddress()"><i class="icon-map-marker icon-large"></i> Place on Map</button>
						</div>
						<div class="row-fluid">
							<div class="span12" id="map-message">Please verify that the marker to the right is on the correct location. If not, please click and drag the marker to the correct spot on the map.</div>
							<img id="map-message-arrow" src="/img/messages/arrow-right.png">
						</div>
						<div class="row-fluid">
							<label class="span4"><strong>Unit Number:</strong></label>
							<input id="SubletUnitNumber" type="text" class="span4">
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
			<div class="container-fluid">
				<div class="row-fluid">
					<label class="span3"><strong>Available From:</strong></label>
					<div class="span6">
						<div class="row-fluid">
							<input id="SubletDateBegin" type="text" class="span5">
							<div class="span2" id="divider">-</div>
							<input id="SubletDateEnd" type="text" class="span5">							
						</div>
					</div>
					<div class="span3">
						<div class="row-fluid">
							<label class="span9"><strong>Flexible Dates:</strong></label>
							<input type="checkbox">
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span3">
						<div class="row-fluid">
							<label class="span8"><strong>Bedrooms:</strong></label>
							<select id="SubletNumberBedrooms" class="span4">
								<option value="">1</option>
								<option value="">2</option>
								<option value="">3</option>
							</select>
						</div>
					</div>
					<div class="span3">
						<div class="row-fluid">
							<label class="span6"><strong>Price</strong><small> /Bed</small><strong>:</strong></label>
							<div class="input-prepend span6">
								<span class="add-on span3">$</span>
								<input class="span9" id="SubletPricePerBedroom" type="text">
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="row-fluid">
							<select id="inputType" class="span4">
								<option value="">Monthly</option>
								<option value="">Total</option>
							</select>							
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span3">
						<strong>Unique Description:</strong>
						<p class="text-info"><small><strong>160 Character Max</strong></small></p>
						<p class="text-error"><small><strong><i id="desc-char-left">160</i> Characters Left</strong></small></p>
					</div>
					<textarea id="SubletDescription" class="span9" rows="3"></textarea>
				</div>
				<div class="row-fluid">
					<div class="span6"><strong>Additional Details </strong><strong class="text-info">(Optional)</strong></div>
				</div>
				<div class="row-fluid">
					<div class="span6">
						<div class="row-fluid">
							<label class="span4"><strong>Furnished:</strong></label>
							<select class="span4">
								<option value="">Partially</option>
								<option value="">Yes</option>
								<option value="">No</option>
							</select>
						</div>
					</div>
					<div class="span6">
						<div class="row-fluid">
							<label class="span4"><strong>Deposit:</strong></label>
							<div class="input-prepend span4 pull-left">
								<span class="add-on span1">$</span>
								<input class="span10" id="SubletDepositAmount" type="text">
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span6">
						<div class="row-fluid">
							<label class="span4"><strong>Parking:</strong></label>
							<select class="span3">
								<option value="">Yes</option>
								<option value="">No</option>
							</select>
						</div>
					</div>
					<div class="span6">
						<div class="row-fluid">
							<label class="span4"><strong>Utilities:</strong></label>
							<select id="SubletUtilityCost" class="span5">
								<option value="">Monthly Fee</option>
								<option value="">Included</option>
								<option value="">Not Included</option>
							</select>
							<div class="input-prepend span3 pull-right">
								<span class="add-on span1">$</span>
								<input class="span8" id="appendedPrependedInput" type="text">
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span6">
						<div class="row-fluid">
							<label class="span4"><strong>Bathroom:</strong></label>
							<select class="span4">
								<option value="">Public</option>
								<option value="">Private</option>
							</select>
						</div>
					</div>
					<div class="span6">
						<div class="row-fluid">
							<label class="span4"><strong>Other Fees:</strong></label>
							<input id="SubletAdditionalFeesDescription" type="text" class="span5">
							<div class="input-prepend span3 pull-right">
								<span class="add-on span1">$</span>
								<input class="span8" id="SubletAdditionalFeesAmount" type="text">
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span6">
						<div class="row-fluid">
							<label class="span4"><strong>A/C:</strong></label>
							<select class="span3">
								<option value="">Yes</option>
								<option value="">No</option>
							</select>
						</div>
					</div>
					<div class="span6">
						<div class="row-fluid">
							<label class="span4"></label>
							<input type="text" class="span5">
							<div class="input-prepend span3 pull-right">
								<span class="add-on span1">$</span>
								<input class="span8" id="appendedPrependedInput" type="text">
							</div>
						</div>
					</div>
				</div>
			</div>
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
							<select id="HousemateQuantity" class="span3">
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
					<input id="HousemateMajor" type="text" class="span10">
				</div>
				<div class="row-fluid">
					<strong>Longer Sublet Description </strong><strong class="text-info">(Optional)</strong>
				</div>
				<div class="row-fluid">
					<textarea class="span12" rows="5"></textarea>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-inverse pull-left back-btn">Back</button>
				<button class="btn btn-inverse pull-right next-btn">Next</button>
			</div>			
		</div>
		<div class="modal-body step" id="photo-step">
			<?php echo $this->Element('photo_manager');?>
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
		A2Cribs.SubletSave.SetupUI();

		$(".next-btn").click(function(){
			$(this).closest(".step").hide().next(".step").show();
		});
		$(".back-btn").click(function(){
			$(this).closest(".step").hide().prev(".step").show();
		});
		$("#SubletDescription").keyup(function(){
			if ($(this).val().length >= 160)
			{
				$(this).val($(this).val().substr(0, 160));
			}
			$("#desc-char-left").text(160 - $(this).val().length);
		});
		$("#SubletDateBegin").datepicker();

		$("#SubletDateEnd").datepicker();

		$("#universityName").focusout(function() {
			A2Cribs.CorrectMarker.FindSelectedUniversity();
		});

	');
?>
	
